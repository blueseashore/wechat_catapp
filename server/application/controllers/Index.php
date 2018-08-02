<?php
/**
 * User: kendo    Date: 2018/8/2
 */
defined('BASEPATH') OR exit('No direct script access allowed');


class Index extends CI_Controller
{
    public function home()
    {

    }

    public function category()
    {
        $response['scroll'] = false;
        $list[] = ['id' => 'weekly', 'title' => '喵星新品', 'url' => '/article/list?category=weekly', 'data' => []];
        $list[] = ['id' => 'tips', 'title' => '喵星卡牌', 'url' => '/article/list?category=tips', 'data' => []];
        $list[] = ['id' => 'cases', 'title' => '喵星学堂', 'url' => '/article/list?category=cases', 'data' => []];
        $list[] = ['id' => 'updates', 'title' => '喵星玩具', 'url' => '/article/list?category=updates', 'data' => []];
        $response['list'] = $list;
        $response['selectedId'] = 0;
        send_json(TRUE, $response);
    }
}