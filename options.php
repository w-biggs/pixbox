<?php
/**
 * Pixbox option page.
 * 
 * @package pixbox
 * @since 0.1.0
 */

add_action('admin_menu', 'pixbox_create_options_menu');

function pixbox_create_options_menu(){
  add_submenu_page(
    'pixbox/albums.php',
    'Pixbox Settings',
    'Settings',
    'manage_options',
    'pixbox/options.php',
    'pixbox_settings_page'
  );
  add_action( 'admin_init', 'register_pixbox_settings' );
}

function register_pixbox_settings(){
  register_setting( 'pixbox-settings', 'password_expiry_age', array('default' => 30) );
  register_setting( 'pixbox-settings', 'pixbox_page');
  register_setting( 'pixbox-settings', 'pixbox_wrapper_classes');
}

function pixbox_settings_page(){
?>
  <div class="wrap">
    <h1>Pixbox</h1>
    <form action="options.php" method="post">
      <?php settings_fields('pixbox-settings'); ?>
      <?php do_settings_sections('pixbox-settings'); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row" for="password_expiry_age">Password Expiry Age (in days)</th>
          <td>
            <input type="number" name="password_expiry_age" value="<?= esc_attr(get_option('password_expiry_age')) ?>">
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" for="pixbox_page">Pixbox Page</th>
          <td>
            <?php wp_dropdown_pages(array(
              'name'             => 'pixbox_page',
              'selected'         => get_option('pixbox_page', 0),
              'show_option_none' => '--None--',
              'post_status'      => array('publish', 'draft')
            )); ?>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" for="pixbox_wrapper_classes">Pixbox Wrapper Classes</th>
          <td>
            <input type="text" name="pixbox_wrapper_classes" value="<?= esc_attr(get_option('pixbox_wrapper_classes')) ?>">
            <p class="description">Any CSS classes to add to the Pixbox wrapper div on the front-end.</p>
          </td>
        </tr>
      </table>
      <?php submit_button(); ?>
      <small>
        Folder icon by <a href="https://github.com/fabianalexisinostroza/Antu-icons">Fabián Alexis</a>.
      </small>
    </form>
  </div>
<?php
}