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

use App\Services\SiteService;
use Hyperf\Contract\ConfigInterface;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Utils\Context;
use Hyperf\Utils\Contracts\Arrayable;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use UU\Admin\Service\TenantService;

class CoreMiddleware extends \Hyperf\HttpServer\CoreMiddleware
{
    protected array $module;

    public function __construct(ContainerInterface $container, string $serverName, ConfigInterface $config)
    {
        parent::__construct($container, $serverName);
        $this->module = $config->get('system.module', []);
    }

    public function dispatch(ServerRequestInterface $request): ServerRequestInterface
    {
        $response = Context::get(ResponseInterface::class);
        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Methods', 'POST,GET,OPTIONS,DELETE')
            // Headers 可以根据实际情况进行改写。
            ->withHeader('Access-Control-Allow-Headers', 'DNT,Keep-Alive,User-Agent,Cache-Control,Content-Type,Authorization,token');
        Context::set(ResponseInterface::class, $response);

        $domain = $request->getUri()->getHost();
        $route = $request->getUri()->getPath();
        $module = $this->getVirtualRoute($route);

        if (is_tenant_enable()) {
            $tenant = TenantService::infoByDomain($domain);
            Context::set('current_tenant_info', $tenant);
            Context::set('current_tenant_id', $tenant->tenant_id ?? 0);
        }

        if (!empty($module)) {
            [$action, $module_id] = $module;
            $module[2] = (bool)$request->getHeaderLine('layout');
            Context::set('module', $module);
            $route = '/v1/system/modules/route/' . $action;
        }
        $routes = $this->dispatcher->dispatch($request->getMethod(), $route);
        $dispatched = new Dispatched($routes);
        $request = $request->withAttribute(Dispatched::class, $dispatched);
        Context::set(ServerRequestInterface::class, $request);
        return $request;
    }

    /**
     * Handle the response when cannot found any routes.
     *
     * @return array|Arrayable|mixed|ResponseInterface|string
     */
    protected function handleNotFound(ServerRequestInterface $request)
    {
        // 重写路由找不到的处理逻辑
        return $this->response()->withStatus(404);
    }

    /**
     * Handle the response when the routes found but doesn't match any available methods.
     *
     * @return array|Arrayable|mixed|ResponseInterface|string
     */
    protected function handleMethodNotAllowed(array $methods, ServerRequestInterface $request)
    {
        // 重写 HTTP 方法不允许的处理逻辑
        return $this->response()->withStatus(405);
    }

    /**
     * @return array
     */
    private function getVirtualRoute(string $route)
    {
        $routes = cache_has_set('cache_module_routes', function () {
            $list = Db::table('system_modules')->where('status', 1)->get(['route', 'module_id'])->keyBy('route')->map(function ($item) {
                return $item->module_id;
            })->toArray();
            $routes = [];
            foreach ($list as $key => $module_id) {
                foreach (['list', 'delete', 'update', 'create'] as $action) {
                    $routes[$key . '/' . $action] = [$action, $module_id];
                }
            }
            return $routes;
        });
        return $routes[$route] ?? [];
    }
}
