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

namespace UU\Admin\Commands;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Utils\Collection;
use Hyperf\DbConnection\Db;
use UU\Admin\Model\SystemUser;

/**
 * Class InstallCommand.
 * @Command
 */
class InstallCommand extends HyperfCommand
{
    protected $name = 'usaas:install';

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    public function handle()
    {
        if (empty(config('server.settings.document_root'))) {
            $this->error('您未设置“静态资源” 目录，请根据文档配置 https://hyperf.wiki/2.2/#/zh-cn/view?id=%e9%85%8d%e7%bd%ae%e9%9d%99%e6%80%81%e8%b5%84%e6%ba%90');
            return 0;
        }
        if ($this->confirm('是否运行数据库文件？', true)) {
            $result = [];
            exec('php bin/hyperf.php vendor:publish uu/admin && php bin/hyperf.php migrate', $result);
            $this->table(['序号', '文件', '备注'], $this->getData($result));
        }
        if ($this->confirm('是否填充基础数据？', true)) {
            $sql = file_get_contents(__DIR__ . '/stubs/data.sql');
            try {
                #替换前缀
                $prefix = $this->ask('设置数据库前缀', config('databases.default.prefix'));
                $sql = str_replace('INSERT INTO `system_', 'INSERT INTO `' . $prefix . 'system_', $sql);
                Db::connection('default')->getPdo()->exec($sql);
            } catch (\Throwable $exception) {
                $this->error($exception->getMessage());
            }
        }

        #设置默认后台密码
        $password = $this->ask('设置admin用户密码', '123456');
        $userInfo = SystemUser::query()->where('username', 'admin')->first();
        $userInfo->fill(['password' => $password])->save();

        if ($this->confirm('是否开启SaaS模式？', false)) {
            $this->gen('#是否开启租户');
            $this->gen('TENANT_ENABLE', 'true');
            $this->gen('#数据隔离类型，database 目前仅支持数据库隔离');
            $this->gen('TENANT_TYPE', 'database');

            $this->info('数据隔离类型，database 目前仅支持数据库隔离');
        }
        #生成JWT秘钥
        $jwt = $this->ask('请设置32位JWT秘钥，按回车自动生成。', str_random(32));
        $this->gen('JWT_ADMIN_SECRET', $jwt);

        $this->info('恭喜您，安装成功！用户名：admin 密码：' . $password);
    }

    protected function configure()
    {
        $this->setDescription('install usaas-admin command.');
    }

    private function getData($result)
    {
        return Collection::make($result)
            ->map(function ($migration, $key) {
                $migration = str_replace(BASE_PATH, ' .', $migration);
                return ['<info>' . ($key + 1) . '</info>', $migration, ''];
            });
    }

    private function gen($key, string $value = null)
    {
        if (empty($value)) {
            file_put_contents(BASE_PATH . '/.env', sprintf(PHP_EOL . '%s', $key), FILE_APPEND);
            return;
        }
        if (empty(env($key))) {
            file_put_contents(BASE_PATH . '/.env', sprintf(PHP_EOL . '%s=%s', $key, $value ?? str_random(32)), FILE_APPEND);
            $this->info($key . ' 已生成!');
        } else {
            $this->info($key . ' 已存在!');
        }
    }
}
