<?php

namespace Model;

use Core\Tentacles\Model;

class AlabPatientDocument extends Model
{
	/**
	 * @var string
	 */
	public $post_type = 'alab_patient_document';

	/**
	 * @var string
	 */
	public $singular_name = 'Documento de Paciente';

	/**
	 * @var string
	 */
	public $plural_name = 'Documentos de Pacientes';

	/**
	 * @var string
	 */
	public $menu_icon = 'dashicons-media-document';

	/**
	 * @var bool
	 */
	public $hierarchical = true;

	/**
	 * @var bool
	 */
	public $public = false;

	/**
	 * @var bool
	 */
	public $exclude_from_search = true;

	/**
	 * @var bool
	 */
	public $query_var = false;

	/**
	 * @var string[]
	 */
	public $support = [
		'author',
	];
}
