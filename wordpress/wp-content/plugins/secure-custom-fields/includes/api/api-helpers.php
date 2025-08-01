<?php

/**
 * This function will return true for a non empty array
 *
 * @since   ACF 5.4.0
 *
 * @param   mixed $array The variable to test.
 * @return  boolean
 */
function acf_is_array( $array ) {
	return ( is_array( $array ) && ! empty( $array ) );
}

/**
 * Alias of acf()->has_setting()
 *
 * @since   ACF 5.6.5
 *
 * @param   string $name Name of the setting to check for.
 * @return  boolean
 */
function acf_has_setting( $name = '' ) {
	return acf()->has_setting( $name );
}

/**
 * acf_raw_setting
 *
 * alias of acf()->get_setting()
 *
 * @since   ACF 5.6.5
 *
 * @param   n/a
 * @return  n/a
 */
function acf_raw_setting( $name = '' ) {
	return acf()->get_setting( $name );
}

/**
 * acf_update_setting
 *
 * alias of acf()->update_setting()
 *
 * @since   ACF 5.0.0
 *
 * @param   $name (string)
 * @param   $value (mixed)
 * @return  n/a
 */
function acf_update_setting( $name, $value ) {
	// validate name.
	$name = acf_validate_setting( $name );

	// update.
	return acf()->update_setting( $name, $value );
}

/**
 * acf_validate_setting
 *
 * Returns the changed setting name if available.
 *
 * @since   ACF 5.6.5
 *
 * @param   n/a
 * @return  n/a
 */
function acf_validate_setting( $name = '' ) {
	return apply_filters( 'acf/validate_setting', $name );
}

/**
 * Alias of acf()->get_setting()
 *
 * @since   ACF 5.0.0
 *
 * @param   string $name  The name of the setting to test.
 * @param string $value An optional default value for the setting if it doesn't exist.
 * @return  n/a
 */
function acf_get_setting( $name, $value = null ) {
	$name = acf_validate_setting( $name );

	// replace default setting value if it exists.
	if ( acf_has_setting( $name ) ) {
		$value = acf_raw_setting( $name );
	}

	// filter.
	$value = apply_filters( "acf/settings/{$name}", $value );

	return $value;
}

/**
 * Return an array of ACF's internal post type names
 *
 * @since ACF 6.1
 * @return array An array of ACF's internal post type names
 */
function acf_get_internal_post_types() {
	return array( 'acf-field-group', 'acf-post-type', 'acf-taxonomy', 'acf-ui-options-page' );
}

/**
 * acf_append_setting
 *
 * This function will add a value into the settings array found in the acf object
 *
 * @since   ACF 5.0.0
 *
 * @param   $name (string)
 * @param   $value (mixed)
 * @return  n/a
 */
function acf_append_setting( $name, $value ) {

	// vars
	$setting = acf_raw_setting( $name );

	// bail early if not array
	if ( ! is_array( $setting ) ) {
		$setting = array();
	}

	// append
	$setting[] = $value;

	// update
	return acf_update_setting( $name, $setting );
}

/**
 * acf_get_data
 *
 * Returns data.
 *
 * @since   ACF 5.0.0
 *
 * @param   string $name
 * @return  mixed
 */
function acf_get_data( $name ) {
	return acf()->get_data( $name );
}

/**
 * acf_set_data
 *
 * Sets data.
 *
 * @since   ACF 5.0.0
 *
 * @param   string $name
 * @param   mixed  $value
 * @return  n/a
 */
function acf_set_data( $name, $value ) {
	return acf()->set_data( $name, $value );
}

/**
 * Appends data to an existing key.
 *
 * @since   ACF 5.9.0
 *
 * @param string $name The data name.
 * @param mixed  $data The data to append to name.
 */
function acf_append_data( $name, $data ) {
	$prev_data = acf()->get_data( $name );
	if ( is_array( $prev_data ) ) {
		$data = array_merge( $prev_data, $data );
	}
	acf()->set_data( $name, $data );
}

/**
 * Alias of acf()->init() - the core ACF init function.
 *
 * @since   ACF 5.0.0
 */
function acf_init() {
	acf()->init();
}

/**
 * acf_has_done
 *
 * This function will return true if this action has already been done
 *
 * @since   ACF 5.3.2
 *
 * @param   $name (string)
 * @return  (boolean)
 */
function acf_has_done( $name ) {

	// return true if already done
	if ( acf_raw_setting( "has_done_{$name}" ) ) {
		return true;
	}

	// update setting and return
	acf_update_setting( "has_done_{$name}", true );
	return false;
}

/**
 * This function will return the path to a file within an external folder
 *
 * @since   ACF 5.5.8
 *
 * @param   string $file Directory path.
 * @param   string $path Optional file path.
 * @return  string File path.
 */
function acf_get_external_path( $file, $path = '' ) {
	return plugin_dir_path( $file ) . $path;
}

/**
 * This function will return the url to a file within an internal ACF folder
 *
 * @since   ACF 5.5.8
 *
 * @param   string $file Directory path.
 * @param   string $path Optional file path.
 * @return  string File path.
 */
function acf_get_external_dir( $file, $path = '' ) {
	return acf_plugin_dir_url( $file ) . $path;
}

/**
 * This function will calculate the url to a plugin folder.
 * Different to the WP plugin_dir_url(), this function can calculate for urls outside of the plugins folder (theme include).
 *
 * @since   ACF 5.6.8
 *
 * @param   string $file A file path inside the ACF plugin to get the plugin directory path from.
 * @return  string The plugin directory path.
 */
function acf_plugin_dir_url( $file ) {
	$path = plugin_dir_path( $file );
	$path = wp_normalize_path( $path );

	// check plugins.
	$check_path = wp_normalize_path( realpath( WP_PLUGIN_DIR ) );
	if ( strpos( $path, $check_path ) === 0 ) {
		return str_replace( $check_path, plugins_url(), $path );
	}

	// check wp-content.
	$check_path = wp_normalize_path( realpath( WP_CONTENT_DIR ) );
	if ( strpos( $path, $check_path ) === 0 ) {
		return str_replace( $check_path, content_url(), $path );
	}

	// check root.
	$check_path = wp_normalize_path( realpath( ABSPATH ) );
	if ( strpos( $path, $check_path ) === 0 ) {
		return str_replace( $check_path, site_url( '/' ), $path );
	}

	// return.
	return plugin_dir_url( $file );
}

/**
 * This function will merge together 2 arrays and also convert any numeric values to ints
 *
 * @since   ACF 5.0.0
 *
 * @param   array $args     The configured arguments array.
 * @param   array $defaults The default properties for the passed args to inherit.
 * @return  array $args Parsed arguments with defaults applied.
 */
function acf_parse_args( $args, $defaults = array() ) {
	$args = wp_parse_args( $args, $defaults );

	// parse types
	$args = acf_parse_types( $args );

	return $args;
}

/**
 * acf_parse_types
 *
 * This function will convert any numeric values to int and trim strings
 *
 * @since   ACF 5.0.0
 *
 * @param   $var (mixed)
 * @return  $var (mixed)
 */
function acf_parse_types( $array ) {
	return array_map( 'acf_parse_type', $array );
}

/**
 * acf_parse_type
 *
 * description
 *
 * @since   ACF 5.0.9
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_parse_type( $v ) {

	// Check if is string.
	if ( is_string( $v ) ) {

		// Trim ("Word " = "Word").
		$v = trim( $v );

		// Convert int strings to int ("123" = 123).
		if ( is_numeric( $v ) && strval( intval( $v ) ) === $v ) {
			$v = intval( $v );
		}
	}

	// return.
	return $v;
}

/**
 * This function will load in a file from the 'admin/views' folder and allow variables to be passed through
 *
 * @since   ACF 5.0.0
 *
 * @param string $view_path
 * @param array  $view_args
 */
function acf_get_view( $view_path = '', $view_args = array() ) {
	// allow view file name shortcut
	if ( substr( $view_path, -4 ) !== '.php' ) {
		$view_path = acf_get_path( "includes/admin/views/{$view_path}.php" );
	}

	// include
	if ( file_exists( $view_path ) ) {
		// Use `EXTR_SKIP` here to prevent `$view_path` from being accidentally/maliciously overridden.
		extract( $view_args, EXTR_SKIP );
		include $view_path;
	}
}

/**
 * acf_merge_atts
 *
 * description
 *
 * @since   ACF 5.0.9
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_merge_atts( $atts, $extra = array() ) {

	// bail early if no $extra
	if ( empty( $extra ) ) {
		return $atts;
	}

	// trim
	$extra = array_map( 'trim', $extra );
	$extra = array_filter( $extra );

	// merge in new atts
	foreach ( $extra as $k => $v ) {

		// append
		if ( $k == 'class' || $k == 'style' ) {
			$atts[ $k ] .= ' ' . $v;

			// merge
		} else {
			$atts[ $k ] = $v;
		}
	}

	return $atts;
}

/**
 * This function will create and echo a basic nonce input
 *
 * @since   ACF 5.6.0
 *
 * @param string $nonce The nonce parameter string.
 */
function acf_nonce_input( $nonce = '' ) {
	echo '<input type="hidden" name="_acf_nonce" value="' . esc_attr( wp_create_nonce( $nonce ) ) . '" />';
}

/**
 * This function will remove the var from the array, and return the var
 *
 * @since   ACF 5.0.0
 *
 * @param array  $extract_array an array passed as reference to be extracted.
 * @param string $key           The key to extract from the array.
 * @param mixed  $default_value The default value if it doesn't exist in the extract array.
 * @return mixed Extracted var or default.
 */
function acf_extract_var( &$extract_array, $key, $default_value = null ) {
	// check if exists - uses array_key_exists to extract NULL values (isset will fail).
	if ( is_array( $extract_array ) && array_key_exists( $key, $extract_array ) ) {

		// store and unset value.
		$v = $extract_array[ $key ];
		unset( $extract_array[ $key ] );

		return $v;
	}

	return $default_value;
}

/**
 * This function will remove the vars from the array, and return the vars
 *
 * @since   ACF 5.0.0
 *
 * @param array $extract_array an array passed as reference to be extracted.
 * @param array $keys          An array of keys to extract from the original array.
 * @return array An array of extracted values.
 */
function acf_extract_vars( &$extract_array, $keys ) {
	$r = array();

	foreach ( $keys as $key ) {
		$r[ $key ] = acf_extract_var( $extract_array, $key );
	}

	return $r;
}

/**
 * acf_get_sub_array
 *
 * This function will return a sub array of data
 *
 * @since   ACF 5.3.2
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_get_sub_array( $array, $keys ) {

	$r = array();

	foreach ( $keys as $key ) {
		$r[ $key ] = $array[ $key ];
	}

	return $r;
}

/**
 * Returns an array of post type names.
 *
 * @since   ACF 5.0.0
 *
 * @param array $args Optional. An array of key => value arguments to match against the post type objects. Default empty array.
 * @return array A list of post type names.
 */
function acf_get_post_types( $args = array() ) {
	$post_types = array();

	// extract special arg
	$exclude   = acf_extract_var( $args, 'exclude', array() );
	$exclude[] = 'acf-field';
	$exclude[] = 'acf-field-group';
	$exclude[] = 'acf-post-type';
	$exclude[] = 'acf-taxonomy';
	$exclude[] = 'acf-ui-options-page';

	// Get post type objects.
	$objects = get_post_types( $args, 'objects' );

	foreach ( $objects as $i => $object ) {
		// Bail early if is exclude.
		if ( in_array( $i, $exclude ) ) {
			continue;
		}

		// Bail early if is builtin (WP) private post type
		// i.e. nav_menu_item, revision, customize_changeset, etc.
		if ( $object->_builtin && ! $object->public ) {
			continue;
		}

		$post_types[] = $i;
	}

	return apply_filters( 'acf/get_post_types', $post_types, $args );
}

