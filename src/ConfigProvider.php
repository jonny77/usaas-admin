<?php

declare(strict_types=1);

namespace UU\Admin;


class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'admin',
                    'description' => 'uu-admin-config',
                    'source' => __DIR__ . '/../publish/admin.php',
                    'destination' => BASE_PATH . '/config/autoload/admin.php',
                ], [
                    'id' => 'auth',
                    'description' => 'uu-auth-config',
                    'source' => __DIR__ . '/../publish/auth.php',
                    'destination' => BASE_PATH . '/config/autoload/auth.php',
                ],
                [
                    'id' => 'migrate_system_department',
                    'description' => '部门表',
                    'source' => __DIR__ . '/../migrations/2021_03_16_211438_create_departments.php',
                    'destination' => BASE_PATH . '/migrations/2021_03_16_211438_create_departments.php',
                ],
                [
                    'id' => 'migrate_system_roles',
                    'description' => '角色表',
                    'source' => __DIR__ . '/../migrations/2021_03_19_214337_create_system_roles.php',
                    'destination' => BASE_PATH . '/migrations/2021_03_19_214337_create_system_roles.php',
                ],
                [
                    'id' => 'migrate_system_permissions',
                    'description' => '权限节点表',
                    'source' => __DIR__ . '/../migrations/2021_03_23_101932_create_system_permissions.php',
                    'destination' => BASE_PATH . '/migrations/2021_03_23_101932_create_system_permissions.php',
                ],
                [
                    'id' => 'migrate_system_users',
                    'description' => '用户角色表',
                    'source' => __DIR__ . '/../migrations/2021_03_23_102401_create_system_role_users.php',
                    'destination' => BASE_PATH . '/migrations/2021_03_23_102401_create_system_role_users.php',
                ],
                [
                    'id' => 'migrate_system_role_permissions',
                    'description' => '角色权限表',
                    'source' => __DIR__ . '/../migrations/2021_03_23_102309_create_system_role_permissions.php',
                    'destination' => BASE_PATH . '/migrations/2021_03_23_102309_create_system_role_permissions.php',
                ],
                [
                    'id' => 'migrate_corp_user_permission',
                    'description' => '用户权限表',
                    'source' => __DIR__ . '/../migrations/2021_03_23_162545_create_corp_user_permission.php',
                    'destination' => BASE_PATH . '/migrations/2021_03_23_162545_create_corp_user_permission.php',
                ],
                [
                    'id' => 'migrate_system_operation_log',
                    'description' => '操作日志表',
                    'source' => __DIR__ . '/../migrations/2021_08_27_142118_create_system_operation_log.php',
                    'destination' => BASE_PATH . '/migrations/2021_08_27_142118_create_system_operation_log.php',
                ],
                [
                    'id' => 'migrate_system_config',
                    'description' => '系统配置表',
                    'source' => __DIR__ . '/../migrations/2021_08_31_162249_create_system_config.php',
                    'destination' => BASE_PATH . '/migrations/2021_08_31_162249_create_system_config.php',
                ],
                [
                    'id' => 'migrate_rate_limiter',
                    'description' => '接口限流表',
                    'source' => __DIR__ . '/../migrations/2021_09_01_190429_create_rate_limiter_table.php',
                    'destination' => BASE_PATH . '/migrations/2021_09_01_190429_create_rate_limiter_table.php',
                ],
                [
                    'id' => 'migrate_models',
                    'description' => '模型管理表',
                    'source' => __DIR__ . '/../migrations/2021_09_06_160916_create_models_table.php',
                    'destination' => BASE_PATH . '/migrations/2021_09_06_160916_create_models_table.php',
                ],
                [
                    'id' => 'migrate_userss',
                    'description' => '用户表',
                    'source' => __DIR__ . '/../migrations/2021_10_06_143241_create_system_users_table.php',
                    'destination' => BASE_PATH . '/migrations/2021_10_06_143241_create_system_users_table.php',
                ],
                [
                    'id' => 'migrate_tenants',
                    'description' => '租户表',
                    'source' => __DIR__ . '/../migrations/2021_10_07_164450_create_tenants_table.php',
                    'destination' => BASE_PATH . '/migrations/2021_10_07_164450_create_tenants_table.php',
                ],
            ],
        ];
    }
}
