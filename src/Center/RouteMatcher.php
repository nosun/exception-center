<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Center;
use Nosun\ExceptionCenter\Data\ExceptionEvent;
final class RouteMatcher
{
    public function __construct(private readonly array $routes) {}
    /** @return array<string,mixed> */
    public function match(ExceptionEvent $event): array
    {
        $candidates=[]; foreach ($this->routes as $route) { if (($route['enabled']??true)===false) continue; $score=0; foreach (['project_code','repository_code','instance_code','environment','category'] as $field) { if (isset($route[$field])) {  $actual = $field === 'category' ? $event->category : $event->context->{$this->contextProperty($field)}; if ($route[$field] !== $actual) continue 2; $score++; } } if (isset($route['exception_type_pattern']) && !fnmatch($route['exception_type_pattern'],$event->exceptionType)) continue; $score+=isset($route['exception_type_pattern'])?2:0; $route['_score']=$score; $candidates[]=$route; }
        usort($candidates,fn($a,$b)=>$b['_score']<=>$a['_score']); return $candidates[0]??['agent_code'=>'human','status'=>'needs_human','workspace'=>null];
    }
    private function contextProperty(string $field): string { return match($field) { 'project_code'=>'projectCode','repository_code'=>'repositoryCode','instance_code'=>'instanceCode','environment'=>'environment', default=>$field }; }
}