function acf_get_pretty_post_types( $post_types = array() ) {

	// get post types
	if ( empty( $post_types ) ) {

		// get all custom post types
		$post_types = acf_get_post_types();
	}

	// get labels
	$ref = array();
	$r   = array();

	foreach ( $post_types as $post_type ) {

		// vars
		$label = acf_get_post_type_label( $post_type );

		// append to r
		$r[ $post_type ] = $label;

		// increase counter
		if ( ! isset( $ref[ $label ] ) ) {
			$ref[ $label ] = 0;
		}

		++$ref[ $label ];
	}

	// get slugs
	foreach ( array_keys( $r ) as $i ) {

		// vars
		$post_type = $r[ $i ];

		if ( $ref[ $post_type ] > 1 ) {
			$r[ $i ] .= ' (' . $i . ')';
		}
	}

	// return
	return $r;
}

/**
 * Function acf_get_post_stati()
 *
 * Returns an array of post status names.
 *
 * @since   ACF 6.1.0
 *
 * @param   array $args Optional. An array of key => value arguments to match against the post status objects. Default empty array.
 * @return  array A list of post status names.
 */
function acf_get_post_stati( $args = array() ) {

	$args['internal'] = false;

	$post_statuses = get_post_stati( $args );

	unset( $post_statuses['acf-disabled'] );

	$post_statuses = (array) apply_filters( 'acf/get_post_stati', $post_statuses, $args );

	return $post_statuses;
}
/**
 * Function acf_get_pretty_post_statuses()
 *
 * Returns a clean array of post status names.
 *
 * @since   ACF 6.1.0
 *
 * @param   array $post_statuses Optional. An array of post status objects. Default empty array.
 * @return  array An array of post status names.
 */
function acf_get_pretty_post_statuses( $post_statuses = array() ) {

	// Get all post statuses.
	$post_statuses = array_merge( $post_statuses, acf_get_post_stati() );

	$ref    = array();
	$result = array();

	foreach ( $post_statuses as $post_status ) {
		$label = acf_get_post_status_label( $post_status );

		$result[ $post_status ] = $label;

		if ( ! isset( $ref[ $label ] ) ) {
			$ref[ $label ] = 0;
		}

		++$ref[ $label ];
	}

	foreach ( array_keys( $result ) as $i ) {
		$post_status = $result[ $i ];

		if ( $ref[ $post_status ] > 1 ) {
			$result[ $i ] .= ' (' . $i . ')';
		}
	}

	return $result;
}

/**
 * acf_get_post_type_label
 *
 * This function will return a pretty label for a specific post_type
 *
 * @since   ACF 5.4.0
 *
 * @param   $post_type (string)
 * @return  (string)
 */
function acf_get_post_type_label( $post_type ) {

	// vars
	$label = $post_type;

	// check that object exists
	// - case exists when importing field group from another install and post type does not exist
	if ( post_type_exists( $post_type ) ) {
		$obj   = get_post_type_object( $post_type );
		$label = $obj->labels->singular_name;
	}

	// return
	return $label;
}

/**
 * Function acf_get_post_status_label()
 *
 * This function will return a pretty label for a specific post_status
 *
 * @since   ACF 6.1.0
 *
 * @param   string $post_status The post status.
 * @return  string The post status label.
 */
function acf_get_post_status_label( $post_status ) {
	$label = $post_status;
	$obj   = get_post_status_object( $post_status );
	$label = is_object( $obj ) ? $obj->label : '';

	return $label;
}

/**
 * acf_verify_nonce
 *
 * This function will look at the $_POST['_acf_nonce'] value and return true or false
 *
 * @since   ACF 5.0.0
 *
 * @param   $nonce (string)
 * @return  (boolean)
 */
function acf_verify_nonce( $value ) {

	// vars
	$nonce = acf_maybe_get_POST( '_acf_nonce' );

	// bail early nonce does not match (post|user|comment|term)
	if ( ! $nonce || ! wp_verify_nonce( $nonce, $value ) ) {
		return false;
	}

	// reset nonce (only allow 1 save)
	$_POST['_acf_nonce'] = false;

	// return
	return true;
}

/**
 * Returns true if the current AJAX request is valid.
 * It's action will also allow WPML to set the lang and avoid AJAX get_posts issues
 *
 * @since   ACF 5.2.3
 *
 * @param string $nonce  The nonce to check.
 * @param string $action The action of the nonce.
 * @param bool   $action_is_field Whether the action is a field key or not. Defaults to false.
 * @return boolean
 */
function acf_verify_ajax( $nonce = '', $action = '', $action_is_field = false ) {

	// Bail early if we don't have a nonce to check.
	if ( empty( $nonce ) && empty( $_REQUEST['nonce'] ) ) {
		return false;
	}

	// Build the action if we're trying to validate a specific field nonce.
	if ( $action_is_field ) {
		if ( ! acf_is_field_key( $action ) ) {
			return false;
		}

		$field = acf_get_field( $action );

		if ( empty( $field['type'] ) ) {
			return false;
		}

		$action = 'acf_field_' . $field['type'] . '_' . $action;
	}

	$nonce_to_check = ! empty( $nonce ) ? $nonce : $_REQUEST['nonce']; // phpcs:ignore WordPress.Security -- We're verifying a nonce here.
	$nonce_action   = ! empty( $action ) ? $action : 'acf_nonce';

	// Bail if nonce can't be verified.
	if ( ! wp_verify_nonce( sanitize_text_field( $nonce_to_check ), $nonce_action ) ) {
		return false;
	}

	// Action for 3rd party customization (WPML).
	do_action( 'acf/verify_ajax' );

	return true;
}

/**
 * acf_get_image_sizes
 *
 * This function will return an array of available image sizes
 *
 * @since   ACF 5.0.0
 *
 * @param   n/a
 * @return  (array)
 */
function acf_get_image_sizes() {

	// vars
	$sizes = array(
		'thumbnail' => __( 'Thumbnail', 'secure-custom-fields' ),
		'medium'    => __( 'Medium', 'secure-custom-fields' ),
		'large'     => __( 'Large', 'secure-custom-fields' ),
	);

	// find all sizes
	$all_sizes = get_intermediate_image_sizes();

	// add extra registered sizes
	if ( ! empty( $all_sizes ) ) {
		foreach ( $all_sizes as $size ) {

			// bail early if already in array
			if ( isset( $sizes[ $size ] ) ) {
				continue;
			}

			// append to array
			$label          = str_replace( '-', ' ', $size );
			$label          = ucwords( $label );
			$sizes[ $size ] = $label;
		}
	}

	// add sizes
	foreach ( array_keys( $sizes ) as $s ) {

		// vars
		$data = acf_get_image_size( $s );

		// append
		if ( $data['width'] && $data['height'] ) {
			$sizes[ $s ] .= ' (' . $data['width'] . ' x ' . $data['height'] . ')';
		}
	}

	// add full end
	$sizes['full'] = __( 'Full Size', 'secure-custom-fields' );

	// filter for 3rd party customization
	$sizes = apply_filters( 'acf/get_image_sizes', $sizes );

	// return
	return $sizes;
}

function acf_get_image_size( $s = '' ) {

	// global
	global $_wp_additional_image_sizes;

	// rename for nicer code
	$_sizes = $_wp_additional_image_sizes;

	// vars
	$data = array(
		'width'  => isset( $_sizes[ $s ]['width'] ) ? $_sizes[ $s ]['width'] : get_option( "{$s}_size_w" ),
		'height' => isset( $_sizes[ $s ]['height'] ) ? $_sizes[ $s ]['height'] : get_option( "{$s}_size_h" ),
	);

	// return
	return $data;
}

/**
 * acf_version_compare
 *
 * Similar to the version_compare() function but with extra functionality.
 *
 * @since   ACF 5.5.0
 *
 * @param   string $left    The left version number.
 * @param   string $compare The compare operator.
 * @param   string $right   The right version number.
 * @return  boolean
 */
function acf_version_compare( $left = '', $compare = '>', $right = '' ) {

	// Detect 'wp' placeholder.
	if ( $left === 'wp' ) {
		global $wp_version;
		$left = $wp_version;
	}

	// Return result.
	return version_compare( $left, $right, $compare );
}

/**
 * acf_get_full_version
 *
 * This function will remove any '-beta1' or '-RC1' strings from a version
 *
 * @since   ACF 5.5.0
 *
 * @param   $version (string)
 * @return  (string)
 */
function acf_get_full_version( $version = '1' ) {

	// remove '-beta1' or '-RC1'
	if ( $pos = strpos( $version, '-' ) ) {
		$version = substr( $version, 0, $pos );
	}

	// return
	return $version;
}

/**
 * acf_get_terms
 *
 * This function is a wrapper for the get_terms() function
 *
 * @since   ACF 5.4.0
 *
 * @param   $args (array)
 * @return  (array)
 */
function acf_get_terms( $args ) {

	// defaults
	$args = wp_parse_args(
		$args,
		array(
			'taxonomy'               => null,
			'hide_empty'             => false,
			'update_term_meta_cache' => false,
		)
	);

	// return
	return get_terms( $args );
}

/**
 * acf_get_taxonomy_terms
 *
 * This function will return an array of available taxonomy terms
 *
 * @since   ACF 5.0.0
 *
 * @param   $taxonomies (array)
 * @return  (array)
 */
function acf_get_taxonomy_terms( $taxonomies = array() ) {

	// force array
	$taxonomies = acf_get_array( $taxonomies );

	// get pretty taxonomy names
	$taxonomies = acf_get_pretty_taxonomies( $taxonomies );

	// vars
	$r = array();

	// populate $r
	foreach ( array_keys( $taxonomies ) as $taxonomy ) {

		// vars
		$label           = $taxonomies[ $taxonomy ];
		$is_hierarchical = is_taxonomy_hierarchical( $taxonomy );
		$terms           = acf_get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		// bail early i no terms
		if ( empty( $terms ) ) {
			continue;
		}

		// sort into hierarchical order!
		if ( $is_hierarchical ) {
			$terms = _get_term_children( 0, $terms, $taxonomy );
		}

		// add placeholder
		$r[ $label ] = array();

		// add choices
		foreach ( $terms as $term ) {
			$k                 = "{$taxonomy}:{$term->slug}";
			$r[ $label ][ $k ] = acf_get_term_title( $term );
		}
	}

	// return
	return $r;
}

/**
 * acf_decode_taxonomy_terms
 *
 * This function decodes the $taxonomy:$term strings into a nested array
 *
 * @since   ACF 5.0.0
 *
 * @param   $terms (array)
 * @return  (array)
 */
function acf_decode_taxonomy_terms( $strings = false ) {

	// bail early if no terms
	if ( empty( $strings ) ) {
		return false;
	}

	// vars
	$terms = array();

	// loop
	foreach ( $strings as $string ) {

		// vars
		$data     = acf_decode_taxonomy_term( $string );
		$taxonomy = $data['taxonomy'];
		$term     = $data['term'];

		// create empty array
		if ( ! isset( $terms[ $taxonomy ] ) ) {
			$terms[ $taxonomy ] = array();
		}

		// append
		$terms[ $taxonomy ][] = $term;
	}

	// return
	return $terms;
}

/**
 * acf_decode_taxonomy_term
 *
 * This function will return the taxonomy and term slug for a given value
 *
 * @since   ACF 5.0.0
 *
 * @param   $string (string)
 * @return  (array)
 */
function acf_decode_taxonomy_term( $value ) {

	// vars
	$data = array(
		'taxonomy' => '',
		'term'     => '',
	);

	// int
	if ( is_numeric( $value ) ) {
		$data['term'] = $value;

		// string
	} elseif ( is_string( $value ) ) {
		$value            = explode( ':', $value );
		$data['taxonomy'] = isset( $value[0] ) ? $value[0] : '';
		$data['term']     = isset( $value[1] ) ? $value[1] : '';

		// error
	} else {
		return false;
	}

	// allow for term_id (Used by ACF v4)
	if ( is_numeric( $data['term'] ) ) {

		// global
		global $wpdb;

		// find taxonomy
		if ( ! $data['taxonomy'] ) {
			$data['taxonomy'] = $wpdb->get_var( $wpdb->prepare( "SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_id = %d LIMIT 1", $data['term'] ) );
		}

		// find term (may have numeric slug '123')
		$term = get_term_by( 'slug', $data['term'], $data['taxonomy'] );

		// attempt get term via ID (ACF4 uses ID)
		if ( ! $term ) {
			$term = get_term( $data['term'], $data['taxonomy'] );
		}

		// bail early if no term
		if ( ! $term ) {
			return false;
		}

		// update
		$data['taxonomy'] = $term->taxonomy;
		$data['term']     = $term->slug;
	}

	// return
	return $data;
}

