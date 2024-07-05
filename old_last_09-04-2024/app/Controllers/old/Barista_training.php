<?php

namespace App\Controllers;
 
class barista_training extends BaseController
{
    public function index()
    {
        $master = model('MasterModel');
        $data['media'] = model('Media');
        //echo 'hiiiii';
        $data['barista'] = $master->getRow('tbl_categories',['slug'=>'barista-training','status'=>1]);
        $data['products'] = [];
        if(!empty($data['barista'])) {
            $barista_id = $data['barista']['id'];
            $data['products'] = $master->getRows('tbl_products',['status'=>'publish','category'=>$barista_id]);
        }

        return view('barista_training',$data);
    }
    

}
