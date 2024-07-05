<?php


namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\MasterModel;

class MyController extends BaseController
{
    public $data;
    public $master;
    public function __construct()
    {
        $this->master = new MasterModel();
        $this->data['settings']   = $this->master->getRow('tbl_settings');
       // $this->data['pages']   = $this->master->getRows('tbl_pages');
    }

}