<?php

class acf_field_smart_button extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
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

		$this->label = __('Smart Button');


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
		*  var message = acf._e('FIELD_NAME', 'error');
		*/

		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-smart-button'),
		);

		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
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
	*  @since	3.6
	*  @date	23/01/13
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

		$field = array_merge($this->defaults, $field);

		/* add option to select which post type should be used on the post object. default: all */
		acf_render_field_setting( $field, array(
			'label'	=> __('Filter by Post Type','acf'),
			'instructions' => '',
			'type' => 'select',
			'name' => 'post_type',
			'choices' => acf_get_pretty_post_types(),
			'multiple' => 1,
			'ui' => 1,
			'allow_null' => 1,
			'placeholder' => __("All post types",'acf'),
		));

	}



	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {

		$field = array_merge($this->defaults, $field);

		$field_name = esc_attr( $field['name'] );

		/* overwrite fields with empty values to avoid warning */
		$field['value']['text'] = isset($field['value']['text']) ? $field['value']['text'] : null;
		$field['value']['link'] = isset($field['value']['link']) ? $field['value']['link'] : null;
		$field['value']['post_id'] = isset($field['value']['post_id']) ? $field['value']['post_id'] : null;
		$field['value']['use_external'] = isset($field['value']['use_external']) ? $field['value']['use_external'] : null;

		?>

			<table class="acf-smart-button-fields">
				<tr>
					<td valign="top">
						<label><?php _e('text', 'acf-smart-button'); ?></label>
						<input type="text" value="<?php echo esc_attr( $field['value']['text'] ); ?>" name="<?php echo $field_name; ?>[text]" class="text" />
					</td>
					<td valign="top" class="external hidden">
						<label><?php _e('External Link', 'acf-smart-button'); ?></label>
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
						<label><?php _e('Internal Link', 'acf-smart-button'); ?></label>
						<?php
							// str replace to get raw key (there seems to be no other way?)
							$field_raw_key = str_replace('field_', '', $field['key']);
						?>
						<div class="acf-field acf-field-<?php echo $field_raw_key; ?> acf-field-post-object" data-name="<?php echo $field['_name']; ?>[post_id]" data-type="post_object" data-key="<?php echo $field['key']; ?>">
							<div class="acf-input">
							<?php
								$types = array('post', 'page');

								@do_action('acf/render_field/type=post_object', array(
									'name' => $field_name . '[post_id]',
									'value' => $field['value']['post_id'],
									'post_type' => $types,
									'allow_null' => 1
									//'_name' => 'acf[' . $field['_name'] . '][post_id]',
									//'key' => 'acf[' . $field['key'] . '][post_id]'
								));
							?>
							</div>
						</div>
					</td>
					<td>
						<label><?php _e('Use external Link', 'acf-smart-button'); ?></label>
						<div class="switcher">
							<div class="button-link-switch">
								<?php
									$swticher_id = $field['id'] . '[use_external_swichter]';
								?>
							    <input type="checkbox" name="<?php echo $field_name; ?>[use_external]" class="button-link-switch-checkbox" id="<?php echo $swticher_id; ?>" <?php if($field['value']['use_external']) { echo 'checked'; } ?>>
							    <label class="button-link-switch-label" for="<?php echo $swticher_id; ?>"></label>
							</div>
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
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function input_admin_enqueue_scripts() {

		$dir = plugin_dir_url( __FILE__ );

		// register & include JS
		wp_register_script( 'acf-smart-button', "{$dir}js/input.js" );
		wp_enqueue_script('acf-smart-button');


		// register & include CSS
		wp_register_style( 'acf-smart-button', "{$dir}css/input.css" );
		wp_enqueue_style('acf-smart-button');

		wp_enqueue_script(array(
			'acf-smart-button',
		));

	}

	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_head() {



	}

	*/


	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/

   	/*

   	function input_form_data( $args ) {



   	}

   	*/


	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_footer() {



	}

	*/


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_enqueue_scripts() {

	}

	*/


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_head() {

	}

	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	/*

	function load_value( $value, $post_id, $field ) {

		return $value;

	}

	*/


	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	/*

	function update_value( $value, $post_id, $field ) {

		return $value;

	}

	*/


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/


	function format_value( $value, $post_id, $field ) {

		// directly return false if there is no button text. A button is only valid if there is a valid text and url
		if( empty($value['text']) ) {
			return false;
		}

		// return url always as same data field, overwrite use_external with true or false for further processing
		// Returns target="_blank" html as separate field to make the view even leaner
		if(!array_key_exists('use_external', $value)) {
			// internal
			if( empty($value['post_id']) ) { // return false if there is no post_id
				return false;
			}else { // add the values
				$value['url'] = get_permalink($value['post_id']);
				$value['target'] = ''; // empty target, is an empty string on purpose
			}
		} else {
			// external
			if( empty($value['link']) ) { // return false if there is no link
				return false;
			}else { // add the values
				$value['url'] = $value['link'];
				$value['target'] = 'target="_blank"'; // set to open in a new window if external
			}
		}

		// unsed fields that are not needed (or do you?)
		unset($value['link']);
		unset($value['post_id']);
		unset($value['use_external']);

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
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/

	/*

	function validate_value( $valid, $value, $field, $input ){

		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}


		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-FIELD_NAME'),
		}


		// return
		return $valid;

	}

	*/


	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/

	/*

	function delete_value( $post_id, $key ) {



	}

	*/


	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function load_field( $field ) {

		return $field;

	}

	*/


	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function update_field( $field ) {

		return $field;

	}

	*/


	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/

	/*

	function delete_field( $field ) {



	}

	*/


}


// create field
new acf_field_smart_button();

?>
