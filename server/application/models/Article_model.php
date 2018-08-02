<?php

/**
 * User: kendo    Date: 2018/8/2
 */
class Article_model extends CI_Model
{
    private $_table = 'wp_posts';
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('blog', TRUE);
    }

    public function get($id = 0)
    {
        $this->db->select('post_title as title,brand_code,brand_name,brand_website,brand_logo,brand_status,create_time');

        $data = $this->db->get_where($this->_table, ['id' => $id])->row_array();
    }
}