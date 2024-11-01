<div class="scgm-shortcode-wrapper">
	<h2 class="scgm-shortcode-title" data-shortcode="sc-gmap">[sc-gmap]</h2>
	<p class="scgm-shortcode-desc"><?=__('Configure and Insert short code to place a Google Map in your post or page. You can ignore attributes marked as optional, to use their default values.', 'sc-google-maps')?></p>

	<?php
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sc-maps',
			'orderby' => 'title',
			'order' => 'ASC'
		);
		$maps = get_posts($args);
		wp_reset_postdata();
	?>

	<?php if(sizeof($maps) <= 0) { ?>
		<div style="border: 1px solid goldenrod; background-color: lightyellow; padding: 5px; border-radius: 12px; font-size: 11px; color: darkgoldenrod;">
			<span class='dashicons dashicons-info' style='color: #00A8EF;'></span>
			<?php
			echo __(
				'You are using Basic Mode of this plugin. If you want to use Multiple Locations with Custom Markers,
					Information Popups and want to save all configuration of your short code, please use
					<a href="edit.php?post_type=sc-maps"><strong style="color: maroon;">Advanced Mode</strong></a>.',
				'sc-google-maps'
			);
			?>
		</div>
	<?php } ?>

	<div class="scgm-controls-group">
		<?php if(sizeof($maps) > 0 ) { ?>
			<div class="scgm-control">
				<label for="scgm-control-map" class="scgm-control-label"><?=__('Use an existing map?', 'sc-google-maps')?></label>
				<select id="scgm-control-map" name="scgm-param-map" class="scgm-control-select scgm-param" data-param-name="map">
					<option value="">No, use basic mode</option>
					<?php
						foreach ($maps as $map) {
					?>
							<option value="<?php echo $map->ID; ?>"><?php echo $map->post_title; ?></option>
					<?php
						}
					?>
				</select>
			</div>
		<?php } ?>

		<div class="scgm-control" data-mode="basic">
			<label for="scgm-control-address" class="scgm-control-label"><?=__('Address', 'sc-google-maps')?> <span class="required scgm-right"><?=__('(required)', 'sc-google-maps')?></span></label>
			<input type="text" id="scgm-control-address" name="scgm-param-address" class="scgm-control-text scgm-param" data-param-name="address" data-required="1" placeholder="This field is required" />
		</div>

		<div class="scgm-control" data-mode="basic">
			<label for="scgm-control-width" class="scgm-control-label"><?=__('Map Width', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
			<input type="text" id="scgm-control-width" name="scgm-param-width" class="scgm-control-text scgm-param" data-param-name="width" />
			<span class="notes"><?=__('i.e. 50% or 400px - default is 100%', 'sc-google-maps')?></span>
		</div>

		<div class="scgm-control" data-mode="basic">
			<label for="scgm-control-height" class="scgm-control-label"><?=__('Map Height', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
			<input type="text" id="scgm-control-height" name="scgm-param-height" class="scgm-control-text scgm-param" data-param-name="height" />
			<span class="notes"><?=__('i.e. 50% or 400px - default is 400px', 'sc-google-maps')?></span>
		</div>

		<div class="scgm-control" data-mode="basic">
			<div class="scgm-preview" style="float: right; width: 10%; text-align: right;">
				<img src="" style="display: none;" />
			</div>
			<label for="scgm-control-marker" class="scgm-control-label" style="float: left; width: 89%;"><?=__('Address Marker Icon URL', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
			<select id="scgm-control-marker" name="scgm-param-marker" class="scgm-control-select scgm-param scgm-opt-a" data-param-name="marker" style="float: left; width: 89%;">
				<option value="">Default</option>
				<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/blue.png">Blue</option>
				<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/green.png">Green</option>
				<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/litegreen.png">Lite Green</option>
				<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/orange.png">Orange</option>
				<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/pink.png">Pink</option>
				<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/red.png">Red</option>
				<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/skyblue.png">Sky Blue</option>
				<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/teal.png">Teal</option>
				<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/teapink.png">Tea Pink</option>
				<option value="<?php echo sc_GMAPS_IMGURL; ?>/markers/yellow.png">Yellow</option>
				<option value="custom">Custom URL</option>
			</select>
			<input type="text" id="scgm-control-marker" name="scgm-param-marker" class="scgm-control-text scgm-param scgm-opt-b" data-param-name="marker" style="float: left; width: 89%; display: none;" />
			<span class="notes" style="float: left; width: 89%;"><?=__('URL to a custom .png/.jpg/.gif image to use as marker icon. Default is Google Map Pin Icon.', 'sc-google-maps')?></span>
		</div>

		<div class="scgm-control" data-mode="basic">
			<label for="scgm-control-zoom" class="scgm-control-label"><?=__('Initial Zoom Level', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
			<input type="text" id="scgm-control-zoom" name="scgm-param-zoom" class="scgm-control-text scgm-param" data-param-name="zoom" />
			<span class="notes"><?=__('Default is 15 - lower value zoom out while higher value zoom in the map.', 'sc-google-maps')?></span>
		</div>

		<div class="scgm-control" data-mode="basic">
			<label for="scgm-control-type" class="scgm-control-label"><?=__('Map Type', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
			<select id="scgm-control-type" name="scgm-param-type" class="scgm-control-select scgm-param" data-param-name="type">
				<option value="">-- Select --</option>
				<option value="HYBRID">HYBRID</option>
				<option value="ROADMAP">ROADMAP (default)</option>
				<option value="SATELLITE">SATELLITE</option>
				<option value="TERRAIN">TERRAIN</option>
			</select>
		</div>

		<div class="scgm-control" data-mode="basic">
			<label for="scgm-control-swheel" class="scgm-control-label"><?=__('Mouse Scroll Wheel', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
			<select id="scgm-control-swheel" name="scgm-param-swheel" class="scgm-control-select scgm-param" data-param-name="swheel">
				<option value="">-- Select --</option>
				<option value="disable">Disable (default)</option>
				<option value="enable">Enable</option>
			</select>
		</div>

		<div class="scgm-control" data-mode="basic">
			<label for="scgm-control-controls" class="scgm-control-label"><?=__('Map Controls', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
			<select id="scgm-control-controls" name="scgm-param-controls" class="scgm-control-select scgm-param" data-param-name="controls">
				<option value="">-- Select --</option>
				<option value="hide">Hide</option>
				<option value="show">Show (default)</option>
			</select>
		</div>

		<div class="scgm-control" data-mode="basic">
			<label for="scgm-control-cache" class="scgm-control-label"><?=__('Cache', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
			<select id="scgm-control-cache" name="scgm-param-cache" class="scgm-control-select scgm-param" data-param-name="cache">
				<option value="">-- Select --</option>
				<option value="disable">Disable</option>
				<option value="enable">Enable (default)</option>
			</select>
			<span class="notes"><?=__('If enabled, stores results in cache for 30 days - which improves speed. If you want to get fresh results every time, do not enable cache.', 'sc-google-maps')?></span>
		</div>

		<div class="scgm-control" data-mode="basic">
			<label for="scgm-control-class" class="scgm-control-label"><?=__('CSS: Map DIV Class(es)', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
			<input type="text" id="scgm-control-class" name="scgm-param-class" class="scgm-control-text scgm-param" data-param-name="class" />
			<span class="notes"><?=__('Default is sc-gmap', 'sc-google-maps')?></span>
		</div>

		<div class="scgm-control" data-mode="basic">
			<label for="scgm-control-id" class="scgm-control-label"><?=__('CSS: Map DIV ID', 'sc-google-maps')?> <span class="optional scgm-right"><?=__('(optional)', 'sc-google-maps')?></span></label>
			<input type="text" id="scgm-control-id" name="scgm-param-id" class="scgm-control-text scgm-param" data-param-name="id" />
			<span class="notes"><?=__('Default is sc-gmap - do not include # (hash) sign. This ID is also used as map object ID.', 'sc-google-maps')?></span>
		</div>


		<div class="scgm-control separator">
			<button type="button" id="scgm-action-insert" class="button button-primary button-large scgm-control-button scgm-right"><?=__('Insert Short Code', 'sc-google-maps')?></button>
		</div>
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

		$("#scgm-control-map").on("change", function(){
			var v = $(this).val();

			if(v != "") {
				$("[data-mode=basic]").hide();
				$("[data-required=1]").attr("data-required", "0");
			} else {
				$("[data-mode=basic]").show();
				$("[data-required=0]").attr("data-required", "1");
			}
		});

		sc_gmaps_insert();
	});
</script>
