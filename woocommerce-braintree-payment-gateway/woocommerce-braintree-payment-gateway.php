<?php
/**
 * Plugin Name: WooCommerce Braintree Payment Gateway
 * Plugin URI: https://wp-ecommerce.net/
 * Description: Braintree Payment Gateway allows you to accept payments on your Woocommerce store. It authorizes credit card payments and processes them securely with your merchant account.
 * Version: 1.9.4
 * Author: wp.insider
 * Author URI: https://wp-ecommerce.net/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least: 5.0
 * WC tested up to: 6.1
 */
//Slug - wcbpg
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Begins execution of the plugin.
 */
add_action( 'plugins_loaded', 'run_WC_braintree_payment_gateway', 2147483647 );

function run_WC_braintree_payment_gateway() {

	/**
	 * Tell WooCommerce that Braintree class exists
	 */
	function add_WC_braintree_payment_gateway( $methods ) {
		$methods[] = 'WC_braintree_payment_gateway';
		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_WC_braintree_payment_gateway' );

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	/**
	 * Braintree gateway class
	 */
	class WC_braintree_payment_gateway extends WC_Payment_Gateway {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->id                 = 'braintree';
			$this->icon               = apply_filters( 'woocommerce_braintree_icon', plugins_url( 'public/images/cards.png', __FILE__ ) );
			$this->has_fields         = true;
			$this->method_title       = 'Braintree';
			$this->method_description = 'Braintree Payment Gateway authorizes credit card payments and processes them securely with your merchant account.';

			// Load the form fields
			$this->init_form_fields();

			// Load the settings
			$this->init_settings();

			// Get setting values
			$this->title       = $this->get_option( 'title' );
			$this->description = $this->get_option( 'description' );
			$this->enabled     = $this->get_option( 'enabled' );
			$this->sandbox     = $this->get_option( 'sandbox' );
			$this->environment = $this->sandbox == 'no' ? 'production' : 'sandbox';
			$this->merchant_id = $this->sandbox == 'no' ? $this->get_option( 'merchant_id' ) : $this->get_option( 'sandbox_merchant_id' );
			$this->private_key = $this->sandbox == 'no' ? $this->get_option( 'private_key' ) : $this->get_option( 'sandbox_private_key' );
			$this->public_key  = $this->sandbox == 'no' ? $this->get_option( 'public_key' ) : $this->get_option( 'sandbox_public_key' );

			// Hooks
			add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
			add_action( 'admin_notices', array( $this, 'checks' ) );
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		/**
		 * Admin Panel Options
		 */
		public function admin_options() {
			?>
<h3><?php _e( 'Braintree', 'woocommerce' ); ?></h3>
<p><?php _e( 'Braintree Payment Gateway authorizes credit card payments and processes them securely with your merchant account.', 'woocommerce' ); ?></p>
<table class="form-table">
			<?php $this->generate_settings_html(); ?>
	<script type="text/javascript">
	jQuery('#woocommerce_braintree_sandbox').change(function() {
		var sandbox = jQuery('#woocommerce_braintree_sandbox_public_key, #woocommerce_braintree_sandbox_private_key, #woocommerce_braintree_sandbox_merchant_id').closest('tr'),
			production = jQuery('#woocommerce_braintree_public_key, #woocommerce_braintree_private_key, #woocommerce_braintree_merchant_id').closest('tr');

		if (jQuery(this).is(':checked')) {
			sandbox.show();
			production.hide();
		} else {
			sandbox.hide();
			production.show();
		}
	}).change();
	</script>
</table>
			<?php
		}

		/**
		 * Check if SSL is enabled and notify the user
		 */
		public function checks() {
			if ( $this->enabled == 'no' ) {
				return;
			}

			// PHP Version
			if ( version_compare( phpversion(), '5.4.0', '<' ) ) {
				echo '<div class="error"><p>' . sprintf( __( 'Braintree Error: Braintree requires PHP 5.4.0 and above. You are using version %s.', 'woocommerce' ), phpversion() ) . '</p></div>';
			}

			// Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected
			elseif ( 'no' == get_option( 'woocommerce_force_ssl_checkout' ) && ! class_exists( 'WordPressHTTPS' ) ) {
				$greater_than_33 = version_compare( '3.3', WC_VERSION );
				$wc_settings_url = admin_url( sprintf( 'admin.php?page=wc-settings&tab=%s', $greater_than_33 ? 'advanced' : 'checkout' ) );

				echo '<div class="error"><p>' . sprintf( __( 'Braintree is enabled, but the <a href="%s">Secure checkout</a> option is disabled; your checkout may not be secure! Please enable SSL and ensure your server has a valid SSL certificate - Braintree will only work in sandbox mode.', 'woocommerce' ), $wc_settings_url ) . '</p></div>';
			}
		}

		/**
		 * Check if this gateway is enabled
		 */
		public function is_available() {
			if ( 'yes' != $this->enabled ) {
				return false;
			}

			if ( ! is_ssl() && 'yes' != $this->sandbox ) {
				return false;
			}

			return true;
		}

		/**
		 * Initialise Gateway Settings Form Fields
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'             => array(
					'title'       => __( 'Enable/Disable', 'woocommerce' ),
					'label'       => __( 'Enable Braintree Payment Gateway', 'woocommerce' ),
					'type'        => 'checkbox',
					'description' => '',
					'default'     => 'no',
				),
				'title'               => array(
					'title'       => __( 'Title', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
					'default'     => __( 'Credit card', 'woocommerce' ),
					'desc_tip'    => true,
				),
				'description'         => array(
					'title'       => __( 'Description', 'woocommerce' ),
					'type'        => 'textarea',
					'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),
					'default'     => 'Pay securely with your credit card.',
					'desc_tip'    => true,
				),
				'sandbox'             => array(
					'title'       => __( 'Sandbox', 'woocommerce' ),
					'label'       => __( 'Enable Sandbox Mode', 'woocommerce' ),
					'type'        => 'checkbox',
					'description' => __( 'Place the payment gateway in sandbox mode using sandbox API keys (real payments will not be taken).', 'woocommerce' ),
					'default'     => 'yes',
				),
				'sandbox_merchant_id' => array(
					'title'       => __( 'Sandbox Merchant ID', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Get your API keys from your Braintree account.', 'woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
				),
				'sandbox_public_key'  => array(
					'title'       => __( 'Sandbox Public Key', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Get your API keys from your Braintree account.', 'woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
				),
				'sandbox_private_key' => array(
					'title'       => __( 'Sandbox Private Key', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Get your API keys from your Braintree account.', 'woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
				),
				'merchant_id'         => array(
					'title'       => __( 'Merchant ID', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Get your API keys from your Braintree account.', 'woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
				),
				'public_key'          => array(
					'title'       => __( 'Public Key', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Get your API keys from your Braintree account.', 'woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
				),
				'private_key'         => array(
					'title'       => __( 'Private Key', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'Get your API keys from your Braintree account.', 'woocommerce' ),
					'default'     => '',
					'desc_tip'    => true,
				),
			);
		}

		private function get_braintree_api() {

			if ( ! function_exists( 'requireDependencies' ) ) {
				require_once 'includes/lib/Braintree.php';
			}

			try {
				Braintree_Configuration::environment( $this->environment );
				Braintree_Configuration::merchantId( $this->merchant_id );
				Braintree_Configuration::publicKey( $this->public_key );
				Braintree_Configuration::privateKey( $this->private_key );
			} catch ( Exception $e ) {
				return false;
			}
			return true;
		}

		/**
		 * Initialise Credit Card Payment Form Fields
		 */
		public function payment_fields() {
			global $woocommerce;
			$this->get_braintree_api();
			try {
				$clientToken = Braintree_ClientToken::generate();
			} catch ( Exception $e ) {
				$eClass = get_class( $e );
				$ret    = 'Braintree Error: ' . $eClass;
				if ( $eClass == 'Braintree\Exception\Authentication' ) {
					$ret = __( 'Braintree Authentication Error. Check your API keys.', 'wp_braintree_lang' );
				}
				echo '<b style="color:red;">' . $ret . '</b>';
				die();
			}
			wp_localize_script(
				'wc-braintree-payment-gateway',
				'Braintree_params',
				array(
					'client_token' => $clientToken,
					'total_amount' => WC()->cart->total,
				)
			);
			?>
<div id="braintree-3ds-modal-container" class="braintree-3ds-modal-container" style="display: none;">
	<div id="braintree-3ds-modal" class="braintree-3ds-modal">
		<div class="braintree-3ds-modal-header">
			<span class="braintree-3ds-modal-close-btn">&times;</span>
		</div>
		<div id="braintree-3ds-modal-content" class="braintree-3ds-modal-content">
		</div>
	</div>
</div>
<div id="braintree-spinner-container" class="braintree-spinner-container" style="display: none;">
	<div class="braintree-spinner"><i></i><i></i><i></i><i></i></div>
</div>
<fieldset id="braintree-cc-form">
	<input type="hidden" id="braintree-payment-nonce" name="braintree-payment-nonce">
	<input type="hidden" id="braintree-error" name="braintree-error" value="">
	<div class="form-row form-row-wide">
		<label for="braintree-card-number"><?php echo __( 'Card Number', 'woocommerce' ); ?> <span class="required">*</span></label>
		<div class="braintree-form-control" id="braintree-card-number"></div>
	</div>

	<div class="form-row form-row-wide">
		<div class="braintree-one-half">
			<label for="braintree-card-expiry-month"><?php echo __( 'Expiry', 'woocommerce' ); ?> <span class="required">*</span></label>
			<div class="braintree-form-control" id="braintree-expiration-month"></div>
			<div class="braintree-form-control" id="braintree-expiration-year"></div>
		</div>
		<div class="braintree-one-half">
			<label for="braintree-card-cvc"><?php echo __( 'Verification Number (CVV)', 'woocommerce' ); ?> <span class="required">*</span></label>
			<div class="braintree-form-control" id="braintree-cvv"></div>
		</div>
	</div>
</fieldset>
			<?php
		}

		/**
		 * Outputs style used for Braintree Payment fields
		 * Outputs scripts used for Braintree Payment
		 */
		public function payment_scripts() {
			if ( ! is_checkout() || ! $this->is_available() ) {
				return;
			}

			wp_register_style( 'wc-braintree-style', plugins_url( 'public/css/woocommerce-braintree-payment-gateway-public.css', __FILE__ ), array(), '20160306', 'all' );
			wp_enqueue_style( 'wc-braintree-style' );

			wp_enqueue_script( 'wc-braintree-client', 'https://js.braintreegateway.com/web/3.88.4/js/client.min.js', array(), null, true );
			wp_enqueue_script( 'wc-braintree-hosted', 'https://js.braintreegateway.com/web/3.88.4/js/hosted-fields.min.js', array(), null, true );
			wp_enqueue_script( 'wc-three-d-secure', 'https://js.braintreegateway.com/web/3.88.4/js/three-d-secure.min.js', array(), null, true );

			wp_enqueue_script( 'wc-braintree-payment-gateway', plugins_url( 'public/js/woocommerce-braintree-payment-gateway-public.js', __FILE__ ), array( 'jquery' ), WC_VERSION, true );
		}

		/**
		 * Process the payment
		 */
		public function process_payment( $order_id ) {
			global $woocommerce;
			$order = new WC_Order( $order_id );

			$braintreeErrArr = array(
				'number'          => __( 'Please check the credit card number.', 'woocommerce' ),
				'cvv'             => __( 'Please check CVV number.', 'woocommerce' ),
				'expirationMonth' => __( 'Please check credit card expiration month.', 'woocommerce' ),
				'expirationYear'  => __( 'Please check credit card expiration year.', 'woocommerce' ),
				'empty'           => __( 'Please fill in the credit card details.', 'woocommerce' ),
				'check'           => __( 'Please check your credit card details.', 'woocommerce' ),
			);

			$braintree_error = filter_input( INPUT_POST, 'braintree-error', FILTER_SANITIZE_STRING );

			if ( ! empty( $braintree_error ) ) {
				$errNotices = explode( ',', $braintree_error );
				foreach ( $errNotices as $errNotice ) {
					if ( ! empty( $errNotice ) ) {
						wc_add_notice( $braintreeErrArr[ $errNotice ], 'error' );
					}
				}
				return array(
					'result'   => 'fail',
					'redirect' => '',
				);
			}

			$this->get_braintree_api();

			$payment_nonce = filter_input( INPUT_POST, 'braintree-payment-nonce', FILTER_SANITIZE_STRING );

			$c_fname = filter_input( INPUT_POST, 'billing_first_name', FILTER_SANITIZE_STRING );
			$c_lname = filter_input( INPUT_POST, 'billing_last_name', FILTER_SANITIZE_STRING );
			$c_phone = filter_input( INPUT_POST, 'billing_phone', FILTER_SANITIZE_STRING );
			$c_email = filter_input( INPUT_POST, 'billing_email', FILTER_SANITIZE_STRING );

			$result = Braintree_Transaction::sale(
				array(
					'amount'             => $order->get_total(), //order_total,
					'paymentMethodNonce' => $payment_nonce,
					'channel'            => 'TipsandTricks_SP',
					'customer'           => array(
						'firstName' => $c_fname,
						'lastName'  => $c_lname,
						'phone'     => $c_phone,
						'email'     => $c_email,
					),
					'options'            => array(
						'submitForSettlement' => true,
					),
				)
			);

			if ( $result->success ) {
				//              echo("Success! Transaction ID: " . $result->transaction->id);
				// Payment complete
				$order->payment_complete( $result->transaction->id );
				// Add order note
				$order->add_order_note( sprintf( __( '%1$s payment approved! Transaction ID: %2$s', 'woocommerce' ), $this->title, $result->transaction->id ) );
				// Remove cart
				WC()->cart->empty_cart();
				// Return thank you page redirect
				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);
			} else {
				if ( $result->transaction ) {
					$errMessage = sprintf( __( '%1$s payment declined.<br />Error: %2$s<br />Code: %3$s', 'woocommerce' ), $this->title, $result->message . ( ! empty( $result->transaction->additionalProcessorResponse ) ? '<br />' . $result->transaction->additionalProcessorResponse : '' ), $result->transaction->processorResponseCode );
					$order->add_order_note( $errMessage );
					wc_add_notice( $errMessage, 'error' );
				} else {
					$exclude = array( 81725 ); //Credit card must include number, paymentMethodNonce, or venmoSdkPaymentMethodCode.
					foreach ( ( $result->errors->deepAll() ) as $error ) {
						if ( ! in_array( $error->code, $exclude ) ) {
							wc_add_notice( __( 'Error', 'woocommerce' ) . ' - ' . $error->message, 'error' );
						}
					}
				}
				return array(
					'result'   => 'fail',
					'redirect' => '',
				);
			}
		}

	}

}

function wcbpg_add_link_to_settings_menu( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=braintree">Settings</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}

add_filter( 'plugin_action_links', 'wcbpg_add_link_to_settings_menu', 10, 2 );
