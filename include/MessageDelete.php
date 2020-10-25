<?php

/**
 * @brief   メッセージ削除のビジネスロジック
 * @version $Id: MessageDelete.php,v 1.1 2006/02/22 15:34:54 mikhail Exp $
 */
require_once 'exComponent/DeleteConfirm.php';

class MessageDeleteProcessor extends exDeleteConfirmTypicalComponentProcessor
{
    public function _processDelete($component)
    {
        $handler = &bxBBS::getHandler('message');

        $criteria = new CriteriaCompo();

        $criteria->add(new Criteria('bbs_id', $component->obj_->data_->getVar('bbs_id')));

        $criteria->add(new Criteria('parent', $component->obj_->data_->getVar('id')));

        // 本体を消す

        return $handler->delete($component->obj_->data_);
    }
}
