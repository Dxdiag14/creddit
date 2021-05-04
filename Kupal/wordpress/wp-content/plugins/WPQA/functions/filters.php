<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Filter get comment time */
function wpqa_get_comment_time($d = '', $gmt = false, $translate = true, $comment = 0) {
	$comment_date = $gmt ? $comment->comment_date_gmt : $comment->comment_date;
	if ( '' == $d ) {
		$time_format = wpqa_options("time_format");
		$time_format = ($time_format?$time_format:get_option("time_format"));
		$date = mysql2date($time_format, $comment_date, $translate);
	}else {
		$date = mysql2date($d, $comment_date, $translate);
	}
	return $date;
}
/* Filter server */
add_filter("wpqa_server","wpqa_server");
if (!function_exists('wpqa_server')) :
	function wpqa_server($type) {
		if (isset($type) && isset($_SERVER[$type])) {
			return $_SERVER[$type];
		}
	}
endif;
/* Filter get search content */
add_filter('wpqa_get_search_filter','wpqa_get_search_filter');
if (!function_exists('wpqa_get_search_filter')) :
	function wpqa_get_search_filter() {
		return wpqa_search_terms();
	}
endif;
/* Custom widget search */
function wpqa_custom_widget_search($form) {
	$live_search  = wpqa_options("live_search");
	$search_attrs = wpqa_options("search_attrs");
	$search_type  = wpqa_search_type();
	if (isset($search_attrs) && is_array($search_attrs) && !empty($search_attrs)) {
		$k_count = 0;
		foreach ($search_attrs as $key => $value) {
			if (isset($value["value"]) && $value["value"] != "" && $value["value"] != "0") {
				$k_count++;
				$count_search_attrs = $k_count;
			}
		}
	}
	$form = '<form role="search" method="get" class="search-form main-search-form" action="'.esc_url(wpqa_get_search_permalink()).'">
		<label>
			<input type="search" class="search-field'.($live_search == "on"?" live-search":"").'"'.($live_search == "on"?" autocomplete='off'":"").' placeholder="'.esc_attr__("Search ...","wpqa").'" value="'.wpqa_search_terms().'" name="search">';
			if ($live_search == "on") {
				$form .= '<div class="loader_2 search_loader"></div>
				<div class="search-results results-empty"></div>';
			}
			$form .= '<select name="search_type" class="search_type">';
				if (isset($count_search_attrs) && $count_search_attrs > 1) {
					$form .= '<option value="-1">'.esc_html__("Select kind of search","wpqa").'</option>';
				}
				if (isset($search_attrs) && is_array($search_attrs) && !empty($search_attrs)) {
					foreach ($search_attrs as $key => $value) {
						$form = apply_filters("wpqa_filter_search_attrs_options",$form,$search_attrs,$key,$value,$search_type);
						if ($key == "questions" && isset($search_attrs["questions"]["value"]) && $search_attrs["questions"]["value"] == "questions") {
							$form .= '<option '.selected((isset($search_type) && $search_type != ""?$search_type:""),"questions",false).' value="questions">'.esc_html__("Questions","wpqa").'</option>';
						}else if ($key == "answers" && isset($search_attrs["answers"]["value"]) && $search_attrs["answers"]["value"] == "answers") {
							$form .= '<option '.selected((isset($search_type) && $search_type != ""?$search_type:""),"answers",false).' value="answers">'.esc_html__("Answers","wpqa").'</option>';
						}else if ($key == "question-category" && isset($search_attrs["question-category"]["value"]) && $search_attrs["question-category"]["value"] == "question-category") {
							$form .= '<option '.selected((isset($search_type) && $search_type != ""?$search_type:""),"question-category",false).' value="question-category">'.esc_html__("Question categories","wpqa").'</option>';
						}else if ($key == "question_tags" && isset($search_attrs["question_tags"]["value"]) && $search_attrs["question_tags"]["value"] == "question_tags") {
							$form .= '<option '.selected((isset($search_type) && $search_type != ""?$search_type:""),"question_tags",false).' value="question_tags">'.esc_html__("Question tags","wpqa").'</option>';
						}else if ($key == "posts" && isset($search_attrs["posts"]["value"]) && $search_attrs["posts"]["value"] == "posts") {
							$form .= '<option '.selected((isset($search_type) && $search_type != ""?$search_type:""),"posts",false).' value="posts">'.esc_html__("Posts","wpqa").'</option>';
						}else if ($key == "comments" && isset($search_attrs["comments"]["value"]) && $search_attrs["comments"]["value"] == "comments") {
							$form .= '<option '.selected((isset($search_type) && $search_type != ""?$search_type:""),"comments",false).' value="comments">'.esc_html__("Comments","wpqa").'</option>';
						}else if ($key == "category" && isset($search_attrs["category"]["value"]) && $search_attrs["category"]["value"] == "category") {
							$form .= '<option '.selected((isset($search_type) && $search_type != ""?$search_type:""),"category",false).' value="category">'.esc_html__("Post categories","wpqa").'</option>';
						}else if ($key == "post_tag" && isset($search_attrs["post_tag"]["value"]) && $search_attrs["post_tag"]["value"] == "post_tag") {
							$form .= '<option '.selected((isset($search_type) && $search_type != ""?$search_type:""),"post_tag",false).' value="post_tag">'.esc_html__("Post tags","wpqa").'</option>';
						}else if ($key == "users" && isset($search_attrs["users"]["value"]) && $search_attrs["users"]["value"] == "users") {
							$form .= '<option '.selected((isset($search_type) && $search_type != ""?$search_type:""),"users",false).' value="users">'.esc_html__("Users","wpqa").'</option>';
						}
					}
				}
			$form .= '</select>
		</label>
		<input type="submit" class="search-submit" value="'.esc_attr__("Search","wpqa").'">
	</form>';
	return $form;
}
add_filter('get_search_form','wpqa_custom_widget_search',100);
/* Get countries */
add_filter('wpqa_get_countries','wpqa_get_countries');
if (!function_exists('wpqa_get_countries')) :
	function wpqa_get_countries() {
		$countries = array(
			'AF' => esc_html__( 'Afghanistan', 'wpqa' ),
			'AX' => esc_html__( '&#197;land Islands', 'wpqa' ),
			'AL' => esc_html__( 'Albania', 'wpqa' ),
			'DZ' => esc_html__( 'Algeria', 'wpqa' ),
			'AD' => esc_html__( 'Andorra', 'wpqa' ),
			'AO' => esc_html__( 'Angola', 'wpqa' ),
			'AI' => esc_html__( 'Anguilla', 'wpqa' ),
			'AQ' => esc_html__( 'Antarctica', 'wpqa' ),
			'AG' => esc_html__( 'Antigua and Barbuda', 'wpqa' ),
			'AR' => esc_html__( 'Argentina', 'wpqa' ),
			'AM' => esc_html__( 'Armenia', 'wpqa' ),
			'AW' => esc_html__( 'Aruba', 'wpqa' ),
			'AU' => esc_html__( 'Australia', 'wpqa' ),
			'AT' => esc_html__( 'Austria', 'wpqa' ),
			'AZ' => esc_html__( 'Azerbaijan', 'wpqa' ),
			'BS' => esc_html__( 'Bahamas', 'wpqa' ),
			'BH' => esc_html__( 'Bahrain', 'wpqa' ),
			'BD' => esc_html__( 'Bangladesh', 'wpqa' ),
			'BB' => esc_html__( 'Barbados', 'wpqa' ),
			'BY' => esc_html__( 'Belarus', 'wpqa' ),
			'BE' => esc_html__( 'Belgium', 'wpqa' ),
			'PW' => esc_html__( 'Belau', 'wpqa' ),
			'BZ' => esc_html__( 'Belize', 'wpqa' ),
			'BJ' => esc_html__( 'Benin', 'wpqa' ),
			'BM' => esc_html__( 'Bermuda', 'wpqa' ),
			'BT' => esc_html__( 'Bhutan', 'wpqa' ),
			'BO' => esc_html__( 'Bolivia', 'wpqa' ),
			'BQ' => esc_html__( 'Bonaire, Saint Eustatius and Saba', 'wpqa' ),
			'BA' => esc_html__( 'Bosnia and Herzegovina', 'wpqa' ),
			'BW' => esc_html__( 'Botswana', 'wpqa' ),
			'BV' => esc_html__( 'Bouvet Island', 'wpqa' ),
			'BR' => esc_html__( 'Brazil', 'wpqa' ),
			'IO' => esc_html__( 'British Indian Ocean Territory', 'wpqa' ),
			'VG' => esc_html__( 'British Virgin Islands', 'wpqa' ),
			'BN' => esc_html__( 'Brunei', 'wpqa' ),
			'BG' => esc_html__( 'Bulgaria', 'wpqa' ),
			'BF' => esc_html__( 'Burkina Faso', 'wpqa' ),
			'BI' => esc_html__( 'Burundi', 'wpqa' ),
			'KH' => esc_html__( 'Cambodia', 'wpqa' ),
			'CM' => esc_html__( 'Cameroon', 'wpqa' ),
			'CA' => esc_html__( 'Canada', 'wpqa' ),
			'CV' => esc_html__( 'Cape Verde', 'wpqa' ),
			'KY' => esc_html__( 'Cayman Islands', 'wpqa' ),
			'CF' => esc_html__( 'Central African Republic', 'wpqa' ),
			'TD' => esc_html__( 'Chad', 'wpqa' ),
			'CL' => esc_html__( 'Chile', 'wpqa' ),
			'CN' => esc_html__( 'China', 'wpqa' ),
			'CX' => esc_html__( 'Christmas Island', 'wpqa' ),
			'CC' => esc_html__( 'Cocos (Keeling) Islands', 'wpqa' ),
			'CO' => esc_html__( 'Colombia', 'wpqa' ),
			'KM' => esc_html__( 'Comoros', 'wpqa' ),
			'CG' => esc_html__( 'Congo (Brazzaville)', 'wpqa' ),
			'CD' => esc_html__( 'Congo (Kinshasa)', 'wpqa' ),
			'CK' => esc_html__( 'Cook Islands', 'wpqa' ),
			'CR' => esc_html__( 'Costa Rica', 'wpqa' ),
			'HR' => esc_html__( 'Croatia', 'wpqa' ),
			'CU' => esc_html__( 'Cuba', 'wpqa' ),
			'CW' => esc_html__( 'Cura&Ccedil;ao', 'wpqa' ),
			'CY' => esc_html__( 'Cyprus', 'wpqa' ),
			'CZ' => esc_html__( 'Czech Republic', 'wpqa' ),
			'DK' => esc_html__( 'Denmark', 'wpqa' ),
			'DJ' => esc_html__( 'Djibouti', 'wpqa' ),
			'DM' => esc_html__( 'Dominica', 'wpqa' ),
			'DO' => esc_html__( 'Dominican Republic', 'wpqa' ),
			'EC' => esc_html__( 'Ecuador', 'wpqa' ),
			'EG' => esc_html__( 'Egypt', 'wpqa' ),
			'SV' => esc_html__( 'El Salvador', 'wpqa' ),
			'GQ' => esc_html__( 'Equatorial Guinea', 'wpqa' ),
			'ER' => esc_html__( 'Eritrea', 'wpqa' ),
			'EE' => esc_html__( 'Estonia', 'wpqa' ),
			'ET' => esc_html__( 'Ethiopia', 'wpqa' ),
			'FK' => esc_html__( 'Falkland Islands', 'wpqa' ),
			'FO' => esc_html__( 'Faroe Islands', 'wpqa' ),
			'FJ' => esc_html__( 'Fiji', 'wpqa' ),
			'FI' => esc_html__( 'Finland', 'wpqa' ),
			'FR' => esc_html__( 'France', 'wpqa' ),
			'GF' => esc_html__( 'French Guiana', 'wpqa' ),
			'PF' => esc_html__( 'French Polynesia', 'wpqa' ),
			'TF' => esc_html__( 'French Southern Territories', 'wpqa' ),
			'GA' => esc_html__( 'Gabon', 'wpqa' ),
			'GM' => esc_html__( 'Gambia', 'wpqa' ),
			'GE' => esc_html__( 'Georgia', 'wpqa' ),
			'DE' => esc_html__( 'Germany', 'wpqa' ),
			'GH' => esc_html__( 'Ghana', 'wpqa' ),
			'GI' => esc_html__( 'Gibraltar', 'wpqa' ),
			'GR' => esc_html__( 'Greece', 'wpqa' ),
			'GL' => esc_html__( 'Greenland', 'wpqa' ),
			'GD' => esc_html__( 'Grenada', 'wpqa' ),
			'GP' => esc_html__( 'Guadeloupe', 'wpqa' ),
			'GT' => esc_html__( 'Guatemala', 'wpqa' ),
			'GG' => esc_html__( 'Guernsey', 'wpqa' ),
			'GN' => esc_html__( 'Guinea', 'wpqa' ),
			'GW' => esc_html__( 'Guinea-Bissau', 'wpqa' ),
			'GY' => esc_html__( 'Guyana', 'wpqa' ),
			'HT' => esc_html__( 'Haiti', 'wpqa' ),
			'HM' => esc_html__( 'Heard Island and McDonald Islands', 'wpqa' ),
			'HN' => esc_html__( 'Honduras', 'wpqa' ),
			'HK' => esc_html__( 'Hong Kong', 'wpqa' ),
			'HU' => esc_html__( 'Hungary', 'wpqa' ),
			'IS' => esc_html__( 'Iceland', 'wpqa' ),
			'IN' => esc_html__( 'India', 'wpqa' ),
			'ID' => esc_html__( 'Indonesia', 'wpqa' ),
			'IR' => esc_html__( 'Iran', 'wpqa' ),
			'IQ' => esc_html__( 'Iraq', 'wpqa' ),
			'IE' => esc_html__( 'Republic of Ireland', 'wpqa' ),
			'IM' => esc_html__( 'Isle of Man', 'wpqa' ),
			'IL' => esc_html__( 'Israel', 'wpqa' ),
			'IT' => esc_html__( 'Italy', 'wpqa' ),
			'CI' => esc_html__( 'Ivory Coast', 'wpqa' ),
			'JM' => esc_html__( 'Jamaica', 'wpqa' ),
			'JP' => esc_html__( 'Japan', 'wpqa' ),
			'JE' => esc_html__( 'Jersey', 'wpqa' ),
			'JO' => esc_html__( 'Jordan', 'wpqa' ),
			'KZ' => esc_html__( 'Kazakhstan', 'wpqa' ),
			'KE' => esc_html__( 'Kenya', 'wpqa' ),
			'KI' => esc_html__( 'Kiribati', 'wpqa' ),
			'KW' => esc_html__( 'Kuwait', 'wpqa' ),
			'KG' => esc_html__( 'Kyrgyzstan', 'wpqa' ),
			'LA' => esc_html__( 'Laos', 'wpqa' ),
			'LV' => esc_html__( 'Latvia', 'wpqa' ),
			'LB' => esc_html__( 'Lebanon', 'wpqa' ),
			'LS' => esc_html__( 'Lesotho', 'wpqa' ),
			'LR' => esc_html__( 'Liberia', 'wpqa' ),
			'LY' => esc_html__( 'Libya', 'wpqa' ),
			'LI' => esc_html__( 'Liechtenstein', 'wpqa' ),
			'LT' => esc_html__( 'Lithuania', 'wpqa' ),
			'LU' => esc_html__( 'Luxembourg', 'wpqa' ),
			'MO' => esc_html__( 'Macao S.A.R., China', 'wpqa' ),
			'MK' => esc_html__( 'Macedonia', 'wpqa' ),
			'MG' => esc_html__( 'Madagascar', 'wpqa' ),
			'MW' => esc_html__( 'Malawi', 'wpqa' ),
			'MY' => esc_html__( 'Malaysia', 'wpqa' ),
			'MV' => esc_html__( 'Maldives', 'wpqa' ),
			'ML' => esc_html__( 'Mali', 'wpqa' ),
			'MT' => esc_html__( 'Malta', 'wpqa' ),
			'MH' => esc_html__( 'Marshall Islands', 'wpqa' ),
			'MQ' => esc_html__( 'Martinique', 'wpqa' ),
			'MR' => esc_html__( 'Mauritania', 'wpqa' ),
			'MU' => esc_html__( 'Mauritius', 'wpqa' ),
			'YT' => esc_html__( 'Mayotte', 'wpqa' ),
			'MX' => esc_html__( 'Mexico', 'wpqa' ),
			'FM' => esc_html__( 'Micronesia', 'wpqa' ),
			'MD' => esc_html__( 'Moldova', 'wpqa' ),
			'MC' => esc_html__( 'Monaco', 'wpqa' ),
			'MN' => esc_html__( 'Mongolia', 'wpqa' ),
			'ME' => esc_html__( 'Montenegro', 'wpqa' ),
			'MS' => esc_html__( 'Montserrat', 'wpqa' ),
			'MA' => esc_html__( 'Morocco', 'wpqa' ),
			'MZ' => esc_html__( 'Mozambique', 'wpqa' ),
			'MM' => esc_html__( 'Myanmar', 'wpqa' ),
			'NA' => esc_html__( 'Namibia', 'wpqa' ),
			'NR' => esc_html__( 'Nauru', 'wpqa' ),
			'NP' => esc_html__( 'Nepal', 'wpqa' ),
			'NL' => esc_html__( 'Netherlands', 'wpqa' ),
			'AN' => esc_html__( 'Netherlands Antilles', 'wpqa' ),
			'NC' => esc_html__( 'New Caledonia', 'wpqa' ),
			'NZ' => esc_html__( 'New Zealand', 'wpqa' ),
			'NI' => esc_html__( 'Nicaragua', 'wpqa' ),
			'NE' => esc_html__( 'Niger', 'wpqa' ),
			'NG' => esc_html__( 'Nigeria', 'wpqa' ),
			'NU' => esc_html__( 'Niue', 'wpqa' ),
			'NF' => esc_html__( 'Norfolk Island', 'wpqa' ),
			'KP' => esc_html__( 'North Korea', 'wpqa' ),
			'NO' => esc_html__( 'Norway', 'wpqa' ),
			'OM' => esc_html__( 'Oman', 'wpqa' ),
			'PK' => esc_html__( 'Pakistan', 'wpqa' ),
			'PS' => esc_html__( 'Palestinian Territory', 'wpqa' ),
			'PA' => esc_html__( 'Panama', 'wpqa' ),
			'PG' => esc_html__( 'Papua New Guinea', 'wpqa' ),
			'PY' => esc_html__( 'Paraguay', 'wpqa' ),
			'PE' => esc_html__( 'Peru', 'wpqa' ),
			'PH' => esc_html__( 'Philippines', 'wpqa' ),
			'PN' => esc_html__( 'Pitcairn', 'wpqa' ),
			'PL' => esc_html__( 'Poland', 'wpqa' ),
			'PT' => esc_html__( 'Portugal', 'wpqa' ),
			'QA' => esc_html__( 'Qatar', 'wpqa' ),
			'RE' => esc_html__( 'Reunion', 'wpqa' ),
			'RO' => esc_html__( 'Romania', 'wpqa' ),
			'RU' => esc_html__( 'Russia', 'wpqa' ),
			'RW' => esc_html__( 'Rwanda', 'wpqa' ),
			'BL' => esc_html__( 'Saint Barth&eacute;lemy', 'wpqa' ),
			'SH' => esc_html__( 'Saint Helena', 'wpqa' ),
			'KN' => esc_html__( 'Saint Kitts and Nevis', 'wpqa' ),
			'LC' => esc_html__( 'Saint Lucia', 'wpqa' ),
			'MF' => esc_html__( 'Saint Martin (French part)', 'wpqa' ),
			'SX' => esc_html__( 'Saint Martin (Dutch part)', 'wpqa' ),
			'PM' => esc_html__( 'Saint Pierre and Miquelon', 'wpqa' ),
			'VC' => esc_html__( 'Saint Vincent and the Grenadines', 'wpqa' ),
			'SM' => esc_html__( 'San Marino', 'wpqa' ),
			'ST' => esc_html__( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'wpqa' ),
			'SA' => esc_html__( 'Saudi Arabia', 'wpqa' ),
			'SN' => esc_html__( 'Senegal', 'wpqa' ),
			'RS' => esc_html__( 'Serbia', 'wpqa' ),
			'SC' => esc_html__( 'Seychelles', 'wpqa' ),
			'SL' => esc_html__( 'Sierra Leone', 'wpqa' ),
			'SG' => esc_html__( 'Singapore', 'wpqa' ),
			'SK' => esc_html__( 'Slovakia', 'wpqa' ),
			'SI' => esc_html__( 'Slovenia', 'wpqa' ),
			'SB' => esc_html__( 'Solomon Islands', 'wpqa' ),
			'SO' => esc_html__( 'Somalia', 'wpqa' ),
			'ZA' => esc_html__( 'South Africa', 'wpqa' ),
			'GS' => esc_html__( 'South Georgia/Sandwich Islands', 'wpqa' ),
			'KR' => esc_html__( 'South Korea', 'wpqa' ),
			'SS' => esc_html__( 'South Sudan', 'wpqa' ),
			'ES' => esc_html__( 'Spain', 'wpqa' ),
			'LK' => esc_html__( 'Sri Lanka', 'wpqa' ),
			'SD' => esc_html__( 'Sudan', 'wpqa' ),
			'SR' => esc_html__( 'Suriname', 'wpqa' ),
			'SJ' => esc_html__( 'Svalbard and Jan Mayen', 'wpqa' ),
			'SZ' => esc_html__( 'Swaziland', 'wpqa' ),
			'SE' => esc_html__( 'Sweden', 'wpqa' ),
			'CH' => esc_html__( 'Switzerland', 'wpqa' ),
			'SY' => esc_html__( 'Syria', 'wpqa' ),
			'TW' => esc_html__( 'Taiwan', 'wpqa' ),
			'TJ' => esc_html__( 'Tajikistan', 'wpqa' ),
			'TZ' => esc_html__( 'Tanzania', 'wpqa' ),
			'TH' => esc_html__( 'Thailand', 'wpqa' ),
			'TL' => esc_html__( 'Timor-Leste', 'wpqa' ),
			'TG' => esc_html__( 'Togo', 'wpqa' ),
			'TK' => esc_html__( 'Tokelau', 'wpqa' ),
			'TO' => esc_html__( 'Tonga', 'wpqa' ),
			'TT' => esc_html__( 'Trinidad and Tobago', 'wpqa' ),
			'TN' => esc_html__( 'Tunisia', 'wpqa' ),
			'TR' => esc_html__( 'Turkey', 'wpqa' ),
			'TM' => esc_html__( 'Turkmenistan', 'wpqa' ),
			'TC' => esc_html__( 'Turks and Caicos Islands', 'wpqa' ),
			'TV' => esc_html__( 'Tuvalu', 'wpqa' ),
			'UG' => esc_html__( 'Uganda', 'wpqa' ),
			'UA' => esc_html__( 'Ukraine', 'wpqa' ),
			'AE' => esc_html__( 'United Arab Emirates', 'wpqa' ),
			'GB' => esc_html__( 'United Kingdom (UK)', 'wpqa' ),
			'US' => esc_html__( 'United States (US)', 'wpqa' ),
			'UY' => esc_html__( 'Uruguay', 'wpqa' ),
			'UZ' => esc_html__( 'Uzbekistan', 'wpqa' ),
			'VU' => esc_html__( 'Vanuatu', 'wpqa' ),
			'VA' => esc_html__( 'Vatican', 'wpqa' ),
			'VE' => esc_html__( 'Venezuela', 'wpqa' ),
			'VN' => esc_html__( 'Vietnam', 'wpqa' ),
			'WF' => esc_html__( 'Wallis and Futuna', 'wpqa' ),
			'EH' => esc_html__( 'Western Sahara', 'wpqa' ),
			'WS' => esc_html__( 'Western Samoa', 'wpqa' ),
			'YE' => esc_html__( 'Yemen', 'wpqa' ),
			'ZM' => esc_html__( 'Zambia', 'wpqa' ),
			'ZW' => esc_html__( 'Zimbabwe', 'wpqa' )
		);
		asort($countries);
		return $countries;
	}
