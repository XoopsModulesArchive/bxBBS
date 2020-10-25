<?php

/**
 * @brief   選択された BBS を表示する
 * @version $Id: viewbbs.php,v 1.1 2006/02/22 15:34:51 mikhail Exp $
 */
include '../../mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';

require_once './class/global.php';
require_once './class/user.php';    // ここで読み込んでおく

require_once 'exForm/Form.php';

require_once './include/bxBBSListController.php';
require_once './include/MessageFilter.php';

$filter = new bxBBSUserMessageFilter();
if ($filter->isError()) {
    exFrame::fatal(__FILE__, __LINE__, $filter->msg_);
}

$handler = &bxBBS::getHandler('bbs');
$bbs = &$handler->get($filter->bbs_id_);
if (!is_object($bbs)) {
    redirect_header('index.php', 1, _MD_BXBBS_ERROR_BBS_NOEXISTS2);
}

// テンプレートのためのユーザー
$user = new bxBBSUser($xoopsUser);
$cookieUser = new bxBBSCookieUser();
$cookieUser->getCookie();
$user->cookieUser_ = &$cookieUser;

// テンプレートのための新しいメッセージ定義
$mHandler = &bxBBS::getHandler('message');
$post = $mHandler->create();
$post->setDefault($user->cookieUser_);

// 返信の場合は ID が飛んできているので調べる
$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $parent = $mHandler->get($id);

    if (is_object($parent)) {
        if ($parent->getVar('bbs_id') == $filter->bbs_id_) {    // 同じ掲示板じゃないと無効
            $post->setVar('title', 'Re: ' . $parent->getVar('title'));

            $tmp = explode("\n", $parent->getVar('message', 'n'));

            $str = '';

            foreach ($tmp as $t) {
                $str .= '> ' . $t . "\n";
            }

            $post->setVar('message', $str);
        }
    }
}

$listController = new bxBBSListController();
$listController->filter_ = &$filter;
$listController->fetch($mHandler->getCount($filter->getCriteria()), $bbs->getVar('page_limit'));

$messages = &$mHandler->getObjects($listController->getCriteria());

// View
$GLOBALS['xoopsOption']['template_main'] = 'bxbbs_forum.html';
$xoopsTpl->assign('bbs', $bbs->getStructure());
$xoopsTpl->assign('bbsUser', $user->getStructure());
$xoopsTpl->assign('post', $post->getStructure('e'));
foreach ($messages as $message) {
    $xoopsTpl->append('messages', $message->getStructure());
}
$listController->freeze();
$xoopsTpl->assign('navi', $listController->getStructure());

require XOOPS_ROOT_PATH . '/footer.php';
