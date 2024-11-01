<?php
/**
 * Plugin Name: WP Pranks
 * Plugin URI: https://wordpress.org/plugins/wp-pranks/
 * Description: A playful plugin with several options to pull a joke/prank on your friends.
 * Version: 1.0
 * Author: Webvolution
 * Author URI: https://www.wordpress.org
 * Terms:
 *    By using this plugin you are agreeing to the following terms:
 *    Only prank respected colleagues and those above you in the organizational hierarchy, no pranking subordinates.
 *    Never prank anyone you don't like or disagree with.
 *    These pranks should not cost anyone money, shame, slander, or damage reputations by exposing the prank to current or potential clients.
 *    Apply pranks only if the person can handle it and only once.  Multiple pranks becomes harassment.
 *    Know the rules, if in doubt ask the most important person in your office and check HR.
 *    Plugin author is not liable for any damages resulting from plugin or codebase.

 *    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *    SOFTWARE.
 */

function wp_pranks_footer_hook() { ?>
  <style>
    <?php
       $webv_gcc_options = get_option('wp_pranks_setting_option_name');
       if (isset($webv_gcc_options['select_a_prank_1']) && !empty($webv_gcc_options['select_a_prank_1'])) {
         switch ($webv_gcc_options['select_a_prank_1']) {
           case 'prank-1':
             ?>
    /* bw */
    html {
      -webkit-filter: grayscale(100%) !important;
      filter: grayscale(100%) !important;
    }
    <?php
    break;
  case 'prank-2':
    ?>
    /* upside down */
    html {
      -webkit-transform:rotate(-180deg) !important;
      -moz-transform:rotate(-180deg)  !important;
      -o-transform:rotate(-180deg)  !important;
      transform:rotate(-180deg)  !important;
      ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=2)"  !important;
      filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=2)  !important;
    }
    <?php
    break;
    case 'prank-3':
      ?>
    /* hide off numbered paragraphs */
    p:nth-child(odd) { display:none !important; }
    <?php
  break;
  case 'prank-4':
    ?>
    /* blurry page */
    body { -webkit-filter: blur(2px) !important; }
    <?php
  break;
  case 'prank-5':
    ?>
    /* poop emoji upper left */
    body::before {
      content: "💩" !important;
      position: fixed !important;
      font-size: 2rem !important;
    }
    <?php
  break;
  case 'prank-6':
    ?>
    /* comics sans */
    * { font-family: "Comic Sans MS", "Comic Sans", cursive !important; }
    <?php
  break;
  case 'prank-7':
    ?>
    /* large font */
    * { font-size: 250px !important; }
    <?php
  break;
default: break;
}
}
?>
  </style>
<?php }

add_action('wp_footer', 'wp_pranks_footer_hook');
class Webv_Pranks_SettingsPage {

  private $pranks_setting_options;

