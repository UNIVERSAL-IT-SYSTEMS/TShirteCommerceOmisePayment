<?php
/**
 * @author Adel Waehayi - www.adeeisme.com
 * @date: 2017-01-23
 * 
 * order omise
 * 
 * @copyright  Copyright (C) 2017 adeeisme.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Omise_m extends MY_Model
{
	
	public $_table_name = 'payment_omise';
	public $_order_by 	= 'id';
	public $_primary_key = 'id';
	
	function __construct ()
	{
		parent::__construct();
	}
	
	// convert object to array
	public function fields($fields)
	{
		$this->load->helper('security');
		
		$data = array();
		if (count($fields))
		{
			foreach($fields as $key=>$value)
			{
				$data[$key]	= xss_clean(strip_tags($value));
			}
		}
		
		return $data;
	}

	function getPayments($status = '')
	{
        if(!empty($status)){
            $this->db->where('status', $status);
        }
		$query = $this->db->get('payment_omise');
		return $query->result();
	}

	function getOrder($order_id = '')
	{
        if(!empty($order_id)){
            $this->db->where('order_id', $order_id);
        }
		$query = $this->db->get('payment_omise');
		
		return $query->row();
	}
}