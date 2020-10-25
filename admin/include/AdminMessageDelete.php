<?php

/**
 * @brief   メッセージの削除機能に対する入力アクションフォームと削除ロジック
 * @version $Id: AdminMessageDelete.php,v 1.1 2006/02/22 15:34:52 mikhail Exp $
 */
require_once 'exForm/Form.php';
require_once 'exComponent/DeleteConfirm.php';

class AdminDeleteMessageForm extends exAbstractActionForm
{
    public $id_ = [];

    public function doGet($data = null)
    {
        $this->msg_[] = '不正なアクセスです';
    }

    public function doPost($data = null)
    {
        $ids = $_POST['id'];

        if (is_array($ids)) {
            foreach ($ids as $id) {
                $this->id_[] = (int)$id;
            }
        } elseif (is_numeric($ids)) {
            $this->id_[] = (int)$ids;
        }

        if (!count($this->id_)) {
            $this->msg_[] = '不正なリクエストです';
        }
    }
}

class AdminMessageDeleteProcessor extends exDeleteConfirmTypicalComponentProcessor
{
    public function _processDelete($component)
    {
        $handler = &bxBBS::getHandler('message');

        foreach ($component->obj_->id_ as $id) {
            $criteria = new Criteria('id', $id);

            if (!$handler->deletes($criteria)) {
                return false;
            }

            unset($criteria);
        }

        return true;
    }
}
