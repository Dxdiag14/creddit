<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Redirect pages */
add_action('parse_query','wpqa_redirect_pages',10);
if (!function_exists('wpqa_redirect_pages')) :
	function wpqa_redirect_pages() {
		if (is_author()) {
			$user_login = get_queried_object();
			if (isset($user_login) && is_object($user_login) && isset($user_login->ID)) {
				$user_login = get_userdata(esc_attr($user_login->ID));
			}
			$author_name = esc_attr(get_query_var('author_name'));
			if (isset($user_login) && !is_object($user_login)) {
				$user_login = get_user_by('login',urldecode($author_name));
			}
			if (isset($user_login) && !is_object($user_login)) {
				$user_login = get_user_by('slug',urldecode($author_name));
			}
			if (isset($user_login) && !is_object($user_login)) {
				$author_name = str_ireplace("-"," ",$author_name);
				$user_login = get_user_by('slug',urldecode($author_name));
			}
			$get_user_id = (isset($user_login->ID)?esc_attr($user_login->ID):"");
			if ($get_user_id != "" && !is_admin()) {
				wp_redirect(wpqa_profile_url($get_user_id));
				exit;
			}
		}
		if (wpqa_is_user_profile()) {
			if (get_option('permalink_structure')) {
				$wpqa_user    = esc_attr(get_query_var(apply_filters('wpqa_user','wpqa_user')));
				$wpqa_user_id = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
				$profile_type = wpqa_options("profile_type");
				if ($profile_type == "login") {
					$user_name = trim(urldecode(esc_attr(wpqa_get_user_login($wpqa_user_id))));
				}else {
					$user_name = trim(urldecode(esc_attr(wpqa_get_user_nicename($wpqa_user_id))));
				}
				$user_name_2 = str_ireplace("-"," ",$user_name);
				if (($user_name != $wpqa_user && $user_name_2 != $wpqa_user) || (isset($_GET['wpqa_user_id']) && $_GET['wpqa_user_id'] > 0 && $_GET['wpqa_user_id'] == $wpqa_user_id)) {
					$wpqa_user_title = wpqa_user_title();
					if ($wpqa_user_title) {
						$type_slug = explode("-",$wpqa_user_title);
						$type_slug = $type_slug[0].(isset($type_slug[1])?'_'.$type_slug[1]:'');
						wp_redirect(wpqa_get_profile_permalink($wpqa_user_id,$type_slug));
					}else {
						wp_redirect(wpqa_profile_url($wpqa_user_id));
					}
					exit;
				}
			}
		}
		if (wpqa_is_search()) {
			$request_uri = wpqa_server('REQUEST_URI');
			if (get_option('permalink_structure') && (!empty($_GET['search']) && strpos($request_uri,'get_search_results') === false) || !empty($_GET['s']) || !empty($_GET['search_type'])) {
				wp_redirect(wpqa_search_link());
				exit;
			}
		}
	}
endif;
/* Rewrite tags */
add_action('wpqa_init','wpqa_rewrite_tags',2);
if (!function_exists('wpqa_rewrite_tags')) :
	function wpqa_rewrite_tags() {
		$main_pages = array(
			"wpqa_search_id"        => "search",
			"wpqa_type"             => "search_type",
			"wpqa_checkout_id"      => "checkout",
			"wpqa_checkout_item"    => "checkout_item",
			"wpqa_checkout_related" => "checkout_related",
			"wpqa_subscriptions"    => "wpqa_subscriptions",
			"wpqa_buy_points"       => "wpqa_buy_points",
			"wpqa_login"            => "wpqa_login",
			"wpqa_signup"           => "wpqa_signup",
			"wpqa_lost_password"    => "wpqa_lost_password",
			"wpqa_add_categories"   => "add_category",
			"wpqa_add_questions"    => "add_question",
			"wpqa_edit_questions"   => "edit_question",
			"wpqa_add_groups"       => "add_group",
			"wpqa_edit_groups"      => "edit_group",
			"wpqa_group_requests"   => "group_request",
			"wpqa_group_users"      => "group_user",
			"wpqa_group_admins"     => "group_admin",
			"wpqa_blocked_users"    => "blocked_user",
			"wpqa_posts_group"      => "post_group",
			"wpqa_view_posts_group" => "view_post_group",
			"wpqa_edit_posts_group" => "edit_post_group",
			"wpqa_add_posts"        => "wpqa_add_post",
			"wpqa_edit_posts"       => "edit_post",
			"wpqa_edit_comments"    => "edit_comment",
			"wpqa_user_id"          => "wpqa_user_id"
		);
		foreach ($main_pages as $key => $value) {
			add_rewrite_tag('%'.apply_filters($key,$value).'%','([^/]+)');
		}

		$user_pages = array(
			"wpqa_edit_id"             => "edit",
			"wpqa_password_id"         => "password",
			"wpqa_privacy_id"          => "privacy",
			"wpqa_financial_id"        => "financial",
			"wpqa_transactions_id"     => "transactions",
			"wpqa_mails_id"            => "mails",
			"wpqa_delete_id"           => "delete",
			"wpqa_followers"           => "followers",
			"wpqa_following"           => "following",
			"wpqa_pending_questions"   => "pending_questions",
			"wpqa_pending_posts"       => "pending_posts",
			"wpqa_notifications"       => "notifications",
			"wpqa_activities"          => "activities",
			"wpqa_referrals"           => "referrals",
			"wpqa_messages"            => "messages",
			"wpqa_questions"           => "questions",
			"wpqa_answers"             => "answers",
			"wpqa_best_answers"        => "best_answers",
			"wpqa_groups"              => "groups",
			"wpqa_points"              => "points",
			"wpqa_polls"               => "polls",
			"wpqa_asked"               => "asked",
			"wpqa_asked_questions"     => "asked_questions",
			"wpqa_paid_questions"      => "paid_questions",
			"wpqa_followed"            => "followed",
			"wpqa_favorites"           => "favorites",
			"wpqa_posts"               => "posts",
			"wpqa_comments"            => "comments",
			"wpqa_followers_questions" => "followers_questions",
			"wpqa_followers_answers"   => "followers_answers",
			"wpqa_followers_posts"     => "followers_posts",
			"wpqa_followers_comments"  => "followers_comments",
			"wpqa_withdrawals_id"      => "withdrawals"
		);
		foreach ($user_pages as $key => $value) {
			add_rewrite_tag('%'.apply_filters($key,$value).'%','([1]{1,})');
		}
	}
