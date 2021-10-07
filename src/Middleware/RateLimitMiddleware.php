<?php

declare(strict_types=1);
/**
 * This file is part of usaas.
 *
 * @link     https://www.uupt.com
 * @document https://www.uupt.com
 * @contact maozihao@uupaotui.com
 * @license  https://github.com/uu-paotui/usaas/blob/main/LICENSE
 */
namespace UU\Admin\Middleware;

use bandwidthThrottle\tokenBucket\storage\StorageException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\RateLimit\Exception\RateLimitException;
use Hyperf\RateLimit\Handler\RateLimitHandler;
use Hyperf\Utils\Coroutine;
use Hyperf\Utils\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AuthMiddleware.
 */
class RateLimitMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     */
    private RateLimitHandler $rateLimitHandler;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $bucketKey = $request->getUri()->getPath();
        if ($this->isVerify($bucketKey) && $this->inAllowedMethods($request->getMethod()) && ! empty($config = $this->config($bucketKey))) {
            $bucket = $this->rateLimitHandler->build($bucketKey, $config['count'], $config['count'], $config['ttl']);

            $maxTime = microtime(true) + 1;
            $seconds = 0;

            while (true) {
                try {
                    if ($bucket->consume($annotation->consume ?? 1, $seconds)) {
                        return $handler->handle($request);
                    }
                } catch (StorageException $exception) {
                    throw new RateLimitException('Service Unavailable.', 503);
                }
                if (microtime(true) + $seconds > $maxTime) {
                    break;
                }
                Coroutine::sleep($seconds > 0.001 ? $seconds : 0.001);
            }
            throw new RateLimitException('Service Unavailable.', 503);
        }
        return $handler->handle($request);
    }

    public function isVerify($request_uri)
    {
        $configs = get_rate_limit();
        $uris = [];
        foreach ($configs as $config) {
            $uris[] = $config->request_uri;
        }
        return in_array($request_uri, $uris);
    }

    public function config($request_uri)
    {
        $configs = get_rate_limit();
        foreach ($configs as $config) {
            if ($request_uri == $config->request_uri) {
                return [
                    'count' => $config->count,
                    'ttl' => $config->ttl,
                ];
            }
        }
        return [];
    }

    /**
     * Whether requests using this method are allowed to be logged.
     *
     * @param string $method
     *
     * @return bool
     */
    protected function inAllowedMethods($method)
    {
        return true;
//        return $allowedMethods->map(function ($method) {
//            return strtoupper($method);
//        })->contains($method);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->config['except'] as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            $methods = [];
            if (Str::contains($except, ':')) {
                [$methods, $except] = explode(':', $except);
                $methods = explode(',', $methods);
            }
            $methods = array_map('strtoupper', $methods);
            if ($this->is($request, $except)
                && (empty($methods) || in_array($request->getMethod(), $methods))) {
                return true;
            }
        }
        return false;
    }

    private function getMicroTime()
    {
        [$usec, $sec] = explode(' ', microtime());
        return (float) $usec + (float) $sec;
    }
}
