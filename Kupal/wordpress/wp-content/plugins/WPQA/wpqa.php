<?php
/**
 * Plugin Name: WPQA - The WordPress Questions And Answers Plugin
 * Plugin URI: https://2code.info/wpqa/
 * Description: Question and answer plugin with point and badges system.
 * Version: 4.4.4
 * Author: 2code
 * Author URI: https://2code.info/
 * License: GPL2
 *
 * Text Domain: wpqa
 * Domain Path: /languages/
 */


// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

/* Load the core */
require_once plugin_dir_path(__FILE__).'includes/class-wpqa.php';
require_once plugin_dir_path(__FILE__).'includes/themes.php';

/* Defines */
define("wpqa_plugin_version","4.4.4");
define("wpqa_widgets","WPQA");
define("wpqa_theme_name",wpqa_theme_name());
define("wpqa_meta",wpqa_theme_name);
define("wpqa_terms",wpqa_theme_name);
define("wpqa_author",wpqa_theme_name);
define("wpqa_options",wpqa_theme_name."_options");
if (!defined("prefix_meta")) {
	define("prefix_meta",wpqa_meta."_");
}
if (!defined("prefix_terms")) {
	define("prefix_terms",wpqa_terms."_");
}
if (!defined("prefix_author")) {
	define("prefix_author",wpqa_author."_");
}

/* Class */
register_activation_hook(__FILE__,array('WPQA','activate'));
register_deactivation_hook(__FILE__,array('WPQA','deactivate'));

/* Load plugin textdomain */
function wpqa_load_textdomain() {
	load_plugin_textdomain('wpqa',false,dirname(plugin_basename(__FILE__)).'/languages/');
}
add_action('plugins_loaded','wpqa_load_textdomain');

/* Post types */
require_once plugin_dir_path(__FILE__).'CPT/questions.php';
require_once plugin_dir_path(__FILE__).'CPT/reports.php';
require_once plugin_dir_path(__FILE__).'CPT/groups.php';
require_once plugin_dir_path(__FILE__).'CPT/posts.php';
require_once plugin_dir_path(__FILE__).'CPT/statements.php';
require_once plugin_dir_path(__FILE__).'CPT/messages.php';
require_once plugin_dir_path(__FILE__).'CPT/points.php';
require_once plugin_dir_path(__FILE__).'CPT/notifications.php';
require_once plugin_dir_path(__FILE__).'CPT/activities.php';
require_once plugin_dir_path(__FILE__).'CPT/requests.php';

/* Functions */
require_once plugin_dir_path(__FILE__).'functions/actions.php';
require_once plugin_dir_path(__FILE__).'functions/ajax-action.php';
require_once plugin_dir_path(__FILE__).'functions/author.php';
require_once plugin_dir_path(__FILE__).'functions/avatar.php';
require_once plugin_dir_path(__FILE__).'functions/categories.php';
require_once plugin_dir_path(__FILE__).'functions/check-account.php';
require_once plugin_dir_path(__FILE__).'functions/comments.php';
require_once plugin_dir_path(__FILE__).'functions/cover.php';
require_once plugin_dir_path(__FILE__).'functions/filters.php';
require_once plugin_dir_path(__FILE__).'functions/functions.php';
require_once plugin_dir_path(__FILE__).'functions/group.php';
require_once plugin_dir_path(__FILE__).'functions/mails.php';
require_once plugin_dir_path(__FILE__).'functions/menu.php';
require_once plugin_dir_path(__FILE__).'functions/points.php';
require_once plugin_dir_path(__FILE__).'functions/popup.php';
require_once plugin_dir_path(__FILE__).'functions/questions.php';
require_once plugin_dir_path(__FILE__).'functions/referrals.php';
require_once plugin_dir_path(__FILE__).'functions/resizer.php';
require_once plugin_dir_path(__FILE__).'functions/review.php';
require_once plugin_dir_path(__FILE__).'functions/rewrite.php';
require_once plugin_dir_path(__FILE__).'functions/subscriptions.php';

/* Payments */
require_once plugin_dir_path(__FILE__).'payments/form.php';
require_once plugin_dir_path(__FILE__).'payments/packages.php';
require_once plugin_dir_path(__FILE__).'payments/payments.php';
require_once plugin_dir_path(__FILE__).'payments/paypal.php';
require_once plugin_dir_path(__FILE__).'payments/stripe.php';
require_once plugin_dir_path(__FILE__).'payments/answer.php';

