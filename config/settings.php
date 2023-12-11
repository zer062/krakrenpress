<?php
return [
    /**
     * The settings app slug
     */
    'settings_page_slug' => 'alab-app-settings',

	'settings_key' => 'alab',

    /**
     * App settings menu title
     */
    'settings_menu_title' => __( 'Configurações Alab', APP_DOMAIN ),

    /**
     * App settings page title
     */
    'settings_page_title' => __( 'Configurações Alab', APP_DOMAIN ),

    /**
     * Define all settings tab
     */
    'settings_tabs' => [
		[
			'slug' => 'clicksign',
			'title' => __( 'ClickSign', APP_DOMAIN ),
			'description' => __( 'Configurações do plugin', APP_DOMAIN ),
			'fields' => [
				'clicksign_api' => [
					'type' => 'text',
					'label' => __( 'URL da API', APP_DOMAIN ),
					'required' => true,
				],
				'clicksign_token' => [
					'type' => 'text',
					'label' => __( 'Token', APP_DOMAIN ),
					'required' => true,
				],
			],
		],
    ]
];
