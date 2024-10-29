<?php
/*
	APH Prism Highlighter
	https://jagowebdev.com
*/ 
class Aphph_Admin
{
	/**
		Initiate options when the plugin is activated
	*/
	private $components_list;
	
	private $data_options = array (
									'lang-used' => array (
													'core',
													'clike',
													'markup',
													'markup-templating',
													'php',
													'css',
													'javascript',
													'sass',
													'sql',
													'adddarkplain',
													'addlightplain'
												),
									'default-lang' => 'php',
									'max-height' => '480',
									'add-css' => 0,
									'add-css-value' => '',
									'theme' => 'default',
									'gutter' => 1,
									'start-number' => 1,
									'auto-links' => 1,
									'show-lang' => 0,
									'show-hidden-char' => 0,
									'class' => '',
									'token' => '1470914799'
								);
	
	private $options;
	private $options_phdata;
	private $admin_notices;
	
    public function __construct()
    {
		$this->init();
		
		$this->admin_notices = new Aphph_Admin_Notices;
		$this->options = get_option( APHPH_OPTION, array() );
		$this->options_phdata = get_option( APHPH_OPTION_PHDATA, array() );
				
		register_activation_hook ( APHPH_PLUGIN_PATH . APHPH_DS . APHPH_PLUGIN_FILE_NAME, array($this, 'activate_plugin') );
		
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_filter( 'plugin_action_links', array($this, 'action_link'), 10, 5);
		add_action( 'admin_enqueue_scripts', array($this, 'register_scripts') );
		add_action( 'updated_option', array($this, 'build_files'), 10, 3); 
		add_action( 'plugins_loaded', array($this, 'check_update') );
		
		// AJAX
		add_action( 'wp_ajax_nopriv_aphph-dismiss-notice' , array( $this , 'ajax_no_priv' ) );
		add_action( 'wp_ajax_aphph-dismiss-notice', array($this, 'ajax_dismiss_notice') );
    }
	
	private function init() 
	{
		$contents =  file_get_contents(APHPH_PLUGIN_PATH . APHPH_DS . 'includes/prism/components_json.json');
		$components = json_decode($contents, true);
		// Languages 
		foreach ($components as $item => $val) 
		{
			if ($item == 'languages') {
				foreach($val as $component_name => $item) {
					if ($component_name == 'meta') {
						continue;
					}
					$components_list['lang-list'][$component_name] = $item;
				}
				
				$components_list['lang-list']['adddarkplain'] = array('title' => 'Dark Plain');
				$components_list['lang-list']['addlightplain'] = array('title' => 'Light Plain');
			}
			
			if ($item == 'themes') {
				foreach($val as $theme_name => $item) {
					if ($theme_name == 'meta') {
						continue;
					}
					if (is_array($item)) {
						$title = $item['title'];
					} else {
						$title = $item;
					}
					$components_list['themes'][$theme_name] = $title;
				}
			}
			
			if ($item == 'plugins') {
				foreach($val as $theme_name => $item) {
					if ($theme_name == 'meta') {
						continue;
					}
					if (is_array($item)) {
						$title = $item['title'];
					} else {
						$title = $item;
					}
					$components_list['plugins'][$theme_name] = $title;
				}
			}
		}
		$this->components_list = $components_list;
	}
	
	public function ajax_no_priv()
	{
		
	}
	
	// When the close button is clicked 
	public function ajax_dismiss_notice()
	{
		$check = wp_verify_nonce($_POST['nonce'], 'aphph-admin-all');
		if ($check)
		{
			
			$this->admin_notices->delete_notice($_POST['msg']);
		}
		wp_send_json_success(
			array(
				'msg' => 'success',
				'check' => $check
			)
		);
	}
	
	public function page_init()
    {
		register_setting(
            'aphph_option_group', // Option group
            APHPH_OPTION,
			array ($this, 'submit_validation')
        );
	}
	
