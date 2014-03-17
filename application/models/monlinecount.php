<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('ICrud.php');

class Monlinecount extends CI_Model implements ICrud
{
	
	private $accountTable = 'log_online_count';
	private $logcachedb = null;
	
	public function __construct()
	{
		parent::__construct();
		$this->logcachedb = $this->load->database('log_cachedb', TRUE);
	}
	
	public function count($parameter = null, $extension = null)
	{
		if(!empty($parameter))
		{
			foreach($parameter as $key=>$value)
			{
				$this->logcachedb->where($key, $value);
			}
		}
		if(!empty($extension))
		{
			
		}
		return $this->logcachedb->count_all_results($this->accountTable);
	}
	
	public function create($row)
	{
		if(!empty($row))
		{
			if($this->logcachedb->insert($this->accountTable, $row))
			{
				return $this->logcachedb->insert_id();
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function read($parameter = null, $extension = null, $limit = 0, $offset = 0)
	{
		if(!empty($parameter))
		{
			foreach($parameter as $key=>$value)
			{
				$this->logcachedb->where($key, $value);
			}
		}
		if(!empty($extension))
		{
			if(!empty($extension['select']) && is_array($extension['select']))
			{
				$this->logcachedb->select($extension['select']);
			}
			if(!empty($extension['group_by']))
			{
				$this->logcachedb->group_by($extension['group_by'][0], $extension['group_by'][1]);
			}
			if(!empty($extension['order_by']))
			{
				$this->logcachedb->order_by($extension['order_by'][0], $extension['order_by'][1]);
			}
		}
		if($limit==0 && $offset==0) {
			$query = $this->logcachedb->get($this->accountTable);
		} else {
			$query = $this->logcachedb->get($this->accountTable, $limit, $offset);
		}
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function update($id, $row)
	{
		if(!empty($id) && is_array($id) && !empty($row))
		{
			$this->logcachedb->where('server_id', $id['server_id']);
			$this->logcachedb->where('log_date', $id['log_date']);
			$this->logcachedb->where('log_hour', $id['log_hour']);
			return $this->logcachedb->update($this->accountTable, $row);
		}
		else
		{
			return false;
		}
	}
	
	public function delete($id)
	{
		if(!empty($id) && is_array($id))
		{
			$this->logcachedb->where('server_id', $id['server_id']);
			$this->logcachedb->where('log_date', $id['log_date']);
			$this->logcachedb->where('log_hour', $id['log_hour']);
			return $this->logcachedb->delete($this->accountTable);
		}
		else
		{
			return false;
		}
	}
	
	public function query($sql)
	{
		if(!empty($sql))
		{
			$query = $this->logcachedb->query($sql);
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}
		return false;
	}
}

?>