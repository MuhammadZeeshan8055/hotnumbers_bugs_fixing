<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\MasterModel;


class Categories extends BaseController
{
    private $master;
    protected $page;
    protected $uri;
    protected $table = "tbl_categories";
    private $data;

    public function __construct()
    {

        $this->uri = service('uri');
        $this->master = new MasterModel();

        $this->data['db'] = \Config\Database::connect();
    }

    public function product_categories()
    {
        $parent = 1;
        $productModel = model('ProductsModel');
        $this->data['productModel'] = model('ProductsModel');
        $this->data['categories'] = $this->master->query("SELECT * FROM tbl_categories WHERE group_name='product_cat' ORDER BY sort_order ASC");
        $this->data['content'] = ADMIN . "/categories/page-listing";
        $this->data['media'] = model('Media');
        $this->data['parent'] = $parent;
        $this->data['page'] ="product-categories";
        $this->data['group'] = "product";

        _render_page('/' . ADMIN . '/index', $this->data);
    }



    public function product_category_sortorder() {
        if(!empty($_POST)) {
            foreach(json_decode($_POST['data'],true) as $post) {
                $order = $post['order'];
                $id = $post['id'];
                $this->master->query("UPDATE tbl_categories SET sort_order='$order' WHERE group_name='product_cat' AND id='$id'");
            }
            echo json_encode(['success'=>1]);
        }
        exit;
    }

    public function page_categories()
    {
        $parent = 1;
        $this->data['productModel'] = model('ProductsModel');
        $this->data['categories'] = $this->master->getRows($this->table,['group_name'=>'page']);
        $this->data['content'] = ADMIN . "/categories/page-listing";
        $this->data['media'] = model('Media');
        $this->data['parent'] = $parent;
        $this->data['page'] ="page-categories";
        $this->data['group'] = "page";

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function post_categories()
    {
        $parent = 1;
        $this->data['productModel'] = model('ProductsModel');
        $this->data['categories'] = $this->master->getRows($this->table,['group_name'=>'post']);
        $this->data['content'] = ADMIN . "/categories/page-listing";
        $this->data['media'] = model('Media');
        $this->data['parent'] = $parent;
        $this->data['page'] ="post-categories";
        $this->data['group'] = "post";

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function add()
    {
        if ($data = $this->request->getPost()) {
            if (!empty($_FILES['img']['name'])) {

                $avatar = $this->request->getFile('img');
                $newfileName = $avatar->getRandomName();
                $avatar->move(SITE_IMAGES . 'categories', $newfileName);

                //unlink(base_url('/categories/'. $data['img']));

                if(!empty($data['img'])){
                    remove_file_from_directry(SITE_IMAGES.'/categories/',$data['img']);
                }

                $data['img'] = $newfileName;
            } else {
                $data['img'] = $data['img'];
            }

            $data['slug'] = trim($data['slug'],'-');
            $data['description'] = addslashes($data['description']);

            $group = $data['group_name'];

            if($data['group_name'] === "product") {
                $data['group_name'] = 'product_cat';
            }
            if($data['group_name'] === "page") {
                $data['group_name'] = 'page_cat';
            }

            $id = $this->master->insertData($this->table,$data, 'id',  $data['id']);



            $msg = 'Successfully Updated';
            return redirect()->to(base_url(ADMIN . '/'.$group.'-categories/add/' . $id))->with('msg', $msg);



//            notice_success("Successfully Updated!");
//            redirect(ADMIN . "/categories/add/" . $id);
        }

        $group = $this->uri->getSegment(2);
        if($group == "product-categories") {
            $this->data['group'] = 'product';
        }
        if($group == "page-categories") {
            $this->data['group'] = 'page';
        }

        $this->data['page'] = 'product-categories';

        $catType = !empty($_GET['parent']) ? (int)$_GET['parent'] : '';
        $this->data['parent_categories'] = $this->master->getRows($this->table, ['status'=>1]);
        //pr(        $this->data['parent_categories'] );
        $this->data['parent'] = $catType;
        $cate_id = $this->uri->getSegment(4);
        $this->data['categories_row'] = $this->master->getRow($this->table, ['id' => $cate_id]);

        //$this->data['page'] = "features";
        $this->data['content'] = ADMIN . "/categories/add_cate";
        _render_page('/' . ADMIN . '/index', $this->data);

    }

    public function delete(int $id)
    {
        if ($id > 0) {
            $this->master->query("DELETE FROM tbl_categories WHERE id='$id' or parent ='$id'");
            $this->res['ok'] = '1';
            $this->res['id'] = $id;
            echo json_encode($this->res);
            exit;
        }
    }


//    public function delete()
//    {
//
//
//
//
//        $id = end($this->uri->segments);
//        if ((int)$id > 0) {
//            //echo "DELETE FROM tbl_categories WHERE id='$id' or parent ='$id'";exit;
//            // $this->master->query("DELETE FROM tbl_categories WHERE id='$id' or parent ='$id'");
//            $this->db->query("DELETE FROM tbl_categories WHERE id='$id' or parent ='$id'");
//            $this->db->query("UPDATE tbl_books SET category = replace(category,'$id','Null')  WHERE 1");
//            $this->response['ok'] = '1';
//            $this->response['id'] = $id;
//            echo json_encode($this->response);
//            exit;
//        }
//    }

}
