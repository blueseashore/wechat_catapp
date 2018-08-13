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
        $this->load->model('article_model');
    }

    /**
     * @name 获取文章列表
     * @desc
     * @method POST
     */
    public function list()
    {
        $param = $this->input->get();
        send_json(TRUE, ['list' => $this->article_model->list($param)]);
    }

    /**
     * @name 获取文章详情
     */
    public function detail()
    {
        $param = $this->input->get();
        $data = [];
        if (!empty($param['id'])) {
            $data = $this->article_model->get($param['id']);
        }
        send_json(TRUE, $data);
    }

    /**
     * @name 搜索
     */
    public function search()
    {
        $param = $this->input->get();
        $data = [];
        if (!empty($param['searchValue'])) {
            $data = $this->article_model->list($param);
        }
        send_json(TRUE, $data);
    }
}