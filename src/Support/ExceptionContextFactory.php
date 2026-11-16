<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Support;
use Nosun\ExceptionCenter\Data\ExceptionContext;
final class ExceptionContextFactory
{
    public function make(?array $overrides = null): ExceptionContext
    {
        $config=\function_exists('config') ? (array) config('exception-center', []) : [];
        $console=\function_exists('app') && app()->runningInConsole();
        $url=null; $operation=null; $requestId=null;
        if (!$console && \function_exists('request') && request()->hasSession() !== null) {
            try { $url=request()->fullUrl(); $operation=request()->route()?->getName() ?: request()->method(); $requestId=request()->header('X-Request-Id') ?: request()->header('X-Request-ID'); } catch (\Throwable) {}
        }
        $data=array_merge(['project_code'=>$config['project'] ?? getenv('EXCEPTION_CENTER_PROJECT') ?: '', 'repository_code'=>$config['repository'] ?? getenv('EXCEPTION_CENTER_REPOSITORY') ?: '', 'instance_code'=>$config['instance'] ?? getenv('EXCEPTION_CENTER_INSTANCE') ?: '', 'environment'=>$config['environment'] ?? getenv('APP_ENV') ?: 'production', 'service'=>$console ? 'console' : 'http', 'operation'=>$operation, 'request_id'=>$requestId, 'source_version'=>getenv('EXCEPTION_CENTER_SOURCE_VERSION') ?: null, 'source_host'=>gethostname() ?: null, 'source_url'=>$url], $overrides ?? []);
        return ExceptionContext::fromArray($data);
    }
}
