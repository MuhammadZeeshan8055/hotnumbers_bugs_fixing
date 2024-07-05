<?php


namespace App\Controllers;
use App\Models\UpdatepassModel;
use CodeIgniter\Controllers;


class edit_account extends CI_Controller 
{ 
	public function change_pass()
	{
		if(!empty($this->request->getPost()))
		{

            echo "121";
			$old_pass=$this->request->getPost('password_current');
			$new_pass=$this->request->getPost('password_1');
			$confirm_pass=$this->request->getPost('password_2');
            $session_id=$this->session->userdata('loginid');
            echo $session_id;
            exit;
			$que=$this->query("select * from login where loginid='$session_id'");
			$row=$que->row();
			if((!strcmp($old_pass, $pass))&& (!strcmp($new_pass, $confirm_pass)))
            {
				$this->UpdatepassModel->change_pass($session_id,$new_pass);
				echo "Password changed successfully !";
			}
			else{
				echo "Invalid";
			}
		}
		$this->load->view('change_pass');	
	}
}
