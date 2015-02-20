<?php
/**
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.1
 */

load_theme_textdomain( 'fau', get_template_directory() . '/languages' );
require_once( get_template_directory() . '/functions/constants.php' );
$options = fau_initoptions();
require_once( get_template_directory() . '/functions/helper-functions.php' );
require_once ( get_template_directory() . '/functions/theme-options.php' );     
require_once( get_template_directory() .'/functions/bootstrap.php');
require_once( get_template_directory() .'/functions/shortcodes.php');
require_once( get_template_directory() .'/functions/menu.php');
require_once( get_template_directory() . '/functions/custom-fields.php' );
require_once( get_template_directory() . '/functions/posttype_imagelink.php' );
require_once( get_template_directory() . '/functions/posttype_ad.php' );
require_once( get_template_directory() . '/functions/widgets.php' );



function fau_setup() {
	global $options;
	

	if ( ! isset( $content_width ) ) $content_width = $options['content-width'];
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css' ) );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Switches default core markup for search form, comment form, and comments
	// to output valid HTML5.
//	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	/*
	 * This theme supports all available post formats by default.
	 * See http://codex.wordpress.org/Post_Formats
	 */
//	add_theme_support( 'post-formats', array(
//		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
//	) );

	if ( ! function_exists( '_wp_render_title_tag' ) ) :
	    function theme_slug_render_title() {
	?>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php
	    }
	    add_action( 'wp_head', 'theme_slug_render_title' );
	endif;
	 
	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'meta', __( 'Meta-Navigation oben', 'fau' ) );
	register_nav_menu( 'meta-footer', __( 'Meta-Navigation unten', 'fau' ) );
	register_nav_menu( 'main-menu', __( 'Haupt-Navigation', 'fau' ) );
	
	register_nav_menu( 'quicklinks-1', __( 'Quicklinks 1', 'fau' ) );
	register_nav_menu( 'quicklinks-2', __( 'Quicklinks 2', 'fau' ) );
	register_nav_menu( 'quicklinks-3', __( 'Quicklinks 3', 'fau' ) );
	register_nav_menu( 'quicklinks-4', __( 'Quicklinks 4', 'fau' ) );
	
	register_nav_menu( 'error-1', __( 'Fehler 1', 'fau' ) );
	register_nav_menu( 'error-2', __( 'Fehler 2', 'fau' ) );
	register_nav_menu( 'error-3', __( 'Fehler 3', 'fau' ) );
	register_nav_menu( 'error-4', __( 'Fehler 4', 'fau' ) );
	
	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 300, 150, false );
	
	add_image_size( 'hero', $options['slider-image-width'], $options['slider-image-height'], $options['slider-image-crop']);	// 1260:350
	add_image_size( 'post-thumb', $options['default_postthumb_width'], $options['default_postthumb_height'], $options['default_postthumb_crop']); // 3:2
	add_image_size( 'topevent-thumb', $options['default_topevent_thumb_width'], $options['default_topevent_thumb_height'], $options['default_topevent_thumb_crop']); 
	add_image_size( 'page-thumb', $options['default_submenuthumb_width'], $options['default_submenuthumb_height'], true); // 220:110
	
	
	add_image_size( 'post', 300, 200, false);
	add_image_size( 'person-thumb', 60, 80, true); // 300, 150
	add_image_size( 'person-thumb-bigger', 90, 120, true);

	
	
	add_image_size( 'logo-thumb', 140, 110, true);
	
	add_image_size( 'gallery-full', 940, 470);
	add_image_size( 'gallery-thumb', 120, 80, true);
	add_image_size( 'gallery-grid', 145, 120, false);

	add_image_size( 'image-2-col', 300, 200, true);
	add_image_size( 'image-4-col', 140, 70, true);	
		
	// This theme uses its own gallery styles.
//	add_filter( 'use_default_gallery_style', '__return_false' );
	
	
	/* Remove something out of the head */
	remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed	
	remove_action( 'wp_head', 'post_comments_feed_link ', 2 ); // Display the links to the general feeds: Post and Comment Feed
	remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
	remove_action( 'wp_head', 'index_rel_link' ); // index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
	//remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0);
	
}
add_action( 'after_setup_theme', 'fau_setup' );

function fau_initoptions() {
   global $defaultoptions;
    
    $oldoptions = get_option('fau_theme_options');
    if (isset($oldoptions) && (is_array($oldoptions))) {
        $newoptions = array_merge($defaultoptions,$oldoptions);	  
    } else {
        $newoptions = $defaultoptions;
    }    
    return $newoptions;
}



/**
 * Enqueues scripts and styles for front end.
 *
 * @since FAU 1.0
 *
 * @return void
 */
