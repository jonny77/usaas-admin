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

use HPlus\Route\Annotation\PostApi;
use Hyperf\DbConnection\Db;
use UU\Contract\Exception\ApiException;

trait HasCreateApi
{
    /**
     * @PostApi(summary="创建")
     */
    public function create()
    {
        if (! method_exists($this->service, 'create')) {
            $info = new $this->model($this->request->all());
            Db::beginTransaction();
            try {
                $info->save();
                Db::commit();
            } catch (\Throwable $err) {
                Db::rollBack();
                throw new ApiException(400, $err->getMessage());
            }
            return $info;
        }
        return $this->service->create($this->request->all());
    }
}
