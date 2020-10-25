<?php

/**
 * @brief   書き込みをしめす DataObject の定義ファイル
 * @author  minahito
 * @version $Id: message.php,v 1.1 2006/02/22 15:34:53 mikhail Exp $
 */
require_once 'xoops/class.object.php';

class bxBBSMessageObject extends exXoopsObject
{
    public function __construct($id = null)
    {
        $this->initVar('id', XOBJ_DTYPE_INT, 0, false);

        $this->initVar('serial', XOBJ_DTYPE_INT, 1, false);

        $this->initVar('bbs_id', XOBJ_DTYPE_INT, 1, false);

        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);

        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 64);

        $this->initVar('email', XOBJ_DTYPE_TXTBOX, null, false, 64);

        $this->initVar('url', XOBJ_DTYPE_URL, 'http://', false, 64);

        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 64);

        $this->initVar('message', XOBJ_DTYPE_TXTAREA, null, false, null);

        $this->initVar('passwd', XOBJ_DTYPE_TXTBOX, null, false, 34);

        $this->initVar('inputdate', XOBJ_DTYPE_INT, 0, false);

        $this->initVar('ip', XOBJ_DTYPE_TXTBOX, null, false, 22);

        if (is_array($id)) {
            $this->assignVars($id);
        }
    }

    /**
     * @brief Cookie にしかなく、xoopsUser と衝突しない内容をセットする
     * @param mixed $cookieUser
     */
    public function setDefault($cookieUser)
    {
        if (is_object($cookieUser)) {
            if (!$this->getVar('id')) {
                $this->setVar('name', $cookieUser->getVar('name', 'e'));

                $this->setVar('email', $cookieUser->getVar('email', 'e'));

                $this->setVar('url', $cookieUser->getVar('url', 'e'));
            }

            $this->setVar('passwd', $cookieUser->getVar('passwd', 'e'));
        }
    }

    public function &getStructure($type = 's')
    {
        $ret = &parent::getStructure($type);

        // 仮想ユーザーをセット

        if (class_exists('bxbbsuser')) {
            $uHandler = xoops_getHandler('user');

            $user = new bxBBSUser($uHandler->get($this->getVar('uid')));

            $ret['bbsuser'] = $user->getArray($type);

            if (0 === $ret['bbsuser']['uid']) {
                $ret['bbsuser']['name'] = $ret['name'];

                $ret['bbsuser']['email'] = $ret['email'];

                $ret['bbsuser']['url'] = $ret['url'];
            }
        }

        return $ret;
    }

    /**
     * このオブジェクトとデーターベースとの接続方法を返す
     */
    public function getTableInfo()
    {
        $tinfo = new exTableInfomation('bxbbs_message', 'id');

        return ($tinfo);
    }
}