/**
 * acf_array
 *
 * Casts the value into an array.
 *
 * @since   ACF 5.7.10
 *
 * @param   mixed $val The value to cast.
 * @return  array
 */
function acf_array( $val = array() ) {
	return (array) $val;
}

/**
 * Returns a non-array value.
 *
 * @since   ACF 5.8.10
 *
 * @param   mixed $val The value to review.
 * @return  mixed
 */
function acf_unarray( $val ) {
	if ( is_array( $val ) ) {
		return reset( $val );
	}
	return $val;
}

/**
 * acf_get_array
 *
 * This function will force a variable to become an array
 *
 * @since   ACF 5.0.0
 *
 * @param   $var (mixed)
 * @return  (array)
 */
function acf_get_array( $var = false, $delimiter = '' ) {

	// array
	if ( is_array( $var ) ) {
		return $var;
	}

	// bail early if empty
	if ( acf_is_empty( $var ) ) {
		return array();
	}

	// string
	if ( is_string( $var ) && $delimiter ) {
		return explode( $delimiter, $var );
	}

	// place in array
	return (array) $var;
}

/**
 * acf_get_numeric
 *
 * This function will return numeric values
 *
 * @since   ACF 5.4.0
 *
 * @param   $value (mixed)
 * @return  (mixed)
 */
function acf_get_numeric( $value = '' ) {

	// vars
	$numbers  = array();
	$is_array = is_array( $value );

	// loop
	foreach ( (array) $value as $v ) {
		if ( is_numeric( $v ) ) {
			$numbers[] = (int) $v;
		}
	}

	// bail early if is empty
	if ( empty( $numbers ) ) {
		return false;
	}

	// convert array
	if ( ! $is_array ) {
		$numbers = $numbers[0];
	}

	// return
	return $numbers;
}

/**
 * acf_get_posts
 *
 * Similar to the get_posts() function but with extra functionality.
 *
 * @since   ACF 5.1.5
 *
 * @param   array $args The query args.
 * @return  array
 */
