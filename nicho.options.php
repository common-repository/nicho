<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="wrap">
  <h2>Nicho Plugin Options</h2>
  <hr>

  <form method="post" action="options.php">
    <?php settings_fields( 'nicho-settings-group' ); ?>
    <?php do_settings_sections( 'nicho-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Nicho Page Name (slug)</th>
        <td><input type="text" name="nicho_post_name" value="<?php echo esc_attr( get_option('nicho_post_name') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Nicho Page Title</th>
        <td><input type="text" name="nicho_post_title" value="<?php echo esc_attr( get_option('nicho_post_title') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Nicho Page ID</th>
        <td><?php echo esc_attr( get_option('nicho_post_id') ); ?> <input type="hidden" name="nicho_post_id" value="<?php echo esc_attr( get_option('nicho_post_id') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Nicho Url</th>
        <td><input type="text" name="nicho_url" value="<?php echo esc_attr( get_option('nicho_url') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Nicho Iframe Left and Right Margin</th>
        <td><input type="text" name="nicho_post_offset" value="<?php echo esc_attr( get_option('nicho_post_offset') ); ?>" /></td>
        </tr>
    </table>

    <?php submit_button(); ?>
  </form>
</div>
