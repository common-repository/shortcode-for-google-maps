<?php
// Register Maps Custom Post Type - Since v2.0
if ( ! function_exists('sc_register_maps_cpt') ) {
	function sc_register_maps_cpt() {

		$labels = array(
			'name'                => _x( 'Google Maps', 'Post Type General Name', 'sc-google-maps' ),
			'singular_name'       => _x( 'Google Map', 'Post Type Singular Name', 'sc-google-maps' ),
			'menu_name'           => __( 'Google Maps', 'sc-google-maps' ),
			//'name_admin_bar'      => __( 'sc Google Map', 'sc-google-maps' ),
			//'parent_item_colon'   => __( 'sc Google Maps', 'sc-google-maps' ),
			'all_items'           => __( 'All Maps', 'sc-google-maps' ),
			'add_new_item'        => __( 'Add New Map', 'sc-google-maps' ),
			'add_new'             => __( 'Add New Map', 'sc-google-maps' ),
			'new_item'            => __( 'New Map', 'sc-google-maps' ),
			'edit_item'           => __( 'Edit Map', 'sc-google-maps' ),
			'update_item'         => __( 'Update Map', 'sc-google-maps' ),
			'view_item'           => __( 'View Map', 'sc-google-maps' ),
			'search_items'        => __( 'Search Maps', 'sc-google-maps' ),
			'not_found'           => __( 'Not found', 'sc-google-maps' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'sc-google-maps' ),
		);
		$args = array(
			'label'               => __( 'sc Map', 'sc-google-maps' ),
			'description'         => __( 'Defines a map entry.', 'sc-google-maps' ),
			'labels'              => $labels,
			'supports'            => array( 'title', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 99,
			'menu_icon'           => sc_GMAPS_IMGURL.'/icon-scgm24x24.png', //'dashicons-location-alt',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'sc-maps', $args );

	}
	add_action( 'init', 'sc_register_maps_cpt', 0 );

	// Register meta box for custom fields
	function sc_maps_meta_box_add() {
		add_meta_box( 'sc-maps-meta', 'Map Options', 'sc_maps_meta_box_cb', 'sc-maps', 'normal', 'high' );
	}
	add_action( 'add_meta_boxes', 'sc_maps_meta_box_add' );

	function sc_maps_meta_box_cb($post) {
		$scgm_params = get_post_custom($post->ID);
		$scgm_param_width = isset($scgm_params['scgm-param-width'])?esc_attr($scgm_params['scgm-param-width'][0]):'';
		$scgm_param_height = isset($scgm_params['scgm-param-height'])?esc_attr($scgm_params['scgm-param-height'][0]):'';
		$scgm_param_zoom = isset($scgm_params['scgm-param-zoom'])?esc_attr($scgm_params['scgm-param-zoom'][0]):'';
		$scgm_param_type = isset($scgm_params['scgm-param-type'])?esc_attr($scgm_params['scgm-param-type'][0]):'';
		$scgm_param_swheel = isset($scgm_params['scgm-param-swheel'])?esc_attr($scgm_params['scgm-param-swheel'][0]):'';
		$scgm_param_controls = isset($scgm_params['scgm-param-controls'])?esc_attr($scgm_params['scgm-param-controls'][0]):'';
		$scgm_param_cache = isset($scgm_params['scgm-param-cache'])?esc_attr($scgm_params['scgm-param-cache'][0]):'';
		$scgm_param_class = isset($scgm_params['scgm-param-class'])?esc_attr($scgm_params['scgm-param-class'][0]):'';
		$scgm_param_id = isset($scgm_params['scgm-param-id'])?esc_attr($scgm_params['scgm-param-id'][0]):'';

		//print_r($scgm_params);

		// We'll use this nonce field later on when saving.
		wp_nonce_field('sc_maps_meta_box_nonce', 'meta_box_nonce');
		?>
		<div class="scgm-controls-group" style="float: inherit">
			<div class="scgm-control" style="float: inherit">
				<label for="scgm-control-width" class="scgm-control-label"><?=__('Map Width', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<input type="text" id="scgm-control-width" name="scgm-param-width" value="<?php echo $scgm_param_width; ?>" class="scgm-control-text scgm-param" data-param-name="width" />
				<span class="notes"><?=__('i.e. 50% or 400px - default is 100%', 'sc-google-maps')?></span>
			</div>

			<div class="scgm-control" style="float: inherit">
				<label for="scgm-control-height" class="scgm-control-label"><?=__('Map Height', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<input type="text" id="scgm-control-height" name="scgm-param-height" value="<?php echo $scgm_param_height; ?>" class="scgm-control-text scgm-param" data-param-name="height" />
				<span class="notes"><?=__('i.e. 50% or 400px - default is 400px', 'sc-google-maps')?></span>
			</div>

			<div class="scgm-control" style="float: inherit">
				<label for="scgm-control-zoom" class="scgm-control-label"><?=__('Initial Zoom Level', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<input type="text" id="scgm-control-zoom" name="scgm-param-zoom" value="<?php echo $scgm_param_zoom; ?>" class="scgm-control-text scgm-param" data-param-name="zoom" />
				<span class="notes"><?=__('Default is 15 - lower value zoom out while higher value zoom in the map.', 'sc-google-maps')?></span>
			</div>

			<div class="scgm-control" style="float: inherit">
				<label for="scgm-control-type" class="scgm-control-label"><?=__('Map Type', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<select id="scgm-control-type" name="scgm-param-type" class="scgm-control-select scgm-param" data-param-name="type">
					<option value="">-- Select --</option>
					<option value="HYBRID" <?php selected($scgm_param_type, 'HYBRID'); ?>>HYBRID</option>
					<option value="ROADMAP" <?php selected($scgm_param_type, 'ROADMAP'); ?>>ROADMAP (default)</option>
					<option value="SATELLITE" <?php selected($scgm_param_type, 'SATELLITE'); ?>>SATELLITE</option>
					<option value="TERRAIN" <?php selected($scgm_param_type, 'TERRAIN'); ?>>TERRAIN</option>
				</select>
			</div>

			<div class="scgm-control" style="float: inherit">
				<label for="scgm-control-swheel" class="scgm-control-label"><?=__('Mouse Scroll Wheel', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<select id="scgm-control-swheel" name="scgm-param-swheel" class="scgm-control-select scgm-param" data-param-name="swheel">
					<option value="">-- Select --</option>
					<option value="disable" <?php selected($scgm_param_swheel, 'disable'); ?>>Disable (default)</option>
					<option value="enable" <?php selected($scgm_param_swheel, 'enable'); ?>>Enable</option>
				</select>
			</div>

			<div class="scgm-control" style="float: inherit">
				<label for="scgm-control-controls" class="scgm-control-label"><?=__('Map Controls', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<select id="scgm-control-controls" name="scgm-param-controls" class="scgm-control-select scgm-param" data-param-name="controls">
					<option value="">-- Select --</option>
					<option value="hide" <?php selected($scgm_param_controls, 'hide'); ?>>Hide</option>
					<option value="show" <?php selected($scgm_param_controls, 'show'); ?>>Show (default)</option>
				</select>
			</div>

			<div class="scgm-control" style="float: inherit">
				<label for="scgm-control-cache" class="scgm-control-label"><?=__('Cache', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<select id="scgm-control-cache" name="scgm-param-cache" class="scgm-control-select scgm-param" data-param-name="cache">
					<option value="">-- Select --</option>
					<option value="disable" <?php selected($scgm_param_cache, 'disable'); ?>>Disable</option>
					<option value="enable" <?php selected($scgm_param_cache, 'enable'); ?>>Enable (default)</option>
				</select>
				<span class="notes"><?=__('If enabled, stores results in cache for 30 days - which improves speed. If you want to get fresh results every time, do not enable cache.', 'sc-google-maps')?></span>
			</div>

			<div class="scgm-control" style="float: inherit">
				<label for="scgm-control-class" class="scgm-control-label"><?=__('CSS: Map DIV Class(es)', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<input type="text" id="scgm-control-class" name="scgm-param-class" value="<?php echo $scgm_param_class; ?>" class="scgm-control-text scgm-param" data-param-name="class" />
				<span class="notes"><?=__('Default is sc-gmap', 'sc-google-maps')?></span>
			</div>

			<div class="scgm-control" style="float: inherit">
				<label for="scgm-control-id" class="scgm-control-label"><?=__('CSS: Map DIV ID', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<input type="text" id="scgm-control-id" name="scgm-param-id" value="<?php echo $scgm_param_id; ?>" class="scgm-control-text scgm-param" data-param-name="id" />
				<span class="notes"><?=__('Default is sc-gmap - do not include # (hash) sign. This ID is also used as map object ID.', 'sc-google-maps')?></span>
			</div>
		</div>
	<?php
	}

	// Save Custom Fields
	function sc_maps_meta_box_save( $post_id ) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'sc_maps_meta_box_nonce' ) ) return;

		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' ) ) return;

		// Make sure data is set before trying to save it
		if( isset( $_POST['scgm-param-width'] ) )
			update_post_meta( $post_id, 'scgm-param-width', esc_attr( $_POST['scgm-param-width'] ) );

		if( isset( $_POST['scgm-param-height'] ) )
			update_post_meta( $post_id, 'scgm-param-height', esc_attr( $_POST['scgm-param-height'] ) );

		if( isset( $_POST['scgm-param-zoom'] ) )
			update_post_meta( $post_id, 'scgm-param-zoom', esc_attr( $_POST['scgm-param-zoom'] ) );

		$param_type = (isset($_POST['scgm-param-type']) && !empty($_POST['scgm-param-type']))?$_POST['scgm-param-type']:"ROADMAP";
		update_post_meta($post_id, 'scgm-param-type', esc_attr($param_type));

		$param_swheel = (isset($_POST['scgm-param-swheel']) && !empty($_POST['scgm-param-swheel']))?$_POST['scgm-param-swheel']:'disable';
		update_post_meta($post_id, 'scgm-param-swheel', $param_swheel);

		$param_controls = (isset($_POST['scgm-param-controls']) && !empty($_POST['scgm-param-controls']))?$_POST['scgm-param-controls']:'show';
		update_post_meta($post_id, 'scgm-param-controls', $param_controls);

		$param_cache = (isset($_POST['scgm-param-cache']) && !empty($_POST['scgm-param-cache']))?$_POST['scgm-param-cache']:'enable';
		update_post_meta($post_id, 'scgm-param-cache', $param_cache);

		if( isset( $_POST['scgm-param-class'] ) )
			update_post_meta( $post_id, 'scgm-param-class', esc_attr( $_POST['scgm-param-class'] ) );

		if( isset( $_POST['scgm-param-id'] ) )
			update_post_meta( $post_id, 'scgm-param-id', esc_attr( $_POST['scgm-param-id'] ) );

	}
	add_action( 'save_post', 'sc_maps_meta_box_save' );

	// Register meta box for attached locations
	function sc_maps_meta_box_locations() {
		add_meta_box( 'sc-maps-locations', 'Locations on this map', 'sc_maps_meta_box_locations_cb', 'sc-maps', 'side', 'default' );
	}
	add_action( 'add_meta_boxes', 'sc_maps_meta_box_locations' );

	function sc_maps_meta_box_locations_cb($post) {
		$post_status = $post->post_status;

		if($post_status != "auto-draft" && $post_status != "draft") {
			$args = array(
				'posts_per_page' => -1,
				'post_type' => 'sc-locations',
				'orderby' => 'title',
				'order' => 'ASC',
				'meta_query' => array(
					array(
						'key' => 'scgm-param-map',
						'value' => $post->ID
					)
				)
			);
			$locations = get_posts($args);

			echo "<table width='100%' cellpadding='5' cellspacing='0'>";

			foreach($locations as $location) {
				echo "<tr>";
				echo "<td style='width: 80%; border-bottom: 1px solid lightgray;'>".$location->post_title."</td>";
				echo "<td style='width: 20%; text-align: right; border-bottom: 1px solid lightgray;'><a href='post.php?post={$location->ID}&action=edit'>Edit</a></td>";
				echo "</tr>";
			}

			echo "<tr>";
			echo "<td colspan='2' style='padding-top: 20px; text-align: center;'><a href='post-new.php?post_type=sc-locations' class='page-title-action'>Add New Location</a></td>";
			echo "</tr>";

			echo "</table>";

			wp_reset_postdata();
		}
	}

	// Register meta box for map's short code
	function sc_maps_meta_box_sc() {
		add_meta_box( 'sc-maps-sc', 'Short Code', 'sc_maps_meta_box_sc_cb', 'sc-maps', 'side', 'high' );
	}
	add_action( 'add_meta_boxes', 'sc_maps_meta_box_sc' );

	function sc_maps_meta_box_sc_cb($post) {
		$post_status = $post->post_status;

		if($post_status != "auto-draft" && $post_status != "draft") {
			echo "<div style='text-align: center;'>";
			echo "<code style='display: block;'>[sc-gmap map={$post->ID}]</code>";
			echo "</div>";
		}
	}

	// Add short code column to maps posts list
	function sc_maps_columns_head($defaults) {
		unset($defaults['date']);

		$defaults['short_code'] = __('Short Code');
		$defaults['date'] = __('Date');

		return $defaults;
	}
	function sc_maps_columns_content($column_name, $post_ID) {
		if ($column_name == 'short_code') {
			echo "<code>[sc-gmap map={$post_ID}]</code>";
		}

		if($column_name == 'date') {
			echo get_the_date();
		}
	}
	add_filter('manage_sc-maps_posts_columns', 'sc_maps_columns_head', 10);
	add_action('manage_sc-maps_posts_custom_column', 'sc_maps_columns_content', 10, 2);
}