<?php

namespace App\Models;
use CodeIgniter\Model;


class GigsModel extends Model {

    protected $gigstable = 'tbl_gigs_events';

    public function __construct()
    {
        parent::__construct();
    }

    /// all gigs list
    public function get_all_gigs($field='*',$args='')
    {
       
            $query = $this->db->query("SELECT $field FROM $this->gigstable $args");
            return $query->getResult();

    }

}