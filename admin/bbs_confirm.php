<?php

/**
 * @file
 * @brief   BBS投稿時の確認 & CSRF ガード
 * @author  minahito
 * @version $Id: bbs_confirm.php,v 1.1 2006/02/22 15:34:52 mikhail Exp $
 */
$xoopsOption['nocommon'] = true;
require_once '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require_once '../class/global.php';

// Form を読み込む
require_once 'exComponent/Preview.php';

// Session からの復元に必要
require_once './include/AdminBBSEditForm.php';
require_once '../class/bbs.php';

// session_start
require_once XOOPS_ROOT_PATH . '/include/common.php';
require XOOPS_ROOT_PATH . '/include/cp_header.php';

$compo = new exPreviewComponent(null, null, 'edit_bbs');

switch ($ret = $compo->init()) {
    case COMPONENT_INIT_FAIL:
        die;
        break;
    case ACTIONFORM_POST_SUCCESS:
        $handler = bxBBS::getHandler('bbs');
        $editform = &session::get($compo->name_);
        $editform->data_->setDirty();
        if ($handler->insert($editform->data_)) {
            session::unregister($compo->name_);

            redirect_header('./index.php', 1, 'データーベースを更新しました');
        } else {
            print 'Fatal::データーベースへの書き込みに失敗しました';
        }
        break;
    case ACTIONFORM_INIT_FAIL:
    case ACTIONFORM_INIT_SUCCESS:
    case COMPONENT_INIT_SUCCESS:
        xoops_cp_header();
        $compo->display();
        xoops_cp_footer();
        break;
}
