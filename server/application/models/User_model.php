<?php
/**
 * 微信用户模型
 * User: kendo    Date: 2018/8/2
 */

class User_model extends CI_Model
{
    private $_table = 'users';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 检测微信用户是否存在
     * @param string $openId
     * @return mixed
     */
    public function checkUserByOpenID($openId = '')
    {
        return $this->db->get_where($this->_table, ['openid' => $openId])->row_array();
    }

    public function addWxUser(array $param)
    {
        $time = time();
        $data = [
            'openid' => $param['openid'],
            'unionid' => $param['unionid'],
            'create_time' => date('Y-m-d H:i:s', $time), //创建时间
            'last_login_time' => date('Y-m-d H:i:s', $time), //最后一次登陆时间
            'token' => md5($param['openid'] . $time), //登陆校验盐
        ];
        return $this->db->insert($this->_table, $data);
    }
}