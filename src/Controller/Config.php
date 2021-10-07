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
namespace UU\Admin\Controller;

use HPlus\Route\Annotation\AdminController;
use HPlus\Route\Annotation\DeleteApi;
use HPlus\Route\Annotation\GetApi;
use HPlus\Route\Annotation\Path;
use HPlus\Route\Annotation\PostApi;
use HPlus\Route\Annotation\PutApi;
use HPlus\Route\Annotation\Query;
use HPlus\Validate\Annotations\RequestValidation;
use Hyperf\Database\Model\Builder;
use Hyperf\Di\Annotation\Inject;
use UU\Admin\Model\Model;
use UU\Admin\Model\SystemConfig;
use UU\Admin\Service\ConfigService;
use UU\Admin\Traits\HasCreateApi;
use UU\Admin\Traits\HasDeleteApi;
use UU\Admin\Traits\HasUpdateApi;
use UU\Admin\Validate\SystemConfigValidate;

/**
 * @AdminController(tag="系统配置", description="系统配置")
 * Class Tenant
 */
class Config extends Controller
{
    use HasUpdateApi;
    use HasCreateApi;
    use HasDeleteApi;

    /**
     * @Inject
     */
    private ConfigService $service;

    /**
     * @var Model
     */
    private $model = SystemConfig::class;

    /**
     * @GetApi(summary="获取设置配置")
     */
    public function _self_path()
    {
        return $this->service->configForm();
    }

    /**
     * @PostApi(path="update/{key}", summary="设置配置")
     * @Path(key="key")
     * @return array
     */
    public function updateSetting(string $key)
    {
        $data = $this->request->getParsedBody();
        return $this->service->updateConfig($key, $data);
    }

    /**
     * @GetApi(path="items/{key}", summary="修改配置")
     * @Path(key="key")
     * @param int $key
     * @return Builder|\Hyperf\Database\Model\Model|object
     */
    public function getConfig(string $key)
    {
        return $this->service->config($key);
    }

    /**
     * @PutApi(path="{id:\d+}", summary="修改配置")
     * @RequestValidation(validate=SystemConfigValidate::class, scene="update", filter=true)
     * @Path(key="id")
     * @return Builder|\Hyperf\Database\Model\Model|object
     */
    public function update(int $id)
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->update($id, $data);
    }

    /**
     * @GetApi(summary="菜单维护列表")
     * @Query(key="parent_id")
     */
    public function list()
    {
        return $this->service->list($this->request->all());
    }

    /**
     * @PostApi(path="_self_path", summary="设置配置")
     */
    public function setConfig(): array
    {
        return $this->service->setConfig($this->request->all());
    }

    /**
     * @DeleteApi(path="{id:\d+}", summary="删除用户")
     * @Path(key="id")
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->service->delete($id);
    }
}
