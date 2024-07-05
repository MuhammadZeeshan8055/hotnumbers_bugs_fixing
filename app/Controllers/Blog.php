<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\BlogModel;
use CodeIgniter\Model;

class blog extends BaseController
{
    protected $blogs;
    
    public function __construct()
    {
        $this->blogs = new BlogModel();
    }
    
    
    //// fetch shop categories data
    public function index()
    {
        $data['blog_posts'] = $this->blogs->get_all_blogs('post_id,title,slug,post_date,img,content','WHERE post_status="publish" limit 6');
        //pr( $data['fetch_Shopcategories'],false);
        $data['media'] = model('Media');
        return view('blog/blog_listing',$data);
        //return view('shop');
    }

    public function ajaxlist() {
        $start = $this->request->getPost('start');
        $limit = $this->request->getPost('limit');
        $post_type = $this->request->getPost('post_type');
        $media = model('Media');

        $args = "LEFT JOIN tbl_files AS file ON file.id=img WHERE post_status='publish' ";

        if($post_type) {
            $args .= "AND post_type='$post_type' ";
        }

        $args .= "limit $start, $limit";

        $blog_posts = $this->blogs->get_all_blogs('post_id,title,slug,post_date,img,content,file.path AS img_path',$args);

        $total_args = "WHERE post_status='publish'";
        if($post_type) {
            $total_args .= " AND post_type='$post_type' ";
        }
        $total_posts = $this->blogs->get_all_blogs('COUNT(post_id) AS total',$total_args);

        if(!empty($total_posts[0])){
            foreach($blog_posts as $i=>$v) {
                $content = strip_tags($blog_posts[$i]->content);

                $blog_posts[$i]->content = substr($content,0,200);
                if(strlen($content) > 200) {
                    $blog_posts[$i]->content .= '...';
                }

                $blog_posts[$i]->title = stripslashes($blog_posts[$i]->title);

                $blog_posts[$i]->img_path = $media->get_media_src($blog_posts[$i]->img,'medium');
            }
            echo json_encode([
                'shown'=>$limit,
                'start'=>$start,
                'total'=>$total_posts[0]->total,
                'posts'=>$blog_posts,
            ]);
        }

        exit;
    }

    public function details($slug='')
    {
        $blogdata['blog_post'] = $this->blogs->get_blog_post($slug);
        $blogdata['media'] = model('Media');

        return view('blog/details',$blogdata);
    }

}