endif;
/* After the update */
add_action("upgrader_process_complete","wpqa_upgrader_process_complete");
function wpqa_upgrader_process_complete() {
	update_option("FlushRewriteRules",true);
}
/* Rewrite tags */
add_action('wpqa_init','wpqa_rewrite_rules',2);
if (!function_exists('wpqa_rewrite_rules')) :
	function wpqa_rewrite_rules() {
		if ((bool)get_option('FlushRewriteRules')) {
			flush_rewrite_rules(true);
			delete_option('FlushRewriteRules');
		}
		$priority                 = 'top';
		$page_slug                = 'page';
		
		$search_slug              = wpqa_options('search_slug');
		$checkout_slug            = wpqa_options('checkout_slug');
		$subscriptions_slug       = wpqa_options('subscriptions_slug');
		$buy_points_slug          = wpqa_options('buy_points_slug');
		$login_slug               = wpqa_options('login_slug');
		$signup_slug              = wpqa_options('signup_slug');
		$lost_password_slug       = wpqa_options('lost_password_slug');
		$add_category_slug        = wpqa_options('add_category_slug');
		$add_questions_slug       = wpqa_options('add_questions_slug');
		$edit_questions_slug      = wpqa_options('edit_questions_slug');
		$add_groups_slug          = wpqa_options('add_groups_slug');
		$edit_groups_slug         = wpqa_options('edit_groups_slug');
		$group_requests_slug      = wpqa_options('group_requests_slug');
		$group_users_slug         = wpqa_options('group_users_slug');
		$group_admins_slug        = wpqa_options('group_admins_slug');
		$blocked_users_slug       = wpqa_options('blocked_users_slug');
		$posts_group_slug         = wpqa_options('posts_group_slug');
		$view_posts_group_slug    = wpqa_options('view_posts_group_slug');
		$edit_posts_group_slug    = wpqa_options('edit_posts_group_slug');
		$add_posts_slug           = wpqa_options('add_posts_slug');
		$edit_posts_slug          = wpqa_options('edit_posts_slug');
		$edit_comments_slug       = wpqa_options('edit_comments_slug');
		$user_slug                = wpqa_options('profile_slug');
		
		$edit_slug                = wpqa_options('edit_slug');
		$password_slug            = wpqa_options('password_slug');
		$privacy_slug             = wpqa_options('privacy_slug');
		$financial_slug           = wpqa_options('financial_slug');
		$transactions_slug        = wpqa_options('transactions_slug');
		$mails_slug               = wpqa_options('mails_slug');
		$delete_slug              = wpqa_options('delete_slug');
		$user_followers_slug      = wpqa_options('followers_slug');
		$user_following_slug      = wpqa_options('following_slug');
		$pending_questions_slug   = wpqa_options('pending_questions_slug');
		$pending_posts_slug       = wpqa_options('pending_posts_slug');
		$user_notifications_slug  = wpqa_options('notifications_slug');
		$user_activities_slug     = wpqa_options('activities_slug');
		$user_referrals_slug      = wpqa_options('referrals_slug');
		$user_messages_slug       = wpqa_options('messages_slug');
		$user_questions_slug      = wpqa_options('questions_slug');
		$user_answers_slug        = wpqa_options('answers_slug');
		$best_answers_slug        = wpqa_options('best_answers_slug');
		$user_groups_slug         = wpqa_options('groups_slug');
		$user_points_slug         = wpqa_options('points_slug');
		$user_polls_slug          = wpqa_options('polls_slug');
		$user_asked_slug          = wpqa_options('asked_slug');
		$asked_questions_slug     = wpqa_options('asked_questions_slug');
		$paid_questions_slug      = wpqa_options('paid_questions_slug');
		$user_followed_slug       = wpqa_options('followed_slug');
		$user_favorites_slug      = wpqa_options('favorites_slug');
		$user_posts_slug          = wpqa_options('posts_slug');
		$user_comments_slug       = wpqa_options('comments_slug');
		$followers_questions_slug = wpqa_options('followers_questions_slug');
		$followers_answers_slug   = wpqa_options('followers_answers_slug');
		$followers_posts_slug     = wpqa_options('followers_posts_slug');
		$followers_comments_slug  = wpqa_options('followers_comments_slug');
		$withdrawals_slug         = wpqa_options('withdrawals_slug');
		
		$search_id                = apply_filters('wpqa_search_id','search');
		$search_type              = apply_filters('wpqa_type','search_type');
		$checkout_id              = apply_filters('wpqa_checkout_id','checkout');
		$checkout_item            = apply_filters('wpqa_checkout_item','checkout_item');
		$checkout_related         = apply_filters('wpqa_checkout_related','checkout_related');
		$subscriptions_id         = apply_filters('wpqa_subscriptions','wpqa_subscriptions');
		$buy_points_id            = apply_filters('wpqa_buy_points','wpqa_buy_points');
		$login_id                 = apply_filters('wpqa_login','wpqa_login');
		$signup_id                = apply_filters('wpqa_signup','wpqa_signup');
		$lost_password_id         = apply_filters('wpqa_lost_password','wpqa_lost_password');
		$add_category_id          = apply_filters('wpqa_add_categories','add_category');
		$add_questions_id         = apply_filters('wpqa_add_questions','add_question');
		$edit_questions_id        = apply_filters('wpqa_edit_questions','edit_question');
		$add_groups_id            = apply_filters('wpqa_add_groups','add_group');
		$edit_groups_id           = apply_filters('wpqa_edit_groups','edit_group');
		$group_requests_id        = apply_filters('wpqa_group_requests','group_request');
		$group_users_id           = apply_filters('wpqa_group_users','group_user');
		$group_admins_id          = apply_filters('wpqa_group_admins','group_admin');
		$blocked_users_id         = apply_filters('wpqa_blocked_users','blocked_user');
		$posts_group_id           = apply_filters('wpqa_posts_group','post_group');
		$view_posts_group_id      = apply_filters('wpqa_view_posts_group','view_post_group');
		$edit_posts_group_id      = apply_filters('wpqa_edit_posts_group','edit_post_group');
		$add_posts_id             = apply_filters('wpqa_add_posts','wpqa_add_post');
		$edit_posts_id            = apply_filters('wpqa_edit_posts','edit_post');
		$edit_comments_id         = apply_filters('wpqa_edit_comments','edit_comment');
		$page_id                  = apply_filters('wpqa_page_id','page');
		$user_id                  = apply_filters('wpqa_user_id','wpqa_user_id');
		$edit_id                  = apply_filters('wpqa_edit_id','edit');
		$password_id              = apply_filters('wpqa_password_id','password');
		$privacy_id               = apply_filters('wpqa_privacy_id','privacy');
		$financial_id             = apply_filters('wpqa_financial_id','financial');
		$transactions_id          = apply_filters('wpqa_transactions_id','transactions');
		$mails_id                 = apply_filters('wpqa_mails_id','mails');
		$delete_id                = apply_filters('wpqa_delete_id','delete');
		$user_followers_id        = apply_filters('wpqa_followers','followers');
		$user_following_id        = apply_filters('wpqa_following','following');
		$pending_questions_id     = apply_filters('wpqa_pending_questions','pending_questions');
		$pending_posts_id         = apply_filters('wpqa_pending_posts','pending_posts');
		$user_notifications_id    = apply_filters('wpqa_notifications','notifications');
		$user_activities_id       = apply_filters('wpqa_activities','activities');
		$user_referrals_id        = apply_filters('wpqa_referrals','referrals');
		$user_messages_id         = apply_filters('wpqa_messages','messages');
		$user_questions_id        = apply_filters('wpqa_questions','questions');
		$user_answers_id          = apply_filters('wpqa_answers','answers');
		$best_answers_id          = apply_filters('wpqa_best_answers','best_answers');
		$user_groups_id           = apply_filters('wpqa_groups','groups');
		$user_points_id           = apply_filters('wpqa_points','points');
		$user_polls_id            = apply_filters('wpqa_polls','polls');
		$user_wpqa_asked          = apply_filters('wpqa_asked','asked');
		$asked_questions_id       = apply_filters('wpqa_asked_questions','asked_questions');
		$paid_questions_id        = apply_filters('wpqa_paid_questions','paid_questions');
		$user_followed_id         = apply_filters('wpqa_followed','followed');
		$user_favorites_id        = apply_filters('wpqa_favorites','favorites');
		$user_posts_id            = apply_filters('wpqa_posts','posts');
		$user_comments_id         = apply_filters('wpqa_comments','comments');
		$followers_questions_id   = apply_filters('wpqa_followers_questions','followers_questions');
		$followers_answers_id     = apply_filters('wpqa_followers_answers','followers_answers');
		$followers_posts_id       = apply_filters('wpqa_followers_posts','followers_posts');
		$followers_comments_id    = apply_filters('wpqa_followers_comments','followers_comments');
		$withdrawals_id           = apply_filters('wpqa_withdrawals_id','withdrawals');
		
		$main_rule = $user_rule   = '/([^/]+)/';
		$root_rule                = $main_rule.'?$';
		$edit_rule                = $main_rule . $edit_slug  . '/?$';
		$password_rule            = $main_rule . $password_slug  . '/?$';
		$privacy_rule             = $main_rule . $privacy_slug  . '/?$';
		$financial_rule           = $main_rule . $financial_slug  . '/?$';
		$transactions_rule        = $main_rule . $transactions_slug  . '/?$';
		$mails_rule               = $main_rule . $mails_slug  . '/?$';
		$delete_rule              = $main_rule . $delete_slug  . '/?$';
		$page_rule                = $main_rule . $page_slug . '/?([0-9]{1,})/?$';
		$page_rules               = '/' . $page_slug . '/?([0-9]{1,})/?$';
		
		$search_root_rule = $checkout_root_rule = $subscriptions_root_rule = $buy_points_root_rule = $login_root_rule = $signup_root_rule = $lost_password_root_rule = $add_category_root_rule = $add_questions_root_rule = $edit_questions_root_rule = $add_groups_root_rule = $edit_groups_root_rule = $group_requests_root_rule = $group_users_root_rule = $group_admins_root_rule = $blocked_users_root_rule = $posts_group_root_rule = $view_posts_group_root_rule = $edit_posts_group_root_rule = $add_posts_root_rule = $edit_posts_root_rule = $edit_comments_root_rule  = '/?$';
		$search_type_rule = $checkout_item_rule = $main_rule.'([^/]+)/?$';
		$checkout_related_rule = $main_rule.'([^/]+)/([^/]+)/?$';
		$search_page_rule = $main_rule.'([^/]+)/([0-9]{1,})/?$';
		
		// Search All
		add_rewrite_rule( $search_slug . $search_page_rule,                     'index.php?' . $search_id . '=$matches[1]&' . $search_type .  '=$matches[2]&' . $page_id . '=$matches[3]', $priority );
		add_rewrite_rule( $search_slug . $search_type_rule,                     'index.php?' . $search_id . '=$matches[1]&' . $search_type .  '=$matches[2]',                              $priority );
		add_rewrite_rule( $search_slug . $search_root_rule,                     'index.php?' . $search_id . '=$matches[1]',                                                                $priority );

		// Checkout All
		add_rewrite_rule( $checkout_slug . $checkout_related_rule,              'index.php?' . $checkout_id . '=$matches[1]&' . $checkout_item . '=$matches[2]&' . $checkout_related . '=$matches[3]', $priority );
		add_rewrite_rule( $checkout_slug . $checkout_item_rule,                 'index.php?' . $checkout_id . '=$matches[1]&' . $checkout_item . '=$matches[2]',                                       $priority );
		add_rewrite_rule( $checkout_slug . $checkout_root_rule,                 'index.php?' . $checkout_id . '=$matches[1]',                                                                          $priority );
		
		// Subscriptions
		add_rewrite_rule( $subscriptions_slug . $subscriptions_root_rule,       'index.php?' . $subscriptions_id, $priority );

		// Buy points
		add_rewrite_rule( $buy_points_slug . $buy_points_root_rule,             'index.php?' . $buy_points_id, $priority );
		
		// Login
		add_rewrite_rule( $login_slug . $login_root_rule,                       'index.php?' . $login_id, $priority );
		
		// Signup
		add_rewrite_rule( $signup_slug . $signup_root_rule,                     'index.php?' . $signup_id, $priority );
		
		// Lost password
		add_rewrite_rule( $lost_password_slug . $lost_password_root_rule,       'index.php?' . $lost_password_id, $priority );
		
		// Add category
		add_rewrite_rule( $add_category_slug . $add_category_root_rule,         'index.php?' . $add_category_id, $priority );
		
		// Ask question
		add_rewrite_rule( $add_questions_slug . $add_questions_root_rule,       'index.php?' . $add_questions_id, $priority );
		
		// Edit question
		add_rewrite_rule( $edit_questions_slug . $edit_questions_root_rule,     'index.php?' . $edit_questions_id, $priority );
		
		// Add group
		add_rewrite_rule( $add_groups_slug . $add_groups_root_rule,             'index.php?' . $add_groups_id, $priority );
		
		// Edit group
		add_rewrite_rule( $edit_groups_slug . $edit_groups_root_rule,           'index.php?' . $edit_groups_id, $priority );
		
		// Group requests
		add_rewrite_rule( $group_requests_slug . $group_requests_root_rule,     'index.php?' . $group_requests_id, $priority );
		
		// Group users
		add_rewrite_rule( $group_users_slug . $group_users_root_rule,           'index.php?' . $group_users_id, $priority );
		
		// Group admins
		add_rewrite_rule( $group_admins_slug . $group_admins_root_rule,         'index.php?' . $group_admins_id, $priority );
		
		// Group blocked users
		add_rewrite_rule( $blocked_users_slug . $blocked_users_root_rule,       'index.php?' . $blocked_users_id, $priority );
		
		// Group posts
		add_rewrite_rule( $posts_group_slug . $posts_group_root_rule,           'index.php?' . $posts_group_id, $priority );
		
		// View group post
		add_rewrite_rule( $view_posts_group_slug . $view_posts_group_root_rule, 'index.php?' . $view_posts_group_id, $priority );
		
		// Edit group post
		add_rewrite_rule( $edit_posts_group_slug . $edit_posts_group_root_rule, 'index.php?' . $edit_posts_group_id, $priority );
		
		// Add post
		add_rewrite_rule( $add_posts_slug . $add_posts_root_rule,               'index.php?' . $add_posts_id, $priority );
		
		// Edit post
		add_rewrite_rule( $edit_posts_slug . $edit_posts_root_rule,             'index.php?' . $edit_posts_id, $priority );
		
		// Edit comment
		add_rewrite_rule( $edit_comments_slug . $edit_comments_root_rule,       'index.php?' . $edit_comments_id, $priority );
		
		// User profile rules
		$followers_rule                = $user_rule . $user_followers_slug      . '/?$';
		$followers_page_rule           = $user_rule . $user_followers_slug      . $page_rules;
		$following_rule                = $user_rule . $user_following_slug      . '/?$';
		$following_page_rule           = $user_rule . $user_following_slug      . $page_rules;
		$pending_questions_rule        = $user_rule . $pending_questions_slug   . '/?$';
		$pending_questions_page_rule   = $user_rule . $pending_questions_slug   . $page_rules;
		$pending_posts_rule            = $user_rule . $pending_posts_slug       . '/?$';
		$pending_posts_page_rule       = $user_rule . $pending_posts_slug       . $page_rules;
		$notifications_rule            = $user_rule . $user_notifications_slug  . '/?$';
		$notifications_page_rule       = $user_rule . $user_notifications_slug  . $page_rules;
		$activities_rule               = $user_rule . $user_activities_slug     . '/?$';
		$activities_page_rule          = $user_rule . $user_activities_slug     . $page_rules;
		$referrals_rule                = $user_rule . $user_referrals_slug      . '/?$';
		$referrals_page_rule           = $user_rule . $user_referrals_slug      . $page_rules;
		$messages_rule                 = $user_rule . $user_messages_slug       . '/?$';
		$messages_page_rule            = $user_rule . $user_messages_slug       . $page_rules;
		$questions_rule                = $user_rule . $user_questions_slug      . '/?$';
		$questions_page_rule           = $user_rule . $user_questions_slug      . $page_rules;
		$answers_rule                  = $user_rule . $user_answers_slug        . '/?$';
		$answers_page_rule             = $user_rule . $user_answers_slug        . $page_rules;
		$best_answers_rule             = $user_rule . $best_answers_slug        . '/?$';
		$best_answers_page_rule        = $user_rule . $best_answers_slug        . $page_rules;
		$groups_rule                   = $user_rule . $user_groups_slug        . '/?$';
		$groups_page_rule              = $user_rule . $user_groups_slug        . $page_rules;
		$points_rule                   = $user_rule . $user_points_slug         . '/?$';
		$points_page_rule              = $user_rule . $user_points_slug         . $page_rules;
		$polls_rule                    = $user_rule . $user_polls_slug          . '/?$';
		$polls_page_rule               = $user_rule . $user_polls_slug          . $page_rules;
		$asked_rule                    = $user_rule . $user_asked_slug          . '/?$';
		$asked_page_rule               = $user_rule . $user_asked_slug          . $page_rules;
		$asked_questions_rule          = $user_rule . $asked_questions_slug     . '/?$';
		$asked_questions_page_rule     = $user_rule . $asked_questions_slug     . $page_rules;
		$paid_questions_rule           = $user_rule . $paid_questions_slug      . '/?$';
		$paid_questions_page_rule      = $user_rule . $paid_questions_slug      . $page_rules;
		$followed_rule                 = $user_rule . $user_followed_slug       . '/?$';
		$followed_page_rule            = $user_rule . $user_followed_slug       . $page_rules;
		$favorites_rule                = $user_rule . $user_favorites_slug      . '/?$';
		$favorites_page_rule           = $user_rule . $user_favorites_slug      . $page_rules;
		$posts_rule                    = $user_rule . $user_posts_slug          . '/?$';
		$posts_page_rule               = $user_rule . $user_posts_slug          . $page_rules;
		$comments_rule                 = $user_rule . $user_comments_slug       . '/?$';
		$comments_page_rule            = $user_rule . $user_comments_slug       . $page_rules;
		$followers_questions_rule      = $user_rule . $followers_questions_slug . '/?$';
		$followers_questions_page_rule = $user_rule . $followers_questions_slug . $page_rules;
		$followers_answers_rule        = $user_rule . $followers_answers_slug   . '/?$';
		$followers_answers_page_rule   = $user_rule . $followers_answers_slug   . $page_rules;
		$followers_posts_rule          = $user_rule . $followers_posts_slug     . '/?$';
		$followers_posts_page_rule     = $user_rule . $followers_posts_slug     . $page_rules;
		$followers_comments_rule       = $user_rule . $followers_comments_slug  . '/?$';
		$followers_comments_page_rule  = $user_rule . $followers_comments_slug  . $page_rules;
		$withdrawals_rule              = $user_rule . $withdrawals_slug  . '/?$';
		$withdrawals_page_rule         = $user_rule . $withdrawals_slug  . $page_rules;

		// Filters
		$questions_rule                = apply_filters("wpqa_questions_rule",$questions_rule,$user_rule,$user_questions_slug);
		$questions_page_rule           = apply_filters("wpqa_questions_page_rule",$questions_page_rule,$user_rule,$user_questions_slug,$page_rules);
		
		// User Pagination|Edit|Password|Privacy|Financial|Transactions|Mails|Delete
		$user_pages = array(
			$followers_rule           => $user_followers_id,
			$following_rule           => $user_following_id,
			$pending_questions_rule   => $pending_questions_id,
			$pending_posts_rule       => $pending_posts_id,
			$notifications_rule       => $user_notifications_id,
			$activities_rule          => $user_activities_id,
			$referrals_rule           => $user_referrals_id,
			$messages_rule            => $user_messages_id,
			$questions_rule           => $user_questions_id,
			$answers_rule             => $user_answers_id,
			$best_answers_rule        => $best_answers_id,
			$groups_rule              => $user_groups_id,
			$points_rule              => $user_points_id,
			$polls_rule               => $user_polls_id,
			$asked_rule               => $user_wpqa_asked,
			$asked_questions_rule     => $asked_questions_id,
			$paid_questions_rule      => $paid_questions_id,
			$followed_rule            => $user_followed_id,
			$favorites_rule           => $user_favorites_id,
			$posts_rule               => $user_posts_id,
			$comments_rule            => $user_comments_id,
			$followers_questions_rule => $followers_questions_id,
			$followers_answers_rule   => $followers_answers_id,
			$followers_posts_rule     => $followers_posts_id,
			$followers_comments_rule  => $followers_comments_id,
			$withdrawals_rule         => $withdrawals_id,
			$edit_rule                => $edit_id,
			$password_rule            => $password_id,
			$privacy_rule             => $privacy_id,
			$financial_rule           => $financial_id,
			$transactions_rule        => $transactions_id,
			$mails_rule               => $mails_id,
			$delete_rule              => $delete_id
		);
		foreach ($user_pages as $key => $value) {
			add_rewrite_rule( $user_slug . $key, 'index.php?' . $user_id  . '=$matches[1]&' . $value . '=1', $priority );
		}
		$user_paged_pages = array(
			$followers_page_rule           => $user_followers_id,
			$following_page_rule           => $user_following_id,
			$pending_questions_page_rule   => $pending_questions_id,
			$pending_posts_page_rule       => $pending_posts_id,
			$notifications_page_rule       => $user_notifications_id,
			$activities_page_rule          => $user_activities_id,
			$referrals_page_rule           => $user_referrals_id,
			$messages_page_rule            => $user_messages_id,
			$questions_page_rule           => $user_questions_id,
			$answers_page_rule             => $user_answers_id,
			$best_answers_page_rule        => $best_answers_id,
			$groups_page_rule              => $user_groups_id,
			$points_page_rule              => $user_points_id,
			$polls_page_rule               => $user_polls_id,
			$asked_page_rule               => $user_wpqa_asked,
			$asked_questions_page_rule     => $asked_questions_id,
			$paid_questions_page_rule      => $paid_questions_id,
			$followed_page_rule            => $user_followed_id,
			$favorites_page_rule           => $user_favorites_id,
			$posts_page_rule               => $user_posts_id,
			$comments_page_rule            => $user_comments_id,
			$followers_questions_page_rule => $followers_questions_id,
			$followers_answers_page_rule   => $followers_answers_id,
			$followers_posts_page_rule     => $followers_posts_id,
			$followers_comments_page_rule  => $followers_comments_id,
			$withdrawals_page_rule         => $withdrawals_id
		);
		foreach ($user_paged_pages as $key => $value) {
			add_rewrite_rule( $user_slug . $key, 'index.php?' . $user_id . '=$matches[1]&' . $value . '=1&' . $page_id . '=$matches[2]', $priority );
		}
		add_rewrite_rule( $user_slug . $root_rule, 'index.php?' . $user_id . '=$matches[1]', $priority );
	}
