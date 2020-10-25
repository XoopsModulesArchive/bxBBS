<?php

/**
 * @file
 * @brief   メッセージ投稿時の確認 & CSRF ガード
 * @author  minahito
 * @version $Id: confirm.php,v 1.1 2006/02/22 15:34:51 mikhail Exp $
 */
$xoopsOption['nocommon'] = true;
require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require_once './class/global.php';

// Form を読み込む
require_once 'exComponent/Preview.php';
require_once 'exComponent/render/ConfirmModelRender.php';
require_once './include/MessageConfirm.php';

// Session からの復元に必要
require_once './include/MessageForm.php';
require_once './class/user.php';
require_once './class/message.php';

// session_start
require_once XOOPS_ROOT_PATH . '/include/common.php';
require XOOPS_ROOT_PATH . '/header.php';

$compo = new exPreviewComponent(
    null,
    new exConrimComponentModelRender(new MessageConfirmRenderModel()),
    'edit_message'
);

switch ($ret = $compo->init()) {
    case COMPONENT_INIT_FAIL:
        die;
        break;
    case ACTIONFORM_POST_SUCCESS:
        $handler = &bxBBS::getHandler('message');
        $editform = &session::get($compo->name_);
        $isNew = $editform->data_->isNew();
        $editform->data_->setDirty();
        if ($handler->insert($editform->data_)) {
            if ($isNew) {
                // increment 処理

                if ($xoopsModuleConfig['post_increment'] && is_object($xoopsUser)) {
                    $xoopsUser->incrementPost();
                }

                // 続いて BBS を修正する

                $handler = &bxBBS::getHandler('bbs');

                $bbs = &$handler->get($editform->data_->getVar('bbs_id'));

                $bbs->setVar('serial', $bbs->getVar('serial') + 1);

                $handler->insert($bbs);
            }

            // cookie

            $editform->cookieUser_->setCookie();

            redirect_header('./viewbbs.php?bbs_id=' . $editform->data_->getVar('bbs_id'), 1, _MD_BXBBS_POST_SUCCESS);
        } else {
            redirect_header('./viewbbs.php?bbs_id=' . $editform->data_->getVar('bbs_id'), 3, _MD_BXBBS_POST_FAIL);
        }
        break;
    case ACTIONFORM_INIT_FAIL:    // これはフェータル
    case ACTIONFORM_INIT_SUCCESS:
    case COMPONENT_INIT_SUCCESS:
        $compo->display();
        break;
}

require XOOPS_ROOT_PATH . '/footer.php';
