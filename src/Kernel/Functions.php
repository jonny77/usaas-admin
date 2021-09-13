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
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\JobInterface;
use Hyperf\Contract\IdGeneratorInterface;
use Hyperf\DbConnection\Db;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\SimpleCache\CacheInterface;
use Qbhy\HyperfAuth\AuthManager;
use UU\Admin\Kernel\Utils\QueryPage;

if (! function_exists('di')) {
    /**
     * Finds an entry of the container by its identifier and returns it.
     * @param null|string $id
     * @return ContainerInterface|mixed
     */
    function di($id = null)
    {
        $container = ApplicationContext::getContainer();
        if ($id) {
            return $container->get($id);
        }

        return $container;
    }
}

if (! function_exists('format_throwable')) {
    /**
     * Format a throwable to string.
     */
    function format_throwable(Throwable $throwable): string
    {
        return di()->get(FormatterInterface::class)->format($throwable);
    }
}

if (! function_exists('queue_push')) {
    /**
     * Push a job to async queue.
     */
    function queue_push(JobInterface $job, int $delay = 0, string $key = 'default'): bool
    {
        $driver = di()->get(DriverFactory::class)->get($key);
        return $driver->push($job, $delay);
    }
}

if (! function_exists('url_add_query')) {
    function url_add_query($url, $key, $value)
    {
        $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);
        if (! str_contains($url, '?')) {
            return $url . '?' . $key . '=' . $value;
        }
        return $url . '&' . $key . '=' . $value;
    }
}

if (! function_exists('cache')) {
    function cache(): CacheInterface
    {
        return ApplicationContext::getContainer()->get(CacheInterface::class);
    }
}

if (! function_exists('cache_has_set')) {
    function cache_has_set(string $key, $callback, $tll = 3600)
    {
        $data = cache()->get($key);
        if ($data || $data === false) {
            return $data;
        }
        $data = call_user_func($callback);
        if ($data === null) {
            p('设置空缓存防止穿透');
            cache()->set($key, false, 10);
        } else {
            cache()->set($key, $data, $tll);
        }
        return $data;
    }
}

if (! function_exists('array_filter_null')) {
    /**
     * 过滤空数据.
     *
     * @param $arr
     *
     * @return array
     */
    function array_filter_null($arr)
    {
        return array_filter($arr, function ($item) {
            if ($item === '' || $item === null) {
                return false;
            }
            return true;
        });
    }
}

if (! function_exists('make_openid')) {
    /*
 * 分布式全局唯一ID 生成器
 *  Twitter 提出的一个分布式全局唯一算法
 */
    function make_openid()
    {
        $container = ApplicationContext::getContainer();
        $generator = $container->get(IdGeneratorInterface::class);
        $charId = md5('open' . $generator->generate());
        for ($i = 0; $i < 32; ++$i) {
            if (rand(0, 10) > 5) {
                $charId[$i] = ucfirst($charId[$i]);
            }
        }
        return $charId;
    }
}

if (! function_exists('is_valid_url')) {
    function is_valid_url($url)
    {
        $check = 0;
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $check = 1;
        }
        return $check;
    }
}

if (! function_exists('str_random')) {
    function str_random($num = 6): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $num; ++$i) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}

if (! function_exists('get_user_info')) {
    /**
     * 获取当前应用调用方身份。通过账户密码获取的.
     */
    function get_user_info()
    {
        #存到上下文
        return Context::getOrSet('user_info', function () {
            return di(AuthManager::class)->guard('users')->user();
        });
    }
}

if (! function_exists('get_admin_info')) {
    /**
     * 获取当前应用调用方身份。通过账户密码获取的.
     */
    function get_admin_info()
    {
//        return \UU\Admin\Model\SystemUser::first();
        #存到上下文
        return Context::getOrSet('get_admin_info', function () {
            return di(AuthManager::class)->guard('admin')->user();
        });
    }
}

if (! function_exists('get_admin_id')) {
    /**
     * 获取当前应用调用方身份。通过账户密码获取的.
     */
    function get_admin_id(): int
    {
        #存到上下文
        return Context::getOrSet('get_admin_id', function () {
            try {
                $userId = auth('admin')->user()->getId();
            } catch (\Throwable $exception) {
                p('获取用户ID失败' . $exception->getMessage());
                $userId = 0;
            }
            return $userId;
        });
    }
}

if (! function_exists('get_user_id')) {
    /**
     * 获取当前应用调用方身份。通过账户密码获取的.
     */
    function get_user_id(): int
    {
        #存到上下文
        return Context::getOrSet('get_user_id', function () {
            return di(AuthManager::class)->guard('users')->user()->getId();
        });
    }
}

if (! function_exists('is_super_administrator')) {
    /**
     * 获取当前应用调用方身份。通过账户密码获取的.
     */
    function is_super_administrator(): bool
    {
        #todo 2021年7月29日11:45:06
        return false;
    }
}

/*
 * 分布式全局唯一ID 生成器
 *  Twitter 提出的一个分布式全局唯一算法
 */
if (! function_exists('get_guid')) {
    function get_guid($session = true)
    {
        $container = ApplicationContext::getContainer();
        $generator = $container->get(IdGeneratorInterface::class);
        $gid = md5('cookie' . $generator->generate());
        if (! $session) {
            return $gid;
        }
        $charId = strtoupper($gid);
        $hyphen = chr(45); // "-"
        return substr($charId, 0, 8) . $hyphen
            . substr($charId, 8, 4) . $hyphen
            . substr($charId, 12, 4) . $hyphen
            . substr($charId, 16, 4) . $hyphen
            . substr($charId, 20, 12);
    }
}

if (! function_exists('page')) {
    /**
     * 分页查询助手.
     *
     * @param $query
     * @param $data
     */
    function page($query, $data): QueryPage
    {
        return (new QueryPage())->setQuery($query)->setData($data);
    }
}

/*
 * 派发事件
 */
if (! function_exists('event_dispatch')) {
    function event_dispatch(object $object)
    {
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($object);
    }
}

if (! function_exists('get_rate_limit')) {
    /**
     * 获取当前应用调用方身份。通过账户密码获取的.
     * @return int
     */
    function get_rate_limit(): array
    {
        #存到Redis
        //cache()->delete('get_rate_limit');
        return cache_has_set('get_rate_limit', function () {
            return Db::table('system_rate_limiter')->where('status', 1)->get(['count', 'request_uri', 'request_method', 'ttl', 'limit_start_time', 'limit_end_time'])->toArray();
        });
    }
}
