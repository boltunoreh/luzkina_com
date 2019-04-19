<?php

class JPIBFI_Visual_Options extends JPIBFI_Options {

	private $image_height = 41;
	private $image_width = 65;

	function get_default_options() {
		$defaults = array(
			'show_button'          => 'hover',
			'button_margin_bottom' => 20,
			'button_margin_top'    => 20,
			'button_margin_left'   => 20,
			'button_margin_right'  => 20,
			'button_position'      => 'top-left',
			'description_option'   => array( 'img_title', 'img_alt', 'post_title' ),
			'pinLinkedImages'      => true,
			'transparency_value'   => 0.2,
			'pin_image'            => 'default',
			'pin_image_button'     => 'square',
			'pin_image_icon'       => 'circle',
			'pin_image_size'       => 'normal',
			'custom_image_url'     => '',
			'custom_image_height'  => 0,
			'custom_image_width'   => 0
		);
		return $defaults;
	}

	function sanitize( $input ) {
		$input = parent::sanitize( $input );
		$save  = false;
		if ( $input['show_button'] != 'hover' ) {
			$input['show_button'] = 'hover';
			$save                 = true;
		}
		if ( $input['pin_image_icon'] != 'circle' ) {
			$input['pin_image_icon'] = 'circle';
			$save                    = true;
		}

		if ( ! is_array( $input['description_option'] ) ) {
			$input['description_option'] = array( 'img_title', 'img_alt', 'post_title' );
			$save                        = true;
		}

		if ( array_key_exists( 'use_custom_image', $input ) ) {
			$use_custom         = $input['use_custom_image'] == true;
			$input['pin_image'] = $use_custom ? 'custom' : 'old_default';
			unset( $input['use_custom_image'] );
			$save = true;
		}

		if ( $save ) {
			$this->update( $input );
		}

		return $input;
	}

	public function get_options_for_view() {
		$options = $this->get();
		if ( $options['pin_image'] == 'custom' ) {
			$options['pinImageWidth']  = $options['custom_image_width'];
			$options['pinImageHeight'] = $options['custom_image_height'];
		} else if ( $options['pin_image'] == 'old_default' ) {
			$options['pinImageWidth']  = $this->image_width;
			$options['pinImageHeight'] = $this->image_height;
		} else {
			$baseHeight = 54;
			switch ( $options['pin_image_size'] ) {
				case 'small':
					$options['pinImageHeight'] = $baseHeight * 0.8;
					break;
				case 'large':
					$options['pinImageHeight'] = $baseHeight * 1.5;
					break;
				case 'normal':
				default:
					$options['pinImageHeight'] = $baseHeight;
					break;
			}

			switch ( $options['pin_image_button'] ) {
				case 'rectangle':
				case 'rounded-rectangle':
					$options['pinImageWidth'] = $options['pinImageHeight'] * 1.5;
					break;
				case 'square':
				case 'rounded-square':
				case 'round':
				default:
					$options['pinImageWidth'] = $options['pinImageHeight'];
					break;
			}
		}
		unset( $options['custom_image_width'], $options['custom_image_height'] );

		return $options;
	}

	public function get_option_name() {
		return 'jpibfi_visual_options';
	}

	function get_types() {
		return array(
			'show_button'          => 'string',
			'button_margin_bottom' => 'int',
			'button_margin_top'    => 'int',
			'button_margin_left'   => 'int',
			'button_margin_right'  => 'int',
			'button_position'      => 'string',
			'description_option'   => 'array',
			'pinLinkedImages'      => 'boolean',
			'transparency_value'   => 'float',
			'pin_image'            => 'string',
			'pin_image_button'     => 'string',
			'pin_image_icon'       => 'string',
			'pin_image_size'       => 'string',
			'custom_image_url'     => 'string',
			'custom_image_height'  => 'int',
			'custom_image_width'   => 'int'
		);
	}
}