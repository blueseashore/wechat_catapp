<?php
/**
 * 首页控制器
 * User: kendo    Date: 2018/8/2
 */
defined('BASEPATH') OR exit('No direct script access allowed');


class Index extends CI_Controller
{
    public function home()
    {

    }

    /**
     * @name 获取分类
     */
    public function category()
    {
        $response['scroll'] = false;
        $response['list'] = [
            ['id' => 'cat-newest', 'title' => '最新', 'url' => '/article/list?cat=0', 'data' => []],
            ['id' => 'cat-new', 'title' => '新品', 'url' => '/article/list?cat=24', 'data' => []],
            ['id' => 'cat-breed', 'title' => '卡牌', 'url' => '/article/list?cat=25', 'data' => []],
            ['id' => 'cat-school', 'title' => '学堂', 'url' => '/article/list?cat=26', 'data' => []],
            ['id' => 'cat-tool', 'title' => '玩具', 'url' => '/article/list?cat=27', 'data' => []]
        ];
        $response['selectedId'] = 0;
        send_json(TRUE, $response);
    }
}