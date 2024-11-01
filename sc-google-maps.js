jQuery(document).ready(function($){
	$("#scgm_InsertShortcode").on("click", function(e){
		e.preventDefault();

		var data = {action: 'sc_gmaps_load_ui'};

		sc_gmaps_load_ui(data);
	});
});

function sc_gmaps_load_ui(data) {
	jQuery.get(scgm_ajax.url, data, function(resp){
		jQuery.colorbox({
			title: scgm_ajax.icon+scgm_ajax.tag,
			className: scgm_ajax.i+"cbox",
			html: resp,
			open: true,
			width: 600,
			height: 500,
			opacity: '0.7',
			fixed: true
		});
	});
}

function sc_gmaps_insert() {
	jQuery("#scgm-action-insert").on("click", function(e) {
		e.preventDefault();

		var shortcode = jQuery(".scgm-shortcode-title").attr("data-shortcode");
		var params = {};
		var isOk = true;

		jQuery(".scgm-param").each(function(i){
			var p_name = jQuery(this).attr("data-param-name");
			var p_val = jQuery(this).val();
			var p_required = jQuery(this).attr("data-required");

			// If map attribute is used, then ignore other attributes
			if(p_name == "map" && p_val != "") {
				params[p_name] = p_val;
				return false;
			}

			if(p_val != "") {
				params[p_name] = p_val;
			} else if(p_val == "" && p_required == "1") { //Required parameter, must not be empty
				jQuery(this).addClass("highlight-error");
				jQuery(this).focus();
				isOk = false;
			}
		});

		if(isOk) {
			sc_gmaps_insert_shortcode(shortcode, params);
		}
	});
}

function sc_gmaps_insert_shortcode(shortcode, params) {
	params = (typeof params === 'undefined') ? null : params;

	var attrs = [];
	var strAttrs = "";
	var out = shortcode;

	if(params != null) {
		jQuery.each(params, function(key, val){
			attrs.push(key+"=\""+val+"\"");
		});

		attrs.sort();
		strAttrs = attrs.join(" ");
	}

	if(strAttrs != "") {
		out += " "+strAttrs;
	}

	window.parent.send_to_editor('[' + out + ']');
	jQuery.colorbox.close();
}
