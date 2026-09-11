(function ($) {
	'use strict';

	function previewHtml(attachment, type) {
		if (!attachment) {
			return '<span class="ms-admin-slot__empty">Vazio</span>';
		}
		if (type === 'video' || (attachment.mime && attachment.mime.indexOf('video/') === 0)) {
			if (attachment.image && attachment.image.src) {
				return '<img src="' + attachment.image.src + '" alt="">';
			}
			return '<video src="' + attachment.url + '" muted preload="metadata"></video>';
		}
		var src = attachment.url;
		if (attachment.sizes && attachment.sizes.medium) {
			src = attachment.sizes.medium.url;
		} else if (attachment.sizes && attachment.sizes.thumbnail) {
			src = attachment.sizes.thumbnail.url;
		}
		return '<img src="' + src + '" alt="">';
	}

	$(document).on('click', '.ms-admin-pick', function (event) {
		event.preventDefault();
		var slot = $(this).closest('.ms-admin-slot');
		var type = slot.data('type') || 'image';
		var title = 'Escolher foto';
		var library = { type: type };
		if (type === 'video') {
			title = 'Escolher vídeo';
		} else if (type === 'media') {
			title = 'Escolher foto ou vídeo';
			library = { type: ['image', 'video'] };
		}
		var frame = wp.media({
			title: title,
			library: library,
			multiple: false,
			button: { text: 'Usar neste espaço' },
		});
		frame.on('select', function () {
			var attachment = frame.state().get('selection').first().toJSON();
			slot.find('input[type="hidden"]').val(attachment.id);
			slot.find('.ms-admin-slot__preview').html(previewHtml(attachment, type));
			slot.addClass('has-file');
		});
		frame.open();
	});

	$(document).on('click', '.ms-admin-clear', function (event) {
		event.preventDefault();
		var slot = $(this).closest('.ms-admin-slot');
		slot.find('input[type="hidden"]').val('0');
		slot.find('.ms-admin-slot__preview').html('<span class="ms-admin-slot__empty">Vazio</span>');
		slot.removeClass('has-file');
	});
})(jQuery);
