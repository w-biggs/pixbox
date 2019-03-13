<?php
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
          <th scope="row">Password Expiry Age</th>
          <td>
            <input type="number" name="password_expiry_age" value="<?= esc_attr(get_option('password_expiry_age')) ?>">
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
