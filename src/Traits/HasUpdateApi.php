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

trait HasUpdateApi
{
    /**
     * 修改.
     * @param array $data
     * @param null|\Closure $closure
     * @throws ApiException
     * @return mixed
     */
    public function update(int $id)
    {
        $userInfo = $this->model::query()->find($id);
        if (empty($userInfo)) {
            throw new ApiException(403, '数据不存在');
        }
        $userInfo->fill($this->request->all())->save();
        return $userInfo;
    }
}
