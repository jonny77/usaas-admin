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
use UU\Admin\Model\SystemRateLimiter;
use UU\Contract\Exception\ApiException;

class RateLimiterService
{
    /**
     * @param $id
     * @throws ApiException
     * @return Builder|\Hyperf\Database\Model\Model|object
     */
    public function info($id)
    {
        $info = SystemRateLimiter::query()->where('id', $id)->first();
        if (empty($info)) {
            throw new ApiException(400, '数据不存在');
        }
        return $info;
    }

    public function create(array $data)
    {
        $model = new SystemRateLimiter($data);
        $model->save();
        return $model;
    }

    public function list(array $data)
    {
        $model = SystemRateLimiter::query();
        $data['order_field'] ??= 'order';
        $data['order_type'] ??= 'asc';
        return page($model, $data)->like('request_uri')->equal('request_method,status')->paginate();
    }

    public function update(int $id, array $data)
    {
        $info = $this->info($id);
        $info->fill($data);
        $info->save();
        return $info;
    }

    public function delete(int $id)
    {
        $info = $this->info($id);
        $info->delete();
        return [
            'message' => '删除成功',
        ];
    }
}
