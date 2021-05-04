<?php
$before_widget = '<section id="%1$s" class="widget %2$s">';
$after_widget = '</section>';
$before_title = '<h2 class="widget-title"><i class="icon-folder"></i>';
$after_title = '</h2>';

/* discy_widgets_init */
add_action( 'widgets_init', 'discy_widgets_init' );
function discy_widgets_init() {
	global $before_widget,$after_widget,$before_title,$after_title;
	
	$sidebars = discy_options('sidebars');
	if ($sidebars) {
		foreach ($sidebars as $sidebar) {
			register_sidebar( array(
				'name' => esc_html($sidebar["name"]),
				'id' => sanitize_title(esc_html($sidebar["name"])),
				'before_widget' => $before_widget , 'after_widget' => $after_widget , 'before_title' => $before_title , 'after_title' => $after_title ,
			) );
		}
	}
	
	$footer_layout = discy_options("footer_layout");
	
	if ($footer_layout == "footer_1c" || $footer_layout == "footer_2c" || $footer_layout == "footer_3c" || $footer_layout == "footer_4c" || $footer_layout == "footer_5c") {
		register_sidebar( array(
			'name' => esc_html__("First footer widget area","discy"),
			'id' => "footer_1c_sidebar",
			'before_widget' => $before_widget , 'after_widget' => $after_widget , 'before_title' => $before_title , 'after_title' => $after_title ,
		));
	}
	if ($footer_layout == "footer_2c" || $footer_layout == "footer_3c" || $footer_layout == "footer_4c" || $footer_layout == "footer_5c") {
		register_sidebar( array(
			'name' => esc_html__("Second footer widget area","discy"),
			'id' => "footer_2c_sidebar",
			'before_widget' => $before_widget , 'after_widget' => $after_widget , 'before_title' => $before_title , 'after_title' => $after_title ,
		));
	}
	if ($footer_layout == "footer_3c" || $footer_layout == "footer_4c" || $footer_layout == "footer_5c") {
		register_sidebar( array(
			'name' => esc_html__("Third footer widget area","discy"),
			'id' => "footer_3c_sidebar",
			'before_widget' => $before_widget , 'after_widget' => $after_widget , 'before_title' => $before_title , 'after_title' => $after_title ,
		));
	}
	if ($footer_layout == "footer_4c" || $footer_layout == "footer_5c") {
		register_sidebar( array(
			'name' => esc_html__("Fourth footer widget area","discy"),
			'id' => "footer_4c_sidebar",
			'before_widget' => $before_widget , 'after_widget' => $after_widget , 'before_title' => $before_title , 'after_title' => $after_title ,
		));
	}
	if ($footer_layout == "footer_5c") {
		register_sidebar( array(
			'name' => esc_html__("Fifth footer widget area","discy"),
			'id' => "footer_5c_sidebar",
			'before_widget' => $before_widget , 'after_widget' => $after_widget , 'before_title' => $before_title , 'after_title' => $after_title ,
		));
	}
}
if (function_exists('register_sidebar')) {
	register_sidebar(array('name' => esc_html__('Sidebar','discy'),'id' => 'sidebar_default',
		'before_widget' => $before_widget,
		'after_widget'  => $after_widget,	
		'before_title'  => $before_title,
		'after_title'   => $after_title
	));
	
	register_sidebar(array('name' => esc_html__('Sidebar 2','discy'),'id' => 'sidebar_default_2',
		'before_widget' => $before_widget,
		'after_widget'  => $after_widget,	
		'before_title'  => $before_title,
		'after_title'   => $after_title
	));
}?>