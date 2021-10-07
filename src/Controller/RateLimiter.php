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

use HPlus\Route\Annotation\AdminController;
use HPlus\Route\Annotation\DeleteApi;
use HPlus\Route\Annotation\GetApi;
use HPlus\Route\Annotation\Path;
use HPlus\Route\Annotation\PostApi;
use HPlus\Route\Annotation\PutApi;
use HPlus\Route\Annotation\Query;
use HPlus\Validate\Annotations\RequestValidation;
use Hyperf\Di\Annotation\Inject;
use UU\Admin\Service\RateLimiterService;
use UU\Admin\Validate\SystemRateLimiterValidate;

/**
 * @AdminController(tag="限流管理", description="限流管理")
 */
class RateLimiter extends Controller
{
    /**
     * @Inject
     */
    private RateLimiterService $service;

    /**
     * @PostApi(summary="创建")
     * @RequestValidation(validate=SystemRateLimiterValidate::class, scene="create", filter=true)
     */
    public function create()
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->create($data);
    }

    /**
     * @GetApi(summary="列表")
     * @Query(key="request_uri")
     * @Query(key="request_method")
     * @Query(key="status")
     * @Query(key="page")
     * @Query(key="limit")
     */
    public function list()
    {
        return $this->service->list($this->request->all());
    }

    /**
     * @PutApi(path="{id:\d+}", summary="修改")
     * @RequestValidation(validate=SystemRateLimiterValidate::class, scene="update", filter=true)
     * @Path(key="id")
     */
    public function update(int $id)
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->update($id, $data);
    }

    /**
     * @DeleteApi(path="{id:\d+}", summary="删除")
     * @Path(key="id")
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->service->delete($id);
    }
}
