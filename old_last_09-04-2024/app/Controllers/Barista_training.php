<?php

namespace App\Controllers;
 
class barista_training extends BaseController
{
    public function index()
    {
        $master = model('MasterModel');
        $data['media'] = model('Media');
        $ProductsModel = model('ProductsModel');

        //echo 'hiiiii';
        $data['barista'] = $master->getRow('tbl_categories',['slug'=>'barista-training','status'=>1]);
        $data['products'] = [];
        if(!empty($data['barista'])) {
            $barista_id = $data['barista']['id'];
            $data['products'] = $ProductsModel->product_by_category_slug('barista-training');
        }

        return view('barista_training',$data);
    }
    

}
