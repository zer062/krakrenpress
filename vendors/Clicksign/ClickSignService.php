<?php

namespace Vendors\Clicksign;

class ClickSignService {
	/**
	 * @var string|false|mixed|null
	 */
	private string $token;

	/**
	 * @var string|false|mixed|null
	 */
	private string $api_url;

	/**
	 *
	 */
	public function __construct() {
		$this->token = get_option('alab_clicksign_token');
		$this->api_url = get_option('alab_clicksign_api');
	}

	/**
	 * @param string $name
	 * @param string $content
	 *
	 * @return mixed|null
	 */
	public function submit_document_to_sign(string $name, string $content, ) {
		return $this->request('documents', [
			'document' => [
				'path' => '/' . $name,
				'content_base64' => $content,
				'auto_close' => true,
				'locale' => 'pt-BR',
			]
		]);
	}

	public function cancel_document(string $key) {
		return $this->request('documents/' . $key . '/cancel', [], 'PATCH');
	}

	public function delete_document(string $key) {
		return $this->request('documents/' . $key, [], 'DELETE');
	}

	/**
	 * @param string $name
	 * @param string $email
	 *
	 * @return mixed|null
	 */
	public function create_signer(string $name, string $email) {
		return $this->request('signers', [
			'signer' => [
				'name' => $name,
				'email' => $email,
				'auths' => [ 'email'],
			]
		]);
	}

	/**
	 * @param string $document
	 * @param string $signer
	 * @param string $sign_as
	 *
	 * @return mixed|null
	 */
	public function add_signer_to_document(string $document, string $signer, string $sign_as) {
		return $this->request('lists', [
			'list' => [
				'document_key' => $document,
				'signer_key' => $signer,
				'sign_as' => $sign_as,
			]
		]);
	}

	/**
	 * @param string $path
	 * @param array $data
	 *
	 * @return mixed|null
	 */
	private function request(string $path, array $data, string $method = 'POST') {
		$response = wp_remote_request(
			sprintf(
				'%s/api/v1/%s?access_token=%s',
				$this->api_url,
				$path,
				$this->token,
			),
			[
				'method' => $method,
				'headers' => [
					'Content-Type' => 'application/json',
					'Accept' => 'application/json'
				],
				'body' => json_encode($data)
			]
		);

		if (is_wp_error($response)) {
			error_log( print_r( $response, true ) );

			return null;
		}

		return json_decode(wp_remote_retrieve_body($response));
	}
}
