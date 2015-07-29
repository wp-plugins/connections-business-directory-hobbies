<?php
/**
 * An extension for the Connections plugin which adds a metabox for hobbies.
 *
 * @package   Connections Hobbies
 * @category  Extension
 * @author    Steven A. Zahm
 * @license   GPL-2.0+
 * @link      http://connections-pro.com
 * @copyright 2015 Steven A. Zahm
 *
 * @wordpress-plugin
 * Plugin Name:       Connections Hobbies
 * Plugin URI:        http://connections-pro.com
 * Description:       An extension for the Connections plugin which adds a metabox for hobbies.
 * Version:           1.0
 * Author:            Steven A. Zahm
 * Author URI:        http://connections-pro.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       connections_hobbies
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists('Connections_Hobbies') ) {

	class Connections_Hobbies {

		public function __construct() {

			self::defineConstants();
			self::loadDependencies();

			// This should run on the `plugins_loaded` action hook. Since the extension loads on the
			// `plugins_loaded action hook, call immediately.
			self::loadTextdomain();

			// Register the metabox and fields.
			add_action( 'cn_metabox', array( __CLASS__, 'registerMetabox') );

			// Add the business hours option to the admin settings page.
			// This is also required so it'll be rendered by $entry->getContentBlock( 'hobbies' ).
			add_filter( 'cn_content_blocks', array( __CLASS__, 'settingsOption') );

			// Add the action that'll be run when calling $entry->getContentBlock( 'hobbies' ) from within a template.
			add_action( 'cn_output_meta_field-hobbies', array( __CLASS__, 'block' ), 10, 4 );

			// Add filter to a custom inline style.
			add_filter( 'cn_metabox_checkbox-group_style', array( __CLASS__, 'style' ), 10, 2 );

			// Register the widget.
			add_action( 'widgets_init', array( 'CN_Hobbies_Widget', 'register' ) );
		}

		/**
		 * Define the constants.
		 *
		 * @access  private
		 * @static
		 * @since  1.0
		 * @return void
		 */
		private static function defineConstants() {

			define( 'CNHOBBY_CURRENT_VERSION', '1.0' );
			define( 'CNHOBBY_DIR_NAME', plugin_basename( dirname( __FILE__ ) ) );
			define( 'CNHOBBY_BASE_NAME', plugin_basename( __FILE__ ) );
			define( 'CNHOBBY_PATH', plugin_dir_path( __FILE__ ) );
			define( 'CNHOBBY_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * The widget.
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 * @return void
		 */
		private static function loadDependencies() {

			require_once( CNHOBBY_PATH . 'includes/class.widgets.php' );
		}

		/**
		 * Load the plugin translation.
		 *
		 * Credit: Adapted from Ninja Forms / Easy Digital Downloads.
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 *
		 * @uses   apply_filters()
		 * @uses   get_locale()
		 * @uses   load_textdomain()
		 * @uses   load_plugin_textdomain()
		 *
		 * @return void
		 */
		public static function loadTextdomain() {

			// Plugin textdomain. This should match the one set in the plugin header.
			$domain = 'connections_hobbies';

			// Set filter for plugin's languages directory
			$languagesDirectory = apply_filters( "cn_{$domain}_languages_directory", CN_DIR_NAME . '/languages/' );

			// Traditional WordPress plugin locale filter
			$locale   = apply_filters( 'plugin_locale', get_locale(), $domain );
			$fileName = sprintf( '%1$s-%2$s.mo', $domain, $locale );

			// Setup paths to current locale file
			$local  = $languagesDirectory . $fileName;
			$global = WP_LANG_DIR . "/{$domain}/" . $fileName;

			if ( file_exists( $global ) ) {

				// Look in global `../wp-content/languages/{$domain}/` folder.
				load_textdomain( $domain, $global );

			} elseif ( file_exists( $local ) ) {

				// Look in local `../wp-content/plugins/{plugin-directory}/languages/` folder.
				load_textdomain( $domain, $local );

			} else {

				// Load the default language files
				load_plugin_textdomain( $domain, FALSE, $languagesDirectory );
			}
		}

		/**
		 * Defines the hobby options.
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 *
		 * @uses   apply_filters()
		 *
		 * @return array An associative array containing the hobbies.
		 */
		private static function hobbies() {

			$options = array(
				__( '3D Printing', 'connections_hobbies' ),
				__( 'Acting', 'connections_hobbies' ),
				__( 'Antiquing', 'connections_hobbies' ),
				__( 'Antiquities', 'connections_hobbies' ),
				__( 'Archery', 'connections_hobbies' ),
				__( 'Art Collecting', 'connections_hobbies' ),
				__( 'Astrology', 'connections_hobbies' ),
				__( 'Astronomy', 'connections_hobbies' ),
				__( 'Auto Racing', 'connections_hobbies' ),
				__( 'Backpacking', 'connections_hobbies' ),
				__( 'Badminton', 'connections_hobbies' ),
				__( 'Base Jumping', 'connections_hobbies' ),
				__( 'Baseball', 'connections_hobbies' ),
				__( 'Basketball', 'connections_hobbies' ),
				__( 'Baton Twirling', 'connections_hobbies' ),
				__( 'Beekeeping', 'connections_hobbies' ),
				__( 'Billiards', 'connections_hobbies' ),
				__( 'Birdwatching', 'connections_hobbies' ),
				__( 'Blacksmithing', 'connections_hobbies' ),
				__( 'Board Games', 'connections_hobbies' ),
				__( 'Book Collecting', 'connections_hobbies' ),
				__( 'Book Restoration', 'connections_hobbies' ),
				__( 'Bowling', 'connections_hobbies' ),
				__( 'Boxing', 'connections_hobbies' ),
				__( 'Bridge', 'connections_hobbies' ),
				__( 'Calligraphy', 'connections_hobbies' ),
				__( 'Candle Making', 'connections_hobbies' ),
				__( 'Card Collecting', 'connections_hobbies' ),
				__( 'Cheer Leading', 'connections_hobbies' ),
				__( 'Chess', 'connections_hobbies' ),
				__( 'Climbing', 'connections_hobbies' ),
				__( 'Coin Collecting', 'connections_hobbies' ),
				__( 'Color Guard', 'connections_hobbies' ),
				__( 'Coloring', 'connections_hobbies' ),
				__( 'Comic Book Collecting', 'connections_hobbies' ),
				__( 'Computer Programming', 'connections_hobbies' ),
				__( 'Cooking', 'connections_hobbies' ),
				__( 'Couponing', 'connections_hobbies' ),
				__( 'Cricket', 'connections_hobbies' ),
				__( 'Crocheting', 'connections_hobbies' ),
				__( 'Cryptography', 'connections_hobbies' ),
				__( 'Curling', 'connections_hobbies' ),
				__( 'Cycling', 'connections_hobbies' ),
				__( 'Dancing', 'connections_hobbies' ),
				__( 'Darts', 'connections_hobbies' ),
				__( 'Debate', 'connections_hobbies' ),
				__( 'Drawing', 'connections_hobbies' ),
				__( 'Driving', 'connections_hobbies' ),
				__( 'Electronics', 'connections_hobbies' ),
				__( 'Embroidery', 'connections_hobbies' ),
				__( 'Equestrianism', 'connections_hobbies' ),
				__( 'Fencing', 'connections_hobbies' ),
				__( 'Field Hockey', 'connections_hobbies' ),
				__( 'Fishing', 'connections_hobbies' ),
				__( 'Flag Football', 'connections_hobbies' ),
				__( 'Flying', 'connections_hobbies' ),
				__( 'Footbag', 'connections_hobbies' ),
				__( 'Football', 'connections_hobbies' ),
				__( 'Foraging', 'connections_hobbies' ),
				__( 'Frisbee', 'connections_hobbies' ),
				__( 'Gaming', 'connections_hobbies' ),
				__( 'Gardening', 'connections_hobbies' ),
				__( 'Genealogy', 'connections_hobbies' ),
				__( 'Geocaching', 'connections_hobbies' ),
				__( 'Geology', 'connections_hobbies' ),
				__( 'Glassblowing', 'connections_hobbies' ),
				__( 'Golf', 'connections_hobbies' ),
				__( 'Gymnastics', 'connections_hobbies' ),
				__( 'Handball', 'connections_hobbies' ),
				__( 'Hiking', 'connections_hobbies' ),
				__( 'Hockey', 'connections_hobbies' ),
				__( 'Home Brewing', 'connections_hobbies' ),
				__( 'Hunting', 'connections_hobbies' ),
				__( 'Ice Skating', 'connections_hobbies' ),
				__( 'Insect Collecting', 'connections_hobbies' ),
				__( 'Jewelry Making', 'connections_hobbies' ),
				__( 'Jigsaw Puzzles', 'connections_hobbies' ),
				__( 'Jogging', 'connections_hobbies' ),
				__( 'Juggling', 'connections_hobbies' ),
				__( 'Kart Racing', 'connections_hobbies' ),
				__( 'Kayaking', 'connections_hobbies' ),
				__( 'Knitting', 'connections_hobbies' ),
				__( 'Leather Crafting', 'connections_hobbies' ),
				__( 'Macrame', 'connections_hobbies' ),
				__( 'Magic', 'connections_hobbies' ),
				__( 'Marbles', 'connections_hobbies' ),
				__( 'Martial Arts', 'connections_hobbies' ),
				__( 'Metal Detecting', 'connections_hobbies' ),
				__( 'Meteorology', 'connections_hobbies' ),
				__( 'Microscopy', 'connections_hobbies' ),
				__( 'Mineral Collecting', 'connections_hobbies' ),
				__( 'Motorcycling', 'connections_hobbies' ),
				__( 'Mountain Biking', 'connections_hobbies' ),
				__( 'Music', 'connections_hobbies' ),
				__( 'Netball', 'connections_hobbies' ),
				__( 'Origami', 'connections_hobbies' ),
				__( 'Paintball', 'connections_hobbies' ),
				__( 'Painting', 'connections_hobbies' ),
				__( 'Photography', 'connections_hobbies' ),
				__( 'Playing An Instrument', 'connections_hobbies' ),
				__( 'Poker', 'connections_hobbies' ),
				__( 'Polo', 'connections_hobbies' ),
				__( 'Pottery', 'connections_hobbies' ),
				__( 'Puzzles', 'connections_hobbies' ),
				__( 'Quilting', 'connections_hobbies' ),
				__( 'Racquetball', 'connections_hobbies' ),
				__( 'Radio', 'connections_hobbies' ),
				__( 'Rafting', 'connections_hobbies' ),
				__( 'Rappelling', 'connections_hobbies' ),
				__( 'Reading', 'connections_hobbies' ),
				__( 'Record Collecting', 'connections_hobbies' ),
				__( 'Roller Derby', 'connections_hobbies' ),
				__( 'Roller Skating', 'connections_hobbies' ),
				__( 'Rowing', 'connections_hobbies' ),
				__( 'Rugby', 'connections_hobbies' ),
				__( 'Sailing', 'connections_hobbies' ),
				__( 'Sand Art', 'connections_hobbies' ),
				__( 'Scrap Booking', 'connections_hobbies' ),
				__( 'Scuba Diving', 'connections_hobbies' ),
				__( 'Sculpting', 'connections_hobbies' ),
				__( 'Seashell Collecting', 'connections_hobbies' ),
				__( 'Sewing', 'connections_hobbies' ),
				__( 'Shopping', 'connections_hobbies' ),
				__( 'Shortwave Radio', 'connections_hobbies' ),
				__( 'Singing', 'connections_hobbies' ),
				__( 'Skateboarding', 'connections_hobbies' ),
				__( 'Skiing', 'connections_hobbies' ),
				__( 'Skydiving', 'connections_hobbies' ),
				__( 'Snowboarding', 'connections_hobbies' ),
				__( 'Soap Making', 'connections_hobbies' ),
				__( 'Soccer', 'connections_hobbies' ),
				__( 'Squash', 'connections_hobbies' ),
				__( 'Stamp Collecting', 'connections_hobbies' ),
				__( 'Stand-up Comedy', 'connections_hobbies' ),
				__( 'Surfing', 'connections_hobbies' ),
				__( 'Swimming', 'connections_hobbies' ),
				__( 'Tennis', 'connections_hobbies' ),
				__( 'Trainspotting', 'connections_hobbies' ),
				__( 'Traveling', 'connections_hobbies' ),
				__( 'Video Games', 'connections_hobbies' ),
				__( 'Vintage Cars', 'connections_hobbies' ),
				__( 'Volleyball', 'connections_hobbies' ),
				__( 'Watching Movies', 'connections_hobbies' ),
				__( 'Web Surfing', 'connections_hobbies' ),
				__( 'Weightlifting', 'connections_hobbies' ),
				__( 'Woodworking', 'connections_hobbies' ),
				__( 'Writing', 'connections_hobbies' ),
				__( 'Yoga', 'connections_hobbies' ),
			);

			/**
			 * Filter the list of available hobbies.
			 *
			 * @since 1.0
			 *
			 * @param $options $options An index array of hobbies.
			 */
			$options = apply_filters( 'cn_hobby_options', $options );

			/*
			 * Make the list alphabetical.
			 */
			natsort( $options );

			/*
			 * Create an associative array from the supplied hobby names.
			 */
			$keys    = array_map( 'strtolower', $options );
			$keys    = array_map( 'esc_attr', $keys );
			$keys    = array_map( 'sanitize_title_with_dashes', $keys );
			$options = array_combine( $keys, $options );

			return $options;
		}

		/**
		 * Return the hobby based on the supplied key.
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 *
		 * @param  string $code  The key of the bobby to return.
		 * @return mixed         bool | string	The hobby name  if found, if not, FALSE.
		 */
		private static function hobby( $code = '' ) {

			if ( ! is_string( $code ) || empty( $code ) || $code === '-1' ) {

				return FALSE;
			}

			$hobbies = self::hobbies();
			$hobby   = isset( $hobbies[ $code ] ) ? $hobbies[ $code ] : FALSE;

			return $hobby;
		}

		/**
		 * Registered the custom metabox.
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 * @uses   levels()
		 * @uses   cnMetaboxAPI::add()
		 * @return void
		 */
		public static function registerMetabox() {

			$atts = array(
				'name'     => __( 'Hobbies', 'connections_hobbies' ),
				'id'       => 'hobbies',
				'title'    => __( 'Hobbies', 'connections_hobbies' ),
				'context'  => 'side',
				'priority' => 'core',
				'fields'   => array(
					array(
						'id'      => 'hobbies',
						'type'    => 'checkbox-group',
						'options' => self::hobbies(),
						'default' => '',
						),
					),
				);

			cnMetaboxAPI::add( $atts );
		}

		/**
		 * Add the custom meta as an option in the content block settings in the admin.
		 * This is required for the output to be rendered by $entry->getContentBlock().
		 *
		 * @access private
		 * @since  1.0
		 * @param  array  $blocks An associative array containing the registered content block settings options.
		 * @return array
		 */
		public static function settingsOption( $blocks ) {

			$blocks['hobbies'] = __( 'Hobbies', 'connections_hobbies' );

			return $blocks;
		}

		public static function style( $style, $id ) {

			if ( 'hobbies' == $id ) {

				$style['max-height'] = '300px';
				$style['overflow-y'] = 'scroll';
			}

			return $style;
		}

		/**
		 * Renders the Hobbies content block.
		 *
		 * Called by the cn_meta_output_field-hobbies action in cnOutput->getMetaBlock().
		 *
		 * @access  private
		 * @since   1.0
		 * @static
		 *
		 * @uses    esc_attr()
		 *
		 * @param  string $id     The field id.
		 * @param  array  $value  The hobby ID.
		 * @param  null   $object
		 * @param  array  $atts   The shortcode atts array passed from the calling action.
		 *
		 * @return string
		 */
		public static function block( $id, $value, $object = NULL, $atts ) {

			echo '<ul class="cn-hobbies">';

			foreach ( $value as $code ) {

				if ( $hobby = self::hobby( $code ) ) {

					printf( '<li class="cn-hobby cn-%1$s">%2$s</li>', esc_attr( $code ), esc_html( $hobby ) );
				}
			}

			echo '</ul>';
		}
	}

	/**
	 * Start up the extension.
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @return mixed object | bool
	 */
	function Connections_Hobbies() {

			if ( class_exists('connectionsLoad') ) {

					return new Connections_Hobbies();

			} else {

				add_action(
					'admin_notices',
					 create_function(
						 '',
						'echo \'<div id="message" class="error"><p><strong>ERROR:</strong> Connections must be installed and active in order use Connections Hobbies.</p></div>\';'
						)
				);

				return FALSE;
			}
	}

	/**
	 * Since Connections loads at default priority 10, and this extension is dependent on Connections,
	 * we'll load with priority 11 so we know Connections will be loaded and ready first.
	 */
	add_action( 'plugins_loaded', 'Connections_Hobbies', 11 );

}
