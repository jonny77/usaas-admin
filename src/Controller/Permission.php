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
use Hyperf\Utils\Str;
use UU\Admin\Service\PermissionService;
use UU\Admin\Validate\SystemPermissionValidate;

/**
 * @AdminController(tag="菜单权限", description="菜单权限管理")
 * Class Tenant
 */
class Permission extends Controller
{
    /**
     * @Inject
     */
    private PermissionService $service;

    /**
     * @PostApi(summary="创建权限")
     * PreAuthorize("role:create")
     * @RequestValidation(validate=SystemPermissionValidate::class, scene="create")
     */
    public function create()
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->create($data);
    }

    /**
     * @GetApi(summary="权限菜单列表")
     * @Query(key="name")
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
     * @PostApi(summary="权限菜单禁用，包含下级会被禁用")
     * @Query(key="permission_id")
     * @Query(key="status")
     */
    public function status()
    {
        return $this->service->status($this->request->post('permission_id'), $this->request->post('status', 0));
    }

    /**
     * @GetApi(summary="添加角色拉取菜单列表")
     */
    public function tree()
    {
        return $this->service->tree();
    }

    /**
     * @PutApi(path="{permission_id:\d+}", summary="修改菜单")
     * @RequestValidation(validate=SystemPermissionValidate::class, scene="update")
     * @Path(key="permission_id")
     */
    public function update(int $permission_id)
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->update($permission_id, $data);
    }

    /**
     * @DeleteApi(path="{permission_id:\d+}", summary="删除菜单")
     * @Path(key="permission_id")
     * @return mixed
     */
    public function delete(int $permission_id)
    {
        return $this->service->delete($permission_id);
    }

    /**
     * @GetApi(path="route", summary="路由列表")
     */
    public function route()
    {
        $kw = $this->request->query('query', '');
        $routes = $this->service->getSystemRouteOptions(true);
        $routes = array_filter($routes, function ($item) use ($kw) {
            if (empty($kw)) {
                return true;
            }
            return Str::contains($item['value'], $kw);
        });
        $routes = array_values($routes);
        return ['items' => $routes, 'total' => count($routes)];
    }
}
