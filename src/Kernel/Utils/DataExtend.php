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
namespace UU\Admin\Kernel\Utils;

/**
 * 数据处理扩展
 * Class DataExtend.
 */
class DataExtend
{
    /**
     * 一维数组生成数据树.
     * @param array $list 待处理数据
     * @param string $cid 自己的主键
     * @param string $parent_id 上级的主键
     * @param string $children 子数组名称
     * @return array
     */
    public static function arr2tree(array $list, string $cid = 'id', string $parent_id = 'parent_id', string $children = 'children')
    {
        [$tree, $temp] = [[], array_combine(array_column($list, $cid), array_values($list))];
        foreach ($list as $vo) {
            if (isset($vo[$parent_id], $temp[$vo[$parent_id]])) {
                $temp[$vo[$parent_id]][$children][] = &$temp[$vo[$cid]];
            } else {
                $tree[] = &$temp[$vo[$cid]];
            }
        }
        return $tree;
    }

    public static function childrenInMenu(&$item)
    {
        if (isset($item['children'])) {
            $item['meta']['hideChildrenInMenu'] = self::isHideChildren($item['children']);
        }
        return $item;
    }

    public static function isHideChildren(&$items)
    {
        $count = count($items);
        $hiddenCount = [];
        foreach ($items as &$item) {
            if ($item['meta']['hideMenu'] == 1) {
                $hiddenCount[] = true;
            }
            $item = self::childrenInMenu($item);
        }
        return $count == count($hiddenCount);
    }

    /**
     * 一维数组生成数据树.
     * @param array $list 待处理数据
     * @param string $cid 自己的主键
     * @param string $parent_id 上级的主键
     * @param string $cpath 当前 PATH
     * @param string $ppath 上级 PATH
     */
    public static function arr2table(array $list, string $cid = 'id', string $parent_id = 'parent_id', string $cpath = 'path', string $ppath = ''): array
    {
        $tree = [];
        foreach (static::arr2tree($list, $cid, $parent_id) as $attr) {
            $attr[$cpath] = "{$ppath}-{$attr[$cid]}";
            $attr['sub'] = $attr['sub'] ?? [];
            $attr['spc'] = count($attr['sub']);
            $attr['spt'] = substr_count($ppath, '-');
            $attr['spl'] = str_repeat('　├　', $attr['spt']);
            $children = $attr['sub'];
            unset($attr['sub']);
            $tree[] = $attr;
            if (! empty($children)) {
                $tree = array_merge($tree, static::arr2table($children, $cid, $parent_id, $cpath, $attr[$cpath]));
            }
        }
        return $tree;
    }

    /**
     * 获取数据树子ID集合.
     * @param array $list 数据列表
     * @param mixed $value 起始有效ID值
     * @param string $ckey 当前主键ID名称
     * @param string $pkey 上级主键ID名称
     */
    public static function getArrSubIds(array $list, $value = 0, string $ckey = 'id', string $pkey = 'parent_id'): array
    {
        $ids = [intval($value)];
        foreach ($list as $vo) {
            if (intval($vo[$pkey]) > 0 && intval($vo[$pkey]) === intval($value)) {
                $ids = array_merge($ids, static::getArrSubIds($list, intval($vo[$ckey]), $ckey, $pkey));
            }
        }
        return $ids;
    }
}
