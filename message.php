<?php

/**
 * @brief   メッセージ番号から rediret を行うプログラム
 * @version $Id: message.php,v 1.1 2006/02/22 15:34:51 mikhail Exp $
 */
require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require_once XOOPS_ROOT_PATH . '/header.php';

require_once './class/global.php';
require_once './include/MessageFilter.php';

$id = $_GET['id'] ?? 0;
if ($id <= 0) {
    redirect_header('./index.php', 1, '不正なリクエストです');
}

$handler = bxBBS::getHandler('message');
$message = &$handler->get($id);
if (!is_object($message)) {
    redirect_header('./index.php', 1, '不正なリクエストです');
}

// limit_page 取得
$bHandler = &bxBBS::getHandler('bbs');
$bbs = &$bHandler->get($message->getVar('bbs_id'));

$filter = new bxBBSUserMessageFilter();
$filter->bbs_id_ = $message->getVar('bbs_id');

$pid = ($message->getVar('parent')) ?: $message->getVar('id');

// サーチを行う
$tinfo = $message->getTableInfo();
$criteria = $filter->getCriteria();
$sql = 'SELECT * FROM ' . $xoopsDB->prefix($tinfo->tablenames_[0]) . ' ' . $criteria->renderWhere() . ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();

$res = $xoopsDB->query($sql);
$count = 0;
$parent = null;
while (false !== ($row = $xoopsDB->fetchArray($res))) {
    if ($row['id'] == $pid) {
        $parent = $handler->create();

        $parent->assignVars($row);

        break;
    }

    $count++;
}

if (is_object($parent)) {
    $perpage = $bbs->getVar('page_limit');

    $start = (int)($count / $perpage) * $perpage;

    header(
        'location: viewbbs.php?bbs_id=' . $parent->getVar('bbs_id') . '&start=' . $start . '#' . $message->getVar('id')
    );
} else {
    xoops_error('指定されたメッセージが見つかりません');
}

require_once XOOPS_ROOT_PATH . '/footer.php';
