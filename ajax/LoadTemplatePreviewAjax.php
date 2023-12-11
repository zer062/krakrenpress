<?php

namespace ajax;

use Core\Tentacles\Ajax;

class LoadTemplatePreviewAjax extends Ajax {

	/**
	 * @var string
	 */
	public $action = 'alab_load_template_preview';

	/**
	 * @var string
	 */
	public $nonce = 'alab_load_template_fields_nonce';

	/**
	 * @return void
	 */
	public function handle() {

		if (!isset($this->params['template']) || !$this->params['template']) {
			echo  '<h5>Template inválido</h5>'; die;
		}

		$patient_data = [];
		parse_str($this->params['data'], $patient_data);
		$document = get_field('documento_corpo', $patient_data['template']);
		$doctor = $this->getDoctorData();

		unset($patient_data['template']);

		$vars = [];
		$values = array_values($patient_data);

		foreach ($patient_data as $key => $value) {
			$vars['[' . $key . ']'] = $value;
		}

		$document = str_replace(array_keys($doctor), array_values($doctor), $document);
		$document = str_replace(array_keys($vars), array_values($values), $document);

		$output = '<div class="row"><div style="height: 620px; overflow-y: scroll; font-family: Dejavu Sans; padding-right: 20px;">';
		$output .= $document;
		$output .= '</div></div>';
		echo $output; die;
	}

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
}
