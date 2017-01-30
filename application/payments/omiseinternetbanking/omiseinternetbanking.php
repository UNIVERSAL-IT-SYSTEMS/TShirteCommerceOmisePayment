<?php
/**
 * @author Adel Waehayi - www.adeeisme.com
 * @date: 2017-01-19
 * 
 * internet banking payment with omise
 * 
 * @copyright  Copyright (C) 2017 adeeisme.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Omiseinternetbanking
{
	function __construct($options)
	{
		$this->ini = $options;		
	}
	
	function action($data = array(), $post = array(), $id)
	{
        $ci = & get_instance();
        $ci->load->model('order_m');
		$lang = getLanguages();

        $order = $ci->order_m->getOrderNumber($data['item_number']);

        define('OMISE_API_VERSION', '2014-07-27');
        define('OMISE_PUBLIC_KEY', $this->ini['api_public_key']);
        define('OMISE_SECRET_KEY', $this->ini['api_secret_key']);
        if(in_array($post['internet_banking'],array('bay','bbl','ktb','scb')))
        {
	        require_once ROOTPATH.DS.'application'.DS.'third_party'.DS.'omise-php-master'.DS.'lib'.DS.'Omise.php';
            $charge = OmiseCharge::create(array(
                'description' => $data['item_number'],
                'amount' => round($data['amount']*100),
                'currency' => $data['currency_code'],
                'return_uri' => site_url('omise/callback/'.$order->id),
                'offsite' => 'internet_banking_'.$post['internet_banking'],
            ));

		    header('Location: ' . $charge->offsetGet('authorize_uri'));
        }
        else
        {
		    $ci->session->set_flashdata('error', language('payment_select_error', $lang));
			redirect(site_url('cart/checkout'));
        }
	}
}
?>