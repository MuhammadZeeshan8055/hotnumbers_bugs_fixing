<?php 

namespace App\Models;
use CodeIgniter\Model;


class FetchCategories extends Model {
    
    protected $data;
    
    //fetch shop categories 
    public function fetch_shopcategories()
    {
        $fetch_category= $this->db->query("SELECT * FROM tbl_categories where parent = (SELECT id FROM tbl_categories where slug='shop')");
        return $fetch_category->getResult();
    }
}
