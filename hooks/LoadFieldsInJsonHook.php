<?php

namespace Hook;

use Core\Tentacles\Hook;

class LoadFieldsInJsonHook extends Hook
{
	/**
	 * @var bool
	 */
	public $is_filter = true;

	/**
	 * @var string
	 */
	public $action = 'acf/settings/load_json';

	/**
	 * @return string
	 */
	public function handle()
	{
        return APP_PATH . '/assets/fields';
	}
}