function fau_scripts_styles() {
	global $options;
	wp_enqueue_style( 'fau-style', get_stylesheet_uri(), array(), $options['js-version'] );	
//	wp_enqueue_script( 'fau-libs-jquery', get_fau_template_uri() . '/js/libs/jquery-1.11.1.min.js', array(), $options['js-version'], true );
	wp_enqueue_script( 'fau-libs-plugins', get_fau_template_uri() . '/js/libs/plugins.js', array('jquery'), $options['js-version'], true );
//	if (is_front_page() || is_home()) {
	    wp_enqueue_script( 'fau-libs-jquery-flexslider', get_fau_template_uri() . '/js/libs/jquery.flexslider.js', array('jquery'), $options['js-version'], true );
	    // wird bei Startseioe Slider und auch bei gallerien verwendet
//	}
	wp_enqueue_script( 'fau-libs-jquery-caroufredsel', get_fau_template_uri() . '/js/libs/jquery.caroufredsel.js', array('jquery'), $options['js-version'], true );
	    // wird bei Bild-Menus und Galerien verwendet
	wp_enqueue_script( 'fau-libs-jquery-hoverintent', get_fau_template_uri() . '/js/libs/jquery.hoverintent.js', array(), $options['js-version'], true );
	wp_enqueue_script( 'fau-libs-jquery-fluidbox', get_fau_template_uri() . '/js/libs/jquery.fluidbox.js', array(), $options['js-version'], true );
	wp_enqueue_script( 'fau-libs-jquery-fancybox', get_fau_template_uri() . '/js/libs/jquery.fancybox.js', array('jquery'), $options['js-version'], true );
	wp_enqueue_script( 'fau-scripts', get_fau_template_uri() . '/js/scripts.js', array('jquery'), $options['js-version'], true );
}
add_action( 'wp_enqueue_scripts', 'fau_scripts_styles' );


function fau_addmetatags() {
    global $options;

    $output = "";
    $output .= '<meta http-equiv="Content-Type" content="text/html; charset='.get_bloginfo('charset').'" />'."\n";
    $output .= '<!--[if IE]> <meta http-equiv="X-UA-Compatible" content="IE=9"> <![endif]-->'."\n";
    $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";    
    
    // $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">'."\n";    

    $output .= fau_get_rel_alternate();
            
    if ((isset( $options['google-site-verification'] )) && ( strlen(trim($options['google-site-verification']))>1 )) {
        $output .= '<meta name="google-site-verification" content="'.$options['google-site-verification'].'">'."\n";
    }
	
    if ((isset($options['favicon-file'])) && ($options['favicon-file_id']>0 )) {	 
        $output .=  '<link rel="shortcut icon" href="'.$options['favicon-file'].'">'."\n";
    } else {
        $output .=  '<link rel="apple-touch-icon" href="'.get_fau_template_uri().'/apple-touch-icon.png">'."\n";
        $output .=  '<link rel="shortcut icon" href="'.get_fau_template_uri().'/favicon.ico">'."\n";
    }
    echo $output;
}

add_action('wp_head', 'fau_addmetatags',1);



/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since FAU 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function fau_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Seite %s', 'fau' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'fau_wp_title', 10, 2 );


/**
 * Resets the Excerpt More
 */

function fau_excerpt_more( $more ) {
    global $options;
    return $options['default_excerpt_morestring'];
}
add_filter('excerpt_more', 'fau_excerpt_more');

/**
 * Resets the Excerpt More
 */
function fau_excerpt_length( $length ) {
    global $options;
    return $options['default_excerpt_length'];
}
add_filter( 'excerpt_length', 'fau_excerpt_length', 999 );



/* Header Setup */
function fau_custom_header_setup() {
    global $default_header_logos;
    global $options;
	$args = array(
	    'default-image'          => $options['default_logo_src'],
	    'height'                 => $options['default_logo_height'],
	    'width'                  => $options['default_logo_width'],
	    'admin-head-callback'    => 'fau_admin_header_style',
	);
	add_theme_support( 'custom-header', $args );

	register_default_headers( $default_header_logos );
}
add_action( 'after_setup_theme', 'fau_custom_header_setup' );



function fau_admin_header_style() {
    wp_register_style( 'themeadminstyle', get_fau_template_uri().'/css/admin.css' );	   
    wp_enqueue_style( 'themeadminstyle' );	
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-datepicker');
    wp_register_script('themeadminscripts', get_fau_template_uri().'/js/admin.js', array('jquery'));    
    wp_enqueue_script('themeadminscripts');	   
}
add_action( 'admin_enqueue_scripts', 'fau_admin_header_style' );

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since FAU 1.0
 */
