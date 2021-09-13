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
namespace UU\Admin\Service;

use App\Exception\BusinessException;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Throwable;
use UU\Admin\Kernel\Utils\DataExtend;
use UU\Admin\Model\SystemPermission;
use UU\Admin\Model\SystemRolePermission;
use UU\Admin\Traits\HasPermission;
use UU\Contract\Exception\ApiException;

class PermissionService
{
    use HasPermission;

    private $auth;

    public function create(array $data)
    {
        $systemPermission = new SystemPermission($data);
        $systemPermission->save();
        return $systemPermission;
    }

    public function getPermissions()
    {
        return $this->userPermission(get_admin_info())->pluck('slug')->toArray();
    }

    /**
     * @param $permission_id
     * @throws ApiException
     * @return Builder|Model|object
     */
    public function info($permission_id)
    {
        if (! $this->isSuperAdmin()) {
            throw new ApiException(400, '您无权限');
        }
        $info = SystemPermission::query()->where('permission_id', $permission_id)->first();
        if (empty($info)) {
            throw new ApiException(400, '菜单不存在');
        }
        return $info;
    }

    /**
     * 修改菜单.
     */
    public function update(int $permission_id, array $data)
    {
        $roleInfo = $this->info($permission_id);
        if (isset($data['permissions'])) {
            $permissions = [];
            Db::beginTransaction();
            try {
                SystemPermission::where('role_id', $permission_id)->delete();
                foreach ($data['permissions'] as $permission_id) {
                    if (! $this->checkPermissionId($permission_id)) {
                        throw new ApiException(400, '越权操作!');
                    }
                    $permissions[] = [
                        'permission_id' => $permission_id,
                        'role_id' => $permission_id,
                    ];
                }
                SystemPermission::insert($permissions);
                Db::commit();
            } catch (Throwable $err) {
                Db::rollBack();
                throw new ApiException($err->getCode(), $err->getMessage());
            }
        }
        $roleInfo->fill($data)->save();
        return $roleInfo;
    }

    public function list(array $data)
    {
        $data = array_filter_null($data);
        $data['order_field'] ??= 'order';
        $data['order_type'] ??= 'asc';
        $model = SystemPermission::query()->where('parent_id', 0)->with('children');
        return page($model, $data)->like('name')->equal('status')->get();
    }

    public function status($permission_id, $status = 0)
    {
        #菜单考虑到不多就直接循环理
        #todo 权限注解实现
        $ids = SystemPermission::where('parent_id', $permission_id)->pluck('permission_id')->toArray();
        $parent_ids = [$permission_id];
        while (count($ids) > 0) {
            $parent_ids = array_unique(array_merge($parent_ids, $ids));
            $ids = SystemPermission::whereIn('parent_id', $ids)->pluck('permission_id')->toArray();
        }
        SystemPermission::query()->whereIn('permission_id', $parent_ids)->update(['status' => $status]);
        #子级如果启用，那得把他的父级也要启用
//        if ($status == 1) {
//            $ids = [$permission_id];
//            $mid_ids = [];
//            while (count($ids) > 0) {
//                $mid_ids = array_unique(array_merge($mid_ids, $ids));
//                $ids = SystemPermission::whereIn('permission_id', $parent_ids)->pluck('parent_id')->toArray();
//            }
//            SystemPermission::query()->whereIn('permission_id', $parent_ids)->update(['status' => 1]);
//        }

        return [
            'message' => '操作成功！' . ($status == 0 ? '禁用' : '启用') . count($parent_ids) . '条',
        ];
    }

    public function tree()
    {
        $model = SystemPermission::query()->orderBy('order');
        $data = $model->where('status', 1)->get()->toArray();
        return DataExtend::arr2tree($data, 'permission_id');
    }

    public function delete(int $permission_id)
    {
        $roleInfo = $this->info($permission_id);
        if (SystemPermission::where('parent_id', $permission_id)->count() > 0) {
            throw new BusinessException(403, '存在子菜单，不允许删除！');
        }
        SystemRolePermission::where('permission_id', $permission_id)->delete();
        $roleInfo->delete();
        return [
            'message' => '删除成功',
        ];
    }

    public function getSystemRouteOptions($isUrl = false)
    {
        $router = get_container(DispatcherFactory::class)->getRouter('http');
        $data = $router->getData();
        $options = [];
        $options['*'] = [
            'label' => '*',
            'pid' => 0,
            'id' => 0,
            'value' => '*',
        ];
        $ids = [];
        if ($isUrl) {
            unset($options['*']);
            $route_key = '#/';
            $options[$route_key] = [
                'label' => $route_key,
                'value' => $route_key,
                'pid' => 0,
                'id' => 0,
            ];
        }
        foreach ($data as $routes_data) {
            foreach ($routes_data as $http_method => $routes) {
                $route_list = [];
                if (isset($routes[0]['routeMap'])) {
                    foreach ($routes as $map) {
                        array_push($route_list, ...$map['routeMap']);
                    }
                } else {
                    $route_list = $routes;
                }
                foreach ($route_list as $route => $v) {
                    //p($route);
                    // 过滤掉脚手架页面配置方法
                    $callback = is_array($v) ? ($v[0]->callback) : $v->callback;
                    if (! is_array($callback)) {
                        if (is_callable($callback)) {
                            continue;
                        }
                        if (strpos($callback, '@') !== false) {
                            [$controller, $action] = explode('@', $callback);
                        } else {
                            continue;
                        }
                    } else {
                        [$controller, $action] = $callback;
                    }
                    $route = is_string($route) ? rtrim($route) : rtrim($v[0]->route);
                    if ($isUrl) {
                        $route_key = $route;
                    } else {
                        $route_key = "{$http_method}::{$route}";
                    }
                    $pid = md5($controller);
                    if (in_array($pid, $ids)) {
                        $id = uniqid();
                    } else {
                        $ids[] = $pid;
                        if (! $isUrl) {
                            $arr = explode('/', $route);
                            array_pop($arr);
                            $prefix = config('admin.route.prefix'); //修复前缀admin/bug
                            if (! in_array($prefix, $arr)) {
                                array_pop($arr);
                            }
                            $arr[] = '*';
                            $route = implode('/', $arr);
                            $route_key = "ANY::{$route}";
                            $options[$route_key] = [
                                'label' => $route_key,
                                'value' => $route_key,
                                'pid' => 0,
                                'id' => $pid,
                            ];
                        }
                        continue;
                    }
                    $options[$route_key] = [
                        'value' => $route_key,
                        'label' => $route_key,
                        'pid' => $pid,
                        'id' => $id,
                    ];
                }
            }
        }
        return array_values($options);
    }
}
