<?php
namespace App\Controllers\Admin;

use App\Models\MasterModel;
use App\Models\ProductsModel;
use CodeIgniter\Controller;
use App\Controllers\BaseController;



class Dashboard extends BaseController
{
    private $data;
    public function __construct()
    {
        $this->data['page'] ="dashboard";
    }
    public function index(){
        $master = new MasterModel();
        $productModel = model('ProductsModel');

        $this->data['totalProducts'] = $master->query("SELECT COUNT(*) AS count FROM tbl_products WHERE status='publish'",false,true);
        $this->data['totalOrders'] = $master->query("SELECT COUNT(*) AS count FROM tbl_orders",false,true);
        $this->data['totalCustomers'] = $master->query("SELECT COUNT(*) AS count FROM tbl_users",false,true);

        $this->data['content'] = ADMIN . "/dashboard";

        $this->data['top_sales'] = $master->query("SELECT * FROM tbl_products WHERE status='publish' ORDER BY total_sales DESC LIMIT 10");

        $this->data['year_sales'] = $productModel->annual_stats('1 year');

        _render_page('/' . ADMIN . '/index', $this->data);
    }
}