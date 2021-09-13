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
 * @property int $role_permission_id 自增ID
 * @property int $permission_id 权限ID
 * @property int $role_id 角色ID
 * @property \Carbon\Carbon $created_at 创建时间
 * @property int $create_user_id 创建人
 * @property \Carbon\Carbon $updated_at 修改时间
 * @property string $updated_by 修改人
 */
class SystemRolePermission extends Model
{
    protected $primaryKey = 'role_permission_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_role_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_permission_id', 'permission_id', 'role_id', 'created_at', 'create_user_id', 'updated_at', 'updated_by'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['role_permission_id' => 'integer', 'permission_id' => 'integer', 'role_id' => 'integer', 'created_at' => 'datetime', 'create_user_id' => 'integer', 'updated_at' => 'datetime'];
}
