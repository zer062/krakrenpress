<?php

namespace Hook;

use Core\Tentacles\Hook;

class LoadAssetsHook extends Hook
{
	/**
	 * @var bool
	 */
	public $is_filter = true;

	/**
	 * @var string
	 */
	public $action = 'wp_enqueue_scripts';

	/**
	 * @return string
	 */
	public function handle()
	{
		wp_enqueue_style('alab-styles', plugin_dir_url(dirname(__FILE__)) . 'assets/css/styles.css', [], '1.0.0');
		wp_enqueue_style('alab-modal-css', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css', [], '1.0.0');
		wp_enqueue_script('alab-modal-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', ['jquery'], '1.0.0');
		wp_enqueue_script('alab-form-validate-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js', ['jquery'], '1.0.0');

		wp_register_script('alab-scripts', plugin_dir_url(dirname(__FILE__)) . 'assets/js/scripts.js', ['jquery']);
		$localize = [
			'ajax_url' => admin_url('admin-ajax.php'),
			'alab_load_template_fields_nonce' => wp_create_nonce('alab_load_template_fields_nonce'),
			'alab_load_template_preview_nonce' => wp_create_nonce('alab_load_template_preview_nonce'),
			'alab_generate_document_nonce' => wp_create_nonce('alab_generate_document_nonce'),
		];

		wp_localize_script('alab-scripts', 'alab_vars', $localize);
		wp_enqueue_script('alab-scripts');
	}
}
