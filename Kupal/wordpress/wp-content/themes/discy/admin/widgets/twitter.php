<?php
/* Twitter */
add_action( 'widgets_init', 'discy_latest_tweet_widget' );
function discy_latest_tweet_widget() {
	register_widget( 'Latest_Tweets' );
}
class Latest_Tweets extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'twitter-widget' );
		$control_ops = array( 'id_base' => 'twitter-widget' );
		parent::__construct( 'twitter-widget',discy_theme_name.' - Twitter', $widget_ops, $control_ops );
	}
	
	private function hyperlinks($text){
		$text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" target=\"_blank\">$1</a>", $text);
		$text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" target=\"_blank\">$1</a>", $text);
		$text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" target=\"_blank\">$1</a>", $text);
		$text = preg_replace('/([\.|\,|\:|\?|\?|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"https://twitter.com/#search?q=$2\" target=\"_blank\">#$2</a>$3 ", $text);
		return $text;
	}
	
	private function twitter_users($text){
		$text = preg_replace('/([\.|\,|\:|\?|\?|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"https://twitter.com/$2\" target=\"_blank\">@".'$2'."</a>$3 ", $text);
		return $text;
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title		  = apply_filters('widget_title', $instance['title'] );
		$no_of_tweets = (int)$instance['no_of_tweets'];
		$accounts	  = esc_attr($instance['accounts']);
		
		echo ($before_widget);
			if ($title) {
				echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
			}else {
				echo "<h3 class='screen-reader-text'>".esc_html__("Twitter","discy")."</h3>";
			}?>
			<div class="widget-wrap">
				<?php $tweets = get_transient('discy_twitter_widget_'.$args["widget_id"].$accounts);
				if ($tweets == false) {
					$tweets = discy_twitter_tweets($accounts,$no_of_tweets);
					set_transient('discy_twitter_widget_'.$args["widget_id"].$accounts, $tweets, HOUR_IN_SECONDS);
				}
				if (isset($tweets) && is_array($tweets)) {
					$i = 0;?>
					<ul>
						<?php foreach ( $tweets as $item ) {
							$tweet     = $item->text;
							$tweet     = make_clickable( $tweet );
							$tweet     = $this->twitter_users( $tweet );
							$permalink = 'https://twitter.com/#!/'. $accounts .'/status/'. $item->id_str;
							
							$time = strtotime( $item->created_at );
							$h_time = sprintf( esc_html__( 'about %s ago','discy' ), human_time_diff( $time ) );
							
							echo '<li class="tweet-item">
								<a target="_blank" href="'.esc_url($permalink).'" class="tweet-icon"><i class="icon-twitter"></i></a>
								<div class="tweet-text">
									<a target="_blank" class="tweet-name" href="'.esc_url($permalink).'">'.$accounts.'</a>
									'.$tweet.'
									<br>
									<span class="tweet-time">'.$h_time.'</span>
								</div>
							</li>';
							$i++;
							if ( $i >= $no_of_tweets ) {
								break;
							}
						}?>
					</ul>
				<?php }?>
				<div class="clearfix"></div>
			</div>
		<?php echo ($after_widget);
	}
	
	public function form( $instance ) {
		/* Save Button */
	}
}?>