	public function activate_plugin()
	{
		if (!$this->options) 
		{
			update_option(APHPH_OPTION, $this->data_options);
			update_option(APHPH_OPTION_VERSION, APHPH_PLUGIN_VERSION);
			update_option(APHPH_OPTION_PHDATA, $this->components_list);
			add_action('wp_head', array($this, 'build_files'));
		}
		
		update_option(APHPH_OPTION_PHDATA, $this->components_list);
	}
	
	public function check_update() 
	{
		$plugin_option_version = get_option( APHPH_OPTION_VERSION, '0' );
		if (version_compare(APHPH_PLUGIN_VERSION, $plugin_option_version) > 0)
		{
			update_option(APHPH_OPTION_VERSION, APHPH_PLUGIN_VERSION);
			update_option(APHPH_OPTION_PHDATA, $this->components_list);
			if (!$plugin_option_version || $plugin_option_version < '1.3.1')
			{
				$msg = 'APH PRISM HIGHLIGHTER v' . APHPH_PLUGIN_VERSION . ' Update the prism core, update compatibilities to WordPress 5, and several bug fixes';
				$this->admin_notices->add_notice($msg, 'success', false, true);
			}				
		}
	}
	
	public function register_scripts($hook)
	{
		if ($hook == 'settings_page_'.APHPH_PLUGIN_DIR_NAME)
		{
			wp_enqueue_style('aphph-style', APHPH_PLUGIN_URL . '/css/aphph-admin.css?rand='.time(), '', APHPH_PLUGIN_VERSION);
			wp_enqueue_style('aphph-icon', APHPH_PLUGIN_URL . '/css/icomoon/style.css?rand='.time(), '', APHPH_PLUGIN_VERSION);
			wp_enqueue_script('aphph-taboverride', APHPH_PLUGIN_URL . '/js/taboverride/taboverride.min.js', '', APHPH_PLUGIN_VERSION);
			wp_enqueue_script('aphph-prism-components', APHPH_PLUGIN_URL . '/includes/prism/components.js?rand='.time(), '', APHPH_PLUGIN_VERSION);
			wp_enqueue_script('aphph-admin', APHPH_PLUGIN_URL . '/js/aphph-admin.js?rand='.time(), 'aphph-prism-components', APHPH_PLUGIN_VERSION);
			
		}
		
		wp_enqueue_script('aphph-admin-all', APHPH_PLUGIN_URL . '/js/aphph-admin-all.js?rand='.time(), '', APHPH_PLUGIN_VERSION);
		wp_localize_script (
			'aphph-admin-all', 
			'aphph', 
			array(
				'nonce'	=> wp_create_nonce('aphph-admin-all'),
				'ajaxurl' => admin_url('admin-ajax.php')
			)
		);
	}
	
	/**
	* Add Settings ling to plugin list Settings | Deactivate | Edit
	*/
	public function action_link($links, $file)	
	{
		static $plugin;
		
		if (!isset($plugin))
			$plugin = APHPH_PLUGIN_DIR_NAME . '/' . APHPH_PLUGIN_FILE_NAME;
		
		if ($plugin == $file)
		{
			$setting_link = '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page='.APHPH_PLUGIN_DIR_NAME.'">Settings</a>';
			array_unshift($links, $setting_link);
		}
		return $links;
	}

	/**
     * Add options page
     */
    public function add_plugin_page()
    {
        $page_title	= 'APH Prism Highlighter Options';
		$menu_title	= 'Prism Highlighter';
		$url = 'aph-prism-highlighter';
        add_options_page(
            $page_title, 
            $menu_title, 
            'manage_options', 
            $url,
            array( $this, 'admin_setting_page' )
        );
    }

