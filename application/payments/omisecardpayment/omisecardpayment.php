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

class Omisecardpayment
{
	function __construct($options)
	{
		$this->ini = $options;		
	}
	
	function action($data = array(), $post = array(), $id)
	{		
		$ci = & get_instance();
		$ci->load->library('session');
		$lang = getLanguages();

        define('OMISE_API_VERSION', '2014-07-27');
        define('OMISE_PUBLIC_KEY', $this->ini['api_public_key']);
        define('OMISE_SECRET_KEY', $this->ini['api_secret_key']);

        require_once ROOTPATH.DS.'application'.DS.'third_party'.DS.'omise-php-master'.DS.'lib'.DS.'Omise.php';
        try{
            $token = OmiseToken::create(
                array(
                    'card' => array(
                        'name' => $post['card_name'],
                        'number' => $post['card_num'],
                        'expiration_month' => $post['expired_date']['m'],
                        'expiration_year' => $post['expired_date']['y'],
                        'security_code' => $post['cvv_num']
                    )
                )
            );
            if(is_object($token)&&!empty($token->offsetGet('id'))){
                $charge = OmiseCharge::create(array(
                    'amount' => round($data['amount']*100),
                    'currency' => $data['currency_code'],
                    'card' => $token->offsetGet('id')
                ));
                if($charge->offsetGet('status')=='successful')
                {
                    $money = $charge->offsetGet('amount')/100;

                    $ci->load->model('order_m');
                    $order = $ci->order_m->getOrderNumber($data['item_number']);

                    if(isset($order->total) && $money == round($order->total,2))
                    {
                        $update['status'] = 'completed';
                        $updatehis['order_id'] = $order->id;
                        $updatehis['label'] = 'order_status';
                        $updatehis['content'] = json_encode(array($order->order_number=>'completed'));
                        $updatehis['date'] = date('Y-m-d H:i:s');
                        $ci->order_m->_table_name = 'orders';
                        if($ci->order_m->save($update, $order->id))
                        {
                            $ci->order_m->_table_name = 'orders_histories';
                            $ci->order_m->save($updatehis);
                            
                            $user = $ci->order_m->getUser($order->id);
                            //params shortcode email.
                            $params = array(
                                'username'=>$user->username,
                                'email'=>$user->email,
                                'date'=>date('Y-m-d H:i:s'),
                                'total'=>number_format($money, 2),
                                'order_number'=>$data['item_number'],
                                'status'=>'completed',
                            );
                            
                            $subject = configEmail('sub_order_status', $params);
                            $message = configEmail('order_status', $params);
                            
                            $ci->load->library('email');
                            $ci->email->from(getEmail(config_item('admin_email')), getSiteName(config_item('site_name')));
                            $ci->email->to($user->email);    
                            $ci->email->subject ( $subject);
                            $ci->email->message ($message);   
                            $ci->email->send();
                        }
                        $ci->session->set_flashdata('msg', language('payment_success_msg', $lang));
                        if(isset($ci->ini['message']))
                            $ci->session->set_flashdata('message', $ci->ini['message']);
                    }
                    else
                    {
		                throw new Exception();
                    }
                }
                else
                {
		            throw new Exception();
                }
            }
            else
            {
		        throw new Exception();
            }
		    redirect(site_url('payment/confirm'));
        }
        catch(Exception $e)
        {
            if($excomise = $e->getOmiseError())
		        $ci->session->set_flashdata('error', language($excomise['code'], $lang));
            else
		        $ci->session->set_flashdata('error', language('payment_error_msg', $lang));
            redirect(site_url('cart/checkout'));
        }
	}
}
?>