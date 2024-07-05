<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\GigsModel;
use CodeIgniter\Model;

class Events extends BaseController
{

    protected $gigs;

    public function __construct()
    {
        $this->gigs = new GigsModel();
    }
 
    ///gigs events
    public function index()
    {
       
        $data['gigs_posts'] = $this->gigs->get_all_gigs('gig_id,date,title,description,url,img,location,time,price','limit 2');
        return view('events/gigs_events',$data);
    }
    
    /// load gigs
    public function ajaxlist() {
       
        $start = $this->request->getPost('start');
        $limit = $this->request->getPost('limit');

        $args = "limit $start, $limit";

        $gigs_posts = $this->gigs->get_all_gigs('gig_id,date,title, description,url,img,location,time,price',$args);

        $total_gigs = count($gigs_posts);
        

        if(!empty($total_gigs)){
            echo json_encode([
                'shown'=>$limit,
                'start'=>$start,
                'total'=> $total_gigs,
                'posts'=>$gigs_posts
            ]);
        }
        

        exit;
    }



}
