<?php if ((isset($blog_h) && $blog_h == "blog_h" && !is_array($sort_meta_title_image)) || !is_array($sort_meta_title_image) || (empty($blog_h) && is_single()) || $post_style != "style_3") {
	$sort_meta_title_image = array(array("value" => "meta_title",'name' => esc_html__('Meta and title','discy'),"default" => "yes"),array("value" => "image",'name' => esc_html__('Image','discy'),"default" => "yes"));
}

$user_id                = get_current_user_id();
$is_super_admin         = is_super_admin($user_id);
$active_moderators      = discy_options("active_moderators");
$pending_posts          = (has_wpqa() && wpqa_is_pending_posts() && ($is_super_admin || $active_moderators == "on") && wpqa_is_user_owner() && ($is_super_admin || (isset($moderator_categories) && is_array($moderator_categories) && !empty($moderator_categories)))?true:false);
$pending_posts_page     = (has_wpqa() && wpqa_is_pending_posts()?true:false);
$moderators_permissions = (has_wpqa() && $active_moderators == "on"?wpqa_user_moderator($user_id):"");
$post_link_target       = apply_filters("discy_post_link_target","");

$questions_position = discy_options("between_questions_position");
$adv_type_repeat = discy_options("between_adv_type_repeat");
if (!isset($blog_h) && isset($k_ad_p) && (($k_ad_p == $questions_position) || ($adv_type_repeat == "on" && $k_ad_p != 0 && $k_ad_p % $questions_position == 0))) {
	echo discy_ads("between_adv_type","between_adv_code","between_adv_href","between_adv_img","","","discy-ad-inside".($post_style == "style_3"?" adv-style-3".$post_columns:""),"on");
}

if (empty($post) && isset($GLOBALS['post'])) {
	$post_data = $post = $GLOBALS['post'];
}else {
	$post_data = $post;
}
$count_post_all = (int)(has_wpqa()?wpqa_count_comments($post_data->ID):get_comments_number());
$what_post = discy_post_meta("what_post","",false);
$discy_thumbnail_id = discy_post_meta("_thumbnail_id","",false);

$show_featured_image  = "";
if (has_post_thumbnail()) {
	$show_featured_image = 1;
	if ($featured_image == "on" && empty($blog_h) && isset($wp_page_template) && ($wp_page_template == "template-blog.php" || $wp_page_template == "template-home.php")) {
		$show_featured_image = 0;
	}else if ($featured_image == "on" && is_singular()) {
		$show_featured_image = 0;
	}else if ($featured_image == "on" && is_category()) {
		$show_featured_image = 0;
	}else if ($featured_image == "on") {
		$show_featured_image = 0;
	}
}else {
	$show_featured_image = 1;
	$discy_image = discy_image();
	if (!empty($discy_image) && $featured_image == "on") {
		$show_featured_image = 0;
	}
}

if ((is_single() && empty($blog_h)) || (is_page() && empty($blog_h) && (empty($wp_page_template) || ($wp_page_template != "template-blog.php" && $wp_page_template != "template-home.php")))) {
	$post_style = "";
}

$featured_style = "";
if (((is_single() && empty($blog_h)) || (is_page() && empty($blog_h) && (empty($wp_page_template) || ($wp_page_template != "template-blog.php" && $wp_page_template != "template-home.php")))) && isset($featured_image_style) && $featured_image_style != "" && $featured_image_style != "default") {
	$featured_style = " featured_style_2".(isset($featured_image_width) && $featured_image_style == "custom_size" && $featured_image_width > 350?" featured_style_350":"");
}

