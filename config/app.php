<?php
return [
    /**
     * The model path.
     * There stay all custom post type classes
     */
    'models_path' => APP_PATH . '/custom-post-types',

	/**
	 * The taxonomies classes path
	 * Define which  folder all taxonomies will be loaded.
	 */
    'taxonomies_path' => APP_PATH . '/taxonomies',

	/**
	 * The hooks path.
	 * Insert all hooks class in this folder to auto load into WordPress.
	 */
	'hooks_path' => APP_PATH . '/hooks',

	/**
	 * Short codes path
	 */
    'shortcodes_path' => APP_PATH . '/shortcodes',

	/**
	 * Ajax actions
	 */
    'ajax_path' => APP_PATH . '/ajax',

	/**
	 * Cron jobs actions
	 */
    'jobs_path' => APP_PATH . '/jobs',

    /**
     * Website Settings.
     * All website settings. All data in this file,
     * will generate one subpage in wp-admin > Settings > My Settings
     * You can edita all settings in
     */
    'settings_file' => APP_CONFIG_PATH . '/settings.php',

	/**
	 * Load all integrated plugins
	 * You can integrate third party plugins. Just add the main file in integrated_plugins map.
	 */
	'plugins' => [],

	/**
	 * Load third party packages
	 */
	'vendors' => [
		'/Clicksign/ClickSignService.php',
		'/TCPDF/tcpdf.php',
	]
];
