<?php
class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("Aauth");
	}

	public function signin()
	{
		$this->load->view('layouts/header');
		$this->load->view('layouts/siderbar');
		$this->load->view('sign-in');
		$this->load->view('layouts/footer');
	}

	public function signup()
	{
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('layouts/header');
			$this->load->view('layouts/siderbar');
			$this->load->view('sign-up');
			$this->load->view('layouts/footer');
		}
		else
		{
			if($this->aauth->create_user($this->input->post('email'),$this->input->post('password'),$this->input->post('username')))
			{
				$this->load->view('layouts/header');
				$this->load->view('layouts/siderbar');
				$this->load->view('success-signup');
				$this->load->view('layouts/footer');
			}else
			{
				$this->load->view('layouts/header');
				$this->load->view('layouts/siderbar');
				$this->load->view('error-signup');
				$this->load->view('layouts/footer');
			};
		}
	}
}