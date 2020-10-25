<?php

/**
 * @brief   メッセージの投函（妥当性検査を終えたらすぐ confirm へ流すように書く）
 * @version $Id: regist.php,v 1.1 2006/02/22 15:34:51 mikhail Exp $
 */
include '../../mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require_once './class/global.php';
require_once './class/user.php';

require_once './include/MessageForm.php';

require_once 'exComponent/Input.php';

$compo = new exInputComponent(
    null,
    null,
    'edit_message',
    new MessageForm(),
    new exSuccessForwardConfig(EXFORWARD_LOCATION, 'confirm.php')
);

switch ($compo->initSelf()) {
    case ACTIONFORM_INIT_FAIL:
    case COMPONENT_INIT_FAIL:
        print $compo->form_->getHtmlErrors();
        $compo->display();
        break;
    case COMPONENT_INIT_SUCCESS:
        $compo->display();
        break;
}

require XOOPS_ROOT_PATH . '/footer.php';


