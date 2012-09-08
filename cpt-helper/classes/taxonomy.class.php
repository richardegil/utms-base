<?php

/**
 * Creates custom taxonomies
 *
 *
 * @author Gijs Jorissen
 * @since 0.2
 *
 */
class Cuztom_Taxonomy
{
	var $taxonomy_name;
	var $taxonomy_labels;
	var $taxonomy_args;
	var $post_type_name;
	
	
	/**
	 * Constructs the class with important vars and method calls
	 * If the taxonomy exists, it will be attached to the post type
	 *
	 * @param string $name
	 * @param string $post_type_name
	 * @param array $args
	 * @param array $labels
	 *
	 * @author Gijs Jorissen
	 * @since 0.2
	 *
	 */
	function __construct( $name, $post_type_name = null, $args = array(), $labels = array() )
	{
		if( ! empty( $name ) )
		{
			$this->post_type_name = $post_type_name;
			
			// Taxonomy properties
			$this->taxonomy_name		= Cuztom::uglify( $name );
			$this->taxonomy_labels		= $labels;
			$this->taxonomy_args		= $args;

			if( ! taxonomy_exists( $this->taxonomy_name ) )
			{
				add_action( 'init', array( &$this, 'register_taxonomy' ) );
			}
			else
			{
				add_action( 'init', array( &$this, 'register_taxonomy_for_object_type' ) );
			}
		}
	}
	
	
	/**
	 * Registers the custom taxonomy with the given arguments
	 *
	 * @author Gijs Jorissen
	 * @since 0.2
	 *
	 */
	function register_taxonomy()
	{
		$name 		= Cuztom::beautify( $this->taxonomy_name );
		$plural 	= Cuztom::pluralize( $name );

		// Default labels, overwrite them with the given labels.
		$labels = array_merge(

			// Default
			array(
				'name' 					=> _x( $plural, 'taxonomy general name', CUZTOM_TEXTDOMAIN ),
				'singular_name' 		=> _x( $name, 'taxonomy singular name', CUZTOM_TEXTDOMAIN ),
			    'search_items' 			=> __( 'Search ' . $plural, CUZTOM_TEXTDOMAIN ),
			    'all_items' 			=> __( 'All ' . $plural, CUZTOM_TEXTDOMAIN ),
			    'parent_item' 			=> __( 'Parent ' . $name, CUZTOM_TEXTDOMAIN ),
			    'parent_item_colon' 	=> __( 'Parent ' . $name . ':', CUZTOM_TEXTDOMAIN ),
			    'edit_item' 			=> __( 'Edit ' . $name, CUZTOM_TEXTDOMAIN ), 
			    'update_item' 			=> __( 'Update ' . $name, CUZTOM_TEXTDOMAIN ),
			    'add_new_item' 			=> __( 'Add New ' . $name, CUZTOM_TEXTDOMAIN ),
			    'new_item_name' 		=> __( 'New ' . $name . ' Name', CUZTOM_TEXTDOMAIN ),
			    'menu_name' 			=> __( $name, CUZTOM_TEXTDOMAIN ),
			),

			// Given labels
			$this->taxonomy_labels

		);

		// Default arguments, overwitten with the given arguments
		$args = array_merge(

			// Default
			array(
				'label'					=> $plural,
				'labels'				=> $labels,
				"hierarchical" 			=> true,
				'public' 				=> true,
				'show_ui' 				=> true,
				'show_in_nav_menus' 	=> true,
				'_builtin' 				=> false,
			),

			// Given
			$this->taxonomy_args

		);
		
		register_taxonomy( $this->taxonomy_name, $this->post_type_name, $args );
	}
	
	
	/**
	 * Used to attach the existing taxonomy to the post type
	 *
	 * @author Gijs Jorissen
	 * @since 0.2
	 *
	 */
	function register_taxonomy_for_object_type()
	{
		register_taxonomy_for_object_type( $this->taxonomy_name, $this->post_type_name );
	}	
}

?>