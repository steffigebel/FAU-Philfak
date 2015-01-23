<?php

/* 
 * Code fuer den Custom Type "ad" / Werbebanner
 */


// Register Custom Post Type
function ad_post_type() {
	
	$labels = array(
		'name'                => __( 'Werbung',  'fau' ),
		'singular_name'       => __( 'Werbung',  'fau' ),
		'menu_name'           => __( 'Werbung', 'fau' ),
		'all_items'          => __( 'Übersicht', 'fau' ),
	);
	$rewrite = array(
		'slug'                => 'ad',
		'with_front'          => true,
		'pages'               => false,
		'feeds'               => false,
	);
	$args = array(
		'description'         => __( 'Werbebanner und Skyscraper erstellen und bearbeiten.', 'fau' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'query_var'           => 'ad',
		'rewrite'             => $rewrite,
		'capability_type'     => 'ad',
		'capabilities' => array(
		    'edit_post' => 'edit_ad',
		    'read_post' => 'read_ad',
		    'delete_post' => 'delete_ad',
		    'edit_posts' => 'edit_ads',
		    'edit_others_posts' => 'edit_others_ads',
		    'publish_posts' => 'publish_ads',
		    'read_private_posts' => 'read_private_ads',
		    'delete_posts' => 'delete_ads',
		    'delete_private_posts' => 'delete_private_ads',
		    'delete_published_posts' => 'delete_published_ads',
		    'delete_others_posts' => 'delete_others_ads',
		    'edit_private_posts' => 'edit_private_ads',
		    'edit_published_posts' => 'edit_published_ads'
		),
		'map_meta_cap' => true
	);
	register_post_type( 'ad', $args );

}

// Hook into the 'init' action
if ( current_user_can('publish_pages') ) {
    add_action( 'init', 'ad_post_type', 0 );
}

function ad_restrict_manage_posts() {
	global $typenow;

	if( $typenow == "ad" ){
		$filters = get_object_taxonomies($typenow);
		
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			wp_dropdown_categories(array(
			    'show_option_all' => sprintf(__('Alle %s anzeigen', 'fau'), $tax_obj->label),
			    'taxonomy' => $tax_slug,
			    'name' => $tax_obj->name,
			    'orderby' => 'name',
			    'selected' => isset($_GET[$tax_slug]) ? $_GET[$tax_slug] : '',
			    'hierarchical' => $tax_obj->hierarchical,
			    'show_count' => true,
			    'hide_if_empty' => true
			));
		}

	}
}
add_action( 'restrict_manage_posts', 'ad_restrict_manage_posts' );


function ad_post_types_admin_order( $wp_query ) {
	if (is_admin()) {

		$post_type = $wp_query->query['post_type'];

		if ( $post_type == 'ad') {
		    if( ! isset($wp_query->query['orderby']))	{
				$wp_query->set('orderby', 'title');
				$wp_query->set('order', 'ASC');
			}

		}
	}
}
add_filter('pre_get_posts', 'ad_post_types_admin_order');





