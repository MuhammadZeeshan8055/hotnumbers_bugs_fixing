<?php 

namespace App\Models;
use CodeIgniter\Model;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class req_wholesales_acc extends Model {
    
    public function saverecords($datasave)
    {
      //  $username = strstr($datasave['email'], '@', true);
        
      //  $datasave =$this->db->query("INSERT into login set firstname='".$username."', username='".$username."', email='".$datasave['email']."', password='".md5($datasave['password'])."'");
           //print_r ($this->db);
            //exit;
      //  return true;
        //}
    }
    
    
}
