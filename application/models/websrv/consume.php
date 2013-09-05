<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Consume extends CI_Model
{
	private $accountTable = 'log_consume';
	private $logdb = null;

	public function __construct()
	{
		parent::__construct ();
		$this->logdb = $this->load->database ( 'logdb', true );
	}

	public function insert($row)
	{
		if (! empty ( $row ))
		{
			return $this->logdb->insert ( $this->accountTable, $row );
		} else
		{
			return false;
		}
	}

	public function get($id)
	{
		if (! empty ( $id ))
		{
			$this->logdb->where ( 'log_id', $id );
			$result = $this->logdb->get ( $this->accountTable );
			if ($result->num_rows () > 0)
			{
				return $result->result ();
			} else
			{
				return false;
			}
		} else
		{
			return fales;
		}
	}
}
?>