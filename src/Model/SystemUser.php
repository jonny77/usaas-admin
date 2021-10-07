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

use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Utils\Context;
use Qbhy\HyperfAuth\Authenticatable;

/**
 * @property int $user_id
 * @property string $username 用户名
 * @property string $mobile 手机号
 * @property string $nickname 昵称
 * @property string $email 邮箱
 * @property string $password 密码
 * @property string $avatar 头像
 * @property string $description 描述
 * @property int $sex 性别：1男;2女 0未知
 * @property int $status 状态 0 禁用 1 启用
 * @property int $role_id 角色ID
 * @property int $department_id 部门ID
 * @property int $create_user_id 创建人ID
 * @property int $tenant_id 租户ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemUser extends Model implements Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_users';

    protected $primaryKey = 'user_id';

    protected $hidden = ['password'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'username', 'mobile', 'nickname', 'email', 'password', 'avatar', 'description', 'sex', 'status', 'role_id', 'department_id', 'create_user_id', 'tenant_id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['user_id' => 'integer', 'sex' => 'integer', 'status' => 'integer', 'role_id' => 'integer', 'department_id' => 'integer', 'create_user_id' => 'integer', 'tenant_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->user_id;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }

    public static function retrieveById($key): ?Authenticatable
    {
        // TODO: Implement retrieveById() method.
        return static::findFromCache($key);
    }

    /**
     * A user has and belongs to many roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SystemRole::class, 'system_role_users', 'user_id', 'role_id');
    }

    public function permissionIds()
    {
        return Context::getOrSet($this->user_id . '_permission_ids', function () {
            $ids = [];
            $roleList = SystemRoleUser::query()->with('permission')->where('user_id', get_admin_info()->user_id)->get();
            $roleList->map(function ($item) use (&$ids) {
                return $item->permission->map(function ($permission) use (&$ids) {
                    $ids[] = $permission['permission_id'];
                });
            });
            return array_unique($ids);
        });
    }
}