function acf_get_posts( $args = array() ) {

	// Vars.
	$posts = array();

	// Apply default args.
	$args = wp_parse_args(
		$args,
		array(
			'posts_per_page'         => -1,
			'post_type'              => '',
			'post_status'            => 'any',
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	// Avoid default 'post' post_type by providing all public types.
	if ( ! $args['post_type'] ) {
		$args['post_type'] = acf_get_post_types();
	}

	if ( ! $args['post_status'] ) {
		$args['post_status'] = acf_get_post_stati();
	}

	// Check if specific post IDs have been provided.
	if ( $args['post__in'] ) {

		// Clean value into an array of IDs.
		$args['post__in'] = array_map( 'intval', acf_array( $args['post__in'] ) );
	}

	/**
	 * Filters the args used in `acf_get_posts()` that are passed to `get_posts()`.
	 *
	 * @since ACF 6.1.7
	 *
	 * @param array $args The args passed to `get_posts()`.
	 */
	$args = apply_filters( 'acf/acf_get_posts/args', $args );

	// Query posts.
	$posts = get_posts( $args );

	// Remove any potential empty results.
	$posts = array_filter( $posts );

	// Manually order results.
	if ( $posts && $args['post__in'] ) {
		$order = array();
		foreach ( $posts as $i => $post ) {
			$order[ $i ] = array_search( $post->ID, $args['post__in'] );
		}
		array_multisort( $order, $posts );
	}

	/**
	 * Filters the results found in the `acf_get_posts()` function.
	 *
	 * @since ACF 6.1.7
	 *
	 * @param array $posts The results from the `get_posts()` call.
	 */
	return apply_filters( 'acf/acf_get_posts/results', $posts );
}

/**
 * _acf_query_remove_post_type
 *
 * This function will remove the 'wp_posts.post_type' WHERE clause completely
 * When using 'post__in', this clause is unnecessary and slow.
 *
 * @since   ACF 5.1.5
 *
 * @param   $sql (string)
 * @return  $sql
 */
function _acf_query_remove_post_type( $sql ) {

	// global
	global $wpdb;

	// bail early if no 'wp_posts.ID IN'
	if ( strpos( $sql, "$wpdb->posts.ID IN" ) === false ) {
		return $sql;
	}

	// get bits
	$glue = 'AND';
	$bits = explode( $glue, $sql );

	// loop through $where and remove any post_type queries
	foreach ( $bits as $i => $bit ) {
		if ( strpos( $bit, "$wpdb->posts.post_type" ) !== false ) {
			unset( $bits[ $i ] );
		}
	}

	// join $where back together
	$sql = implode( $glue, $bits );

	// return
	return $sql;
}

/**
 * acf_get_grouped_posts
 *
 * This function will return all posts grouped by post_type
 * This is handy for select settings
 *
 * @since   ACF 5.0.0
 *
 * @param   $args (array)
 * @return  (array)
 */
function acf_get_grouped_posts( $args ) {

	// vars
	$data = array();

	// defaults
	$args = wp_parse_args(
		$args,
		array(
			'posts_per_page'         => -1,
			'paged'                  => 0,
			'post_type'              => 'post',
			'orderby'                => 'menu_order title',
			'order'                  => 'ASC',
			'post_status'            => 'any',
			'suppress_filters'       => false,
			'update_post_meta_cache' => false,
		)
	);

	// find array of post_type
	$post_types          = acf_get_array( $args['post_type'] );
	$post_types_labels   = acf_get_pretty_post_types( $post_types );
	$is_single_post_type = ( count( $post_types ) == 1 );

	// attachment doesn't work if it is the only item in an array
	if ( $is_single_post_type ) {
		$args['post_type'] = reset( $post_types );
	}

	// add filter to orderby post type
	if ( ! $is_single_post_type ) {
		add_filter( 'posts_orderby', '_acf_orderby_post_type', 10, 2 );
	}

	// get posts
	$posts = get_posts( $args );

	// remove this filter (only once)
	if ( ! $is_single_post_type ) {
		remove_filter( 'posts_orderby', '_acf_orderby_post_type', 10 );
	}

	// loop
	foreach ( $post_types as $post_type ) {

		// vars
		$this_posts = array();
		$this_group = array();

		// populate $this_posts
		foreach ( $posts as $post ) {
			if ( $post->post_type == $post_type ) {
				$this_posts[] = $post;
			}
		}

		// bail early if no posts for this post type
		if ( empty( $this_posts ) ) {
			continue;
		}

		// sort into hierarchical order!
		// this will fail if a search has taken place because parents wont exist
		if ( is_post_type_hierarchical( $post_type ) && empty( $args['s'] ) ) {

			// vars
			$post_id   = $this_posts[0]->ID;
			$parent_id = acf_maybe_get( $args, 'post_parent', 0 );
			$offset    = 0;
			$length    = count( $this_posts );

			// get all posts from this post type
			$all_posts = get_posts(
				array_merge(
					$args,
					array(
						'posts_per_page' => -1,
						'paged'          => 0,
						'post_type'      => $post_type,
					)
				)
			);

			// find starting point (offset)
			foreach ( $all_posts as $i => $post ) {
				if ( $post->ID == $post_id ) {
					$offset = $i;
					break;
				}
			}

			// order posts
			$ordered_posts = get_page_children( $parent_id, $all_posts );

			// compare array lengths
			// if $ordered_posts is smaller than $all_posts, WP has lost posts during the get_page_children() function
			// this is possible when get_post( $args ) filter out parents (via taxonomy, meta and other search parameters)
			if ( count( $ordered_posts ) == count( $all_posts ) ) {
				$this_posts = array_slice( $ordered_posts, $offset, $length );
			}
		}

		// populate $this_posts
		foreach ( $this_posts as $post ) {
			$this_group[ $post->ID ] = $post;
		}

		// group by post type
		$label          = $post_types_labels[ $post_type ];
		$data[ $label ] = $this_group;
	}

	// return
	return $data;
}

/**
 * The internal ACF function to add order by post types for use in `acf_get_grouped_posts`
 *
 * @param string $orderby  The current orderby value for a query.
 * @param object $wp_query The WP_Query.
 * @return string The potentially modified orderby string.
 */
function _acf_orderby_post_type( $orderby, $wp_query ) {
	global $wpdb;

	$post_types = $wp_query->get( 'post_type' );

	// Prepend the SQL.
	if ( is_array( $post_types ) ) {
		$post_types = array_map( 'esc_sql', $post_types );
		$post_types = implode( "','", $post_types );
		$orderby    = "FIELD({$wpdb->posts}.post_type,'$post_types')," . $orderby;
	}

	return $orderby;
}

function acf_get_post_title( $post = 0, $is_search = false ) {

	// vars
	$post    = get_post( $post );
	$title   = '';
	$prepend = '';
	$append  = '';

	// bail early if no post
	if ( ! $post ) {
		return '';
	}

	// title
	$title = get_the_title( $post->ID );

	// empty
	if ( $title === '' ) {
		$title = __( '(no title)', 'secure-custom-fields' );
	}

	// status
	if ( get_post_status( $post->ID ) != 'publish' ) {
		$append .= ' (' . get_post_status( $post->ID ) . ')';
	}

	// ancestors
	if ( $post->post_type !== 'attachment' ) {

		// get ancestors
		$ancestors = get_ancestors( $post->ID, $post->post_type );
		$prepend  .= str_repeat( '- ', count( $ancestors ) );
	}

	// merge
	$title = $prepend . $title . $append;

	// return
	return $title;
}

function acf_order_by_search( $array, $search ) {

	// vars
	$weights = array();
	$needle  = strtolower( $search );

	// add key prefix
	foreach ( array_keys( $array ) as $k ) {
		$array[ '_' . $k ] = acf_extract_var( $array, $k );
	}

	// add search weight
	foreach ( $array as $k => $v ) {

		// vars
		$weight   = 0;
		$haystack = strtolower( $v );
		$strpos   = strpos( $haystack, $needle );

		// detect search match
		if ( $strpos !== false ) {

			// set weight to length of match
			$weight = strlen( $search );

			// increase weight if match starts at beginning of string
			if ( $strpos == 0 ) {
				++$weight;
			}
		}

		// append to wights
		$weights[ $k ] = $weight;
	}

	// sort the array with menu_order ascending
	array_multisort( $weights, SORT_DESC, $array );

	// remove key prefix
	foreach ( array_keys( $array ) as $k ) {
		$array[ substr( $k, 1 ) ] = acf_extract_var( $array, $k );
	}

	// return
	return $array;
}

/**
 * acf_get_pretty_user_roles
 *
 * description
 *
 * @since   ACF 5.3.2
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_get_pretty_user_roles( $allowed = false ) {

	// vars
	$editable_roles = get_editable_roles();
	$allowed        = acf_get_array( $allowed );
	$roles          = array();

	// loop
	foreach ( $editable_roles as $role_name => $role_details ) {

		// bail early if not allowed
		if ( ! empty( $allowed ) && ! in_array( $role_name, $allowed ) ) {
			continue;
		}

		// append
		$roles[ $role_name ] = translate_user_role( $role_details['name'] );
	}

	// return
	return $roles;
}

/**
 * acf_get_grouped_users
 *
 * This function will return all users grouped by role
 * This is handy for select settings
 *
 * @since   ACF 5.0.0
 *
 * @param   $args (array)
 * @return  (array)
 */
function acf_get_grouped_users( $args = array() ) {

	// vars
	$r = array();

	// defaults
	$args = wp_parse_args(
		$args,
		array(
			'users_per_page' => -1,
			'paged'          => 0,
			'role'           => '',
			'orderby'        => 'login',
			'order'          => 'ASC',
		)
	);

	// offset
	$i              = 0;
	$min            = 0;
	$max            = 0;
	$users_per_page = acf_extract_var( $args, 'users_per_page' );
	$paged          = acf_extract_var( $args, 'paged' );

	if ( $users_per_page > 0 ) {

		// prevent paged from being -1
		$paged = max( 0, $paged );

		// set min / max
		$min = ( ( $paged - 1 ) * $users_per_page ) + 1; // 1,  11
		$max = ( $paged * $users_per_page ); // 10, 20

	}

	// find array of post_type
	$user_roles = acf_get_pretty_user_roles( $args['role'] );

	// fix role
	if ( is_array( $args['role'] ) ) {

		// global
		global $wp_version, $wpdb;

		// vars
		$roles = acf_extract_var( $args, 'role' );

		// new WP has role__in
		if ( version_compare( $wp_version, '4.4', '>=' ) ) {
			$args['role__in'] = $roles;

			// old WP doesn't have role__in
		} else {

			// vars
			$blog_id    = get_current_blog_id();
			$meta_query = array( 'relation' => 'OR' );

			// loop
			foreach ( $roles as $role ) {
				$meta_query[] = array(
					'key'     => $wpdb->get_blog_prefix( $blog_id ) . 'capabilities',
					'value'   => '"' . $role . '"',
					'compare' => 'LIKE',
				);
			}

			// append
			$args['meta_query'] = $meta_query;
		}
	}

	// get posts
	$users = get_users( $args );

	// loop
	foreach ( $user_roles as $user_role_name => $user_role_label ) {

		// vars
		$this_users = array();
		$this_group = array();

		// populate $this_posts
		foreach ( array_keys( $users ) as $key ) {

			// bail early if not correct role
			if ( ! in_array( $user_role_name, $users[ $key ]->roles ) ) {
				continue;
			}

			// extract user
			$user = acf_extract_var( $users, $key );

			// increase
			++$i;

			// bail early if too low
			if ( $min && $i < $min ) {
				continue;
			}

			// bail early if too high (don't bother looking at any more users)
			if ( $max && $i > $max ) {
				break;
			}

			// group by post type
			$this_users[ $user->ID ] = $user;
		}

		// bail early if no posts for this post type
		if ( empty( $this_users ) ) {
			continue;
		}

		// append
		$r[ $user_role_label ] = $this_users;
	}

	// return
	return $r;
}

/**
 * acf_json_encode
 *
 * Returns json_encode() ready for file / database use.
 *
 * @since   ACF 5.0.0
 *
 * @param   array $json The array of data to encode.
 * @return  string
 */
function acf_json_encode( $json ) {
	return json_encode( $json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
}

/**
 * acf_str_exists
 *
 * This function will return true if a sub string is found
 *
 * @since   ACF 5.0.0
 *
 * @param   $needle (string)
 * @param   $haystack (string)
 * @return  (boolean)
 */
function acf_str_exists( $needle, $haystack ) {

	// return true if $haystack contains the $needle
	if ( is_string( $haystack ) && strpos( $haystack, $needle ) !== false ) {
		return true;
	}

	// return
	return false;
}

/**
 * A legacy function designed for developer debugging.
 *
 * @deprecated 6.2.6 Removed for security, but keeping the definition in case third party devs have it in their code.
 * @since ACF 5.0.0
 *
 * @return false
 */
function acf_debug() {
	_deprecated_function( __FUNCTION__, '6.2.7' );
	return false;
}

/**
 * A legacy function designed for developer debugging.
 *
 * @deprecated 6.2.6 Removed for security, but keeping the definition in case third party devs have it in their code.
 * @since ACF 5.0.0
 *
 * @return false
 */
function acf_debug_start() {
	_deprecated_function( __FUNCTION__, '6.2.7' );
	return false;
}

/**
 * A legacy function designed for developer debugging.
 *
 * @deprecated 6.2.6 Removed for security, but keeping the definition in case third party devs have it in their code.
 * @since ACF 5.0.0
 *
 * @return false
 */
function acf_debug_end() {
	_deprecated_function( __FUNCTION__, '6.2.7' );
	return false;
}

/**
 * acf_encode_choices
 *
 * description
 *
 * @since   ACF 5.0.0
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_encode_choices( $array = array(), $show_keys = true ) {

	// bail early if not array (maybe a single string)
	if ( ! is_array( $array ) ) {
		return $array;
	}

	// bail early if empty array
	if ( empty( $array ) ) {
		return '';
	}

	// vars
	$string = '';

	// if allowed to show keys (good for choices, not for default values)
	if ( $show_keys ) {

		// loop
		foreach ( $array as $k => $v ) {

			// ignore if key and value are the same
			if ( strval( $k ) == strval( $v ) ) {
				continue;
			}

			// show key in the value
			$array[ $k ] = $k . ' : ' . $v;
		}
	}

	// implode
	$string = implode( "\n", $array );

	// return
	return $string;
}

function acf_decode_choices( $string = '', $array_keys = false ) {

	// bail early if already array
	if ( is_array( $string ) ) {
		return $string;

		// allow numeric values (same as string)
	} elseif ( is_numeric( $string ) ) {

		// do nothing
		// bail early if not a string
	} elseif ( ! is_string( $string ) ) {
		return array();

		// bail early if is empty string
	} elseif ( $string === '' ) {
		return array();
	}

	// vars
	$array = array();

	// explode
	$lines = explode( "\n", $string );

	// key => value
	foreach ( $lines as $line ) {

		// vars
		$k = trim( $line );
		$v = trim( $line );

		// look for ' : '
		if ( acf_str_exists( ' : ', $line ) ) {
			$line = explode( ' : ', $line );

			$k = trim( $line[0] );
			$v = trim( $line[1] );
		}

		// append
		$array[ $k ] = $v;
	}

	// return only array keys? (good for checkbox default_value)
	if ( $array_keys ) {
		return array_keys( $array );
	}

	// return
	return $array;
}

/**
 * acf_str_replace
 *
 * This function will replace an array of strings much like str_replace
 * The difference is the extra logic to avoid replacing a string that has already been replaced
 * This is very useful for replacing date characters as they overlap with each other
 *
 * @since   ACF 5.3.8
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_str_replace( $string = '', $search_replace = array() ) {

	// vars
	$ignore = array();

	// remove potential empty search to avoid PHP error
	unset( $search_replace[''] );

	// loop over conversions
	foreach ( $search_replace as $search => $replace ) {

		// ignore this search, it was a previous replace
		if ( in_array( $search, $ignore ) ) {
			continue;
		}

		// bail early if substring not found
		if ( strpos( $string, $search ) === false ) {
			continue;
		}

		// replace
		$string = str_replace( $search, $replace, $string );

		// append to ignore
		$ignore[] = $replace;
	}

	// return
	return $string;
}

/**
 * date & time formats
 *
 * These settings contain an association of format strings from PHP => JS
 *
 * @since   ACF 5.3.8
 *
 * @param   n/a
 * @return  n/a
 */

acf_update_setting(
	'php_to_js_date_formats',
	array(

		// Year
		'Y' => 'yy',    // Numeric, 4 digits                                1999, 2003
		'y' => 'y',     // Numeric, 2 digits                                99, 03


		// Month
		'm' => 'mm',    // Numeric, with leading zeros                      01–12
		'n' => 'm',     // Numeric, without leading zeros                   1–12
		'F' => 'MM',    // Textual full                                     January – December
		'M' => 'M',     // Textual three letters                            Jan - Dec


		// Weekday
		'l' => 'DD',    // Full name  (lowercase 'L')                       Sunday – Saturday
		'D' => 'D',     // Three letter name                                Mon – Sun


		// Day of Month
		'd' => 'dd',    // Numeric, with leading zeros                      01–31
		'j' => 'd',     // Numeric, without leading zeros                   1–31
		'S' => '',      // The English suffix for the day of the month      st, nd or th in the 1st, 2nd or 15th.

	)
);

acf_update_setting(
	'php_to_js_time_formats',
	array(

		'a' => 'tt',    // Lowercase Ante meridiem and Post meridiem        am or pm
		'A' => 'TT',    // Uppercase Ante meridiem and Post meridiem        AM or PM
		'h' => 'hh',    // 12-hour format of an hour with leading zeros     01 through 12
		'g' => 'h',     // 12-hour format of an hour without leading zeros  1 through 12
		'H' => 'HH',    // 24-hour format of an hour with leading zeros     00 through 23
		'G' => 'H',     // 24-hour format of an hour without leading zeros  0 through 23
		'i' => 'mm',    // Minutes with leading zeros                       00 to 59
		's' => 'ss',    // Seconds, with leading zeros                      00 through 59

	)
);


/**
 * acf_split_date_time
 *
 * This function will split a format string into separate date and time
 *
 * @since   ACF 5.3.8
 *
 * @param   $date_time (string)
 * @return  $formats (array)
 */
function acf_split_date_time( $date_time = '' ) {

	// vars
	$php_date = acf_get_setting( 'php_to_js_date_formats' );
	$php_time = acf_get_setting( 'php_to_js_time_formats' );
	$chars    = str_split( $date_time );
	$type     = 'date';

	// default
	$data = array(
		'date' => '',
		'time' => '',
	);

	// loop
	foreach ( $chars as $i => $c ) {

		// find type
		// - allow misc characters to append to previous type
		if ( isset( $php_date[ $c ] ) ) {
			$type = 'date';
		} elseif ( isset( $php_time[ $c ] ) ) {
			$type = 'time';
		}

		// append char
		$data[ $type ] .= $c;
	}

	// trim
	$data['date'] = trim( $data['date'] );
	$data['time'] = trim( $data['time'] );

	// return
	return $data;
}

/**
 * acf_convert_date_to_php
 *
 * This function converts a date format string from JS to PHP
 *
 * @since   ACF 5.0.0
 *
 * @param   $date (string)
 * @return  (string)
 */
function acf_convert_date_to_php( $date = '' ) {

	// vars
	$php_to_js = acf_get_setting( 'php_to_js_date_formats' );
	$js_to_php = array_flip( $php_to_js );

	// return
	return acf_str_replace( $date, $js_to_php );
}

/**
 * acf_convert_date_to_js
 *
 * This function converts a date format string from PHP to JS
 *
 * @since   ACF 5.0.0
 *
 * @param   $date (string)
 * @return  (string)
 */
function acf_convert_date_to_js( $date = '' ) {

	// vars
	$php_to_js = acf_get_setting( 'php_to_js_date_formats' );

	// return
	return acf_str_replace( $date, $php_to_js );
}

/**
 * acf_convert_time_to_php
 *
 * This function converts a time format string from JS to PHP
 *
 * @since   ACF 5.0.0
 *
 * @param   $time (string)
 * @return  (string)
 */
function acf_convert_time_to_php( $time = '' ) {

	// vars
	$php_to_js = acf_get_setting( 'php_to_js_time_formats' );
	$js_to_php = array_flip( $php_to_js );

	// return
	return acf_str_replace( $time, $js_to_php );
}

/**
 * acf_convert_time_to_js
 *
 * This function converts a date format string from PHP to JS
 *
 * @since   ACF 5.0.0
 *
 * @param   $time (string)
 * @return  (string)
 */
function acf_convert_time_to_js( $time = '' ) {

	// vars
	$php_to_js = acf_get_setting( 'php_to_js_time_formats' );

	// return
	return acf_str_replace( $time, $php_to_js );
}

/**
 * acf_update_user_setting
 *
 * description
 *
 * @since   ACF 5.0.0
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_update_user_setting( $name, $value ) {

	// get current user id
	$user_id = get_current_user_id();

	// get user settings
	$settings = get_user_meta( $user_id, 'acf_user_settings', true );

	// ensure array
	$settings = acf_get_array( $settings );

	// delete setting (allow 0 to save)
	if ( acf_is_empty( $value ) ) {
		unset( $settings[ $name ] );

		// append setting
	} else {
		$settings[ $name ] = $value;
	}

	// update user data
	return update_metadata( 'user', $user_id, 'acf_user_settings', $settings );
}

/**
 * acf_get_user_setting
 *
 * description
 *
 * @since   ACF 5.0.0
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_get_user_setting( $name = '', $default = false ) {

	// get current user id
	$user_id = get_current_user_id();

	// get user settings
	$settings = get_user_meta( $user_id, 'acf_user_settings', true );

	// ensure array
	$settings = acf_get_array( $settings );

	// bail arly if no settings
	if ( ! isset( $settings[ $name ] ) ) {
		return $default;
	}

	// return
	return $settings[ $name ];
}

/**
 * acf_in_array
 *
 * description
 *
 * @since   ACF 5.0.0
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_in_array( $value = '', $array = false ) {

	// bail early if not array
	if ( ! is_array( $array ) ) {
		return false;
	}

	// find value in array
	return in_array( $value, $array );
}

/**
 * acf_get_valid_post_id
 *
 * This function will return a valid post_id based on the current screen / parameter
 *
 * @since   ACF 5.0.0
 *
 * @param   $post_id (mixed)
 * @return  $post_id (mixed)
 */
function acf_get_valid_post_id( $post_id = 0 ) {

	// allow filter to short-circuit load_value logic
	$preload = apply_filters( 'acf/pre_load_post_id', null, $post_id );
	if ( $preload !== null ) {
		return $preload;
	}

	// vars
	$_post_id = $post_id;

	// if not $post_id, load queried object
	if ( ! $post_id ) {

		// try for global post (needed for setup_postdata)
		$post_id = (int) get_the_ID();

		// try for current screen
		if ( ! $post_id ) {
			$post_id = get_queried_object();
		}
	}

	// $post_id may be an object.
	// todo: Compare class types instead.
	if ( is_object( $post_id ) ) {

		// post
		if ( isset( $post_id->post_type, $post_id->ID ) ) {
			$post_id = $post_id->ID;

			// user
		} elseif ( isset( $post_id->roles, $post_id->ID ) ) {
			$post_id = 'user_' . $post_id->ID;

			// term
		} elseif ( isset( $post_id->taxonomy, $post_id->term_id ) ) {
			$post_id = 'term_' . $post_id->term_id;

			// comment
		} elseif ( isset( $post_id->comment_ID ) ) {
			$post_id = 'comment_' . $post_id->comment_ID;

			// default
		} else {
			$post_id = 0;
		}
	}

	// allow for option == options
	if ( $post_id === 'option' ) {
		$post_id = 'options';
	}

	// append language code
	if ( $post_id == 'options' ) {
		$dl = acf_get_setting( 'default_language' );
		$cl = acf_get_setting( 'current_language' );

		if ( $cl && $cl !== $dl ) {
			$post_id .= '_' . $cl;
		}
	}

	// filter for 3rd party
	$post_id = apply_filters( 'acf/validate_post_id', $post_id, $_post_id );

	// return
	return $post_id;
}



/**
 * acf_get_post_id_info
 *
 * This function will return the type and id for a given $post_id string
 *
 * @since   ACF 5.4.0
 *
 * @param   $post_id (mixed)
 * @return  $info (array)
 */
function acf_get_post_id_info( $post_id = 0 ) {

	// vars
	$info = array(
		'type' => 'post',
		'id'   => 0,
	);

	// bail early if no $post_id
	if ( ! $post_id ) {
		return $info;
	}

	// check cache
	// - this function will most likely be called multiple times (saving loading fields from post)
	// $cache_key = "get_post_id_info/post_id={$post_id}";
	// if( acf_isset_cache($cache_key) ) return acf_get_cache($cache_key);
	// numeric
	if ( is_numeric( $post_id ) ) {
		$info['id'] = (int) $post_id;

		// string
	} elseif ( is_string( $post_id ) ) {

		// vars
		$glue = '_';
		$type = explode( $glue, $post_id );
		$id   = array_pop( $type );
		$type = implode( $glue, $type );
		$meta = array( 'post', 'user', 'comment', 'term' );

		// check if is taxonomy (ACF < 5.5)
		// - avoid scenario where taxonomy exists with name of meta type
		if ( ! in_array( $type, $meta ) && acf_isset_termmeta( $type ) ) {
			$type = 'term';
		}

		// meta
		if ( is_numeric( $id ) && in_array( $type, $meta ) ) {
			$info['type'] = $type;
			$info['id']   = (int) $id;

			// option
		} else {
			$info['type'] = 'option';
			$info['id']   = $post_id;
		}
	}

	// update cache
	// acf_set_cache($cache_key, $info);
	// filter
	$info = apply_filters( 'acf/get_post_id_info', $info, $post_id );

	// return
	return $info;
}

/**
 * acf_isset_termmeta
 *
 * This function will return true if the termmeta table exists
 * https://developer.wordpress.org/reference/functions/get_term_meta/
 *
 * @since   ACF 5.4.0
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_isset_termmeta( $taxonomy = '' ) {

	// bail early if no table
	if ( get_option( 'db_version' ) < 34370 ) {
		return false;
	}

	// check taxonomy
	if ( $taxonomy && ! taxonomy_exists( $taxonomy ) ) {
		return false;
	}

	// return
	return true;
}

/**
 * This function will walk through the $_FILES data and upload each found.
 *
 * @since   ACF 5.0.9
 *
 * @param array $ancestors An internal parameter, not required.
 */
function acf_upload_files( $ancestors = array() ) {

	if ( empty( $_FILES['acf'] ) ) {
		return;
	}

	$file = acf_sanitize_files_array( $_FILES['acf'] ); // phpcs:disable WordPress.Security.NonceVerification.Missing -- Verified upstream.

	// walk through ancestors.
	if ( ! empty( $ancestors ) ) {
		foreach ( $ancestors as $a ) {
			foreach ( array_keys( $file ) as $k ) {
				$file[ $k ] = $file[ $k ][ $a ];
			}
		}
	}

	// is array?
	if ( is_array( $file['name'] ) ) {
		foreach ( array_keys( $file['name'] ) as $k ) {
			$_ancestors = array_merge( $ancestors, array( $k ) );

			acf_upload_files( $_ancestors );
		}

		return;
	}

	// Bail early if file has error (no file uploaded).
	if ( $file['error'] ) {
		return;
	}

	$field_key  = end( $ancestors );
	$nonce_name = $field_key . '_file_nonce';

	if ( empty( $_REQUEST['acf'][ $nonce_name ] ) || ! wp_verify_nonce( sanitize_text_field( $_REQUEST['acf'][ $nonce_name ] ), 'acf/file_uploader_nonce/' . $field_key ) ) {
		return;
	}

	// Assign global _acfuploader for media validation.
	$_POST['_acfuploader'] = $field_key;

	// file found!
	$attachment_id = acf_upload_file( $file );

	// update $_POST
	array_unshift( $ancestors, 'acf' );
	acf_update_nested_array( $_POST, $ancestors, $attachment_id );
}

/**
 * acf_upload_file
 *
 * This function will upload a $_FILE
 *
 * @since   ACF 5.0.9
 *
 * @param   $uploaded_file (array) array found from $_FILE data
 * @return  $id (int) new attachment ID
 */
function acf_upload_file( $uploaded_file ) {

	// required
	// require_once( ABSPATH . "/wp-load.php" ); // WP should already be loaded
	require_once ABSPATH . '/wp-admin/includes/media.php'; // video functions
	require_once ABSPATH . '/wp-admin/includes/file.php';
	require_once ABSPATH . '/wp-admin/includes/image.php';

	// required for wp_handle_upload() to upload the file
	$upload_overrides = array( 'test_form' => false );

	// upload
	$file = wp_handle_upload( $uploaded_file, $upload_overrides );

	// bail early if upload failed
	if ( isset( $file['error'] ) ) {
		return $file['error'];
	}

	// vars
	$url      = $file['url'];
	$type     = $file['type'];
	$file     = $file['file'];
	$filename = basename( $file );

	// Construct the object array
	$object = array(
		'post_title'     => $filename,
		'post_mime_type' => $type,
		'guid'           => $url,
	);

	// Save the data
	$id = wp_insert_attachment( $object, $file );

	// Add the meta-data
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );

	/** This action is documented in wp-admin/custom-header.php */
	do_action( 'wp_create_file_in_uploads', $file, $id ); // For replication

	// return new ID
	return $id;
}

/**
 * acf_update_nested_array
 *
 * This function will update a nested array value. Useful for modifying the $_POST array
 *
 * @since   ACF 5.0.9
 *
 * @param   $array (array) target array to be updated
 * @param   $ancestors (array) array of keys to navigate through to find the child
 * @param   $value (mixed) The new value
 * @return  (boolean)
 */
function acf_update_nested_array( &$array, $ancestors, $value ) {

	// if no more ancestors, update the current var
	if ( empty( $ancestors ) ) {
		$array = $value;

		// return
		return true;
	}

	// shift the next ancestor from the array
	$k = array_shift( $ancestors );

	// if exists
	if ( isset( $array[ $k ] ) ) {
		return acf_update_nested_array( $array[ $k ], $ancestors, $value );
	}

	// return
	return false;
}

/**
 * acf_is_screen
 *
 * This function will return true if all args are matched for the current screen
 *
 * @since   ACF 5.1.5
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_is_screen( $id = '' ) {

	// bail early if not defined
	if ( ! function_exists( 'get_current_screen' ) ) {
		return false;
	}

	// vars
	$current_screen = get_current_screen();

	// no screen
	if ( ! $current_screen ) {
		return false;

		// array
	} elseif ( is_array( $id ) ) {
		return in_array( $current_screen->id, $id );

		// string
	} else {
		return ( $id === $current_screen->id );
	}
}

/**
 * Check if we're in an ACF admin screen
 *
 * @since  ACF 6.2.2
 *
 * @return boolean Returns true if the current screen is an ACF admin screen.
 */
function acf_is_acf_admin_screen() {
	if ( ! is_admin() || ! function_exists( 'get_current_screen' ) ) {
		return false;
	}
	$screen = get_current_screen();
	if ( $screen && ! empty( $screen->post_type ) && substr( $screen->post_type, 0, 4 ) === 'acf-' ) {
		return true;
	}

	return false;
}

/**
 * acf_maybe_get
 *
 * This function will return a var if it exists in an array
 *
 * @since   ACF 5.1.5
 *
 * @param   $array (array) the array to look within
 * @param   $key (key) the array key to look for. Nested values may be found using '/'
 * @param   $default (mixed) the value returned if not found
 * @return  $post_id (int)
 */
function acf_maybe_get( $array = array(), $key = 0, $default = null ) {

	return isset( $array[ $key ] ) ? $array[ $key ] : $default;
}

function acf_maybe_get_POST( $key = '', $default = null ) {

	return isset( $_POST[ $key ] ) ? acf_sanitize_request_args( $_POST[ $key ] ) : $default; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing -- Checked elsewhere.
}

function acf_maybe_get_GET( $key = '', $default = null ) {

	return isset( $_GET[ $key ] ) ? acf_sanitize_request_args( $_GET[ $key ] ) : $default; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Checked elsewhere.
}

/**
 * Returns an array of attachment data.
 *
 * @since   ACF 5.1.5
 *
 * @param   integer|WP_Post The attachment ID or object
 * @return  array|false
 */
function acf_get_attachment( $attachment ) {

	// Allow filter to short-circuit load attachment logic.
	// Alternatively, this filter may be used to switch blogs for multisite media functionality.
	$response = apply_filters( 'acf/pre_load_attachment', null, $attachment );
	if ( $response !== null ) {
		return $response;
	}

	// Get the attachment post object.
	$attachment = get_post( $attachment );
	if ( ! $attachment ) {
		return false;
	}
	if ( $attachment->post_type !== 'attachment' ) {
		return false;
	}

	// Load various attachment details.
	$meta          = wp_get_attachment_metadata( $attachment->ID );
	$attached_file = get_attached_file( $attachment->ID );
	if ( strpos( $attachment->post_mime_type, '/' ) !== false ) {
		list($type, $subtype) = explode( '/', $attachment->post_mime_type );
	} else {
		list($type, $subtype) = array( $attachment->post_mime_type, '' );
	}

	// Generate response.
	$response = array(
		'ID'          => $attachment->ID,
		'id'          => $attachment->ID,
		'title'       => $attachment->post_title,
		'filename'    => wp_basename( $attached_file ),
		'filesize'    => 0,
		'url'         => wp_get_attachment_url( $attachment->ID ),
		'link'        => get_attachment_link( $attachment->ID ),
		'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'author'      => $attachment->post_author,
		'description' => $attachment->post_content,
		'caption'     => $attachment->post_excerpt,
		'name'        => $attachment->post_name,
		'status'      => $attachment->post_status,
		'uploaded_to' => $attachment->post_parent,
		'date'        => $attachment->post_date_gmt,
		'modified'    => $attachment->post_modified_gmt,
		'menu_order'  => $attachment->menu_order,
		'mime_type'   => $attachment->post_mime_type,
		'type'        => $type,
		'subtype'     => $subtype,
		'icon'        => wp_mime_type_icon( $attachment->ID ),
	);

	// Append filesize data.
	if ( isset( $meta['filesize'] ) ) {
		$response['filesize'] = $meta['filesize'];
	} else {
		/**
		 * Allows shortcutting our ACF's `filesize` call to prevent us making filesystem calls.
		 * Mostly useful for third party plugins which may offload media to other services, and filesize calls will induce a remote download.
		 *
		 * @since ACF 6.2.2
		 *
		 * @param int|null $shortcut_filesize The default filesize.
		 * @param WP_Post $attachment The attachment post object we're looking for the filesize for.
		 */
		$shortcut_filesize = apply_filters( 'acf/filesize', null, $attachment );
		if ( $shortcut_filesize ) {
			$response['filesize'] = intval( $shortcut_filesize );
		} elseif ( file_exists( $attached_file ) ) {
			$response['filesize'] = filesize( $attached_file );
		}
	}

	// Restrict the loading of image "sizes".
	$sizes_id = 0;

	// Type specific logic.
	switch ( $type ) {
		case 'image':
			$sizes_id = $attachment->ID;
			$src      = wp_get_attachment_image_src( $attachment->ID, 'full' );
			if ( $src ) {
				$response['url']    = $src[0];
				$response['width']  = $src[1];
				$response['height'] = $src[2];
			}
			break;
		case 'video':
			$response['width']  = acf_maybe_get( $meta, 'width', 0 );
			$response['height'] = acf_maybe_get( $meta, 'height', 0 );
			if ( $featured_id = get_post_thumbnail_id( $attachment->ID ) ) {
				$sizes_id = $featured_id;
			}
			break;
		case 'audio':
			if ( $featured_id = get_post_thumbnail_id( $attachment->ID ) ) {
				$sizes_id = $featured_id;
			}
			break;
	}

	// Load array of image sizes.
	if ( $sizes_id ) {
		$sizes      = get_intermediate_image_sizes();
		$sizes_data = array();
		foreach ( $sizes as $size ) {
			$src = wp_get_attachment_image_src( $sizes_id, $size );
			if ( $src ) {
				$sizes_data[ $size ]             = $src[0];
				$sizes_data[ $size . '-width' ]  = $src[1];
				$sizes_data[ $size . '-height' ] = $src[2];
			}
		}
		$response['sizes'] = $sizes_data;
	}

	/**
	 * Filters the attachment $response after it has been loaded.
	 *
	 * @since   ACF 5.9.0
	 *
	 * @param   array $response Array of loaded attachment data.
	 * @param   WP_Post $attachment Attachment object.
	 * @param   array|false $meta Array of attachment meta data, or false if there is none.
	 */
	return apply_filters( 'acf/load_attachment', $response, $attachment, $meta );
}

/**
 * This function will truncate and return a string
 *
 * @since   ACF 5.0.0
 *
 * @param string  $text   The text to truncate.
 * @param integer $length The number of characters to allow in the string.
 *
 * @return  string
 */
function acf_get_truncated( $text, $length = 64 ) {
	$text       = trim( $text );
	$the_length = function_exists( 'mb_strlen' ) ? mb_strlen( $text ) : strlen( $text );

	$cut_length = $length - 3;
	$return     = function_exists( 'mb_substr' ) ? mb_substr( $text, 0, $cut_length ) : substr( $text, 0, $cut_length );

	if ( $the_length > $cut_length ) {
		$return .= '...';
	}

	return $return;
}

/**
 * acf_current_user_can_admin
 *
 * This function will return true if the current user can administrate the ACF field groups
 *
 * @since   ACF 5.1.5
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_current_user_can_admin() {

	if ( acf_get_setting( 'show_admin' ) && current_user_can( acf_get_setting( 'capability' ) ) ) {
		return true;
	}

	// return
	return false;
}

/**
 * Wrapper function for current_user_can( 'edit_post', $post_id ).
 *
 * @since ACF 6.3.4
 *
 * @param integer $post_id The post ID to check.
 * @return boolean
 */
function acf_current_user_can_edit_post( int $post_id ): bool {
	/**
	 * The `edit_post` capability is a meta capability, which
	 * gets converted to the correct post type object `edit_post`
	 * equivalent.
	 *
	 * If the post type does not have `map_meta_cap` enabled and the user is
	 * not manually mapping the `edit_post` capability, this will fail
	 * unless the role has the `edit_post` capability added to a user/role.
	 *
	 * However, more (core) stuff will likely break in this scenario.
	 */
	$user_can_edit = current_user_can( 'edit_post', $post_id );

	return (bool) apply_filters( 'acf/current_user_can_edit_post', $user_can_edit, $post_id );
}

/**
 * acf_get_filesize
 *
 * This function will return a numeric value of bytes for a given filesize string
 *
 * @since   ACF 5.1.5
 *
 * @param   $size (mixed)
 * @return  (int)
 */
function acf_get_filesize( $size = 1 ) {

	// vars
	$unit  = 'MB';
	$units = array(
		'TB' => 4,
		'GB' => 3,
		'MB' => 2,
		'KB' => 1,
	);

	// look for $unit within the $size parameter (123 KB)
	if ( is_string( $size ) ) {

		// vars
		$custom = strtoupper( substr( $size, -2 ) );

		foreach ( $units as $k => $v ) {
			if ( $custom === $k ) {
				$unit = $k;
				$size = substr( $size, 0, -2 );
			}
		}
	}

	// calc bytes
	$bytes = floatval( $size ) * pow( 1024, $units[ $unit ] );

	// return
	return $bytes;
}

/**
 * acf_format_filesize
 *
 * This function will return a formatted string containing the filesize and unit
 *
 * @since   ACF 5.1.5
 *
 * @param   $size (mixed)
 * @return  (int)
 */
function acf_format_filesize( $size = 1 ) {

	// convert
	$bytes = acf_get_filesize( $size );

	// vars
	$units = array(
		'TB' => 4,
		'GB' => 3,
		'MB' => 2,
		'KB' => 1,
	);

	// loop through units
	foreach ( $units as $k => $v ) {
		$result = $bytes / pow( 1024, $v );

		if ( $result >= 1 ) {
			return $result . ' ' . $k;
		}
	}

	// return
	return $bytes . ' B';
}

/**
 * acf_get_valid_terms
 *
 * This function will replace old terms with new split term ids
 *
 * @since   ACF 5.1.5
 *
 * @param   $terms (int|array)
 * @param   $taxonomy (string)
 * @return  $terms
 */
function acf_get_valid_terms( $terms = false, $taxonomy = 'category' ) {

	// force into array
	$terms = acf_get_array( $terms );

	// force ints
	$terms = array_map( 'intval', $terms );

	// bail early if function does not yet exist or
	if ( ! function_exists( 'wp_get_split_term' ) || empty( $terms ) ) {
		return $terms;
	}

	// attempt to find new terms
	foreach ( $terms as $i => $term_id ) {
		$new_term_id = wp_get_split_term( $term_id, $taxonomy );

		if ( $new_term_id ) {
			$terms[ $i ] = $new_term_id;
		}
	}

	// return
	return $terms;
}

/**
 * acf_validate_attachment
 *
 * This function will validate an attachment based on a field's restrictions and return an array of errors
 *
 * @since   ACF 5.2.3
 *
 * @param   $attachment (array) attachment data. Changes based on context
 * @param   $field (array) field settings containing restrictions
 * @param   context (string)                                     $file is different when uploading / preparing
 * @return  $errors (array)
 */
function acf_validate_attachment( $attachment, $field, $context = 'prepare' ) {

	// vars
	$errors = array();
	$file   = array(
		'type'   => '',
		'width'  => 0,
		'height' => 0,
		'size'   => 0,
	);

	// upload
	if ( $context == 'upload' ) {

		// vars
		$file['type'] = pathinfo( $attachment['name'], PATHINFO_EXTENSION );
		$file['size'] = filesize( $attachment['tmp_name'] );

		if ( strpos( $attachment['type'], 'image' ) !== false ) {
			$size           = getimagesize( $attachment['tmp_name'] );
			$file['width']  = acf_maybe_get( $size, 0 );
			$file['height'] = acf_maybe_get( $size, 1 );
		}

		// prepare
	} elseif ( $context == 'prepare' ) {
		$use_path       = isset( $attachment['filename'] ) ? $attachment['filename'] : $attachment['url'];
		$file['type']   = pathinfo( $use_path, PATHINFO_EXTENSION );
		$file['size']   = acf_maybe_get( $attachment, 'filesizeInBytes', 0 );
		$file['width']  = acf_maybe_get( $attachment, 'width', 0 );
		$file['height'] = acf_maybe_get( $attachment, 'height', 0 );

		// custom
	} else {
		$file         = array_merge( $file, $attachment );
		$use_path     = isset( $attachment['filename'] ) ? $attachment['filename'] : $attachment['url'];
		$file['type'] = pathinfo( $use_path, PATHINFO_EXTENSION );
	}

	// image
	if ( $file['width'] || $file['height'] ) {

		// width
		$min_width = (int) acf_maybe_get( $field, 'min_width', 0 );
		$max_width = (int) acf_maybe_get( $field, 'max_width', 0 );

		if ( $file['width'] ) {
			if ( $min_width && $file['width'] < $min_width ) {

				// min width
				/* translators: 1: image width */
				$errors['min_width'] = sprintf( __( 'Image width must be at least %dpx.', 'secure-custom-fields' ), $min_width );
			} elseif ( $max_width && $file['width'] > $max_width ) {

				// min width
				/* translators: 1: image width */
				$errors['max_width'] = sprintf( __( 'Image width must not exceed %dpx.', 'secure-custom-fields' ), $max_width );
			}
		}

		// height
		$min_height = (int) acf_maybe_get( $field, 'min_height', 0 );
		$max_height = (int) acf_maybe_get( $field, 'max_height', 0 );

		if ( $file['height'] ) {
			if ( $min_height && $file['height'] < $min_height ) {

				// min height
				/* translators: 1: image height */
				$errors['min_height'] = sprintf( __( 'Image height must be at least %dpx.', 'secure-custom-fields' ), $min_height );
			} elseif ( $max_height && $file['height'] > $max_height ) {

				// min height
				/* translators: 1: image height */
				$errors['max_height'] = sprintf( __( 'Image height must not exceed %dpx.', 'secure-custom-fields' ), $max_height );
			}
		}
	}

	// file size
	if ( $file['size'] ) {
		$min_size = acf_maybe_get( $field, 'min_size', 0 );
		$max_size = acf_maybe_get( $field, 'max_size', 0 );

		if ( $min_size && $file['size'] < acf_get_filesize( $min_size ) ) {

			// min width
			/* translators: 1: file size */
			$errors['min_size'] = sprintf( __( 'File size must be at least %s.', 'secure-custom-fields' ), acf_format_filesize( $min_size ) );
		} elseif ( $max_size && $file['size'] > acf_get_filesize( $max_size ) ) {

			// min width
			/* translators: 1: file size */
			$errors['max_size'] = sprintf( __( 'File size must not exceed %s.', 'secure-custom-fields' ), acf_format_filesize( $max_size ) );
		}
	}

	// file type
	if ( $file['type'] ) {
		$mime_types = acf_maybe_get( $field, 'mime_types', '' );

		// lower case
		$file['type'] = strtolower( $file['type'] );
		$mime_types   = strtolower( $mime_types );

		// explode
		$mime_types = str_replace( array( ' ', '.' ), '', $mime_types );
		$mime_types = explode( ',', $mime_types ); // split pieces
		$mime_types = array_filter( $mime_types ); // remove empty pieces

		if ( ! empty( $mime_types ) && ! in_array( $file['type'], $mime_types ) ) {

			// glue together last 2 types
			if ( count( $mime_types ) > 1 ) {
				$last1 = array_pop( $mime_types );
				$last2 = array_pop( $mime_types );

				$mime_types[] = $last2 . ' ' . __( 'or', 'secure-custom-fields' ) . ' ' . $last1;
			}
			/* translators: 1: file type(s) */
			$errors['mime_types'] = sprintf( __( 'File type must be %s.', 'secure-custom-fields' ), implode( ', ', $mime_types ) );
		}
	}

	/**
	 * Filters the errors for a file before it is uploaded or displayed in the media modal.
	 *
	 * @since   ACF 5.2.3
	 *
	 * @param   array $errors An array of errors.
	 * @param   array $file An array of data for a single file.
	 * @param   array $attachment An array of attachment data which differs based on the context.
	 * @param   array $field The field array.
	 * @param   string $context The current context (uploading, preparing)
	 */
	$errors = apply_filters( "acf/validate_attachment/type={$field['type']}", $errors, $file, $attachment, $field, $context );
	$errors = apply_filters( "acf/validate_attachment/name={$field['_name']}", $errors, $file, $attachment, $field, $context );
	$errors = apply_filters( "acf/validate_attachment/key={$field['key']}", $errors, $file, $attachment, $field, $context );
	$errors = apply_filters( 'acf/validate_attachment', $errors, $file, $attachment, $field, $context );

	// return
	return $errors;
}

/**
 * _acf_settings_uploader
 *
 * Dynamic logic for uploader setting
 *
 * @since   ACF 5.2.3
 *
 * @param   $uploader (string)
 * @return  $uploader
 */

add_filter( 'acf/settings/uploader', '_acf_settings_uploader' );

function _acf_settings_uploader( $uploader ) {

	// if can't upload files
	if ( ! current_user_can( 'upload_files' ) ) {
		$uploader = 'basic';
	}

	// return
	return $uploader;
}

/**
 * acf_translate
 *
 * This function will translate a string using the new 'l10n_textdomain' setting
 * Also works for arrays which is great for fields - select -> choices
 *
 * @since   ACF 5.3.2
 *
 * @param   mixed $string String or array containing strings to be translated.
 * @return  mixed
 */
function acf_translate( $string ) {

	// vars
	$l10n       = acf_get_setting( 'l10n' );
	$textdomain = acf_get_setting( 'l10n_textdomain' );

	// bail early if not enabled
	if ( ! $l10n ) {
		return $string;
	}

	// bail early if no textdomain
	if ( ! $textdomain ) {
		return $string;
	}

	// is array
	if ( is_array( $string ) ) {
		return array_map( 'acf_translate', $string );
	}

	// bail early if empty
	if ( '' === $string ) {
		return $string;
	}

	// translate
	return __( $string, $textdomain );
}

/**
 * acf_maybe_add_action
 *
 * This function will determine if the action has already run before adding / calling the function
 *
 * @since   ACF 5.3.2
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_maybe_add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {

	// if action has already run, execute it
	// - if currently doing action, allow $tag to be added as per usual to allow $priority ordering needed for 3rd party asset compatibility
	if ( did_action( $tag ) && ! doing_action( $tag ) ) {
		call_user_func( $function_to_add );

		// if action has not yet run, add it
	} else {
		add_action( $tag, $function_to_add, $priority, $accepted_args );
	}
}

/**
 * acf_is_row_collapsed
 *
 * This function will return true if the field's row is collapsed
 *
 * @since   ACF 5.3.2
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_is_row_collapsed( $field_key = '', $row_index = 0 ) {

	// collapsed
	$collapsed = acf_get_user_setting( 'collapsed_' . $field_key, '' );

	// cookie fallback ( version < 5.3.2 )
	if ( $collapsed === '' ) {
		$collapsed = acf_extract_var( $_COOKIE, "acf_collapsed_{$field_key}", '' );
		$collapsed = str_replace( '|', ',', $collapsed );

		// update
		acf_update_user_setting( 'collapsed_' . $field_key, $collapsed );
	}

	// explode
	$collapsed = explode( ',', $collapsed );
	$collapsed = array_filter( $collapsed, 'is_numeric' );

	// collapsed class
	return in_array( $row_index, $collapsed );
}

/**
 * Return an image tag for the provided attachment ID
 *
 * @since ACF 5.5.0
 * @deprecated 6.3.2
 *
 * @param integer $attachment_id The attachment ID
 * @param string  $size          The image size to use in the image tag.
 * @return false
 */
function acf_get_attachment_image( $attachment_id = 0, $size = 'thumbnail' ) {
	// report function as deprecated
	_deprecated_function( __FUNCTION__, '6.3.2' );
	return false;
}

/**
 * acf_get_post_thumbnail
 *
 * This function will return a thumbnail image url for a given post
 *
 * @since   ACF 5.3.8
 *
 * @param   $post (obj)
 * @param   $size (mixed)
 * @return  (string)
 */
function acf_get_post_thumbnail( $post = null, $size = 'thumbnail' ) {

	// vars
	$data = array(
		'url'  => '',
		'type' => '',
		'html' => '',
	);

	// post
	$post = get_post( $post );

	// bail early if no post
	if ( ! $post ) {
		return $data;
	}

	// vars
	$thumb_id  = $post->ID;
	$mime_type = acf_maybe_get( explode( '/', $post->post_mime_type ), 0 );

	// attachment
	if ( $post->post_type === 'attachment' ) {

		// change $thumb_id
		if ( $mime_type === 'audio' || $mime_type === 'video' ) {
			$thumb_id = get_post_thumbnail_id( $post->ID );
		}

		// post
	} else {
		$thumb_id = get_post_thumbnail_id( $post->ID );
	}

	// try url
	$data['url'] = wp_get_attachment_image_src( $thumb_id, $size );
	$data['url'] = acf_maybe_get( $data['url'], 0 );

	// default icon
	if ( ! $data['url'] && $post->post_type === 'attachment' ) {
		$data['url']  = wp_mime_type_icon( $post->ID );
		$data['type'] = 'icon';
	}

	// html
	$data['html'] = '<img src="' . $data['url'] . '" alt="" />';

	// return
	return $data;
}

/**
 * acf_get_browser
 *
 * Returns the name of the current browser.
 *
 * @since   ACF 5.0.0
 *
 * @return  string
 */
function acf_get_browser() {

	// Check server var.
	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		$agent = sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] );

		// Loop over search terms.
		$browsers = array(
			'Firefox' => 'firefox',
			'Trident' => 'msie',
			'MSIE'    => 'msie',
			'Edge'    => 'edge',
			'Chrome'  => 'chrome',
			'Safari'  => 'safari',
		);
		foreach ( $browsers as $k => $v ) {
			if ( strpos( $agent, $k ) !== false ) {
				return $v;
			}
		}
	}

	// Return default.
	return '';
}

