<?php
class Error_404 extends CI_Controller
{
	public function __construct()
	{
		parent::__construct ();
	}
	
	public function index()
	{
		echo '404';
		var_dump($_REQUEST);
	}
}

?>