jQuery(function(){

	jQuery.extend( jQuery.validator.messages, {
		required: "Campo obrigatório",
		email: "Por favor, insira um endereço de e-mail válido",
	} );

	jQuery('#alab-doc-form').validate({
		messages: {
			required: "This field is requiredssss",
		},
		submitHandler: function(form) {
			jQuery.ajax({
				url: alab_vars.ajax_url,
				type: 'POST',
				data: {
					action: 'alab_load_template_preview',
					alab_load_template_preview_nonce: alab_vars.alab_load_template_preview_nonce,
					template: jQuery('#alab-doc-template').val(),
					data: jQuery('#alab-doc-form').serialize()
				},
				beforeSend: function() {
					jQuery("#alab-generate-doc").attr("disabled", true);
					jQuery('#alab-generate-preview').attr('disabled', true);
					jQuery('#alab-doc-preview').html('<div style="width: 100%; height: 100%; text-align: center; padding: 200px;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 alab-loading">' +
						'  <path  stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />\n' +
						'</svg></div>');
				},
				success: function(data) {
					jQuery('#alab-doc-preview').html(data);
				},
				complete: function() {
					jQuery("#alab-generate-doc").attr("disabled", false);
					jQuery('#alab-generate-preview').attr('disabled', false);
				}
			});
		}
	});

	jQuery('a[href="#ex1"]').click(function(event) {
		event.preventDefault();
		jQuery(this).modal({
			escapeClose: false,
			clickClose: false,
			showClose: false
		});
	});

	jQuery('#alab-doc-template').on('change', function() {
		console.log(jQuery(this).val());

		jQuery.ajax({
			url: alab_vars.ajax_url,
			type: 'POST',
			data: {
				action: 'alab_load_template_fields',
				alab_load_template_fields_nonce: alab_vars.alab_load_template_fields_nonce,
				template: jQuery(this).val()
			},
			beforeSend: function() {
				jQuery('#alab-doc-fields').html('<div style="width: 100%; height: 100%; text-align: center; padding: 200px;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 alab-loading white">' +
					'  <path  stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />\n' +
					'</svg></div>');
			},
			success: function(data) {
				jQuery('#alab-doc-fields').html(data);
			}
		});
	});

	jQuery('#ex1').on(jQuery.modal.BEFORE_CLOSE, function(event, modal) {
		jQuery('#alab-doc-fields').html('');
		jQuery('#alab-doc-template').val('')
	});

	jQuery("#alab-generate-doc").on("click", function() {
		jQuery.ajax({
			url: alab_vars.ajax_url,
			type: 'POST',
			data: {
				action: 'alab_generate_document',
				alab_generate_document_nonce: alab_vars.alab_generate_document_nonce,
				template: jQuery('#alab-doc-template').val(),
				data: jQuery('#alab-doc-form').serialize()
			},
			beforeSend: function() {
				// jQuery('#alab-generate-doc').attr('disabled', true);
				jQuery('#alab-doc-preview').html('<div style="width: 100%; height: 100%; text-align: center; padding: 200px;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 alab-loading">' +
					'  <path  stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />\n' +
					'</svg></div>');
			},
			success: function(data) {
				if (data.success === true) {
					jQuery('#alab-doc-form').trigger('reset');
					jQuery('#alab-doc-fields').html('');
					jQuery('#alab-doc-preview').html('<div style="width: 100%; height: 100%; text-align: center; padding: 200px;">Documento Gerado com sucesso!<br> Intruções foram enviadas para o email do paciente!<br><br><a href="#" class="alab-btn" rel="modal:close">Fechar</a></div>');
					jQuery('#alab-generate-doc').hide();
					return;
				}

				jQuery('#alab-doc-preview').html('<div style="width: 100%; height: 100%; text-align: center; padding: 200px;"><h2 style="color: red;">Erro ao processar o documento</h2> ' + data.data.error + '<p>Verifique os dados enviados e tente novamente.</p></div>');

			}
		});
	});
});
