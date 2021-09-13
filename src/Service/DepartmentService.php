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
use UU\Admin\Model\Department;
use UU\Admin\Model\SystemUser;
use UU\Contract\Exception\ApiException;
use UU\Contract\Exception\BusinessException;

class DepartmentService
{
    public function list($data)
    {
        #todo 权限暂时先不加 直接搞
        $model = Department::query()->with('children');
        $data['parent_id'] ??= 0;
        $data['order_field'] ??= 'order';
        $data['order_type'] ??= 'asc';
        return page($model, $data)->like('name#query')->equal('parent_id,status')->get();
    }

    public function create(array $data)
    {
        Db::beginTransaction();
        try {
            $department = new Department(array_filter_null($data));
            $department->save();
            if ($department->parent_id == 0) {
                $department->full_path = '0-' . $department->department_id . '-';
            } else {
                $parent_full_path = Department::query()->where('department_id', $department->parent_id)->value('full_path');
                $department->full_path = $parent_full_path . $department->department_id . '-';
            }
            $department->save();
            DB::commit();
        } catch (Throwable $throw) {
            DB::rollBack();
            throw new BusinessException($throw->getMessage(), 403);
        }
        return $department;
    }

    /**
     * @throws ApiException
     * @throws BusinessException
     * @return null|Builder|Model|object
     */
    public function update(int $department_id, array $data)
    {
        #todo :需要检测权限，暂时先不加，后续自动注入实现
        $department = $this->info($department_id);
        if (isset($data['parent_id']) && $data['parent_id'] == $department->department_id) {
            throw new BusinessException('父类选择错误！', 403);
        }
        #todo 如果设置管理员，要检测管理员是否在当前企业下单员工用户下，
        if (isset($data['supervisor_id'])) {
        }
        Db::beginTransaction();
        try {
            $department->fill(array_filter_null($data));
            $department->save();
            if ($department->parent_id == 0) {
                $department->full_path = '0-' . $department->department_id . '-';
            } else {
                $parent_full_path = Department::query()->where('department_id', $department->parent_id)->value('full_path');
                $department->full_path = $parent_full_path . $department->department_id . '-';
            }
            $department->save();
            DB::commit();
        } catch (Throwable $throw) {
            DB::rollBack();
            throw new BusinessException($throw->getMessage(), 403);
        }
        return $department;
    }

    /**
     * 注解的
     * PreAuthorize(permission="department:view").
     * @throws ApiException
     * @return Builder|Department|Model|object
     */
    public function info(int $department_id)
    {
        $info = Department::query()->where('department_id', $department_id)->first();
        if (empty($info)) {
            throw new BusinessException('部门不存在', 403);
        }
        return $info;
    }

    public function delete(int $department_id)
    {
        $deptInfo = $this->info($department_id);
        if (empty($deptInfo)) {
            throw new BusinessException('部门不存在！', 403);
        }
        if (Department::where('full_path', 'like', $deptInfo->full_path . '%')->count() > 1) {
            throw new BusinessException('部门下存在子分类，请先删除子分类！', 403);
        }
        if (SystemUser::where('department_id', $department_id)->count() > 0) {
            throw new BusinessException('部门下存在用户，不允许删除！', 403);
        }
        $deptInfo->delete();
        return [
            'message' => '删除成功！',
        ];
    }
}
