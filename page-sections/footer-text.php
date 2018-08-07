<?php /* Static Name: Footer text */ ?>
<div id="footer-text" class="footer-text">
	<?php $myfooter_text = get_theme_mod('footer_text'); ?>
	
	<?php if($myfooter_text){?>
		<?php echo of_get_option('footer_text'); ?>
	<?php } else { ?>
		<span class="footer-logo"><?php bloginfo('name'); ?></span> &copy; <?php echo date('Y'); ?> &bull; Website Created by <a href="https://www.duckdiverllc.com" target="_blank" class="duck"><i class="dd-duck-icon" style="vertical-align:middle"></i>Duck Diver Marketing</a>
	<?php } ?>
	<?php if( is_front_page() ) { ?>
		<!-- {%FOOTER_LINK} -->
	<?php } ?>
</div>