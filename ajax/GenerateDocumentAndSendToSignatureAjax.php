<?php

namespace ajax;

use Core\Tentacles\Ajax;
use Model\AlabPatientDocument;

class GenerateDocumentAndSendToSignatureAjax extends Ajax {

	/**
	 * @var string
	 */
	public $action = 'alab_generate_document';

	/**
	 * @var string
	 */
	public $nonce = 'alab_generate_document_nonce';

	/**
	 * @var AlabPatientDocument
	 */
	private AlabPatientDocument $document;

	/**
	 * @var \WP_Post
	 */
	private \WP_Post $template;

	/**
	 * @var \WP_User
	 */
	private \WP_User $doctor;

	/**
	 * @return void
	 */
	public function handle() {
		$patient_data = [];
		parse_str($this->params['data'], $patient_data);
		$this->params = array_merge($this->params, $patient_data);
		$this->template = get_post($this->params['template']);
		$this->doctor = get_user_by('id', get_current_user_id());

		$this->save_document();
		update_post_meta(
			$this->document->ID,
			'patient_data',
			serialize($patient_data)
		);
		update_post_meta(
			$this->document->ID,
			'document_pdf_url',
			ABSPATH . 'wp-content/uploads/documents/' . $this->document->ID . '.pdf'
		);
		$this->generate_document();
		return $this->handleToSign();
	}

	/**
	 * @return void
	 */
	private function save_document() {

		$this->document = new AlabPatientDocument();
		$this->document->post_title = sprintf(
			'%s - %s',
			$this->template->post_title,
			$this->params['nome'] ?? ''
		);
		$this->document->save();
	}

	/**
	 * @return void
	 */
	private function generate_document() {
		$pdf = new \TCPDF(
			PDF_PAGE_ORIENTATION,
			PDF_UNIT,
			PDF_PAGE_FORMAT,
			true,
			'UTF-8',
			false
		);

		$content = get_field('documento_corpo', $this->template->ID);
		$patient_data = [];
		parse_str($this->params['data'], $patient_data);
		$doctor = $this->getDoctorData();

		unset($patient_data['template']);

		$vars = [];
		$values = array_values($patient_data);

		foreach ($patient_data as $key => $value) {
			$vars['[' . $key . ']'] = $value;
		}

		$content = str_replace(array_keys($doctor), array_values($doctor), $content);
		$content = str_replace(array_keys($vars), array_values($values), $content);

		$pdf->SetCreator('Alab Advogados');
		$pdf->SetAuthor($this->doctor->first_name . ' ' . $this->doctor->last_name);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('Helvetica', '', 9);
		$pdf->AddPage();
		$pdf->writeHTML($content, true, false, true, false, '');
		$pdf->Output(ABSPATH . 'wp-content/uploads/documents/' . $this->document->ID . '.pdf', 'F');
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	private function getDoctorData(): array {
		$doctor = new \WC_Customer(wp_get_current_user()->ID);
		$result = [
			'[cliente.nome]' => $doctor->get_first_name() ? $doctor->get_first_name() . ' ' . $doctor->get_last_name() : $doctor->get_display_name(),
			'[cliente.razao_social]' => $doctor->get_billing_company(),
			'[cliente.endereco]' => $doctor->get_billing_address() . ' ' . $doctor->get_billing_address_2() . ', ' .  $doctor->get_city() .' - ' .  $doctor->get_state(),
			'[cliente.telefone]' => $doctor->get_billing_phone(),
			'[cliente.cep]' => $doctor->get_postcode(),
		];

		return $result;
	}

	private function handleToSign() {
		$clickSignService = new \vendors\Clicksign\ClickSignService();
		$document_data = $clickSignService->submit_document_to_sign(
			reset($this->document->post_title) . '.pdf',
			'data:application/pdf;base64,' . base64_encode(file_get_contents($this->document->document_pdf_url))
		);

		if (isset($document_data->errors)) {
			$this->handle_on_error($document_data->document->key);
			return wp_send_json_error([
				'message' => 'Erro ao gerar documento!',
				'error' => $this->get_ajax_error_message($document_data->errors)
			]);
		}

		$doctor_data = $clickSignService->create_signer(
			$this->doctor->first_name . ' ' . $this->doctor->last_name,
			$this->doctor->user_email
		);

		if (isset($doctor_data->errors)) {
			$this->handle_on_error($document_data->document->key);
			return wp_send_json_error([
				'message' => 'Erro criar signatário!',
				'error' =>  $this->get_ajax_error_message($doctor_data->errors)
			]);
		}

		$patient_data = $clickSignService->create_signer(
			$this->params['nome'],
			$this->params['email']
		);

		if (isset($patient_data->errors)) {
			$this->handle_on_error($document_data->document->key);
			return wp_send_json_error([
				'message' => 'Erro criar signatário!',
				'error' => $this->get_ajax_error_message($patient_data->errors)
			]);
		}

		$doctor_as_signer_data = $clickSignService->add_signer_to_document(
			$document_data->document->key,
			$doctor_data->signer->key,
			'contractee'
		);
		$patient_data_as_signer_data = $clickSignService->add_signer_to_document(
			$document_data->document->key,
			$patient_data->signer->key,
			'contractor'
		);

		update_post_meta($this->document->ID, 'document_clicksign_key', $document_data->document->key);
		update_post_meta($this->document->ID, 'document_clicksign_doctor_signer_key', $doctor_data->signer->key);
		update_post_meta($this->document->ID, 'document_clicksign_patient_signer_key', $patient_data->signer->key);
		update_post_meta($this->document->ID, 'document_clicksign_doctor_signer_url', $doctor_as_signer_data->list->url);
		update_post_meta($this->document->ID, 'document_clicksign_patient_signer_url', $patient_data_as_signer_data->list->url);
		update_post_meta($this->document->ID, 'document_clicksign_status', 'running');

		return wp_send_json_success(['message' => 'Documento gerado com sucesso!']);
	}

	private function get_ajax_error_message($errors) {
		$message = '';
		foreach ($errors as $error) {
			$message .= '<p>' . $error . '</p>';
		}
		return $message;
	}

	private function handle_on_error(string $key) {
		$this->document->delete();
		$clickSignService = new \vendors\Clicksign\ClickSignService();
		$clickSignService->cancel_document($key);
		$clickSignService->delete_document($key);
	}
}
