<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/9
 * Time: 16:58
 * 1.修改了/var/www/html/GoCms/gocms/userfiles/modules/admin/modules/list.php
if (1) {
// scan_for_modules($modules_options);
$el_params['no_cache'] = true;
$el_params['cleanup_db'] = true;
mw()->modules->scan_for_elements($el_params);
$modules = mw()->layouts_manager->get($el_params);
}
 *2.修改了 运行代码时候，删除数据，数据表字段不存在导致的问题
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */