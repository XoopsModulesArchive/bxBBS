<?php

/**
 * @brief   メッセージ投稿・編集フォーム
 * @version $Id: MessageForm.php,v 1.1 2006/02/22 15:34:54 mikhail Exp $
 */
require_once 'exForm/Form.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

class MessageForm extends exAbstractActionForm
{
    public $bbs_;

    public $cookieUser_;

    /**
     * @brief GET リクエストはないので、不正アクセスとみなす
     * @param mixed $data
     */
    public function doGet($data)
    {
        $this->msg_[] = '不正なリクエストです';
    }

    /**
     * @brief 入力妥当性検査
     * @param mixed $data
     */
    public function doPost($data)
    {
        global $xoopsModule;

        global $xoopsUser;

        // インスタンスの作成

        $handler = &bxBBS::getHandler('message');

        $id = $this->getPositive($_POST['id']);

        if ($id > 0) {
            $this->data_ = &$handler->get($id);

            if (!is_object($this->data_)) {
                $this->msg_[] = '不正な要求です';

                return;
            }
        } else {
            $this->data_ = $handler->create();

            $this->data_->setVar('inputdate', time());

            $this->data_->setVar('update_date', time());

            $bbsUser = new bxBBSUser($xoopsUser);

            $this->data_->setVar('uid', $bbsUser->getVar('uid'));
        }

        // BBS は本当に存在するか？

        $bbs_id = $this->getPositive($_POST['bbs_id']);

        if (!$bbs_id) {
            $this->msg_[] = '不正な要求です';

            return;
        }

        $bHandler = &bxBBS::getHandler('bbs');

        $this->bbs_ = &$bHandler->get($bbs_id);

        if (!is_object($this->bbs_)) {
            $this->msg_[] = '書き込みを行おうとした BBS は存在しません';

            return;
        }

        $this->data_->setVar('bbs_id', $this->bbs_->getVar('bbs_id'));

        // 新規なら serial を設定

        if ($this->data_->isNew()) {
            $this->data_->setVar('serial', $this->bbs_->getVar('serial'));
        }

        // cleanVars に任せず自前で

        if (!mb_strlen($_POST['name'])) {
            $this->msg_[] = _MD_BXBBS_ERRMES_NAME;
        }

        $this->data_->setVar('name', $_POST['name']);

        if (!mb_strlen($_POST['title'])) {
            $this->msg_[] = _MD_BXBBS_ERRMES_TITLE;
        }

        $this->data_->setVar('title', $_POST['title']);

        if (!mb_strlen($_POST['message'])) {
            $this->msg_[] = _MD_BXBBS_ERRMES_MESSAGE;
        }

        $this->data_->setVar('message', $_POST['message']);

        $this->data_->setVar('email', $_POST['email']);

        if (!$this->validateMaxLength($_POST['passwd'], 8)) {
            $this->msg_[] = _MD_BXBBS_ERRMES_PASSWD_TOO_LONG;
        }

        $this->data_->setVar('passwd', md5($_POST['passwd']));

        if (!mb_strlen(trim($_POST['url']))) {
            $this->data_->setVar('url', 'http://');
        } elseif (!$this->validateHttpUrl($_POST['url'])) {
            $this->msg_[] = 'URL が不正です';
        } else {
            $this->data_->setVar('url', trim($_POST['url']));
        }

        $this->data_->setVar('ip', $_SERVER['REMOTE_ADDR']);

        // cookie

        $this->cookieUser_ = new bxBBSCookieUser();

        $this->cookieUser_->fetch();

        // ここまででエラーがあれば重複するものがあるので cleanVars をやらない

        if (!count($this->msg_)) {
            if (!$this->data_->cleanVars()) {
                $this->msg_ = array_merge($this->msg_, $this->data_->getErrors());
            }
        }
    }
}
