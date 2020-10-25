<?php

/**
 * @brief 削除のための補助
 */
require_once 'exComponent/DeleteConfirm.php';

class AmindBBSDeleteProcessor extends exDeleteConfirmTypicalComponentProcessor
{
    public function _processDelete($component)
    {
        $handler = &bxBBS::getHandler('bbs');

        if (!$handler->delete($component->obj_)) {
            return false;
        }

        // メッセージも消す

        $handler = &bxBBS::getHandler('message');

        return $handler->deletes(new Criteria('bbs_id', $component->obj_->getVar('bbs_id')));
    }
}
