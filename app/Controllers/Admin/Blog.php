<?php

namespace App\Controllers\Admin;
use App\Models\Media;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\MasterModel;
use CodeIgniter\Model;


class Blog extends BaseController
{
    private $master;
    protected $uri;
    private $media;
    private $data;
    protected $table = "tbl_posts";

    public function __construct()
    {

        $this->uri = service('uri');
        $this->master = new MasterModel();
        $this->media = model('Media');
        $this->data['page'] = "blog";
        $this->data['media'] = $this->media;

        // $this->data['page'] = "books";

    }

    private function get_tbl_data($group='') {
        if(!empty($_GET['table_data'])) {
            $rows = [
                'post_id',
                'title',
                'featured_post',
                'post_date',
                'post_id',
                'img'
            ];
            $sort_cols = [
                'post_id',
                'title',
                'post_date',
                'featured_post'
            ];

            $output = datatable_query('tbl_posts',$rows,$sort_cols,$group);
            $records = $output['records'];
            unset($output['records']);

            foreach($records as $i=>$row) {
                $image = $this->media->get_media_src($row['img'],'','thumbnail');
                $output['data'][] = [
                    '<img width="100" src="'.$image.'">',
                    stripslashes($row['title']),
                    $row['post_date'],
                    '<div style="width: 150px;">  <a class="edit_row btn btn-primary btn-sm"
                              href="'.base_url(ADMIN . '/blog/add/').'/'.$row['post_id'].'">Edit</a>
                            <a class="del_row edit_row btn bg-black btn-secondary btn-sm"
                               onclick="del_item(\''.base_url(ADMIN . '/blog/delete/').'/'.$row['post_id'].')"
href="javascript:void(0)">Delete</a></div>'
                ];
            }
            echo json_encode($output);
            exit;
        }
    }

    public function index()
    {
        $this->data['content'] = ADMIN . "/blog/listing";

        $this->data['post_type']= 'post';
        $this->get_tbl_data();

        _render_page('/' . ADMIN . '/index', $this->data);

    }

