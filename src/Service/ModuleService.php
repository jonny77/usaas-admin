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
use Hyperf\Utils\Context;
use UU\Admin\Model\SystemModule;
use UU\Admin\Model\TableModel;
use UU\Contract\Exception\ApiException;

class ModuleService
{
    public function list(array $data)
    {
        return page(new SystemModule(), $data)->equal('status')->like('route')->paginate();
    }

    public function create(array $data)
    {
        $model = new SystemModule($data);
        $model->save();
        return $model;
    }

    /**
     * @param $module_id
     * @throws ApiException
     * @return Builder|object
     */
    public function info($module_id)
    {
        $info = SystemModule::query()->where('module_id', $module_id)->first();
        if (empty($info)) {
            throw new ApiException(400, '模型不存在');
        }
        return $info;
    }

    /**
     * 修改权限组.
     */
    public function update(int $module_id, array $data)
    {
        $info = $this->info($module_id);
        $info->fill($data);
        $info->save();
        return $info;
    }

    public function route($module_id, $data = [], $action = 'list')
    {
        #上下文如果有，那就证明是通过虚拟路由进来的
        if ($module = Context::get('module')) {
            [$action, $module_id] = $module;
            unset($module);
        }
        $form = SystemModule::findFromCache($module_id);
        switch ($action) {
            case 'list':
                $model = new TableModel();
                $model->setTable($form->name);
                $model->setKeyName($form->primary_key);
                $dateBetween = $valueBetween = $where = $timeBetween = $equals = $likes = [];
                foreach ($form->filter as $filter) {
                    if (! isset($filter['type'], $filter['field'])) {
                        continue;
                    }
                    switch ($filter['type'] ?? '') {
                        case 'equal':
                            $equals[] = $filter['field'];
                            break;
                        case 'like':
                            $likes[] = $filter['field'];
                            break;
                        case 'dateBetween':
                            $dateBetween[] = $filter['field'];
                            break;
                        case 'timeBetween':
                            $timeBetween[] = $filter['field'];
                            break;
                        case 'valueBetween':
                            $valueBetween[] = $filter['field'];
                            break;
                        case 'where':
                            $where[] = [
                                $filter['field'] => $filter['where_type'],
                            ];
                            break;
                    }
                }
                return page($model, $data)->equal($equals)->like($likes)->timeBetween($timeBetween)->valueBetween($valueBetween)->addWhere($where)->dateBetween($dateBetween)->paginate();
            case 'create':
                break;
            case 'update':
                break;
            case 'delete':
                break;
            default:
                throw new ApiException(403, '路由不存在');
                break;
        }

        return $form;
    }
}
