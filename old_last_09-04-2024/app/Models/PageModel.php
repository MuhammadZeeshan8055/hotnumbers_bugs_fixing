<?php
namespace App\Models;

use \CodeIgniter\Model;

class PageModel extends Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getPages($where='') {
        return $this->db->query("SELECT * FROM `tbl_pages` $where")->getResultArray();
    }

    public function getPage($pageID) {
        return $this->db->query("SELECT * FROM `tbl_pages` WHERE id='$pageID'")->getRow();
    }

    public function getPageBySlug($slug) {
        return $this->db->query("SELECT * FROM `tbl_pages` WHERE slug='$slug'")->getRow();
    }

    public function add($data) {
        $master = new MasterModel();
        if(!empty($data)) {
            $master->insertData('tbl_pages',$data);
        }
        return $master->last_insert_id();
    }

    public function updatePage($data=[],$where=[]) {
        if(!empty($data)) {
            $this->db->table('tbl_pages')->where($where)->update($data);
            return $this->db->table('tbl_pages')->select('id')->where($where)->get()->getRow()->id;
        }
    }
}