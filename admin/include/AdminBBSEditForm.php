<?php

/**
 * @file
 * @brief   管理者用のBBS編集アクションフォーム
 * @version $Id: AdminBBSEditForm.php,v 1.1 2006/02/22 15:34:52 mikhail Exp $
 */
require_once 'exForm/Form.php';
require_once 'exComponent/Input.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

class AdminBBSEditForm extends exAbstractActionForm
{
    public function doGet($data)
    {
        $handler = bxBBS::getHandler('bbs');

        $id = $this->getPositive($_REQUEST['bbs_id']);

        // 探す

        if ($id > 0) {
            $this->data_ = $handler->get($id);

            if (!is_object($this->data_)) {
                $this->data_ = $handler->create();
            }
        } else {
            $this->data_ = $handler->create();
        }
    }

    public function doPost($data)
    {
        global $xoopsUser;

        $this->doGet($data);

        $this->data_->setVar('title', $_POST['title']);

        $this->data_->setVar('ex', $_POST['ex']);

        $this->data_->setVar('priority', $this->getPositive($_POST['priority']));

        $page_limit = $this->getPositive($_POST['page_limit']);

        if ($page_limit < 1) {
            $this->msg_[] = 'page_limitの指定が不正です';
        } else {
            $this->data_->setVar('page_limit', $page_limit);
        }

        $this->data_->setVar('ex', $_POST['ex']);

        if (!$this->data_->cleanVars()) {
            $this->msg_ = array_merge($this->msg_, $this->data_->getErrors());
        }
    }
}

class AdminBBSInputComponentRender extends exInputComponentRender
{
    public function render()
    {
        $form = new XoopsThemeForm('BBS', 'bbs', $_SERVER['SCRIPT_NAME'], 'POST');

        $form->addElement(new XoopsFormHidden('bbs_id', $this->component_->form_->data_->getVar('bbs_id')));

        $form->addElement(new XoopsFormText('TITLE', 'title', 60, 60, $this->component_->form_->data_->getVar('title', 'e')));

        $form->addElement(new XoopsFormDhtmlTextArea('EX', 'ex', $this->component_->form_->data_->getVar('ex', 'e')));

        $form->addElement(new XoopsFormText('PRIORITY', 'priority', 2, 2, $this->component_->form_->data_->getVar('priority')));

        $form->addElement(new XoopsFormText('PERPAGE', 'page_limit', 2, 2, $this->component_->form_->data_->getVar('page_limit')));

        $form->addElement(new XoopsFormButton('', 'submit', _MD_A_BXBBS_LANG_REGIST, 'submit'));

        $form->display();
    }
}