endif;
/* Add permalink structures */
add_action('wpqa_init','wpqa_permastructs',2);
if (!function_exists('wpqa_permastructs')) :
	function wpqa_permastructs() {
		$user_id               = apply_filters('wpqa_user_id','wpqa_user_id');
		$user_slug             = wpqa_options('profile_slug');
		$search_id             = apply_filters('wpqa_search_id','search');
		$search_slug           = wpqa_options('search_slug');
		$checkout_id           = apply_filters('wpqa_checkout_id','checkout');
		$checkout_slug         = wpqa_options('checkout_slug');
		$subscriptions_id      = apply_filters('wpqa_subscriptions','wpqa_subscriptions');
		$subscriptions_slug    = wpqa_options('subscriptions_slug');
		$buy_points_id         = apply_filters('wpqa_buy_points','wpqa_buy_points');
		$buy_points_slug       = wpqa_options('buy_points_slug');
		$login_id              = apply_filters('wpqa_login','wpqa_login');
		$login_slug            = wpqa_options('login_slug');
		$signup_id             = apply_filters('wpqa_signup','wpqa_signup');
		$signup_slug           = wpqa_options('signup_slug');
		$lost_password_id      = apply_filters('wpqa_lost_password','wpqa_lost_password');
		$lost_password_slug    = wpqa_options('lost_password_slug');
		$add_category_id       = apply_filters('wpqa_add_categories','add_category');
		$add_category_slug     = wpqa_options('add_category_slug');
		$add_questions_id      = apply_filters('wpqa_add_questions','add_question');
		$add_questions_slug    = wpqa_options('add_questions_slug');
		$edit_questions_id     = apply_filters('wpqa_edit_questions','edit_question');
		$edit_questions_slug   = wpqa_options('edit_questions_slug');
		$add_groups_id         = apply_filters('wpqa_add_groups','add_group');
		$add_groups_slug       = wpqa_options('add_groups_slug');
		$edit_groups_id        = apply_filters('wpqa_edit_groups','edit_group');
		$edit_groups_slug      = wpqa_options('edit_groups_slug');
		$group_requests_id     = apply_filters('wpqa_group_requests','group_request');
		$group_requests_slug   = wpqa_options('group_requests_slug');
		$group_users_id        = apply_filters('wpqa_group_users','group_user');
		$group_users_slug      = wpqa_options('group_users_slug');
		$group_admins_id       = apply_filters('wpqa_group_admins','group_admin');
		$group_admins_slug     = wpqa_options('group_admins_slug');
		$blocked_users_id      = apply_filters('wpqa_blocked_users','blocked_user');
		$blocked_users_slug    = wpqa_options('blocked_users_slug');
		$posts_group_id        = apply_filters('wpqa_posts_group','post_group');
		$posts_group_slug      = wpqa_options('posts_group_slug');
		$view_posts_group_id   = apply_filters('wpqa_view_posts_group','view_post_group');
		$view_posts_group_slug = wpqa_options('view_posts_group_slug');
		$edit_posts_group_id   = apply_filters('wpqa_edit_posts_group','edit_post_group');
		$edit_posts_group_slug = wpqa_options('edit_posts_group_slug');
		$add_posts_id          = apply_filters('wpqa_add_posts','wpqa_add_post');
		$add_posts_slug        = wpqa_options('add_posts_slug');
		$edit_posts_id         = apply_filters('wpqa_edit_posts','edit_post');
		$edit_posts_slug       = wpqa_options('edit_posts_slug');
		$edit_comments_id      = apply_filters('wpqa_edit_comments','edit_comment');
		$edit_comments_slug    = wpqa_options('edit_comments_slug');
		
		// User Permastruct
		add_permastruct( $user_id, $user_slug . '%' . $user_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Search Permastruct
		add_permastruct( $search_id, $search_slug . '/%' . $search_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Checkout Permastruct
		add_permastruct( $checkout_id, $checkout_slug . '/%' . $checkout_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Subscriptions Permastruct
		add_permastruct( $subscriptions_id, $subscriptions_slug . '/%' . $subscriptions_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Buy points Permastruct
		add_permastruct( $buy_points_id, $buy_points_slug . '/%' . $buy_points_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Login Permastruct
		add_permastruct( $login_id, $login_slug . '/%' . $login_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Signup Permastruct
		add_permastruct( $signup_id, $signup_slug . '/%' . $signup_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Lost password Permastruct
		add_permastruct( $lost_password_id, $lost_password_slug . '/%' . $lost_password_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Add category Permastruct
		add_permastruct( $add_category_id, $add_category_slug . '/%' . $add_category_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Ask question Permastruct
		add_permastruct( $add_questions_id, $add_questions_slug . '/%' . $add_questions_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Edit question Permastruct
		add_permastruct( $edit_questions_id, $edit_questions_slug . '/%' . $edit_questions_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Add group Permastruct
		add_permastruct( $add_groups_id, $add_groups_slug . '/%' . $add_groups_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Edit group Permastruct
		add_permastruct( $edit_groups_id, $edit_groups_slug . '/%' . $edit_groups_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Group requests Permastruct
		add_permastruct( $group_requests_id, $group_requests_slug . '/%' . $group_requests_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Group users Permastruct
		add_permastruct( $group_users_id, $group_users_slug . '/%' . $group_users_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Group admins Permastruct
		add_permastruct( $group_admins_id, $group_admins_slug . '/%' . $group_admins_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Group blocked users Permastruct
		add_permastruct( $blocked_users_id, $blocked_users_slug . '/%' . $blocked_users_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Group posts Permastruct
		add_permastruct( $posts_group_id, $posts_group_slug . '/%' . $posts_group_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// View group post Permastruct
		add_permastruct( $view_posts_group_id, $view_posts_group_slug . '/%' . $view_posts_group_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Edit group post Permastruct
		add_permastruct( $edit_posts_group_id, $edit_posts_group_slug . '/%' . $edit_posts_group_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Add post Permastruct
		add_permastruct( $add_posts_id, $add_posts_slug . '/%' . $add_posts_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Edit post Permastruct
		add_permastruct( $edit_posts_id, $edit_posts_slug . '/%' . $edit_posts_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
		
		// Edit comment Permastruct
		add_permastruct( $edit_comments_id, $edit_comments_slug . '/%' . $edit_comments_id . '%', array(
			'with_front'  => false,
			'ep_mask'     => EP_NONE,
			'paged'       => true,
			'feed'        => false,
			'forcomments' => false,
			'walk_dirs'   => true,
			'endpoints'   => false,
		) );
	}
endif;
/* Get the file */
if (!function_exists('wpqa_get_template')) :
	function wpqa_get_template( $templates,$folder = '' ) {
		if (isset($templates) && is_array($templates)) {
			foreach ($templates as $template) {
				$located = wpqa_locate_template( $template, $folder );
				if ( file_exists( $located ) ) {
					$located = apply_filters( 'wpqa_get_template', $located, $template, $folder );
					return $located;
					break;
				}
			}
		}else if (isset($templates) && is_string($templates)) {
			$located = wpqa_locate_template( $templates, $folder );
			if ( file_exists( $located ) ) {
				$located = apply_filters( 'wpqa_get_template', $located, $templates, $folder );
				return $located;
			}
		}
	}
endif;
/* Something wrong */
if (!function_exists('wpqa_doing_it_wrong')) :
	function wpqa_doing_it_wrong($function,$message,$version) {
		error_log("{$function} was called incorrectly. {$message}.",$version);
		_doing_it_wrong($function,$message,$version);
	}
endif;
/* Locate template */
if (!function_exists('wpqa_locate_template')) :
	function wpqa_locate_template( $template_name, $folder = '' ) {
		global $wpqa;
		$template_path = "wpqa/".($folder != ""?$folder:"");
		$default_path = $wpqa->wpqa_main_path() . 'templates/'.($folder != ""?$folder:"");
		$template = locate_template(array(trailingslashit( $template_path ) . $template_name,$template_name));
		if (!$template) {
			$template = $default_path . $template_name;
		}
		return apply_filters( 'wpqa_locate_template', $template, $template_name, $folder );
	}
endif;
/* Templates content */
function wpqa_content() {
	if (wpqa_is_user_profile()) {
		$nicename = esc_attr(get_query_var("author_name"));
		$user_id  = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
	}

	$templates = array();
	
	if (wpqa_is_user_followers()) {
		$templates = array(
			'user-followers-'.$nicename.'.php',
			'user-followers-'.$user_id.'.php',
			'user-followers.php',
			'profile.php',
		);
	}else if (wpqa_is_user_following()) {
		$templates = array(
			'user-following-'.$nicename.'.php',
			'user-following-'.$user_id.'.php',
			'user-following.php',
			'profile.php',
		);
	}else if (wpqa_is_pending_questions()) {
		$templates = array(
			'user-pending-questions-'.$nicename.'.php',
			'user-pending-questions-'.$user_id.'.php',
			'user-pending-questions.php',
			'profile.php',
		);
	}else if (wpqa_is_pending_posts()) {
		$templates = array(
			'user-pending-posts-'.$nicename.'.php',
			'user-pending-posts-'.$user_id.'.php',
			'user-pending-posts.php',
			'profile.php',
		);
	}else if (wpqa_is_user_notifications()) {
		$templates = array(
			'user-notifications-'.$nicename.'.php',
			'user-notifications-'.$user_id.'.php',
			'user-notifications.php',
			'profile.php',
		);
	}else if (wpqa_is_user_activities()) {
		$templates = array(
			'user-activities-'.$nicename.'.php',
			'user-activities-'.$user_id.'.php',
			'user-activities.php',
			'profile.php',
		);
	}else if (wpqa_is_user_referrals()) {
		$templates = array(
			'user-referrals-'.$nicename.'.php',
			'user-referrals-'.$user_id.'.php',
			'user-referrals.php',
			'profile.php',
		);
	}else if (wpqa_is_user_messages()) {
		$templates = array(
			'user-messages-'.$nicename.'.php',
			'user-messages-'.$user_id.'.php',
			'user-messages.php',
			'profile.php',
		);
	}else if (wpqa_is_user_questions()) {
		$templates = array(
			'user-questions-'.$nicename.'.php',
			'user-questions-'.$user_id.'.php',
			'user-questions.php',
			'profile.php',
		);
	}else if (wpqa_is_user_answers()) {
		$templates = array(
			'user-answers-'.$nicename.'.php',
			'user-answers-'.$user_id.'.php',
			'user-answers.php',
			'profile.php',
		);
	}else if (wpqa_is_best_answers()) {
		$templates = array(
			'user-best-answers-'.$nicename.'.php',
			'user-best-answers-'.$user_id.'.php',
			'user-best-answers.php',
			'profile.php',
		);
	}else if (wpqa_is_user_groups()) {
		$templates = array(
			'user-groups-'.$nicename.'.php',
			'user-groups-'.$user_id.'.php',
			'user-groups.php',
			'profile.php',
		);
	}else if (wpqa_is_user_points()) {
		$templates = array(
			'user-points-'.$nicename.'.php',
			'user-points-'.$user_id.'.php',
			'user-points.php',
			'profile.php',
		);
	}else if (wpqa_is_user_polls()) {
		$templates = array(
			'user-polls-'.$nicename.'.php',
			'user-polls-'.$user_id.'.php',
			'user-polls.php',
			'profile.php',
		);
	}else if (wpqa_is_user_asked()) {
		$templates = array(
			'user-asked-'.$nicename.'.php',
			'user-asked-'.$user_id.'.php',
			'user-asked.php',
			'profile.php',
		);
	}else if (wpqa_is_asked_questions()) {
		$templates = array(
			'user-asked-questions-'.$nicename.'.php',
			'user-asked-questions-'.$user_id.'.php',
			'user-asked-questions.php',
			'profile.php',
		);
	}else if (wpqa_is_paid_questions()) {
		$templates = array(
			'user-paid-questions-'.$nicename.'.php',
			'user-paid-questions-'.$user_id.'.php',
			'user-paid-questions.php',
			'profile.php',
		);
	}else if (wpqa_is_user_followed()) {
		$templates = array(
			'user-followed-'.$nicename.'.php',
			'user-followed-'.$user_id.'.php',
			'user-followed.php',
			'profile.php',
		);
	}else if (wpqa_is_user_favorites()) {
		$templates = array(
			'user-favorites-'.$nicename.'.php',
			'user-favorites-'.$user_id.'.php',
			'user-favorites.php',
			'profile.php',
		);
	}else if (wpqa_is_user_posts()) {
		$templates = array(
			'user-posts-'.$nicename.'.php',
			'user-posts-'.$user_id.'.php',
			'user-posts.php',
			'profile.php',
		);
	}else if (wpqa_is_user_comments()) {
		$templates = array(
			'user-comments-'.$nicename.'.php',
			'user-comments-'.$user_id.'.php',
			'user-comments.php',
			'profile.php',
		);
	}else if (wpqa_is_followers_questions()) {
		$templates = array(
			'user-followers-questions-'.$nicename.'.php',
			'user-followers-questions-'.$user_id.'.php',
			'user-followers-questions.php',
			'profile.php',
		);
	}else if (wpqa_is_followers_answers()) {
		$templates = array(
			'user-followers-answers-'.$nicename.'.php',
			'user-followers-answers-'.$user_id.'.php',
			'user-followers-answers.php',
			'profile.php',
		);
	}else if (wpqa_is_followers_posts()) {
		$templates = array(
			'user-followers-posts-'.$nicename.'.php',
			'user-followers-posts-'.$user_id.'.php',
			'user-followers-posts.php',
			'profile.php',
		);
	}else if (wpqa_is_followers_comments()) {
		$templates = array(
			'user-followers-comments-'.$nicename.'.php',
			'user-followers-comments-'.$user_id.'.php',
			'user-followers-comments.php',
			'profile.php',
		);
	}else if ( wpqa_is_user_edit_profile() && wpqa_is_user_owner() ) {
		$templates = array(
			'edit-'.$nicename.'.php',
			'edit-'.$user_id.'.php',
			'edit.php',
			'profile.php',
		);
	}else if ( wpqa_is_user_password_profile() && wpqa_is_user_owner() ) {
		$templates = array(
			'password-'.$nicename.'.php',
			'password-'.$user_id.'.php',
			'password.php',
			'profile.php',
		);
	}else if ( wpqa_is_user_privacy_profile() && wpqa_is_user_owner() ) {
		$templates = array(
			'privacy-'.$nicename.'.php',
			'privacy-'.$user_id.'.php',
			'privacy.php',
			'profile.php',
		);
	}else if ( wpqa_is_user_withdrawals_profile() && wpqa_is_user_owner() ) {
		$templates = array(
			'withdrawals-'.$nicename.'.php',
			'withdrawals-'.$user_id.'.php',
			'withdrawals.php',
			'profile.php',
		);
	}else if ( wpqa_is_user_financial_profile() && wpqa_is_user_owner() ) {
		$templates = array(
			'financial-'.$nicename.'.php',
			'financial-'.$user_id.'.php',
			'financial.php',
			'profile.php',
		);
	}else if ( wpqa_is_user_transactions_profile() && wpqa_is_user_owner() ) {
		$templates = array(
			'transactions-'.$nicename.'.php',
			'transactions-'.$user_id.'.php',
			'transactions.php',
			'profile.php',
		);
	}else if ( wpqa_is_user_mails_profile() && wpqa_is_user_owner() ) {
		$templates = array(
			'mails-'.$nicename.'.php',
			'mails-'.$user_id.'.php',
			'mails.php',
			'profile.php',
		);
	}else if ( wpqa_is_user_delete_profile() && wpqa_is_user_owner() ) {
		$templates = array(
			'delete-'.$nicename.'.php',
			'delete-'.$user_id.'.php',
			'delete.php',
			'profile.php',
		);
	}else if ( wpqa_is_user_profile() ) {
		$templates = array(
			'profile-'.$nicename.'.php',
			'profile-'.$user_id.'.php',
			'profile.php',
		);
	}else if ( wpqa_is_search() ) {
		$templates = array(
			'search.php',
		);
	}else if ( wpqa_is_checkout() ) {
		$templates = array(
			'checkout.php',
		);
	}else if ( wpqa_is_subscriptions() ) {
		$templates = array(
			'subscriptions.php',
		);
	}else if ( wpqa_is_buy_points() ) {
		$templates = array(
			'buy-points.php',
		);
	}else if ( wpqa_is_login() ) {
		$templates = array(
			'login.php',
		);
	}else if ( wpqa_is_signup() ) {
		$templates = array(
			'signup.php',
		);
	}else if ( wpqa_is_lost_password() ) {
		$templates = array(
			'lost-password.php',
		);
	}else if ( wpqa_is_add_category() ) {
		$templates = array(
			'add-category.php',
		);
	}else if ( wpqa_is_add_questions() ) {
		$templates = array(
			'add-question.php',
		);
	}else if ( wpqa_is_edit_questions() ) {
		$templates = array(
			'edit-question.php',
		);
	}else if ( wpqa_is_add_groups() ) {
		$templates = array(
			'add-group.php',
		);
	}else if ( wpqa_is_edit_groups() ) {
		$templates = array(
			'edit-group.php',
		);
	}else if ( wpqa_is_group_requests() ) {
		$templates = array(
			'group-requests.php',
		);
	}else if ( wpqa_is_group_users() ) {
		$templates = array(
			'group-users.php',
		);
	}else if ( wpqa_is_group_admins() ) {
		$templates = array(
			'group-admins.php',
		);
	}else if ( wpqa_is_blocked_users() ) {
		$templates = array(
			'blocked-users.php',
		);
	}else if ( wpqa_is_posts_group() ) {
		$templates = array(
			'pending-posts.php',
		);
	}else if ( wpqa_is_view_posts_group() ) {
		$templates = array(
			'view-post-group.php',
		);
	}else if ( wpqa_is_edit_posts_group() ) {
		$templates = array(
			'edit-post-group.php',
		);
	}else if ( wpqa_is_add_posts() ) {
		$templates = array(
			'add-post.php',
		);
	}else if ( wpqa_is_edit_posts() ) {
		$templates = array(
			'edit-post.php',
		);
	}else if ( wpqa_is_edit_comments() ) {
		$templates = array(
			'edit-comment.php',
		);
	}

	$templates = apply_filters("wpqa_filter_content",$templates);
	
	if (isset($templates) && is_array($templates) && !empty($templates)) {
		if (wpqa_is_user_profile()) {
			$folder = "profile";
		}
		$wpqa_get_template = wpqa_get_template($templates,(isset($folder) && $folder != ""?$folder."/":""));
		if ($wpqa_get_template) {
			include $wpqa_get_template;
		}
	}
}
/* Include templates */
add_filter('template_include','wpqa_template_include',10);
if (!function_exists('wpqa_template_include')) :
	function wpqa_template_include ($new_template) {
		global $wp_query;
		$wpqa_template_filter = apply_filters("wpqa_template_include",false);
		if (wpqa_is_user_profile() || wpqa_is_search() || wpqa_is_checkout() || wpqa_is_subscriptions() || wpqa_is_buy_points() || wpqa_is_login() || wpqa_is_signup() || wpqa_is_lost_password() || wpqa_is_add_category() || wpqa_is_add_questions() || wpqa_is_edit_questions() || wpqa_is_add_groups() || wpqa_is_edit_groups() || wpqa_is_group_requests() || wpqa_is_group_users() || wpqa_is_group_admins() || wpqa_is_blocked_users() || wpqa_is_posts_group() || wpqa_is_view_posts_group() || wpqa_is_edit_posts_group() || wpqa_is_add_posts() || wpqa_is_edit_posts() || wpqa_is_edit_comments() || $wpqa_template_filter == true) {
			global $wp_query;
			$wp_query->is_404 = false;
			status_header( 200 );
			
			$templates = array(
				'wpqa.php'
			);
			if (!empty($templates)) {
				include wpqa_get_template($templates);
			}
		}else {
			return $new_template;
		}
	}
endif;
/* Check if user in search */
if (!function_exists('wpqa_is_search')) :
	function wpqa_is_search() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_search ) && ( true === $wp_query->wpqa_is_search ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_search', $return );
	}
endif;
/* Check if user in checkout */
if (!function_exists('wpqa_is_checkout')) :
	function wpqa_is_checkout() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_checkout ) && ( true === $wp_query->wpqa_is_checkout ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_checkout', $return );
	}
endif;
/* Check if user in subscriptions */
if (!function_exists('wpqa_is_subscriptions')) :
	function wpqa_is_subscriptions() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_subscriptions ) && ( true === $wp_query->wpqa_is_subscriptions ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_subscriptions', $return );
	}
endif;
/* Check if user in buy points */
if (!function_exists('wpqa_is_buy_points')) :
	function wpqa_is_buy_points() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_buy_points ) && ( true === $wp_query->wpqa_is_buy_points ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_buy_points', $return );
	}
endif;
/* Check if user in login */
if (!function_exists('wpqa_is_login')) :
	function wpqa_is_login() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_login ) && ( true === $wp_query->wpqa_is_login ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_login', $return );
	}
endif;
/* Check if user in signup */
if (!function_exists('wpqa_is_signup')) :
	function wpqa_is_signup() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_signup ) && ( true === $wp_query->wpqa_is_signup ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_signup', $return );
	}
endif;
/* Check if user in lost password */
if (!function_exists('wpqa_is_lost_password')) :
	function wpqa_is_lost_password() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_lost_password ) && ( true === $wp_query->wpqa_is_lost_password ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_lost_password', $return );
	}
endif;
/* Check if user in add category */
if (!function_exists('wpqa_is_add_category')) :
	function wpqa_is_add_category() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_add_category ) && ( true === $wp_query->wpqa_is_add_category ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_add_category', $return );
	}
endif;
/* Check if user in ask question */
if (!function_exists('wpqa_is_add_questions')) :
	function wpqa_is_add_questions() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_add_questions ) && ( true === $wp_query->wpqa_is_add_questions ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_add_questions', $return );
	}
endif;
/* Check if user ask question to user */
if (!function_exists('wpqa_is_add_user_questions')) :
	function wpqa_is_add_user_questions() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_add_user_questions ) && ( true === $wp_query->wpqa_is_add_user_questions ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_add_user_questions', $return );
	}
endif;
/* Check if user in edit question */
if (!function_exists('wpqa_is_edit_questions')) :
	function wpqa_is_edit_questions() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_edit_questions ) && ( true === $wp_query->wpqa_is_edit_questions ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_edit_questions', $return );
	}
endif;
/* Check if user in edit tags */
if (!function_exists('wpqa_is_edit_tags')) :
	function wpqa_is_edit_tags() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_edit_tags ) && ( true === $wp_query->wpqa_is_edit_tags ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_edit_tags', $return );
	}
endif;
/* Check if user in add group */
if (!function_exists('wpqa_is_add_groups')) :
	function wpqa_is_add_groups() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_add_groups ) && ( true === $wp_query->wpqa_is_add_groups ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_add_groups', $return );
	}
endif;
/* Check if user in edit group */
if (!function_exists('wpqa_is_edit_groups')) :
	function wpqa_is_edit_groups() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_edit_groups ) && ( true === $wp_query->wpqa_is_edit_groups ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_edit_groups', $return );
	}
endif;
/* Check if user in group requests */
if (!function_exists('wpqa_is_group_requests')) :
	function wpqa_is_group_requests() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_group_requests ) && ( true === $wp_query->wpqa_is_group_requests ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_group_requests', $return );
	}
endif;
/* Check if user in group users */
if (!function_exists('wpqa_is_group_users')) :
	function wpqa_is_group_users() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_group_users ) && ( true === $wp_query->wpqa_is_group_users ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_group_users', $return );
	}
endif;
/* Check if user in group admins */
if (!function_exists('wpqa_is_group_admins')) :
	function wpqa_is_group_admins() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_group_admins ) && ( true === $wp_query->wpqa_is_group_admins ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_group_admins', $return );
	}
endif;
/* Check if user in blocked users */
if (!function_exists('wpqa_is_blocked_users')) :
	function wpqa_is_blocked_users() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_blocked_users ) && ( true === $wp_query->wpqa_is_blocked_users ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_blocked_users', $return );
	}
endif;
/* Check if user in group posts */
if (!function_exists('wpqa_is_posts_group')) :
	function wpqa_is_posts_group() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_posts_group ) && ( true === $wp_query->wpqa_is_posts_group ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_posts_group', $return );
	}
endif;
/* Check if user in view group post */
if (!function_exists('wpqa_is_view_posts_group')) :
	function wpqa_is_view_posts_group() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_view_posts_group ) && ( true === $wp_query->wpqa_is_view_posts_group ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_view_posts_group', $return );
	}
endif;
/* Check if user in edit group post */
if (!function_exists('wpqa_is_edit_posts_group')) :
	function wpqa_is_edit_posts_group() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_edit_posts_group ) && ( true === $wp_query->wpqa_is_edit_posts_group ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_edit_posts_group', $return );
	}
endif;
/* Check if user in add post */
if (!function_exists('wpqa_is_add_posts')) :
	function wpqa_is_add_posts() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_add_posts ) && ( true === $wp_query->wpqa_is_add_posts ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_add_posts', $return );
	}
