<?php
/**
 * WP Nonstop Smushit plugin
 *
 * Disable bulk smash limit and enjoy a premium feature of WP Smashit completely FREE!
 *
 * @link        https://github.com/obiPlabon/wp-nonstop-smushit
 * @since       1.0.0
 * @package     WP_Nonstop_Smushit
 *
 * Plugin Name: Smush Nonstop
 * Plugin URI:  https://github.com/obiPlabon/wp-nonstop-smushit
 * Description: Disable bulk smash limit and enjoy one of the most exciting premium feature of <a href="https://wordpress.org/plugins/wp-smushit/" target="_blank">WP Smashit</a> completely FREE 😉
 * Version:     2.0.1
 * Author:      obiPlabon
 * Author URI:  https://obiPlabon.im/
 * License:     GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-nonstop-smushit
 * Domain Path: /languages/
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2019 obiPlabon <https://obiPlabon.im>
*/

defined( 'ABSPATH' ) || die();

if ( ! class_exists( 'WP_Nonstop_Smushit' ) ) {
    class WP_Nonstop_Smushit {

        /**
         * Plugin version number
         */
        const VERSION = '2.0.1';

        /**
         * Plugin slug
         */
        const SLUG = 'wp-nonstop-smushit';

        /**
         * WP_Nonstop_Smushit instance
         *
         * @var null
         */
        protected static $instance = null;

        /**
         * Get instance
         *
         * @return null|WP_Nonstop_Smushit
         */
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * WP_Nonstop_Smushit constructor.
         */
        protected function __construct() {
            if ( ! $this->has_wp_smushit() ) {
                add_action( 'admin_notices', [ $this, 'show_dependency_missing_error' ] );
            } else {
                add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
            }
        }

        /**
         * Check the existence of WP Smushit plugin
         * @return bool
         */
        protected function has_wp_smushit() {
            return defined( 'WP_SMUSH_VERSION' ) || class_exists( '\Smush\WP_Smush' ) || class_exists( 'WP_Smush' );
        }

        /**
         * Show dependency missing error message.
         *
         * @return void
         */
        public function show_dependency_missing_error() {
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php printf(
                        esc_html__( '%1$s requires %2$s to be installed. Please install %2$s otherwise there is no point in installing %1$s.', 'wp-nonstop-smushit' ),
                        '<strong>' . esc_html__( 'WP Nonstop Smushit', 'wp-nonstop-smushit' ) . '</strong>',
                        '<mark>' . esc_html__( 'WP Smushit', 'wp-nonstop-smushit' ) . '</mark>'
                    ); ?></p>
            </div>
            <?php
        }

        /**
         * Enqueue required assets.
         *
         * @param $page
         *
         * @return void
         */
        public function enqueue_scripts( $page ) {
            $white_list = [ 'toplevel_page_smush', 'smush_page_smush-bulk' ];
            if ( in_array( $page, $white_list ) ) {
                wp_enqueue_script(
                    self::SLUG,
                    plugin_dir_url( __FILE__ ) . 'assets/js/main.js',
                    null,
                    self::VERSION,
                    true
                );
            }
        }
    }
}

add_action( 'plugins_loaded', [ 'WP_Nonstop_Smushit', 'get_instance' ], 20 );
