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
 * http://www.onextrapixel.com/2012/02/24/taking-control-of-wordpress-3-0-admin-bar/
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
 */
 
 function utms_edit_admin_bar() {
    global $wp_admin_bar;
    //$wp_admin_bar->remove_menu('new-content'); 
    // This removes the complete menu “Add New”. You will not require the below “remove_menu” if you using this line.
    $wp_admin_bar->remove_menu('new-post'); 
    // This (when used individually with other “remove menu” lines removed) will hide the menu item “Post”.
    //$wp_admin_bar->remove_menu('new-page'); 
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
 *CUSTOM DASHBOARD TO SHOW CPT on RIGHT NOW
 *http://wordpress.stackexchange.com/questions/5318/adding-custom-post-type-counts-to-the-dashboard
*/

// Add custom taxonomies and custom post types counts to dashboard
add_action( 'right_now_content_table_end', 'my_add_counts_to_dashboard' );
function my_add_counts_to_dashboard() {
    // Custom post types counts
    $post_types = get_post_types( array( '_builtin' => false ), 'objects' );
    foreach ( $post_types as $post_type ) {
        $num_posts = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name, $num_posts->publish );
        if ( current_user_can( 'edit_posts' ) ) {
            $num = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . '</a>';
            $text = '<a href="edit.php?post_type=' . $post_type->name . '">' . $text . '</a>';
        }
        echo '<td class="first b b-' . $post_type->name . 's">' . $num . '</td>';
        echo '<td class="t ' . $post_type->name . 's">' . $text . '</td>';
        echo '</tr>';

        if ( $num_posts->pending > 0 ) {
            $num = number_format_i18n( $num_posts->pending );
            $text = _n( $post_type->labels->singular_name . ' pending', $post_type->labels->name . ' pending', $num_posts->pending );
            if ( current_user_can( 'edit_posts' ) ) {
                $num = '<a href="edit.php?post_status=pending&post_type=' . $post_type->name . '">' . $num . '</a>';
                $text = '<a href="edit.php?post_status=pending&post_type=' . $post_type->name . '">' . $text . '</a>';
            }
            echo '<td class="first b b-' . $post_type->name . 's">' . $num . '</td>';
            echo '<td class="t ' . $post_type->name . 's">' . $text . '</td>';
            echo '</tr>';
        }
    }
}


	
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
 
$faculty = register_cuztom_post_type( 
	'faculty',
	array(
		'has_archive' => true,
		'supports' => array ('title', 'revisions'),
		'show_in_nav_menus'   => TRUE,
		'show_in_menu'        => TRUE,
		'show_in_nav_menus'   => TRUE
		//'rewrite' => array("slug" => "property") // Permalinks format
	));
//$physician->add_taxonomy( 'category' );
//$physician->add_taxonomy( 'Keyword' );

$faculty->add_meta_box( 
	'Faculty Member Name &amp; Image', 
	array(
		array(
            'name'          => 'faculty_prefixtitle',
            'label'         => 'Prefix Title',
            'description'   => 'Eg: "Dr."',
            'type'          => 'text'
         ),
         array(
            'name'          => 'faculty_fname',
            'label'         => 'First Name',
            'type'          => 'text'
         ),
         array(
            'name'          => 'faculty_mname',
            'label'         => 'Middle Name/Initial',
            'type'          => 'text'
         ),
         array(
            'name'          => 'faculty_lname',
            'label'         => 'Last Name',
            'type'          => 'text'
         ),
          array(
            'name'          => 'faculty_nname',
            'label'         => 'Nick Name',
            'description'   => 'Enter nick name if you have one ',
            'type'          => 'text'
         ),
         array(
            'name'          => 'faculty_title',
            'label'         => 'Title(s)',
            'description'   => 'Eg: "Department Chair, Assistant Professor etc. Primary Title should be first',
            'type'          => 'text',
            'repeatable'	=>	true
         ),
         array(
            'name'          => 'faculty_titleacronym',
            'label'         => 'Title Acronyms',
            'description'   => 'Eg: "FACS, MD, PhD" which will be automagically added to your name ',
            'type'          => 'text',
            'repeatable'	=>	true
         ),
         array(
            'name'          => 'faculty_certification',
            'label'         => 'Board Certifications',
            'description'   => 'Format: American Board of Medical Physics, 2011 ',
            'type'          => 'text',
            'repeatable'	=>	true
         ),
         array(
            'name'          => 'faculty_focus',
            'label'         => 'Professional Focus',
            'type'          => 'text',
            'repeatable'	=>	true
         ),
         array(
            'name'          => 'faculty_interest',
            'label'         => 'Interests',
            'type'          => 'text',
            'repeatable'	=>	true
         ),
		/*array(
			'name' => 'faculty_gender',
			'label' => 'Gender',
			'type' => 'radios',
			'options' => array(
				'male' => 'Male',
				'female' => 'Female'
				)
		),*/
		array(
            'name'          => 'faculty_image',
            'label'         => 'Profile Image',
            'description'   => 'Please use an optimized image of 00px wide x 00px high',
            'type'          => 'image',
         ),
       
	));
	
