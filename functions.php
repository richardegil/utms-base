<?php
/**
 * utms-base functions and definitions
 *
 * @package utms-base
 * @since utms-base 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since utms-base 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'utms_base_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since utms-base 1.0
 */
function utms_base_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	//require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on utms-base, use a find and replace
	 * to change 'utms_base' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'utms_base', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'utms_base' ),
	) );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // utms_base_setup
add_action( 'after_setup_theme', 'utms_base_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since utms-base 1.0
 */
function utms_base_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'utms_base' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'utms_base_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function utms_base_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'utms_base_scripts' );

/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );


/**
 * Hiding Posts and Pages and Other Items from WP Screens & Sticky Bar
 * http://www.instantshift.com/2012/03/06/21-most-useful-wordpress-admin-page-hacks/
 */

function utms_edit_dashboard_items() {
	remove_menu_page('index.php'); // Dashboard
    remove_menu_page('edit.php'); // Posts
    remove_menu_page('upload.php'); // Media
	remove_menu_page('link-manager.php'); // Links
	//remove_menu_page('edit.php?post_type=page'); // Pages
	remove_menu_page('edit-comments.php'); // Comments
	//remove_menu_page('themes.php'); // Appearance
	//remove_menu_page('plugins.php'); // Plugins
	//remove_menu_page('users.php'); // Users
	//remove_menu_page('tools.php'); // Tools
	//remove_menu_page('options-general.php'); // Settings
}


add_action( 'admin_init', 'utms_edit_dashboard_items' );
	
/**
 *Customizing the Admin Bar
 *http://www.onextrapixel.com/2012/02/24/taking-control-of-wordpress-3-0-admin-bar/
 */
 
 function utms_edit_admin_bar() {
    global $wp_admin_bar;
    //$wp_admin_bar->remove_menu('new-content'); 
    // This removes the complete menu “Add New”. You will not require the below “remove_menu” if you using this line.
    $wp_admin_bar->remove_menu('new-post'); 
    // This (when used individually with other “remove menu” lines removed) will hide the menu item “Post”.
    $wp_admin_bar->remove_menu('new-page'); 
    // This (when used individually with other “remove menu” lines removed) will hide the menu item “Page”.
    $wp_admin_bar->remove_menu('new-media'); 
    // This (when used individually with other “remove menu” lines removed) will hide the menu item “Media”.
    $wp_admin_bar->remove_menu('new-link'); 
    // This (when used individually with other “remove menu” lines removed) will hide the menu item “Link”.
    $wp_admin_bar->remove_menu('new-user'); 
    // This (when used individually with other “remove menu” lines removed) will hide the menu item “User”.
    $wp_admin_bar->remove_menu('new-theme'); 
    // This (when used individually with other “remove menu” lines removed) will hide the menu item “Theme”.
    $wp_admin_bar->remove_menu('new-plugin'); 
    // This (when used individually with other “remove menu” lines removed) will hide the menu item “Plugin”.
    $wp_admin_bar->remove_menu('comments');
    // This will remove the Comments Menu
}

add_action( 'wp_before_admin_bar_render', 'utms_edit_admin_bar' );


	
/**
 *CUSTOM POST TYPE 
 */

/**
 *Tutorial from http://wp.tutsplus.com/tutorials/creative-coding/custom-post-type-helper-class/
 *https://github.com/Gizburdt/Wordpress-Cuztom-Helper
 */

include('library/cpt-helper/cuztom_helper.php');  


/**
 *CPT for Physician Profile
 */
 
$physician = register_cuztom_post_type( 
	'physician',
	array(
		'has_archive' => true,
		'supports' => array ('title')
	));
//$physician->add_taxonomy( 'category' );
//$physician->add_taxonomy( 'Keyword' );

$physician->add_meta_box( 
	'Physician Name &amp; Image', 
	array(
		array(
            'name'          => 'physician_fname',
            'label'         => 'First Name',
            'type'          => 'text'
         ),
         array(
            'name'          => 'physician_mname',
            'label'         => 'Middle Name',
            'type'          => 'text'
         ),
         array(
            'name'          => 'physician_lname',
            'label'         => 'Last Name',
            'type'          => 'text'
         ),
          array(
            'name'          => 'physician_nname',
            'label'         => 'Nick Name',
            'description'   => 'Enter nick name if you have one ',
            'type'          => 'text'
         ),
         array(
            'name'          => 'physician_titleacronym',
            'label'         => 'Title Acronyms',
            'description'   => 'Eg: "FACS, MD, PhD" which will be automagically added to your name ',
            'type'          => 'text',
            'repeatable'	=>	true
         ),
		array(
			'name' => 'physician_gender',
			'label' => 'Gender',
			'type' => 'radios',
			'options' => array(
				'male' => 'Male',
				'female' => 'Female'
				)
		),
		array(
            'name'          => 'physician_image',
            'label'         => 'Profile Image',
            'description'   => 'Please use an optimized image of 00px wide x 00px high',
            'type'          => 'image',
         ),
       array(
            'name'          => 'physician_languages',
            'label'         => 'Languages',
            'type'          => 'text',
            'repeatable'	=> true
         ),
	));
	
