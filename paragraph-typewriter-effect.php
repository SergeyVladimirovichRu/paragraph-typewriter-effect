<?php
/*
Plugin Name: Paragraph Typewriter Effect
Description: Adds typewriter animation with paragraph-by-paragraph display and erase effect.
Version: 1.0.2
Author: Sergey_Vladimirovich
Author URI: https://anna-ivanovna.ru/
License: GPLv2 or later
Text Domain: paragraph-typewriter-effect
Requires at least: 5.6
*/

defined('ABSPATH') or die('No access!');

class PTW_ParagraphTypewriter {
    private $settings;

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_filter('the_content', [$this, 'add_running_text']);
    }

    public function add_admin_menu() {
        add_options_page(
            __('Typewriter Settings', 'paragraph-typewriter-effect'),
            __('Typewriter', 'paragraph-typewriter-effect'),
            'manage_options',
            'ptw-settings',
            [$this, 'settings_page']
        );
    }

    public function sanitize_settings($input) {
        $output = [];

        // Sanitize checkboxes
        $output['display_pages'] = isset($input['display_pages']) && is_array($input['display_pages']) ?
            array_map('sanitize_text_field', $input['display_pages']) :
            [];

        // Sanitize IDs
        $output['specific_ids'] = isset($input['specific_ids']) ?
            sanitize_text_field($input['specific_ids']) :
            '';

        return $output;
    }

    public function register_settings() {
        // Security check
        if (isset($_POST['ptw_nonce'])) {
            if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ptw_nonce'])), 'ptw_save')) {
                wp_die('Security check failed');
            }
        }

        register_setting(
            'ptw_group',
            'ptw_settings',
            ['sanitize_callback' => [$this, 'sanitize_settings']]
        );

        add_settings_section(
            'ptw_section',
            'Display Settings',
            null,
            'ptw-settings'
        );

        add_settings_field(
            'display_pages',
            'Where to display',
            [$this, 'display_pages_field'],
            'ptw-settings',
            'ptw_section'
        );
    }

    public function display_pages_field() {
        $options = get_option('ptw_settings');
        $selected = isset($options['display_pages']) ? $options['display_pages'] : [];
        ?>
        <p>
            <label>
                <input type="checkbox" name="ptw_settings[display_pages][]" value="all_posts"
                    <?php checked(in_array('all_posts', $selected)); ?> />
                <?php esc_html_e('All posts', 'paragraph-typewriter-effect'); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="ptw_settings[display_pages][]" value="all_pages" <?php checked(in_array('all_pages', $selected)); ?> />
                <?php esc_html_e('All pages', 'paragraph-typewriter-effect'); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="ptw_settings[display_pages][]" value="specific_ids" <?php checked(in_array('specific_ids', $selected)); ?> />
                <?php esc_html_e('Only on specific IDs:', 'paragraph-typewriter-effect'); ?>
                <input type="text" name="ptw_settings[specific_ids]" value="<?php echo esc_attr($options['specific_ids'] ?? ''); ?>" />
            </label>
        </p>
        <?php
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Typewriter Settings', 'paragraph-typewriter-effect'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('ptw_group');
                wp_nonce_field('ptw_save', 'ptw_nonce');
                do_settings_sections('ptw-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function enqueue_assets() {
        if ($this->should_display()) {
            wp_enqueue_style(
                'ptw-style',
                plugins_url('running-text.css', __FILE__),
                [],
                '1.0.1'
            );
            wp_enqueue_script(
                'ptw-script',
                plugins_url('running-text.js', __FILE__),
                ['jquery'],
                '1.0.1',
                true
            );
        }
    }

    private function should_display() {
        $options = get_option('ptw_settings');
        $display = false;

        if (is_singular()) {
            $post_id = get_the_ID();

            if (!empty($options['specific_ids']) && in_array('specific_ids', $options['display_pages'] ?? [])) {
                $ids = array_filter(array_map('absint', explode(',', sanitize_text_field($options['specific_ids'] ?? ''))));
                if (in_array($post_id, $ids)) {
                    $display = true;
                }
            }

            if (!$display && is_single() && in_array('all_posts', $options['display_pages'] ?? [])) {
                $display = true;
            }

            if (!$display && is_page() && in_array('all_pages', $options['display_pages'] ?? [])) {
                $display = true;
            }
        }

        return $display;
    }

    public function add_running_text($content) {
        if ($this->should_display() && !is_admin()) {
            $container_id = 'ptw-container-' . get_the_ID();
            $original_html = wpautop($content);
            $plain_text = wp_strip_all_tags($content, true);
            $paragraphs = explode("\n", $plain_text);
            $paragraphs = array_filter($paragraphs, function($p) {
                return trim($p) !== '';
            });

            ob_start(); ?>
            <div id="<?php echo esc_attr($container_id); ?>" class="ptw-container">
                <?php echo esc_attr($original_html); ?>
            </div>
            <div id="<?php echo esc_attr($container_id); ?>-animation" class="ptw-animation"></div>
            <?php
            return ob_get_clean();
        }
        return $content;
    }
}

if (defined('WP_CLI') && WP_CLI) {
    class PTW_CLI {
        public function test() {
            WP_CLI::success("Paragraph Typewriter is working!");
        }
    }
    WP_CLI::add_command('ptw', 'PTW_CLI');
}

new PTW_ParagraphTypewriter();
