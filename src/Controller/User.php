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
use Hyperf\Di\Annotation\Inject;
use UU\Admin\Service\UserService;
use UU\Admin\Validate\SystemUserValidate;
use UU\Contract\Exception\BusinessException;

/**
 * @AdminController(tag="系统用户", description="用户相关")
 * Class IndexController
 */
class User extends Controller
{
    /**
     * @Inject
     */
    private UserService $service;

    /**
     * @PostApi(summary="创建一个用户")
     * @RequestValidation(validate=SystemUserValidate::class, scene="create")
     */
    public function create()
    {
        return $this->service->create($this->request->getParsedBody());
    }

    /**
     * @PutApi(path="{user_id:\d+}", summary="修改用户")
     * @RequestValidation(validate=SystemUserValidate::class, scene="update", filter=true)
     * @Path(key="user_id")
     * @throws BusinessException
     */
    public function update(int $user_id)
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->update($user_id, $data);
    }

    /**
     * @GetApi(path="{user_id:\d+}", summary="查看用户详情")
     * @Path(key="user_id")
     * @throws BusinessException
     */
    public function detail(int $user_id)
    {
        return $this->service->info($user_id);
    }

    /**
     * @PutApi(path="modify_password/{user_id:\d+}", summary="修改用户密码")
     * @RequestValidation(validate=SystemUserValidate::class, scene="password", filter=true)
     * @Path(key="user_id")
     * @throws BusinessException
     */
    public function modify_password(int $user_id)
    {
        $data = (array) $this->request->getParsedBody();
        return $this->service->modifyPassword($user_id, $data);
    }

    /**
     * @GetApi(summary="用户列表")
     * @Query(key="username")
     * @Query(key="nickname")
     * @Query(key="department_id")
     * @Query(key="page")
     * @Query(key="limit")
     */
    public function list()
    {
        return $this->service->list($this->request->all());
    }

    /**
     * @PostApi(summary="账密登录", security=false)
     * @RequestValidation(rules={
     *     "username|账号": "require",
     *     "password|密码": "require",
     * })
     * @return mixed
     */
    public function login()
    {
        return $this->service->login($this->request->getParsedBody());
    }

    /**
     * @PostApi(summary="检测账户是否存在")
     * @RequestValidation(rules={
     *     "username|账号": "require"
     * })
     * @return mixed
     */
    public function exist()
    {
        return $this->service->exist($this->request->getParsedBody());
    }

    /**
     * @GetApi(summary="退出登录")
     * @return mixed
     */
    public function logout()
    {
        return $this->service->logout();
    }

    /**
     * @GetApi(summary="刷新token")
     * @return mixed
     */
    public function refresh()
    {
        return $this->service->refresh();
    }

    /**
     * @GetApi(summary="获取我的登录信息")
     */
    public function user_info()
    {
        return $this->service->userInfo();
    }

    /**
     * @GetApi(summary="获取我的登录信息")
     */
    public function permissions()
    {
        return $this->service->permissions();
    }

    /**
     * @GetApi(summary="获取菜单")
     */
    public function menus()
    {
        return $this->service->menus();
    }

    /**
     * @DeleteApi(path="{user_id:\d+}", summary="删除用户")
     * @Path(key="user_id")
     * @return mixed
     */
    public function delete(int $user_id)
    {
        return $this->service->delete($user_id);
    }
}
