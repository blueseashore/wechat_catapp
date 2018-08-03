<?php

/**
 * User: kendo    Date: 2018/8/2
 */
class Article_model extends CI_Model
{

    private $_table = 'wp_posts';
    private $db;
    private $_category = [
        '24' => '喵星新品',
        '25' => '喵星卡牌',
        '26' => '喵星学堂',
        '27' => '喵星玩具',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('blog', TRUE);
    }

    public function list(array $param)
    {
        $column = [
            'id',
            'post_title  title',
            'post_excerpt  thumbnail',
            'post_date date',
        ];
        $data['list'] = [
            [
                'thumbnail' => 'https://blog.gitee.com/wp-content/uploads/2018/07/小程序-700x500.png',
                'title' => '微信小程序开源项目精选 | 码云周刊第 79 期',
                'href' => 'https://blog.gitee.com/2018/07/23/wechat_applet/',
                'category' => '码云周刊',
                'date' => '2018年7月23日',
            ],
        ];
        $this->db->select(join(',', $column));
        if (!empty($param['cat'])) {
            $this->db->where('term_taxonomy_id', intval($param['cat']));
        }
        $this->db->from($this->_table . ' p');
        $this->db->where('ping_status', 'open');
        $this->db->where('post_status', 'publish');
        $this->db->where('post_type', 'post');
        $this->db->join('wp_term_relationships r', 'r.object_id = p.ID');
        $page = !empty($param['page']) ? intval($param['page']) : 1;
        $limit = !empty($param['limit']) ? intval($param['limit']) : 10;
        $this->db->limit($limit, ($page - 1) * $limit);
        $this->db->order_by('p.id', 'desc');
        $this->db->group_by('object_id');

        $data = $this->db->get()->result_array();
        if (!empty($data)) {
            foreach ($data as &$item) {
                $item['href'] = $item['id'];
                $item['date'] = date('Y年m月d日', strtotime($item['date']));
                if (empty($item['thumbnail'])) {
                    $item['thumbnail'] = 'https://blog.gitee.com/wp-content/uploads/2018/07/小程序-700x500.png';
                }
            }
        }
        return $data;
    }


    public function get($id = 0)
    {
        $column = [
            'post_title as title',
            'post_date date',
            'post_content content',
        ];
        $this->db->select(join(',', $column));
        $this->db->from($this->_table);
        $this->db->where('id', intval($id));
        $data = $this->db->get()->row_array();
        $data['category'] = '';
        if (!empty($data['title'])) {
            $this->db->reset_query();
            $this->db->select('term_taxonomy_id');
            $this->db->from($this->_table . ' p');
            $this->db->join('wp_term_relationships r', 'p.id = r.object_id');
            $this->db->where('object_id', $id);
            $this->db->where_in('term_taxonomy_id', array_flip($this->_category));
            $category = $this->db->get()->row_array();
            file_put_contents('/tmp/wxlog',json_encode($category));
            if (!empty($category) && isset($this->_category[$category['term_taxonomy_id']])) {
                $data['category'] = $this->_category[$category['term_taxonomy_id']];
            }
        }
        return $data;
    }
}