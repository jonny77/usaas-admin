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
use UU\Admin\Model\SystemPermission;
use UU\Admin\Model\SystemRoleUser;
use UU\Admin\Model\SystemUser;
use UU\Contract\Exception\ApiException;
use UU\Contract\Exception\BusinessException;

class UserService
{
    public function create(array $data)
    {
        $user = new SystemUser($data);
        Db::beginTransaction();
        try {
            $user->save();
            if (isset($data['roles'])) {
                $roles = [];
                foreach ($data['roles'] as $role) {
//                    if (!$this->checkPermissionId($permission_id)) {
//                        throw new ApiException('越权操作!');
//                    }
                    $roles[] = [
                        'user_id' => $user->user_id,
                        'role_id' => $role,
                    ];
                }
                SystemRoleUser::insert($roles);
            }
            Db::commit();
        } catch (\Throwable $err) {
            Db::rollBack();
            throw new ApiException(400, $err->getMessage());
        }
        return $user;
    }

    /**
     * @param $user_id
     * @throws ApiException
     * @return Builder|Model|object
     */
    public function info($user_id)
    {
        $info = SystemUser::query()->where('user_id', $user_id)->with('roles:role_name,slug')->first();
        if (empty($info)) {
            throw new ApiException(400, '用户不存在');
        }
        return $info;
    }

    /**
     * 修改权限组.
     */
    public function update(int $user_id, array $data)
    {
        $userInfo = $this->info($user_id);
        $userInfo->fill($data);
        Db::beginTransaction();
        try {
            $userInfo->save();
            if (isset($data['roles'])) {
                $roles = [];
                SystemRoleUser::where('user_id', $userInfo->user_id)->delete();
                foreach ($data['roles'] as $role) {
//                    if (!$this->checkPermissionId($permission_id)) {
//                        throw new ApiException('越权操作!');
//                    }
                    $roles[] = [
                        'user_id' => $userInfo->user_id,
                        'role_id' => $role,
                    ];
                }
                SystemRoleUser::insert($roles);
            }
            Db::commit();
        } catch (\Throwable $err) {
            Db::rollBack();
            throw new ApiException(400, $err->getMessage());
        }
        return $userInfo;
    }

    public function list(array $data)
    {
        $data = array_filter_null($data);
        $model = SystemUser::query()->with('roles:role_name,slug');
        return page($model, $data)->like('nickname,username')->equal('department_id')->paginate();
    }

    public function delete(int $user_id)
    {
        $userInfo = $this->info($user_id);
        if ($userInfo->user_id == 1) {
            throw new ApiException(400, '系统用户不允许删除！');
        }
        $userInfo->delete();
        return [
            'message' => '删除成功',
        ];
    }

    public function modifyPassword(int $user_id, array $data)
    {
        $userInfo = $this->info($user_id);
        if (! password_verify($data['old_password'], $userInfo->password)) {
            throw new ApiException(400, '旧密码不正确');
        }
        $userInfo->fill(['password' => $data['password']])->save();
        return $userInfo;
    }

    public function login($param)
    {
        $userInfo = SystemUser::query()->with('roles:role_name,slug')->where('username', $param['username'])->first();
        if (empty($userInfo)) {
            throw new ApiException(400, '账号或密码错误');
        }
        if (empty($userInfo) || ! password_verify($param['password'], $userInfo->password)) {
            throw new ApiException(400, '账号或密码错误');
        }
        $userInfo['token'] = auth('admin')->login($userInfo);
        return $userInfo;
    }

    public function userInfo()
    {
        return SystemUser::query()->with('roles:role_name,slug')->where('user_id', get_admin_info()->user_id)->first();
    }

    public function logout()
    {
        try {
            auth('admin')->logout();
        } catch (\Throwable $exception) {
            p($exception->getMessage());
        }
        return [
            'message' => '退出成功',
        ];
    }

    public function menus()
    {
        return SystemPermission::menus();
    }

    public function exist(array $data)
    {
        $username = $data['username'] ?? '';
        if (SystemUser::query()->where('username', $username)->count() > 0) {
            throw new BusinessException('已存在', 400);
        }
        return $data;
    }

    public function permissions()
    {
        $slugs = [];
        $roleList = SystemRoleUser::query()->with('permission')->where('user_id', get_admin_info()->user_id)->get();
        $roleList->map(function ($item) use (&$slugs) {
            return $item->permission->map(function ($permission) use (&$slugs) {
                $slugs[] = $permission['slug'];
            });
        });
        return array_unique($slugs);
    }

    public function refresh()
    {
        return [
            'access_token' => auth('admin')->refresh(),
            'expires_in' => 7200,
        ];
    }
}
