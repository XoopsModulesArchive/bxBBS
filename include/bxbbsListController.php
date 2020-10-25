<?php

/**
 * @brief   Smarty テンプレートに情報を出しやすいように拡張した ListController。YYBBSからのport
 * @version $Id: bxbbsListController.php,v 1.1 2006/02/22 15:34:54 mikhail Exp $
 */
require_once 'include/ListController.php';

/**
 * @brief Smarty テンプレートに情報を出しやすいように拡張した ListController。YYBBSからのport
 */
class bxBBSListController extends ListController
{
    public function getStructure()
    {
        $ret = [];

        $ret['maxpage'] = $this->maxpage_;

        $ret['current_page'] = $this->current_page_;

        for ($i = 0; $i < $this->maxpage_; $i++) {
            $ret['page'][$i]['number'] = $i + 1;

            $ret['page'][$i]['extra'] = 'start=' . $i * $this->perpage_ . '&amp;' . $this->renderExtraUrl($this->filter_->getExtra());
        }

        if ($this->current_page_ > 1) {
            // POST 用なので Filter の Extra は使用しない

            $ret['prev']['number'] = $this->current_page_ - 1;

            $ret['prev']['extra'] = $this->start_ - $this->perpage_;
        }

        if ($this->current_page_ < $this->maxpage_) {
            // POST 用なので Filter の Extra は使用しない

            $ret['next']['number'] = $this->current_page_ + 1;

            $ret['next']['extra'] = $this->start_ + $this->perpage_;
        }

        return $ret;
    }
}
