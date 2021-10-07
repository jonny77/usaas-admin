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
namespace UU\Admin\Controller;

use GuzzleHttp\Psr7\Response;
use HPlus\Route\Annotation\AdminController;
use HPlus\Route\Annotation\GetApi;
use Hyperf\Di\Annotation\Inject;
use UU\Admin\Service\ConfigService;

/**
 * @AdminController(tag="站点", description="系统配置")
 * Class Tenant
 */
class Site extends Controller
{
    /**
     * @Inject
     */
    private ConfigService $service;

    /**
     * @GetApi(summary="修改配置")
     */
    public function config()
    {
        return new Response(200, [
            'Content-Type' => 'application/javascript; charset=utf-8',
        ], $this->service->configScript());
    }
}
