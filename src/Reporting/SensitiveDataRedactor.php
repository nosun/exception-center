<?php
declare(strict_types=1);
namespace Nosun\ExceptionCenter\Reporting;
final class SensitiveDataRedactor
{
    private const BLOCKED = ['password','password_confirmation','token','access_token','refresh_token','secret','api_key','authorization','cookie','set-cookie','private_key','aws_secret_access_key','webhook'];
    public function __construct(private readonly int $maxStringLength = 4096, private readonly int $maxDepth = 8) {}
    public function redact(mixed $value, int $depth = 0): mixed
    {
        if ($depth >= $this->maxDepth) return '[truncated]';
        if (is_string($value)) return mb_substr($value, 0, $this->maxStringLength);
        if (!is_array($value)) return is_scalar($value) || $value === null ? $value : '[redacted]';
        $result=[]; foreach ($value as $key=>$item) { $normalized=strtolower((string)$key); $result[$key]=in_array($normalized,self::BLOCKED,true) ? '[redacted]' : $this->redact($item,$depth+1); } return $result;
    }
    public function redactUrl(?string $url): ?string { if ($url===null) return null; $parts=parse_url($url); if ($parts===false) return mb_substr($url,0,$this->maxStringLength); $query=[]; parse_str($parts['query']??'', $query); $safe=$this->redact($query); $base=($parts['scheme']??'').'://'.($parts['host']??'').($parts['path']??''); return $base.($safe ? '?'.http_build_query($safe) : ''); }
}
