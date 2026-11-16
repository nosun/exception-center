<?php
declare(strict_types=1);
require __DIR__.'/../vendor/autoload.php';
use Nosun\ExceptionCenter\{ExceptionReporter};
use Nosun\ExceptionCenter\Data\{ExceptionContext,ExceptionEvent};
use Nosun\ExceptionCenter\Reporting\{SensitiveDataRedactor,FingerprintGenerator};
use Nosun\ExceptionCenter\Stores\InMemoryExceptionStore;

$checks=0;
function check(bool $condition,string $message): void { global $checks; $checks++; if (!$condition) throw new RuntimeException("FAIL: $message"); }
$redactor=new SensitiveDataRedactor();
$safe=$redactor->redact(['password'=>'secret','nested'=>['Authorization'=>'Bearer x','order_id'=>12345],'message'=>'ok']);
check($safe['password']==='[redacted]' && $safe['nested']['Authorization']==='[redacted]' && $safe['nested']['order_id']===12345,'sensitive values are redacted');
$context=new ExceptionContext('tradeExpress','tradeExpress','drw','testing','queue','SyncOrders');
$store=new InMemoryExceptionStore(); $reporter=new ExceptionReporter($store);
$id=$reporter->report(new RuntimeException('failed order 12345'),$context);
check(is_string($id) && count($store->all())===1,'Throwable is captured');
$manual=ExceptionEvent::manual('integration.yuntu.invalid_province','P2','invalid province 12345',source:$context);
check($reporter->capture($manual)===$manual->eventId,'manual event is captured');
$fp=new FingerprintGenerator();
$a=$fp->generate($store->all()[0]);
$second=$reporter->report(new RuntimeException('failed order 98765'),$context);
check($a===$fp->generate($store->all()[2]),'dynamic numbers do not change fingerprint');
$failing=new ExceptionReporter(new class implements \Nosun\ExceptionCenter\Contracts\ExceptionStore { public function insert(ExceptionEvent $event): void { throw new RuntimeException('db down'); } });
check($failing->capture($manual)===null,'storage failure is fail-open');
$aggregator=new \Nosun\ExceptionCenter\Center\GroupAggregator();
$group1=$aggregator->add($store->all()[0]); $group2=$aggregator->add($store->all()[2]);
check($group2['occurrence_count']===2,'same fingerprint is aggregated');
$route=(new \Nosun\ExceptionCenter\Center\RouteMatcher([['project_code'=>'tradeExpress','instance_code'=>'drw','exception_type_pattern'=>'RuntimeException','agent_code'=>'express-agent']]))->match($store->all()[0]);
check($route['agent_code']==='express-agent','specific route wins');
echo "{$checks} checks passed\n";
