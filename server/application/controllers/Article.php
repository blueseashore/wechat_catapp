<?php
/**
 * User: kendo    Date: 2018/8/2
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list()
    {
        $data['pagecount'] = 3;
        $data['list'] = [
            [
                'thumbnail' => 'https://blog.gitee.com/wp-content/uploads/2018/07/小程序-700x500.png',
                'title' => '微信小程序开源项目精选 | 码云周刊第 79 期',
                'href' => 'https://blog.gitee.com/2018/07/23/wechat_applet/',
                'category' => '码云周刊',
                'date' => '2018年7月23日',
            ],
            [
                'thumbnail' => 'https://blog.gitee.com/wp-content/uploads/2018/06/默认标题_自定义px_2018.07.11-700x500.png',
                'title' => '世界杯阵型之争的背后，国产开源项目百花争艳 | 码云周刊第 77 期',
                'href' => 'https://blog.gitee.com/2018/06/24/weekly77/',
                'category' => '码云周刊',
                'date' => '2018年6月23日',
            ],
        ];
        send_json(TRUE,$data);
    }

    public function detail()
    {

    }
}