<?php
/**
 * User: kendo    Date: 2018/8/2
 */
class Catetory_model extends CI_Model{
    private $_table = 'wp_posts';
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('blog', TRUE);
    }
}