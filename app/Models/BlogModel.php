<?php

namespace App\Models;
use CodeIgniter\Model;


class BlogModel extends Model {


    protected $table = 'tbl_posts';


    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_blogs($field='*',$args='')
    {
        $query = $this->db->query("SELECT $field FROM `tbl_posts` $args");
        return $query->getResult();
    }
    
    
    /// blogs posts
    public function get_blog_post($slug = "")
    {
        $query = $this->db->query("SELECT * FROM `tbl_posts` WHERE slug = '".$slug."'");
        return $query->getResult();
    }

}