function fau_widgets_init() {

	

	register_sidebar( array(
		'name' => __( 'News Sidebar', 'fau' ),
		'id' => 'news-sidebar',
		'description' => __( 'Sidebar auf der News-Kategorieseite', 'fau' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="small">',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Suche Sidebar', 'fau' ),
		'id' => 'search-sidebar',
		'description' => __( 'Sidebar auf der Such-Ergebnisseite links', 'fau' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="small">',
		'after_title' => '</h2>',
	) );
	
    // Wenn CMS-Workflow vorhanden und aktiviert ist
	if (is_workflow_translation_active()) {
	    register_sidebar( array(
		    'name' => __( 'Sprachwechsler', 'fau' ),
		    'id' => 'language-switcher',
		    'description' => __( 'Sprachwechsler im Header der Seite', 'fau' ),
		    'before_widget' => '',
		    'after_widget' => '',
		    'before_title' => '',
		    'after_title' => '',
	    ) );
	}
	
}
add_action( 'widgets_init', 'fau_widgets_init' );




add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );



function add_video_embed_note($html, $url, $attr) {
	return '<div class="oembed">'.$html.'</div>';
}
add_filter('embed_oembed_html', 'add_video_embed_note', 10, 3);



function fau_protected_attribute ($classes, $item) {
	if($item->post_password != '')
	{
		$classes[] = 'protected-page';
	}
	return $classes;
}
add_filter('page_css_class', 'fau_protected_attribute', 10, 3);


function custom_error_pages()
{
    global $wp_query;
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)
    {
        $wp_query->is_404 = FALSE;
        $wp_query->is_page = TRUE;
        $wp_query->is_singular = TRUE;
        $wp_query->is_single = FALSE;
        $wp_query->is_home = FALSE;
        $wp_query->is_archive = FALSE;
        $wp_query->is_category = FALSE;
        add_filter('wp_title','custom_error_title',65000,2);
        add_filter('body_class','custom_error_class');
        status_header(403);
        get_template_part('403');
        exit;
    }
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)
    {
        $wp_query->is_404 = FALSE;
        $wp_query->is_page = TRUE;
        $wp_query->is_singular = TRUE;
        $wp_query->is_single = FALSE;
        $wp_query->is_home = FALSE;
        $wp_query->is_archive = FALSE;
        $wp_query->is_category = FALSE;
        add_filter('wp_title','custom_error_title',65000,2);
        add_filter('body_class','custom_error_class');
        status_header(401);
        get_template_part('401');
        exit;
    }
}
 
function custom_error_title($title='',$sep='')
{
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)
        return "Forbidden ".$sep." ".get_bloginfo('name');
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)
        return "Unauthorized ".$sep." ".get_bloginfo('name');
}
 
function custom_error_class($classes)
{
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)
    {
        $classes[]="error403";
        return $classes;
    }
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)
    {
        $classes[]="error401";
        return $classes;
    }
}
 
add_action('wp','custom_error_pages');


add_action( 'contextual_help', 'wptuts_screen_help', 10, 3 );
function wptuts_screen_help( $contextual_help, $screen_id, $screen ) {
 
    // The add_help_tab function for screen was introduced in WordPress 3.3.
    if ( ! method_exists( $screen, 'add_help_tab' ) )
        return $contextual_help;
 
    global $hook_suffix;
 
    // List screen properties
    $variables = '<ul style="width:50%;float:left;"> <strong>Screen variables </strong>'
        . sprintf( '<li> Screen id : %s</li>', $screen_id )
        . sprintf( '<li> Screen base : %s</li>', $screen->base )
        . sprintf( '<li>Parent base : %s</li>', $screen->parent_base )
        . sprintf( '<li> Parent file : %s</li>', $screen->parent_file )
        . sprintf( '<li> Hook suffix : %s</li>', $hook_suffix )
        . '</ul>';
 
    // Append global $hook_suffix to the hook stems
    $hooks = array(
        "load-$hook_suffix",
        "admin_print_styles-$hook_suffix",
        "admin_print_scripts-$hook_suffix",
        "admin_head-$hook_suffix",
        "admin_footer-$hook_suffix"
    );
 
    // If add_meta_boxes or add_meta_boxes_{screen_id} is used, list these too
    if ( did_action( 'add_meta_boxes_' . $screen_id ) )
        $hooks[] = 'add_meta_boxes_' . $screen_id;
 
    if ( did_action( 'add_meta_boxes' ) )
        $hooks[] = 'add_meta_boxes';
 
    // Get List HTML for the hooks
    $hooks = '<ul style="width:50%;float:left;"> <strong>Hooks </strong> <li>' . implode( '</li><li>', $hooks ) . '</li></ul>';
 
    // Combine $variables list with $hooks list.
    $help_content = $variables . $hooks;
 
    // Add help panel
    $screen->add_help_tab( array(
        'id'      => 'wptuts-screen-help',
        'title'   => 'Screen Information',
        'content' => $help_content,
    ));
 
    return $contextual_help;
}


