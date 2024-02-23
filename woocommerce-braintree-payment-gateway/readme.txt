=== WooCommerce Braintree Payment Gateway ===
Contributors: wp.insider
Donate link: https://wp-ecommerce.net/braintree-payment-gateway-woocommerce
Tags: WooCommerce, Braintree, payment gateway, braintreepayments
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.9.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce Braintree Payment Gateway allows you to accept credit card payments on your Woocommerce store.

== Description ==

WooCommerce Braintree Payment Gateway allows you to accept credit card payments on your Woocommerce store via the Braintree gateway. It authorizes credit card payments and processes them securely with your Braintree merchant account.

It is compatible with WooCommerce High-Performance Order Storage (HPOS) feature.

= Plugin Functionality: =

* Easy to install and configure
* Compatible with WordPress/Woocommerce plugins
* You don't need any extra plugins or scripts to process the transaction
* Accepts all major credit cards
* It has the 3D secure payment support
* Automatic Payment Confirmation to update order transaction
* Very Simple Clean Code to add a Braintree payment method to woocommerce

Checkout Payment Form is responsive, adapts nicely to all mobile screen sizes.

Configuring this addon is very easy. Simply go to the following WooCommerce settings area to enable the Braintree gateway and enter your Braintree API details:

WooCommerce Settings -> Checkout -> Braintree

You can find detailed usage instruction with screenshots on the [Braintree Gateway for WooCommerce](https://wp-ecommerce.net/braintree-payment-gateway-woocommerce) extension page.

After that, your customers will be able to select the credit card checkout option on the WooCommere checkout page.

This Plugin __does not__ support WooCommerce Subscriptions, but this functionality is in plan for the near future.

= 3D Secure Payment Option =

This Braintree addon supports 3D secure payment option. If you want to use [3D Secure](https://articles.braintreepayments.com/guides/fraud-tools/3d-secure) Payment then you just need to enable that option in your Braintree merchant account. You can contact Braintree support to enable 3D secure in your live account.

Also works with 3DS v2.

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

= 1.9.8 =
* Added HPOS feature compatibility.

= 1.9.7 =
* Removed the admin notice for the secure checkout option which has been removed from WooCommerce.

= 1.9.6 =
* Updated for WooCommerce v8.3.1 
* Tested on latest WP version.

= 1.9.5 =
* Updated to work with 3DS version 2. 
* Updated the Braintree SDK to latest.

= 1.9.4 =
* Postponed plugin init to ensure other addons have loaded.
* Modified plugin code to follow WP Coding Standards.

= 1.9.3 =
* Updated Braintree PHP SDK to prevent deprecation notice when using PHP 7+.

= 1.9.2 =
* Fixed WooCommerce settings URL in force SSL notice.

= 1.9.1 =
* Fixed inability to proceed with payment when 3D Secure is not enabled in Braintree account.

= 1.9 =
* Added 3D Secure support.

= 1.8 =
* Added a function_exists check to see if Braintree library is already loaded.

= 1.7 =
* Replaced a deprecated call with the new method.
* Added a link to the addon's settings menu.

= 1.6 =
* Added woocommerce tested upto version number in the plugin header.

= 1.5 = 
* Display error when the transaction is declined. Thanks to @bolint for making this change.

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