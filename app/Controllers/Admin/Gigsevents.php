<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\MasterModel;



class Gigsevents extends BaseController
{
    private $master;
    protected $page;
    protected $uri;
    protected $table = "tbl_posts";
    private $media;



    public function __construct()
    {
        $this->uri = service('uri');
        $this->master = new MasterModel();
        $this->data['page'] ="gigs_events";
        $this->media = model('Media');
        $this->data['media'] = $this->media;
    }

    public function index()
    {
        $this->data['content'] = ADMIN . "/blog/listing";

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

            $output = datatable_query('tbl_posts',$rows,$sort_cols);
            $records = $output['records'];
            unset($output['records']);

            foreach($records as $i=>$row) {
                $image = $this->media->get_media_src($row['img'],'','thumbnail');
                $output['data'][] = [
                    '<img width="100" src="'.$image.'">',
                    stripslashes($row['title']),
                    $row['post_date'],
                    $row['featured_post'],
                    '<a class="edit_row"
                               href="'.base_url(ADMIN . '/blog/add/').'/'.$row['post_id'].'"><i
                                        class="icon-edit-alt"></i> </a>
                            <a class="del_row edit_row"
                               onclick="del_item(\''.base_url(ADMIN . '/blog/delete/').'/'.$row['post_id'].')"
href="javascript:void(0)"></i><i class="icon-trash"></i></a>'
                ];
            }

            echo json_encode($output);

            exit;
        }

        _render_page('/' . ADMIN . '/index', $this->data);

    }

    public function add()
    {

        if ($data = $this->request->getPost()) {

            if (!empty($_FILES['img']['name'])) {

                $avatar = $this->request->getFile('img');
                $newfileName = $avatar->getRandomName();
                $avatar->move(SITE_IMAGES . 'gigs', $newfileName);

                //unlink(base_url('/categories/'. $data['img']));

                if(!empty($data['img'])){
                    remove_file_from_directry(SITE_IMAGES.'/gigs/',$data['img']);
                }

                $data['img'] = $newfileName;

                // remove_file_from_directry(SITE_IMAGES.'/categories/',$data['img']);

            } else {
                $data['img'] = $data['img'];
            }

            //$data['slug'] = toSlugUrl($data['name']);

            $gig_id = $this->master->insertData($this->table,$data, 'gig_id',  $data['gig_id']);
            $msg = 'Successfully Updated';
            return redirect()->to(base_url(ADMIN . '/gigs_events/add/' . $gig_id))->with('msg', $msg);

        }







        $gig_id = $this->uri->getSegment(4);

        $this->data['gig_row'] = $this->master->getRow($this->table, ['gig_id' => $gig_id]);


        //$this->data['page'] = "features";
        $this->data['content'] = ADMIN . "/gigs_events/add_events";
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
