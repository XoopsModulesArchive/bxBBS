<?php

/**
 * @brief   指定された ID の記事の修正
 * @version $Id: revi.php,v 1.1 2006/02/22 15:34:51 mikhail Exp $
 */
include '../../mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';

require_once './class/global.php';
require_once './class/user.php';    // ここで読み込んでおく

require_once 'exForm/Form.php';
require_once './include/ReviceForm.php';

// テンプレートのためのユーザー
$user = new bxBBSUser($xoopsUser);
$cookieUser = new bxBBSCookieUser();
$cookieUser->getCookie();
$user->cookieUser_ = &$cookieUser;

$form = new ReviceForm();
switch ($form->init()) {
    case ACTIONFORM_INIT_FAIL:
    case ACTIONFORM_INIT_SUCCESS:    // このリターンはありえないが
        print $form->getHtmlErrors();
        break;
    case ACTIONFORM_POST_SUCCESS:
        if ('delete' == $_POST['mode']) {
            session::register('delete_message', $form);

            header('location: delete.php');

            exit;
        }

        // FIXME:: うまくない
        $form->data_->setVar('passwd', $_POST['passwd']);

        $GLOBALS['xoopsOption']['template_main'] = 'bxbbs_revice.html';

        $xoopsTpl->assign('head_message', _MD_BXBBS_REVICE_HEADMES);

        $xoopsTpl->assign('bbs', $form->bbs_->getStructure());
        $xoopsTpl->assign('bbsUser', $user->getStructure());
        $xoopsTpl->assign('post', $form->data_->getStructure('e'));

        break;
}

require XOOPS_ROOT_PATH . '/footer.php';
