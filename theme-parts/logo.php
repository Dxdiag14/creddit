<?php if ($logo_display == "custom_image") {?>
    <a class="logo float_l logo-img" href="<?php echo esc_url(home_url('/'));?>" title="<?php echo esc_attr(get_bloginfo('name','display'))?>">
    	<?php if ((isset($logo_img) && $logo_img != "") || ($retina_logo == "" && isset($logo_img) && $logo_img != "")) {?>
    		<img title="<?php echo esc_attr(get_bloginfo('name','display'))?>" height="<?php echo esc_attr($logo_height)?>" width="<?php echo esc_attr($logo_width)?>" class="<?php echo ($retina_logo == "" && isset($logo_img) && $logo_img != ""?"retina_screen":"default_screen")?>" alt="<?php echo esc_attr(get_bloginfo('name','display'))?> <?php esc_html_e('Logo','discy')?>" src="<?php echo esc_url($logo_img)?>">
    	<?php }
    	if (isset($retina_logo) && $retina_logo != "") {?>
    		<img title="<?php echo esc_attr(get_bloginfo('name','display'))?>" height="<?php echo esc_attr($logo_height)?>" width="<?php echo esc_attr($logo_width)?>" class="retina_screen" alt="<?php echo esc_attr(get_bloginfo('name','display'))?> <?php esc_html_e('Logo','discy')?>" src="<?php echo esc_url($retina_logo)?>">
    	<?php }?>
    </a>
<?php }else {?>
	<a href="<?php echo esc_url(home_url('/'));?>" title="<?php echo esc_attr(get_bloginfo('name','display'))?>" class='logo-name logo float_l'><?php echo esc_attr(get_bloginfo('name','display'))?></a>
<?php }?>