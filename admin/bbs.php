<?php

/**
 * @brief   BBS ¤Î¿·µ¬¡¦ÊÔ½¸
 * @version $Id: bbs.php,v 1.1 2006/02/22 15:34:52 mikhail Exp $
 */
include '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require XOOPS_ROOT_PATH . '/include/cp_header.php';
require_once '../class/global.php';

require_once './include/AdminBBSEditForm.php';

require_once 'exComponent/Input.php';

$compo = new exInputComponent(
    null,
    new AdminBBSInputComponentRender(),
    'edit_bbs',
    new AdminBBSEditForm(),
    new exSuccessForwardConfig(EXFORWARD_LOCATION, 'bbs_confirm.php')
);

switch ($compo->initSelf()) {
    case COMPONENT_INIT_FAIL:
        die;
        break;
    case ACTIONFORM_INIT_FAIL:
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


