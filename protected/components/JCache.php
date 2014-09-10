<?php
class JCache {
    public static function cache_all() {
        self::cache_category();
        return true;
    }

    public static function cache_category($moduleid = 0, $data = array()) {
        if ($moduleid) {
            if (!$data) {
                $result = $db->query("SELECT * FROM {$db->pre}category WHERE moduleid='$moduleid' ORDER BY listorder,catid");
                while ($r = $db->fetch_array($result)) {
                    $data[$r['catid']] = $r;
                }
            }
            $mod = cache_read('module-' . $moduleid . '.php');
            $a = array();
            $d = array(
                'listorder',
                'moduleid',
                'item',
                'template',
                'show_template',
                'seo_title',
                'seo_keywords',
                'seo_description',
                'group_list',
                'group_show',
                'group_add'
            );
            foreach ($data as $r) {
                $e = $r['catid'];
                foreach ($d as $_d) {
                    unset($r[$_d]);
                }
                $a[$e] = $r;
            };
            cache_write('category-' . $moduleid . '.php', $a);
            if (count($data) < 100) {
                $categorys = array();
                foreach ($data as $id => $cat) {
                    $categorys[$id] = array(
                        'id' => $id,
                        'parentid' => $cat['parentid'],
                        'name' => $cat['catname']
                    );
                }
                require_once DT_ROOT . '/include/tree.class.php';
                $tree = new tree;
                $tree->tree($categorys);
                $content = $tree->get_tree(0, "<option value=\\\"\$id\\\">\$spacer\$name</option>") . '</select>';
                cache_write('catetree-' . $moduleid . '.php', $content);
            } else {
                cache_delete('catetree-' . $moduleid . '.php');
            }
        } else {
            foreach ($MODULE as $moduleid => $module) {
                cache_category($moduleid);
            }
        }
    }
}
?>