    public function admin_setting_page()
    {
		?>
		<div class="wrap">
            <h2 style="display:none">Options</h2>
			<div class="aphph-wrap">
				<h2 class="title">APH Prism Highlighter</h2>
				<div class="aphph-form-container"> 
					<form method="post" action="options.php" id="aphph-form">
					<?php settings_fields('aphph_option_group'); ?>
					<table class="form-table">
						<tr>
							<th>Choose languages</th>
							<td>
								<?php $this->option_language_list(); ?>
							</td>
						</tr>
						<tr>
							<th>Default Language</th>
							<td>
								<?php $this->option_default_language()?>
							</td>
						<tr>
							<th>Theme</th>
							<td>
								<?php $this->option_theme_list(); ?>
							</td>
						</tr>
						<tr>
							<th>Options</th>
							<td>
								<?php $this->option_plugin_options(); ?>
							</td>
						</tr>
						<tr>
							<th>Starting Line Number</th>
							<td>
								<input type="text" class="small-text" name="<?php echo APHPH_OPTION ?>[start-number]" value="<?php echo $this->options['start-number'] ?>"/>
								<p class="description">Used when "Show line numbers" is checked</p>
							</td>
						</tr>
						<tr>
							<th>Max Height</th>
							<td>
								<input type="text" class="small-text" name="<?php echo APHPH_OPTION ?>[max-height]" value="<?php echo $this->options['max-height'] ?>"/>px
								<p class="description">Set maximum height of code container. Recomended 480px. This is usefull in long code, users don't need to scroll page a lot when they want to continue reading the article</p>
							</td>
						</tr>
						<tr>
							<th>Additional CSS</th>
							<td>
								<?php
								$list = array(0 => 'No', 1 => 'Yes');
								echo '<select name="' . APHPH_OPTION . '[add-css]" id="aphph-add-css-option">';
								foreach ($list as $key => $val)
								{
									$selected = $key == $this->options['add-css'] ? ' selected="selected"' : '';
									echo '<option value="'.$key.'"'.$selected.'>'. $val .'</option>';
								}
								echo '</select>';
								
								$show_add_css = !$this->options['add-css'] ? ' style="display:none"' : '';
								?>
								<p class="description">
									Add css code to the compiled css file. This is useful for example if we want to add responsive style to the code.
								</p>
								<p class="description">
										The container class is aphph-container. <a href="#" id="aphph-css-example-btn">Click here for example of responsive style</a>
									</p>
								<div id="aphph-add-css-container"<?=$show_add_css?>>
									<p>
										<textarea class="aphph-textarea" id="aphph-add-css-textarea" name="<?php echo APHPH_OPTION ?>[add-css-value]"/><?=$this->options['add-css-value']?></textarea>
									</p>
									
									<pre class="aphph-css-example" id="aphph-css-example" style="display:none">code[class*="language-"], pre[class*="language-"] {
    font-size: 15px;
}

@media screen and (max-width: 640px) {
    code[class*="language-"], pre[class*="language-"] {
		font-size: 13px;
	}
}

@media (min-width:641px) and (max-width:800px) {
    code[class*="language-"], pre[class*="language-"] {
		font-size: 14px;
	}
}</pre>
							</div>
							</td>
						</tr>
						<tr>
							<th>Add global class</th>
							<td>
								<input type="text" name="<?php echo APHPH_OPTION ?>[class]" value="<?php echo $this->options['class']?>"/>
								<p class="description">Add class to each code block container</p>
							</td>
						</tr>
					</table>
					<?php if (function_exists('submit_button'))
					{
						submit_button('Save Changes', 'primary', 'aphph-submit', false);
						echo ' ';
						submit_button('Restore to Defaults', 'primary', 'aphph-defaults', false);
					} else {
						echo '
							<input type="submit" name="aphph-submit" id="aphph-submit" class="button button-primary" value="Save Changes"/> 
							<input type="submit" name="aphph-defaults" id="aphph-defaults" class="button button-primary" value="Restore to Defaults"/>
						';
					}?>
					</form>
				</div>
			</div>
		</div>
		
       
        <?php
    }
	
