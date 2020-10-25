<?php

$modversion['name'] = 'bxBBS';
$modversion['version'] = 0.04;
$modversion['description'] = 'basix BBS';

$modversion['credits'] = 'minahito';
$modversion['author'] = '';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 0;
$modversion['image'] = 'images/bxBBS.gif';
$modversion['dirname'] = 'bxBBS';

// Template
$modversion['templates'][0]['file'] = 'bxbbs_index.html';
$modversion['templates'][0]['description'] = '';
$modversion['templates'][1]['file'] = 'bxbbs_forum.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'bxbbs_item.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'bxbbs_form.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'bxbbs_revice.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'bxbbs_howto.html';
$modversion['templates'][5]['description'] = '';

// Block
$modversion['blocks'][1]['file'] = 'bxbbs_newpost.php';
$modversion['blocks'][1]['name'] = _MI_BXBBS_BNAME1;
$modversion['blocks'][1]['show_func'] = 'b_bxbbs_newpost_show';
$modversion['blocks'][1]['options'] = '10|0';
$modversion['blocks'][1]['edit_func'] = 'b_bxbbs_newpost_edit';
$modversion['blocks'][1]['template'] = 'bxbbs_block_newpost.html';

// Sql
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'][0] = 'bxbbs_message';
$modversion['tables'][1] = 'bxbbs_bbs';

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'bxbbs_search';

// use Smarty
$modversion['use_smarty'] = 1;

// Admin
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

$modversion['config'][1]['name'] = 'post_increment';
$modversion['config'][1]['title'] = '_MI_BXBBS_POSTINCREMENT';
$modversion['config'][1]['description'] = '_MI_BXBBS_POSTINCREMENT_DESC';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 0;

// Menu
$modversion['hasMain'] = 1;
// global $xoopsDB, $_POST, $_GET;
global $xoopsDB;
if (isset($xoopsDB)) {
    $res = $xoopsDB->query('select * from ' . $xoopsDB->prefix('bxbbs_bbs') . ' order by priority');

    $num = $xoopsDB->getRowsNum($res);

    if ($num > 1) {
        $i = 0;

        while (false !== ($myrow = $xoopsDB->fetchArray($res))) {
            //			if ( $_POST['bbs_id'] == $myrow['bbs_id'] || $_GET['bbs_id'] == $myrow['bbs_id'] ) {

            //				$modversion['sub'][$i]['name'] = "<b>".$myrow['title']."</b>";

            //			} else {

            $modversion['sub'][$i]['name'] = $myrow['title'];

            //			}

            $modversion['sub'][$i++]['url'] = 'viewbbs.php?bbs_id=' . $myrow['bbs_id'];
        }
    }
}
