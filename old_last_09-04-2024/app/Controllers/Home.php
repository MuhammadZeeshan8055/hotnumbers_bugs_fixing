<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\BlogModel;
use CodeIgniter\Model;

class Home extends MyController
{
    protected $fetchdata;

    public function __construct()
    {
        parent::__construct();
    }
    
    
    public function index($slug='')
    {
       if($slug) {
           $pageModel = model('PageModel');
           $this->data['page'] = $pageModel->getPageBySlug($slug);
           return view('pages/content-page',$this->data);
       }else {
           /// blog fetch
           $this->fetchdata = new BlogModel();
           $this->data['blog_posts'] = $this->fetchdata->get_all_blogs('*','WHERE post_status="publish" limit 3');

           /// coffee fetch
           $userModel = model('ProductsModel');
           $this->data['productModel'] = $userModel;
           $this->data['coffee_products'] = $userModel->product_by_category_slug('coffee','product.*','publish','ORDER BY product.id DESC LIMIT 6');

           $this->data['media'] = model('Media');

           return view('home',$this->data);
       }
    }
}
