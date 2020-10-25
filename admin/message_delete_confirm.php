<?php

/**
 * @brief   メッセージの一括削除確認
 * @version $Id: message_delete_confirm.php,v 1.1 2006/02/22 15:34:52 mikhail Exp $
 */
$xoopsOption['nocommon'] = true;
require_once '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require_once '../class/global.php';

// Form を読み込む
require_once 'exComponent/DeleteConfirm.php';
require_once './include/AdminMessageDelete.php';

// Session 開始
require_once XOOPS_ROOT_PATH . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/include/cp_header.php';

$editform = &session::get('delete_message');

// 画面遷移設定
$forwards = [
    new exSuccessForwardConfig(EXFORWARD_REDIRECT, 'index.php', '削除を行いました'),
    new exFailForwardConfig(EXFORWARD_REDIRECT, 'index.php', '削除に失敗しました'),
];

$compo = new exDeleteConfirmTypicalComponent(
    new AdminMessageDeleteProcessor(),
    null,
    'delete_message',
    new exConfirmTicketForm(),
    $forwards
);

switch ($compo->init($editform, '指定したメッセージを削除しますか?')) {
    case COMPONENT_INIT_FAIL:
        die;

    case COMPONENT_INIT_SUCCESS:
        xoops_cp_header();
        $compo->display();
        xoops_cp_footer();
        break;
}
