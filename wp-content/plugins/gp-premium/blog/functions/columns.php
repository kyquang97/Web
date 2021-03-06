<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'generate_blog_get_columns' ) ) :
function generate_blog_get_columns()
{
	$generate_blog_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	// If masonry is enabled, set to true
	$columns = ( true == $generate_blog_settings['column_layout'] ) ? true : false;
	
	// If we're not dealing with posts, set it to false	
	$columns = ( 'post' == get_post_type() || is_search() ) ? $columns : false;
	
	// If we're on a singular post or page, disable
	$columns = ( is_singular() ) ? false : $columns;
	
	// Turn off masonry if we're on a WooCommerce search page
	if ( function_exists( 'is_woocommerce' ) ) :
		$columns = ( is_woocommerce() && is_search() ) ? false : $columns;
	endif;
	
	// If masonry is enabled, set to false
	$columns = ( 'true' == $generate_blog_settings['masonry'] ) ? false :  $columns;
	
	// Return the result
	return apply_filters( 'generate_blog_columns', $columns );
}
endif;

if ( ! function_exists( 'generate_blog_add_columns_container' ) ) :
/**
 * Add masonry container
 * @since 1.0
 */
add_action('generate_before_main_content','generate_blog_add_columns_container');
function generate_blog_add_columns_container()
{
	if ( ! generate_blog_get_columns() )
		return;
	
	?>
	<div class="generate-columns-container">
	<?php
}
endif;

if ( ! function_exists( 'generate_blog_add_ending_columns_container' ) ) :
/**
 * Add masonry container
 * @since 1.0
 */
add_action('generate_after_main_content','generate_blog_add_ending_columns_container');
function generate_blog_add_ending_columns_container()
{

	if ( ! generate_blog_get_columns() )
		return;
	
	?>
	</div><!-- .generate-columns-contaier -->
	<?php
}
endif;

if ( ! function_exists( 'generate_blog_columns_css' ) ) :
function generate_blog_columns_css()
{
	$generate_blog_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	if ( function_exists( 'generate_spacing_get_defaults' ) ) :
		$spacing_settings = wp_parse_args( 
			get_option( 'generate_spacing_settings', array() ), 
			generate_spacing_get_defaults() 
		);
	endif;
	
	$separator = ( function_exists('generate_spacing_get_defaults') ) ? $spacing_settings['separator'] : 20;
	
	$return = '';
	if ( generate_blog_get_columns() ) :
		$return .= '.generate-columns {margin-bottom: ' . $separator . 'px;padding-left: ' . $separator . 'px;}';
		$return .= '.generate-columns-container {margin-left: -' . $separator . 'px;}';
		$return .= '.page-header {margin-bottom: ' . $separator . 'px;margin-left: ' . $separator . 'px}';
		$return .= '.separate-containers .generate-columns-container > .paging-navigation {margin-left: ' . $separator . 'px;}';
	endif;
	
	return $return;
}
endif;

if ( ! function_exists( 'generate_blog_get_column_count' ) ) :
function generate_blog_get_column_count()
{
	$generate_blog_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	$count = $generate_blog_settings['columns'];
	
	return apply_filters( 'generate_blog_get_column_count', $count );
}
endif;