<?php

/**
 * @version $Id: user.php,v 1.1 2006/02/22 15:34:53 mikhail Exp $
 */
require_once 'xoops/user.php';

class bxBBSUser extends exXoopsUserObject
{
    public $cookieUser_;

    public function getStructure($type = 's')
    {
        $ret = &parent::getStructure($type);

        if (is_object($this->cookieUser_)) {
            $ret['cookie'] = $this->cookieUser_->getArray($type);
        }

        return $ret;
    }
}

class bxBBSCookieUser extends exXoopsObject
{
    public function __construct($name = null, $email = null, $url = null, $passwd = null, $icon = null, $col = null)
    {
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 60);

        $this->initVar('email', XOBJ_DTYPE_TXTBOX, null, true, 60);

        $this->initVar('url', XOBJ_DTYPE_TXTBOX, 'http://', false, 100);

        $this->initVar('passwd', XOBJ_DTYPE_TXTBOX, null, false, 32);

        if (is_array($name)) {
            $this->assignVars($name);
        }
    }

    /**
     * @brief クッキーへのセット
     * @param mixed $cookiename
     */
    public function setCookie($cookiename = 'xoops_bxbbs')
    {
        setcookie(
            $cookiename,
            implode(
                '<>',
                [
                    $this->getVar('name', 'e'),
                    $this->getVar('email', 'e'),
                    $this->getVar('icon', 'e'),
                    $this->getVar('col', 'e'),
                    $this->getVar('passwd', 'e'),
                    $this->getVar('url', 'e'),
                ]
            ),
            time() + 60 * 60 * 24 * 30
        );
    }

    /**
     * @brief クッキーから自分を構築する
     * @param mixed $cookiename
     */
    public function getCookie($cookiename = 'xoops_bxbbs')
    {
        if (isset($_COOKIE[$cookiename])) {
            $ts = MyTextSanitizer::getInstance();

            $tmp = explode('<>', $_COOKIE[$cookiename]);

            $this->setVar('name', $ts->stripSlashesGPC($ts->stripSlashesGPC($tmp[0])));

            $this->setVar('email', $ts->stripSlashesGPC($ts->stripSlashesGPC($tmp[1])));

            $this->setVar('passwd', $ts->stripSlashesGPC($ts->stripSlashesGPC($tmp[2])));

            $this->setVar('url', $ts->stripSlashesGPC($ts->stripSlashesGPC($tmp[3])));
        }
    }

    /**
     * @brief リクエストより収集する
     */
    public function fetch()
    {
        $this->setVar('name', $_POST['name']);

        $this->setVar('email', $_POST['email']);

        $this->setVar('passwd', $_POST['passwd']);

        $this->setVar('url', $_POST['url']);
    }
}