add_filter('post_gallery', 'fau_post_gallery', 10, 2);
function fau_post_gallery($output, $attr) {
    global $post;
    global $options;
    if (isset($attr['orderby'])) {
        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
        if (!$attr['orderby'])
            unset($attr['orderby']);
    }

    extract(shortcode_atts(array(
        'order' => 'ASC',
        'orderby' => 'menu_order ID',
        'id' => $post->ID,
        'itemtag' => 'dl',
        'icontag' => 'dt',
        'captiontag' => 'dd',
        'columns' => 3,
        'size' => 'thumbnail',
        'include' => '',
        'exclude' => '',
	'type' => NULL,
	'lightbox' => FALSE,
	'captions' => 1
    ), $attr));

    $id = intval($id);
    if ('RAND' == $order) $orderby = 'none';

    if (!empty($include)) {
        $include = preg_replace('/[^0-9,]+/', '', $include);
        $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    }

    if (empty($attachments)) return '';

	
    $output = '';
    if (!isset($attr['captions'])) {
	$attr['captions'] =1;
    }
    if (!isset($attr['type'])) {
	$attr['type'] = 'default';
    }
    switch($attr['type'])  {
	    case "grid":
		    {
			$rand = rand();

			$output .= "<div class=\"image-gallery-grid clearfix\">\n";
			$output .= "<ul class=\"grid\">\n";

			    foreach ($attachments as $id => $attachment) {
				    $img = wp_get_attachment_image_src($id, 'gallery-grid');
				    $meta = get_post($id);
				    // $img_full = wp_get_attachment_image_src($id, 'gallery-full');
				    $img_full = wp_get_attachment_image_src($id, 'full');

				    if(isset( $attr['captions']) && ($attr['captions']==1) && $meta->post_excerpt) {
					    $output .= "<li class=\"has-caption\">\n";
				    } else  {
					    $output .= "<li>\n";
				    }
				//    if(isset($attr['lightbox']))   {
					$output .= '<a href="'.fau_esc_url($img_full[0]).'" class="lightbox"';
					if($meta->post_excerpt != '') $output .= ' title="'.$meta->post_excerpt.'"';
					$output .= ' rel="lightbox-'.$rand.'">';
				 //   }

				    $output .= '<img src="'.fau_esc_url($img[0]).'" width="'.$img[1].'" height="'.$img[2].'" alt="">';
				//    if(isset($attr['lightbox'])) 
				    $output .= '</a>';
				    if(isset( $attr['captions']) && ($attr['captions']==1) && $meta->post_excerpt) {
					    $output .= '<div class="caption">'.$meta->post_excerpt.'</div>';
				    }
			    $output .= "</li>\n";
			}

			    $output .= "</ul>\n";
			$output .= "</div>\n";

			    break;
		    }

	    case "2cols":
		    {
			    $rand = rand();

			    $output .= '<div class="row">'."\n";
			    $i = 0;

			    foreach ($attachments as $id => $attachment) {
				    $img = wp_get_attachment_image_src($id, 'image-2-col');
				    $img_full = wp_get_attachment_image_src($id, 'full');
				    $meta = get_post($id);

				    $output .= '<div class="span4">';
				    $output .= '<a href="'.fau_esc_url($img_full[0]).'" class="lightbox" rel="lightbox-'.$rand.'">';
				    $output .= '<img class="content-image-cols" src="'.fau_esc_url($img[0]).'" width="'.$img[1].'" height="'.$img[2].'" alt=""></a>';
				    if($attr['captions'] && $meta->post_excerpt) $output .= '<div class="caption">'.$meta->post_excerpt.'</div>';
				    $output .= '</div>'."\n";
				    $i++;

				    if($i % 2 == 0) {
					    $output .= '</div><div class="row">'."\n";
				    }
			    }

			    $output .= '</div>'."\n";

			    break;
		    }

	    case "4cols":
		    {
			    $rand = rand();

			    $output .= '<div class="row">'."\n";
			    $i = 0;

			    foreach ($attachments as $id => $attachment) {
				    $img = wp_get_attachment_image_src($id, 'image-4-col');
				    $img_full = wp_get_attachment_image_src($id, 'full');
				    $meta = get_post($id);

				    $output .= '<div class="span2">';
				    $output .= '<a href="'.fau_esc_url($img_full[0]).'" class="lightbox" rel="lightbox-'.$rand.'">';
				    $output .= '<img class="content-image-cols" src="'.fau_esc_url($img[0]).'" width="'.$img[1].'" height="'.$img[2].'" alt=""></a>';
				    if($attr['captions'] && $meta->post_excerpt) $output .= '<div class="caption">'.$meta->post_excerpt.'</div>';
				    $output .= '</div>';
				    $i++;

				    if($i % 4 == 0) {
					    $output .= '    </div><div class="row">'."\n";
				    }
			    }

			    $output .= "</div>\n";

			    break;
		    }

	    default:
		    {
			$rand = rand();	    
			$output .= "<div id=\"slider-$rand\" class=\"image-gallery-slider\">\n";
			$output .= "	<ul class=\"slides\">\n";

			foreach ($attachments as $id => $attachment) {
			    $img = wp_get_attachment_image_src($id, 'gallery-full');
			    $meta = get_post($id);
			    $img_full = wp_get_attachment_image_src($id, 'full');

			    $output .= '<li><img src="'.fau_esc_url($img[0]).'" width="'.$img[1].'" height="'.$img[2].'" alt="">';
			    if (($options['galery_link_original']) || ($meta->post_excerpt != '')) {
				$output .= '<div class="gallery-image-caption">';
				if($meta->post_excerpt != '') { $output .= $meta->post_excerpt; }
				if ($options['galery_link_original']) {
				    if($meta->post_excerpt != '') { $output .= '<br>'; }
				    $output .= '<span class="linkorigin">(<a href="'.fau_esc_url($img_full[0]).'" class="lightbox" rel="lightbox-'.$rand.'">'.__('In Originalgröße','fau').'</a>)</span>';
				}
				$output .='</div>';
			    }
			    $output .= "</li>\n";
			}

			$output .= "	</ul>\n";
			$output .= "</div>\n";

			
			
			$output .= "<div id=\"carousel-$rand\" class=\"image-gallery-carousel\">";
			$output .= "	<ul class=\"slides\">";

			foreach ($attachments as $id => $attachment) {
			    $img = wp_get_attachment_image_src($id, 'gallery-thumb');
			    $output .= '	<li><img src="'.fau_esc_url($img[0]).'" width="'.$img[1].'" height="'.$img[2].'" alt=""></li>';
			}

			$output .= "	</ul>";
			$output .= "</div>";				
			$output .= "<script type=\"text/javascript\"> jQuery(document).ready(function($) {";			
			$output .= "$('#carousel-$rand').flexslider({selector: 'ul > li',animation: 'slide',keyboard:true,multipleKeyboard:true,directionNav:true,controlNav: true,pausePlay: false,slideshow: false,asNavFor: '#slider-$rand',itemWidth: 125,itemMargin: 5});";
			$output .= "$('#slider-$rand').flexslider({selector: 'ul > li',animation: 'slide',keyboard:true,multipleKeyboard:true,directionNav: false,controlNav: false,pausePlay: false,slideshow: false,sync: '#carousel-$rand'});";
			$output .= "});</script>";

		    }
    }

    

    return $output;
}

