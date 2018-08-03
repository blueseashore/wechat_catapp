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

    public function list()
    {
        $param = $this->input->get();
        send_json(TRUE, ['list'=>$this->article_model->list($param)]);
    }

    public function detail()
    {
        $param = $this->input->get();
        file_put_contents('/tmp/wxlog',json_encode($param));
        $data = [];
        if (!empty($param['id'])) {
            $data = $this->article_model->get($param['id']);
        }
        send_json(TRUE, $data);
    }
}