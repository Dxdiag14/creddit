<?php

/* @author    2codeThemes
*  @package   WPQA/payments
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Packages payments */
function wpqa_packages_payment($user_id,$item_id,$payment_type,$asked_user = 0) {
	$output = '';
	$payment_type = wpqa_options($payment_type);
	$packages_payment = wpqa_options($item_id);
	$currency_code = wpqa_get_currency($user_id);
	$currency = (wpqa_options("activate_currencies") == "on"?"_".strtolower($currency_code):"");
	if (isset($packages_payment) && is_array($packages_payment)) {
		$output .= '<div class="points-section buy-points-section buy-packages-section">
			<ul class="row">';
				foreach ($packages_payment as $key => $value) {
					if (isset($value["package_posts"]) && $value["package_posts"] > 0) {
						if (isset($value["package_points"]) && $value["package_points"] > 0) {
							$price_points = sprintf(_n("%s Point","%s Points",$value["package_points"],"wpqa"),wpqa_count_number($value["package_points"]));
						}
						if ($payment_type == "payments" || $payment_type == "payments_points") {
							$price = (isset($value["package_price".$currency])?$value["package_price".$currency]:(isset($value["package_price"])?$value["package_price"]:""));
							$price = floatval($price).' '.$currency_code;
						}else if ($payment_type == "points" && isset($price_points) && isset($value["package_points"]) && $value["package_points"] > 0) {
							$price = $price_points;
						}
						$output .= '<li class="col col12">
							<div class="point-section">
								<div class="point-div">';
									if (isset($price) && $price != "") {
										$show_payment = true;
										$output .= '<span>'.$price.'</span>';
									}
									$output .= esc_html($value["package_name"]);
									if ($payment_type == "payments_points" && isset($value["package_points"]) && $value["package_points"] > 0) {
										$show_payment = true;
										$output .= '<span class="points-price">'.$price_points.'</span>';
									}
								$output .= '</div>
								<p>'.wpqa_kses_stip($value["package_description"]).'</p>
								<div class="buy-points-content">';
									if (is_user_logged_in()) {
										if (isset($show_payment)) {
											$output .= '<a href="'.wpqa_checkout_link(($item_id == "ask_packages"?"buy_questions":"buy_posts"),(int)$value["package_posts"],(isset($asked_user) && $asked_user > 0?$asked_user:"")).'" target="_blank" class="button-default">'.($item_id == "ask_packages"?esc_html__("Buy questions","wpqa"):esc_html__("Buy posts","wpqa")).'</a>
											<div class="clearfix"></div>';
										}
									}else {
										$output .= '<a href="#" class="button-default login-panel">'.esc_html__('Sign In','wpqa').'</a>';
									}
								$output .= '</div>
							</div>
						</li>';
					}
				}
			$output .= '</ul>
		</div><!-- End buy-points-section -->';
	}
	return apply_filters("wpqa_packages_payment",$output,$user_id,$item_id,$payment_type,$asked_user);
}?>