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

class TableModel extends Model
{
    protected $primaryKey = 'department_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_departments';

    public function children()
    {
        return $this->hasMany(get_class($this), 'parent_id', 'department_id')->with(['children'])->orderBy('order');
    }
}