$faculty->add_meta_box( 
	'UT Health Contact Information (Departmental)', 
	array(
		/*
		*Using CPT-onomies
		*array(
            'name'          => 'faculty_specialties',
            'label'         => 'Specialties',
            'type'          => 'select',
            'options' 		=> array(
								'dynamic' => 'Dynamically populated from CPT Specialties'
								),
			'repeatable'	=> true
         ),*/
		array(
            'name'          => 'faculty_phone',
            'label'         => 'UTHealth Phone Number',
            'type'          => 'text'
         ),
         array(
            'name'          => 'faculty_fax',
            'label'         => 'UTHealth Fax Number',
            'type'          => 'text'
         ),
         array(
            'name'          => 'faculty_email',
            'label'         => 'UTHealth Email Address',
            'type'          => 'text'
         ),
         array(
			'name' => 'faculty_assistant',
			'label' => 'Staff Assistant?',
			'type' => 'radios',
			'options' => array(
				'yes' => 'Yes',
				'no' => 'No'
				)
		),
		array(
            'name'          => 'faculty_assistantname',
            'label'         => 'Name of Assistant',
            'type'          => 'text'
         ),
         array(
            'name'          => 'faculty_assistantphone',
            'label'         => 'Assistant\'s Phone',
            'type'          => 'text'
         ),
         array(
            'name'          => 'faculty_assistantemail',
            'label'         => 'Assistant\'s Email',
            'type'          => 'text'
         )
	));
	
$faculty->add_meta_box( 
	'UT Physicians Information (Clinical Practice)', 
	array(
		/*
		*Using CPT-onomies
		*array(
            'name'          => 'faculty_specialties',
            'label'         => 'Specialties',
            'type'          => 'select',
            'options' 		=> array(
								'dynamic' => 'Dynamically populated from CPT Specialties'
								),
			'repeatable'	=> true
         ),*/
		array(
            'name'          => 'faculty_utp-profile',
            'label'         => 'UTPhysicians Website Profile',
            'type'          => 'text'
         )

	));
	

$faculty->add_meta_box( 
	'Education &amp; Fellowships', 
	array(
		array(
            'name'          => 'faculty_graduateschool',
            'label'         => 'Graduate School',
            'description'	=> '(Format: Name of Institution, State)',
            'type'          => 'text',
            'repeatable'	=> true
         ),
		array(
            'name'          => 'faculty_residency',
            'label'         => 'Residency',
            'description'	=> '(Format: Name of Institution, State)',
            'type'          => 'text',
            'repeatable'	=> true
         ),
         array(
            'name'          => 'faculty_fellowship',
            'label'         => 'Fellowship',
            'description'	=> '(Format: Name of Institution, State)',
            'type'          => 'text',
            'repeatable'	=> true
         )
	));
	
$faculty->add_meta_box( 
	'Research &amp; Publications', 
	array(
		array(
            'name'          => 'faculty_pubmed',
            'label'         => 'Pubmed Link',
            'type'          => 'text'
         ),
         array(
            'name'          => 'faculty_activeresearch',
            'label'         => 'Current Research',
            'type'          => 'textarea',
            'repeatable'	=> true,
         ),
         array(
            'name'          => 'faculty_publication',
            'label'         => 'Selected Publications',
            'type'          => 'textarea',
            'repeatable'	=> true,
         )
	));
	
$faculty->add_meta_box( 
	'Awards &amp; Recognitions', 
	array(
		array(
            'name'          => 'faculty_awards',
            'label'         => 'Selected Awards',
            'type'          => 'textarea',
            'repeatable'	=>	true
         ),
         array(
            'name'          => 'faculty_recognition',
            'label'         => 'Selected Recognitions',
            'type'          => 'textarea',
            'repeatable'	=> true
         )
	));

$faculty->add_meta_box( 
	'Biography', 
	array(
		array(
            'name'          => 'faculty_biography',
            'label'         => 'Short Biography',
            'type'          => 'wysiwyg'
         )
	));
	
$faculty->add_meta_box( 
	'Work Experience &amp; Additional Information', 
	array(
		array(
            'name'          => 'faculty_workexperience',
            'label'         => 'Work Experience(s)',
            'type'          => 'textarea',
            'repeatable'	=>	true
         ),
         array(
            'name'          => 'faculty_license',
            'label'         => 'License',
            'type'          => 'text',
            'repeatable'	=>	true
         ),
         array(
            'name'          => 'faculty_affiliations',
            'label'         => 'Affiliations/Associations',
            'type'          => 'text',
            'repeatable'	=> true
         ),
         array(
            'name'          => 'faculty_additionalinfo',
            'label'         => 'Additional Information',
            'type'          => 'wysiwyg'
         )
	));
	
/**
*CPT FOR CLINICS
*/

$utpclinic = register_cuztom_post_type(
			'clinic',
			array(
			'has_archive' => true,
			'show_in_nav_menus'   => TRUE,
			'supports' => array ('title', 'revisions')
			));

$utpclinic->add_meta_box(
		'Clinic Details',
		array(
		array(
            'name' => 'utpclinic_address',
            'label' => 'Clinic Address',
            'type' => 'textarea'
         ),
         array(
            'name' => 'utpclinic_phone',
            'label' => 'Clinic Phone',
            'description'	=> '(The first number is the primary)',
            'type' => 'text'
         ),
         array(
            'name' => 'utpclinic_fax',
            'label' => 'Clinic Fax',
            'type' => 'text'
         ),
         array(
            'name' => 'utpclinic_afterhoursphone',
            'label' => 'Afterhours Phone',
            'type' => 'text'
         ),
         array(
            'name' => 'utpclinic_weekdayhours',
            'label' => 'Clinic Hours',
            'description'	=> '(Format: Monday 8am-5pm)',
            'type' => 'text',
            'repeatable' => true
         )
        ));

/**
*CPT FOR CLINICS
*/

$news = register_cuztom_post_type(
		'news',
		array(
		'has_archive' => true,
		'show_in_menu'        => TRUE,
		'show_in_nav_menus'   => TRUE,
		'supports' => array ('title', 'editor', 'revisions', 'excerpts', 'comments')
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
   if ( $post_type == 'news' && $taxonomy == 'faculty' )
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