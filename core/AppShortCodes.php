<?php


namespace Core;


class AppShortCodes {

	/**
	 * @var array
	 */
	protected $shortcodes = [];

	/**
	 * AppShortCodes constructor.
	 */
	public function __construct() {
		$this->load_shortcodes();
		$this->add_shortcodes();
	}

	/**
	 * Load all shortcodes
	 */
	protected function load_shortcodes() {
		$all_shortcodes = scandir( (new \Core\AppSettings)->get_app_setting( 'shortcodes_path' ) );

		foreach ( $all_shortcodes as $shortcode ) {
			if ( $shortcode === '.' || $shortcode === '..' ) continue;
			$shortcode_class = str_replace('.php', '', "\ShortCode\\{$shortcode}" );
			$this->shortcodes[] = new $shortcode_class();
		}
	}

	/**
	 * Register all short codes
	 */
	protected function add_shortcodes() {

		foreach ($this->shortcodes as $shortcode) {

			add_shortcode( $shortcode->shortcode, function ($atts, $content = null) use ($shortcode) {
				$the_shortcode = new $shortcode();
				$a = shortcode_atts( $the_shortcode->attributes, $atts );
				$the_shortcode->attributes = $a;
				$the_shortcode->content = $content;
				return $the_shortcode->output();
			});

			if (!empty($shortcode->js)) {
				add_action('wp_footer', function () use($shortcode) {
					$js_code = file_get_contents($shortcode->js);
					echo '<script>' . $js_code . '</script>';
				});
			}

			if (!empty($shortcode->css)) {
				add_action('wp_head', function () use($shortcode) {
					$css_code = file_get_contents($shortcode->css);
					echo '<style>' . $css_code . '</style>';
				});
			}
		}
	}
}