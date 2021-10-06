<?php

declare(strict_types=1);

/**
 * This file is UUOA of Hyperf.plus
 *
 * @link     https://www.hyperf.plus
 * @document https://doc.hyperf.plus
 * @contact  4213509@qq.com
 * @license  https://github.com/hyperf-plus/admin/blob/master/LICENSE
 */

use Qbhy\HyperfAuth\Provider\EloquentProvider;
use Qbhy\SimpleJwt\Encoders\Base64UrlSafeEncoder;
use Qbhy\SimpleJwt\EncryptAdapters\PasswordHashEncrypter;
use UU\Admin\Model\SystemUser;

return [
    'default' => [
        'guard' => 'jwt',
        'provider' => 'admin',
    ],
    'guards' => [ // 开发者可以在这里添加自己的 guard ，guard Qbhy\HyperfAuth\AuthGuard 接口
        'jwt' => [
            'driver' => Qbhy\HyperfAuth\Guard\JwtGuard::class,
            'provider' => 'admin',
            'secret' => env('JWT_SECRET', 'hyperf.plus'),
            'ttl' => 120, // 单位分钟
            'default' => PasswordHashEncrypter::class,
            'encoder' => new Base64UrlSafeEncoder(),
            'cache' => function () {
                return make(Qbhy\HyperfAuth\HyperfRedisCache::class);
            },
        ],

        'admin' => [
            'driver' => Qbhy\HyperfAuth\Guard\JwtGuard::class,
            'provider' => 'admin',
            'secret' => env('JWT_ADMIN_SECRET', 'hyperf.plus'),
            'ttl' => 120, // 单位分钟
            'default' => PasswordHashEncrypter::class,
            'encoder' => new Base64UrlSafeEncoder(),
            'cache' => function () {
                return make(Qbhy\HyperfAuth\HyperfRedisCache::class);
            },
        ],
    ],
    'providers' => [
        'admin' => [
            'driver' => EloquentProvider::class,
            'model' => SystemUser::class,
        ],
    ],
];
