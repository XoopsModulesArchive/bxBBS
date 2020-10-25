<?php

/**
 * @brief   BBS の一覧表示
 * @version $Id: index.php,v 1.1 2006/02/22 15:34:52 mikhail Exp $
 */
include '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require XOOPS_ROOT_PATH . '/include/cp_header.php';
require_once '../class/global.php';

require_once 'exComponent/Table.php';

class AdminBBSTableModel extends exTableModel
{
    public $_column_ = ['BBS_ID', 'TITLE', 'EX', 'ACTION'];

    public function init()
    {
        $handler = bxBBS::getHandler('bbs');

        $bbs = $handler->getObjects(null, null, 'priority');

        foreach ($bbs as $b) {
            $this->_row_data_[] = $b->getStructure();
        }

        return COMPONENT_MODEL_INIT_SUCCESS;
    }

    public function getValueAtAction($arr)
    {
        $ret = @sprintf("<a href='bbs.php?bbs_id=%u'>EDIT</a> | ", $arr['bbs_id']) . @sprintf("<a href='message_list.php?bbs_id=%u'>MESSAGE</a> | ", $arr['bbs_id']) . @sprintf("<a href='bbs_delete.php?bbs_id=%u'>DELETE</a>", $arr['bbs_id']);

        return $ret;
    }
}

$table = new exTableComponent();

switch ($table->init(new AdminBBSTableModel())) {
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