endif;
/* Check if user in edit post */
if (!function_exists('wpqa_is_edit_posts')) :
	function wpqa_is_edit_posts() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_edit_posts ) && ( true === $wp_query->wpqa_is_edit_posts ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_edit_posts', $return );
	}
endif;
/* Check if user in edit comment */
if (!function_exists('wpqa_is_edit_comments')) :
	function wpqa_is_edit_comments() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_edit_comments ) && ( true === $wp_query->wpqa_is_edit_comments ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_edit_comments', $return );
	}
endif;
/* Check if the user in profile */
if (!function_exists('wpqa_is_user_profile')) :
	function wpqa_is_user_profile() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_profile ) && ( true === $wp_query->wpqa_is_user_profile ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_profile', $return );
	}
endif;
/* Check if the user in home profile */
if (!function_exists('wpqa_is_home_profile')) :
	function wpqa_is_home_profile() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_home_profile ) && ( true === $wp_query->wpqa_is_home_profile ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_home_profile', $return );
	}
endif;
/* Check if the user in owne profile */
if (!function_exists('wpqa_is_user_owner')) :
	function wpqa_is_user_owner() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_owner ) && ( true === $wp_query->wpqa_is_user_owner ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_owner', $return );
	}
endif;
/* Check if the user in edit profile */
if (!function_exists('wpqa_is_user_edit_profile')) :
	function wpqa_is_user_edit_profile() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_edit_profile ) && ( true === $wp_query->wpqa_is_user_edit_profile ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_edit_profile', $return );
	}
endif;
/* Check if the user in edit profile home */
if (!function_exists('wpqa_is_user_edit_home')) :
	function wpqa_is_user_edit_home() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_edit_home ) && ( true === $wp_query->wpqa_is_user_edit_home ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_edit_home', $return );
	}
endif;
/* Check if the user in password profile */
if (!function_exists('wpqa_is_user_password_profile')) :
	function wpqa_is_user_password_profile() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_password_profile ) && ( true === $wp_query->wpqa_is_user_password_profile ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_password_profile', $return );
	}
endif;
/* Check if the user in privacy profile */
if (!function_exists('wpqa_is_user_privacy_profile')) :
	function wpqa_is_user_privacy_profile() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_privacy_profile ) && ( true === $wp_query->wpqa_is_user_privacy_profile ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_privacy_profile', $return );
	}
endif;
/* Check if the user in withdrawals profile */
if (!function_exists('wpqa_is_user_withdrawals_profile')) :
	function wpqa_is_user_withdrawals_profile() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_withdrawals_profile ) && ( true === $wp_query->wpqa_is_user_withdrawals_profile ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_withdrawals_profile', $return );
	}
endif;
/* Check if the user in financial profile */
if (!function_exists('wpqa_is_user_financial_profile')) :
	function wpqa_is_user_financial_profile() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_financial_profile ) && ( true === $wp_query->wpqa_is_user_financial_profile ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_financial_profile', $return );
	}
endif;
/* Check if the user in transactions profile */
if (!function_exists('wpqa_is_user_transactions_profile')) :
	function wpqa_is_user_transactions_profile() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_transactions_profile ) && ( true === $wp_query->wpqa_is_user_transactions_profile ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_transactions_profile', $return );
	}
endif;
/* Check if the user in mails profile */
if (!function_exists('wpqa_is_user_mails_profile')) :
	function wpqa_is_user_mails_profile() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_mails_profile ) && ( true === $wp_query->wpqa_is_user_mails_profile ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_mails_profile', $return );
	}
endif;
/* Check if the user in delete profile */
if (!function_exists('wpqa_is_user_delete_profile')) :
	function wpqa_is_user_delete_profile() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_delete_profile ) && ( true === $wp_query->wpqa_is_user_delete_profile ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_delete_profile', $return );
	}
endif;
/* Check if the user has profile */
if (!function_exists('wpqa_user_has_profile')) :
	function wpqa_user_has_profile( $user_id = 0 ) {
		$return  = true;
		$user_id = wpqa_get_user_id( $user_id, true, true );
		$user    = get_userdata( $user_id );
		if ( empty( $user ) ) {
			$return = false;
		}
		return (bool) apply_filters( 'wpqa_show_user_profile', $return, $user_id );
	}
endif;
/* Check if the user in user followers */
if (!function_exists('wpqa_is_user_followers')) :
	function wpqa_is_user_followers() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_followers ) && ( true === $wp_query->wpqa_is_user_followers ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_followers', $return );
	}
endif;
/* Check if the user in user following */
if (!function_exists('wpqa_is_user_following')) :
	function wpqa_is_user_following() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_following ) && ( true === $wp_query->wpqa_is_user_following ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_following', $return );
	}
