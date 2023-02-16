<?php
/**
 * The admin advanced settings page functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.4.4
 *
 * @package    woo-checkout-field-editor-pro
 * @subpackage woo-checkout-field-editor-pro/admin
 */

if(!defined('WPINC')){	die; }

if(!class_exists('THWCFD_Admin_Settings_Pro')):

class THWCFD_Admin_Settings_Pro extends THWCFD_Admin_Settings{
	protected static $_instance = null;
	protected $tabs = '';

	private $settings_fields = NULL;
	private $cell_props = array();
	private $cell_props_CB = array();

	public function __construct() {
		parent::__construct();
		$this->page_id = 'pro';
	}
	
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function render_page(){
		$this->render_tabs();
		$this->render_content();
	}
	
	private function render_content(){
		?>
		<div class="th-nice-box">
		    <h2>Key Features of WooCommerce Checkout Field Editor Pro</h2>
		    <p><b>Checkout Field Editor For WooCommerce</b> plugin comes with several advanced features that let you create an organized checkout page. With these premium features, bring your checkout page to its next level.</p>
		    <ul class="feature-list star-list">
		        <li>24 Custom Checkout Field Types</li>
		        <li>Custom section which can be placed at 15 different positions on the checkout page</li>
		        <li>Display fields conditionally</li>
		        <li>Address autofill suggestion</li>
		        <li>Display sections conditionally</li>
		        <li>Price fields with a set of price types</li>
		        <li>Custom validations</li>
		        <li>Change address display format</li>
		        <li>Display fields based on Shipping option or Payment method</li>
		        <li>Compatibility with other plugins</li>
		        <li>Zapier support</li>
		        <li>WPML Compatibility</li>
		        <li>Reset all settings on a single click</li>
		        <li>Manage field display in emails and order details pages</li>
		        <li>Display custom fields optionally at My Account page</li>
		        <li>Customise, Disable or delete default WooCommerce fields</li>
		        <li>Developer friendly with custom hooks</li>
		        <li>Rearrange all fields and sections as per convenience</li>
		        <li>Create your own custom classes for styling the field</li>
		    </ul>
		    <p>
		    	<a class="button big-button" target="_blank" href="https://www.themehigh.com/product/woocommerce-checkout-field-editor-pro/?utm_source=free&utm_medium=premium_tab&utm_campaign=wcfe_upgrade_link">Upgrade to Premium Version</a>
		    	<a class="button big-button" target="_blank" href="https://flydemos.com/wcfe/?utm_source=free&utm_medium=banner&utm_campaign=trydemo" style="margin-left: 20px">Try Demo</a>
			</p>
		</div>
		<div class="th-flexbox">
		    <div class="th-flexbox-child th-nice-box">
		        <h2>Available Field types</h2>
		        <p>Following are the custom checkout field types available in the Checkout Field Editor plugin.</p>
		        <ul class="feature-list">
		            <li>Text</li>
		            <li>Hidden</li>
		            <li>Password</li>
		            <li>Telephone</li>
		            <li>Email</li>
		            <li>Number</li>
		            <li>Textarea</li>
		            <li>Select</li>
		            <li>Multi Select</li>
		            <li>Radio</li>
		            <li>Checkbox</li>
		            <li>Checkbox Group</li>
		            <li>Date picker</li>
		            <li>Datetime local</li>
		            <li>Date</li>
		            <li>Time picker</li>
		            <li>Time</li>
		            <li>Month</li>
		            <li>Week</li>
		            <li>File Upload</li>
		            <li>Heading</li>
		            <li>Paragraph</li>
		            <li>Label</li>
		            <li>URL</li>
		        </ul>
		    </div>
		    <div class="th-flexbox-child th-nice-box">
		        <h2>Display Sections Conditionally</h2>
		        <p>Display the custom sections on your checkout page based on the conditions you set. Following are the positions where these checkout sections can be displayed:</p>
		        <ul class="feature-list">
		            <li>Before customer details</li>
		            <li>After customer details</li>
		            <li>Before billing form</li>
		            <li>After billing form</li>
		            <li>Before shipping form</li>
		            <li>After shipping form</li>
		            <li>Before registration form</li>
		            <li>After registration form</li>
		            <li>Before order notes</li>
		            <li>After order notes</li>
		            <li>Before terms and conditions</li>
		            <li>After terms and conditions</li>
		            <li>Before submit button</li>
		            <li>After submit button</li>
		            <li>Inside a custom step created using WooCommerce MultiStep Checkout</li>
		        </ul>
		    </div>
		</div>
		<div class="th-flexbox">
		    <div class="th-flexbox-child th-nice-box">
		        <h2>Display Fields conditionally</h2>
		        <p>Display the custom and default checkout fields based on the conditions you provide. Conditions on which the fields can be displayed are:</p>
		        <ul class="feature-list">
		            <li>Cart Contents</li>
		            <li>Cart Subtotal</li>
		            <li>Cart Total</li>
		            <li>User Roles</li>
		            <li>Product</li>
		            <li>Product Variation</li>
		            <li>Product type</li>
		            <li>Product Category</li>
		            <li>Shipping Class</li>
		            <li>Shipping Weight</li>
		            <li>Based on other field values</li>
		        </ul>
		    </div>
		    <div class="th-flexbox-child th-nice-box">
		        <h2>Add price fields and choose the price type</h2>
		        <p>With the premium version of the Checkout Page Editor plugin, add an extra price value to the total price by creating a field with price into the checkout form.The available price types that can be added to WooCommerce checkout fields are:</p>
		        <ul class="feature-list">
		            <li>Fixed Price</li>
		            <li>Custom Price</li>
		            <li>Percentage of Cart Total</li>
		            <li>Percentage of Subtotal</li>
		            <li>Percent of Subtotal excluding tax</li>
		            <li>Dynamic Price</li>
		        </ul>
		    </div>
		</div>
		<?php
	}

}

endif;