/**
 * acf_is_ajax
 *
 * This function will return true if performing a wp ajax call
 *
 * @since   ACF 5.3.8
 *
 * @param   n/a
 * @return  (boolean)
 */
function acf_is_ajax( $action = '' ) {

	// vars
	$is_ajax = false;

	// check if is doing ajax
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$is_ajax = true;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Missing
	// check $action
	if ( $action && acf_maybe_get( $_POST, 'action' ) !== $action ) {
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$is_ajax = false;
	}

	// return
	return $is_ajax;
}

/**
 * Returns a date value in a formatted string.
 *
 * @since ACF 5.3.8
 *
 * @param string $value  The date value to format.
 * @param string $format The format to use.
 * @return string
 */
function acf_format_date( $value, $format ) {
	// Bail early if no value or value is not what we expect.
	if ( ! $value || ( ! is_string( $value ) && ! is_int( $value ) ) ) {
		return $value;
	}

	// Numeric (either unix or YYYYMMDD).
	if ( is_numeric( $value ) && strlen( $value ) !== 8 ) {
		$unixtimestamp = $value;
	} else {
		$unixtimestamp = strtotime( $value );
	}

	return date_i18n( $format, $unixtimestamp );
}

/**
 * Previously, deletes the debug.log file.
 *
 * @since      ACF 5.7.10
 * @deprecated 6.2.7
 */