/*
 * Make URLs relative; Several functions
 */
function fau_relativeurl($content){
        return preg_replace_callback('/<a[^>]+/', 'fau_relativeurl_callback', $content);
}
function fau_relativeurl_callback($matches) {
        $link = $matches[0];
        $site_link =  wp_make_link_relative(home_url());  
        $link = preg_replace("%href=\"$site_link%i", 'href="', $link);                 
        return $link;
    }
 add_filter('the_content', 'fau_relativeurl');
 
 function fau_relativeimgurl($content){
        return preg_replace_callback('/<img[^>]+/', 'fau_relativeimgurl_callback', $content);
}
function fau_relativeimgurl_callback($matches) {
        $link = $matches[0];
        $site_link =  wp_make_link_relative(home_url());  
        $link = preg_replace("%src=\"$site_link%i", 'src="', $link);                 
        return $link;
    }
 add_filter('the_content', 'fau_relativeimgurl');
 
 /*
  * Replaces esc_url, but also makes URL relative
  */
 function fau_esc_url( $url) {
     if (!isset($url)) {
	 $url = home_url("/");
     }
     return wp_make_link_relative(esc_url($url));
 }
 
 function get_fau_template_uri () {
     return wp_make_link_relative(get_template_directory_uri());
 }
 function fau_get_template_uri () {
     return wp_make_link_relative(get_template_directory_uri());
 } 

