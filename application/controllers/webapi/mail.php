<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
		$this->load->model('return_format');
	}

	public function send()
	{
		$this->load->library('email');

		$this->email->from('your@example.com', 'Your Name');
		$this->email->to('johnnyeven@gmail.com'); 

		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.'); 

		$this->email->send();

		echo $this->email->print_debugger();
	}
}

?>