endif;
/* Check if the user in user pending questions */
if (!function_exists('wpqa_is_pending_questions')) :
	function wpqa_is_pending_questions() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_pending_questions ) && ( true === $wp_query->wpqa_is_pending_questions ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_pending_questions', $return );
	}
endif;
/* Check if the user in user pending posts */
if (!function_exists('wpqa_is_pending_posts')) :
	function wpqa_is_pending_posts() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_pending_posts ) && ( true === $wp_query->wpqa_is_pending_posts ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_pending_posts', $return );
	}
endif;
/* Check if the user in user notifications */
if (!function_exists('wpqa_is_user_notifications')) :
	function wpqa_is_user_notifications() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_notifications ) && ( true === $wp_query->wpqa_is_user_notifications ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_notifications', $return );
	}
endif;
/* Check if the user in user activities */
if (!function_exists('wpqa_is_user_activities')) :
	function wpqa_is_user_activities() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_activities ) && ( true === $wp_query->wpqa_is_user_activities ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_activities', $return );
	}
endif;
/* Check if the user in user referrals */
if (!function_exists('wpqa_is_user_referrals')) :
	function wpqa_is_user_referrals() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_referrals ) && ( true === $wp_query->wpqa_is_user_referrals ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_referrals', $return );
	}
endif;
/* Check if the user in user messages */
if (!function_exists('wpqa_is_user_messages')) :
	function wpqa_is_user_messages() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_messages ) && ( true === $wp_query->wpqa_is_user_messages ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_messages', $return );
	}
endif;
/* Check if the user in user questions */
if (!function_exists('wpqa_is_user_questions')) :
	function wpqa_is_user_questions() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_questions ) && ( true === $wp_query->wpqa_is_user_questions ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_questions', $return );
	}
endif;
/* Check if the user in user answers */
if (!function_exists('wpqa_is_user_answers')) :
	function wpqa_is_user_answers() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_answers ) && ( true === $wp_query->wpqa_is_user_answers ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_answers', $return );
	}
endif;
/* Check if the user in user best answers */
if (!function_exists('wpqa_is_best_answers')) :
	function wpqa_is_best_answers() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_best_answers ) && ( true === $wp_query->wpqa_is_best_answers ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_best_answers', $return );
	}
endif;
/* Check if the user in user groups */
if (!function_exists('wpqa_is_user_groups')) :
	function wpqa_is_user_groups() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_groups ) && ( true === $wp_query->wpqa_is_user_groups ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_groups', $return );
	}
endif;
/* Check if the user in user points */
if (!function_exists('wpqa_is_user_points')) :
	function wpqa_is_user_points() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_points ) && ( true === $wp_query->wpqa_is_user_points ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_points', $return );
	}
endif;
/* Check if the user in user polls */
if (!function_exists('wpqa_is_user_polls')) :
	function wpqa_is_user_polls() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_polls ) && ( true === $wp_query->wpqa_is_user_polls ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_polls', $return );
	}
endif;
/* Check if the user in user asked */
if (!function_exists('wpqa_is_user_asked')) :
	function wpqa_is_user_asked() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_asked ) && ( true === $wp_query->wpqa_is_user_asked ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_asked', $return );
	}
endif;
/* Check if the user in user asked questions */
if (!function_exists('wpqa_is_asked_questions')) :
	function wpqa_is_asked_questions() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_asked_questions ) && ( true === $wp_query->wpqa_is_asked_questions ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_asked_questions', $return );
	}
endif;
/* Check if the user in user asked questions */
if (!function_exists('wpqa_is_paid_questions')) :
	function wpqa_is_paid_questions() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_paid_questions ) && ( true === $wp_query->wpqa_is_paid_questions ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_paid_questions', $return );
	}
endif;
/* Check if the user in user followed */
if (!function_exists('wpqa_is_user_followed')) :
	function wpqa_is_user_followed() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_followed ) && ( true === $wp_query->wpqa_is_user_followed ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_followed', $return );
	}
endif;
/* Check if the user in user favorites */
if (!function_exists('wpqa_is_user_favorites')) :
	function wpqa_is_user_favorites() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_favorites ) && ( true === $wp_query->wpqa_is_user_favorites ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_favorites', $return );
	}
endif;
/* Check if the user in user posts */
if (!function_exists('wpqa_is_user_posts')) :
	function wpqa_is_user_posts() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_posts ) && ( true === $wp_query->wpqa_is_user_posts ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_posts', $return );
	}
endif;
/* Check if the user in user comments */
if (!function_exists('wpqa_is_user_comments')) :
	function wpqa_is_user_comments() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_user_comments ) && ( true === $wp_query->wpqa_is_user_comments ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_user_comments', $return );
	}
endif;
/* Check if the user in user followers questions */
if (!function_exists('wpqa_is_followers_questions')) :
	function wpqa_is_followers_questions() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_followers_questions ) && ( true === $wp_query->wpqa_is_followers_questions ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_followers_questions', $return );
	}
endif;
/* Check if the user in user followers answers */
if (!function_exists('wpqa_is_followers_answers')) :
	function wpqa_is_followers_answers() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_followers_answers ) && ( true === $wp_query->wpqa_is_followers_answers ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_followers_answers', $return );
	}
endif;
/* Check if the user in user followers posts */
if (!function_exists('wpqa_is_followers_posts')) :
	function wpqa_is_followers_posts() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_followers_posts ) && ( true === $wp_query->wpqa_is_followers_posts ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_followers_posts', $return );
	}
endif;
/* Check if the user in user followers comments */
if (!function_exists('wpqa_is_followers_comments')) :
	function wpqa_is_followers_comments() {
		global $wp_query;
		$return = false;
		if ( !empty( $wp_query->wpqa_is_followers_comments ) && ( true === $wp_query->wpqa_is_followers_comments ) )
			$return = true;
		return (bool) apply_filters( 'wpqa_is_followers_comments', $return );
	}
endif;
/* Get user id */
if (!function_exists('wpqa_get_user_id')) :
	function wpqa_get_user_id( $user_id = 0, $displayed_user_fallback = true, $current_user_fallback = false ) {
		if ( !empty( $user_id ) && is_numeric( $user_id ) ) {
			$wpqa_user_id = $user_id;
		}else {
			$wpqa_user_id = 0;
		}
		return (int) apply_filters( 'wpqa_get_user_id', (int) $wpqa_user_id, $displayed_user_fallback, $current_user_fallback );
	}
endif;
/* Get profile title */
if (!function_exists('wpqa_user_title')) :
	function wpqa_user_title() {
		$title = "";
		if (wpqa_is_user_followers()) {
			$title = "followers";
		}else if (wpqa_is_user_following()) {
			$title = "following";
		}else if (wpqa_is_pending_questions()) {
			$title = "pending-questions";
		}else if (wpqa_is_pending_posts()) {
			$title = "pending-posts";
		}else if (wpqa_is_user_notifications()) {
			$title = "notifications";
		}else if (wpqa_is_user_activities()) {
			$title = "activities";
		}else if (wpqa_is_user_referrals()) {
			$title = "referrals";
		}else if (wpqa_is_user_messages()) {
			$title = "messages";
		}else if (wpqa_is_user_questions()) {
			$title = "questions";
		}else if (wpqa_is_user_answers()) {
			$title = "answers";
		}else if (wpqa_is_best_answers()) {
			$title = "best-answers";
		}else if (wpqa_is_user_groups()) {
			$title = "groups";
		}else if (wpqa_is_user_points()) {
			$title = "points";
		}else if (wpqa_is_user_polls()) {
			$title = "polls";
		}else if (wpqa_is_user_asked()) {
			$title = "asked";
		}else if (wpqa_is_asked_questions()) {
			$title = "asked-questions";
		}else if (wpqa_is_paid_questions()) {
			$title = "paid-questions";
		}else if (wpqa_is_user_followed()) {
			$title = "followed";
		}else if (wpqa_is_user_favorites()) {
			$title = "favorites";
		}else if (wpqa_is_user_posts()) {
			$title = "posts";
		}else if (wpqa_is_user_comments()) {
			$title = "comments";
		}else if (wpqa_is_followers_questions()) {
			$title = "followers-questions";
		}else if (wpqa_is_followers_answers()) {
			$title = "followers-answers";
		}else if (wpqa_is_followers_posts()) {
			$title = "followers-posts";
		}else if (wpqa_is_followers_comments()) {
			$title = "followers-comments";
		}else if (wpqa_is_user_edit_profile()) {
			$title = "edit";
		}else if (wpqa_is_user_password_profile()) {
			$title = "password";
		}else if (wpqa_is_user_privacy_profile()) {
			$title = "privacy";
		}else if (wpqa_is_user_withdrawals_profile()) {
			$title = "withdrawals";
		}else if (wpqa_is_user_financial_profile()) {
			$title = "financial";
		}else if (wpqa_is_user_transactions_profile()) {
			$title = "transactions";
		}else if (wpqa_is_user_mails_profile()) {
			$title = "mails";
		}else if (wpqa_is_user_delete_profile()) {
			$title = "delete";
		}
		return apply_filters("wpqa_filter_user_title",$title);
	}
endif;
/* Get profile title */
if (!function_exists('wpqa_profile_title')) :
	function wpqa_profile_title() {
		$title = "";
		if (wpqa_is_user_followers()) {
			$title = esc_html__("Followers","wpqa");
		}else if (wpqa_is_user_following()) {
			$title = esc_html__("Following","wpqa");
		}else if (wpqa_is_pending_questions()) {
			$title = esc_html__("Pending Questions","wpqa");
		}else if (wpqa_is_pending_posts()) {
			$title = esc_html__("Pending Posts","wpqa");
		}else if (wpqa_is_user_notifications()) {
			$title = esc_html__("Notifications","wpqa");
		}else if (wpqa_is_user_activities()) {
			$title = esc_html__("Activities","wpqa");
		}else if (wpqa_is_user_referrals()) {
			$title = esc_html__("Referrals","wpqa");
		}else if (wpqa_is_user_messages()) {
			$title = esc_html__("Messages","wpqa");
		}else if (wpqa_is_user_questions()) {
			$title = esc_html__("Questions","wpqa");
		}else if (wpqa_is_user_answers()) {
			$title = esc_html__("Answers","wpqa");
		}else if (wpqa_is_best_answers()) {
			$title = esc_html__("Best Answers","wpqa");
		}else if (wpqa_is_user_groups()) {
			$title = esc_html__("Groups","wpqa");
		}else if (wpqa_is_user_points()) {
			$title = esc_html__("Points","wpqa");
		}else if (wpqa_is_user_polls()) {
			$title = esc_html__("Polls","wpqa");
		}else if (wpqa_is_user_asked()) {
			$title = esc_html__("Asked Questions","wpqa");
		}else if (wpqa_is_asked_questions()) {
			$title = esc_html__("Waiting Questions","wpqa");
		}else if (wpqa_is_paid_questions()) {
			$title = esc_html__("Paid Questions","wpqa");
		}else if (wpqa_is_user_followed()) {
			$title = esc_html__("Followed","wpqa");
		}else if (wpqa_is_user_favorites()) {
			$title = esc_html__("Favorites","wpqa");
		}else if (wpqa_is_user_posts()) {
			$title = esc_html__("Posts","wpqa");
		}else if (wpqa_is_user_comments()) {
			$title = esc_html__("Comments","wpqa");
		}else if (wpqa_is_followers_questions()) {
			$title = esc_html__("Followers Questions","wpqa");
		}else if (wpqa_is_followers_answers()) {
			$title = esc_html__("Followers Answers","wpqa");
		}else if (wpqa_is_followers_posts()) {
			$title = esc_html__("Followers Posts","wpqa");
		}else if (wpqa_is_followers_comments()) {
			$title = esc_html__("Followers Comments","wpqa");
		}else if (wpqa_is_user_edit_profile()) {
			$title = esc_html__("Edit profile","wpqa");
		}else if (wpqa_is_user_password_profile()) {
			$title = esc_html__("Change Password","wpqa");
		}else if (wpqa_is_user_privacy_profile()) {
			$title = esc_html__("Privacy","wpqa");
		}else if (wpqa_is_user_withdrawals_profile()) {
			$title = esc_html__("Withdrawals","wpqa");
		}else if (wpqa_is_user_financial_profile()) {
			$title = esc_html__("Financial","wpqa");
		}else if (wpqa_is_user_transactions_profile()) {
			$title = esc_html__("Transactions","wpqa");
		}else if (wpqa_is_user_mails_profile()) {
			$title = esc_html__("Mail settings","wpqa");
		}else if (wpqa_is_user_delete_profile()) {
			$title = esc_html__("Delete account","wpqa");
		}
		return apply_filters("wpqa_filter_profile_title",$title);
	}