add_action('template_redirect', 'rw_relative_urls');
function rw_relative_urls() {
    // Don't do anything if:
    // - In feed
    // - In sitemap by WordPress SEO plugin
    if (is_admin() || is_feed() || get_query_var('sitemap')) {
        return;
    }
    $filters = array(
    //    'post_link',
        'post_type_link',
        'page_link',
        'attachment_link',
        'get_shortlink',
        'post_type_archive_link',
        'get_pagenum_link',
        'get_comments_pagenum_link',
        'term_link',
        'search_link',
        'day_link',
        'month_link',
        'year_link',
        'script_loader_src',
        'style_loader_src',
    );
    foreach ($filters as $filter) {
        add_filter($filter, 'fau_make_link_relative');
    }
}

function fau_make_link_relative($url) {
    $current_site_url = get_site_url();   
	if (!empty($GLOBALS['_wp_switched_stack'])) {
        $switched_stack = $GLOBALS['_wp_switched_stack'];
        $blog_id = end($switched_stack);
        if ($GLOBALS['blog_id'] != $blog_id) {
            $current_site_url = get_site_url($blog_id);
        }
    }
    $current_host = parse_url($current_site_url, PHP_URL_HOST);
    $host = parse_url($url, PHP_URL_HOST);
    if($current_host == $host) {
        $url = wp_make_link_relative($url);
    }
    return $url; 
}

function fau_get_defaultlinks ($list = 'faculty', $ulclass = '', $ulid = '') {
    global $default_link_liste;
    
    
    if (is_array($default_link_liste[$list])) {
	$uselist =  $default_link_liste[$list];
    } else {
	$uselist =  $default_link_liste['faculty'];
    }
    
    $result = '';
    if (isset($uselist['_title'])) {
	$result .= '<h3>'.$uselist['_title'].'</h3>';	
	$result .= "\n";
    }
    $thislist = '';
    foreach($uselist as $key => $entry ) {
	if (substr($key,0,4) != 'link') {
	    continue;
	}
	$thislist .= '<li';
	if (isset($entry['class'])) {
	    $thislist .= ' class="'.$entry['class'].'"';
	}
	$thislist .= '>';
	if (isset($entry['content'])) {
	    $thislist .= '<a href="'.$entry['content'].'">';
	}
	$thislist .= $entry['name'];
	if (isset($entry['content'])) {
	    $thislist .= '</a>';
	}
	$thislist .= "</li>\n";
    }    
    if (isset($thislist)) {
	$result .= '<ul';
	if (!empty($ulclass)) {
	    $result .= ' class="'.$ulclass.'"';
	}
	if (!empty($ulid)) {
	    $result .= ' id="'.$ulid.'"';
	}
	$result .= '>';
	$result .= $thislist;
	$result .= '</ul>';	
	$result .= "\n";	
    }
    return $result;
}

function fau_main_menu_fallback() {
    global $options;
    $output = '';
    $some_pages = get_pages(array('parent' => 0, 'number' => $options['default_mainmenu_number'], 'hierarchical' => 0));
    if($some_pages) {
        foreach($some_pages as $page) {
            $output .= sprintf('<li class="menu-item level1"><a href="%1$s">%2$s</a></li>', get_permalink($page->ID), $page->post_title);
        }
        
        $output = sprintf('<ul role="navigation" aria-label="%1$s" id="nav">%2$s</ul>', __('Navigation', 'fau'), $output);
    }   
    return $output;
}



function fau_custom_excerpt($id = 0, $length = 0, $withp = true, $class = '', $withmore = false, $morestr = '', $continuenextline=false) {
  global $options;
    
    if ($length==0) {
	$length = $options['default_excerpt_length'];
    }
    
    if (empty($morestr)) {
	$morestr = $options['default_excerpt_morestring'];
    }
    
    $excerpt = get_post_field('post_excerpt',$id);
 
    if (mb_strlen(trim($excerpt))<5) {
	$excerpt = get_post_field('post_content',$id);
    }

    $excerpt = preg_replace('/\s+(https?:\/\/www\.youtube[\/a-z0-9\.\-\?&;=_]+)/i','',$excerpt);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt, $options['custom_excerpt_allowtags']); 

  
  if (mb_strlen($excerpt)<5) {
      $excerpt = '<!-- '.__( 'Kein Inhalt', 'fau' ).' -->';
  }
    
  $needcontinue =0;
  if (mb_strlen($excerpt) >  $length) {
	$str = mb_substr($excerpt, 0, $length);
	$needcontinue = 1;
  }  else {
	$str = $excerpt;
  }
	    
    $the_str = '';
    if ($withp) {
	$the_str .= '<p';
	if (isset($class)) {
	    $the_str .= ' class="'.$class.'"';
	}
	$the_str .= '>';
    }
    $the_str .= $str;
    
    if (($needcontinue==1) && ($withmore==true)) {
	    if ($continuenextline) {
		  $the_str .= '<br>';
	    }
	    $the_str .= $morestr;
    }
  
    if ($withp) {
	$the_str .= '</p>';
    }
  return $the_str;
}