	public function option_language_list() {
	
		$lang_used = $this->options['lang-used'];
		echo '<div class="aphph-langused-container aphph-clearfix" id="aphph-langused-container">';
		
		$load_lang['core'] = 'Core';
		$lang_list = $this->components_list['lang-list'];
		
		foreach ($lang_used as $lang)
		{
			// Fallback to old plugin version
			if (strtolower($lang) == 'core')
				continue;
			
			$lang_item = $lang_list[$lang];
			$lang_title = $lang_list[$lang]['title'];

			// Dependencies
			if (is_array($lang_item)) {
				if (key_exists('require', $lang_item)) {
					if (!is_array($lang_item['require'])) {
						$lang_item['require'] = array($lang_item['require']);
					}
					foreach ($lang_item['require'] as $lang_required) {
						if (!key_exists($lang_required, $load_lang)) {
							$load_lang[$lang_required] = $lang_list[$lang_required]['title'];
							$file_name[$lang_required] = 'prism-'.$lang_required.'.min.js';
						}
					}
				}
			}
			$load_lang[$lang] = $lang_title;
			$file_name[$lang] = 'prism-'.$lang.'.min.js';

		}
		
		// Create lang buttons on the Choose language options
		foreach ($load_lang as $lang_name => $lang_title) {
			
			$msg = '';
			/* echo '<pre>';
			print_r($file_name);
			die;
			echo $lang_name; die; */
			if (substr($lang_name, 0, 3) != 'add' 
					&& key_exists($lang_name, $file_name) && !file_exists(APHPH_PLUGIN_PATH . APHPH_DS . 'includes' . APHPH_DS . 'prism' . APHPH_DS . 'components' . APHPH_DS . $file_name[$lang_name])
				)
			{
				$msg = '<span class="description">File ' . $file_name[$lang_name] . ' not exists</span>';
			}
			
			$id 	= $lang_name == 'core' ? 'aphph-langused-disabled' : 'aphph-langused-'.$lang_name;
			$class 	= $lang_name == 'core' ? ' aphph-langused-disabled' : '';
			$link 	= $lang_name == 'core' ? '<span><i class="aphph-icon-cross"></i></span>' : '<a class="aphph-del-lang" href="#"><i class="aphph-icon-cross"></i></a>';
			
			if (key_exists($lang_name, $file_name) && is_array($lang_list[$lang_name])) {
				if (key_exists('aliasTitles', $lang_list[$lang_name])) {
					$lang_title .= ' / ' . join(' / ', $lang_list[$lang_name]['aliasTitles']);
				}
			}
			
			echo '
				<div class="aphph-langused-item'.$class.'" id="'.$id.'">
					<input type="hidden" data-lang-name="'.$lang_name.'" name="'.APHPH_OPTION.'[lang-used][]" value="'.$lang_name.'"/>
					'.$lang_title. $link .'
				</div>' . $msg;
		}
		
		echo '</div>
		
		<a class="button" href="#" id="aphph-show-lang">Add Language</a>
		<a class="button" href="#" id="aphph-delall-langused">Remove All</a>';
	
		// CREATE LIST OF ALL AVAILABLE LANGUAGES - Built in languages
		echo '<div class="aphph-langlist-container" id="aphph-langlist-container">';
		
		unset($lang_list['core']);
		echo '<label for="aphph-langlist-core">
				<input  class="aphph-langlist-item" type="checkbox" name="core" id="aphph-langlist-core" value="core" checked="checked" disabled="disabled"/>
				Core
			</label><hr/>';
					
		// Languages
		foreach ($lang_list as $lang_key => $item)
		{
			$msg = '';
			if (substr($lang_key, 0, 3) != 'add')
			{	
				$file_name = 'prism-'.$lang_key.'.min.js';
				
				if (!file_exists(APHPH_PLUGIN_PATH . APHPH_DS . 'includes' . APHPH_DS . 'prism' . APHPH_DS . 'components' . APHPH_DS . $file_name))
				{
					$msg = '<span class="description">File ' . $file_name . ' not exists</span>';
				}
			}
			$checked = key_exists($lang_key, $load_lang) ? ' checked="checked"' : '';
			
			$title = $item['title'];
			if (key_exists('aliasTitles', $item)) {
				$title .= ' / ' . join(' / ', $item['aliasTitles']);
			}
			echo '
				<label for="aphph-langlist-'.$lang_key.'">
					<input  class="aphph-langlist-item" type="checkbox" name="'.$lang_key.'" id="aphph-langlist-'.$lang_key.'" value="'.$lang_key.'"'. $checked .'/>
					'.$title.'
				</label>' . $msg;
		}
		
		echo '</div>';
		
		// Additional language
	}
	
