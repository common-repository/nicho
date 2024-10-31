<?php
/*
Template Name: Nicho Page
Description: That is standard nicho page template.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>

<script>
var NICHO = window.NICHO || {};
NICHO.FRAME_ID = '_nicho_frame';
NICHO.ROOT = '<?php echo site_url(); ?>';
NICHO.ROUTE = '<?php echo esc_attr( get_option('nicho_post_name') ); ?>';
</script>

<div style="margin-left: <?php echo esc_attr( get_option('nicho_post_offset') ); ?>; margin-right: <?php echo esc_attr( get_option('nicho_post_offset') ); ?>;">
  <div id="nicho-page"></div>
  <?php wp_enqueue_script('nicho', 'https://nicho.com/a/scripts/embed.js' ); ?>
</div>
  

<?php get_footer(); ?>
