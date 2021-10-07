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

use Carbon\Carbon;
use Hyperf\Database\Model\Events\Saved;
use Hyperf\Database\Model\Relations\HasMany;
use UU\Contract\Exception\ApiException;

/**
 * @property int $department_id 部门ID
 * @property string $name 应用名称
 * @property string $full_path 权限节点
 * @property string $remark 备注
 * @property int $order 排序
 * @property int $parent_id 上级ID
 * @property int $status 状态 0 禁用 1 启用
 * @property int $create_user_id 创建人ID
 * @property int $employee_num 部门人数
 * @property int $tenant_id 租户ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Department extends Model
{
    protected $primaryKey = 'department_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_departments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['department_id', 'name', 'full_path', 'remark', 'order', 'parent_id', 'status', 'create_user_id', 'employee_num', 'tenant_id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['department_id' => 'integer', 'order' => 'integer', 'parent_id' => 'integer', 'status' => 'integer', 'create_user_id' => 'integer', 'employee_num' => 'integer', 'tenant_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 设置上层节点，需要在这里自动完成full_path 节点权限的自动分配.
     * @param $value
     */
    public function setParentIdAttribute($value)
    {
        if ($value == 0) {
            $this->attributes['parent_id'] = $value;
            return;
        }
        $parent_full_path = static::query()->where('department_id', $value)->value('full_path');
        if (empty($parent_full_path)) {
            throw new ApiException(400, '父节点不存在');
        }
        $this->attributes['parent_id'] = $value;
    }

    /**
     * 修改信息的时候需要将子节点的路径更新.
     */
    public function saved(Saved $event)
    {
        /** @var Department $model */
        $model = $event->getModel();
        switch (true) {
            #检测是否修改节点层级，如果修改，则更新子集的节点层级
            case $model->isDirty(['full_path']):
                $parent_full_path = static::query()->where('department_id', $model->department_id)->value('full_path');
                $subList = static::query()->where('parent_id', $model->department_id)->get(['department_id']);
                foreach ($subList as $department) {
                    $department->full_path = $parent_full_path . $department->department_id . '-';
                    $department->save();
                }
                break;
        }
    }

    public function department(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id', 'department_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id', 'department_id')->with(['children'])->orderBy('order')->select(['department_id', 'name', 'status', 'order', 'full_path', 'parent_id', 'created_at']);
    }
}