    public function gig_event_listing($getdata=[]) {
        $this->data['content'] = ADMIN . "/blog/listing";

        $this->data['post_type'] = 'gig';

        $this->data['page'] = "gigs_events";

        $this->get_tbl_data(" AND post_type='gig_event' ");

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function add()
    {
        $this->data['categories'] = $this->master->getRows('tbl_categories',' (group_name="post") ');

        $post_id = $this->uri->getSegment(4);

        $this->data['post_row'] = $this->master->getRow($this->table, ['post_id' => $post_id]);


        $this->data['title'] = 'Blog Post';

        $data['media'] = new Media();

        $this->data['page'] = "add_post";
        $this->data['content'] = ADMIN . "/blog/add_post";


        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function add_post() {
        if (!empty($this->request->getPost())) {
            $data = $this->request->getPost();
            if (!empty($_FILES['img']['name'])) {
                $avatar = $this->request->getFile('img');
                $newfileName = $avatar->getRandomName();
                $avatar->move(SITE_IMAGES . '/blogs/', $newfileName);

                if (!empty($data['img'])) {
                    remove_file_from_directry(SITE_IMAGES . '/blogs/', $data['img']);
                }
                $data['img'] = $newfileName;
            }


            $data['status'] = $data['post_status'];

            $db_data = [
              'title'=>$data['title'],
                'slug'=>$data['slug'],
                'post_type'=>$data['post_type'],
                'category'=>$data['category'],
                'post_status'=>$data['post_status'],
                'post_date'=>$data['post_date'],
                'scheduled_date'=>$data['scheduled_date'],
                'img'=>$data['img'],
                'content'=>$data['content']
            ];

            if(!empty($data['post_id'])) {
                $id = $this->master->insertData('tbl_posts', $db_data, 'post_id', $data['post_id']);
            }else {
                $id = $this->master->insertData('tbl_posts', $db_data);
            }

            $msg = 'Successfully Updated';
            return redirect()->to(base_url(ADMIN . '/blog/add/' . $id))->with('msg', $msg);
//            notice_success("Successfully Updated!");
//            redirect(ADMIN . "/post/add/" . $id);
        }
    }

    public function update_featured_post()
    {
        $post_id = $this->uri->segment(4);
        if (!empty($this->input->post())) {
            $status = $this->input->post('featured_post');
            if ($status == 'on') {
                $data = ['featured_post' => 'yes'];
            }
        } else {
            $data = ['featured_post' => 'no'];
        }

        $this->db->query("update tbl_posts  set featured_post='no' ");
        $id = $this->master->save('posts', $data, 'post_id', $post_id);

        echo json_decode($id);

//            notice_success("Successfully Updated!");
//            redirect(ADMIN . "/post/add/" . $id);


    }

    public function search_books()
    {

        if (!empty($this->input->post('name'))) {

            $lcSearchVal = $this->input->post('name');
            $lcSearchVal = str_replace(' ', '+', $lcSearchVal);


            $inQuery = implode(',', array_fill(0, count($brands), '?'));


            $result = $this->master->query("SELECT  REPLACE(bks.name,'+',' ') as  title, bks.id book_id,bks.cover_image  FROM tbl_books as bks WHERE  bks.name LIKE  '%" . $lcSearchVal . "%'");


            if (!empty($result)) {
                $html = ' <ul class="serach_books">';
                foreach ($result as $k => $v) {
                    $book_name = $v->title;
                    if (trim($book_name) === '') continue;
                    $title = str_ireplace($this->input->post('name'), "<strong style='color:red'>" . $lcSearchVal . "</strong>", $book_name);
                    $html .= '<li><a class="select_title" href="javascript:void(0)"  data-id="' . $v->book_id . '" data-img="' . base_url('assets/uploads/images') . "/" . $v->cover_image . '" >' . ucfirst(str_replace("+", " ", $title)) . '</a></li>';
                }
                $html .= '</ul>';
            } else {
                $html .= '';
            }
        } else {
            $html .= '';
        }

        echo json_encode(['response' => $html]);
        exit;
    }


    public function search_authors()
    {

        if (!empty($this->input->post('name'))) {

            $lcSearchVal = $this->input->post('name');
            $inQuery = implode(',', array_fill(0, count($brands), '?'));
            $result = $this->master->query("SELECT auths.fname,auths.lname ,auths.mname ,auths.author_id as auth_id FROM tbl_authors as auths WHERE auths.fname LIKE '%$lcSearchVal%' or auths.lname LIKE '%$lcSearchVal%' or auths.mname LIKE '%$lcSearchVal%'");
            if (!empty($result)) {
                $html = ' <ul class="serach_books">';
                foreach ($result as $k => $v) {
                    $auth_name = $v->fname . " " . $v->mname . " " . $v->lname;
                    if (trim($auth_name) === '') continue;
                    $title = str_ireplace($this->input->post('name'), "<strong style='color:red'>" . $lcSearchVal . "</strong>", $auth_name);
                    $html .= '<li><a class="select_title" href="javascript:void(0)"  data-id="' . $v->auth_id . '" data-img="' . base_url('assets/uploads/images') . "/" . $v->cover_image . '" >' . ucfirst(str_replace("+", " ", $title)) . '</a></li>';
                }
                $html .= '</ul>';
            } else {
                $html .= '';
            }
        } else {
            $html .= '';
        }

        echo json_encode(['response' => $html]);
        exit;

    }

    public function delete()
    {

        $id = end($this->uri->segments);
        if ((int)$id > 0) {

            $this->db->query("DELETE FROM tbl_posts WHERE post_id='$id'");

            $this->response['ok'] = '1';
            $this->response['id'] = $id;
            echo json_encode($this->response);
            exit;
        }
    }
}