function fau_ad_metabox() {
    add_meta_box(
        'fau_ad_metabox',
        __( 'Eigenschaften', 'fau' ),
        'fau_ad_metabox_content',
        'ad',
        'normal',
        'high'
    );
}
function fau_ad_metabox_content( $object, $box ) { 
    global $defaultoptions;
    global $post;

	
    wp_nonce_field( basename( __FILE__ ), 'fau_ad_metabox_content_nonce' ); 

    if ( !current_user_can( 'edit_page', $object->ID) )
	    // Oder sollten wir nach publish_pages  fragen? 
	    // oder nach der Rolle? vgl. http://docs.appthemes.com/tutorials/wordpress-check-user-role-function/ 
	return;

    
    $targeturl = get_post_meta( $object->ID, 'fauval_ad_url', true );
    $code = get_post_meta( $object->ID, 'fauval_ad_code', true );
    $notiz = get_post_meta( $object->ID, 'fauval_ad_notes', true );
    $position = get_post_meta( $object->ID, 'fauval_ad_position', true );
    if (!$position) {
	$position =0;
    }
    
    /* Old values */
    if (empty($code)) {
	$code  = get_post_meta( $object->ID, 'ad_script', true );
    }
    
    $link  = get_post_meta( $object->ID, 'link', true );

    if (empty($targeturl) && isset($link)) {
	$targeturl = $link;
    }
   			
   
    fau_form_textarea('fauval_ad_code', $code, __('HTML-Code zur Einbindung','fau'),80,6, __('Achtung: Dieser HTML-Code wird nicht auf syntaktische Korrektheit geprüft. Fehler, wie nicht geschlossene HTML-Anweisungen, können die gesamte Website beschädigen und dafür sorgen, daß eine kleine süße Katze irgendwo auf der Welt stirbt.','fau'));
    fau_form_textarea('fauval_ad_notes', $notiz, __('Redaktionelle Notizen','fau'),80,3,__('Hier können redaktionelle Notizen hinterlassen werden. Diese werden nur hier angezeigt.','fau'));

    fau_form_url('fauval_ad_url', $targeturl, __('Webadresse','fau'), __('Sollte kein HTML-Code eingegeben werden sollen, kann alternativ direkt eine Zieladresse und ein Bild aus der Mediathek gewählt werden. Hiermit kann die URL des Zieles eingegeben werden. Als Bild wird das gewählte Beitragsbild verwendet.','fau'), $placeholder='http://');   

    
    fau_form_select('fauval_ad_position', array( '1' => __('Sidebar','fau'), '2' => __('Unterhalb des Inhaltsbereich','fau')), $position, __('Position','fau'), __('Angabe an welchen Positionen der Seite diese Werbung angezeigt werden kann.', 'fau'),1, __('Sidebar und unterhalb des Inhaltsbereich','fau'));
    
    
    // function fau_form_select($name= '', $liste = array(), $prevalue, $labeltext = '',  $howtotext = '', $showempty=1, $emptytext = '' ) {

    
    return;

}


add_action( 'add_meta_boxes', 'fau_ad_metabox' );




