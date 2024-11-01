<?php
// Register Locations Custom Post Type - Since v2.0
if ( ! function_exists('sc_register_locations_cpt') ) {
	function sc_register_locations_cpt() {

		$labels = array(
			'name'                => _x( 'sc Google Map Locations', 'Post Type General Name', 'sc-google-maps' ),
			'singular_name'       => _x( 'sc Google Map Location', 'Post Type Singular Name', 'sc-google-maps' ),
			'menu_name'           => __( 'Locations', 'sc-google-maps' ),
			//'name_admin_bar'      => __( 'sc Google Map', 'sc-google-maps' ),
			//'parent_item_colon'   => __( 'sc Google Maps', 'sc-google-maps' ),
			'all_items'           => __( 'Manage Locations', 'sc-google-maps' ),
			'add_new_item'        => __( 'Add New Location', 'sc-google-maps' ),
			'add_new'             => __( 'Add New Location', 'sc-google-maps' ),
			'new_item'            => __( 'New Location', 'sc-google-maps' ),
			'edit_item'           => __( 'Edit Location', 'sc-google-maps' ),
			'update_item'         => __( 'Update Location', 'sc-google-maps' ),
			'view_item'           => __( 'View Location', 'sc-google-maps' ),
			'search_items'        => __( 'Search Locations', 'sc-google-maps' ),
			'not_found'           => __( 'Not found', 'sc-google-maps' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'sc-google-maps' ),
		);
		$args = array(
			'label'               => __( 'sc Map Location', 'sc-google-maps' ),
			'description'         => __( 'Defines a location to place on map.', 'sc-google-maps' ),
			'labels'              => $labels,
			'supports'            => array( 'title'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=sc-maps',
			//'menu_position'       => 58,
			'menu_icon'           => 'dashicons-pressthis',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'sc-locations', $args );

	}
	add_action( 'init', 'sc_register_locations_cpt', 0 );

	// Register meta box for custom fields
	function sc_locations_meta_box_add() {
		add_meta_box( 'sc-locations-meta', 'Location Options', 'sc_locations_meta_box_cb', 'sc-locations', 'normal', 'high' );
	}
	add_action( 'add_meta_boxes', 'sc_locations_meta_box_add' );

	function sc_locations_meta_box_cb($post) {
		$scgm_params = get_post_custom($post->ID);
		$scgm_param_address = isset($scgm_params['scgm-param-address'])?esc_attr($scgm_params['scgm-param-address'][0]):'';
		$scgm_param_marker = isset($scgm_params['scgm-param-marker'])?esc_attr($scgm_params['scgm-param-marker'][0]):'';
		$scgm_param_marker_custom = isset($scgm_params['scgm-param-marker-custom'])?esc_attr($scgm_params['scgm-param-marker-custom'][0]):'';
		$scgm_param_infowindow = isset($scgm_params['scgm-param-infowindow'])?$scgm_params['scgm-param-infowindow'][0]:'';
		$scgm_param_map = isset($scgm_params['scgm-param-map'])?esc_attr($scgm_params['scgm-param-map'][0]):'';

		// We'll use this nonce field later on when saving.
		wp_nonce_field('sc_locations_meta_box_nonce', 'meta_box_nonce');
		?>
		<div class="scgm-controls-group" style="float: inherit">
			<div class="scgm-control" style="float: inherit">
				<label for="scgm-control-address" class="scgm-control-label"><?=__('Address', 'sc-google-maps')?> <span class="required scgm-right"><?=__('(required)', 'sc-google-maps')?></span></label>
				<input type="text" id="scgm-control-address" name="scgm-param-address" value="<?php echo $scgm_param_address; ?>" class="scgm-control-text scgm-param" data-param-name="address" data-required="1" placeholder="This field is required" />
			</div>

			<div class="scgm-control" style="float: inherit">
				<div class="scgm-preview" style="float: right; width: 10%; text-align: right;">
					<img src="" style="display: none;" />
				</div>
				<label for="scgm-control-marker" class="scgm-control-label" style="float: left; width: 89%;"><?=__('Marker Icon', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<select id="scgm-control-marker" name="scgm-param-marker" class="scgm-control-select scgm-param scgm-opt-a" data-param-name="marker" style="float: inherit; width: 89%;">
					<option value="">Default</option>
					<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/blue.png" <?php selected($scgm_param_marker, sc_GMAPS_IMGURL."/markers/blue.png"); ?>>Blue</option>
					<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/green.png" <?php selected($scgm_param_marker, sc_GMAPS_IMGURL."/markers/green.png"); ?>>Green</option>
					<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/litegreen.png" <?php selected($scgm_param_marker, sc_GMAPS_IMGURL."/markers/litegreen.png"); ?>>Lite Green</option>
					<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/orange.png" <?php selected($scgm_param_marker, sc_GMAPS_IMGURL."/markers/orange.png"); ?>>Orange</option>
					<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/pink.png" <?php selected($scgm_param_marker, sc_GMAPS_IMGURL."/markers/pink.png"); ?>>Pink</option>
					<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/red.png" <?php selected($scgm_param_marker, sc_GMAPS_IMGURL."/markers/red.png"); ?>>Red</option>
					<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/skyblue.png" <?php selected($scgm_param_marker, sc_GMAPS_IMGURL."/markers/skyblue.png"); ?>>Sky Blue</option>
					<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/teal.png" <?php selected($scgm_param_marker, sc_GMAPS_IMGURL."/markers/teal.png"); ?>>Teal</option>
					<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/teapink.png" <?php selected($scgm_param_marker, sc_GMAPS_IMGURL."/markers/teapink.png"); ?>>Tea Pink</option>
					<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/yellow.png" <?php selected($scgm_param_marker, sc_GMAPS_IMGURL."/markers/yellow.png"); ?>>Yellow</option>
					<option value="custom" <?php selected($scgm_param_marker, "custom"); ?>>Custom URL</option>
				</select>
				<input type="text" id="scgm-control-marker-custom" name="scgm-param-marker-custom" value="<?php echo $scgm_param_marker_custom; ?>" class="scgm-control-text scgm-param scgm-opt-b" data-param-name="marker" style="float: inherit; width: 89%; <?php if($scgm_param_marker!="custom") {?>display: none;<?php } ?>" />
				<span class="notes" style="float: left; width: 89%;"><?=__('URL to a custom .png/.jpg/.gif image to use as marker icon. Default is Google Map Pin Icon.', 'sc-google-maps')?></span>
			</div>

			<div class="scgm-control" style="float: inherit; clear: both; padding-top: 10px;">
				<label for="scgm-control-infowindow-wrap" class="scgm-control-label" style="float: inherit"><?=__('Content for Info Window', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
				<?php
					$settings = array(
						'media_buttons' => false,
						'teeny' => true,
						'textarea_rows' => '7',
						'textarea_name' => 'scgm-param-infowindow'
					);

					wp_editor( $scgm_param_infowindow, "scgm-control-infowindow", $settings );
				?>
				<span class="notes" style="float: left; width: 89%;"><?=__('Leave blank to display address in the info window.', 'sc-google-maps')?></span>
			</div>

			<div class="scgm-control" style="float: inherit; clear: both; padding-top: 10px;">
				<label for="scgm-control-map" class="scgm-control-label"><?=__('Use with Map', 'sc-google-maps')?> <span class="required scgm-right"><?=__('(required)', 'sc-google-maps')?></span></label>
				<select id="scgm-control-map" name="scgm-param-map" class="scgm-control-select scgm-param" data-param-name="map">
					<?php
						$args = array(
							'posts_per_page' => -1,
							'post_type' => 'sc-maps',
							'orderby' => 'title',
							'order' => 'ASC'
						);
						$maps = get_posts($args);

						foreach ($maps as $map):
					?>
							<option value="<?php echo $map->ID; ?>" <?php selected($scgm_param_map, $map->ID); ?>><?php echo get_the_title($map->ID); ?></option>
					<?php
						endforeach;
						wp_reset_postdata();
					?>
				</select>
			</div>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function($){
				$("#scgm-control-marker").on("change", function(){
					var v = $(this).val();

					if(v !== "" && v !== "custom") {
						//var src = '<?php echo sc_GMAPS_IMGURL; ?>/markers/' + v;
						$(".scgm-preview img").attr("src", v);
						$(".scgm-preview img").show();
					} else {
						$(".scgm-preview img").attr("src", "").hide();
						$(".scgm-preview img").hide();
					}

					if(v == "custom") {
						$(".scgm-opt-b").show();
					} else {
						$(".scgm-opt-b").val("");
						$(".scgm-opt-b").hide();
					}

				});
			});
		</script>
	<?php
	}

	// Save Custom Fields
	function sc_locations_meta_box_save( $post_id ) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'sc_locations_meta_box_nonce' ) ) return;

		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' ) ) return;

		// Make sure data is set before trying to save it
		if( isset( $_POST['scgm-param-address'] ) )
			update_post_meta( $post_id, 'scgm-param-address', esc_attr( $_POST['scgm-param-address'] ) );

		if( isset( $_POST['scgm-param-infowindow'] ) )
			update_post_meta( $post_id, 'scgm-param-infowindow', $_POST['scgm-param-infowindow']);

		if( isset( $_POST['scgm-param-marker'] ) )
			update_post_meta( $post_id, 'scgm-param-marker', esc_attr( $_POST['scgm-param-marker'] ) );

		if( isset( $_POST['scgm-param-marker-custom'] ) )
			update_post_meta( $post_id, 'scgm-param-marker-custom', esc_attr( $_POST['scgm-param-marker-custom'] ) );

		// Post Relation - link it with Selected Map
		if( isset( $_POST['scgm-param-map'] ) )
			update_post_meta( $post_id, 'scgm-param-map', esc_attr( $_POST['scgm-param-map'] ) );
	}
	add_action( 'save_post', 'sc_locations_meta_box_save' );

	// Add short code column to maps posts list
	function sc_locations_columns_head($defaults) {
		unset($defaults['date']);

		$defaults['map'] = __('Map');
		$defaults['date'] = __('Date');

		return $defaults;
	}
	function sc_locations_columns_content($column_name, $post_ID) {
		if ($column_name == 'map') {
			$map_id = get_post_meta($post_ID, 'scgm-param-map', true);
			$map_title = get_the_title($map_id);

			echo "<a href='post.php?post={$map_id}&action=edit'>{$map_title}</a> ({$map_id})";
		}

		if($column_name == 'date') {
			echo get_the_date();
		}
	}
	add_filter('manage_sc-locations_posts_columns', 'sc_locations_columns_head', 10);
	add_action('manage_sc-locations_posts_custom_column', 'sc_locations_columns_content', 10, 2);
}