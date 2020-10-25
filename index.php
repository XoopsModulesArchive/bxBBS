<?php

/**
 * @brief   BBS の一覧を表示する。BBS がひとつしか存在しない場合は、viewbbs.php へ遷移する
 * @version $Id: index.php,v 1.1 2006/02/22 15:34:51 mikhail Exp $
 */
include '../../mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

require_once './class/global.php';

require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';

$handler = &bxBBS::getHandler('bbs');
$objs = &$handler->getObjects(null, null, 'priority');

if (1 == count($objs)) {
    header('location: viewbbs.php?bbs_id=' . $objs[0]->getVar('bbs_id'));

    exit;
}

// View
$GLOBALS['xoopsOption']['template_main'] = 'bxbbs_index.html';
foreach ($objs as $obj) {
    $xoopsTpl->append('bbs', $obj->getStructure());
}

require XOOPS_ROOT_PATH . '/footer.php';
