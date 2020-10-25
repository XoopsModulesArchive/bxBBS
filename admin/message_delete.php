<?php

/**
 * @brief   メッセージの削除（妥当性検査を終えたらすぐ confirm へ流すように書く）
 * @version $Id: message_delete.php,v 1.1 2006/02/22 15:34:52 mikhail Exp $
 */
include '../../../mainfile.php';
require XOOPS_ROOT_PATH . '/include/cp_header.php';

require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require_once '../class/global.php';

require_once './include/AdminMessageDelete.php';

require_once 'exComponent/Input.php';

$compo = new exInputComponent(
    null,
    null,
    'delete_message',
    new AdminDeleteMessageForm(),
    new exSuccessForwardConfig(EXFORWARD_LOCATION, 'message_delete_confirm.php')
);

switch ($compo->initSelf()) {
    case ACTIONFORM_INIT_FAIL:
    case COMPONENT_INIT_FAIL:
        print $compo->form_->getHtmlErrors();
        $compo->display();
        break;
    case COMPONENT_INIT_SUCCESS:
        xoops_cp_header();
        include './head.php';
        $compo->display();
        xoops_cp_footer();
        break;
}

require XOOPS_ROOT_PATH . '/footer.php';


