<?php

namespace Hook;

use Core\Tentacles\Hook;

class SaveFieldsInJsonHook extends Hook
{
	/**
	 * @var bool
	 */
	public $is_filter = true;

	/**
	 * @var string
	 */
	public $action = 'acf/settings/save_json';

	/**
	 * @return string
	 */
	public function handle()
	{
        return APP_PATH . '/assets/fields';
	}
}