  public function __construct() {
    add_action( 'admin_menu', array( $this, 'wp_pranks_setting_add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'wp_pranks_setting_page_init' ) );
  }

  public function wp_pranks_setting_add_plugin_page() {
    add_options_page(
      esc_html__('WP Pranks Setting','webv-pranks-admin-page-title'), // page_title
      esc_html__('WP Pranks','webv-pranks-admin-menu-title'), // menu_title
      'manage_options', // capability
      'webv-pranks-setting-admin', // menu_slug
      array( $this, 'wp_pranks_setting_create_admin_page' ) // function
    );
  }

  public function wp_pranks_setting_create_admin_page() {
    $this->pranks_setting_options = get_option( 'wp_pranks_setting_option_name' ); ?>

    <div class="wrap">
      <h2><?php echo esc_html__('WP Pranks','webv-pranks-admin-headline'); ?></h2>
      <p>
      <ul>
        <li><?php echo esc_html__('B&W - Turns website into black and white like the old days.','webv-pranks-admin-bw-desc'); ?></li>
        <li><?php echo esc_html__('Upside Down - Turns website upside-down.','webv-pranks-admin-upsidedown-desc'); ?></li>
        <li><?php echo esc_html__('Hide All Odd Numbered Paragraphs - Self explanatory, come on.','webv-pranks-admin-odd-p-desc'); ?></li>
        <li><?php echo esc_html__('Blurry - Turns website so blurry even eye-glasses won\'t help.','webv-pranks-admin-blurry-desc'); ?></li>
        <li><?php echo esc_html__('Poop Emoji - Puts a poop emoji in upper left of the page.','webv-pranks-admin-poop-desc'); ?></li>
        <li><?php echo esc_html__('Comics Sans - Turns text to use the Comic Sans font.','webv-pranks-admin-comic-sans-desc'); ?></li>
        <li><?php echo esc_html__('Insanely Large Text - Makes fonts larger for those who have really really bad eye-sight.','webv-pranks-admin-large-text-desc'); ?></li>
      </ul>
      </p>

      <form method="post" action="options.php">
        <?php
        settings_fields( 'wp_pranks_setting_option_group' );
        do_settings_sections( 'webv-pranks-setting-admin-admin' );
        submit_button();
        ?>
      </form>
    </div>
  <?php }

  public function wp_pranks_setting_page_init() {
    register_setting(
      'wp_pranks_setting_option_group', // option_group
      'wp_pranks_setting_option_name', // option_name
      array( $this, 'wp_pranks_setting_sanitize' ) // sanitize_callback
    );

    add_settings_section(
      'wp_pranks_setting_setting_section', // id
      esc_html__('Settings','webv-pranks-admin-h2-label'), // title
      null, // callback
      'webv-pranks-setting-admin-admin' // page
    );

    add_settings_field(
      'select_a_prank_1', // id
      esc_html__('Select A Prank','webv-pranks-admin-select-label'), // title
      array( $this, 'select_a_prank_1_callback' ), // callback
      'webv-pranks-setting-admin-admin', // page
      'wp_pranks_setting_setting_section' // section
    );
  }

  public function wp_pranks_setting_sanitize($input) {
    $sanitary_values = array();

    if ( isset( $input['select_a_prank_1'] ) ) {
      $sanitary_values['select_a_prank_1'] = $input['select_a_prank_1'];
    }

    return $sanitary_values;
  }

  public function select_a_prank_1_callback() {
    ?>
    <select name="wp_pranks_setting_option_name[select_a_prank_1]" id="select_a_prank_1">
      <?php $selected = (isset( $this->pranks_setting_options['select_a_prank_1'] ) && $this->pranks_setting_options['select_a_prank_1'] === 'prank-0') ? 'selected' : '' ; ?>
      <option value="prank-0" <?php echo $selected; ?>><?php echo esc_html__('Choose...(Turned Off)','webv-pranks-admin-off-dropdown-label'); ?></option>
      <?php $selected = (isset( $this->pranks_setting_options['select_a_prank_1'] ) && $this->pranks_setting_options['select_a_prank_1'] === 'prank-1') ? 'selected' : '' ; ?>
      <option value="prank-1" <?php echo $selected; ?>><?php echo esc_html__('B&W','webv-pranks-admin-bw-dropdown-label'); ?></option>
      <?php $selected = (isset( $this->pranks_setting_options['select_a_prank_1'] ) && $this->pranks_setting_options['select_a_prank_1'] === 'prank-2') ? 'selected' : '' ; ?>
      <option value="prank-2" <?php echo $selected; ?>><?php echo esc_html__('Upside Down','webv-pranks-admin-upsidedown-dropdown-label'); ?></option>
      <?php $selected = (isset( $this->pranks_setting_options['select_a_prank_1'] ) && $this->pranks_setting_options['select_a_prank_1'] === 'prank-3') ? 'selected' : '' ; ?>
      <option value="prank-3" <?php echo $selected; ?>><?php echo esc_html__('Hide All Odd Numbered Paragraphs','webv-pranks-admin-odd-p-dropdown-label'); ?></option>
      <?php $selected = (isset( $this->pranks_setting_options['select_a_prank_1'] ) && $this->pranks_setting_options['select_a_prank_1'] === 'prank-4') ? 'selected' : '' ; ?>
      <option value="prank-4" <?php echo $selected; ?>><?php echo esc_html__('Blurry','webv-pranks-admin-blurry-dropdown-label'); ?></option>
      <?php $selected = (isset( $this->pranks_setting_options['select_a_prank_1'] ) && $this->pranks_setting_options['select_a_prank_1'] === 'prank-5') ? 'selected' : '' ; ?>
      <option value="prank-5" <?php echo $selected; ?>><?php echo esc_html__('Poop Emoji','webv-pranks-admin-poop-dropdown-label'); ?></option>
      <?php $selected = (isset( $this->pranks_setting_options['select_a_prank_1'] ) && $this->pranks_setting_options['select_a_prank_1'] === 'prank-6') ? 'selected' : '' ; ?>
      <option value="prank-6" <?php echo $selected; ?>><?php echo esc_html__('Comic Sans','webv-pranks-admin-comic-sans-dropdown-label'); ?></option>
      <?php $selected = (isset( $this->pranks_setting_options['select_a_prank_1'] ) && $this->pranks_setting_options['select_a_prank_1'] === 'prank-7') ? 'selected' : '' ; ?>
      <option value="prank-7" <?php echo $selected; ?>><?php echo esc_html__('Insanely Large Text','webv-pranks-admin-large-text-dropdown-label'); ?></option>
    </select> <?php
  }
}

if ( is_admin() )
  $pranks_setting = new Webv_Pranks_SettingsPage();