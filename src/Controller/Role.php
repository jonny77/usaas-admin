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
use UU\Admin\Service\RoleService;
use UU\Admin\Validate\SystemRolesValidate;
use UU\Contract\Exception\ApiException;

/**
 * @AdminController(tag="角色管理", description="租户权限管理")
 * Class Tenant
 */
class Role extends Controller
{
    /**
     * @Inject
     */
    private RoleService $service;

    /**
     * @PostApi(summary="创建角色")
     * PreAuthorize("role:create")
     * @RequestValidation(validate=SystemRolesValidate::class, scene="create", filter=true)
     */
    public function create()
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->create($data);
    }

    /**
     * @GetApi(summary="角色列表")
     * @Query(key="role_name")
     * @Query(key="permission_id")
     * @Query(key="status")
     * @Query(key="page")
     * @Query(key="limit")
     */
    public function list()
    {
        return $this->service->list($this->request->all());
    }

    /**
     * @GetApi(summary="添加用户的角色列表")
     */
    public function role_tree()
    {
        return $this->service->role_tree();
    }

    /**
     * @PutApi(path="{role_id:\d+}", summary="修改角色")
     * @RequestValidation(validate=SystemRolesValidate::class, scene="update", filter=true)
     * @Path(key="role_id")
     * @throws ApiException
     */
    public function update(int $role_id)
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->update($role_id, $data);
    }

    /**
     * @DeleteApi(path="{role_id:\d+}", summary="删除角色")
     * @Path(key="role_id")
     * @return mixed
     */
    public function delete(int $role_id)
    {
        return $this->service->delete($role_id);
    }
}
