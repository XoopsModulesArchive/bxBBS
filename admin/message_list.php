<?php

/**
 * @brief   メッセージ の一覧表示
 * @version $Id: message_list.php,v 1.1 2006/02/22 15:34:52 mikhail Exp $
 */
include '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require XOOPS_ROOT_PATH . '/include/cp_header.php';
require_once '../class/global.php';

require_once './include/AdminMessageFilter.php';

$table = new exListTableComponent(null, new AdminListTableRender());

switch ($table->init(new AdminMessageTableModel())) {
    case COMPONENT_INIT_FAIL:
        die;
        break;
    case COMPONENT_INIT_SUCCESS:
        xoops_cp_header();
        include './head.php';
        $table->display();
        xoops_cp_footer();
        break;
}
