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
namespace UU\Admin\Model;

/**
 * @property int $id
 * @property int $role_id 角色ID
 * @property int $user_id 员工ID
 * @property int $tenant_id 租户ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemRoleUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_role_users';

    protected $fillable = ['id', 'role_id', 'user_id', 'tenant_id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'role_id' => 'integer', 'user_id' => 'integer', 'tenant_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * A user has and belongs to many roles.
     */
    public function permission()
    {
        return $this->hasManyThrough(SystemPermission::class, SystemRolePermission::class, 'role_id', 'permission_id', 'role_id', 'permission_id')->select(['system_permissions.permission_id', 'system_permissions.slug']);
    }
}
