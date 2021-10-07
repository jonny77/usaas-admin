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

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;
use Throwable;
use UU\Admin\Model\SystemRole;
use UU\Admin\Model\SystemRolePermission;
use UU\Admin\Traits\HasPermission;
use UU\Contract\Exception\ApiException;

class RoleService
{
    use HasPermission;

    private $auth;

    public function create(array $data)
    {
        $corpRole = new SystemRole($data);
        $permissions = [];
        Db::beginTransaction();
        try {
            $corpRole->save();
            if (isset($data['permissions'])) {
                foreach ($data['permissions'] as $permission_id) {
//                    if (!$this->checkPermissionId($permission_id)) {
//                        throw new ApiException('越权操作!');
//                    }
                    $permissions[] = [
                        'permission_id' => $permission_id,
                        'role_id' => $corpRole->role_id,
                    ];
                }
            }
            SystemRolePermission::insert($permissions);
            Db::commit();
        } catch (Throwable $err) {
            Db::rollBack();
            throw new ApiException(400, $err->getMessage());
        }
        return $corpRole;
    }

    public function getPermissions()
    {
        return $this->userPermission(get_admin_info())->pluck('slug')->toArray();
    }

    /**
     * @param $role_id
     * @throws ApiException
     * @return Builder|Model|object
     */
    public function info($role_id)
    {
        $info = SystemRole::query()->where('role_id', $role_id)->first();
        if (empty($info)) {
            throw new ApiException(400, '权限组不存在');
        }
        return $info;
    }

    /**
     * 修改权限组.
     */
    public function update(int $role_id, array $data)
    {
        $roleInfo = $this->info($role_id);
        if (isset($data['slug']) && SystemRole::query()->where('role_id', '!=', $role_id)->where('slug', $data['slug'])->count() > 0) {
            throw new ApiException(400, '角色的标识 已存在');
        }
        if (isset($data['permissions'])) {
            $permissions = [];
            Db::beginTransaction();
            try {
                SystemRolePermission::where('role_id', $role_id)->delete();
                foreach ($data['permissions'] as $permission_id) {
                    if (! $this->checkPermissionId($permission_id)) {
                        throw new ApiException(400, '越权操作!');
                    }
                    $permissions[] = [
                        'permission_id' => $permission_id,
                        'role_id' => $role_id,
                    ];
                }
                SystemRolePermission::insert($permissions);
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
        $model = SystemRole::query()->with('permission');
        if (isset($data['permission_id']) && $data['permission_id'] != -1 && $data['permission_id'] != 0) {
            $role_ids = SystemRolePermission::where('permission_id', $data['permission_id'])->get(['role_id'])->pluck('role_id')->toArray();
            $model->whereIn('role_id', $role_ids);
        }
        if (isset($data['role_id'])) {
            $model->where('role_id', $data['role_id']);
        }
        return page($model, $data)->like('role_name')->equal('status')->paginate(['*'], function ($list) {
            return $list->map(function ($item) {
                $permissions = $item->permission->pluck('permission_id')->toArray();
                unset($item->permission);
                $item->permissions = $permissions;
                return $item;
            });
        });
    }

    public function delete(int $role_id)
    {
        $roleInfo = $this->info($role_id);
        $roleInfo->delete();
        SystemRolePermission::query()->where('role_id', $role_id)->delete();
        return [
            'message' => '删除成功',
        ];
    }

    public function role_tree()
    {
        $model = SystemRole::query()->with('permission');
        $model->where('status', 1);
        return page($model, [])->get(['*'], function ($list) {
            return $list->map(function ($item) {
                $permissions = $item->permission->pluck('permission_id')->toArray();
                unset($item->permission);
                $item->permissions = $permissions;
                return $item;
            });
        });
    }
}
