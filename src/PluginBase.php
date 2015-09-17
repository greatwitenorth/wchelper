<?php

namespace Greatwitenorth\WCHelper;

abstract class PluginBase {
	
	public $override_templates = false;
	
	public function __construct() {
		// Enable template overriding for the plugin.
		!$this->override_templates ?: $this->enable_template_override();
		
	}
	
	protected function base_path($relativePath = '') {
		return untrailingslashit( plugin_dir_path( __FILE__ ) ) . $relativePath;
	}
	
	public function enable_template_override() {
		add_filter( 'woocommerce_locate_template', [$this, 'override_templates'], 1, 3 );
	}


	public function override_templates( $template, $template_name, $template_path ) {
		global $woocommerce;
		$_template = $template;
		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}

		$plugin_path = $this->base_path('/templates/');

		// Look within passed path within the theme - this is priority
		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name
			)
		);

		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		if ( ! $template ) {
			$template = $_template;
		}

		return $template;
	}
}