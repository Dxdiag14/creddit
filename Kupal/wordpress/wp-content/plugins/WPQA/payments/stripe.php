<?php

/* @author    2codeThemes
*  @package   WPQA/payments
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Stripe payment */
add_action('wp_ajax_wpqa_stripe_payment','wpqa_stripe_payment');
add_action('wp_ajax_nopriv_wpqa_stripe_payment','wpqa_stripe_payment');
function wpqa_stripe_payment() {
	$result        = array();
	$user_id       = get_current_user_id();
	$custom        = (isset($_POST['custom'])?esc_html($_POST['custom']):'');
	$item_name     = esc_html($_POST['item_name']);
	$item_number   = esc_html($_POST['item_number']);
	$name          = esc_html($_POST['name']);
	$payer_email   = esc_html($_POST['email']);
	$line1         = (isset($_POST['line1'])?esc_html($_POST['line1']):'');
	$line1         = (isset($_POST['line1'])?esc_html($_POST['line1']):'');
	$postal_code   = (isset($_POST['postal_code'])?esc_html($_POST['postal_code']):'');
	$country       = (isset($_POST['country'])?esc_html($_POST['country']):'');
	$city          = (isset($_POST['city'])?esc_html($_POST['city']):'');
	$state         = (isset($_POST['state'])?esc_html($_POST['state']):'');
	$payment       = floatval($_POST['payment']);
	$item_price    = floatval($payment*100);
	$str_replace   = str_replace('wpqa_'.$item_number.'-','',$custom);
	$currency_code = wpqa_get_currency($user_id);

	if ($line1 != '') {
		update_user_meta($user_id,'line1',$line1);
	}
	if ($line1 != '') {
		update_user_meta($user_id,'line1',$line1);
	}
	if ($postal_code != '') {
		update_user_meta($user_id,'postal_code',$postal_code);
	}
	if ($country != '') {
		update_user_meta($user_id,'country',$country);
	}
	if ($city != '') {
		update_user_meta($user_id,'city',$city);
	}
	if ($state != '') {
		update_user_meta($user_id,'state',$state);
	}
	
	require_once plugin_dir_path(dirname(__FILE__)).'payments/stripe/init.php';
	\Stripe\Stripe::setApiKey(wpqa_options('secret_key'));
	try {
		if (strpos($custom,'wpqa_subscribe-') !== false && $str_replace != 'lifetime') {
			$product_id = get_option("wpqa_product_".$str_replace);
			if ($product_id != "") {
				$get_product = \Stripe\Product::retrieve($product_id);
			}
			if (!isset($get_product) || (isset($get_product) && !isset($get_product->id))) {
				wpqa_stripe_new_product($item_name);
			}

			$plan_id = get_option("wpqa_plan_".$str_replace."_".strtolower($currency_code));
			if ($plan_id != "") {
				$get_plan = \Stripe\Plan::retrieve($plan_id);
				if (isset($get_plan->amount) && $get_plan->amount != $item_price) {
					$get_plan->delete();
					$plan_not_found = true;
				}else if (!isset($get_plan->amount)) {
					$plan_not_found = true;
				}
			}
			if (isset($plan_not_found) || !isset($get_plan) || (isset($get_plan) && !isset($get_plan->id))) {
				wpqa_stripe_new_plan($str_replace,$currency_code,$product_id,$item_name,$item_price);
			}
		}
		if (isset($_POST['payment-intent-id']) && $_POST['payment-intent-id'] != '') {
			$charge = \Stripe\PaymentIntent::retrieve(esc_html($_POST['payment-intent-id']));
			wpqa_finish_stripe_payment($charge->payment_method,$charge->customer);
			if (isset($charge->status) && ($charge->status == 'active' || $charge->status == 'paid' || $charge->status == 'succeeded')) {
				$success = true;
			}else {
				$result['success'] = 0;
				$result['error']   = esc_html__('Transaction has been failed.','wpqa');
			}
		}else if (isset($_POST['payment-method-id']) && $_POST['payment-method-id'] != '') {
			if (!isset($_POST['wpqa_stripe_nonce']) || !wp_verify_nonce($_POST['wpqa_stripe_nonce'],'wpqa_stripe_nonce')) {
				$result['success'] = 0;
				$result['error']   = esc_html__('There is an error, Please reload the page and try again.','wpqa');
			}else {
				$payment_method_id = esc_html($_POST['payment-method-id']);
				$args = array(
					'payment_method'   => $payment_method_id,
					'name'             => $name,
					'email'            => $payer_email,
					'invoice_settings' => array(
						'default_payment_method' => $payment_method_id
					)
				);
				$customer_address = array();
				if ($line1 != '') {
					$customer_address['line1'] = $line1;
				}
				if ($country != '') {
					$customer_address['country'] = $country;
				}
				if ($city != '') {
					$customer_address['city'] = $city;
				}
				if ($state != '') {
					$customer_address['state'] = $state;
				}
				if ($postal_code != '') {
					$customer_address['postal_code'] = $postal_code;
				}
				if (isset($customer_address) && !empty($customer_address)) {
					$args['address'] = $customer_address;
				}
				$customer_description = $item_name;
				if (isset($customer_description) && $customer_description != '') {
					$args['description'] = $customer_description;
				}
				if (isset($customer_metadata)) {
					$args['metadata'] = $customer_metadata;
				}
				$customer = \Stripe\Customer::create($args);
				update_user_meta($user_id,'wpqa_stripe_customer',$customer->id);
				if (strpos($custom,'wpqa_subscribe-') !== false && $str_replace != 'lifetime') {
					$_coupon = get_user_meta($user_id,$user_id.'_coupon',true);
					if ($_coupon != '') {
						$coupons = wpqa_options('coupons');
						$wpqa_find_coupons = wpqa_find_coupons($coupons,$_coupon);
						$coupon_name = preg_replace('/[^a-zA-Z0-9._\-]/','',strtolower($coupons[$wpqa_find_coupons]['coupon_name']));
						$coupon_amount = (int)$coupons[$wpqa_find_coupons]['coupon_amount'];
						$coupon_type = $coupons[$wpqa_find_coupons]['coupon_type'];
						$coupon_id = $coupon_amount.'_'.$coupon_name;
						$get_coupon = \Stripe\Coupon::retrieve($coupon_id);
						if ($coupon_type == "percent") {
							$the_discount = ($payment*$coupon_amount)/100;
							$payment = $payment-$the_discount;
						}else if ($coupon_type == "discount") {
							$payment = $payment-$coupon_amount;
						}
					}

					$charge = \Stripe\Subscription::create([
						'customer' => $customer->id,
						'items'    => [['plan' => $plan_id]],
						'metadata' => ['order_id' => $item_number],
						'expand'   => ['latest_invoice.payment_intent'],
						'coupon'   => (isset($get_coupon) && isset($get_coupon->id)?$get_coupon->id:''),
					]);
					update_user_meta($user_id,"wpqa_subscr_id",$charge->id);
				}else {
					$wpqa_stripe_customer = get_user_meta($user_id,'wpqa_stripe_customer',true);
					$args = array(
						'amount'              => $item_price,
						'currency'            => $currency_code,
						'confirmation_method' => 'automatic',
						'confirm'             => true,
						'customer'            => $wpqa_stripe_customer,
						'payment_method'      => $payment_method_id,
					);
					if (isset($payment_metadata) && !empty($payment_metadata)) {
						$args['metadata'] = $payment_metadata;
					}
					$payment_description = $item_name;
					if (isset($payment_description) && $payment_description != '') {
						$args['description'] = $payment_description;
					}
					$charge = \Stripe\PaymentIntent::create($args);
				}
				if (isset($charge->status) && (($charge->status == 'requires_action' && $charge->next_action->type == 'use_stripe_sdk') || $charge->status == 'incomplete')) {
					if ($charge->status == 'incomplete' && strpos($custom,'wpqa_subscribe-') !== false && isset($payment_method_id)) {
						wpqa_finish_stripe_payment($payment_method_id,$charge->customer);
					}
					$result['confirm_card']   = 1;
					$result['success']        = 0;
					$result['client_secret']  = (isset($charge->client_secret)?esc_html($charge->client_secret):(isset($charge->latest_invoice->payment_intent->client_secret)?esc_html($charge->latest_invoice->payment_intent->client_secret):''));
					$result['payment_method'] = $charge->id;
				}else if ($charge->status == 'active' || $charge->status == 'paid' || $charge->status == 'succeeded') {
					$success = true;
				}else {
					$result['success'] = 0;
					$result['error']   = esc_html__('Transaction has been failed.','wpqa');
				}
			}
		}else {
			$result['success'] = 0;
			$result['error']   = esc_html__('Transaction has been failed.','wpqa');
		}
		if (isset($success) && $success == true) {
			$wpqa_subscr_id = get_user_meta($user_id,"wpqa_subscr_id",true);
			$response = $charge->jsonSerialize();
			$subscr_id = ($wpqa_subscr_id != ""?$wpqa_subscr_id:(isset($charge->id)?$charge->id:$charge->id));
			$redirect_to = wpqa_get_redirect_link($custom,$item_number,$user_id);
			$result['success']  = 1;
			$result['redirect'] = $redirect_to;
			$array = array (
				'item_no'          => $item_number,
				'item_name'        => $item_name,
				'item_price'       => $payment,
				'item_currency'    => $currency_code,
				'item_transaction' => (isset($response['charges']['data'][0]['balance_transaction'])?$response['charges']['data'][0]['balance_transaction']:(isset($response['latest_invoice']['payment_intent']['charges']['data'][0]['balance_transaction'])?$response['latest_invoice']['payment_intent']['charges']['data'][0]['balance_transaction']:'')),
				'custom'           => $custom,
				'sandbox'          => '',
				'payment'          => 'Stripe',
				'id'               => ($subscr_id == $response['id']?(isset($response['latest_invoice']['payment_intent']['id'])?$response['latest_invoice']['payment_intent']['id']:$response['id']):$response['id']),
				'customer'         => ($wpqa_subscr_id == ""?$response['customer']:""),
				'subscr_id'        => $subscr_id,
			);
			wpqa_payment_succeeded($user_id,$array);
		}else if (!isset($result['confirm_card'])) {
			$result['success'] = 0;
			$result['error']   = esc_html__('Transaction has been failed.','wpqa');
		}
	}catch ( \Stripe\Exception\CardException $e ) {
		$result['success'] = 0;
		$result['error']   = $e->getError()->message;
	}catch ( Exception $e ) {
		$error_message = $e->getMessage();
		if (strpos($custom,'wpqa_subscribe-') !== false && strpos($error_message,'No such plan:') !== false && $str_replace != 'lifetime') {
			wpqa_stripe_new_plan($str_replace,$currency_code,$product_id,$item_name,$item_price);
			$result['resubmit_again'] = 1;
		}else if (strpos($custom,'wpqa_subscribe-') !== false && strpos($error_message,'No such product:') !== false && $str_replace != 'lifetime') {
			wpqa_stripe_new_product($item_name);
			$result['resubmit_again'] = 1;
		}
		$result['success'] = 0;
		if (!isset($result['resubmit_again'])) {
			$result['error'] = $error_message;
		}
	}
	echo json_encode(apply_filters('wpqa_json_stripe_payment',$result));
	die();
}
/* Create a new product */
function wpqa_stripe_new_product($item_name) {
	$product = \Stripe\Product::create([
		'name' => $item_name,
		'type' => 'service',
	]);
	if (isset($product->id)) {
		$product_id = $product->id;
		update_option("wpqa_product_".$str_replace,$product->id);
	}
}
/* Create a new plan */
function wpqa_stripe_new_plan($str_replace,$currency_code,$product_id,$item_name,$item_price) {
	$interval = ($str_replace == 'yearly' || $str_replace == '2years'?'year':'month');
	$interval_count = ($str_replace == 'monthly' || $str_replace == 'yearly' || $str_replace == '2years'?($str_replace == '2years'?2:1):($str_replace == '3months'?3:6));
	$plan = \Stripe\Plan::create([
		'currency'       => $currency_code,
		'interval'       => $interval,
		'interval_count' => $interval_count,
		'product'        => $product_id,
		'nickname'       => $item_name,
		'amount'         => $item_price,
	]);
	if (isset($plan->id)) {
		$plan_id = $plan->id;
		update_option("wpqa_plan_".$str_replace."_".strtolower($currency_code),$plan->id);
	}
}
/* Finish stripe payment */
function wpqa_finish_stripe_payment($payment_method_id,$get_the_customer_id) {
	require_once plugin_dir_path(dirname(__FILE__)).'payments/stripe/init.php';
	\Stripe\Stripe::setApiKey(wpqa_options("secret_key"));
	$payment_method = \Stripe\PaymentMethod::retrieve($payment_method_id);
	$payment_method->attach(['customer' => $get_the_customer_id]);
	$update_customer = \Stripe\Customer::update(
		$get_the_customer_id,[
			'invoice_settings' => [
				'default_payment_method' => $payment_method_id,
			],
		]
	);
}
/* Stripe webhooks */
function wpqa_stripe_data_webhooks() {
	if (isset($_GET["action"]) && $_GET["action"] == "stripe") {
		$input = @file_get_contents('php://input');
		$response = json_decode($input);
		if (isset($response->data->object)) {
			$request = $response->data->object;
			if (isset($response->type) && $response->type == "charge.refunded") {
				$item_transaction = $request->balance_transaction;
				$args = array(
					'meta_key'       => 'payment_item_transaction',
					'meta_value'     => $item_transaction,
					'post_type'      => 'statement',
					'posts_per_page' => -1
				);
				$query = new WP_Query($args);
				if ($query->have_posts()) {
					$post_id = (isset($query->posts[0]->ID)?$query->posts[0]->ID:0);
					if ($post_id > 0) {
						$item_transaction_refund = $request->refunds->data[0]->id;
						$user_id = $query->posts[0]->post_author;
						if (!wpqa_find_refund($item_transaction_refund)) {
							$item_currency = get_post_meta($post_id,"payment_item_currency",true);
							$item_number = get_post_meta($post_id,"payment_item_number",true);
							$item_price = (isset($request->amount)?floatval($request->amount/100):get_post_meta($post_id,"payment_item_price",true));
							wpqa_refund_canceled_payment($user_id,$post_id,$item_number);
							$response = array(
								"item_name"            => get_the_title($post_id),
								"item_price"           => $item_price,
								"item_currency"        => $item_currency,
								"item_transaction"     => $item_transaction_refund,
								"original_transaction" => $item_transaction,
							);
							wpqa_insert_refund($response,$user_id,"refund");
							wpqa_site_user_money($item_price,"-",$item_currency,$user_id);
							update_post_meta($post_id,"payment_refund","refund");
							update_post_meta($post_id,"payment_original_transaction",$item_transaction_refund);
						}
					}
				}
			}else if (isset($request->customer) && $request->customer != "") {
				$status = (isset($request->status)?$request->status:"");
				if (($status == "active" || $status == "paid" || $status == "succeeded") && $user_id > 0) {
					$args = array(
						'meta_key'       => 'payment_customer',
						'meta_value'     => $request->customer,
						'post_type'      => 'statement',
						'posts_per_page' => -1
					);
					$query = new WP_Query($args);
					if ($query->have_posts()) {
						$post_id = (isset($query->posts[0]->ID)?$query->posts[0]->ID:0);
						if ($post_id > 0) {
							$user_id = $query->posts[0]->post_author;
							$package_subscribe = get_post_meta($post_id,"payment_replace",true);
							if ($package_subscribe == "") {
								$package_subscribe = get_user_meta($user_id,"package_subscribe",true);
							}
						}
					}else {
						$users = get_users(array('meta_key' => 'subscribe_renew_id','meta_value' => $request->customer,'number' => 1,'count_total' => false));
						$user_id = (isset($users[0]) && isset($users[0]->ID) && $users[0]->ID > 0?$users[0]->ID:0);
					}
					if (isset($user_id) && $user_id > 0) {
						$package_subscribe = get_user_meta($user_id,"package_subscribe",true);
						$currency_code = wpqa_get_currency($user_id);
						$array = array(
							"free"     => array("key" => "free","name" => esc_html__("Free membership","wpqa")),
							"monthly"  => array("key" => "monthly","name" => esc_html__("Monthly membership","wpqa")),
							"3months"  => array("key" => "3months","name" => esc_html__("Three months membership","wpqa")),
							"6months"  => array("key" => "6months","name" => esc_html__("Six Months membership","wpqa")),
							"yearly"   => array("key" => "yearly","name" => esc_html__("Yearly membership","wpqa")),
							"2years"   => array("key" => "2years","name" => esc_html__("Two Years membership","wpqa")),
							"lifetime" => array("key" => "lifetime","name" => esc_html__("Lifetime membership","wpqa")),
						);
						$payment_description = esc_html__("Paid membership","wpqa").(isset($array[$package_subscribe]["name"]) && $array[$package_subscribe]["name"] != ""?" - ".$array[$package_subscribe]["name"]:"")." ".esc_html__("(Renew)","WPQA");
						$array = array (
							'item_no'          => 'subscribe',
							'item_name'        => $payment_description,
							'item_price'       => ($request->amount/100),
							'item_currency'    => $currency_code,
							'item_transaction' => (isset($request->balance_transaction) && $request->balance_transaction != ""?$request->balance_transaction:(isset($request->invoice) && $request->invoice != ""?$request->invoice:'')),
							'payer_email'      => $user->user_email,
							'first_name'       => $user->first_name,
							'last_name'        => $user->last_name,
							'sandbox'          => '',
							'payment'          => 'Stripe',
							"customer"         => $request->customer,
							'renew'            => 'subscribe',
							'custom'           => 'wpqa_subscribe-'.$package_subscribe,
						);
						wpqa_payment_succeeded($user_id,$array);
					}
				}
				http_response_code(200);
				die();
			}
		}
	}
}?>