<?php


namespace App\Controllers\Admin;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\MasterModel;


class Home extends BaseController
{

    protected $master;
    public function __construct()
    {

        $this->master = new MasterModel();
        $this->data['menu_page'] = "home";
    }
    public function index()
    {

      //pr($this->data['category_rows'],false);

         //Home Banner
         $this->data['banner_rows'] = $this->master->getRows('banner',['type'=>'homepage']);
         //pr( $this->data['banner_rows']);

        //Featured Banner
        $this->data['featured_rows'] = $this->master->getRows('featured',['featured_id<='=>'6','status'=>'1']);
        foreach ( $this->data['featured_rows'] as $key=>$val){
            $this->data['featured_rows'][$key]->featured_ids = json_decode($val->featured_ids );
        }


           //pr($this->data['featured_rows']);

        //Home Genres
        $this->data['genres_rows'] = $this->master->getRow('genres');
        $this->data['genres_rows']->genres = json_decode($this->data['genres_rows']->genres);


        $this->data['post_rows'] = $this->master->getRows('posts',['category_id!='=>79],'*',0,3,'desc','post_id');

         //pr($this->data['post_rows']);

        //pr($this->data['genres_rows'],false);
        $this->data['content'] = "home/front_page";
        $this->_render_page('index', $this->data);

    }

}
