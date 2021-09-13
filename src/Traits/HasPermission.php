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
namespace UU\Admin\Traits;

use Psr\SimpleCache\CacheInterface;
use Qbhy\HyperfAuth\Authenticatable;
use UU\Admin\Model\SystemUser;

trait HasPermission
{
    /**
     * @var CacheInterface
     */
    protected $cache;

    private static $cacheName = 'permission:';

    private static $cacheRoleName = 'role:';

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getRoles(Authenticatable $user, $slug = 'slug'): array
    {
        return cache_has_set($slug . ':' . $this->getRoleKey($user->getId()), function () use ($user, $slug) {
            return $user->roles()->pluck($slug)->toArray();
        }, 10);
    }

    public function userPermission(Authenticatable $user)
    {
        /**
         * @var SystemUser $user ;
         */
        $roles = $user->roles;
        $ids_has = [];
        $all_permission = [];

//        return SystemPermission::query()->get([
//            'permission_id',
//            'name',
//            'slug',
//            'desc',
//        ]);
        #过滤掉已经有的权限
        foreach ($roles as $role) {
            $permission = $role->permissions->toArray();
            foreach ($permission as $item) {
                #记录已经有的权限id
                $ids_has[] = $item['permission_id'];
                if (in_array($item['permission_id'], $ids_has)) {
                    continue;
                }
                $all_permission[] = $item;
                unset($item);
            }
        }
        $userPermissions = $user->permissions->toArray();
        foreach ($userPermissions as $permission) {
            if (in_array($permission['permission_id'], $ids_has)) {
                continue;
            }
            $all_permission[] = $permission;
        }

        return $all_permission;
    }

    public function checkPermissionId($permission_id): bool
    {
        return true;
        if ($this->isSuperAdmin()) {
            return true;
        }
        return in_array($permission_id, $this->getPermissionIds($this->userCorpEmpInfo()));
    }

    public function isSuperAdmin()
    {
        return true;
    }

    protected function getRoleKey($id = null)
    {
        return self::$cacheRoleName . $id;
    }

    protected function getPermissionKey($id = null): string
    {
        return self::$cacheRoleName . $id;
    }

    /**
     * @param mixed $userId
     * @return bool|mixed
     */
    protected function getPermissionSlug(Authenticatable $user)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return cache_has_set($this->getPermissionKey($user->getId()), function () use ($user) {
            $roles = $this->userPermission($user);
            $allPermission = [];
            foreach ($roles as $role) {
                $allPermission[] = $role['slug'];
            }
            return array_unique($allPermission);
        }, 10);
    }

    /**
     * @param mixed $userId
     * @return bool|mixed
     */
    protected function getPermissionIds(Authenticatable $user)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return cache_has_set(__FUNCTION__ . $this->getPermissionKey($user->getId()), function () use ($user) {
            $roles = $this->userPermission($user);
            $allPermission = [];
            foreach ($roles as $role) {
                $allPermission[] = $role['permission_id'];
            }
            return array_unique($allPermission);
        }, 10);
    }
}
