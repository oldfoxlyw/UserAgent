<?php
class Sleep extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
	}
	
	public function test()
	{
		sleep(10);
	}
}

?>