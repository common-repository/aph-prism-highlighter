<?php

class Aphph_Build {
	public function build_files( $components_list) 
	{
		// BUILD PATH
		$upload_dir = wp_upload_dir();
		$build_path = $upload_dir['basedir'] . APHPH_DS . 'aphph';
		
		if (!file_exists($build_path)) {
			mkdir($build_path, 0777);
		}
		
		$options = get_option(APHPH_OPTION);
		$token = $options['token'];
		
		// Build prism with selected lang
		$path = APHPH_PLUGIN_PATH . APHPH_DS . 'includes' . APHPH_DS . 'prism' . APHPH_DS;
		// $scripts = file_get_contents($path . 'prism.js') . ';';
		// echo $path. '<br/>';
		// echo WP_PLUGIN_URL; 
		// die;
		$scripts = '';
		$addcss = '';
		
		foreach ($options['lang-used'] as $lang) 
		{
			$jsfile = $path . 'components' . APHPH_DS . 'prism-'.$lang.'.min.js';
			if (file_exists($jsfile)) {
				$scripts .= file_get_contents($jsfile) . ';';
			}
			
			if ($lang == 'adddarkplain' || $lang == 'addlightplain')
			{
				$addcss .= "\r\n" . 
			"pre.aphph-adddarkplain,
pre.aphph-addlightplain {
    padding: 7px 15px;
    display: block;
    font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
    word-wrap: break-word;
    font-size: 95%;
    text-align: left
}
pre.aphph-addlightplain {
	background: #f9f9f9; !important;
    color: #4a4a4a;
}
pre.aphph-adddarkplain {
	background: #131313 !important;
	color: #CACACA;
}";
			}
		}
		
		/**
		 * Add Plugins...
		*/
	
		$plugin_path = $path . 'plugins' . APHPH_DS;
		$plugin_used = array();
		
		$scripts = '';
		foreach ( $components_list['plugins'] as $plugin_name => $item ) {
			if (!empty($options[$plugin_name])) {
				$scripts .= file_get_contents($plugin_path . $plugin_name . APHPH_DS .'prism-'.$plugin_name.'.min.js') . ';';
				$plugin_used = $plugin_name;
			}
		}
		
		/**
			Script
		*/	
		/* Cleanup the build directory */
		$files = scandir($build_path);
		foreach ($files as $file) {
			if ($file == '.' || $file == '..')
				continue;
			unlink ($build_path . APHPH_DS . $file);
		}

		// We build with $token to make sure the client browser use our lastest build
		file_put_contents($build_path . APHPH_DS . 'aphph-prism-' . $token . '.js', $scripts);
		
		/**
			Theme
		*/
		
		// Get theme css
		if (strtolower($options['theme']) == 'default')
			$options['theme'] = 'prism';
		$prism_css = file_get_contents($path . 'themes' . APHPH_DS . $options['theme']. '.css');
		
		// Get plugins css
		foreach ($plugin_used as $plugin)
		{
			$css_file = $path . 'plugins' . APHPH_DS . $plugin . APHPH_DS . 'prism-' . $plugin . '.css';
			if (file_exists($css_file))
			{
				$prism_css .= "\r\n" . file_get_contents($css_file);
			}
		}
		
		// ADDITIONAL CSS
		$prism_css .= $addcss;
		
		if ($options['max-height']){
			$prism_css .= "\r\n" . 
			'pre.aphph-container {
	max-height: '.$options['max-height'] .'px;	
}';
		}
		
		if ($options['add-css']) {
			$prism_css .= "\r\n" . $options['add-css-value'];
		}
		
		$file_path = $build_path . APHPH_DS . 'aphph-prism-' . $token . '.css';
		// $old_path = $theme_path . APHPH_DS . 'aphph-prism-' . $token . '.css';
		file_put_contents($file_path, $prism_css);
	}
}