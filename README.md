# Duck Diver Framework

A starter theme based on _strap (based on _s) theme for WordPress.

Utilizing Bootstrap 4 with an array of shortcodes for simple integration.

## List of Action Hooks
* 'dd_before_header' - Before the masthead
* 'dd_after_header' - After the navbar and </header>
* 'dd_before_slider' - If slider is active.
* 'dd_after_slider'
* 'dd_before_main_content' - After Slider before Main Content Container
* 'dd_before_footer' - After Main Content - Before the Footer
* 'dd_after_footer' - After the Footer before the wp_footer is called.

### Changelog

#### Ver 1.1.2
Added action hooks and ability to turn off slider and remove CPT from admin.
#### Ver 1.1
Version 1.1 updates framework to be more framework like, and implements bootstrap 4.1.  The inclusion of bootstrap 4 is more solidified with this version, and there's better layout. Resolved the issue with container not being full width because of improper div nesting.

#### Ver 1.0
Initial build as framework. Uses bootstrap 4.0.0. Basically a conversion of the _strap theme to Duck Diver theme.
