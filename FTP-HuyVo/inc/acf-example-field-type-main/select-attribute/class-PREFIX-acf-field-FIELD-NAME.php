<?php

/**
 * Defines the custom field type class.
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * PREFIX_acf_field_FIELD_NAME class.
 */
class PREFIX_acf_field_FIELD_NAME extends \acf_field
{
	/**
	 * Controls field type visibilty in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environment values relating to the theme or plugin.
	 *
	 * @var array $env Plugin or theme context such as 'url' and 'version'.
	 */
	private $env;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		/**
		 * Field type reference used in PHP and JS code.
		 *
		 * No spaces. Underscores allowed.
		 */
		$this->name = 'Choose Attribute';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __('Choose Attribute', 'TEXTDOMAIN');

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'basic'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME

		/**
		 * Field type Description.
		 *
		 * For field descriptions. May contain spaces.
		 */
		$this->description = __('Choose an attribute from the list', 'TEXTDOMAIN');

		/**
		 * Field type Doc URL.
		 *
		 * For linking to a documentation page. Displayed in the field picker modal.
		 */
		$this->doc_url = '#';

		/**
		 * Field type Tutorial URL.
		 *
		 * For linking to a tutorial resource. Displayed in the field picker modal.
		 */
		$this->tutorial_url = '#';

		/**
		 * Defaults for your custom user-facing settings for this field type.
		 */
		$this->defaults = array(
			'attribute'	=> '',
		);

		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("FIELD_NAME", "error");
		 * ```
		 */
		$this->l10n = array(
			'error'	=> __('Error! Please select an attribute', 'TEXTDOMAIN'),
		);

		$this->env = array(
			'url'     => site_url(str_replace(ABSPATH, '', __DIR__)), // URL to the acf-FIELD-NAME directory.
			'version' => '1.0', // Replace this with your theme or plugin version constant.
		);

		/**
		 * Field type preview image.
		 *
		 * A preview image for the field type in the picker modal.
		 */
		$this->preview_image = $this->env['url'] . '/assets/images/field-preview-custom.png';

		parent::__construct();
	}

	/**
	 * Settings to display when users configure a field of this type.
	 *
	 * These settings appear on the ACF “Edit Field Group” admin page when
	 * setting up the field.
	 *
	 * @param array $field
	 * @return void
	 */
	public function render_field_settings($field)
	{
		// Lấy danh sách WooCommerce attributes
		$attribute_choices = array();

		if (function_exists('wc_get_attribute_taxonomies')) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ($attribute_taxonomies) {
				foreach ($attribute_taxonomies as $attr) {
					// Taxonomy đầy đủ: pa_color, pa_size...
					$taxonomy = 'pa_' . $attr->attribute_name;

					// Label đẹp để hiển thị (ví dụ: Color, Size)
					$label = $attr->attribute_label;

					// Nếu muốn hiển thị "Label (pa_name)" cho rõ ràng hơn
					// $label = $attr->attribute_label . ' (' . $taxonomy . ')';

					$attribute_choices[$taxonomy] = $label;
				}
			}
		}

		// Render setting select
		// acf_render_field_setting(
		// 	$field,
		// 	array(
		// 		'label'         => __('Attribute', 'huyvo'),
		// 		'instructions'  => __('Choose a WooCommerce product attribute', 'huyvo'),
		// 		'type'          => 'select',
		// 		'name'          => 'attribute',          // Tên setting, lưu vào $field['attribute']
		// 		'choices'       => $attribute_choices,   // <-- Đây là phần quan trọng!
		// 		'allow_null'    => 1,                    // Optional: cho phép chọn "không có"
		// 		'ui'            => 1,                    // Optional: bật UI select2 đẹp hơn
		// 	)
		// );
	}
	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */
	public function render_field($field)
	{
		// Debug output to show what field data is available.
		$attribute_choices = array();

		if (function_exists('wc_get_attribute_taxonomies')) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ($attribute_taxonomies) {
				foreach ($attribute_taxonomies as $attr) {
					// Taxonomy đầy đủ: pa_color, pa_size...
					$taxonomy = 'pa_' . $attr->attribute_name;

					// Label đẹp để hiển thị (ví dụ: Color, Size)
					$label = $attr->attribute_label;

					// Nếu muốn hiển thị "Label (pa_name)" cho rõ ràng hơn
					// $label = $attr->attribute_label . ' (' . $taxonomy . ')';

					$attribute_choices[$taxonomy] = $label;
				}
			}
		}

		// Display an input field that uses the 'font_size' setting.
?>
		<select
			name="<?php echo esc_attr($field['name']) ?>"
			value="<?php echo esc_attr($field['value']) ?>">
			<option value=""><?php echo __('Select an attribute', 'huyvo') ?></option>
			<?php foreach ($attribute_choices as $key => $value) : ?>
				<option value="<?php echo esc_attr($key) ?>" <?php selected($field['value'], $key) ?>><?php echo esc_html($value) ?></option>
			<?php endforeach; ?>
		</select>
<?php
	}

	/**
	 * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
	 *
	 * Callback for admin_enqueue_script.
	 *
	 * @return void
	 */
	public function input_admin_enqueue_scripts()
	{
		$url     = trailingslashit($this->env['url']);
		$version = $this->env['version'];

		wp_register_script(
			'PREFIX-FIELD-NAME',
			"{$url}assets/js/field.js",
			array('acf-input'),
			$version
		);

		wp_register_style(
			'PREFIX-FIELD-NAME',
			"{$url}assets/css/field.css",
			array('acf-input'),
			$version
		);

		wp_enqueue_script('PREFIX-FIELD-NAME');
		wp_enqueue_style('PREFIX-FIELD-NAME');
	}
}