endif;
/* Get comment reply link */
add_filter("comment_reply_link","wpqa_comment_reply_link",1,3);
if (!function_exists('wpqa_comment_reply_link')) :
	function wpqa_comment_reply_link($link,$args,$comment) {
		$group_id = (int)get_post_meta($comment->comment_post_ID,"group_id",true);
		if ($group_id > 0) {
			$link = '<li><a rel="nofollow" class="comment-reply-link wpqa-reply-link" href="'.wpqa_custom_permalink($comment->comment_post_ID,"view_posts_group","view_group_post").'" data-id="'.$comment->comment_ID.'" data-post_id="'.$comment->comment_post_ID.'" aria-label="'.esc_attr(sprintf($args['reply_to_text'], $comment->comment_author)).'"><i class="icon-reply"></i>'.esc_html__("Reply","wpqa").'</a></li>';
		}else {
			$link = '<li><a rel="nofollow" class="comment-reply-link '.(get_option('comment_registration') && !is_user_logged_in()?'login-panel':'wpqa-reply-link').'" href="'.get_the_permalink($comment->comment_post_ID).'#respond" data-id="'.$comment->comment_ID.'" data-post_id="'.$comment->comment_post_ID.'" aria-label="'.esc_attr(sprintf($args['reply_to_text'], $comment->comment_author)).'"><i class="icon-reply"></i>'.esc_html__("Reply","wpqa").'</a></li>';
		}
		return $link;
	}
endif;?>