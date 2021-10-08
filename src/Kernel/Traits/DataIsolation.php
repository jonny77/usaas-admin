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
namespace UU\Admin\Kernel\Traits;

use Hyperf\Database\Model\Events\Booted;
use Hyperf\Database\Model\Events\Creating;
use UU\Admin\Model\DataScope;

trait DataIsolation
{
    public function creating(Creating $event)
    {
        if (is_tenant_enable()) {
            $event->getModel()->tenant_id = get_tenant_id();
        }
    }

    public function Booted(Booted $event)
    {
        if (is_tenant_enable()) {
            $event->getModel()::addGlobalScope(new DataScope());
        }
    }
}