/* Shortcodes */
require_once plugin_dir_path(__FILE__).'shortcodes/shortcodes.php';

/* Widgets */
require_once plugin_dir_path(__FILE__).'widgets/widgets.php';

/* The code that runs the enqueue style */
add_action('wp_enqueue_scripts','wpqa_enqueue_style');
function wpqa_enqueue_style() {
	wp_enqueue_style('wpqa-custom-css',plugins_url('assets/css/custom.css',__FILE__),array(),wpqa_plugin_version);
	$captcha_answer    = wpqa_options("captcha_answer");
	$poll_image        = wpqa_options("poll_image");
	$poll_image_title  = wpqa_options("poll_image_title");
	$comment_limit     = (int)wpqa_options((is_singular("question")?"answer_limit":"comment_limit"));
	$comment_min_limit = (int)wpqa_options((is_singular("question")?"answer_min_limit":"comment_min_limit"));
	$ajax_file         = wpqa_options("ajax_file");
	$ajax_file         = ($ajax_file == "theme"?plugins_url('includes/ajax.php',__FILE__):admin_url("admin-ajax.php"));
	wp_enqueue_script("wpqa-scripts-js",plugins_url('assets/js/scripts.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
	wp_enqueue_script("wpqa-custom-js",plugins_url('assets/js/custom.js',__FILE__),array("jquery","jquery-ui-core","jquery-ui-datepicker","jquery-ui-sortable"),wpqa_plugin_version,true);
	$wpqa_js = array(
		'admin_url'              => $ajax_file,
		'poll_image'             => $poll_image,
		'poll_image_title'       => $poll_image_title,
		'comment_limit'          => $comment_limit,
		'comment_min_limit'      => $comment_min_limit,
		'home_url'               => esc_url(home_url('/')),
		'wpqa_error_text'        => esc_html__('Please fill the required field.','wpqa'),
		'wpqa_error_min_limit'   => esc_html__('Sorry, The minimum characters is','wpqa'),
		'wpqa_error_limit'       => esc_html__('Sorry, The maximum characters is','wpqa'),
		'sure_delete_comment'    => esc_html__('Are you sure you want to delete the comment?','wpqa'),
		'sure_delete_answer'     => esc_html__('Are you sure you want to delete the answer?','wpqa'),
		'wpqa_remove_image'      => esc_html__('Are you sure you want to delete the image?','wpqa'),
		'wpqa_remove_attachment' => esc_html__('Are you sure you want to delete the attachment?','wpqa'),
		'no_vote_question'       => esc_html__('Sorry, you cannot vote your question.','wpqa'),
		'no_vote_more'           => esc_html__('Sorry, you cannot vote on the same question more than once.','wpqa'),
		'no_vote_user'           => esc_html__('Voting is available to members only.','wpqa'),
		'no_vote_answer'         => esc_html__('Sorry, you cannot vote your answer.','wpqa'),
		'no_vote_more_answer'    => esc_html__('Sorry, you cannot vote on the same answer more than once.','wpqa'),
		'no_vote_comment'        => esc_html__('Sorry, you cannot vote your comment.','wpqa'),
		'no_vote_more_comment'   => esc_html__('Sorry, you cannot vote on the same comment more than once.','wpqa'),
		'follow_question_attr'   => esc_html__('Follow the question','wpqa'),
		'unfollow_question_attr' => esc_html__('Unfollow the question','wpqa'),
		'follow'                 => esc_html__('Follow','wpqa'),
		'unfollow'               => esc_html__('Unfollow','wpqa'),
		'select_file'            => esc_html__('Select file','wpqa'),
		'browse'                 => esc_html__('Browse','wpqa'),
		'reported'               => esc_html__('Thank you, your report will be reviewed shortly.','wpqa'),
		'wpqa_error_comment'     => esc_html__('Please type a comment.','wpqa'),
		'click_continue'         => esc_html__('Click here to continue.','wpqa'),
		'click_not_finish'       => esc_html__('Complete your following above to continue.','wpqa'),
		'ban_user'               => esc_html__('Ban user','wpqa'),
		'unban_user'             => esc_html__('Unban user','wpqa'),
		'no_poll_more'           => esc_html__('Sorry, you cannot poll on the same question more than once.','wpqa'),
		'must_login'             => esc_html__('Please login to vote and see the results.','wpqa'),
	);
	wp_localize_script('wpqa-custom-js','wpqa_custom',$wpqa_js);
	if (wpqa_is_user_edit_profile() || wpqa_is_user_financial_profile() || wpqa_is_user_withdrawals_profile()) {
		wp_enqueue_script("wpqa-edit-js",plugins_url('assets/js/edit.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
		$wpqa_js = array(
			'admin_url'         => $ajax_file,
			'not_min_points'    => esc_html__("You don't get the minimum points to can request your payment.","wpqa"),
			'not_enough_points' => esc_html__("You don't have these points.","wpqa"),
			'not_enough_money'  => esc_html__("You don't get the minimum money to can request your payment.","wpqa"),
		);
		wp_localize_script('wpqa-edit-js','wpqa_edit',$wpqa_js);
	}
	if (wpqa_is_user_password_profile()) {
		wp_enqueue_script("wpqa-passwrod-js",plugins_url('assets/js/password.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
	}
	if (wpqa_is_user_mails_profile()) {
		wp_enqueue_script("wpqa-mails-js",plugins_url('assets/js/mails.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
	}
	if (wpqa_is_user_delete_profile()) {
		wp_enqueue_script("wpqa-delete-js",plugins_url('assets/js/delete.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
		$wpqa_js = array(
			'delete_account' => esc_html__('Are you sure you want to delete your account?','wpqa'),
		);
		wp_localize_script('wpqa-delete-js','wpqa_delete',$wpqa_js);
	}
	if (wpqa_is_user_messages()) {
		wp_enqueue_script("wpqa-message-js",plugins_url('assets/js/message.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
		$wpqa_js = array(
			'admin_url'            => $ajax_file,
			'sure_delete_message'  => esc_html__('Are you sure you want to delete the message?','wpqa'),
			'block_message_text'   => esc_html__('Block Message','wpqa'),
			'unblock_message_text' => esc_html__('Unblock Message','wpqa'),
		);
		wp_localize_script('wpqa-message-js','wpqa_message',$wpqa_js);
	}
	if (wpqa_is_checkout()) {
		$checkout_value = wpqa_checkout();
		if (is_user_logged_in() && wpqa_is_checkout()) {
			$payment_methods = wpqa_options("payment_methodes");
			$publishable_key = wpqa_options("publishable_key");
			if (isset($payment_methods["stripe"]["value"]) && $payment_methods["stripe"]["value"] == "stripe") {
				wp_enqueue_script("wpqa-stripe","https://js.stripe.com/v3/",array("jquery"),wpqa_plugin_version,true);
			}
			wp_enqueue_script("wpqa-payment-js",plugins_url('assets/js/payment.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
			$wpqa_js = array(
				'admin_url'       => $ajax_file,
				'publishable_key' => $publishable_key,
			);
			wp_localize_script('wpqa-payment-js','wpqa_payment',$wpqa_js);
		}
	}
	if (wpqa_is_user_referrals()) {
		wp_enqueue_script("wpqa-referral-js",plugins_url('assets/js/referral.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
		$wpqa_js = array(
			'admin_url'       => $ajax_file,
			'email_exist'     => esc_html__('This email is already exists.','wpqa'),
			'sent_invitation' => esc_html__('The invitation was sent.','wpqa'),
		);
		wp_localize_script('wpqa-referral-js','wpqa_referral',$wpqa_js);
	}
	if (wpqa_is_pending_questions() || wpqa_is_pending_posts()) {
		wp_enqueue_script("wpqa-review-js",plugins_url('assets/js/review.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
		$wpqa_js = array(
			'admin_url'        => $ajax_file,
			'sure_ban'         => esc_html__('Are you sure you want to ban this user?','wpqa'),
			'sure_delete'      => esc_html__('Are you sure you want to delete the question?','wpqa'),
			'sure_delete_post' => esc_html__('Are you sure you want to delete the post?','wpqa'),
			'ban_user'         => esc_html__('Ban user','wpqa'),
			'unban_user'       => esc_html__('Unban user','wpqa'),
			'no_questions'     => esc_html__('There are no questions yet.','wpqa'),
			'no_posts'         => esc_html__('There are no posts yet.','wpqa'),
		);
		wp_localize_script('wpqa-review-js','wpqa_review',$wpqa_js);
	}
	if (is_single()) {
		$require_name_email    = get_option("require_name_email");
		$comment_editor        = wpqa_options((is_singular("question")?"answer_editor":"comment_editor"));
		$attachment_answer     = wpqa_options("attachment_answer");
		$featured_image_answer = wpqa_options("featured_image_answer");
		$terms_active_comment  = wpqa_options("terms_active_comment");
		$activate_editor_reply = wpqa_options("activate_editor_reply");
		$popup_share_seconds   = wpqa_options("popup_share_seconds");
		$user_id               = get_current_user_id();
		$is_logged             = ($user_id > 0?"logged":"unlogged");
		$display_name          = ($user_id > 0?get_the_author_meta('display_name',$user_id):"");
		$profile_url           = ($user_id > 0?wpqa_profile_url($user_id):"");
		$logout_url            = ($user_id > 0?wpqa_get_logout():"");
		wp_enqueue_script("wpqa-single-js",plugins_url('assets/js/single.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
		$wpqa_js = array(
			'wpqa_dir'               => plugin_dir_url(__FILE__),
			'wpqa_best_answer_nonce' => wp_create_nonce('wpqa_best_answer_nonce'),
			'require_name_email'     => ($require_name_email == 1?'require_name_email':''),
			'admin_url'              => $ajax_file,
			'comment_limit'          => $comment_limit,
			'comment_min_limit'      => $comment_min_limit,
			'captcha_answer'         => $captcha_answer,
			'attachment_answer'      => $attachment_answer,
			'featured_image_answer'  => $featured_image_answer,
			'terms_active_comment'   => $terms_active_comment,
			'comment_editor'         => $comment_editor,
			'activate_editor_reply'  => $activate_editor_reply,
			'is_logged'              => $is_logged,
			'display_name'           => $display_name,
			'profile_url'            => $profile_url,
			'logout_url'             => $logout_url,
			'popup_share_seconds'    => $popup_share_seconds,
			'comment_action'         => esc_url(site_url('/wp-comments-post.php')),
			'wpqa_error_name'        => esc_html__('Please fill the required fields (name).','wpqa'),
			'wpqa_error_email'       => esc_html__('Please fill the required fields (email).','wpqa'),
			'wpqa_valid_email'       => esc_html__('Please enter a valid email address.','wpqa'),
			'wpqa_error_comment'     => esc_html__('Please type a comment.','wpqa'),
			'wpqa_error_min_limit'   => esc_html__('Sorry, The minimum characters is','wpqa'),
			'wpqa_error_limit'       => esc_html__('Sorry, The maximum characters is','wpqa'),
			'wpqa_error_terms'       => esc_html__('There are required fields (Agree of the terms).','wpqa'),
			'cancel_reply'           => esc_html__('Cancel reply.','wpqa'),
			'logged_as'              => esc_html__('Logged in as','wpqa'),
			'logout_title'           => esc_html__('Log out of this account','wpqa'),
			'logout'                 => esc_html__('Log out','wpqa'),
			'reply'                  => esc_html__('Reply','wpqa'),
			'submit'                 => esc_html__('Submit','wpqa'),
			'choose_best_answer'     => esc_html__('Select as best answer','wpqa'),
			'cancel_best_answer'     => esc_html__('Cancel the best answer','wpqa'),
			'best_answer'            => esc_html__('Best answer','wpqa'),
			'best_answer_selected'   => esc_html__('There is another one select this a best answer','wpqa'),
			'wpqa_error_captcha'     => esc_html__('The captcha is incorrect, Please try again.','wpqa'),
			'sure_delete'            => esc_html__('Are you sure you want to delete the question?','wpqa'),
			'sure_delete_post'       => esc_html__('Are you sure you want to delete the post?','wpqa'),
			'add_favorite'           => esc_html__('Add this question to favorites','wpqa'),
			'remove_favorite'        => esc_html__('Remove this question of my favorites','wpqa'),
			'get_points'             => esc_html__('You have bumped your question.','wpqa'),
		);
		wp_localize_script('wpqa-single-js','wpqa_single',$wpqa_js);
	}
	if (wpqa_is_subscriptions()) {
		wp_enqueue_script("wpqa-subscriptions-js",plugins_url('assets/js/subscriptions.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
		$wpqa_js = array(
			'admin_url'           => $ajax_file,
			'cancel_subscription' => esc_html__('Are you sure you want to cancel your subscription?','wpqa'),
			'trial_subscription'  => esc_html__('Are you sure you want to get the trial subscription?','wpqa'),
		);
		wp_localize_script('wpqa-subscriptions-js','wpqa_subscriptions',$wpqa_js);
	}
	if (!is_user_logged_in()) {
		wp_enqueue_script("wpqa-unlogged-js",plugins_url('assets/js/unlogged.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
		$wpqa_js = array(
			'wpqa_dir'           => plugin_dir_url(__FILE__),
			'admin_url'          => $ajax_file,
			'captcha_answer'     => $captcha_answer,
			'wpqa_error_text'    => esc_html__('Please fill the required field.','wpqa'),
			'wpqa_error_captcha' => esc_html__('The captcha is incorrect, Please try again.','wpqa'),
		);
		wp_localize_script('wpqa-unlogged-js','wpqa_unlogged',$wpqa_js);
	}
	$active_groups = wpqa_options("active_groups");
	$search_type = (wpqa_is_search()?wpqa_search_type():"");
	if ($active_groups == "on" && (wpqa_is_add_groups() || wpqa_is_edit_groups() || is_singular("group") || wpqa_is_view_posts_group() || wpqa_is_edit_posts_group() || wpqa_is_group_requests() || wpqa_is_group_users() || wpqa_is_group_admins() || wpqa_is_blocked_users() || wpqa_is_posts_group() || wpqa_is_user_groups() || is_post_type_archive("group") || is_page_template("template-groups.php") || $search_type == "groups")) {
		wp_enqueue_script("wpqa-groups-js",plugins_url('assets/js/groups.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
		$wpqa_js = array(
			'admin_url'         => $ajax_file,
			'comment_action'    => esc_url(site_url('/wp-comments-post.php')),
			'like_posts_attr'   => esc_html__('Like','wpqa'),
			'unlike_posts_attr' => esc_html__('Unlike','wpqa'),
			'cancel_reply'      => esc_html__('Cancel reply.','wpqa'),
			'reply'             => esc_html__('Reply','wpqa'),
			'submit'            => esc_html__('Submit','wpqa'),
			'sure_delete_group' => esc_html__('Are you sure you want to delete the group?','wpqa'),
			'sure_delete_posts' => esc_html__('Are you sure you want to delete the group post?','wpqa'),
			'sure_block_user'   => esc_html__('Are you sure you want to block the user from the group?','wpqa'),
			'sure_remove_user'  => esc_html__('Are you sure you want to remove the user from the group?','wpqa'),
			'remove_moderator'  => esc_html__('Are you sure you want to remove the moderator from the group?','wpqa'),
		);
		wp_localize_script('wpqa-groups-js','wpqa_groups',$wpqa_js);
	}
}
/* The code that runs the enqueue for admin */
add_action('admin_enqueue_scripts','wpqa_enqueue_admin');
function wpqa_enqueue_admin() {
	wp_enqueue_script("wpqa-admin-custom-js",plugins_url('assets/js/admin-custom.js',__FILE__),array("jquery"),wpqa_plugin_version,true);
	$new_payments = (int)get_option("new_payments");
	$new_requests = (int)get_option("new_requests");
	$new_reports = (int)get_option("new_reports");
	$new_question_reports = (int)get_option("new_question_reports");
	$option_js = array(
		'ajax_a'                    => plugins_url('includes/ajax.php',__FILE__),
		'comment_status'            => (isset($_GET['comment_status'])?esc_js($_GET['comment_status']):''),
		'report_type'               => (isset($_GET['types'])?esc_js($_GET['types']):''),
		'statement'                 => (isset($_GET['statement'])?esc_js($_GET['statement']):''),
		'request'                   => (isset($_GET['request'])?esc_js($_GET['request']):''),
		'user_roles'                => (isset($_GET['role'])?esc_js($_GET['role']):''),
		'new_payments'              => $new_payments,
		'new_requests'              => $new_requests,
		'new_reports'               => $new_reports,
		'new_question_reports'      => $new_question_reports,
		'confirm_refund'            => esc_html__('Are you sure you want to refund?','wpqa'),
		'refunded'                  => esc_html__('Refunded','wpqa'),
		'confirm_delete'            => esc_html__('Are you sure you want to delete?','wpqa'),
		'confirm_delete_attachment' => esc_html__('If you press will delete the attachment!','wpqa'),
		'confirm_delete_history'    => esc_html__('Are you sure you want to delete the history?','wpqa'),
		'deleting'                  => esc_html__('Deleting...','wpqa'),
	);
	wp_localize_script('wpqa-admin-custom-js','option_js',$option_js);
}?>