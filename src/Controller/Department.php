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
use UU\Admin\Service\DepartmentService;
use UU\Admin\Validate\DepartmentsValidate;
use UU\Contract\Exception\ApiException;

/**
 * @AdminController(tag="部门管理", description="企业部门相关接口")
 * Class Tenant
 */
class Department extends Controller
{
    /**
     * @Inject
     */
    private DepartmentService $service;

    /**
     * @GetApi(summary="部门列表")
     * @Query(key="limit", name="条数限制")
     * @Query(key="status", name="状态")
     * @Query(key="page", name="分页ID")
     * @Query(key="parent_id", name="上级ID")
     * @Query(key="query", name="搜索名称")
     */
    public function list()
    {
        $data = $this->request->all();
        return $this->service->list($data);
    }

    /**
     * @PostApi(summary="创建部门")
     * @RequestValidation(validate=DepartmentsValidate::class, scene="create")
     */
    public function create()
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->create($data);
    }

    /**
     * @GetApi(path="{department_id:\d+}", summary="查询部门详情")
     * @Path(key="department_id", name="部门ID")
     */
    public function detail(int $department_id)
    {
        return $this->service->info($department_id);
    }

    /**
     * @DeleteApi(path="{department_id:\d+}", summary="删除部门")
     * @Path(key="department_id", name="部门ID")
     */
    public function delete(int $department_id)
    {
        return $this->service->delete($department_id);
    }

    /**
     * @PutApi(path="{department_id:\d+}", summary="修改部门信息")
     * @Path(key="department_id", name="部门ID")
     * @RequestValidation(validate=DepartmentsValidate::class, scene="update")
     * @throws ApiException
     */
    public function put(int $department_id)
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->update($department_id, $data);
    }
}
