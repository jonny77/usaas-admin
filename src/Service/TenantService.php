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

use App\Model\SystemTenant;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Database\Model\Builder;
use UU\Admin\Model\SystemRateLimiter;
use UU\Contract\Exception\ApiException;
use UU\Contract\Exception\BusinessException;

class TenantService
{
    /**
     * @Cacheable(prefix="info_by_domain_#domain")
     * @param string $domain
     */
    public static function infoByDomain($domain = '')
    {
        [$prefix] = explode('.', $domain) ?? [''];
        if (in_array($prefix, ['www', 'localhost', '127', '192', '10', 'admin', 'm', 'web', 'wap'])) {
            return 0;
        }
        $tenant = SystemTenant::where('domain', $prefix)->first();
        if (empty($tenant)) {
            throw new BusinessException('租户不存在！');
        }
        if (strtotime($tenant->end_time ?? '') <= time()) {
            throw new BusinessException('租户已过期，请及时续费！');
        }
        return $tenant;
    }
}
