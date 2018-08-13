<?php

/**
 * User: kendo    Date: 2018/8/2
 */
class Article_model extends CI_Model
{

    private $_table = 'wp_posts';
    private $db;
    private $_category = [
        '0' => '首页',
        '24' => '新品',
        '25' => '卡牌',
        '26' => '学堂',
        '27' => '玩具',
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
        $this->db->select(join(',', $column));
        if (!empty($param['cat'])) {
            $this->db->where('term_taxonomy_id', intval($param['cat']));
        } else {
            $this->db->where_in('term_taxonomy_id', array_flip($this->_category));
        }
        $this->db->from($this->_table . ' p');
        $this->db->where('ping_status', 'open');
        $this->db->where('post_status', 'private');
        $this->db->where('post_type', 'post');
        $this->db->join('wp_term_relationships r', 'r.object_id = p.ID');

        $page = !empty($param['page']) ? intval($param['page']) : 1;
        $limit = !empty($param['limit']) ? intval($param['limit']) : 10;

        if (!empty($param['searchValue'])) {
            $this->db->like('post_title', trim($param['searchValue']));
            $this->db->or_like('post_content', trim($param['searchValue']));
        }else{
            $this->db->limit($limit, ($page - 1) * $limit);
        }

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
            file_put_contents('/tmp/wxlog', json_encode($category));
            if (!empty($category) && isset($this->_category[$category['term_taxonomy_id']])) {
                $data['cat'] = $category['term_taxonomy_id'];
                $data['category'] = $this->_category[$category['term_taxonomy_id']];
            }
        }
        return $data;
    }
}