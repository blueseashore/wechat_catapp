<?php
/**
 * User: kendo    Date: 2018/8/1
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use EasyWeChat\Factory;

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function login()
    {
        $config = [
            'app_id' => 'wx9aa1f8936c40ff46',
            'secret' => '',
            'response_type' => 'array',
        ];
        $app = Factory::miniProgram($config);
        $code = $this->input->get()['code'];
        $wxUserInfo = $app->auth->session($code);
        if (empty($wxUserInfo['errcode']) && !empty($wxUserInfo['openid'])) {
            //正确解码
            if (!isset($_SESSION['session_key']) || $_SESSION['session_key'] !== $wxUserInfo['session_key']) {
                $_SESSION['session_key'] = $wxUserInfo['session_key'];
            }
            $res = $this->user_model->checkUserByOpenID($wxUserInfo['openid']); //检查是否存在用户
            if ($res) { //存在微信用户
                $_SESSION = $res;
                if (!empty($res['account'])) { //已经绑定
                    $response = ['bind' => 1, 'oscuid' => $res['oscuid']];
                } else { //未绑定
                    $response = ['bind' => 0];
                }
                send_json(TRUE, $response);
            } else { //不存在用户，创建用户
                $create_res = $this->user_model->addWxUser($wxUserInfo); //检查是否存在用户
                if ($create_res) {
                    $response = ['bind' => 0];
                    send_json(TRUE, $response);
                } else {
                    send_json(FALSE, '系统异常');
                }
            }
        } else {
            //异常情况，登陆所用code过期、或者是重复使用等
            send_json(FALSE, $wxUserInfo['errmsg']);
        }
    }


    public function userUpdateInfo()
    {
    }

    public function mineList()
    {
        send_json(TRUE, []);
    }


}
