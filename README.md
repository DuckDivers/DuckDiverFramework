# Duck Diver Framework

A starter theme for Duck Diver Marketing, based on _strap (based on _s) theme for WordPress.

Utilizing Bootstrap 4 with an array of shortcodes for simple integration.

## List of Action Hooks
* 'dd_before_header' - Before the masthead
* 'dd_after_header' - After the navbar and </header>
* 'dd_before_slider' - If slider is active.
* 'dd_after_slider'
* 'dd_before_main_content' - After Slider before Main Content Container
* 'dd_before_footer' - After Main Content - Before the Footer
* 'dd_after_footer' - After the Footer before the wp_footer is called.
* 'dd_homepage_scripts' - Additional Scripts for the Homepage footer.  
  * By default, the ('dd_homepage_scripts', 'add_slider_to_homepage', 5); is called to include the slider script.

## List of Filters
* 'dd_main_width' - Defaults to col-md-9 - in use in the sidebar position function.
* 'dd_sidebar_width' - Defaults to col-md-3 - in use in the sidebar position function.
* 'dd_slider_image_size' - defaults to 'slider-post-thumbnail' which is 2000 x 600

### Changelog

== 1.2.22 ==
* Make functions-strap.php pluggable

### 1.2.14
* Make for PHP 7.4
### 1.2.9
* Added dd_slider_image_size filter.
* Added WC parent theme WC support on plugin activation.  Included basic WC functions in pluggable functions for child theme.
* Added WC Sidebar options to customizer.
* Added extra mt-6 class - 4rem;

### 1.2.7
Remove Comment form from Page templates, add slider options to crossfade or slide.

### 1.2.6
Fix for Slider Width

### 1.2.5
Added abilityto remove wpautoP

### 1.2.1
Minor stylesheet revisions. Added CF7 Functions.

### Ver 1.2.0
Update to Bootstrap 4.3.1, Include FB PAGE ID to open social widget links in Facebook App on IOS or Android.  Include class Mobile_Detect

### Ver 1.1.6
Minor tweaks to TinyMCE Plugin to remove old shortcodes from Menu.  Added code for WC Cart.
#### Ver 1.1.3
Added to customer in theme options "Sidebar Position" - left, right, none
#### Ver 1.1.2
Added action hooks and ability to turn off slider and remove CPT from admin.
#### Ver 1.1
Version 1.1 updates framework to be more framework like, and implements bootstrap 4.1.  The inclusion of bootstrap 4 is more solidified with this version, and there's better layout. Resolved the issue with container not being full width because of improper div nesting.

#### Ver 1.0
Initial build as framework. Uses bootstrap 4.0.0. Basically a conversion of the _strap theme to Duck Diver theme.
