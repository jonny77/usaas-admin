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

use App\Exception\BusinessException;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Concerns\HasAttributes;
use Hyperf\Utils\Collection as BaseCollection;
use UU\Admin\Model\SystemConfig;
use UU\Contract\Exception\ApiException;

class ConfigService
{
    use HasAttributes;

    public function config($key = null)
    {
        $list = SystemConfig::query()->when($key, function ($db) use ($key) {
            $db->where('key', $key);
        })->where('parent_id', 0)->with('children')->get(['field', 'key', 'id', 'value', 'type'])->keyBy('key')->map(function ($config) {
            $items = [];
            foreach ($config->children->toArray() as $item) {
                $items[$item['field']] = $item['value']; //$this->castAttribute($item->field, $item->value, $item->type);
            }
            return $items;
        });
        return $key ? $list[$key] ?? [] : $list;
    }

    public function setConfig($config)
    {
        if (empty($config['key'])) {
            throw new BusinessException(403, '配置key必传');
        }
        foreach ($config as $key => $value) {
            SystemConfig::where('key', $config['key'])->updateOrCreate([
                'name' => $key,
                'key' => $config['key'],
            ], [
                'name' => $key,
                'key' => $config['key'],
                'value' => $value,
            ]);
        }
        $newConfig = [
            'key' => $config['key'],
        ];
        SystemConfig::where('key', $config['key'])->get(['key', 'name', 'value'])->map(function ($item) use (&$newConfig) {
            $newConfig[$item['name']] = $item['value'];
        });
        return $newConfig;
    }

    public function delete(int $id)
    {
        $info = $this->info($id);
        $info->delete();
        return [
            'message' => '删除成功',
        ];
    }

    public function configScript()
    {
        $system = $this->config('system');
        return 'window.__PRODUCTION__UU_ADMIN_KEY__CONF__={"VITE_GLOB_APP_TITLE":"' . $system['site_name'] . '","VITE_GLOB_APP_SHORT_NAME":"uu_admin_key","VITE_GLOB_API_URL":"' . $system['api_url'] . '","VITE_GLOB_UPLOAD_URL":"/upload","VITE_GLOB_API_URL_PREFIX":""};Object.freeze(window.__PRODUCTION__UU_ADMIN_KEY__CONF__);Object.defineProperty(window,"__PRODUCTION__UU_ADMIN_KEY__CONF__",{configurable:false,writable:false,});';
    }

    public function list($data = [])
    {
        $data['parent_id'] ??= 0;
        return SystemConfig::query()->where('parent_id', $data['parent_id'])->orderBy('order')->get(); //->with('children')
    }

    /**
     * @param $id
     * @throws ApiException
     * @return Builder|\Hyperf\Database\Model\Model|object
     */
    public function info($id)
    {
        $info = SystemConfig::query()->where('id', $id)->first();
        if (empty($info)) {
            throw new ApiException(400, '数据不存在');
        }
        return $info;
    }

    public function update(int $id, array $data)
    {
        $info = $this->info($id);
        $info->fill($data);
        $info->save();
        return $info;
    }

    public function configForm()
    {
        return SystemConfig::query()->with('children')->where('parent_id', 0)->get(['field', 'name', 'key', 'id'])->map(function ($config) {
            $items = $values = [];
            foreach ($config->children->toArray() as $itemConfig) {
                $item = [
                    'field' => $itemConfig['field'],
                    'label' => $itemConfig['name'],
                    'component' => $itemConfig['component'],
                    'required' => $itemConfig['required'] == 1,
                    'defaultValue' => $itemConfig['value'],
                ];
                if (! empty($itemConfig['options'])) {
                    $optionsTemp = explode("\n", $itemConfig['options']);
                    $options = [];
                    foreach ($optionsTemp as $optionTemp) {
                        [$value, $label] = explode('|', $optionTemp);
                        $options[] = compact('value', 'label');
                    }
                    $item['componentProps'] = [
                        'options' => $options,
                    ];
                }
                $values[$itemConfig['field']] = $itemConfig['value'];
                $items[] = $item;
                //$this->castAttribute($item->field, $item->value, $item->type);
            }
            return [
                'key' => $config->key,
                'name' => $config->name,
                'items' => $items,
                'values' => $values,
            ];
        });
    }

    public function updateConfig(string $key, array $data)
    {
        $id = SystemConfig::query()->where('key', $key)->value('id');
        if (! $id) {
            throw new ApiException(400, '配置不存在');
        }
        $keys = array_keys($data);
        $list = SystemConfig::query()->whereIn('field', $keys)->get(['field', 'id', 'value']);
        $configs = [];
        foreach ($list as $config) {
            if (isset($data[$config->field])) {
                $configs[$config->field] = $data[$config->field];
                $config->value = $data[$config->field];
                $config->save();
            }
        }
        return $configs;
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param string $key
     * @param mixed $value
     * @param mixed $castType
     */
    protected function castAttribute($key, $value, $castType)
    {
        if (is_null($value) && in_array($castType, static::$primitiveCastTypes)) {
            return $value;
        }
        switch ($castType) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return $this->fromFloat($value);
            case 'decimal':
                return $this->asDecimal($value, explode(':', $this->getCasts()[$key], 2)[1]);
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'object':
                return $this->fromJson($value, true);
            case 'array':
            case 'json':
                return $this->fromJson($value);
            case 'collection':
                return new BaseCollection($this->fromJson($value));
            case 'date':
                return $this->asDate($value);
            case 'datetime':
            case 'custom_datetime':
                return $this->asDateTime($value);
            case 'timestamp':
                return $this->asTimestamp($value);
        }
        return $value;
    }
}
