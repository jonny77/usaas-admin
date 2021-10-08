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

use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;
use UU\Admin\Kernel\Traits\DataIsolation;

abstract class Model extends BaseModel implements CacheableInterface
{
    use Cacheable;
    use DataIsolation;

    protected $hidden = ['create_user_id'];
}