$physician->add_meta_box( 
	'Interests &amp Appointment Dates', 
	array(
		/*
		*Using CPT-onomies
		*array(
            'name'          => 'physician_specialties',
            'label'         => 'Specialties',
            'type'          => 'select',
            'options' 		=> array(
								'dynamic' => 'Dynamically populated from CPT Specialties'
								),
			'repeatable'	=> true
         ),*/
		array(
            'name'          => 'physician_interests',
            'label'         => 'Areas of Interest',
            'type'          => 'textarea',
         ),
         array(
            'name'          => 'physician_appointmentdate',
            'label'         => 'Appointed Date',
            'type'          => 'date'
         ),
         array(
            'name'          => 'physician_startdate',
            'label'         => 'Start Date',
            'description'	=> 'If different from Appointed Date (This will not be displayed on the profile)',
            'type'          => 'date'
         )
	));
	

$physician->add_meta_box( 
	'Education &amp; Fellowships', 
	array(
		array(
            'name'          => 'physician_graduateschool',
            'label'         => 'Graduate School',
            'description'	=> '(Format: Name of Institution, State)',
            'type'          => 'text',
            'repeatable'	=> true
         ),
		array(
            'name'          => 'physician_residency',
            'label'         => 'Residency',
            'description'	=> '(Format: Name of Institution, State)',
            'type'          => 'text',
            'repeatable'	=> true
         ),
         array(
            'name'          => 'physician_fellowship',
            'label'         => 'Fellowship',
            'description'	=> '(Format: Name of Institution, State)',
            'type'          => 'text',
            'repeatable'	=> true
         )
	));
	
/*
		*Using CPT-onomies
		*$physician->add_meta_box( 
	'Clinics &amp; Locations', 
	array(
		array(
            'name'          => 'physician_locations',
            'label'         => 'Select the Clinic Location',
            //'description'	=> '(Note: This section will be thought out in more detail)',
            'type'          => 'select',
            'options' 		=> array(
								'dynamic' => 'Dynamically populated from CTP locations'
								),
			'repeatable'	=> true
         )
	));

*/


/**
 *CPT for Location
 */

$utplocation = register_cuztom_post_type( 
	'location',
	array(
		'has_archive' => true,
		'supports' => array ('title')
	));
	
$utplocation->add_meta_box( 
	'Location Details', 
	array(
		array(
            'name'          => 'utplocation_name',
            'label'         => 'Physical Address',
            'description'	=> '(Note: This section will be thought out in more detail)',
            'type'          => 'textarea'
         )
	));
	
/**
 *CPT for Specialities
 */

$utpspecialties = register_cuztom_post_type( 
	'specialties',
	array(
		'has_archive' => true,
		'supports' => array ('title')
	));
	
/**
 *CPT FOR CLINICS 
 */

$utpclinic = register_cuztom_post_type( 
	'clinic',
	array(
		'has_archive' => true,
		'supports' => array ('title')
	));
	
$utpclinic->add_meta_box( 
	'Clinic Details', 
	array(
		array(
            'name'          => 'utpclinic_address',
            'label'         => 'Clinic Address',
            'type'          => 'textarea'
         ),
         /*
		*Using CPT-onomies
		*array(
            'name'          => 'utpclinic_location',
            'label'         => 'Select the Clinic Location',
            'type'          => 'select',
            'options' 		=> array(
								'dynamic' => 'Dynamically populated from CTP locations'
								)
         ),*/
         array(
            'name'          => 'utpclinic_phone',
            'label'         => 'Clinic Phone',
            'description'	=> '(The first number is the primary)',
            'type'          => 'text'
         ),
         array(
            'name'          => 'utpclinic_afterhoursphone',
            'label'         => 'Afterhours Phone',
            'type'          => 'text'
         ),
         array(
            'name'          => 'utpclinic_fax',
            'label'         => 'Clinic Fax',
            'type'          => 'text'
         ),
         array(
            'name'          => 'utpclinic_weekdayhours',
            'label'         => 'Clinic Hours M-F',
            'description'	=> '(Format: 8-5, 9-5)',
            'type'          => 'text'
         ),
         array(
            'name'          => 'utpclinic_weekendhours',
            'label'         => 'Clinic Hours Weekends',
            'description'	=> '(Format: Sat 8-5, +Add Sun 9-5)',
            'type'          => 'text',
            'repeatable'	=> true
         )
	));
	

/**
	*CPT-onomies
	http://rachelcarden.com/cpt-onomies/
	*/

/*add_action( 'wp_loaded', 'my_website_register_cpt_onomy' );

function my_website_register_cpt_onomy() {
   global $cpt_onomies_manager;
   $cpt_onomies_manager->register_cpt_onomy( 'Clinic', 'Location', array( 'restrict_user_capabilities' => array( 'administrator' ) ) );
}*/

add_filter( 'custom_post_type_onomies_meta_box_format', 'my_website_custom_post_type_onomies_meta_box_format', 1, 3 );
function my_website_custom_post_type_onomies_meta_box_format( $format, $taxonomy, $post_type ) {
	//return options are autocomplete, checklist and dropdown
   // when editing a post with the post type 'physician',
   // we want to assign the 'specialities' CPT-onomy terms with an autocomplete box
   if ( $post_type == 'physician' && $taxonomy == 'specialties' )
      return 'autocomplete';
   // no matter the post type, we want to assign the 'location' CPT-onomy terms with a select dropdown
   elseif ( $taxonomy == 'location' || 'clinic' ) 
      return 'checklist';
   // no matter the post type, we want to assign the 'directors' CPT-onomy terms with a checklist
   //elseif ( $taxonomy == 'directors' )
      //return 'checklist';
   // WordPress filters must always return a value
   return $format;
}

?>