endif;
/* Parse query */
add_action('parse_query','wpqa_parse_query',2);
if (!function_exists('wpqa_parse_query')) :
	function wpqa_parse_query($posts_query) {
		if (!$posts_query->is_main_query() || true === $posts_query->get('suppress_filters') || is_admin() || is_page() || is_single()) {
			return;
		}
		
		$is_user          = $posts_query->get(apply_filters('wpqa_user_id','wpqa_user_id'));
		$is_search        = $posts_query->get(apply_filters('wpqa_search_id','search'));
		$is_checkout      = $posts_query->get(apply_filters('wpqa_checkout_id','checkout'));
		$is_question      = $posts_query->get(apply_filters('wpqa_edit_questions','edit_question'));
		$is_group         = $posts_query->get(apply_filters('wpqa_edit_groups','edit_group'));
		$group_requests   = $posts_query->get(apply_filters('wpqa_group_requests','group_request'));
		$group_users      = $posts_query->get(apply_filters('wpqa_group_users','group_user'));
		$group_admins     = $posts_query->get(apply_filters('wpqa_group_admins','group_admin'));
		$blocked_users    = $posts_query->get(apply_filters('wpqa_blocked_users','blocked_user'));
		$posts_group      = $posts_query->get(apply_filters('wpqa_posts_group','post_group'));
		$view_posts_group = $posts_query->get(apply_filters('wpqa_view_posts_group','view_post_group'));
		$edit_posts_group = $posts_query->get(apply_filters('wpqa_edit_posts_group','edit_post_group'));
		$is_post          = $posts_query->get(apply_filters('wpqa_edit_posts','edit_post'));
		$is_comment       = $posts_query->get(apply_filters('wpqa_edit_comments','edit_comment'));
		$is_edit          = $posts_query->get(apply_filters('wpqa_edit_id','edit'));
		$is_password      = $posts_query->get(apply_filters('wpqa_password_id','password'));
		$is_privacy       = $posts_query->get(apply_filters('wpqa_privacy_id','privacy'));
		$is_withdrawals   = $posts_query->get(apply_filters('wpqa_withdrawals_id','withdrawals'));
		$is_financial     = $posts_query->get(apply_filters('wpqa_financial_id','financial'));
		$is_transactions  = $posts_query->get(apply_filters('wpqa_transactions_id','transactions'));
		$is_mails         = $posts_query->get(apply_filters('wpqa_mails_id','mails'));
		$is_delete        = $posts_query->get(apply_filters('wpqa_delete_id','delete'));
		
		if ( !empty( $is_user ) || !empty( $is_search ) || !empty( $is_checkout ) || !empty( $is_question ) || !empty( $is_group ) || !empty( $group_requests ) || !empty( $group_users ) || !empty( $group_admins ) || !empty( $blocked_users ) || !empty( $posts_group ) || !empty( $view_posts_group ) || !empty( $edit_posts_group ) || !empty( $is_post ) || !empty( $is_comment ) || isset($posts_query->query_vars[apply_filters('wpqa_subscriptions','wpqa_subscriptions')]) || isset($posts_query->query_vars[apply_filters('wpqa_buy_points','wpqa_buy_points')]) || isset($posts_query->query_vars[apply_filters('wpqa_login','wpqa_login')]) || isset($posts_query->query_vars[apply_filters('wpqa_signup','wpqa_signup')]) || isset($posts_query->query_vars[apply_filters('wpqa_lost_password','wpqa_lost_password')]) || isset($posts_query->query_vars[apply_filters('wpqa_add_categories','add_category')]) || isset($posts_query->query_vars[apply_filters('wpqa_add_questions','add_question')]) || isset($posts_query->query_vars[apply_filters('wpqa_add_groups','add_group')]) || isset($posts_query->query_vars[apply_filters('wpqa_add_posts','wpqa_add_post')]) ) {
			$posts_query->is_home = false;
			$posts_query->is_404  = false;
		}
		
		if ( !empty($is_question) ) {
			$the_question = false;
			if ( is_numeric( $is_question ) ) {
				$the_question = get_post( $is_question );
			}
		}
		
		if ( !empty($is_group) ) {
			$the_group = false;
			if ( is_numeric( $is_group ) ) {
				$the_group = get_post( $is_group );
			}
		}
		
		if ( !empty($group_requests) ) {
			$group_requests = false;
			if ( is_numeric( $group_requests ) ) {
				$the_group = get_post( $group_requests );
			}
		}
		
		if ( !empty($group_users) ) {
			$group_users = false;
			if ( is_numeric( $group_users ) ) {
				$the_group = get_post( $group_users );
			}
		}
		
		if ( !empty($group_admins) ) {
			$group_admins = false;
			if ( is_numeric( $group_admins ) ) {
				$the_group = get_post( $group_admins );
			}
		}
		
		if ( !empty($blocked_users) ) {
			$blocked_users = false;
			if ( is_numeric( $blocked_users ) ) {
				$the_group = get_post( $blocked_users );
			}
		}
		
		if ( !empty($posts_group) ) {
			$the_group = false;
			if ( is_numeric( $posts_group ) ) {
				$the_group = get_post( $posts_group );
			}
		}
		
		if ( !empty($view_posts_group) ) {
			$the_post = false;
			if ( is_numeric( $view_posts_group ) ) {
				$the_post = get_post( $view_posts_group );
			}
		}
		
		if ( !empty($edit_posts_group) ) {
			$the_post = false;
			if ( is_numeric( $edit_posts_group ) ) {
				$the_post = get_post( $edit_posts_group );
			}
		}
		
		if ( !empty($is_post) ) {
			$the_post = false;
			if ( is_numeric( $is_post ) ) {
				$the_post = get_post( $is_post );
			}
		}
		
		if ( !empty($is_comment) ) {
			$the_comment = false;
			if ( is_numeric( $is_comment ) ) {
				$the_comment = get_comment( $is_comment );
			}
		}
		
		if ( !empty( $is_user ) ) {
			$the_user = false;
			if ( get_option( 'permalink_structure' ) ) {
				$profile_type = wpqa_options('profile_type');
				$the_user = get_user_by(($profile_type == 'login'?'login':'slug'),$is_user);
				if (isset($is_user) && is_object($is_user)) {
					$the_user = get_userdata(esc_attr($is_user->ID));
				}
				if (isset($the_user) && !is_object($the_user)) {
					$the_user = get_user_by(($profile_type == 'login'?'slug':'login'),urldecode($is_user));
				}
				if (isset($the_user) && !is_object($the_user)) {
					$the_user = get_user_by(($profile_type == 'login'?'slug':'login'),str_ireplace("-"," ",urldecode($is_user)));
				}
				if (isset($the_user) && !is_object($the_user)) {
					$the_user = get_user_by('login',str_ireplace("-"," ",urldecode($is_user)));
				}
				if ( is_numeric( $is_user ) ) {
					$the_user = get_user_by('id',$is_user);
					if (isset($the_user) && !is_object($the_user)) {
						$the_user = get_user_by('login',$is_user);
					}
					if (isset($the_user) && !is_object($the_user)) {
						$the_user = get_user_by('slug',urldecode($is_user));
					}
				}
			}else if ( is_numeric( $is_user ) ) {
				$the_user = get_user_by('id',$is_user);
			}
			
			if ( empty( $the_user->ID ) || ! wpqa_user_has_profile( $the_user->ID ) ) {
				$posts_query->set_404();
				return;
			}
			
			$is_followers           = $posts_query->get(apply_filters('wpqa_followers','followers'));
			$is_following           = $posts_query->get(apply_filters('wpqa_following','following'));
			$is_pending_questions   = $posts_query->get(apply_filters('wpqa_pending_questions','pending_questions'));
			$is_pending_posts       = $posts_query->get(apply_filters('wpqa_pending_posts','pending_posts'));
			$is_notifications       = $posts_query->get(apply_filters('wpqa_notifications','notifications'));
			$is_activities          = $posts_query->get(apply_filters('wpqa_activities','activities'));
			$is_referrals           = $posts_query->get(apply_filters('wpqa_referrals','referrals'));
			$is_messages            = $posts_query->get(apply_filters('wpqa_messages','messages'));
			$is_questions           = $posts_query->get(apply_filters('wpqa_questions','questions'));
			$is_answers             = $posts_query->get(apply_filters('wpqa_answers','answers'));
			$is_best_answers        = $posts_query->get(apply_filters('wpqa_best_answers','best_answers'));
			$is_groups              = $posts_query->get(apply_filters('wpqa_groups','groups'));
			$is_points              = $posts_query->get(apply_filters('wpqa_points','points'));
			$is_polls               = $posts_query->get(apply_filters('wpqa_polls','polls'));
			$is_asked               = $posts_query->get(apply_filters('wpqa_asked','asked'));
			$is_asked_questions     = $posts_query->get(apply_filters('wpqa_asked_questions','asked_questions'));
			$is_paid_questions      = $posts_query->get(apply_filters('wpqa_paid_questions','paid_questions'));
			$is_followed            = $posts_query->get(apply_filters('wpqa_followed','followed'));
			$is_favorites           = $posts_query->get(apply_filters('wpqa_favorites','favorites'));
			$is_posts               = $posts_query->get(apply_filters('wpqa_posts','posts'));
			$is_comments            = $posts_query->get(apply_filters('wpqa_comments','comments'));
			$is_followers_questions = $posts_query->get(apply_filters('wpqa_followers_questions','followers_questions'));
			$is_followers_answers   = $posts_query->get(apply_filters('wpqa_followers_answers','followers_answers'));
			$is_followers_posts     = $posts_query->get(apply_filters('wpqa_followers_posts','followers_posts'));
			$is_followers_comments  = $posts_query->get(apply_filters('wpqa_followers_comments','followers_comments'));
			
			if ( !empty( $is_edit ) ) {
				$posts_query->wpqa_is_user_edit_profile = true;
				$posts_query->wpqa_is_user_edit_home = true;
			}else if ( !empty( $is_password ) ) {
				$posts_query->wpqa_is_user_password_profile = true;
				$posts_query->wpqa_is_user_edit_home = true;
			}else if ( !empty( $is_privacy ) ) {
				$posts_query->wpqa_is_user_privacy_profile = true;
				$posts_query->wpqa_is_user_edit_home = true;
			}else if ( !empty( $is_withdrawals ) ) {
				$posts_query->wpqa_is_user_withdrawals_profile = true;
				$posts_query->wpqa_is_user_edit_home = true;
			}else if ( !empty( $is_financial ) ) {
				$posts_query->wpqa_is_user_financial_profile = true;
				$posts_query->wpqa_is_user_edit_home = true;
			}else if ( !empty( $is_delete ) ) {
				$posts_query->wpqa_is_user_delete_profile = true;
				$posts_query->wpqa_is_user_edit_home = true;
			}else if ( !empty( $is_mails ) ) {
				$posts_query->wpqa_is_user_mails_profile = true;
				$posts_query->wpqa_is_user_edit_home = true;
			}else if ( !empty( $is_transactions ) ) {
				$posts_query->wpqa_is_user_transactions_profile = true;
			}else if ( ! empty( $is_followers ) ) {
				$posts_query->wpqa_is_user_followers = true;
			}else if ( ! empty( $is_following ) ) {
				$posts_query->wpqa_is_user_following = true;
			}else if ( ! empty( $is_pending_questions ) ) {
				$posts_query->wpqa_is_pending_questions = true;
			}else if ( ! empty( $is_pending_posts ) ) {
				$posts_query->wpqa_is_pending_posts = true;
			}else if ( ! empty( $is_notifications ) ) {
				$posts_query->wpqa_is_user_notifications = true;
			}else if ( ! empty( $is_activities ) ) {
				$posts_query->wpqa_is_user_activities = true;
			}else if ( ! empty( $is_referrals ) ) {
				$posts_query->wpqa_is_user_referrals = true;
			}else if ( ! empty( $is_messages ) ) {
				$posts_query->wpqa_is_user_messages = true;
			}else if ( ! empty( $is_questions ) ) {
				$posts_query->wpqa_is_user_questions = true;
			}else if ( ! empty( $is_answers ) ) {
				$posts_query->wpqa_is_user_answers = true;
			}else if ( ! empty( $is_best_answers ) ) {
				$posts_query->wpqa_is_best_answers = true;
			}else if ( ! empty( $is_groups ) ) {
				$posts_query->wpqa_is_user_groups = true;
			}else if ( ! empty( $is_points ) ) {
				$posts_query->wpqa_is_user_points = true;
			}else if ( ! empty( $is_polls ) ) {
				$posts_query->wpqa_is_user_polls = true;
			}else if ( ! empty( $is_asked ) ) {
				$posts_query->wpqa_is_user_asked = true;
			}else if ( ! empty( $is_asked_questions ) ) {
				$posts_query->wpqa_is_asked_questions = true;
			}else if ( ! empty( $is_paid_questions ) ) {
				$posts_query->wpqa_is_paid_questions = true;
			}else if ( ! empty( $is_followed ) ) {
				$posts_query->wpqa_is_user_followed = true;
			}else if ( ! empty( $is_favorites ) ) {
				$posts_query->wpqa_is_user_favorites = true;
			}else if ( ! empty( $is_posts ) ) {
				$posts_query->wpqa_is_user_posts = true;
			}else if ( ! empty( $is_comments ) ) {
				$posts_query->wpqa_is_user_comments = true;
			}else if ( ! empty( $is_followers_questions ) ) {
				$posts_query->wpqa_is_followers_questions = true;
			}else if ( ! empty( $is_followers_answers ) ) {
				$posts_query->wpqa_is_followers_answers = true;
			}else if ( ! empty( $is_followers_posts ) ) {
				$posts_query->wpqa_is_followers_posts = true;
			}else if ( ! empty( $is_followers_comments ) ) {
				$posts_query->wpqa_is_followers_comments = true;
			}else {
				$posts_query->wpqa_is_home_profile = true;
			}
			$posts_query->wpqa_is_user_profile = true;
			if ( get_current_user_id() === $the_user->ID ) {
				$posts_query->wpqa_is_user_owner = true;
			}
			$posts_query->set('wpqa_user_id',$the_user->ID);
			$profile_type = wpqa_options("profile_type");
			$posts_query->set('author_name',trim(urldecode(esc_attr($profile_type != "login" && isset($the_user->user_nicename) && $the_user->user_nicename != ""?$the_user->user_nicename:$the_user->user_login))));
			$posts_query->set('wpqa_user',trim(urldecode(esc_attr($profile_type != "login" && isset($the_user->user_nicename) && $the_user->user_nicename != ""?$the_user->user_nicename:$the_user->user_login))));
			$displayed_user = $the_user;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_search_id','search')]) ) {
			$search_terms = wpqa_search_terms();
			if ( !empty( $search_terms ) )
				$posts_query->wpqa_search_terms = $search_terms;
			$posts_query->wpqa_is_search = true;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_checkout_id','checkout')]) ) {
			$checkout_term = wpqa_checkout_term();
			if ( !empty( $checkout_term ) )
				$posts_query->wpqa_checkout_term = $checkout_term;
			$checkout_item = wpqa_checkout_item();
			if ( !empty( $checkout_item ) )
				$posts_query->wpqa_checkout_item = $checkout_item;
			$checkout_related = wpqa_checkout_related();
			if ( !empty( $checkout_related ) )
				$posts_query->wpqa_checkout_related = $checkout_related;
			$posts_query->wpqa_is_checkout = true;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_subscriptions','wpqa_subscriptions')]) ) {
			$posts_query->wpqa_is_subscriptions = true;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_buy_points','wpqa_buy_points')]) ) {
			$posts_query->wpqa_is_buy_points = true;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_login','wpqa_login')]) ) {
			$posts_query->wpqa_is_login = true;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_signup','wpqa_signup')]) ) {
			$posts_query->wpqa_is_signup = true;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_lost_password','wpqa_lost_password')]) ) {
			$posts_query->wpqa_is_lost_password = true;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_add_categories','add_category')]) ) {
			$posts_query->wpqa_is_add_category = true;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_add_questions','add_question')]) ) {
			$posts_query->wpqa_is_add_questions = true;
			if ($posts_query->query_vars[apply_filters('wpqa_add_questions','add_question')] != "") {
				$posts_query->wpqa_is_add_user_questions = true;
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_edit_questions','edit_question')]) ) {
			$posts_query->wpqa_is_edit_questions = true;
			if ( !empty( $the_question->ID ) ) {
				$posts_query->set('edit_question',$the_question->ID);
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_add_groups','add_group')]) ) {
			$posts_query->wpqa_is_add_groups = true;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_edit_groups','edit_group')]) ) {
			$posts_query->wpqa_is_edit_groups = true;
			if ( !empty( $the_group->ID ) ) {
				$posts_query->set('edit_group',$the_group->ID);
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_group_requests','group_request')]) ) {
			$posts_query->wpqa_is_group_requests = true;
			if ( !empty( $the_group->ID ) ) {
				$posts_query->set('group_request',$the_group->ID);
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_group_users','group_user')]) ) {
			$posts_query->wpqa_is_group_users = true;
			if ( !empty( $the_group->ID ) ) {
				$posts_query->set('group_user',$the_group->ID);
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_group_admins','group_admin')]) ) {
			$posts_query->wpqa_is_group_admins = true;
			if ( !empty( $the_group->ID ) ) {
				$posts_query->set('group_admin',$the_group->ID);
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_blocked_users','blocked_user')]) ) {
			$posts_query->wpqa_is_blocked_users = true;
			if ( !empty( $the_group->ID ) ) {
				$posts_query->set('blocked_user',$the_group->ID);
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_posts_group','post_group')]) ) {
			$posts_query->wpqa_is_posts_group = true;
			if ( !empty( $the_group->ID ) ) {
				$posts_query->set('post_group',$the_group->ID);
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_view_posts_group','view_post_group')]) ) {
			$posts_query->wpqa_is_view_posts_group = true;
			if ( !empty( $the_post->ID ) ) {
				$posts_query->set('view_post_group',$the_post->ID);
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_edit_posts_group','edit_post_group')]) ) {
			$posts_query->wpqa_is_edit_posts_group = true;
			if ( !empty( $the_post->ID ) ) {
				$posts_query->set('edit_post_group',$the_post->ID);
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_add_posts','wpqa_add_post')]) ) {
			$posts_query->wpqa_is_add_posts = true;
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_edit_posts','edit_post')]) ) {
			$posts_query->wpqa_is_edit_posts = true;
			if ( !empty( $the_post->ID ) ) {
				$posts_query->set('edit_post',$the_post->ID);
			}
		}else if ( isset($posts_query->query_vars[apply_filters('wpqa_edit_comments','edit_comment')]) ) {
			$posts_query->wpqa_is_edit_comments = true;
			if ( !empty( $the_comment->ID ) ) {
				$posts_query->set('edit_comment',$the_comment->ID);
			}
		}
	}
endif;
/* Parse args */
if (!function_exists('wpqa_parse_args')) :
	function wpqa_parse_args( $args, $defaults = array(), $filter_key = '' ) {
		if ( is_object( $args ) ) {
			$r = get_object_vars( $args );
		}else if ( is_array( $args ) ) {
			$r =& $args;
		}else {
			wp_parse_str( $args, $r );
		}
		if ( !empty( $filter_key ) ) {
			$r = apply_filters( 'wpqa_before_' . $filter_key . '_parse_args', $r );
		}
		if ( is_array( $defaults ) && !empty( $defaults ) ) {
			$r = array_merge( $defaults, $r );
		}
		if ( !empty( $filter_key ) ) {
			$r = apply_filters( 'wpqa_after_' . $filter_key . '_parse_args', $r );
		}
		return $r;
	}
endif;
/* Search var */
if (!function_exists('wpqa_search')) :
	function wpqa_search($encode = "") {
		$search_value = (get_query_var(apply_filters('wpqa_search_id','search')) != ""?get_query_var(apply_filters('wpqa_search_id','search')):(get_query_var('s') != ""?get_query_var('s'):""));
		if ($encode == "encode") {
			$search_value = urlencode(urldecode(trim($search_value)));
		}else {
			$search_value = urldecode(trim($search_value));
		}
		$search_value = esc_js(esc_html($search_value));
		return apply_filters( 'wpqa_search', $search_value );
	}
endif;
/* Search terms */
if (!function_exists('wpqa_search_terms')) :
	function wpqa_search_terms( $passed_terms = '' ) {
		if ( !empty( $passed_terms ) ) {
			$search_terms = sanitize_title( $passed_terms );
		}else {
			$search_terms = esc_html(get_query_var(apply_filters('wpqa_search_id','search')));
		}
		$search_terms = esc_js(!empty( $search_terms ) ? urldecode( trim( $search_terms ) ) : false);
		return apply_filters( 'wpqa_search_terms', $search_terms, $passed_terms );
	}
endif;
/* Get search link content */
if (!function_exists('wpqa_search_link')) :
	function wpqa_search_link($query = "",$type = "",$page = "") {
		$query = ($query != ""?$query:wpqa_search("encode"));
		$type  = ($type != ""?$type:wpqa_search_type());
		$paged = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):""));
		$paged = ($page != ""?$page:$paged);
		$user_filter = ($type == "users" && isset($_GET["user_filter"]) && $_GET["user_filter"] != ""?esc_attr($_GET["user_filter"]):"");
		if (get_option('permalink_structure')) {
			$search_slug = wpqa_options("search_slug");
			$paged = ($paged != ""?"page=".esc_attr($paged):'');
			$url = home_url('/'.$search_slug.'/'.($query != ""?$query.'/':'').($query != "" && $type != ""?$type.'/':'').($user_filter != ''?'?user_filter='.$user_filter.($paged != ""?"&".$paged:""):($paged != ""?"?".$paged:"")));
		}else {
			$url = esc_url_raw(add_query_arg(array(apply_filters('wpqa_search_id','search') => $query,apply_filters('wpqa_type','search_type') => $type,'page' => esc_attr($paged),array('user_filter' => $user_filter)),home_url('/')));
		}
		return esc_url_raw(apply_filters( 'wpqa_filter_search_link', $url ));
	}
endif;
/* Search type */
if (!function_exists('wpqa_search_type')) :
	function wpqa_search_type() {
		$search_type = esc_attr(get_query_var(apply_filters('wpqa_type','search_type')));
		$search_type = !empty($search_type) && $search_type != "-1"?esc_attr($search_type):wpqa_options("default_search");
		return apply_filters( 'wpqa_filter_search_type', $search_type );
	}
endif;
/* Get search url */
function wpqa_get_search_permalink() {
	$search_slug = wpqa_options("search_slug");
	if (get_option('permalink_structure')) {
		$url = $search_slug;
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array( apply_filters('wpqa_search_id','search') => wpqa_search() ), home_url( '/' ) ));
	}
	return apply_filters( 'wpqa_get_search_url', $url );
}
/* Checkout var */
if (!function_exists('wpqa_checkout')) :
	function wpqa_checkout() {
		$checkout_value = (get_query_var(apply_filters('wpqa_checkout_id','checkout')) != ""?get_query_var(apply_filters('wpqa_checkout_id','checkout')):"");
		$checkout_value = urldecode(trim($checkout_value));
		$checkout_value = esc_js(esc_html($checkout_value));
		return apply_filters( 'wpqa_checkout', $checkout_value );
	}
endif;
/* Checkout term */
if (!function_exists('wpqa_checkout_term')) :
	function wpqa_checkout_term( $passed_term = '' ) {
		if ( !empty( $passed_term ) ) {
			$checkout_term = sanitize_title( $passed_term );
		}else {
			$checkout_term = esc_html(get_query_var(apply_filters('wpqa_checkout_id','checkout')));
		}
		$checkout_term = esc_js(!empty( $checkout_term ) ? urldecode( trim( $checkout_term ) ) : false);
		return apply_filters( 'wpqa_checkout_term', $checkout_term, $passed_term );
	}
endif;
/* Checkout item */
if (!function_exists('wpqa_checkout_item')) :
	function wpqa_checkout_item( $passed_item = '' ) {
		if ( !empty( $passed_item ) ) {
			$checkout_item = sanitize_title( $passed_item );
		}else {
			$checkout_item = esc_html(get_query_var(apply_filters('wpqa_checkout_id','checkout')));
		}
		$checkout_item = esc_js(!empty( $checkout_item ) ? urldecode( trim( $checkout_item ) ) : false);
		return apply_filters( 'wpqa_checkout_item', $checkout_item, $passed_item );
	}
endif;
/* Checkout related */
if (!function_exists('wpqa_checkout_related')) :
	function wpqa_checkout_related( $passed_item = '' ) {
		if ( !empty( $passed_item ) ) {
			$checkout_related = sanitize_title( $passed_item );
		}else {
			$checkout_related = esc_html(get_query_var(apply_filters('wpqa_checkout_related','checkout_related')));
		}
		$checkout_related = esc_js(!empty( $checkout_related ) ? urldecode( trim( $checkout_related ) ) : false);
		return apply_filters( 'wpqa_checkout_related', $checkout_related, $passed_item );
	}
endif;
/* Get checkout link content */
if (!function_exists('wpqa_checkout_link')) :
	function wpqa_checkout_link($query = "",$type = "",$related = "") {
		$type  = ($type != ""?$type:wpqa_checkout_get_item());
		$related  = ($related != ""?$related:wpqa_checkout_get_related());
		if (get_option('permalink_structure')) {
			$checkout_slug = wpqa_options("checkout_slug");
			$url = home_url('/'.$checkout_slug.'/'.($query != ""?$query.'/':'').($query != "" && $type != ""?$type.'/':'').($query != "" && $related != ""?$related.'/':''));
		}else {
			$url = esc_url_raw(add_query_arg(array(apply_filters('wpqa_checkout_id','checkout') => $query,apply_filters('wpqa_checkout_item','checkout_item') => $type,apply_filters('wpqa_checkout_related','checkout_related') => $related),home_url('/')));
		}
		return esc_url_raw(apply_filters( 'wpqa_filter_checkout_link', $url ));
	}
endif;
/* Checkout get item */
if (!function_exists('wpqa_checkout_get_item')) :
	function wpqa_checkout_get_item() {
		$checkout_item = esc_attr(get_query_var(apply_filters('wpqa_checkout_item','checkout_item')));
		$checkout_item = !empty($checkout_item)?esc_attr($checkout_item):"";
		return apply_filters( 'wpqa_filter_checkout_item', $checkout_item );
	}
endif;
/* Checkout get related */
if (!function_exists('wpqa_checkout_get_related')) :
	function wpqa_checkout_get_related() {
		$checkout_related = esc_attr(get_query_var(apply_filters('wpqa_checkout_related','checkout_related')));
		$checkout_related = !empty($checkout_related)?esc_attr($checkout_related):"";
		return apply_filters( 'wpqa_filter_checkout_related', $checkout_related );
	}
endif;
/* Get checkout url */
function wpqa_get_checkout_permalink() {
	$checkout_slug = wpqa_options("checkout_slug");
	if (get_option('permalink_structure')) {
		$url = $checkout_slug;
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array( apply_filters('wpqa_checkout_id','checkout') => wpqa_checkout() ), home_url( '/' ) ));
	}
	return apply_filters( 'wpqa_get_checkout_url', $url );
}
/* Get subscriptions url */
function wpqa_subscriptions_permalink() {
	$subscriptions_slug = wpqa_options("subscriptions_slug");
	if (get_option('permalink_structure')) {
		$url = $subscriptions_slug;
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array( apply_filters('wpqa_subscriptions','wpqa_subscriptions') => "" ), home_url( '/' ) ));
	}
	return apply_filters( 'wpqa_subscriptions_url', $url );
}
/* Get buy points url */
function wpqa_buy_points_permalink() {
	$buy_points_slug = wpqa_options("buy_points_slug");
	if (get_option('permalink_structure')) {
		$url = $buy_points_slug;
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array( apply_filters('wpqa_buy_points','wpqa_buy_points') => "" ), home_url( '/' ) ));
	}
	return apply_filters( 'wpqa_buy_points_url', $url );
}
/* Get login url */
function wpqa_login_permalink() {
	$login_slug = wpqa_options("login_slug");
	if (get_option('permalink_structure')) {
		$url = $login_slug;
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array( apply_filters('wpqa_login','wpqa_login') => "" ), home_url( '/' ) ));
	}
	return apply_filters( 'wpqa_login_url', $url );
}
/* Get signup url */
function wpqa_signup_permalink() {
	$signup_slug = wpqa_options("signup_slug");
	if (get_option('permalink_structure')) {
		$url = $signup_slug;
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array( apply_filters('wpqa_signup','wpqa_signup') => "" ), home_url( '/' ) ));
	}
	return apply_filters( 'wpqa_signup_url', $url );
}
/* Get lost password url */
function wpqa_lost_password_permalink() {
	$lost_password_slug = wpqa_options("lost_password_slug");
	if (get_option('permalink_structure')) {
		$url = $lost_password_slug;
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array( apply_filters('wpqa_lost_password','wpqa_lost_password') => "" ), home_url( '/' ) ));
	}
	return apply_filters( 'wpqa_lost_password_url', $url );
}
/* Get add post url */
function wpqa_add_post_permalink() {
	$add_posts_slug = wpqa_options("add_posts_slug");
	if (get_option('permalink_structure')) {
		$url = $add_posts_slug;
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array( apply_filters('wpqa_add_posts','wpqa_add_post') => ""), home_url( '/' ) ));
	}

	if (is_category()) {
		$term_id = (int)get_query_var('wpqa_term_id');
		$url = esc_url_raw(add_query_arg("category",$term_id,$url));
	}
	
	return apply_filters( 'wpqa_add_post_url', $url );
}
/* Get add category url */
function wpqa_add_category_permalink() {
	$add_category_slug = wpqa_options("add_category_slug");
	if (get_option('permalink_structure')) {
		$url = $add_category_slug;
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array( apply_filters('wpqa_add_categories','add_category') => "" ), home_url( '/' ) ));
	}
	return apply_filters( 'wpqa_add_category_url', $url );
}
/* Get ask question url */
function wpqa_add_question_permalink($user = '',$user_id = '') {
	$add_questions_slug = wpqa_options("add_questions_slug");
	$get_query_var      = '';
	if ((wpqa_is_user_profile() && $user == "user") || ($user == "user" && $user_id != "")) {
		$wpqa_user_id = (int)($user_id != ""?$user_id:get_query_var(apply_filters('wpqa_user_id','wpqa_user_id')));
		$profile_type = wpqa_options("profile_type");
		if ($profile_type == "login") {
			$user_name = esc_attr(urlencode(trim(wpqa_get_user_login($wpqa_user_id))));
		}else {
			$user_name = esc_attr(urlencode(trim(wpqa_get_user_nicename($wpqa_user_id))));
		}
		$get_query_var = ($user_name != ""?user_trailingslashit(esc_attr($user_name)):"");
	}
	
	if (get_option('permalink_structure')) {
		$url = $add_questions_slug;
		$url = rtrim(home_url(user_trailingslashit($url).$get_query_var),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg(array(apply_filters('wpqa_add_questions','add_question') => (isset($get_query_var) && $get_query_var != ""?$get_query_var:"")),home_url('/')));
	}

	if (is_tax("question-category")) {
		$term_id = (int)get_query_var('wpqa_term_id');
		$url = esc_url_raw(add_query_arg("category",$term_id,$url));
	}

	return apply_filters( 'wpqa_add_question_url', $url );
}
/* Get user at ask question page */
add_filter("wpqa_add_question_user","wpqa_add_question_user");
if (!function_exists('wpqa_add_question_user')) :
	function wpqa_add_question_user() {
		if (wpqa_is_user_profile()) {
			$get_user_name = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
		}else {
			$get_user_name = get_query_var(apply_filters('wpqa_add_questions','add_question'));
		}
		$user_login = get_user_by('login',urldecode($get_user_name));
		if (isset($user_login) && !is_object($user_login)) {
			$user_login = get_user_by('slug',urldecode($get_user_name));
		}
		if (isset($user_login) && !is_object($user_login)) {
			$user_login = get_user_by('id',(int)$get_user_name);
		}
		if (isset($user_login) && is_object($user_login)) {
			$get_user_id = $user_login->ID;
			return (int)$get_user_id;
		}
	}
