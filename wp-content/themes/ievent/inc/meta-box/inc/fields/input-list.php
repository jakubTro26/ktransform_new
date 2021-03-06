<?php
/**
 * Input list field.
 */
class RWMB_Input_List_Field extends RWMB_Choice_Field {

	/**
	 * Enqueue scripts and styles
	 */
	public static function admin_enqueue_scripts() {
		wp_enqueue_style( 'rwmb-input-list', ievent_RWMB_CSS_URL . 'input-list.css', array(), ievent_RWMB_VER );
		wp_enqueue_script( 'rwmb-input-list', ievent_RWMB_JS_URL . 'input-list.js', array(), ievent_RWMB_VER, true );
	}

	/**
	 * Walk options
	 *
	 * @param mixed $meta
	 * @param array $field
	 * @param mixed $options
	 * @param mixed $db_fields
	 *
	 * @return string
	 */
	public static function walk( $field, $options, $db_fields, $meta ) {
		$walker = new RWMB_Walker_Input_List( $db_fields, $field, $meta );
		$output = sprintf( '<ul class="rwmb-input-list %s %s">',
			$field['collapse'] ? 'collapse' : '',
		 	$field['inline']   ? 'inline'   : ''
		);
		$output .= $walker->walk( $options, $field['flatten'] ? - 1 : 0 );
		$output .= '</ul>';

		return $output;
	}

	/**
	 * Normalize parameters for field
	 *
	 * @param array $field
	 * @return array
	 */
	public static function normalize( $field ) {
		$field = $field['multiple'] ? RWMB_Multiple_Values_Field::normalize( $field ) : $field;
		$field = RWMB_Input_Field::normalize( $field );
		$field = parent::normalize( $field );
		$field = wp_parse_args( $field, array(
			'collapse' => true,
			'inline'   => null,
		) );

		$field['flatten'] = $field['multiple'] ? $field['flatten'] : true;
		$field['inline'] = ! $field['multiple'] && ! isset( $field['inline'] ) ? true : $field['inline'];

		return $field;
	}

	/**
	 * Get the attributes for a field
	 *
	 * @param array $field
	 * @param mixed $value
	 *
	 * @return array
	 */
	public static function get_attributes( $field, $value = null ) {
		$attributes           = RWMB_Input_Field::get_attributes( $field, $value );
		$attributes['id']     = false;
		$attributes['type']   = $field['multiple'] ? 'checkbox' : 'radio';
		$attributes['value']  = $value;

		return $attributes;
	}
}
