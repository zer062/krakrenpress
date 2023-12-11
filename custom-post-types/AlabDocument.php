<?php

namespace Model;

use Core\Tentacles\Model;

class AlabDocument extends Model
{
	/**
	 * @var string
	 */
	public $post_type = 'alab_document';

	/**
	 * @var string
	 */
	public $singular_name = 'Documento';

	/**
	 * @var string
	 */
	public $plural_name = 'Documentos';

	/**
	 * @var string
	 */
	public $menu_icon = 'dashicons-media-default';

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
		'title',
		'revisions',
	];
}
