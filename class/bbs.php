<?php

/**
 * @brief   BBS を定義しているファイル
 * @author  minahito
 * @version $Id: bbs.php,v 1.1 2006/02/22 15:34:53 mikhail Exp $
 */
require_once 'xoops/class.object.php';

class bxBBSBbsObject extends exXoopsObject
{
    public function __construct($id = null)
    {
        $this->initVar('bbs_id', XOBJ_DTYPE_INT, 0, false);

        $this->initVar('serial', XOBJ_DTYPE_INT, 0, false);

        $this->initVar('page_limit', XOBJ_DTYPE_INT, 10, true);

        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, true, 64);

        $this->initVar('ex', XOBJ_DTYPE_TXTAREA, null, true, null);

        $this->initVar('howto', XOBJ_DTYPE_TXTAREA, null, false, null);

        $this->initVar('serial', XOBJ_DTYPE_INT, 1, false);

        $this->initVar('priority', XOBJ_DTYPE_INT, 0, false);

        $this->initVar('status', XOBJ_DTYPE_INT, 0, false);

        if (is_array($id)) {
            $this->assignVars($id);
        }
    }

    /**
     * このオブジェクトとデーターベースとの接続方法を返す
     */
    public function getTableInfo()
    {
        $tinfo = new exTableInfomation('bxbbs_bbs', 'bbs_id');

        return ($tinfo);
    }
}