function acf_clear_log() {
	_deprecated_function( __FUNCTION__, '6.2.7' );
	return false;
}

/**
 * acf_log
 *
 * description
 *
 * @since   ACF 5.3.8
 *
 * @param   $post_id (int)
 * @return  $post_id (int)
 */
function acf_log() {

	// vars
	$args = func_get_args();

	// loop
	foreach ( $args as $i => $arg ) {

		// array | object
		if ( is_array( $arg ) || is_object( $arg ) ) {
			$arg = print_r( $arg, true );

			// bool
		} elseif ( is_bool( $arg ) ) {
			$arg = 'bool(' . ( $arg ? 'true' : 'false' ) . ')';
		}

		// update
		$args[ $i ] = $arg;
	}

	// log
	error_log( implode( ' ', $args ) );
}

/**
 * acf_dev_log
 *
 * Used to log variables only if ACF_DEV is defined
 *
 * @since   ACF 5.7.4
 *
 * @param   mixed
 * @return  void
 */
function acf_dev_log() {
	if ( defined( 'ACF_DEV' ) && ACF_DEV ) {
		call_user_func_array( 'acf_log', func_get_args() );
	}
}

/**
 * acf_doing
 *
 * This function will tell ACF what task it is doing
 *
 * @since   ACF 5.3.8
 *
 * @param   $event (string)
 * @param   context (string)
 * @return  n/a
 */
