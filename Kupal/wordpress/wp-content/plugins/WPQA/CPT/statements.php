<?php

/* @author    2codeThemes
*  @package   WPQA/CPT
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Insert a new payment */
function wpqa_insert_payment($response,$user_id,$_coupon = '',$_coupon_value = '') {
	$item_transaction = (isset($response['item_transaction']) && $response['item_transaction'] != ""?$response['item_transaction']:"");
	$payment_method = (isset($response['payment']) && $response['payment'] != ""?$response['payment']:"");
	$data = array(
		'post_title'  => $response["item_name"],
		'post_status' => "publish",
		'post_author' => $user_id,
		'post_type'   => 'statement'
	);
	$post_id = wp_insert_post($data);
	if ($post_id == 0 || is_wp_error($post_id)) {
		error_log(esc_html__("Error in post.","wpqa"));
	}else {
		if ($item_transaction != "") {
			update_post_meta($post_id,"payment_item_transaction",$item_transaction);
		}
		$variables = array(
			"payment_new" => 1,
		);
		if (isset($response['item_no']) && $response['item_no'] != "") {
			$variables["payment_item_number"] = $response['item_no'];
		}
		if (isset($response['item_price']) && $response['item_price'] != "") {
			$variables["payment_item_price"] = $response['item_price'];
		}
		if (isset($response['item_currency']) && $response['item_currency'] != "") {
			$variables["payment_item_currency"] = $response['item_currency'];
		}
		if (isset($response['sandbox']) && $response['sandbox'] != "") {
			$variables["payment_sandbox"] = $response['sandbox'];
		}
		if (isset($_coupon) && $_coupon != "") {
			$variables["payment_coupon"] = $_coupon;
		}
		if (isset($_coupon_value) && $_coupon_value != "") {
			$variables["payment_coupon_value"] = $_coupon_value;
		}
		if (isset($response['id']) && $response['id'] != "") {
			$variables["payment_id"] = $response['id'];
		}
		if (isset($response['customer']) && $response['customer'] != "") {
			$variables["payment_customer"] = $response['customer'];
		}
		if (isset($response['subscr_id']) && $response['subscr_id'] != "") {
			$variables["payment_subscr_id"] = $response['subscr_id'];
		}
		if ($payment_method != "") {
			$variables["payment_method"] = $payment_method;
		}
		if (isset($response['points']) && $response['points'] != "") {
			$variables["payment_points"] = $response['points'];
		}
		$user_email = get_the_author_meta('user_email',$user_id);
		if (isset($response['payer_email']) && $user_email != $response['payer_email']) {
			if (isset($response['payer_email']) && $response['payer_email'] != "") {
				$variables["payment_payer_email"] = $response['payer_email'];
			}
			if (isset($response['first_name']) && $response['first_name'] != "") {
				$variables["payment_first_name"] = $response['first_name'];
			}
			if (isset($response['last_name']) && $response['last_name'] != "") {
				$variables["payment_last_name"] = $response['last_name'];
			}
		}
		if (isset($response['renew']) && $response['renew'] != "") {
			$variables["payment_renew"] = $response['renew'];
		}
		if (isset($response['reward']) && $response['reward'] != "") {
			$variables["payment_reward"] = $response['reward'];
		}
		if (isset($response['trial']) && $response['trial'] != "") {
			$variables["payment_trial"] = $response['trial'];
		}
		if (isset($response['payment_asked']) && $response['payment_asked'] != "") {
			$variables["payment_asked"] = $response['payment_asked'];
		}
		if (isset($response['payment_package']) && $response['payment_package'] != "") {
			$variables["payment_package"] = $response['payment_package'];
		}
		if (isset($response['subscribe']) && $response['subscribe'] != "") {
			$variables["payment_subscribe"] = $response['subscribe'];
		}
		if (isset($response['item_no']) && isset($response['custom'])) {
			$str_replace = str_replace("wpqa_".$response['item_no']."-","",$response['custom']);
			if (strpos($response['custom'],'wpqa_ask_question-') !== false) {
				if (is_numeric($str_replace)) {
					$payment_asked = $str_replace;
				}
			}else if (strpos($response['custom'],'wpqa_buy_questions-') !== false) {
				$explode = explode("-",$str_replace);
				if (isset($explode[1]) && $explode[1] != "") {
					$payment_asked = $explode[1];
				}
			}else if ($str_replace != "") {
				$variables["payment_replace"] = $str_replace;
			}
			if (isset($payment_asked) && $payment_asked != "") {
				$variables["payment_asked"] = $payment_asked;
			}
			if (isset($explode[0]) && $explode[0] != "") {
				$variables["payment_replace"] = $explode[0];
			}
		}
		if (isset($response['custom']) && $response['custom'] != "") {
			$variables["payment_custom"] = $response['custom'];
		}
		foreach ($variables as $key => $value) {
			update_post_meta($post_id,$key,$value);
		}
	}
}
/* Insert a new refund */
function wpqa_insert_refund($response,$user_id,$type = 'refund') {
	$data = array(
		'post_title'  => $response["item_name"],
		'post_status' => "publish",
		'post_author' => $user_id,
		'post_type'   => 'statement'
	);
	$post_id = wp_insert_post($data);
	if ($post_id == 0 || is_wp_error($post_id)) {
		error_log(esc_html__("Error in post.","wpqa"));
	}else {
		$variables = array(
			"payment_new"    => 1,
			"statement_type" => $type
		);
		if (isset($response['item_price']) && $response['item_price'] != "") {
			$variables["payment_item_price"] = $response['item_price'];
		}
		if (isset($response['item_currency']) && $response['item_currency'] != "") {
			$variables["payment_item_currency"] = $response['item_currency'];
		}
		if (isset($response['item_transaction']) && $response['item_transaction'] != "") {
			$variables["payment_item_transaction"] = $response['item_transaction'];
		}
		if (isset($response['original_transaction']) && $response['original_transaction'] != "") {
			$variables["payment_original_transaction"] = $response['original_transaction'];
		}
		foreach ($variables as $key => $value) {
			update_post_meta($post_id,$key,$value);
		}
	}
}
/* Find refund */
function wpqa_find_refund($item_transaction) {
	$args = array(
		'meta_key'       => 'item_transaction',
		'meta_value'     => $item_transaction,
		'post_type'      => 'statement',
		'posts_per_page' => -1
	);
	$query = new WP_Query($args);
	if ($query->have_posts() && isset($query->posts[0]->ID) && $query->posts[0]->ID > 0) {
		return $query->posts[0]->ID;
	}
}
/* Statements post type */
function wpqa_statement_post_types_init() {
	$payment_available = wpqa_payment_available();
	if ($payment_available == true) {
	    register_post_type( 'statement',
	        array(
		     	'label' => esc_html__('Statements','wpqa'),
		        'labels' => array(
					'name'               => esc_html__('Statements','wpqa'),
					'singular_name'      => esc_html__('Statements','wpqa'),
					'menu_name'          => esc_html__('Statements','wpqa'),
					'name_admin_bar'     => esc_html__('Statements','wpqa'),
					'edit_item'          => esc_html__('Edit Statement','wpqa'),
					'all_items'          => esc_html__('All Statements','wpqa'),
					'search_items'       => esc_html__('Search Statements','wpqa'),
					'parent_item_colon'  => esc_html__('Parent Statement:','wpqa'),
					'not_found'          => esc_html__('No Statements Found.','wpqa'),
					'not_found_in_trash' => esc_html__('No Statements Found in Trash.','wpqa'),
				),
		        'description'         => '',
		        'public'              => false,
		        'show_ui'             => true,
		        'capability_type'     => 'post',
		        'capabilities'        => array('create_posts' => 'do_not_allow'),
		        'map_meta_cap'        => true,
		        'publicly_queryable'  => false,
		        'exclude_from_search' => false,
		        'hierarchical'        => false,
		        'query_var'           => false,
		        'show_in_rest'        => false,
		        'has_archive'         => false,
				'menu_position'       => 5,
				'menu_icon'           => "dashicons-cart",
		        'supports'            => array('title'),
	        )
	    );
	}
}
add_action( 'wpqa_init', 'wpqa_statement_post_types_init', 0 );
/* Get money note */
function wpqa_get_money_note($views) {
	$query = array('posts_per_page' => -1,'post_status' => 'any','post_type' => 'payment');
	$items = get_posts($query);
	if (is_array($items) && !empty($items)) {
		foreach ($items as $post) {
			set_post_type($post->ID,"statement");
		}
	}
	$_payments = (int)get_option("payments_option");
	if ($_payments > 0) {
		$count = $_payments;
		for ($payments = 1; $payments <= $count; $payments++) {
			$payment_item = get_option("payments_".$payments);
			if (!isset($payment_item["moved_done"])) {
				$data = array(
					'post_title'  => $payment_item["item_name"],
					'post_status' => "publish",
					'post_author' => $payment_item["user_id"],
					'post_type'   => 'statement',
					'post_date'   => date("Y-m-d H:i:s",$payment_item["time"])
				);
				$payment_item["moved_done"] = 1;
				update_option("payments_".$payments,$payment_item);
				$_payments--;
				update_option("payments_option",$_payments);
				$post_id = wp_insert_post($data);
				if ($post_id == 0 || is_wp_error($post_id)) {
					error_log(esc_html__("Error in post.","wpqa"));
				}else {
					$variables = array("payment_item_number","payment_item_price","payment_item_currency","payment_item_transaction","payment_sandbox","payment_coupon","payment_coupon_value","payment_new","payment_id","payment_customer","payment_subscr_id","payment_method","payment_points","payment_payer_email","payment_first_name","payment_last_name","payment_subscribe");
					foreach ($variables as $value) {
						$replace = str_ireplace("payment_","",$value);
						if (isset($payment_item[$value]) && $payment_item[$value] != "") {
							update_post_meta($post_id,$value,$payment_item[$value]);
						}else if (isset($payment_item[$replace]) && $payment_item[$replace] != "") {
							update_post_meta($post_id,$value,$payment_item[$replace]);
						}else if ($value == "payment_method" && isset($payment_item["payment"]) && $payment_item["payment"] != "") {
							update_post_meta($post_id,$value,$payment_item["payment"]);
						}else if ($value == "points" && isset($payment_item["points"]) && $payment_item["points"] != "") {
							update_post_meta($post_id,$value,$payment_item["points"]);
						}
					}
				}
			}
			sleep(1);
		}
	}
	
	update_option("new_payments",0);
	$the_currency = get_option("the_currency");
	if (isset($the_currency) && is_array($the_currency)) {
		$count = $k = 0;
		foreach ($the_currency as $key => $currency) {
			if (isset($currency) && $currency != "") {
				$all_money = get_option("all_money_".$currency);
				if ($all_money > 0) {
					$all_money_array[$currency] = $all_money;
					$count++;
				}
			}
		}
		if (isset($all_money_array) && is_array($all_money_array)) {
			foreach ($all_money_array as $currency => $money) {
				$k++;
				if ($money > 0) {
					if ($k == 1) {
						echo '<div class="alert-message alert-message-money warning"><i class="dashicons dashicons-clipboard"></i><span>'.esc_html__("My money","wpqa").'</span></div>';
					}
					if ($k == 1) {
						echo '<div class="alert-message alert-message-money alert-message-normal"><i class="dashicons dashicons-clipboard"></i>';
					}else {
						echo '<div><i class="dashicons dashicons-clipboard"></i>';
					}
					echo '<span>'.(isset($money) && $money != ""?$money:0)." ".$currency.'</span>';
					if ($k != 1) {
						echo '</div>';
					}
					if ($k == $count) {
						echo '</div>';
					}
				}
			}
		}
	}
	do_action("wpqa_action_after_all_money");
	return $views;
}
add_filter('views_edit-statement','wpqa_get_money_note');
/* Admin columns for post types */
function wpqa_statement_columns($old_columns){
	$columns = array();
	$columns["cb"]          = "<input type=\"checkbox\">";
	$columns["item"]        = esc_html__("Title","wpqa");
	$columns["price"]       = esc_html__("Price","wpqa")." - (".esc_html__("coupon","wpqa").")";
	$columns["author_p"]    = esc_html__("Author","wpqa");
	$columns["date_p"]      = esc_html__("Date","wpqa");
	$columns["transaction"] = esc_html__("Transaction","wpqa");
	$columns["email"]       = esc_html__("Payer email","wpqa")." - (".esc_html__("sandbox","wpqa").")";
	return $columns;
}
add_filter('manage_edit-statement_columns', 'wpqa_statement_columns');
function wpqa_statement_primary_column($default,$screen) {
	if ('edit-statement' === $screen) {
		$default = 'item';
	}
	return $default;
}
add_filter('list_table_primary_column','wpqa_statement_primary_column',10,2);
function wpqa_statement_custom_columns($column) {
	global $post;
	$item_price = get_post_meta($post->ID,"payment_item_price",true);
	$item_transaction = get_post_meta($post->ID,"payment_item_transaction",true);
	$payment_method = get_post_meta($post->ID,"payment_method",true);
	if ($payment_method == "Stripe") {
		$payment_id = get_post_meta($post->ID,"payment_id",true);
	}
	$statement_type = get_post_meta($post->ID,"statement_type",true);
	$payment_refund = get_post_meta($post->ID,"payment_refund",true);
	$payment_reversed = get_post_meta($post->ID,"payment_reversed",true);
	$payment_canceled = get_post_meta($post->ID,"payment_canceled",true);
	switch ( $column ) {
		case 'item' :
			echo get_the_title($post->ID);
		break;
		case 'price' :
			$payment_new = get_post_meta($post->ID,"payment_new",true);
			$item_currency = get_post_meta($post->ID,"payment_item_currency",true);
			$points = get_post_meta($post->ID,"payment_points",true);
			$coupon = get_post_meta($post->ID,"payment_coupon",true);
			echo '<span class="money-span'.($payment_canceled == "canceled"?" canceled-span":"").($payment_refund == "refund" || $payment_reversed == "reversed" || $statement_type == "refund" || $statement_type == "reversed"?" refund-span":"").($payment_new == 1?" payment_new":"").'">'.($item_price > 0?($payment_refund == "refund" || $payment_reversed == "reversed" || $statement_type == "refund" || $statement_type == "reversed"?"-":"").floatval($item_price)." ".$item_currency:esc_html__("Free","wpqa").($points != ""?" - ".$points." ".esc_html__("points","wpqa"):"")).'</span>'.($coupon != ""?" <span class='gray-span coupon-span'>(".$coupon.")</span>":"").($payment_refund == "refund" || $payment_reversed == "reversed"?"<span class='money-span refund-span margin_l_20'>".($payment_refund == "refund"?esc_html__("Refunded","wpqa"):esc_html__("Reversed","wpqa"))."</span>":"").($payment_canceled == "canceled"?"<span class='money-span canceled-span margin_l_20'>".esc_html__("Canceled","wpqa")."</span>":"");
			update_post_meta($post->ID,"payment_new",0);
		break;
		case 'author_p' :
			$user_name = get_the_author_meta('display_name',$post->post_author);
			echo '<a target="_blank" href="'.wpqa_profile_url((int)$post->post_author).'"><strong>'.$user_name.'</strong></a><a class="tooltip_s" data-title="'.esc_html__("View statements","wpqa").'" href="'.admin_url('edit.php?post_type=statement&author='.$post->post_author).'"><i class="dashicons dashicons-cart"></i></a><a class="tooltip_s" data-title="'.esc_html__("Edit user","wpqa").'" target="_blank" href="'.admin_url('user-edit.php?user_id='.$post->post_author).'"><i class="dashicons dashicons-admin-users"></i></a>';
			do_action('wpqa_statement_columns_author',$post->post_author,$post);
		break;
		case 'date_p' :
			$date_format = wpqa_options("date_format");
			$date_format = ($date_format?$date_format:get_option("date_format"));
			$human_time_diff = human_time_diff(get_the_time('U'), current_time('timestamp'));
			echo ($human_time_diff." ".esc_html__("ago","wpqa")." - ".esc_html(get_the_time($date_format)));
		break;
		case 'transaction' :
			$original_transaction = get_post_meta($post->ID,"payment_original_transaction",true);
			echo ($item_transaction != ""?esc_html($item_transaction):"---").($payment_method != ""?" <span class='gray-span ".($payment_method == "PayPal"?"paypal-span":"payment_method")."'>".esc_html($payment_method)."</span>":"").($original_transaction != ""?" <span class='gray-span margin_l_20'>".esc_html($original_transaction)."</span>":"");
		break;
		case 'email' :
			$payer_email = get_post_meta($post->ID,"payment_payer_email",true);
			if ($payer_email == "") {
				$payer_email = get_the_author_meta('user_email',$post->post_author);
			}
			$sandbox = get_post_meta($post->ID,"payment_sandbox",true);
			echo ($payer_email != ""?esc_html($payer_email).(isset($sandbox) && $sandbox != ""?" <span class='gray-span sandbox-span'>(".$sandbox.")</span>":""):get_the_author_meta('display_name',$post->post_author));
			if ($payment_refund != "refund" && $payment_reversed != "reversed" && $payment_canceled != "canceled" && $statement_type != "refund" && $statement_type != "canceled" && $statement_type != "reversed" && $item_price > 0 && (($payment_method == "PayPal" && $item_transaction != "") || ($payment_method == "Stripe" && isset($payment_id) && strpos($payment_id,'pi_') !== false))) {
				echo '<a class="button refund-button" data-id="'.$post->ID.'" data-user="'.$post->post_author.'"'.($payment_method == "Stripe" && isset($payment_id) && strpos($payment_id,'pi_') !== false?" data-pi='".$payment_id."'":"").'>'.esc_html__("Make a refund","wpqa").'</a>';
			}
		break;
	}
}
add_action('manage_statement_posts_custom_column','wpqa_statement_custom_columns',2);
/* Statement title */
add_action('admin_head-edit.php','wpqa_edit_statement_change_title');
function wpqa_edit_statement_change_title() {
	global $post;
	if ((isset($_GET["post_type"]) && $_GET["post_type"] == "statement") || (isset($post->post_type) && $post->post_type == "statement")) {
	    add_filter('the_title','wpqa_statement_new_title',100,2);
	}
}
function wpqa_statement_new_title($title,$id) {
	global $post;
	if ($post->post_type == "statement") {
		$payment_item_number = get_post_meta($post->ID,"payment_item_number",true);
		if ($payment_item_number == 'pay_answer') {
			$payment_replace = get_post_meta($post->ID,"payment_replace",true);
			$title = $title.'<a class="tooltip_s" target="_blank" data-title="'.esc_html__("View question","wpqa").'" href="'.get_the_permalink($payment_replace).'"><i class="dashicons dashicons-editor-help"></i></a>';
		}
		return $title;
	}
}
/* Statement menus */
add_action('admin_menu','wpqa_add_admin_statement');
function wpqa_add_admin_statement() {
	add_submenu_page('edit.php?post_type=statement',esc_html__('Payments','wpqa'),esc_html__('Payments','wpqa'),'manage_options','edit.php?post_type=statement&statement=payments');
	add_submenu_page('edit.php?post_type=statement',esc_html__('Refunds','wpqa'),esc_html__('Refunds','wpqa'),'manage_options','edit.php?post_type=statement&statement=refunds');
	add_submenu_page('edit.php?post_type=statement',esc_html__('Canceled','wpqa'),esc_html__('Canceled','wpqa'),'manage_options','edit.php?post_type=statement&statement=canceled');
}
add_filter("views_edit-statement","wpqa_statements_status");
function wpqa_statements_status($status) {
	$pay_ask               = wpqa_options('pay_ask');
	$ask_payment_style     = wpqa_options('ask_payment_style');
	$payment_type_ask      = wpqa_options('payment_type_ask');
	$pay_post              = wpqa_options('pay_post');
	$post_payment_style    = wpqa_options('post_payment_style');
	$payment_type_post     = wpqa_options('payment_type_post');
	$pay_to_sticky         = wpqa_options('pay_to_sticky');
	$payment_type_sticky   = wpqa_options('payment_type_sticky');
	$subscriptions_payment = wpqa_options('subscriptions_payment');
	$buy_points_payment    = wpqa_options('buy_points_payment');
	$pay_answer            = wpqa_options('pay_answer');
	$payment_type_answer   = wpqa_options('payment_type_answer');
	$payment_type_subscriptions = wpqa_options("payment_type_subscriptions");
	$trial_subscription    = wpqa_options("trial_subscription");
	$reward_subscription   = wpqa_options("reward_subscription");
	$get_status = (isset($_GET['item_number'])?esc_attr($_GET['item_number']):'');
	$get_points = (isset($_GET['buy'])?esc_attr($_GET['buy']):'');
	if ($pay_ask == "on" && $ask_payment_style == "once") {
		$status['ask_question'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=ask_question').'"'.($get_status == "ask_question"?' class="current"':'').'>'.esc_html__('Ask a new question','wpqa').' ('.wpqa_meta_count("payment_item_number","ask_question").')</a>';
	}
	if ($pay_ask == "on" && $ask_payment_style == "packages") {
		$status['buy_questions'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=buy_questions').'"'.($get_status == "buy_questions"?' class="current"':'').'>'.esc_html__('Buy questions','wpqa').' ('.wpqa_meta_count("payment_item_number","buy_questions").')</a>';
	}
	if ($pay_post == "on" && $post_payment_style == "once") {
		$status['add_post'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=add_post').'"'.($get_status == "add_post"?' class="current"':'').'>'.esc_html__('Add a new post','wpqa').' ('.wpqa_meta_count("payment_item_number","add_post").')</a>';
	}
	if ($pay_post == "on" && $post_payment_style == "packages") {
		$status['buy_posts'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=buy_posts').'"'.($get_status == "buy_posts"?' class="current"':'').'>'.esc_html__('Buy posts','wpqa').' ('.wpqa_meta_count("payment_item_number","buy_posts").')</a>';
	}
	if ($pay_to_sticky == "on") {
		$status['pay_sticky'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=pay_sticky').'"'.($get_status == "pay_sticky"?' class="current"':'').'>'.esc_html__('Paid for sticky','wpqa').' ('.wpqa_meta_count("payment_item_number","pay_sticky").')</a>';
	}
	if ($subscriptions_payment == "on") {
		$status['subscribe'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=subscribe').'"'.($get_status == "subscribe"?' class="current"':'').'>'.esc_html__('Paid membership','wpqa').' ('.wpqa_meta_count("payment_item_number","subscribe").')</a>';
		$status['renew'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=renew').'"'.($get_status == "renew"?' class="current"':'').'>'.esc_html__('Membership renew','wpqa').' ('.wpqa_meta_count("payment_renew","","!=").')</a>';
		if ($trial_subscription == "on") {
			$status['trial'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=trial').'"'.($get_status == "trial"?' class="current"':'').'>'.esc_html__('Membership trial','wpqa').' ('.wpqa_meta_count("payment_trial","","!=").')</a>';
		}
		if ($reward_subscription == "on") {
			$status['reward'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=reward').'"'.($get_status == "reward"?' class="current"':'').'>'.esc_html__('Membership reward','wpqa').' ('.wpqa_meta_count("payment_reward","","!=").')</a>';
		}
	}
	if ($buy_points_payment == "on") {
		$status['buy_points'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=buy_points').'"'.($get_status == "buy_points"?' class="current"':'').'>'.esc_html__('Buy points','wpqa').' ('.wpqa_meta_count("payment_item_number","buy_points").')</a>';
	}
	if ($pay_answer == "on") {
		$status['pay_answer'] = '<a href="'.admin_url('edit.php?post_type=statement&item_number=pay_answer').'"'.($get_status == "pay_answer"?' class="current"':'').'>'.esc_html__('Pay to add answer','wpqa').' ('.wpqa_meta_count("payment_item_number","pay_answer").')</a>';
	}
	if (($pay_ask == "on" && ($payment_type_ask == "points" || $payment_type_ask == "payments_points")) || ($pay_post == "on" && ($payment_type_post == "points" || $payment_type_post == "payments_points")) || ($pay_to_sticky == "on" && ($payment_type_sticky == "points" || $payment_type_sticky == "payments_points")) || ($subscriptions_payment == "on" && ($payment_type_subscriptions == "points" || $payment_type_subscriptions == "payments_points")) || ($pay_answer == "on" && ($payment_type_answer == "points" || $payment_type_answer == "payments_points"))) {
		$status['points'] = '<a href="'.admin_url('edit.php?post_type=statement&buy=points').'"'.($get_points == "points"?' class="current"':'').'>'.esc_html__('Buy with points','wpqa').' ('.wpqa_meta_count("payment_points",0,">").')</a>';
	}
	return $status;
}
add_action('current_screen','wpqa_statements_exclude',10,2);
function wpqa_statements_exclude($screen) {
	if ($screen->id != 'edit-statement')
		return;
	$get_status = (isset($_GET['item_number'])?esc_attr($_GET['item_number']):'');
	$get_payment = (isset($_GET['payment_methods'])?esc_attr($_GET['payment_methods']):'');
	$get_points = (isset($_GET['buy'])?esc_attr($_GET['buy']):'');
	$statement = (isset($_GET['statement'])?esc_attr($_GET['statement']):'');
	if ($get_status == "ask_question" || $get_status == "buy_questions" || $get_status == "add_post" || $get_status == "buy_posts" || $get_status == "pay_sticky" || $get_status == "subscribe" || $get_status == "buy_points" || $get_status == "pay_answer") {
		add_filter('parse_query','wpqa_list_statement_item');
	}
	if ($get_status == "renew" || $get_status == "trial" || $get_status == "reward") {
		add_filter('parse_query','wpqa_statement_renew_trial_reward');
	}
	if ($get_points == "points") {
		add_filter('parse_query','wpqa_list_statement_points');
	}
	if ($statement != "") {
		add_filter('parse_query','wpqa_list_statement_'.$statement);
	}
}
add_filter('manage_edit-statement_sortable_columns','wpqa_statement_sortable_columns');
function wpqa_statement_sortable_columns($defaults) {
	$defaults['date_p'] = 'date';
	$defaults['price']  = 'payment_item_price';
	return $defaults;
}
function wpqa_list_statement_item($clauses) {
	$get_status = (isset($_GET['item_number'])?esc_attr($_GET['item_number']):'');
	if ($get_status != "") {
		$clauses->query_vars['meta_key'] = "payment_item_number";
		$clauses->query_vars['meta_value'] = $get_status;
		$clauses->query_vars['post_type'] = "statement";
	}
}
function wpqa_list_statement_payments($clauses) {
	$statement = (isset($_GET['statement'])?esc_attr($_GET['statement']):'');
	if ($statement != "") {
		$clauses->query_vars['meta_query'][] = array (
			'key'     => 'statement_type',
			'compare' => 'NOT EXISTS'
		);
		$clauses->query_vars['post_type'] = "statement";
	}
}
function wpqa_list_statement_refunds($clauses) {
	$statement = (isset($_GET['statement'])?esc_attr($_GET['statement']):'');
	if ($statement == "refunds") {
		$clauses->query_vars['meta_key'] = "statement_type";
		$clauses->query_vars['meta_value'] = "refund";
		$clauses->query_vars['post_type'] = "statement";
	}
}
function wpqa_list_statement_reversed($clauses) {
	$statement = (isset($_GET['statement'])?esc_attr($_GET['statement']):'');
	if ($statement == "refunds") {
		$clauses->query_vars['meta_key'] = "statement_type";
		$clauses->query_vars['meta_value'] = "reversed";
		$clauses->query_vars['post_type'] = "statement";
	}
}
function wpqa_list_statement_canceled($clauses) {
	$statement = (isset($_GET['statement'])?esc_attr($_GET['statement']):'');
	if ($statement == "canceled") {
		$clauses->query_vars['meta_key'] = "statement_type";
		$clauses->query_vars['meta_value'] = "canceled";
		$clauses->query_vars['post_type'] = "statement";
	}
}
function wpqa_list_statement_points($clauses) {
	$get_points = (isset($_GET['buy'])?esc_attr($_GET['buy']):'');
	if ($get_points == "points") {
		$clauses->query_vars['meta_query'][] = array (
			'key'     => 'payment_points',
			'value'   => 0,
			'compare' => '>'
		);
		$clauses->query_vars['post_type'] = "statement";
	}
}
function wpqa_statement_renew_trial_reward($clauses) {
	$get_status = (isset($_GET['item_number'])?esc_attr($_GET['item_number']):'');
	if ($get_status == "renew" || $get_status == "trial" || $get_status == "reward") {
		$clauses->query_vars['meta_query'][] = array (
			'key'     => 'payment_'.$get_status,
			'value'   => '',
			'compare' => '!='
		);
		$clauses->query_vars['post_type'] = "statement";
	}
}
add_filter('bulk_actions-edit-statement','wpqa_bulk_actions_statement');
function wpqa_bulk_actions_statement($actions) {
	unset($actions['edit']);
	return $actions;
}
add_filter('bulk_post_updated_messages','wpqa_bulk_updated_messages_statement',1,2);
function wpqa_bulk_updated_messages_statement($bulk_messages,$bulk_counts) {
	if (get_current_screen()->post_type == "statement") {
		$bulk_messages['post'] = array(
			'deleted' => _n('%s statement permanently deleted.','%s statements permanently deleted.',$bulk_counts['deleted'],'wpqa'),
			'trashed' => _n('%s statement trashed.','%s statements trashed.',$bulk_counts['trashed'],'wpqa'),
		);
	}
	return $bulk_messages;
}
add_filter('post_row_actions','wpqa_row_actions_statement',1,2);
function wpqa_row_actions_statement($actions,$post) {
	if ($post->post_type == "statement") {
		unset($actions['trash']);
		unset($actions['view']);
		unset($actions['edit']);
		$actions['inline hide-if-no-js'] = "";
	}
	return $actions;
}
function wpqa_payment_subscribe_filter() {
	global $post_type;
	if ($post_type == 'statement') {
		$subscriptions_payment = wpqa_options("subscriptions_payment");
		if ($subscriptions_payment == "on") {
			$payment_subscribe = (isset($_GET['payment_subscribe'])?esc_attr($_GET['payment_subscribe']):'');
			$subscriptions_options = wpqa_options("subscriptions_options");
			$array = array(
				"monthly"  => array("key" => "monthly","name" => esc_html__("Monthly membership","wpqa")),
				"3months"  => array("key" => "3months","name" => esc_html__("Three months membership","wpqa")),
				"6months"  => array("key" => "6months","name" => esc_html__("Six Months membership","wpqa")),
				"yearly"   => array("key" => "yearly","name" => esc_html__("Yearly membership","wpqa")),
				"2years"   => array("key" => "2years","name" => esc_html__("Two Years membership","wpqa")),
				"lifetime" => array("key" => "lifetime","name" => esc_html__("Lifetime membership","wpqa")),
			);
			echo '<select name="payment_subscribe">
				<option value="-1">'.esc_html__("Subscription plans","wpqa").'</option>';
				foreach ($array as $key => $value) {
					if (isset($subscriptions_options[$key]) && $subscriptions_options[$key] == $key) {
						echo '<option '.selected($payment_subscribe,$key,false).' value="'.$key.'">'.$value["name"].' ('.wpqa_meta_count("payment_custom",$key).')</option>';
					}
				}
			echo '</select>';
		}
		$get_payment = (isset($_GET['payment_methods'])?esc_attr($_GET['payment_methods']):'');
		$payment_methods = wpqa_options("payment_methodes");
		echo '<select name="payment_methods">
			<option value="-1">'.esc_html__("Payment methods","wpqa").'</option>';
			$key = "stripe";
			if (isset($payment_methods[$key]["value"]) && $payment_methods[$key]["value"] == $key) {
				echo '<option '.selected($get_payment,$key,false).' value="'.$key.'">Stripe ('.wpqa_meta_count("payment_method","stripe").')</option>';
			}
			$key = "paypal";
			if (isset($payment_methods[$key]["value"]) && $payment_methods[$key]["value"] == $key) {
				echo '<option '.selected($get_payment,$key,false).' value="'.$key.'">PayPal ('.wpqa_meta_count("payment_method","paypal").')</option>';
			}
		echo '</select>';
		$from = (isset($_GET['date-from']) && $_GET['date-from'])?$_GET['date-from'] :'';
		$to = (isset($_GET['date-to']) && $_GET['date-to'])?$_GET['date-to']:'';
		$data_js = " data-js='".json_encode(array("changeMonth" => true,"changeYear" => true,"yearRange" => "2018:+00","dateFormat" => "yy-mm-dd"))."'";

		echo '<span class="site-form-date"><input class="site-date" type="text" name="date-from" placeholder="'.esc_html__("Date From","wpqa").'" value="'.esc_attr($from).'" '.$data_js.'></span>
		<span class="site-form-date"><input class="site-date" type="text" name="date-to" placeholder="'.esc_html__("Date To","wpqa").'" value="'.esc_attr($to).'" '.$data_js.'></span>';
	}
}
add_action('restrict_manage_posts','wpqa_payment_subscribe_filter');
function wpqa_payment_subscribe_posts_query($query) {
	global $post_type,$pagenow;
	if ($pagenow == 'edit.php' && $post_type == 'statement') {
		$orderby = $query->get('orderby');
		$payment_subscribe = (isset($_GET['payment_subscribe'])?esc_attr($_GET['payment_subscribe']):'');
		$get_payment = (isset($_GET['payment_methods'])?esc_attr($_GET['payment_methods']):'');
		$date_from = (isset($_GET['date-from'])?esc_attr($_GET['date-from']):'');
		$date_to = (isset($_GET['date-to'])?esc_attr($_GET['date-to']):'');
		if (($payment_subscribe != "" && $payment_subscribe != "-1") || ($get_payment != "" && $get_payment != "-1")) {
			$query->query_vars['meta_query'] = array();
		}
		if ($payment_subscribe != "" && $payment_subscribe != "-1") {
			$query->query_vars['meta_query'][] = array(
				'key'   => 'payment_custom',
				'value' => $payment_subscribe,
			);
		}
		if ($get_payment != "" && $get_payment != "-1") {
			$query->query_vars['meta_query'][] = array(
				'key'   => 'payment_method',
				'value' => $get_payment,
			);
		}
		if (!empty($_GET['date-from']) && !empty($_GET['date-to'])) {
			$query->query_vars['date_query'][] = array(
				'after'     => sanitize_text_field($_GET['date-from']),
				'before'    => sanitize_text_field($_GET['date-to']),
				'inclusive' => true,
				'column'    => 'post_date'
			);
		}
		if (!empty($_GET['date-from']) && empty($_GET['date-to'])) {
			$today = sanitize_text_field($_GET['date-from']);
			$today = explode("-",$today);
			$query->query_vars['date_query'] = array(
	            'year'  => $today[0],
	            'month' => $today[1],
	            'day'   => $today[2],
	        );
		}
		if (empty($_GET['date-from']) && !empty($_GET['date-to'])) {
			$today = sanitize_text_field($_GET['date-to']);
			$today = explode("-",$today);
			$query->query_vars['date_query'] = array(
	            'year'  => $today[0],
	            'month' => $today[1],
	            'day'   => $today[2],
	        );
		}
		if ($orderby == 'date_p') {
			$query->query_vars('orderby','date');
		}else if ($orderby == 'payment_item_price') {
			$query->query_vars('meta_key','payment_item_price');
			$query->query_vars('orderby','meta_value_num');
			$query->query_vars['meta_query'][] = array(
				'key'     => 'payment_item_price',
				'value'   => 0,
				'type'    => 'NUMERIC',
				'compare' => '>',
			);
		}
	}
}
add_action('pre_get_posts','wpqa_payment_subscribe_posts_query');
function wpqa_months_dropdown_statement($return,$post_type) {
	if ($post_type == "statement") {
		$return = true;
	}
	return $return;
}
add_filter("disable_months_dropdown","wpqa_months_dropdown_statement",1,2);
/* Remove filter */
function wpqa_manage_statement_tablenav($which) {
	if ($which == "top") {
		global $post_type,$pagenow;
		if ($pagenow == 'edit.php' && $post_type == 'statement') {
			$payment_subscribe = (isset($_GET['payment_subscribe'])?esc_attr($_GET['payment_subscribe']):'');
			$get_payment = (isset($_GET['payment_methods'])?esc_attr($_GET['payment_methods']):'');
			$date_from = (isset($_GET['date-from'])?esc_attr($_GET['date-from']):'');
			$date_to = (isset($_GET['date-to'])?esc_attr($_GET['date-to']):'');
			if (($payment_subscribe != "" && $payment_subscribe != "-1") || ($get_payment != "" && $get_payment != "-1") || $date_from != "" || $date_to != "") {
				echo '<a class="button" href="'.admin_url('edit.php?post_type=statement').'">'.esc_html__("Remove filters","wpqa").'</a>';
			}
		}
	}
}
add_filter("manage_posts_extra_tablenav","wpqa_manage_statement_tablenav");?>