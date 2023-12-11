<?php

namespace Ajax;

use Core\Tentacles\Ajax;

class LoadTemplateFieldsAjax extends Ajax {

	/**
	 * @var string
	 */
	public $action = 'alab_load_template_fields';

	/**
	 * @var string
	 */
	public $nonce = 'alab_load_template_fields_nonce';

	/**
	 * @return void
	 */
	public function handle() {

		if (!isset($this->params['template']) || !$this->params['template']) {
			echo  '<h5>Esse Documento não possui campos definidos</h5>'; die;
		}


		$fields = get_field('formulario', $this->params['template']);

		if (!$fields) {
			echo  '<h5>Esse Documento não possui campos definidos</h5>'; die;
		}

		$output = '';

		foreach ($fields as $field) {
			$output .= '<div class="row">' .
			           '<div class="col">' .
						'<label>' . $field['nome'] . ': *</label>';
			switch ($field['tipo']) {
				case 'text':
					$output .= '<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="' . $field['identificador'] . '" id="' . $field['identificador'] . '" required="required">';
					break;
				case 'email':
					$output .= '<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="' . $field['identificador'] . '" id="' . $field['identificador'] . '" required="required">';
					break;
				case 'textarea':
					$output .= '<textarea class="woocommerce-Input woocommerce-Input--text input-text" name="' . $field['identificador'] . '" id="' . $field['identificador'] . '" rows="5" required="required"></textarea>';
					break;
			}
			$output .= '</div>' .
			'</div>';
		}

		echo $output; die;
	}
}
