<?php

/**
 * @brief   本モジュール用のクラスメソッドを定義したクラス
 * @version $Id: global.php,v 1.1 2006/02/22 15:34:53 mikhail Exp $
 */
class bxBBS
{
    /**
     * @brief 指定した名前のハンドラを取得する
     * @param mixed $name
     * @return \exXoopsObjectHandler|mixed
     * @return \exXoopsObjectHandler|mixed
     */
    public function &getHandler($name)
    {
        global $xoopsModule;

        global $__bxbbs_handelrs_cache__;

        global $xoopsDB;

        $name = mb_strtolower(trim($name));

        if (!isset($__bxbbs_handelrs_cache__[$name])) {
            $class = 'bxBBS' . ucfirst($name) . 'Object';

            if (!class_exists($class)) { // １回だけ読み込む
                $filename = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/' . $name . '.php';

                if (file_exists($filename)) {
                    require_once $filename;
                }
            }

            $handler_class = $class . 'Handler';

            if (class_exists($handler_class)) {
                $__bxparty_handelrs_cache__[$name] = new $handler_class($xoopsDB);
            } else {
                $__bxparty_handelrs_cache__[$name] = new exXoopsObjectHandler($xoopsDB, $class);
            }

            return $__bxparty_handelrs_cache__[$name];
        }

        return $__bxparty_handelrs_cache__[$name];
    }
}
