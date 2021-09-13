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

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

abstract class Controller
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->response = $container->get(ResponseInterface::class);
        $this->request = $container->get(RequestInterface::class);
    }
}
