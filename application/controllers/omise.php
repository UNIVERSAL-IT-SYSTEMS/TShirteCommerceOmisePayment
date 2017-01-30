<?php
/**
 * @author Adel Waehayi - www.adeeisme.com
 * @date: 2017-01-19
 * 
 * omise
 * 
 * @copyright  Copyright (C) 2017 adeeisme.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Omise extends Frontend_Controller
{
	public function __construct(){
		parent::__construct();	
	}
	
	public function index()
	{	
	    redirect(site_url());
	}
	
	public function events()
	{
        /**
        * Event Webhook
        *  This function will be call directly by omise, no relation between customer
        * Instruction
        *  put 'http(s)://path-to-your-website/omise/events' on webhook section
        */
        if($post = json_decode(file_get_contents('php://input')))
        {
            $this->load->model('order_m');
            $order = $this->order_m->getOrderNumber($post->data->description);

            if(isset($order->total))
            {
                $this->load->model('omise_m');
                $data = array(
                    "order_id" => $order->id,
                    "charge_id" => $post->data->id,
                    "status" => 'pending',
                    "created_on" => date('Y-m-d H:i:s'),
                    "modified_on" 	=> date('Y-m-d H:i:s')
                );
                $this->omise_m->save($data);
            }
        }
	}

    public function callback($order_id){
        /**
        * CallBack
        *  This function is a page for customer to be landed after success or fail making netbanking payment
        * Instruction
        *  nothing to do here
        */
        try{
            if(is_numeric($order_id)){
                $lang = getLanguages();
                $this->load->model('order_m');
                if($order = $this->order_m->getOrder($order_id))
                {
                    if($order->status=='completed')
                    {
                        $this->session->set_flashdata('msg', language('payment_success_msg', $lang));
                        redirect(site_url('payment/confirm'));
                    }
                    elseif($order->status=='pending')
                    {
                        $file = ROOTPATH.DS.'application'.DS.'third_party'.DS.'omise-php-master'.DS.'lib'.DS.'Omise.php';
                        if(file_exists($file))
                        {
                            $this->load->model('payment_m');
                            $rows = $this->payment_m->getData();
                            foreach($rows as $row)
                                if($row->type=='omiseinternetbanking'){
                                    $ini = json_decode($row->configs,true);
                                    break;
                                }
                            
                            if(empty($ini))
                                throw new Exception('Unknown Payment Method');

                            define('OMISE_API_VERSION', '2014-07-27');
                            define('OMISE_PUBLIC_KEY', $ini['api_public_key']);
                            define('OMISE_SECRET_KEY', $ini['api_secret_key']);

                            include_once($file);

                            $this->load->model('omise_m');
                            if($omise_payment = $this->omise_m->getOrder($order->id))
                            {
                                $charge = OmiseCharge::retrieve($omise_payment->charge_id);
                                if($charge->offsetGet('status')=='successful')
                                {
                                    $money = $charge->offsetGet('amount')/100;
                                        
                                    if(isset($order->total) && $money == round($order->total,2))
                                    {
                                        $updatehis['order_id'] = $order->id;
                                        $updatehis['label'] = 'order_status';
                                        $updatehis['content'] = json_encode(array($order->order_number=>'completed'));
                                        $updatehis['date'] = date('Y-m-d H:i:s');
                                        if($this->order_m->save(array('status'=>'completed'), $order->id))
                                        {
                                            $this->order_m->_table_name = 'orders_histories';
                                            $this->order_m->save($updatehis);
                                            
                                            $this->omise_m->save(array('status'=>$charge->offsetGet('status')), $omise_payment->id);

                                            $this->load->helper('cms');
                                            $user = $this->order_m->getUser($order->id);
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
                                            
                                            $this->load->library('email');
                                            $this->email->from(getEmail(config_item('admin_email')), getSiteName(config_item('site_name')));
                                            $this->email->to($user->email);    
                                            $this->email->subject ( $subject);
                                            $this->email->message ($message);   
                                            $this->email->send();

                                            $this->session->set_flashdata('msg', sprintf(language('payment_success_msg', $lang),$order->order_number));
                                            redirect(site_url('payment/confirm'));
                                        }
                                    }
                                }
                                elseif($charge->offsetGet('status')=='failed')
                                {
                                    if($this->order_m->save(array('status'=>'refused'), $omise_payment->order_id))
                                    {
                                        $this->omise_m->save(array('status'=>$charge->offsetGet('status')), $omise_payment->id);
                                    }
                                    $this->session->set_flashdata('error', language('payment_error_msg', $lang));
                                    redirect(site_url('cart/checkout'));
                                }
                            }
                            else
                            {
		                        $this->load->view('omise/wait');
                            }
                        }
                    }
                    elseif($order->status=='refused')
                    {
                        $this->session->set_flashdata('error', language('payment_error_msg', $lang));
                        redirect(site_url('cart/checkout'));
                    }
                }
            }
            else
            {
                redirect(site_url('cart/checkout'));
            }
        }
        catch(Exception $e)
        {
            /* have to do something here in case there is an error */
        }
    }
}
?>