endif;
/* Get add group url */
function wpqa_add_group_permalink() {
	$add_groups_slug = wpqa_options("add_groups_slug");
	if (get_option('permalink_structure')) {
		$url = $add_groups_slug;
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg(array(apply_filters('wpqa_add_groups','add_group') => ""),home_url('/')));
	}
	return apply_filters( 'wpqa_add_group_url', $url );
}
/* Get edit url question - group - post - comment */
function wpqa_edit_permalink( $type_id = 0,$type = 'question' ) {
	$edits_slug = wpqa_options('edit_'.$type.'s_slug');
	$early_url = apply_filters( 'wpqa_pre_get_'.$type.'_permalink', (int) $type_id );
	if ( is_string( $early_url ) )
		return $early_url;
	if (get_option('permalink_structure')) {
		$url = $edits_slug . '/%' . apply_filters('wpqa_edit_'.$type.'s','edit_'.$type) . '%';
		$url = str_replace( '%' . apply_filters('wpqa_edit_'.$type.'s','edit_'.$type) . '%', $type_id, $url );
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array(
			apply_filters('wpqa_edit_'.$type.'s','edit_'.$type) => $type_id,
		), home_url( '/' ) ));
	}
	return apply_filters( 'wpqa_get_'.$type.'_permalink', $url, $type_id );
}
/* Get custom url group */
function wpqa_custom_permalink( $type_id,$types,$type ) {
	$custom_links_slug = wpqa_options($types.'_slug');
	$early_url = apply_filters( 'wpqa_pre_get_'.$type.'_permalink', (int) $type_id );
	if ( is_string( $early_url ) )
		return $early_url;
	if (get_option('permalink_structure')) {
		$url = $custom_links_slug . '/%' . apply_filters('wpqa_'.$types,$type) . '%';
		$url = str_replace( '%' . apply_filters('wpqa_'.$types,$type) . '%', $type_id, $url );
		$url = rtrim(home_url(user_trailingslashit($url)),'/').'/';
	}else {
		$url = esc_url_raw(add_query_arg( array(
			apply_filters('wpqa_'.$types,$type) => $type_id,
		), home_url( '/' ) ));
	}
	return apply_filters( 'wpqa_get_'.$type.'_permalink', $url, $type_id );
}
/* Get logout url */
function wpqa_get_logout() {
	$after_logout = wpqa_options("after_logout");
	$after_logout_link = wpqa_options("after_logout_link");
	$protocol = is_ssl() ? 'https' : 'http';
	if ($after_logout == "same_page") {
		$redirect_to = $protocol.'://'.wpqa_server('HTTP_HOST').wpqa_server('REQUEST_URI');
	}else if ($after_logout == "custom_link" && $after_logout_link != "") {
		$redirect_to = esc_url_raw($after_logout_link);
	}else {
		$redirect_to = esc_url_raw(home_url('/'));
	}
	$url = esc_url_raw(wp_logout_url($redirect_to));
	return apply_filters('wpqa_filter_get_logout',$url);
}
/* Get profile content url */
function wpqa_get_profile_permalink( $user_id = 0,$type = 'questions' ) {
	$type_slug = explode("_",$type);
	$type_slug = $type_slug[0].(isset($type_slug[1])?'-'.$type_slug[1]:'');
	$type_slug = wpqa_options($type.'_slug');
	$user_id = wpqa_get_user_id( $user_id );
	if ( empty( $user_id ) )
		return false;
	$early_profile_url = apply_filters( 'wpqa_pre_get_'.$type.'_permalink', (int) $user_id );
	if ( is_string( $early_profile_url ) )
		return $early_profile_url;
	if (get_option('permalink_structure')) {
		$url = rtrim(user_trailingslashit(wpqa_profile_url($user_id).$type_slug),'/').'/';
	}else {
		$url = add_query_arg( array(
			apply_filters('wpqa_'.$type,$type) => '1',
		), wpqa_profile_url($user_id) );
	}
	return apply_filters( 'wpqa_get_'.$type.'_permalink', esc_url_raw($url), $user_id );
}
/* Filter profile url */
add_filter('author_link','wpqa_author_link',10,3);
if (!function_exists('wpqa_author_link')) :
	function wpqa_author_link($link,$user_id,$user_nicename) {
		$user_id = wpqa_get_user_id( $user_id );
		if ( empty( $user_id ) )
			return false;
		$early_profile_url = apply_filters( 'wpqa_pre_get_user_profile_url', (int) $user_id );
		if ( is_string( $early_profile_url ) )
			return $early_profile_url;
		if (get_option('permalink_structure')) {
			$user_slug = wpqa_options('profile_slug');
			$link = $user_slug.'/%'.apply_filters('wpqa_user_id','wpqa_user_id').'%';
			$profile_type = wpqa_options('profile_type');
			if ( empty( $user_nicename ) || $profile_type == 'nicename' ) {
				$user_nicename = esc_attr(urldecode(urlencode(trim(wpqa_get_user_nicename($user_id)))));
			}else {
				$user_nicename = esc_attr(urldecode(urlencode(trim(wpqa_get_user_login($user_id)))));
			}
			$user_nicename = str_ireplace("+","-",$user_nicename);
			$user_nicename = str_ireplace(" ","-",$user_nicename);
			$link = str_replace( '%' . apply_filters('wpqa_user_id','wpqa_user_id') . '%', $user_nicename, $link );
			$link = rtrim(home_url(user_trailingslashit($link)),'/').'/';
		}else {
			$link = esc_url_raw(add_query_arg( array( apply_filters('wpqa_user_id','wpqa_user_id') => $user_id ), home_url( '/' ) ));
		}
		$link = apply_filters( 'wpqa_profile_url', $link, $user_id, $user_nicename );
		return esc_url_raw($link);
	}