/**
 * CMS-Workflow Plugin
 */
function is_workflow_translation_active() {
    global $cms_workflow;
    if (isset($cms_workflow->translation) && $cms_workflow->translation->module->options->activated) {
        return true;
    }
    return false;
}

function fau_get_rel_alternate() {
    if(is_workflow_translation_active()) {
        return Workflow_Translation::get_rel_alternate();
    } else {
        return '';
    }
}

/*
 * wpSEO Metaboxen nur für Pages und Posts
 */
add_filter( 'wpseo_add_meta_boxes', 'prefix_wpseo_add_meta_boxes' );
 
function prefix_wpseo_add_meta_boxes() {
    global $post;
    $post_types_without_seo = array( 'event', 'person', 'ad' );
    return !in_array( get_post_type($post), $post_types_without_seo);
} 

/* Newsseiten */
function fau_display_news_teaser($id = 0, $withdate = false) {
    if ($id ==0) return;   
    global $options;
    
    $post = get_post($id);
    $output = '';
    if ($post) {
	$output .= '<article class="news-item">';
	
	$link = get_post_meta( $post->ID, 'external_link', true );
	$external = 0;
	if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
	    $external = 1;
	} else {
	    $link = get_permalink($post->ID);
	}
	
	$output .= "\t<h2>";  
	$output .= '<a ';
	if ($external==1) {
	    $output .= 'class="external" ';
	}
	$output .= 'href="'.$link.'">'.get_the_title($post->ID).'</a>';
	$output .= "</h2>\n";  
	
	
	 $categories = get_the_category();
	    $separator = ', ';
	    $thiscatstr = '';
	    $typestr = '';
	    if($categories){
		$typestr .= '<span class="news-meta-categories fa fa-tag"> ';
		$typestr .= __('Kategorie', 'fau');
		$typestr .= ': ';
		foreach($categories as $category) {
		    $thiscatstr .= '<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
		}
		$typestr .= trim($thiscatstr, $separator);
		$typestr .= '</span> ';
	    }
	    
	
	if ($withdate) {
	    $output .= '<div class="news-meta">'."\n";
	    $output .= $typestr;
	    $output .= '<span class="news-meta-date fa fa-calendar"> '.get_the_date('',$post->ID)."</span>\n";
	    $output .= '</div>'."\n";
	}

	
	$output .= "\t".'<div class="row">'."\n";  
	
	if ((has_post_thumbnail( $post->ID )) ||($options['default_postthumb_always']))  {
	    $output .= "\t\t".'<div class="span3">'."\n"; 
	    $output .= '<a href="'.$link.'" class="news-image';
	    if ($external==1) {
		$output .= ' external';
	    }
	    $output .= '">';

	    $post_thumbnail_id = get_post_thumbnail_id( $post->ID, 'post-thumb' ); 
	    $imagehtml = '';
	    $imgwidth = $options['default_postthumb_width'];
	    $imgheight = $options['default_postthumb_height'];
	    if ($post_thumbnail_id) {
		$sliderimage = wp_get_attachment_image_src( $post_thumbnail_id,  'post-thumb');
		$imageurl = $sliderimage[0]; 	
		$imgwidth = $sliderimage[1];
		$imgheight = $sliderimage[2];
	    }
	    if (!isset($imageurl) || (strlen(trim($imageurl)) <4 )) {
		$imageurl = $options['default_postthumb_src'];
	    }
	    $output .= '<img src="'.fau_esc_url($imageurl).'" width="'.$imgwidth.'" height="'.$imgheight.'" alt="">';
	    $output .= '</a>';
	    
	    $output .= "\t\t".'</div>'."\n"; 
	    $output .= "\t\t".'<div class="span5">'."\n"; 
	} else {
	    $output .= "\t\t".'<div class="span8">'."\n"; 
	}
	$output .= "\t\t\t".'<p>'."\n"; 
	
	
	
	$abstract = get_post_meta( $post->ID, 'abstract', true );
	if (strlen(trim($abstract))<3) {
	   $abstract =  fau_custom_excerpt($post->ID,$options['default_anleser_excerpt_length'],false,'',true);
	}
	$output .= $abstract;

	
	$output .= '<a class="read-more-arrow';
	if ($external==1) {
	    $output .= ' external';
	}
	$output .= '" href="'.$link.'">›</a>'; 
	$output .= "\t\t\t".'</p>'."\n"; 
	
	
	$output .= "\t\t".'</div>'."\n"; 
	$output .= "\t</div> <!-- /row -->\n";	
	$output .= "</article> <!-- /news-item -->\n";	
    }
    return $output;
}



