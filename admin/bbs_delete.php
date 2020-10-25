<?php

/**
 * @brief   指定された BBS_ID の BBS を削除します
 * @author  minahito
 * @version $Id: bbs_delete.php,v 1.1 2006/02/22 15:34:52 mikhail Exp $
 */
$xoopsOption['nocommon'] = true;
require_once '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require_once '../class/global.php';

// Form を読み込む
require_once 'exComponent/DeleteConfirm.php';
require_once './include/AdminBBSDelete.php';

// Session 開始
require_once XOOPS_ROOT_PATH . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/include/cp_header.php';

// リクエストは一種類なので、ActionForm を使用せずにこの場で処理を行う
$bbs_id = exAbstractActionForm::getPositive($_REQUEST['bbs_id']);
$handler = &bxBBS::getHandler('bbs');
$bbs = &$handler->get($bbs_id);

if (!is_object($bbs)) {
    redirect_header('index.php', 1, '不正な要求です');
}

// 画面遷移設定
$forwards = [
    new exSuccessForwardConfig(EXFORWARD_REDIRECT, 'index.php', '削除を行いました'),
    new exFailForwardConfig(EXFORWARD_REDIRECT, 'index.php', '削除に失敗しました'),
];

$compo = new exDeleteConfirmTypicalComponent(
    new AmindBBSDeleteProcessor(),
    null,
    'bbs_delete',
    new exObjectConfirmTicketForm(),
    $forwards
);

switch ($compo->init($bbs, 'このBBSを削除しますか?')) {
    case COMPONENT_INIT_FAIL:
        die;

    case COMPONENT_INIT_SUCCESS:
        xoops_cp_header();
        $compo->display();
        xoops_cp_footer();
        break;
}