if (isset($blog_h) && $blog_h == "blog_h") {?>
	<div id="post-<?php the_ID(); ?>" <?php post_class('article-post article-post-only clearfix'.$featured_style.($post_data->post_content == " post-no-content"?" post--content":"").($post_style == "style_2"?" post-style-2":"").($post_style == "style_3"?" post-style-3 post-with-columns".$post_columns:""));?>>
<?php }else {?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('article-post article-post-only clearfix'.$featured_style.($post_data->post_content == " post-no-content"?" post--content":"").($post_style == "style_2"?" post-style-2":"").($post_style == "style_3"?" post-style-3 post-with-columns".$post_columns:""));?>>
		<?php if (is_singular("post")) {
			do_action("wpqa_post_content",$post_data->ID,$user_id,$post_data->post_author);
		}else {
			do_action("wpqa_post_content_loop",$post_data->ID,$user_id,$post_data->post_author);
		}
}
	if ($pending_posts) {?>
		<div class="load_span"><span class="loader_2"></span></div>
	<?php }?>
	<div class="single-inner-content">
		<?php if (isset($sort_meta_title_image) && is_array($sort_meta_title_image)) {
			foreach ($sort_meta_title_image as $sort_meta_title_image_key => $sort_meta_title_image_value) {
				if (isset($sort_meta_title_image_value["value"]) && $sort_meta_title_image_value["value"] == "image") {
					if ($post_style == "style_2" || $post_style == "style_3") {
						if ($what_post != "none") {
							include locate_template("theme-parts/banner.php");
						}?>
						<div class="post-list<?php echo ($what_post == "none" || (!$what_post || $what_post == "image" || $what_post == "image_lightbox" || $what_post == "audio") && (!$discy_thumbnail_id || ($featured_image != 0 && $featured_image == "on"))?" post-list-0":"")?>">
					<?php }
				}else if (isset($sort_meta_title_image_value["value"]) && $sort_meta_title_image_value["value"] == "meta_title") {?>
					<header class="article-header<?php echo (((empty($blog_h) && isset($wp_page_template) && ($wp_page_template == "template-blog.php" || $wp_page_template == "template-home.php")) || !is_page()) && !is_attachment() && $author_by == "on"?"":" header-no-author").($author_by != "on" && $post_date != "on" && $title_post != "on" && $category_post != "on" && $post_comment != "on" && $post_views != "on"?" header-no-meta":"")?>">
						<?php if ((isset($blog_h) && $blog_h == "blog_h") || ((isset($wp_page_template) && ($wp_page_template == "template-blog.php" || $wp_page_template == "template-home.php")) || !is_page()) && !is_attachment()) {
							if ($post_style == "style_2" || $post_style == "style_3") {
								$category_post = $author_by = "";
								if ($post_style == "style_3") {
									$read_more = $post_share = "";
								}
							}
							if ($post_date == "on" || $category_post == "on" || $post_comment == "on" || $post_views == "on") {?>
								<div class="post-meta">
									<?php discy_meta($post_date,$category_post,$post_comment,"","",$post_views,$post_data->ID,$post_data)?>
								</div>
							<?php }
						}
						
						if ($title_post == "on" && !is_attachment()) {
							if ( (is_single() && empty($blog_h)) || (is_page() && empty($blog_h) && (empty($wp_page_template) || ($wp_page_template != "template-blog.php" && $wp_page_template != "template-home.php"))) ) {
								$custom_page_setting = discy_post_meta("custom_page_setting");
								if ($custom_page_setting == "on") {
									$breadcrumbs = discy_post_meta("breadcrumbs");
								}else {
									$breadcrumbs = discy_options("breadcrumbs");
								}
								$breadcrumbs_style = discy_options("breadcrumbs_style");
								$breadcrumbs_content_title = discy_options("breadcrumbs_content_title");
								if ($breadcrumbs != "on" || ($breadcrumbs == "on" && ($breadcrumbs_style != "style_2" || ($breadcrumbs_content_title != "on" && $breadcrumbs_style == "style_2")))) {
									the_title( '<'.($breadcrumbs == "on" && $breadcrumbs_style == "style_2"?"h2":"h1").' class="'.(isset($title_post_style) && $title_post_style == "style_2"?"post-title-2":"post-title").'">'.(isset($title_post_style) && $title_post_style == "style_2" && isset($title_post_icon) && $title_post_icon != ""?"<i class='".$title_post_icon."'></i>":"").(is_sticky()?"<i class='icon-pencil'></i>":""), '</'.($breadcrumbs == "on" && $breadcrumbs_style == "style_2"?"h2":"h1").'>' );
								}
							}else {
								the_title( '<h2 class="post-title"><a'.$post_link_target.' class="post-title" href="' . esc_url( get_permalink() ) . '" rel="bookmark">'.(is_sticky()?"<i class='icon-pencil'></i>":""), '</a></h2>' );
							}
						}
						if ( ((empty($blog_h) && isset($wp_page_template) && ($wp_page_template == "template-blog.php" || $wp_page_template == "template-home.php")) || !is_page()) && !is_attachment() && $author_by == "on" ) {
							$post_username = discy_post_meta("post_username","",false);
							if ($post_data->post_author > 0) {
								echo sprintf(esc_html_x( '%s', 'post author', 'discy' ),'<a class="post-author" rel="author" href="' . esc_url( get_author_posts_url( $post_data->post_author ) ) . '">'.esc_html(get_the_author()).'</a>');
							}else {
								echo esc_attr($post_username);
							}
						}
						if ($post_style != "style_2" && $post_style != "style_3" && $what_post != "none") {
							include locate_template("theme-parts/banner.php");
						}
						do_action("discy_content_before_header");?>
					</header>
				<?php }
			}
		}?>
		
		<div class="post-wrap-content<?php echo ((is_page() && empty($blog_h) && (empty($wp_page_template) || ($wp_page_template != "template-blog.php" && $wp_page_template != "template-home.php")))?"":" post-content ").(is_attachment()?" post-attachment ":"")?>">
			<div class="<?php echo (empty($blog_h) && isset($wp_page_template) && $wp_page_template == "template-contact.php"?"post-contact":"post-content-text")?>">
				<?php do_action("discy_before_post_content",$post_data->ID,$post_data);
				$get_the_content = get_the_content();
				$get_the_content = apply_filters('the_content',$get_the_content);
				if ((is_single() && empty($blog_h)) || (is_page() && empty($blog_h) && (empty($wp_page_template) || ($wp_page_template != "template-blog.php" && $wp_page_template != "template-home.php")))) {
					if (is_attachment()) {
						$site_width = (int)discy_options("site_width");
						$mins_width = ($site_width > 1170?$site_width-1170:0);
						if (wp_attachment_is_image()) {
							if ($discy_sidebar == "menu_sidebar") {
								$img_width = 629+$mins_width;
								$img_height = 420+($mins_width/2);
							}else if ($discy_sidebar == "menu_left") {
								$img_width = 908+$mins_width;
								$img_height = 600+($mins_width/2);
							}else if ($discy_sidebar == "full") {
								$img_width = 1108+$mins_width;
								$img_height = 700+($mins_width/2);
							}else if ($discy_sidebar == "centered") {
								$img_width = 768+$mins_width;
								$img_height = 510+($mins_width/2);
							}else {
								$img_width = 829+$mins_width;
								$img_height = 550+($mins_width/2);
							}
							$img_url = wp_get_attachment_url();
							$image = discy_get_aq_resize_url($img_url,$img_width,$img_height);?>
							<div class="wp-caption aligncenter">
								<img width="<?php echo esc_attr($img_width)?>" height="<?php echo esc_attr($img_height)?>" class="attachment-<?php echo esc_attr($img_width)?>x<?php echo esc_attr($img_height)?>" alt="<?php echo esc_attr( get_the_title() ); ?>" src="<?php echo esc_url($image)?>">
								<?php if (!empty($post_data->post_excerpt)) {?>
									<p class="wp-caption-text"><?php echo get_the_excerpt(); ?></p>
								<?php }?>
							</div>
						<?php }else {?>
							<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a><br>
							<p><?php if ( !empty( $post_data->post_excerpt ) ) the_excerpt(); ?></p>
						<?php }?>
						<div class="post-inner">
							<div class="post-inner-content"><?php echo ($get_the_content)?></div>
						</div><!-- End post-inner -->
					<?php }else {
						$show_post_filter = apply_filters('discy_show_post_filter',true);
						if ($show_post_filter == true) {
							do_action("discy_before_content",$post_data->ID);
							echo ($get_the_content);
							do_action("discy_after_content",$post_data->ID);
						}
					}
				}else {
					if (strpos($get_the_content,'more-link') === false && $post_data->post_content != "") {?>
						<div class="all_not_signle_post_content"><p><?php discy_excerpt($post_excerpt,$excerpt_type);?></p></div>
						<?php if ($pending_posts) {?>
							<div class='all_signle_post_content discy_hide'>
								<?php echo ($get_the_content)?>
							</div>
						<?php }
					}else {
						echo ($get_the_content);
					}
				}?>
			</div>
			<?php if (empty($blog_h) && isset($wp_page_template) && $wp_page_template == "template-faqs.php") {
				include locate_template("theme-parts/faqs.php");
			}
			if (empty($blog_h) && isset($wp_page_template) && $wp_page_template == "template-categories.php") {
				include locate_template("theme-parts/categories.php");
			}
			if (empty($blog_h) && isset($wp_page_template) && $wp_page_template == "template-tags.php") {
				include locate_template("theme-parts/tags.php");
			}
			if (empty($blog_h) && isset($wp_page_template) && $wp_page_template == "template-users.php") {
				include locate_template("theme-parts/users.php");
			}
			if ( (is_single() && empty($blog_h)) || (is_page() && empty($blog_h) && (empty($wp_page_template) || ($wp_page_template != "template-blog.php" && $wp_page_template != "template-home.php"))) ) {
				wp_link_pages(array('before' => '<div class="pagination post-pagination">','after' => '</div>','link_before' => '<span>','link_after' => '</span>'));
				
				if ( $post_tags == "on" && 'post' === get_post_type() ) {
					$tags_list = get_the_tag_list( '', '' );
					if ( $tags_list ) {
						echo "<div class='tagcloud'>".$tags_list."</div>";
					}
				}
			}
			do_action("wpqa_after_post_tags",$post_data->ID,$post);?>
		</div>
		
		<?php if (!is_page_template("template-users.php") && !is_page_template("template-contact.php") && !is_page_template("template-faqs.php") && !is_page_template("template-categories.php") && !is_page_template("template-tags.php")) {?>
			<footer<?php echo ($pending_posts?" class='pending-post-footer'":"")?>>
				<?php do_action("discy_action_before_edit_post",$post_data->ID,(isset($blog_h)?$blog_h:""));

				if ( ((is_page() && empty($blog_h) && (empty($wp_page_template) || ($wp_page_template != "template-blog.php" && $wp_page_template != "template-home.php")))) && get_edit_post_link() ) {
					edit_post_link(sprintf(esc_html__( 'Edit %s', 'discy' ),the_title( '<span class="screen-reader-text">"', '"</span>', false )),'<span class="edit-link">','</span>');
				}
				
				if (is_single() && empty($blog_h)) {
					$post_delete   = discy_options("post_delete");
					$can_edit_post = discy_options("can_edit_post");
					if (($post_data->post_author != 0 && $post_data->post_author == $user_id) || is_super_admin($user_id)) {
						if (has_wpqa() && ($can_edit_post == "on" || is_super_admin($user_id))) {
							echo '<span class="edit-link"><a href="'.wpqa_edit_permalink($post_data->ID,"post").'">'.esc_html__("Edit","discy").'</a></span>';
						}
						if ($post_delete == "on" || is_super_admin($user_id)) {
							echo '<span class="delete-link post-delete"><a href="'.esc_url_raw(add_query_arg(array("delete" => $post_data->ID,"wpqa_delete_nonce" => wp_create_nonce("wpqa_delete_nonce")),get_permalink($post_data->ID))).'">'.esc_html__("Delete","discy").'</a></span>';
						}
					}
					do_action("discy_content_after_links");
				}
				if (has_wpqa() && $pending_posts) {
					wpqa_review_post($post_data,$is_super_admin,$moderators_permissions);
				}else {
					if ( (strpos(get_the_content(),'more-link') !== false || $read_more == "on") && !is_single() && (empty($blog_h) && (isset($wp_page_template) && ($wp_page_template == "template-blog.php" || $wp_page_template == "template-home.php")) || !is_page()) ) {?>
						<a<?php echo esc_attr($post_link_target)?> class="post-read-more" href="<?php echo esc_url(get_permalink())?>" rel="bookmark" title="<?php esc_attr_e('Read','discy')?> <?php the_title()?>"><?php esc_html_e('Read more','discy')?></a>
					<?php }
					if (has_wpqa() && empty($blog_h)) {
						$share_facebook = (isset($post_share["share_facebook"]["value"])?$post_share["share_facebook"]["value"]:"");
						$share_twitter  = (isset($post_share["share_twitter"]["value"])?$post_share["share_twitter"]["value"]:"");
						$share_linkedin = (isset($post_share["share_linkedin"]["value"])?$post_share["share_linkedin"]["value"]:"");
						$share_whatsapp = (isset($post_share["share_whatsapp"]["value"])?$post_share["share_whatsapp"]["value"]:"");
						wpqa_share($post_share,$share_facebook,$share_twitter,$share_linkedin,$share_whatsapp);
					}
				}?>
			</footer>
		<?php }
		if ($post_style == "style_2" || $post_style == "style_3") {?>
			</div><!-- End post-list -->
		<?php }?>
	</div><!-- End single-inner-content -->
