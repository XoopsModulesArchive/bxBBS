<?php

/**
 * @brief   メッセージ削除の確認と実行
 * @version $Id: delete.php,v 1.1 2006/02/22 15:34:51 mikhail Exp $
 */
$xoopsOption['nocommon'] = true;
require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require_once './class/global.php';

// Form を読み込む
require_once 'exComponent/DeleteConfirm.php';
require_once 'exComponent/render/ConfirmModelRender.php';
require_once './include/MessageConfirm.php';
require_once './include/MessageDelete.php';

// Session からの復元に必要
// このへんは require 多すぎる。何とかしなければ…… Modules に依存せず場所を決める必要がある
require_once './include/ReviceForm.php';
require_once './class/bbs.php';
require_once './class/message.php';
require_once './class/user.php';

// session_start
require_once XOOPS_ROOT_PATH . '/include/common.php';
require XOOPS_ROOT_PATH . '/header.php';

$editform = &session::get('delete_message');

// 前画面で権限は検査済みなので、
// 画面遷移設定
$forwards = [
    new exSuccessForwardConfig(EXFORWARD_REDIRECT, 'viewbbs.php?bbs_id=' . $editform->data_->getVar('bbs_id'), '削除を行いました'),
    new exFailForwardConfig(EXFORWARD_REDIRECT, 'viewbbs.php?bbs_id=' . $editform->data_->getVar('bbs_id'), '削除に失敗しました'),
];

$compo = new exDeleteConfirmTypicalComponent(
    new MessageDeleteProcessor(),
    new exConrimComponentModelRender(new MessageDeleteConfirmRenderModel()),
    'bbs_delete',
    new exBeanConfirmTicketForm(),
    $forwards
);

switch ($compo->init($editform, '')) {
    case COMPONENT_INIT_FAIL:
        die;

    case COMPONENT_INIT_SUCCESS:
        $compo->display();
        break;
}

require_once XOOPS_ROOT_PATH . '/footer.php';
