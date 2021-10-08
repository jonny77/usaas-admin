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

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;
use Hyperf\Database\Model\Scope;

class DataScope implements Scope
{
    /**
     * 全局数据隔离注入.
     */
    public function apply(Builder $builder, Model $model)
    {
        if (! is_super_administrator()) {
            $builder->where($model->getTable() . '.tenant_id', get_tenant_id());
        }
    }
}
