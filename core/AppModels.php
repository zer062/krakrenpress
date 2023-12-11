<?php


namespace Core;

class AppModels
{
	/**
	 * @var array
	 */
    protected $models = [];

	/**
	 * AppModels constructor.
	 */
    public function __construct()
    {
        $this->load_models();
    }

	/**
	 * Load all model and register post types
	 *
	 * @return void
	 */
    protected function load_models()
    {
	    $settings = include ( APP_CONFIG_PATH . '/app.php' );
        $all_models = scandir( $settings[ 'models_path' ] );

        foreach ( $all_models as $model ) {
            if ( $model === '.' || $model === '..' ) continue;
            $model_class = str_replace('.php', '', "\Model\\{$model}" );
            $this->models[] = new $model_class();
        }
    }
}
