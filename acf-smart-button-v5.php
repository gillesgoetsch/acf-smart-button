<?php

class acf_field_smart_button extends acf_field {

	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct() {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		$this->name = 'smart_button';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		$this->label = __('Smart Button', 'acf-smart-button');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		$this->category = 'basic';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		$this->defaults = array();


		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf.__('FIELD_NAME', 'error');
		*/
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-smart-button'),
		);

		/*
		*  setting (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		$this->settings = array(
			'path' => apply_filters( 'acf/helpers/get_path', __FILE__ ),
			'dir' => apply_filters( 'acf/helpers/get_dir', __FILE__ ),
			'version' => '1.0.0'
		);

		// do not delete!
    parent::__construct();

	}


	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	1.0.0
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/

		$field = array_merge( $this->defaults, $field );

		/* add option to select which post type should be used on the post object. default: all */
		acf_render_field_setting( $field, array(
			'label'	=> __('Filter by Post Type', 'acf'),
			'instructions' => '',
			'type' => 'select',
			'name' => 'post_type',
			'choices' => acf_get_pretty_post_types(),
			'multiple' => 1,
			'ui' => 1,
			'allow_null' => 1,
			'placeholder' => __('All post types', 'acf'),
		) );

	}


	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	1.0.0
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {

		$field = array_merge( $this->defaults, $field );

		$field_name = esc_attr( $field['name'] );

		/* overwrite fields with empty values to avoid warning */
		$field['value']['text'] = isset( $field['value']['text'] ) ? $field['value']['text'] : null;
		$field['value']['link'] = isset( $field['value']['link'] ) ? $field['value']['link'] : null;
		$field['value']['post_id'] = isset( $field['value']['post_id'] ) ? $field['value']['post_id'] : null;
		$field['value']['use_external'] = isset( $field['value']['use_external'] ) ? $field['value']['use_external'] : null;

		$required = $field['required'] ? 'required' : null;
		$required_asterisk = $field['required'] ? '<span class="acf-required">*</span>' : null;

		?>

		<table class="acf-smart-button-fields">
			<tr>
				<td valign="top" class="button-text">
					<label><?php _e('Text', 'acf-smart-button'); echo $required_asterisk; ?></label>
					<input type="text" value="<?php echo esc_attr( $field['value']['text'] ); ?>" name="<?php echo $field_name; ?>[text]" class="text"<?php echo $required ?>>
				</td>
				<td valign="top" class="external hidden">
					<label><?php _e('External Link', 'acf-smart-button'); echo $required_asterisk; ?></label>
					<?php
						do_action('acf/render_field/type=url', array(
							'type' => 'text',
							'name' => $field_name . '[link]',
							'value' => $field['value']['link'],
							'id' => $field_name.'_external',
							'class' => 'external',
							'placeholder' => ''
						));
					?>
				</td>
				<td valign="top" class="internal">
					<label><?php _e('Internal Link', 'acf-smart-button'); echo $required_asterisk; ?></label>
					<?php
						// str replace to get raw key (there seems to be no other way?)
						$field_raw_key = str_replace( 'field_', '', $field['key'] );
					?>
					<div class="acf-field acf-field-<?php echo $field_raw_key; ?> acf-field-post-object" data-name="<?php echo $field['_name']; ?>[post_id]" data-type="post_object" data-key="<?php echo $field['key']; ?>">
						<div class="acf-input">
						<?php
							// $types = array('post', 'page');
							@do_action('acf/render_field/type=post_object', array(
								'name' => $field_name . '[post_id]',
								'value' => $field['value']['post_id'],
								// 'post_type' => $types, // Removed so the selection isn't restricted to just posts and pages
								'allow_null' => 1
								//'_name' => 'acf[' . $field['_name'] . '][post_id]',
								//'key' => 'acf[' . $field['key'] . '][post_id]'
							));
						?>
						</div>
					</div>
				</td>
				<td class="switcher-container">
					<label><?php _e('Use external Link', 'acf-smart-button'); ?></label>
					<div class="switcher">
						<div class="button-link-switch">
							<?php
								$switcher_id = $field['id'] . '[use_external_switcher]';
							?>
						  <input type="checkbox" name="<?php echo $field_name; ?>[use_external]" class="button-link-switch-checkbox" id="<?php echo $switcher_id; ?>"<?php if( $field['value']['use_external'] ) { echo ' checked'; } ?>>
						  <label class="button-link-switch-label" for="<?php echo $switcher_id; ?>"></label>
					</div>
				</td>
			</tr>
		</table>

		<?php

	}

	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	function input_admin_enqueue_scripts() {

		$dir = plugin_dir_url( __FILE__ );

		// register & include JS
		wp_register_script( 'acf-smart-button', "{$dir}js/input.js" );
		wp_enqueue_script( 'acf-smart-button' );

		// register & include CSS
		wp_register_style( 'acf-smart-button', "{$dir}css/input.css" );
		wp_enqueue_style( 'acf-smart-button' );

	}


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	1.0.0
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
	function format_value( $value, $post_id, $field ) {

		// directly return false if there is no button text. A button is only valid if there is a valid text and url
		if ( empty( $value['text'] ) ) {
			return false;
		}

		// return url always as same data field, overwrite use_external with true or false for further processing
		// Returns target="_blank" html as separate field to make the view even leaner

		// internal
		if( !array_key_exists( 'use_external', $value ) ) {

			// return false if there is no post_id
			if( empty( $value['post_id'] ) ) {
				return false;

				// add the values
			} else {
				$value['url'] = get_permalink($value['post_id']);

				// empty target, is an empty string on purpose
				$value['target'] = '';
			}

			// external
		} else {

			// return false if there is no link
			if ( empty( $value['link'] ) ) {
				return false;

				// add the values
			} else {
				$value['url'] = $value['link'];

				// set to open in a new window if external
				$value['target'] = 'target="_blank"';
			}
		}

		// unused fields that are not needed
		unset( $value['link'] );
		unset( $value['post_id'] );
		unset( $value['use_external'] );

		return $value;
	}


	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @since	1.0.4
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return $valid
	*/
	function validate_value( $valid, $value, $field, $input ) {

		// store use_external for later use
    $use_external = array_key_exists( 'use_external', $value ) ? true : false;

		// unset use_external and target from value so we can easily check for empty $value
    unset( $value['use_external'] );
    unset( $value['target'] );

    // not required and all fields are empty, so we're good
    if( !$field['required'] && !$value ) {
      return true;
    }

    // not required, but let's make sure corresponding fields are filled so we don't have partial data
    if (!$field['required']) {

      // link is set but no text
      if( ( $value['link'] || $value['post_id'] ) && !$value['text'] ) {
        $valid = __('Text is required with a link. Please either add text or remove the link.', 'acf-smart-button');
      }

      // text is present but no link set
      if ( $value['text'] && ( ( $use_external && !$value['link'] ) || ( !$use_external && !$value['post_id'] ) ) ) {
        $valid = __('A link is required with text. Please either add a link or remove the text.', 'acf-smart-button');
      }

      // field is required
    } else {

      // no text or link set
      if ( !$value['text'] && $value['post_id'] && !$value['link'] ) {
        $valid = __('Both text and link are required', 'acf-smart-button');
      }

      // link is set but no text
      if( ( $value['link'] || $value['post_id'] ) && !$value['text'] ) {
        $valid = __('Text is required', 'acf-smart-button');
      }

      // text is present but no link set
      if ( $value['text'] && ( ( $use_external && !$value['link'] ) || ( !$use_external && !$value['post_id'] ) ) ) {
        $valid = __('A link is required', 'acf-smart-button');
      }

    }

    // return
    return $valid;

	}
}


// create field
new acf_field_smart_button();

?>
