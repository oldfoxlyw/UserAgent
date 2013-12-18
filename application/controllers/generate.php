<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Generate extends CI_Controller
{
	private $root_path = null;
	
	public function index()
	{
		$this->load->helper ( 'security' );
		$this->load->library ( 'Guid' );
		echo do_hash ( $this->guid->toString () );
	}
	
	public function code()
	{
		$prefix = $this->input->get ( 'prefix', TRUE ); // 激活码前缀
		$limit = $this->input->get ( 'limit', TRUE ); // 激活码的位数
		$count = $this->input->get ( 'count', TRUE ); // 激活码个数
		$comment = $this->input->get( 'comment', TRUE );
		
		$this->load->model('mcode');
		
		$numberPool = array (
				'0',
				'1',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7',
				'8',
				'9' 
		);
		
		$prefix = empty ( $prefix ) ? '' : $prefix;
		$limit = empty ( $limit ) ? 8 : intval ( $limit );
		$count = empty ( $count ) ? 100 : intval ( $count );
		$comment = empty( $comment ) ? '' : $comment;
		
		if(!empty($prefix))
		{
			$prefixLength = strlen($prefix);
		}
		else
		{
			$prefixLength = 0;
		}
		
		for($i = 0; $i < $count; $i++)
		{
			$code = '';
			for($j = 0; $j < $limit - $prefixLength; $j++)
			{
				$code .= $numberPool[rand(0, 9)];
			}
			$code = $prefix . $code;
			
			$parameter = array(
					'code'		=>	$code,
					'comment'	=>	$comment
			);
			if(!$this->mcode->create($parameter))
			{
				$i--;
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */