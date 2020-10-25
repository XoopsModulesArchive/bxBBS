<?php

/**
 * @file
 * @brief   掲示板の閲覧用メッセージフィルタ
 * @version $Id: MessageFilter.php,v 1.1 2006/02/22 15:34:54 mikhail Exp $
 */
require_once 'exForm/Filter.php';

/**
 * @brief BBS_ID を必要としない PostFilter
 */
class bxBBSMessagePostFilter extends exAbstractFilterForm
{
    public function getCriteria($start = 0, $limit = 0, $sort = 0)
    {
        return $this->getDefaultCriteria($start, $limit);
    }

    public function getDefaultCriteria($start = 0, $limit = 0)
    {
        $criteria = new CriteriaCompo();

        $criteria->setSort('inputdate');

        $criteria->setOrder('DESC');

        $criteria->setStart($start);

        $criteria->setLimit($limit);

        return ($criteria);
    }

    public function getTotal()
    {
        $handler = &YYBBS::getHandler('message');

        return $handler->getCount($this->getCriteria());
    }
}

/**
 * @brief BBS 指定でなければいけないフィルター
 */
class bxBBSUserMessageFilter extends bxBBSMessagePostFilter
{
    public $bbs_id_ = null;

    public function fetch()
    {
        $this->bbs_id_ = $this->getPositiveIntger('bbs_id');

        if (!$this->bbs_id_) {
            $this->msg_[] = _MD_BXBBS_ERROR_BBS_NOEXISTS1;
        }
    }

    public function getCriteria($start = 0, $limit = 0)
    {
        $criteria = $this->getDefaultCriteria($start, $limit);

        if ($this->bbs_id_) {
            $criteria->add(new Criteria('bbs_id', $this->bbs_id_));
        }

        return $criteria;
    }

    public function getExtra()
    {
        return ['bbs_id' => $this->bbs_id_];
    }
}