<?php if (isset($blog_h) && $blog_h == "blog_h") {?>
	</div>
<?php }else {
	if (is_single()) {
		do_action('discy_after_post_article',$post_data->ID);
	}?>
	</article><!-- End article -->
<?php }

if ( ( (is_single() && empty($blog_h)) || (is_page() && empty($blog_h) && (empty($wp_page_template) || ($wp_page_template != "template-blog.php" && $wp_page_template != "template-home.php"))) ) && !is_attachment() ) :
	if (empty($order_sections)) :
		$order_sections = array(
			"author"        => array("sort" => esc_html__("About the author","discy"),"value" => "author"),
			"next_previous" => array("sort" => esc_html__("Next and Previous articles","discy"),"value" => "next_previous"),
			"advertising"   => array("sort" => esc_html__("Advertising","discy"),"value" => "advertising"),
			"related"       => array("sort" => esc_html__("Related articles","discy"),"value" => "related"),
			"comments"      => array("sort" => esc_html__("Comments","discy"),"value" => "comments"),
		);
	endif;
	foreach ($order_sections as $key_r => $value_r) :
		if ($value_r["value"] == "") :
			unset($order_sections[$key_r]);
		else :
			if (!is_page_template("template-blog.php") && !is_page_template("template-home.php") && !is_page_template("template-users.php") && !is_page_template("template-contact.php") && !is_page_template("template-faqs.php") && !is_page_template("template-categories.php") && !is_page_template("template-tags.php") && $value_r["value"] == "author" && isset($post_data->post_author)) :
				$the_author_meta_description = get_the_author_meta("description",$post_data->post_author);
				if ($the_author_meta_description != "") :
					do_action("wpqa_author",array("user_id" => $post_data->post_author,"author_page" => "single-author","owner" => "","type_post" => "","widget" => "single-author"));
				endif;
			elseif (is_single() && $value_r["value"] == "next_previous") :
				if ($post_nav_category == "on") {
					$previous_post = get_previous_post(true,'','category');
					$next_post = get_next_post(true,'','category');
				}else {
					$previous_post = get_previous_post();
					$next_post = get_next_post();
				}
				if ((isset($previous_post) && is_object($previous_post)) || (isset($next_post) && is_object($next_post))) :?>
					<div class="page-navigation page-navigation-single clearfix">
						<?php do_action("discy_content_before_previous")?>
						<div class="row">
							<?php if (isset($previous_post) && is_object($previous_post)) {?>
								<div class="col col6 col-nav-previous">
									<div class="nav-previous">
										<div class="navigation-content">
											<span class="navigation-i"><i class="icon-left-thin"></i></span>
											<span class="navigation-text"><?php esc_html_e('Previous article',"discy");?></span>
											<div class="clearfix"></div>
											<?php previous_post_link('%link');?>
										</div>
									</div>
								</div>
							<?php }
							if (isset($next_post) && is_object($next_post)) {?>
								<div class="col col6 col-nav-next">
									<div class="nav-next">
										<div class="navigation-content">
											<span class="navigation-i"><i class="icon-right-thin"></i></span>
											<span class="navigation-text"><?php esc_html_e('Next article',"discy");?></span>
											<div class="clearfix"></div>
											<?php next_post_link('%link')?>
										</div>
									</div>
								</div>
							<?php }?>
						</div>
					</div><!-- End page-navigation -->
				<?php endif;
			elseif (!is_page_template("template-blog.php") && !is_page_template("template-home.php") && !is_page_template("template-users.php") && !is_page_template("template-contact.php") && !is_page_template("template-faqs.php") && !is_page_template("template-categories.php") && !is_page_template("template-tags.php") && $value_r["value"] == "advertising") :
				echo discy_ads("share_adv_type","share_adv_code","share_adv_href","share_adv_img","","on","discy-ad-inside");
			elseif (is_single() && $value_r["value"] == "related") :
				include locate_template("theme-parts/related.php");
			elseif (!is_page_template("template-blog.php") && !is_page_template("template-home.php") && !is_page_template("template-users.php") && !is_page_template("template-contact.php") && !is_page_template("template-faqs.php") && !is_page_template("template-categories.php") && !is_page_template("template-tags.php") && $value_r["value"] == "comments" && (comments_open() || $count_post_all > 0)) :
				comments_template();
			endif;
		endif;
	endforeach;
endif;?>