=== WooCommerce Braintree Payment Gateway ===
Contributors: wp.insider
Donate link: https://wp-ecommerce.net/braintree-payment-gateway-woocommerce
Tags: WooCommerce, Braintree, payment gateway, credit card, braintree plugin, braintreepayments, wordpress payments
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce Braintree Payment Gateway allows you to accept credit card payments on your Woocommerce store.

== Description ==

WooCommerce Braintree Payment Gateway allows you to accept credit card payments on your Woocommerce store via the Braintree gateway. It authorizes credit card payments and processes them securely with your Braintree merchant account.

= Plugin Functionality: =

* Easy to install and configure
* Compatible with WordPress/Woocommerce plugins
* You don't need any extra plugins or scripts to process the transaction
* Accepts all major credit cards
* Automatic Payment Confirmation to update order transaction
* Very Simple Clean Code to add a Braintree payment method to woocommerce

Checkout Payment Form is responsive, adapts nicely to all mobile screen sizes.

Configuring this addon is very easy. Simply go to the following WooCommerce settings area to enable the Braintree gateway and enter your Braintree API details:

WooCommerce Settings -> Checkout -> Braintree

You can find detailed usage instruction with screenshots on the [Braintree Gateway for WooCommerce](https://wp-ecommerce.net/braintree-payment-gateway-woocommerce) extension page.

After that, your customers will be able to select the credit card checkout option on the WooCommere checkout page.

This Plugin __does not__ support WooCommerce Subscriptions, but this functionality is in plan for the near future.

= Developers =

Github repository - https://github.com/wp-insider/woocommerce-braintree-payment-gateway

== Installation ==

= Automatic Installation =
* Login to your WordPress Admin area
* Go to "Plugins > Add New" from the left hand menu
* In the search box type "WooCommerce Braintree Payment Gateway"
* From the search result you will see "WooCommerce Braintree Payment Gateway" click on "Install Now" to install the plugin
* A popup window will ask you to confirm your wish to install the Plugin.

= Manual Installation =
1. Download the plugin zip file
2. Login to your WordPress Admin. Click on "Plugins > Add New" from the left hand menu.
3. Click on the "Upload" option, then click "Choose File" to select the zip file from your computer. Once selected, press "OK" and press the "Install Now" button.
4. Activate the plugin.
5. Open the Settings page for WooCommerce and click the "Checkout" tab.
6. Click on the sub tab for "Braintree".
7. Configure your "Braintree" settings. See below for details.

= Configure the plugin =
To configure the plugin, go to __WooCommerce > Settings__ from the left hand menu, then click "Checkout" from the top tab menu. You should see __"Braintree"__ as an option at the top of the screen. Click on it to configure the payment gateway.

* __Enable/Disable__ - check the box to enable Braintree Payment Gateway.
* __Title__ - allows you to determine what your customers will see this payment option as on the checkout page, default is "Credit card".
* __Description__ - controls the message that appears under the payment fields on the checkout page.
* __Sandbox__ - check the box to enable sandbox mode to test how would payments work for you, real payments will not be taken. Uncheck this option to put It in production mode.
* __Sandbox Merchant ID/Merchant ID__  	- enter your Braintree Merchant ID, this is gotten from your account page on [Braintree website](https://www.braintreepayments.com/).
* __Sandbox Public Key/Public Key__  	- enter your Braintree Public Key, this is gotten from your account page on [Braintree website](https://www.braintreepayments.com/).
* __Sandbox Private Key/Private Key__  	- enter your Braintree Private Key, this is gotten from your account page on [Braintree website](https://www.braintreepayments.com/).
* Click on __Save Changes__ for the changes you made to be effected.

== Frequently Asked Questions ==

= What Do I Need To Use The Plugin =

1. You need to have WooCommerce plugin installed and activated on your WordPress site.
2. You need to open an account on [Braintree website](https://www.braintreepayments.com/).

= Will my customers be able to checkout using their credit cards after I install this plugin? =

Yes

= Does this Plugin support WooCommerce Subscriptions? =

No, It doesn't, but this functionality is in plan for the near future.

= Does this plugin require the SSL? =

As an online merchant, it is your responsibility to make sure the information you collect from your customers is protected so yes, you need it.

== Screenshots ==

1. Configuration page with options for the Plugin. 
2. Frontend shop Checkout page Payment Form. 

== Changelog ==

= 1.4 =
* Fixed a typo.

= 1.3 =
* Upgraded the Braintree library to the latest version.

= 1.2.0 =
* PHP version check changed from 5.2.1 to 5.4.0
* Updated Braintree PHP Client Library to version 3.9.0.
* Added customer information to Braintree.

= 1.1.0 =
* PHP version check changed from 5.3 to 5.2.1
* Updated Braintree PHP Client Library to version 2.35.2. Released December 17, 2014.

= 1.0.0 =
* First release

== Upgrade Notice ==