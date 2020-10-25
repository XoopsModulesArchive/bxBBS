<?php

/**
 * @brief   メッセージ修正リクエスト入力フォーム
 * @version $Id: ReviceForm.php,v 1.1 2006/02/22 15:34:54 mikhail Exp $
 */
require_once 'exForm/Form.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * @brief 自己復元を含むメッセージ修正/および削除の入力フォーム
 */
class ReviceForm extends exAbstractActionForm
{
    public $bbs_;

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
        global $xoopsUser;

        // ここでいう serial は BBS 単位の ID のため BBSID が別途必要になる

        $serial = $this->getPositive($_POST['serial']);

        if (!$serial) {
            $this->msg_[] = 'メッセージ番号が指定されていません';

            return;
        }

        $bbs_id = $this->getPositive($_POST['bbs_id']);

        if (!$bbs_id) {
            $this->msg_[] = 'BBS番号が指定されていません';

            return;
        }

        //BBS が存在するかどうか確認

        $handler = &bxBBS::getHandler('bbs');

        $this->bbs_ = &$handler->get($bbs_id);

        if (!is_object($this->bbs_)) {
            $this->msg_[] = '指定された BBS は存在しません';

            return;
        }

        // インスタンスをセットしフォームロード

        $criteria = new CriteriaCompo();

        $criteria->add(new Criteria('serial', $serial));

        $criteria->add(new Criteria('bbs_id', $this->bbs_->getVar('bbs_id')));

        $handler = &bxBBS::getHandler('message');

        $this->data_ = &$handler->getOne($criteria);

        if (!is_object($this->data_)) {
            $this->msg_[] = '指定されたメッセージはありません';

            return;
        }

        // チェック

        $check = false;

        if (is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin() or $this->data_->getVar('uid') == $xoopsUser->uid()) {
                $check = true;
            }
        }

        // パスワードの一致による許可はゲスト専用とする

        if (!$this->data_->getVar('uid') && !$check) {
            $check = mb_strlen($_POST['passwd']) ? ($this->data_->getVar('passwd', 'e') == md5($_POST['passwd'])) : false;
        }

        if (!$check) {
            $this->msg_[] = 'このメッセージを修正することができません';

            $this->msg_[] = 'パスワードが間違っているか、権限がありません';
        }
    }
}
