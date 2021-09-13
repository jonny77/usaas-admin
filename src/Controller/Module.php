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
use UU\Admin\Service\ModuleService;
use UU\Admin\Validate\SystemModulesValidate;
use UU\Contract\Exception\BusinessException;

/**
 * @AdminController(tag="模型管理", description="模型管理。虚拟路由，增删改查表单")
 * Class Tenant
 */
class Module extends Controller
{
    /**
     * @Inject
     */
    private ModuleService $service;

    /**
     * @GetApi(summary="模型列表")
     * @Query(key="name")
     * @Query(key="status")
     * @Query(key="page")
     * @Query(key="limit")
     */
    public function list()
    {
        return $this->service->list($this->request->all());
    }

    /**
     * @PostApi(summary="创建一个模型")
     * @RequestValidation(validate=SystemModulesValidate::class, scene="create")
     */
    public function create()
    {
        return $this->service->create($this->request->getParsedBody());
    }

    /**
     * @PutApi(path="{module_id:\d+}", summary="修改模型")
     * @RequestValidation(validate=SystemModulesValidate::class, scene="update", filter=true)
     * @Path(key="module_id")
     * @throws BusinessException
     */
    public function update(int $module_id)
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->update($module_id, $data);
    }

//    /**
//     * @PostApi (summary="模型设置")
//     * @RequestValidation(validate=SystemModulesValidate::class, scene="update",filter=true)
//     * @Path(key="module_id")
//     * @throws BusinessException
//     */
//    public function config(int $module_id)
//    {
//        $data = (array)$this->request->getParsedBody();
//        return $this->service->update($module_id, $data);
//    }

    /**
     * @GetApi(path="{module_id:\d+}", summary="查看模型详情")
     * @Path(key="module_id")
     * @throws BusinessException
     */
    public function detail(int $module_id)
    {
        return $this->service->info($module_id);
    }

    /**
     * @GetApi(path="route/list", summary="生成路由")
     * @Query(key="module_id")
     */
    public function route_list()
    {
        return $this->service->route($this->request->query('module_id'), $this->request->all());
    }

    /**
     * @PostApi(path="route/create", summary="创建数据路由")
     * @Query(key="module_id")
     */
    public function route_create()
    {
        return $this->service->route($this->request->query('module_id'), $this->request->all());
    }

    /**
     * @PutApi(path="route/update", summary="修改数据路由")
     * @Query(key="module_id")
     */
    public function route_update()
    {
        return $this->service->route($this->request->query('module_id'), $this->request->all());
    }

    /**
     * @DeleteApi(path="route/delete", summary="删除数据路由")
     * @Query(key="module_id")
     */
    public function route_delete()
    {
        return $this->service->route($this->request->query('module_id'), $this->request->all());
    }
}
