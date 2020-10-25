<?php

/**
 * @brief 一般ユーザー用のコンファーム画面を作るための Render 用 Model
 */
require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

class MessageConfirmRenderModel extends exConfirmRenderModel
{
    public $caption_ = 'メッセージの確認';

    public $headmessage_ = '以下の内容で登録します。確認の上、３分以内に Confirm ボタンを押して下さい';

    public $filter_ = ['passwd', 'ip', 'id', 'bbs_id'];

    public $_keyname_ = [
        'inputdate' => '初回投稿日時',
    ];

    public $ts_;

    public function __construct()
    {
        $this->ts_ = MyTextSanitizer::getInstance();
    }

    public function getValueAtMessage($key)
    {
        return $this->ts_->displayTarea($this->_array_[$key]);
    }

    public function getValueAtInputdate($key)
    {
        return formatTimestamp($this->_array_[$key]);
    }
}

class MessageDeleteConfirmRenderModel extends MessageConfirmRenderModel
{
    public $caption_ = 'メッセージの削除';

    public $headmessage_ = '以下の内容で削除します。確認の上、３分以内に Confirm ボタンを押して下さい';
}
