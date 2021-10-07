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
 * @property int $role_id
 * @property string $role_name 角色名
 * @property string $slug 角色的标识
 * @property int $parent_id 父级ID
 * @property int $status 状态 1有效 0无效
 * @property int $create_user_id 创建人ID
 * @property string $remark 角色备注
 * @property int $tenant_id 租户ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_role';

    protected $primaryKey = 'role_id';

    protected $appends = ['key'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'role_name', 'slug', 'parent_id', 'status', 'create_user_id', 'remark', 'tenant_id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['role_id' => 'integer', 'parent_id' => 'integer', 'status' => 'integer', 'create_user_id' => 'integer', 'tenant_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

//    public function Booted(Booted $event)
//    {
//        $event->getModel()::addGlobalScope(new DataScope());
//    }

    public function getKeyAttribute()
    {
        return $this->role_id . '';
    }

    public function permission()
    {
        return $this->hasManyThrough(SystemPermission::class, SystemRolePermission::class, 'role_id', 'permission_id', 'role_id', 'permission_id')->select('system_permissions.permission_id');
    }
}