function acf_doing( $event = '', $context = '' ) {

	acf_update_setting( 'doing', $event );
	acf_update_setting( 'doing_context', $context );
}

/**
 * acf_is_doing
 *
 * This function can be used to state what ACF is doing, or to check
 *
 * @since   ACF 5.3.8
 *
 * @param   $event (string)
 * @param   context (string)
 * @return  (boolean)
 */
function acf_is_doing( $event = '', $context = '' ) {

	// vars
	$doing = false;

	// task
	if ( acf_get_setting( 'doing' ) === $event ) {
		$doing = true;
	}

	// context
	if ( $context && acf_get_setting( 'doing_context' ) !== $context ) {
		$doing = false;
	}

	// return
	return $doing;
}

/**
 * acf_is_plugin_active
 *
 * This function will return true if the ACF plugin is active
 * - May be included within a theme or other plugin
 *
 * @since   ACF 5.4.0
 *
 * @param   $basename (int)
 * @return  $post_id (int)
 */
function acf_is_plugin_active() {

	// vars
	$basename = acf_get_setting( 'basename' );

	// ensure is_plugin_active() exists (not on frontend)
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	// return
	return is_plugin_active( $basename );
}

/**
 * acf_send_ajax_results
 *
 * This function will print JSON data for a Select2 AJAX query
 *
 * @since   ACF 5.4.0
 *
 * @param   $response (array)
 * @return  n/a
 */
function acf_send_ajax_results( $response ) {

	// validate
	$response = wp_parse_args(
		$response,
		array(
			'results' => array(),
			'more'    => false,
			'limit'   => 0,
		)
	);

	// limit
	if ( $response['limit'] && $response['results'] ) {

		// vars
		$total = 0;

		foreach ( $response['results'] as $result ) {

			// parent
			++$total;

			// children
			if ( ! empty( $result['children'] ) ) {
				$total += count( $result['children'] );
			}
		}

		// calc
		if ( $total >= $response['limit'] ) {
			$response['more'] = true;
		}
	}

	// return
	wp_send_json( $response );
}

/**
 * acf_is_sequential_array
 *
 * This function will return true if the array contains only numeric keys
 *
 * @source  http://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
 *
 * @since   ACF 5.4.0
 *
 * @param   $array (array)
 * @return  (boolean)
 */
function acf_is_sequential_array( $array ) {

	// bail early if not array
	if ( ! is_array( $array ) ) {
		return false;
	}

	// loop
	foreach ( $array as $key => $value ) {

		// bail early if is string
		if ( is_string( $key ) ) {
			return false;
		}
	}

	// return
	return true;
}

/**
 * acf_is_associative_array
 *
 * This function will return true if the array contains one or more string keys
 *
 * @source  http://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
 *
 * @since   ACF 5.4.0
 *
 * @param   $array (array)
 * @return  (boolean)
 */
