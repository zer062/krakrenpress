<?php

namespace ShortCode;

use Core\Tentacles\ShortCode;
use Model\AlabDocument;

class CustomerDocumentsShortcode extends ShortCode {

	/**
	 * @var string
	 */
	public $shortcode = 'alab_customer_new_document';

	/**
	 * @return false|mixed|string|null
	 */
	public function output() {
		$documents = get_posts([
			'post_type' => 'alabpatientdocument',
			'author' => get_current_user_id(),
			'posts_per_page' => -1
		]);
		$templates = get_posts([
			'post_type' => 'alabdocument',
			'meta_key' => 'document_customers',
			'meta_compare' => 'LIKE',
			'meta_value' => sprintf('s:%s:"%s"', strlen(get_current_user_id()), get_current_user_id()),
			'posts_per_page' => -1
		]);

		return view(
			APP_PATH . '/views/customer-documents.php',
			[
				'documents' => $documents,
				'templates' => $templates,
			]
		);
	}
}