/* 
 * Suchergebnisse 
 */
function fau_display_search_resultitem() {
    global $post;
    global $options;
    
    $output = '';
    $withthumb = $options['search_display_post_thumbnails'];
    $withcats =  $options['search_display_post_cats'];
    if (isset($post) && isset($post->ID)) {
	
	$link = get_post_meta( $post->ID, 'external_link', true );
	$external = 0;
	if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
	    $external = 1;
	} else {
	    $link = fau_make_link_relative(get_permalink($post->ID));
	}
	
	
	$output .= '<article class="search-result">'."\n";
	$output .= "\t<h3><a href=\"".$link."\">".get_the_title()."</a></h3>\n";
	$type = get_post_type();
	if ( $type == 'post') {
	     $typestr = '<div class="search-meta">';

	    $categories = get_the_category();
	    $separator = ', ';
	    $thiscatstr = '';
	    if(($withcats==true) && ($categories)){
		$typestr .= '<span class="post-meta-category fa fa-tag"> ';
		$typestr .= __('Kategorie', 'fau');
		$typestr .= ': ';
		foreach($categories as $category) {
		    $thiscatstr .= '<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
		}
		$typestr .= trim($thiscatstr, $separator);
		$typestr .= '</span> ';
	    }
	    $topevent_date = get_post_meta( $post->ID, 'topevent_date', true );
	    if ($topevent_date) {
		    $typestr .= '<span class="post-meta-date fa fa-calendar"> ';
		    $typestr .= date_i18n( get_option( 'date_format' ), strtotime( $topevent_date ) ); 
		    $typestr .= ' (';
		    $typestr .= __('Veranstaltungshinweis', 'fau');
		    $typestr .= ')';
		    $typestr .= '</span>';
			
	     } else {
		$typestr .= '<span class="post-meta-date fa fa-calendar"> ';
		$typestr .= get_the_date();
		$typestr .= '</span>';
	     }
	    $typestr .= '</div>'."\n";
	    
	} elseif ($type == 'event') {
	    $typestr = '<div class="search-meta">';
	    $typestr .= '<span class="fa fa-calendar-o"> ';
	    $typestr .= __('Veranstaltungshinweis', 'fau');
	    $typestr .= '</span>';
	    $typestr .= '</div>'."\n";
	} else  {
	     $typestr = '';
	}

	if (!empty($typestr)) { 
	     $output .= "\t".$typestr."\n"; 
	}
	$output .= "\t".'<div class="row">'."\n";  
	
	
	if (($withthumb==true) && (has_post_thumbnail( $post->ID )) )  {
	    $output .= "\t\t".'<div class="span3">'."\n"; 
	    $output .= '<a href="'.$link.'" class="news-image';
	    if ($external==1) {
		$output .= ' external';
	    }
	    $output .= '">';

	    $post_thumbnail_id = get_post_thumbnail_id( $post->ID, 'post-thumb' ); 
	    $imagehtml = '';
	    if ($post_thumbnail_id) {
		$sliderimage = wp_get_attachment_image_src( $post_thumbnail_id,  'post-thumb');
		$imageurl = $sliderimage[0]; 	
	    }
	    if (!isset($imageurl) || (strlen(trim($imageurl)) <4 )) {
		$imageurl = $options['default_postthumb_src'];
	    }
	    $output .= '<img src="'.fau_esc_url($imageurl).'" width="'.$options['default_postthumb_width'].'" height="'.$options['default_postthumb_height'].'" alt="">';
	    $output .= '</a>';
	    
	    $output .= "\t\t".'</div>'."\n"; 
	    $output .= "\t\t".'<div class="span5">'."\n"; 
	} else {
	    $output .= "\t\t".'<div class="span8">'."\n"; 
	}
	
	
	
	$output .= "\t\t".'<p>'."\n"; 
	$output .= fau_custom_excerpt($post->ID,$options['default_search_excerpt_length'],false,'',true,$options['search_display_excerpt_morestring']);	
	if ($options['search_display_continue_arrow']) {
	    $output .= '<a class="read-more-arrow';
	    if ($external==1) {
		$output .= ' external';
	    }
	    $output .= '" href="'.$link.'">›</a>'; 
	}
	$output .= "\t\t\t".'</p>'."\n"; 
	$output .= "\t</div> <!-- /row -->\n";	
	$output .= "</article>\n";
    } else {
	$output .= "<!-- empty result -->\n";
    }
    return $output;						     
							
}