endif;
/* Get user profile url */
if (!function_exists('wpqa_profile_url')) :
	function wpqa_profile_url($user_id = 0,$user_nicename = '') {
		return get_author_posts_url($user_id,$user_nicename);
	}
endif;
/* Get user nicename */
if (!function_exists('wpqa_get_user_nicename')) :
	function wpqa_get_user_nicename ( $user_id = 0, $args = array() ) {
		$user_id = wpqa_get_user_id( $user_id );
		if ( empty( $user_id ) )
			return false;
		$r = wpqa_parse_args( $args, array(
			'user_id' => $user_id,
			'before'  => '',
			'after'   => '',
			'force'   => ''
			), 'get_user_nicename' );
		
		if ( empty( $r['force'] ) ) {
			$user     = get_userdata( $user_id );
			$nicename = (isset($user->user_nicename) && $user->user_nicename != ''?$user->user_nicename:(isset($user->user_login) && $user->user_login != ''?$user->user_login:''));
		}else {
			$nicename = (string) $r['force'];
		}
		$return = !empty( $nicename ) ? ( $r['before'] . $nicename . $r['after'] ) : '';
		return (string) apply_filters( 'wpqa_get_user_nicename', $return, $user_id, $r );
	}
endif;
/* Get user login */
if (!function_exists('wpqa_get_user_login')) :
	function wpqa_get_user_login ( $user_id = 0, $args = array() ) {
		$user_id = wpqa_get_user_id( $user_id );
		if ( empty( $user_id ) )
			return false;
		$r = wpqa_parse_args( $args, array(
			'user_id' => $user_id,
			'before'  => '',
			'after'   => '',
			'force'   => ''
			), 'get_user_login' );
		
		if ( empty( $r['force'] ) ) {
			$user  = get_userdata( $user_id );
			$login = $user->user_login;
		}else {
			$login = (string) $r['force'];
		}
		$return = !empty( $login ) ? ( $r['before'] . $login . $r['after'] ) : '';
		return (string) apply_filters( 'wpqa_get_user_login', $return, $user_id, $r );
	}
endif;
/* Body classes */
add_filter('body_class','wpqa_body_classes');
function wpqa_body_classes($classes) {
	if (wpqa_is_search()) {
		$classes[] = "wpqa-search";
		$classes[] = "wpqa-search-".wpqa_search_type();
	}else if (wpqa_is_checkout()) {
		$classes[] = "wpqa-checkout";
		$classes[] = "wpqa-checkout-".wpqa_checkout_get_item();
	}else if (wpqa_is_subscriptions()) {
		$classes[] = "wpqa-subscriptions";
	}else if (wpqa_is_buy_points()) {
		$classes[] = "wpqa-buy-points";
	}else if (wpqa_is_login()) {
		$classes[] = "wpqa-login";
	}else if (wpqa_is_signup()) {
		$classes[] = "wpqa-signup";
	}else if (wpqa_is_lost_password()) {
		$classes[] = "wpqa-lost-password";
	}else if (wpqa_is_add_category()) {
		$classes[] = "wpqa-add-category";
	}else if (wpqa_is_add_questions()) {
		$classes[] = "wpqa-add-question";
	}else if (wpqa_is_edit_questions()) {
		$classes[] = "wpqa-edit-question";
	}else if (wpqa_is_edit_tags()) {
		$classes[] = "wpqa-edit-tag";
	}else if (wpqa_is_add_groups()) {
		$classes[] = "wpqa-add-group";
	}else if (wpqa_is_edit_groups()) {
		$classes[] = "wpqa-edit-group";
	}else if (wpqa_is_group_requests()) {
		$classes[] = "wpqa-group-requests";
	}else if (wpqa_is_group_users()) {
		$classes[] = "wpqa-group-users";
	}else if (wpqa_is_group_admins()) {
		$classes[] = "wpqa-group-admins";
	}else if (wpqa_is_blocked_users()) {
		$classes[] = "wpqa-blocked-users";
	}else if (wpqa_is_posts_group()) {
		$classes[] = "wpqa-pending-post";
	}else if (wpqa_is_view_posts_group()) {
		$classes[] = "wpqa-view-post-group";
	}else if (wpqa_is_edit_posts_group()) {
		$classes[] = "wpqa-edit-post-group";
	}else if (wpqa_is_add_posts()) {
		$classes[] = "wpqa-add-post";
	}else if (wpqa_is_edit_posts()) {
		$classes[] = "wpqa-edit-post";
	}else if (wpqa_is_edit_comments()) {
		$classes[] = "wpqa-edit-comment";
	}else if (wpqa_is_user_profile()) {
		$classes[] = (wpqa_user_title() != ""?"wpqa-profile wpqa-".wpqa_user_title():"wpqa-profile");
	}
	return $classes;
}
/* Load the title */
add_filter('document_title_parts','wpqa_the_title',9);
function wpqa_the_title($title) {
	$wpqa_get_the_title = wpqa_get_the_title();
	if ($wpqa_get_the_title != "") {
		$title['title'] = $wpqa_get_the_title;
	}
	return $title;
}
/* Get the title */
function wpqa_get_the_title() {
	if (is_search() || wpqa_is_search()) {
		$search_value = wpqa_search();
		if ($search_value != "") {
			$out_data = esc_html__('Search results for ', 'wpqa') . '"' . $search_value . '"';
		}else {
			$out_data = esc_html__('Search', 'wpqa');
		}
		$title = $out_data;
	}else if (wpqa_is_checkout()) {
		$title = esc_html__('Checkout', 'wpqa');
	}else if (wpqa_is_subscriptions()) {
		$title = esc_html__('Subscriptions','wpqa');
	}else if (wpqa_is_buy_points()) {
		$title = esc_html__('Buy points','wpqa');
	}else if (wpqa_is_login()) {
		$title = esc_html__('Login','wpqa');
	}else if (wpqa_is_signup()) {
		$title = esc_html__('Signup','wpqa');
	}else if (wpqa_is_lost_password()) {
		$title = esc_html__('Lost password','wpqa');
	}else if (wpqa_is_add_category()) {
		$title = esc_html__('Add category','wpqa');
	}else if (wpqa_is_add_questions()) {
		$title = esc_html__('Ask question','wpqa');
	}else if (wpqa_is_edit_questions()) {
		$title = esc_html__('Edit question','wpqa');
	}else if (wpqa_is_edit_tags()) {
		$title = esc_html__('Edit tags','wpqa');
	}else if (wpqa_is_add_groups()) {
		$title = esc_html__('Add group','wpqa');
	}else if (wpqa_is_edit_groups()) {
		$title = esc_html__('Edit group','wpqa');
	}else if (wpqa_is_group_requests()) {
		$title = esc_html__('Group requests','wpqa');
	}else if (wpqa_is_group_users()) {
		$title = esc_html__('Group users','wpqa');
	}else if (wpqa_is_group_admins()) {
		$title = esc_html__('Group admins','wpqa');
	}else if (wpqa_is_blocked_users()) {
		$title = esc_html__('Blocked users','wpqa');
	}else if (wpqa_is_posts_group()) {
		$title = esc_html__('Group posts','wpqa');
	}else if (wpqa_is_view_posts_group()) {
		$title = esc_html__('View group post','wpqa');
	}else if (wpqa_is_edit_posts_group()) {
		$title = esc_html__('Edit group post','wpqa');
	}else if (wpqa_is_add_posts()) {
		$title = esc_html__('Add post','wpqa');
	}else if (wpqa_is_edit_posts()) {
		$title = esc_html__('Edit post','wpqa');
	}else if (wpqa_is_edit_comments()) {
		$title = esc_html__('Edit comment','wpqa');
	}else if (wpqa_is_user_profile()) {
		$wpqa_user_id = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
		$display_name = get_the_author_meta('display_name',$wpqa_user_id);
		if (wpqa_profile_title()) {
			$title = $display_name." - ".wpqa_profile_title();
		}else {
			$title = $display_name;
		}
	}
	return (isset($title)?$title:"");
}
/* Edit profile layout */
add_filter(wpqa_theme_name."_sidebars_where","wpqa_sidebars_dir_profile");
add_filter(wpqa_theme_name."_sidebars_dir","wpqa_sidebars_dir_profile");
function wpqa_sidebars_dir_profile($sidebar_dir) {
	if (wpqa_is_user_edit_home() && wpqa_is_user_owner()) {
		$sidebar_dir = "main_full main_center";
	}
	return $sidebar_dir;
}?>