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
 * Plugin Name: Disable Bulk Smush Limit of Smush Image Optimization
 * Plugin URI:  https://github.com/obiPlabon/wp-nonstop-smushit
 * Description: The free version of Smush Image Optimization has a bulk image optimization limit of 50 images per iteration. This plugin disables that limit and allows you to optimize unlimited images.
 * Version:     2.3.0
 * Author:      obiPlabon
 * Author URI:  https://obiPlabon.com/
 * License:     GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-nonstop-smushit
 * Domain Path: /languages/
 * Requires Plugins: wp-smushit
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
		const VERSION = '2.3.0';

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
				add_action( 'admin_footer', [ $this, 'enqueue_scripts' ] );
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
						esc_html__( '%1$s requires the %2$s plugin to function. Please install and activate %2$s first.', 'wp-nonstop-smushit' ),
						'<strong>' . esc_html__( 'Disable Bulk Smush Limit of Smush Image Optimization', 'wp-nonstop-smushit' ) . '</strong>',
						'<a href="' . esc_url( admin_url( 'plugin-install.php?s=wp-smushit&tab=search&type=term' ) ) . '"><strong>' . esc_html__( 'Smush Image Optimization', 'wp-nonstop-smushit' ) . '</strong></a>'
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
		public function enqueue_scripts() {
			?>
			<script>
				;(function(window) {
					'use strict';
					if (!window.MutationObserver) {
						return;
					}

					var observer = new MutationObserver(function(mutations) {
						mutations.forEach(function(mutation) {
							if (mutation.type !== 'attributes' || mutation.attributeName !== 'class') {
								return;
							}

							var exceeded = mutation.target.classList.contains('wp-smush-exceed-limit');
							if (exceeded) {
								const button = mutation.target.querySelector('.wp-smush-resume-bulk-smush') ?? mutation.target.querySelector('.wp-smush-all');
								
								if (button) {
									button.click();
								}
							}
						});
					});

					const container = document.querySelector('.wp-smush-bulk-progress-bar-wrapper');
					if (container) {
						observer.observe(container, {attributes: true});
					}
				})(window);
			</script>
		<?php
		}
	}
}

add_action( 'plugins_loaded', [ 'WP_Nonstop_Smushit', 'get_instance' ], 20 );
