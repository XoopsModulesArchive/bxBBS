<?php

/**
 * @brief   管理画面用メッセージフィルタとレンダー２種ずつを定義したファイル
 * @version $Id: AdminMessageFilter.php,v 1.1 2006/02/22 15:34:52 mikhail Exp $
 */
require_once 'exForm/Filter.php';
require_once 'exComponent/table/ListTable.php';

class AdminMessageFilter extends exAbstractFilterForm
{
    public $thread_ = false;

    public $bbs_id_ = null;

    public $sort_ = ['id', 'inputdate', 'bbs_id', 'uid', 'name', 'title', 'parent'];

    public function fetch()
    {
        $this->thread_ = (bool)($this->_getRequest('thread'));

        $this->bbs_id_ = $this->getPositiveIntger('bbs_id');
    }

    public function getCriteria($start = 0, $limit = 0, $sort = 0)
    {
        $criteria = $this->getSortCriteria($start, $limit, $sort);

        if ($this->bbs_id_) {
            $criteria->add(new Criteria('bbs_id', $this->bbs_id_));
        }

        return $criteria;
    }

    public function getDefaultCriteria($start, $limit)
    {
        $criteria = parent::getDefaultCriteria($start, $limit);

        $criteria->setSort('inputdate');

        $criteria->setOrder('DESC');

        return $criteria;
    }

    public function getExtra()
    {
        $ret = [];

        if ($this->bbs_id_) {
            $ret['bbs_id'] = $this->bbs_id_;
        }

        $ret['thread'] = ($this->thread_) ? 1 : 0;

        return $ret;
    }
}

class AdminMessageTableModel extends exListTableModel
{
    public $_column_ = ['CHK', 'ID', 'INPUTDATE', 'BBS_ID', 'UID', 'NAME', 'TITLE'];

    public $filter_;

    public function __construct($component = null)
    {
        parent::exListTableModel($component);

        if (!$this->filter_) {
            $this->filter_ = new AdminMessageFilter();
        }
    }

    public function init()
    {
        $handler = bxBBS::getHandler('message');

        $this->listController_ = new ListController();

        $this->listController_->filter_ = &$this->filter_;

        $this->listController_->fetch($handler->getCount($this->filter_->getCriteria()), 20);

        $objs = $handler->getObjects($this->listController_->getCriteria());

        foreach ($objs as $mes) {
            $this->_row_data_[] = $mes->getStructure();
        }

        return COMPONENT_MODEL_INIT_SUCCESS;
    }

    public function getValueAtChk($arr)
    {
        return "<input type='checkbox' name='id[]' value='" . $arr['id'] . "'>";
    }

    public function getValueAtInputdate($arr)
    {
        return formatTimestamp($arr['inputdate']);
    }
}

class AdminListTableRender extends exListTableRender
{
    public function _fetchHtmlHead()
    {
        $ret = '';

        $ret .= parent::_fetchHtmlHead();

        $ret .= "<form action='message_delete.php?thread=0' method='POST'>";

        return $ret;
    }

    public function _fetchHtmlFoot()
    {
        $ret = '';

        $ret = parent::_fetchHtmlFoot();

        $ret .= "<input type='submit' value='Delete'>";

        $ret .= '</form>';

        return $ret;
    }
}
