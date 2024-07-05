<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;
use App\Models\MasterModel;
use App\Models\PageModel;


class Pages extends BaseController
{
    private $master;
    protected $uri;
    protected $table = "tbl_pages";
    private $data;


    public function __construct()
    {
        $this->uri = service('uri');
        $this->master = new MasterModel();
        $this->data['page'] = "page";

    }

    public function index()
    {
        $pageModel = model("PageModel");
        $this->data['pages'] = $pageModel->getPages();
        $this->data['content'] = ADMIN . "/pages/index";
        _render_page('/' . ADMIN . '/index', $this->data);

    }

    public function add() {
        $pageid = $this->request->getGet('id');

        $load = $this->request->getGet('load');

        $pageModel = model('PageModel');

        $this->data['allPages'] = $pageModel->getPages();

        $this->data['page'] = "page-add";

        $this->data['content'] = ADMIN . "/pages/add_page";

        if(!empty($pageid)) {
            $this->data['pageData'] = $pageModel->getPage($pageid);
        }

        if(!empty($load)) {
            $this->data['pageData'] = $pageModel->getPage($load);
        }

        if(empty($load) && empty($pageid) && !empty($this->data['allPages'][0])) {
            return redirect()->to(admin_url().'pages/add?load='.$this->data['allPages'][0]['id']);
        }

        if($pageid) {
            echo view('admin/pages/content', $this->data);
            exit;
        }

        if($this->uri->getSegment(4) === "blank") {
            echo view('admin/pages/blank');
            exit;
        }

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function add_1()
    {
        $pageid = $this->uri->getSegment(4);

        if($pageid === "blank") {
           echo view('admin/pages/blank');
          exit;
        }

        if($pageid) {
            $this->data['page_data']  = $this->master->getRow('tbl_pages', ['id' => $pageid]);
        }

        if($this->request->getPost('operation')) {
            $post = $this->request->getPost();

            if($post) {

                $meta = $post['meta'];
                $page_status = $post['status'];
                $page_title = $post['page_title'];
                $page_slug = $post['page_slug'];
                $page_id = $post['page_id'];
                $operation = $post['operation'];
                $content = '';
                if(!empty($post['content'])) {
                    $widget_order = $post['widget_order'];

                    unset($post['meta']);
                    unset($post['status']);
                    unset($post['page_title']);
                    unset($post['page_slug']);
                    unset($post['operation']);
                    unset($post['page_id']);
                    unset($post['page_widgets']);

                    if(!empty($post['content'])) {
                        $content = $post['content'];
                        $content_reorder = [];
                        foreach($widget_order as $idx=>$order) {
                            $content_reorder[$order] = $content[$idx];
                        }
                        ksort($content_reorder);

                        $content = json_encode($content_reorder);
                    }else {
                        $content = '';
                    }
                }


                $data = [
                    'page_title'=>$page_title,
                    'meta_keywords'=>$meta['keywords'],
                    'meta_description' => $meta['description'],
                    'meta_image' => $meta['image'],
                    'page_slug'=>$page_slug,
                    'content' => $content,
                    'status' => $page_status,
                    'date_updated' => date('Y-m-d h:i:s')
                ];

                if($operation == "add") {
                    $this->master->insertData('tbl_pages',$data);
                    $msg = "New page added";
                    notice_success($msg);
                    return redirect()->to('admin/pages');
                }else {
                    $this->master->insertData('tbl_pages',$data,'id',$page_id);
                    $msg = "Page updated";
                    notice_success($msg);
                    return redirect()->to('admin/pages/add/'.$page_id);
                }


            }


            exit;
        }

        $this->data['page'] = "page-add";

        $this->data['content'] = ADMIN . "/pages/add_page";
        _render_page('/' . ADMIN . '/index', $this->data);
        //exit;
    }

    public function getWidget($widgetID) {
       echo view('admin/pages/widgets/'.$widgetID);
        exit;
    }

    public function deletePage($pageID=0) {
        $this->master->delete_data('tbl_pages','id',$pageID);
        echo json_encode(['Success'=>1]);
        exit;
    }

    public function getresult()
    {
        header('Access-Control-Allow-Origin: *');

        header('Access-Control-Allow-Methods: GET, POST');

        header("Access-Control-Allow-Headers: X-Requested-With");
        $pageid = $this->uri->getSegment(4);

        $getRow = $this->master->getRow('tbl_pages', ['page_id' => $pageid]);

        if (!empty($getRow)) {
            $content = json_decode($getRow['content'], true);
            $content['pageid'] = $pageid;
            echo json_encode($content);
        }
        exit;
    }
    public function savePostData()
    {
        if(!empty($this->request->getPost())){
            $data = $this->request->getPost();

            $data['active'] = $data['status'];
            $data['slug'] =  toSlugUrl($data['page_title']);
            $page_id =$data['page_id'];
            unset($data['page_id']);
            $this->master->insertData('tbl_pages',$data,'page_id', $page_id);
            return;

        }
    }
    public function setHTMLCSS()
    {
        //pr($this->request->getPost());

        $data = file_get_contents('php://input');
       // $data = json_decode($data,true);


        $page_name = $this->request->getPost('page_name');



        if (!empty($data)) {

            $data_arr = json_decode($data, true);
            $page_id = '';
            $get_page_id = '';
            $post_data['content'] = $data;

            if (!empty($data_arr['pages'][0]['id'])) {
                $page_id = $get_page_id = $data_arr['pages'][0]['id'];
            }
            if (!$this->master->getRow('tbl_pages', ['page_id' => $page_id])) {
                $page_id = '';
            }

            if ($this->master->insertData('tbl_pages', ['content' => $data, 'page_id' => $get_page_id], 'page_id', $page_id)) {
                echo 1;
            }



        }
    }

    public function save_page() {
        $post = $this->request->getPost();
        $pageModel = new PageModel();

        if(!empty($post['operation']) && $post['operation'] === "add") {
            $title = $post['title'];
            $slug = $post['slug'];
            $meta = json_encode($post['meta']);
            $newPageId = $pageModel->add([
                'title' => $title,
                'meta_data' => $meta,
                'slug' => $slug
            ]);
            notice_success('Page added successfully');
            return redirect()->to(admin_url().'pages/add?load='.$newPageId);
        }

        if(!empty($post['operation']) && $post['operation'] === "edit") {
            $title = $post['title'];
            $slug = $post['slug'];
            $meta = json_encode($post['meta']);
            $newPageId = $pageModel->updatePage([
                'title' => $title,
                'meta_data' => $meta,
                'slug' => $slug
            ]);
            notice_success('Page updated successfully');
            return redirect()->to(admin_url().'pages/add?load='.$newPageId);
        }

        if(!empty($post['html'])) {
            $html = base64_encode($post['html']);
            $slug = $post['file'];

            $pageModel->updatePage([
                'content' => $html
            ], ['slug'=>$slug]);

            return 'Page updated successfully';
        }
    }


}