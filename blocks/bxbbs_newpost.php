<?php

/**
 * @version $Id: bxbbs_newpost.php,v 1.1 2006/02/22 15:34:53 mikhail Exp $
 */
require_once XOOPS_ROOT_PATH . '/modules/exFrame/frameloader.php';
require_once __DIR__ . '/../class/global.php';
require_once __DIR__ . '/../class/message.php';
require_once __DIR__ . '/../class/bbs.php';
require_once __DIR__ . '/../include/MessageFilter.php';

function b_bxbbs_newpost_show($options)
{
    $block = [];

    $block['fullmode'] = false;

    if ($options[1]) {
        $block['fullmode'] = true;
    }

    $filter = new bxBBSMessagePostFilter();

    $handler = &bxBBS::getHandler('message');

    $criteria = $filter->getCriteria();

    $criteria->setStart(0);

    $criteria->setLimit($options[0]);

    $objs = &$handler->getObjects($criteria);

    $bHandler = &bxBBS::getHandler('bbs');

    foreach ($objs as $obj) {
        $array = $obj->getStructure();

        $bbs = &$bHandler->get($obj->getVar('bbs_id'));

        $array['bbs'] = &$bbs->getStructure();

        $block['messages'][] = &$array;

        unset($array);
    }

    return $block;
}

function b_bxbbs_newpost_edit($options)
{
    $inputtag = "<input type='text' name='options[0]' value='" . $options[0] . "'>";

    $form = sprintf(_MB_BXBBS_DISPLAY, $inputtag);

    $form .= '<br>' . _MB_BXBBS_DISPLAYF . "&nbsp;<input type='radio' name='options[1]' value='1'";

    if (1 == $options[1]) {
        $form .= ' checked';
    }

    $form .= '>&nbsp;' . _YES . "<input type='radio' name='options[1]' value='0'";

    if (0 == $options[1]) {
        $form .= ' checked';
    }

    $form .= '>&nbsp;' . _NO;

    return $form;
}
