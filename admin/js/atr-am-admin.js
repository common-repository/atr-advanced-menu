jQuery(document).ready(function ($) {
	jQuery('*[id^="menu-item-chooseimage-wrapper-"]').each(function () {
		var selected_radio = jQuery("input[type=radio]:checked", this).val();
		var the_current_custom_image_prop_src = jQuery(this).parent().children(".customimage").children('label').children('img').prop('src');
		var the_current_custom_image_att_src = jQuery(this).parent().children(".customimage").children('label').children('img').attr('src');
		var the_current_featured_image_prop_src = jQuery(this).parent().children(".featuredimage").children('label').children('img').prop('src');
		var the_current_featured_image_att_src = jQuery(this).parent().children(".featuredimage").children('label').children('img').attr('src');
		var missing_image_item_menu = jQuery(this).parents().parents('li').children('dl').children('dt').children('.item-title').text();
		switch (selected_radio) {
		case '0':
			jQuery(this).parent().children(".customimage").hide();
			jQuery(this).parent().children(".featuredimage").hide();
			jQuery(this).parent().children(".icon-class").hide();
			break;
		case '1':
			jQuery(this).parent().children(".customimage").show();
			jQuery(this).parent().children(".featuredimage").hide();
			jQuery(this).parent().children(".icon-class").hide();
			if (the_current_custom_image_att_src === '') {
				jQuery(".nav-tab-wrapper").before('<div class="error notice">Missing image definition in : "' + missing_image_item_menu + '"</div>');
			} else {
				check_custom_image(the_current_custom_image_prop_src);
			};
			break;
		case '2':
			jQuery(this).parent().children(".customimage").hide();
			jQuery(this).parent().children(".featuredimage").show();
			jQuery(this).parent().children(".icon-class").hide();
			if (the_current_featured_image_att_src === '') {
				jQuery(".nav-tab-wrapper").before('<div class="error notice">Missing image definition in : "' + missing_image_item_menu + '"</div>');
			} else {
				check_custom_image(the_current_featured_image_prop_src);
			};
			break;
		case '3':
			jQuery(this).parent().children(".customimage").hide();
			jQuery(this).parent().children(".featuredimage").hide();
			jQuery(this).parent().children(".icon-class").show();
			break;
		}
		jQuery('input[type=radio]', this).on('change', function () {
			var selected_radio_changed = jQuery(this).val();
			switch (selected_radio_changed) {
			case '0':
				jQuery(this).parent().parent().children(".customimage").hide();
				jQuery(this).parent().parent().children(".featuredimage").hide();
				jQuery(this).parent().parent().children(".icon-class").hide();
				break;
			case '1':
				jQuery(this).parent().parent().children(".customimage").show();
				jQuery(this).parent().parent().children(".featuredimage").hide();
				jQuery(this).parent().parent().children(".icon-class").hide();
				break;
			case '2':
				jQuery(this).parent().parent().children(".customimage").hide();
				jQuery(this).parent().parent().children(".featuredimage").show();
				jQuery(this).parent().parent().children(".icon-class").hide();
				break;
			case '3':
				jQuery(this).parent().parent().children(".customimage").hide();
				jQuery(this).parent().parent().children(".featuredimage").hide();
				jQuery(this).parent().parent().children(".icon-class").show();
				break;
			}
		});
	});
	jQuery('*[id^="iconclass-preview-button-"]').click(function (event) {
		event.preventDefault();
		var icon_class = jQuery(this).siblings('.edit-menu-item-iconclass').val();
		jQuery(this).children('i').removeClass().addClass(icon_class);
	});
	var mediaUploader;
	jQuery('*[id^="upload-button-"]').each(function () {
		var customimage_url = "#customimage-url-" + jQuery(this).attr("id").match(/[\d]+$/);
		var menu_item_customimage_id = '#menu-item-customimage-' + jQuery(this).attr("id").match(/[\d]+$/);
		jQuery(this).click(function (e) {
			e.preventDefault();
			if (mediaUploader) {
				mediaUploader.open();
				return;
			}
			mediaUploader = wp.media.frames.file_frame = wp.media({
					title : 'Choose Image',
					button : {
						text : 'Choose Image'
					},
					multiple : false
				});
			mediaUploader.on('select', function () {
				attachment = mediaUploader.state().get('selection').first().toJSON();
				$(customimage_url).val(attachment.url);
				$(menu_item_customimage_id).attr({
					src : attachment.url
				});
				jQuery('.customimage-error').remove();
			});
			mediaUploader.open();
		});
	});
});
function check_custom_image(img_url) {
	jQuery.ajax({
		url : img_url,
		type : 'HEAD',
		error : function () {
			jQuery(".nav-tab-wrapper").before('<div class="error notice">Image for: "' + missing_image_item_menu + '" not found</div>');
		},
		success : function () {}

	});
};
!function (a) {
	"function" == typeof define && define.amd ? define(["jquery"], a) : "object" == typeof exports ? a(require("jquery")) : a(jQuery)
}
(function (a, b) {
	function c(a, b, c, d) {
		return !(a.selector != b.selector || a.context != b.context || c && c.$lqguid != b.fn.$lqguid || d && d.$lqguid != b.fn2.$lqguid)
	}
	a.extend(a.fn, {
		livequery : function (b, e) {
			var f,
			g = this;
			return a.each(d.queries, function (a, d) {
				return c(g, d, b, e) ? (f = d) && !1 : void 0
			}),
			f = f || new d(g.selector, g.context, b, e),
			f.stopped = !1,
			f.run(),
			g
		},
		expire : function (b, e) {
			var f = this;
			return a.each(d.queries, function (a, g) {
				c(f, g, b, e) && !f.stopped && d.stop(g.id)
			}),
			f
		}
	});
	var d = a.livequery = function (b, c, e, f) {
		var g = this;
		return g.selector = b,
		g.context = c,
		g.fn = e,
		g.fn2 = f,
		g.elements = a([]),
		g.stopped = !1,
		g.id = d.queries.push(g) - 1,
		e.$lqguid = e.$lqguid || d.guid++,
		f && (f.$lqguid = f.$lqguid || d.guid++),
		g
	};
	d.prototype = {
		stop : function () {
			var b = this;
			b.stopped || (b.fn2 && b.elements.each(b.fn2), b.elements = a([]), b.stopped = !0)
		},
		run : function () {
			var b = this;
			if (!b.stopped) {
				var c = b.elements,
				d = a(b.selector, b.context),
				e = d.not(c),
				f = c.not(d);
				b.elements = d,
				e.each(b.fn),
				b.fn2 && f.each(b.fn2)
			}
		}
	},
	a.extend(d, {
		guid : 0,
		queries : [],
		queue : [],
		running : !1,
		timeout : null,
		registered : [],
		checkQueue : function () {
			if (d.running && d.queue.length)
				for (var a = d.queue.length; a--; )
					d.queries[d.queue.shift()].run()
		},
		pause : function () {
			d.running = !1
		},
		play : function () {
			d.running = !0,
			d.run()
		},
		registerPlugin : function () {
			a.each(arguments, function (b, c) {
				if (a.fn[c] && !(a.inArray(c, d.registered) > 0)) {
					var e = a.fn[c];
					a.fn[c] = function () {
						var a = e.apply(this, arguments);
						return d.run(),
						a
					},
					d.registered.push(c)
				}
			})
		},
		run : function (c) {
			c !== b ? a.inArray(c, d.queue) < 0 && d.queue.push(c) : a.each(d.queries, function (b) {
				a.inArray(b, d.queue) < 0 && d.queue.push(b)
			}),
			d.timeout && clearTimeout(d.timeout),
			d.timeout = setTimeout(d.checkQueue, 20)
		},
		stop : function (c) {
			c !== b ? d.queries[c].stop() : a.each(d.queries, d.prototype.stop)
		}
	}),
	d.registerPlugin("append", "prepend", "after", "before", "wrap", "attr", "removeAttr", "addClass", "removeClass", "toggleClass", "empty", "remove", "html", "prop", "removeProp"),
	a(function () {
		d.play()
	})
});
jQuery(function ($) {
	$('.menu-item-settings').each(function (i) {
		$(this).append('<a class="button button-primary atr-menu-save">Save this item</a>');
	});
	
	// $('.atr-menu-save').live('click', function () {
	$('.menu-item-settings').on('click', '.atr-menu-save', function () {	
		button = $(this);
		el = button.parent();
		var subtitle = el.find('.edit-menu-item-subtitle').val();
		var customimage_url = el.find('.edit-menu-item-customimage').val();
		var customimage_lbl = el.find('.customimage label');
		var chooseimage = jQuery("input[type=radio][name^=menu-item-chooseimage]:checked", el).val();
		var panelclass = el.find('.edit-menu-item-panelclass').val();
		var iconclass = el.find('.edit-menu-item-iconclass').val();
		var content_from_post = el.find('.edit-menu-item-content-from-post').val();
		var remttl = el.find('.remove-title').find('input').attr('checked');
		var postexcerpt = el.find('.atr-mm-postexcerpt').find('input').attr('checked');
		var postfeatimg = el.find('.atr-mm-postfeatimg').find('input').attr('checked');
		var url = el.find('.edit-menu-item-url').val();
		var attrtitle = el.find('.edit-menu-item-attr-title').val();
		var title = el.find('.edit-menu-item-title').val();
		var classes = el.find('.edit-menu-item-classes').val();
		var xfn = el.find('.edit-menu-item-xfn').val();
		var desc = el.find('.edit-menu-item-description').val();
		var target = el.find('.field-link-target').find('input').attr('checked');
		var menuid = el.find('.menu-item-data-db-id').val();
		var parentid = el.find('.menu-item-data-parent-id').val();
		var menu = $('#menu').val();
		var pos = el.find('.menu-item-data-position').val();
		if (chooseimage === '1') {
			if (customimage_url.length > 0) {
				jQuery.ajax({
					url : customimage_url,
					type : 'HEAD',
					error : function () {
						if (jQuery('.customimage-error').length === 0) {
							customimage_lbl.find('.menu-item-customimage').after('<p class="atr-inline-warning customimage-error"><span class="dashicons dashicons-warning"></span>Url in textbox is incorrect. Image not changed!<p>');
						} else {
							jQuery('.customimage-error').remove();
							customimage_lbl.find('.menu-item-customimage').after('<p class="atr-inline-warning customimage-error"><span class="dashicons dashicons-warning"></span>Url is incorrect. Image not changed!<p>');
						}
					},
					success : function () {
						jQuery.ajax({
							type : 'POST',
							url : ajaxurl,
							data : 'action=saveitem&url=' + url + '&attrtitle=' + attrtitle + '&title=' + title + '&classes=' + classes + '&xfn=' + xfn + '&desc=' + desc + '&subtitle=' + subtitle + '&panelclass=' + panelclass + '&iconclass=' + iconclass + '&content_from_post=' + content_from_post + '&remttl=' + remttl + '&postexcerpt=' + postexcerpt + '&postfeatimg=' + postfeatimg + '&customimage=' + customimage_url + '&chooseimage=' + chooseimage + '&target=' + target + '&menuid=' + menuid + '&parentid=' + parentid + '&menu=' + menu + '&pos=' + pos + '&max=' + $('.menu-item-settings').length,
							beforeSend : function (xhr) {
								button.text('Saving...');
							},
							success : function (data) {
								button.text('Saved/Save again');
								jQuery('.customimage-error').remove();
							}
						});
					}
				});
			} else {
				alert('Url textbox is empty. Please try again!');
			}
		} else {
			jQuery.ajax({
				type : 'POST',
				url : ajaxurl,
				data : 'action=saveitem&url=' + url + '&attrtitle=' + attrtitle + '&title=' + title + '&classes=' + classes + '&xfn=' + xfn + '&desc=' + desc + '&subtitle=' + subtitle + '&panelclass=' + panelclass + '&iconclass=' + iconclass + '&content_from_post=' + content_from_post + '&remttl=' + remttl + '&postexcerpt=' + postexcerpt + '&postfeatimg=' + postfeatimg + '&customimage=' + customimage_url + '&chooseimage=' + chooseimage + '&target=' + target + '&menuid=' + menuid + '&parentid=' + parentid + '&menu=' + menu + '&pos=' + pos + '&max=' + $('.menu-item-settings').length,
				beforeSend : function (xhr) {
					button.text('Saving...');
				},
				success : function (data) {
					button.text('Saved/Save again');
				}
			});
		};
		return false;
	});
});