	public function option_default_language() {
		$lang_used = $this->options['lang-used'];
		echo '<select name="' . APHPH_OPTION . '[default-lang]" id="opt-aphph-default-lang">';
		foreach($lang_used as $lang)
		{
			if ($lang == 'core')
				continue;
			
			$selected = $this->options['default-lang'] == $lang ? ' selected="selected"' : '';
			$item = $this->components_list['lang-list'][$lang];
		
			$title = $item;
			if (is_array($item)) {
				
				$title = $item['title'];
				if (key_exists('aliasTitles', $item)) {
					$title .= ' / ' . join(' / ', $item['aliasTitles']);
				}
			}
			echo '<option value="'.$lang.'"' . $selected . '>' . $title . '</option>';
			// echo '<option value="'.$lang.'"' . $selected . '>' . $this->options_phdata['lang-list'][$lang] . '</option>';
		}
		echo '</select>
				<p class="description">Default language in the code editor\'s drop down menu</p>';
		
		// Other list of language, hidden, displayed when the language pack changed;
		echo '<span id="aphph-json-user-options" style="display:none">'.json_encode($this->options).'</span>';
	}
	
	public function option_theme_list() {
		
		$theme_list = $this->components_list['themes'];

		$options = '<select name="' . APHPH_OPTION . '[theme]">';
		foreach($theme_list as $theme => $theme_name)
		{
			$selected = $this->options['theme'] == $theme ? ' selected="selected"' : '';
			$options .= '<option value="'.$theme.'"' . $selected . '>' . $theme_name . '</option>';
		}
		$options .= '</select>';
		
		echo $options;
	}
	
	public function option_plugin_options() {
		$options = @$this->options['options'];
		?>
		<p>
			<label for="aphph-gutter">
				<?php $checked = $this->options['gutter'] == 1 ? ' checked="checked"' : ''; ?>
				<input type="checkbox" name="<?php echo APHPH_OPTION?>[gutter]" id="aphph-gutter" value="1"<?php echo $checked?>/>
				Show line numbers
			</label>
		</p>
		<p>
			<label for="aphph-auto-links">
				<?php $checked = $this->options['auto-links'] == 1 ? ' checked="checked"' : ''; ?>
				<input type="checkbox" name="<?php echo APHPH_OPTION?>[auto-links]" id="aphph-auto-links" value="1"<?php echo $checked?>/>
				Make all url links in the code clickable
			</label>
		</p>
		<p>
			<label for="aphph-show-langtitle">
				<?php $checked = $this->options['show-lang'] == 1 ? ' checked="checked"' : ''; ?>
				<input type="checkbox" name="<?php echo APHPH_OPTION?>[show-lang]" id="aphph-show-langtitle" value="1"<?php echo $checked?>/>
				Show language title to each code block container
			</label>
		</p>
		<p>
			<label for="aphph-show-hiddenchar">
				<?php $checked = $this->options['show-hidden-char'] == 1 ? ' checked="checked"' : ''; ?>
				<input type="checkbox" name="<?php echo APHPH_OPTION?>[show-hidden-char]" id="aphph-show-hiddenchar" value="1"<?php echo $checked?>/>
				Show hidden character. Show tabs and line breaks with symbol <a href="http://prismjs.com/plugins/show-invisibles/index.html" target="_blank">demo</a>
			</label>
		</p>
		<?php
	}
	
	public function submit_validation($inputs)
	{
		$token = time();
		$inputs['token'] = $token;
		
		if (key_exists('aphph-defaults', $_POST))
		{
			$inputs = $this->data_options;
		} else {
			foreach ($this->options as $key => $val)
			{
				if (!key_exists($key, $inputs))
				{
					$inputs[$key] = 0;
				}
			}
		}
		return $inputs;
	}
	
	public function build_files()
	{
		$obj = new Aphph_Build;
		$obj->build_files();
	}
}

?>