<?php


namespace App\Controllers\old;
use App\Controllers\CI_Controller;
use App\Models\UpdatepassModel;
use CodeIgniter\Controllers;


class edit_account extends CI_Controller 
{ 
	public function change_pass()
	{
		if(!empty($this->request->getPost()))
		{
			$old_pass=$this->request->getPost('password_current');
			$new_pass=$this->request->getPost('password_1');
			$confirm_pass=$this->request->getPost('password_2');
            $session_id=$this->session->userdata('loginid');

            pr($old_pass);

		}
		$this->load->view('change_pass');	
	}
}
