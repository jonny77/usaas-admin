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
namespace UU\Admin\Traits;

use UU\Contract\Exception\ApiException;

trait HasDeleteApi
{
    /**
     * @param $model
     * @param null|\Closure $closure 扩展占位备用
     * @throws ApiException
     * @return string[]
     */
    public function delete($model, int $user_id, ?\Closure $closure = null)
    {
        $info = $model::query()->find($user_id);
        if (empty($info)) {
            throw new ApiException(403, '数据不存在');
        }
        # $closure todo

        $info->delete();
        return [
            'message' => '删除成功',
        ];
    }
}