function fau_ad_metabox_content_save( $post_id ) {
    global $options;
    if (  'ad'!= get_post_type()  ) {
	return;
    }


	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	return;
	
	
	if ( !isset( $_POST['fau_ad_metabox_content_nonce'] ) || !wp_verify_nonce( $_POST['fau_ad_metabox_content_nonce'], basename( __FILE__ ) ) )
		return $post_id;



	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
		return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
		return;
	}
	
    /* Old values */	
    
    $targeturl = get_post_meta( $post_id, 'fauval_ad_url', true );
    $code = get_post_meta( $post_id, 'fauval_ad_code', true );
    $notiz = get_post_meta($post_id, 'fauval_ad_notes', true );
    $position = get_post_meta( $post_id, 'fauval_ad_position', true );

    
    /* Old values */
    if (empty($code)) {
	$code  = get_post_meta($post_id, 'ad_script', true );
    }
    if (empty($targeturl))  {
	$targeturl = get_post_meta( $post_id, 'link', true );
    }
    
    
    
    $newval =  $_POST['fauval_ad_code'] ;
	
    if (!empty(trim($newval))) {
	if (isset($code)  && ($code != $newval)) {
	    update_post_meta( $post_id, 'fauval_ad_code', $newval );
	} else {
	    add_post_meta( $post_id, 'fauval_ad_code', $newval, true );
	}
    } elseif ($code) {
	delete_post_meta( $post_id, 'fauval_ad_code', $code );	
    } 
    if (get_post_meta( $post_id, 'ad_script', true )) {
	delete_post_meta( $post_id, 'ad_script', $code );
    }

    
    if (filter_var($_POST['fauval_ad_url'], FILTER_VALIDATE_URL)) {
	$newval = $_POST['fauval_ad_url'];
    } else {
	$newval = '';
    }
    
    if (!empty($newval)) {
	    if (isset($targeturl)  && ($targeturl != $newval)) {
		update_post_meta( $post_id, 'fauval_ad_url', $newval );
	    } else {
		add_post_meta( $post_id, 'fauval_ad_url', $newval, true );
	    }
    } else {
	    if ($targeturl) {
		delete_post_meta( $post_id, 'fauval_ad_url', $oldval );	
	    }    
    } 
    if (get_post_meta( $post_id, 'link', true )) {
	    delete_post_meta( $post_id, 'link', $code );
    }
   
    
    $newval = ( isset( $_POST['fauval_ad_notes'] ) ? sanitize_text_field( $_POST['fauval_ad_notes'] ) : 0 );
	
    if (!empty(trim($newval))) {
	if (isset($notiz)  && ($notiz != $newval)) {
	    update_post_meta( $post_id, 'fauval_ad_notes', $newval );
	} else {
	    add_post_meta( $post_id, 'fauval_ad_notes', $newval, true );
	}
    } elseif ($notiz) {
	delete_post_meta( $post_id, 'fauval_ad_notes', $notiz );	
    } 
  
    
     $newval = intval($_POST['fauval_ad_position']) ;
	
    if (!empty(trim($newval))) {
	if (isset($position)  && ($position != $newval)) {
	    update_post_meta( $post_id, 'fauval_ad_position', $newval );
	} else {
	    add_post_meta( $post_id, 'fauval_ad_position', $newval, true );
	}
    } elseif ($position) {
	delete_post_meta( $post_id, 'fauval_ad_position', $position );	
    } 
   
    
}
add_action( 'save_post', 'fau_ad_metabox_content_save' );








function fau_get_ad($type, $withhr = true) {
    global $options;
    global $post;
    
    // werbebanner_seitlich   oder     werbebanner_unten
   $list = get_post_meta( $post->ID, $type, true );
   $class = '';  
   
   if ($type == 'werbebanner_unten') {
	$class = '';
   } else {
        $class = ' fau-werbung-right';
   }
   $out = '';
   
   if ((isset($list)) && (!empty($list))) {
       if (!is_array($list)) {
	    $val = $list;
	    $list = array();
	    $list[0] = $val;
       } 
       
       if ($withhr) {
	   $out .= "<hr>\n";
       }
       
       $out .= '<aside class="fau-werbung'.$class.'" role="region">';
       foreach ($list as $id) {

	    $out .= '<h3>';	    
	    if (isset($options['url_banner-ad-notice'])) {
		$out .= '<a class="banner-ad-notice" href="'.$options['url_banner-ad-notice'].'">';
	    }
	    $out .= $options['title_banner-ad-notice'];
	    if (isset($options['url_banner-ad-notice'])) {
		  $out .= '</a>';
	    }
	    $out .= '</h3>';	   
	    $out .= ' <div class="fau-werbung-content">';
	    $scriptcode = get_post_meta( $id, 'fauval_ad_code', true );
	    if (empty($scriptcode)) {
		$scriptcode = get_post_meta( $id, 'ad_script', true );
	    }
	    
	    if(isset($scriptcode)) {
		
		$out .=  html_entity_decode($scriptcode);
	    } else  {
		$link =    get_post_meta( $id, 'fauval_ad_url', true ); 
		if (empty($link)) {
		    $link =    get_post_meta( $id, 'link', true ); 
		}
		if($link) {
		    $out .=  '<a href="'.get_field('link', $id).'">';
		}
		$out .=  get_the_post_thumbnail($id, 'full');
		if($link) {
		    $out .=  '</a>';
		}
				
	    }
	    $out .= '</div>';

       }
       $out .= '</aside>';
       return $out;

   }
   
}