function acf_is_associative_array( $array ) {

	// bail early if not array
	if ( ! is_array( $array ) ) {
		return false;
	}

	// loop
	foreach ( $array as $key => $value ) {

		// bail early if is string
		if ( is_string( $key ) ) {
			return true;
		}
	}

	// return
	return false;
}

/**
 * acf_add_array_key_prefix
 *
 * This function will add a prefix to all array keys
 * Useful to preserve numeric keys when performing array_multisort
 *
 * @since   ACF 5.4.0
 *
 * @param   $array (array)
 * @param   $prefix (string)
 * @return  (array)
 */
function acf_add_array_key_prefix( $array, $prefix ) {

	// vars
	$array2 = array();

	// loop
	foreach ( $array as $k => $v ) {
		$k2            = $prefix . $k;
		$array2[ $k2 ] = $v;
	}

	// return
	return $array2;
}

/**
 * acf_remove_array_key_prefix
 *
 * This function will remove a prefix to all array keys
 * Useful to preserve numeric keys when performing array_multisort
 *
 * @since   ACF 5.4.0
 *
 * @param   $array (array)
 * @param   $prefix (string)
 * @return  (array)
 */
function acf_remove_array_key_prefix( $array, $prefix ) {

	// vars
	$array2 = array();
	$l      = strlen( $prefix );

	// loop
	foreach ( $array as $k => $v ) {
		$k2            = ( substr( $k, 0, $l ) === $prefix ) ? substr( $k, $l ) : $k;
		$array2[ $k2 ] = $v;
	}

	// return
	return $array2;
}

/**
 * This function will connect an attachment (image etc) to the post
 * Used to connect attachments uploaded directly to media that have not been attached to a post
 *
 * @since   ACF 5.8.0 Added filter to prevent connection.
 * @since   ACF 5.5.4
 *
 * @param   integer $attachment_id The attachment ID.
 * @param   integer $post_id       The post ID.
 * @return  boolean True if attachment was connected.
 */
function acf_connect_attachment_to_post( $attachment_id = 0, $post_id = 0 ) {

	// bail early if $attachment_id is not valid.
	if ( ! $attachment_id || ! is_numeric( $attachment_id ) ) {
		return false;
	}

	// bail early if $post_id is not valid.
	if ( ! $post_id || ! is_numeric( $post_id ) ) {
		return false;
	}

	/**
	 * Filters whether or not to connect the attachment.
	 *
	 * @since   ACF 5.8.0
	 *
	 * @param   bool $bool Returning false will prevent the connection. Default true.
	 * @param   int $attachment_id The attachment ID.
	 * @param   int $post_id The post ID.
	 */
	if ( ! apply_filters( 'acf/connect_attachment_to_post', true, $attachment_id, $post_id ) ) {
		return false;
	}

	// vars
	$post = get_post( $attachment_id );

	// Check if is valid post.
	if ( $post && $post->post_type == 'attachment' && $post->post_parent == 0 ) {

		// update
		wp_update_post(
			array(
				'ID'          => $post->ID,
				'post_parent' => $post_id,
			)
		);

		// return
		return true;
	}

	// return
	return true;
}

/**
 * acf_encrypt
 *
 * This function will encrypt a string using PHP
 * https://bhoover.com/using-php-openssl_encrypt-openssl_decrypt-encrypt-decrypt-data/
 *
 * @since   ACF 5.5.8
 *
 * @param   $data (string)
 * @return  (string)
 */
function acf_encrypt( $data = '' ) {

	// bail early if no encrypt function
	if ( ! function_exists( 'openssl_encrypt' ) ) {
		return base64_encode( $data );
	}

	// generate a key
	$key = wp_hash( 'acf_encrypt' );

	// Generate an initialization vector
	$iv = openssl_random_pseudo_bytes( openssl_cipher_iv_length( 'aes-256-cbc' ) );

	// Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
	$encrypted_data = openssl_encrypt( $data, 'aes-256-cbc', $key, 0, $iv );

	// The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
	return base64_encode( $encrypted_data . '::' . $iv );
}

/**
 * acf_decrypt
 *
 * This function will decrypt an encrypted string using PHP
 * https://bhoover.com/using-php-openssl_encrypt-openssl_decrypt-encrypt-decrypt-data/
 *
 * @since   ACF 5.5.8
 *
 * @param   $data (string)
 * @return  (string)
 */
function acf_decrypt( $data = '' ) {

	// bail early if no decrypt function
	if ( ! function_exists( 'openssl_decrypt' ) ) {
		return base64_decode( $data );
	}

	// generate a key
	$key = wp_hash( 'acf_encrypt' );

	// To decrypt, split the encrypted data from our IV - our unique separator used was "::"
	list($encrypted_data, $iv) = explode( '::', base64_decode( $data ), 2 );

	// decrypt
	return openssl_decrypt( $encrypted_data, 'aes-256-cbc', $key, 0, $iv );
}

/**
 * acf_parse_markdown
 *
 * A very basic regex-based Markdown parser function based off [slimdown](https://gist.github.com/jbroadway/2836900).
 *
 * @since   ACF 5.7.2
 *
 * @param   string $text The string to parse.
 * @return  string
 */
function acf_parse_markdown( $text = '' ) {

	// trim
	$text = trim( $text );

	// rules
	$rules = array(
		'/=== (.+?) ===/'            => '<h2>$1</h2>',                   // headings
		'/== (.+?) ==/'              => '<h3>$1</h3>',                   // headings
		'/= (.+?) =/'                => '<h4>$1</h4>',                   // headings
		'/\[([^\[]+)\]\(([^\)]+)\)/' => '<a href="$2">$1</a>',           // links
		'/(\*\*)(.*?)\1/'            => '<strong>$2</strong>',           // bold
		'/(\*)(.*?)\1/'              => '<em>$2</em>',                   // italic
		'/`(.*?)`/'                  => '<code>$1</code>',               // inline code
		'/\n\*(.*)/'                 => "\n<ul>\n\t<li>$1</li>\n</ul>",  // ul lists
		'/\n[0-9]+\.(.*)/'           => "\n<ol>\n\t<li>$1</li>\n</ol>",  // ol lists
		'/<\/ul>\s?<ul>/'            => '',                              // fix extra ul
		'/<\/ol>\s?<ol>/'            => '',                              // fix extra ol
	);
	foreach ( $rules as $k => $v ) {
		$text = preg_replace( $k, $v, $text );
	}

	// autop
	$text = wpautop( $text );

	// return
	return $text;
}

/**
 * acf_get_sites
 *
 * Returns an array of sites for a network.
 *
 * @since   ACF 5.4.0
 *
 * @return  array
 */
function acf_get_sites() {
	$results = array();
	$sites   = get_sites( array( 'number' => 0 ) );
	if ( $sites ) {
		foreach ( $sites as $site ) {
			$results[] = get_site( $site )->to_array();
		}
	}
	return $results;
}

/**
 * acf_convert_rules_to_groups
 *
 * Converts an array of rules from ACF4 to an array of groups for ACF5
 *
 * @since   ACF 5.7.4
 *
 * @param   array  $rules    An array of rules.
 * @param   string $anyorall The anyorall setting used in ACF4. Defaults to 'any'.
 * @return  array
 */
function acf_convert_rules_to_groups( $rules, $anyorall = 'any' ) {

	// vars
	$groups = array();
	$index  = 0;

	// loop
	foreach ( $rules as $rule ) {

		// extract vars
		$group = acf_extract_var( $rule, 'group_no' );
		$order = acf_extract_var( $rule, 'order_no' );

		// calculate group if not defined
		if ( $group === null ) {
			$group = $index;

			// use $anyorall to determine if a new group is needed
			if ( $anyorall == 'any' ) {
				++$index;
			}
		}

		// calculate order if not defined
		if ( $order === null ) {
			$order = isset( $groups[ $group ] ) ? count( $groups[ $group ] ) : 0;
		}

		// append to group
		$groups[ $group ][ $order ] = $rule;

		// sort groups
		ksort( $groups[ $group ] );
	}

	// sort groups
	ksort( $groups );

	// return
	return $groups;
}

/**
 * acf_register_ajax
 *
 * Registers an ajax callback.
 *
 * @since   ACF 5.7.7
 *
 * @param   string  $name     The ajax action name.
 * @param   array   $callback The callback function or array.
 * @param   boolean $public   Whether to allow access to non logged in users.
 * @return  void
 */
function acf_register_ajax( $name = '', $callback = false, $public = false ) {

	// vars
	$action = "acf/ajax/$name";

	// add action for logged-in users
	add_action( "wp_ajax_$action", $callback );

	// add action for non logged-in users
	if ( $public ) {
		add_action( "wp_ajax_nopriv_$action", $callback );
	}
}

/**
 * acf_str_camel_case
 *
 * Converts a string into camelCase.
 * Thanks to https://stackoverflow.com/questions/31274782/convert-array-keys-from-underscore-case-to-camelcase-recursively
 *
 * @since   ACF 5.8.0
 *
 * @param   string $string The string ot convert.
 * @return  string
 */
function acf_str_camel_case( $string = '' ) {
	return lcfirst( str_replace( ' ', '', ucwords( str_replace( '_', ' ', $string ) ) ) );
}

/**
 * acf_array_camel_case
 *
 * Converts all array keys to camelCase.
 *
 * @since   ACF 5.8.0
 *
 * @param   array $array The array to convert.
 * @return  array
 */
function acf_array_camel_case( $array = array() ) {
	$array2 = array();
	foreach ( $array as $k => $v ) {
		$array2[ acf_str_camel_case( $k ) ] = $v;
	}
	return $array2;
}

/**
 * Returns true if the current screen is using the block editor.
 *
 * @since ACF 5.8.0
 *
 * @return boolean
 */
function acf_is_block_editor() {
	if ( function_exists( 'get_current_screen' ) ) {
		$screen = get_current_screen();
		if ( $screen && method_exists( $screen, 'is_block_editor' ) ) {
			return $screen->is_block_editor();
		}
	}
	return false;
}

/**
 * Return an array of the WordPress reserved terms
 *
 * @since ACF 6.1
 *
 * @return array The WordPress reserved terms list.
 */
function acf_get_wp_reserved_terms() {
	return array( 'action', 'attachment', 'attachment_id', 'author', 'author_name', 'calendar', 'cat', 'category', 'category__and', 'category__in', 'category__not_in', 'category_name', 'comments_per_page', 'comments_popup', 'custom', 'customize_messenger_channel', 'customized', 'cpage', 'day', 'debug', 'embed', 'error', 'exact', 'feed', 'fields', 'hour', 'link', 'link_category', 'm', 'minute', 'monthnum', 'more', 'name', 'nav_menu', 'nonce', 'nopaging', 'offset', 'order', 'orderby', 'p', 'page', 'page_id', 'paged', 'pagename', 'pb', 'perm', 'post', 'post__in', 'post__not_in', 'post_format', 'post_mime_type', 'post_status', 'post_tag', 'post_type', 'posts', 'posts_per_archive_page', 'posts_per_page', 'preview', 'robots', 's', 'search', 'second', 'sentence', 'showposts', 'static', 'status', 'subpost', 'subpost_id', 'tag', 'tag__and', 'tag__in', 'tag__not_in', 'tag_id', 'tag_slug__and', 'tag_slug__in', 'taxonomy', 'tb', 'term', 'terms', 'theme', 'themes', 'title', 'type', 'types', 'w', 'withcomments', 'withoutcomments', 'year' );
}

/**
 * Detect if we're on a multisite subsite.
 *
 * @since ACF 6.2.4
 *
 * @return boolean true if we're in a multisite install and not on the main site
 */
function acf_is_multisite_sub_site() {
	if ( is_multisite() && ! is_main_site() ) {
		return true;
	}
	return false;
}

/**
 * Detect if we're on a multisite main site.
 *
 * @since ACF 6.2.4
 *
 * @return boolean true if we're in a multisite install and on the main site
 */
function acf_is_multisite_main_site() {
	if ( is_multisite() && is_main_site() ) {
		return true;
	}
	return false;
}
