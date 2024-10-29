<?php
class Aphph_Admin_Block
{	
    public function __construct()
    {
		$this->init();
	}
	
	public function init() {
		add_action( 'load-post-edit.php', array($this, 'add_custom_block') );
		add_action( 'load-post-new.php', array($this, 'add_custom_block') );
		add_action( 'load-post.php', array($this, 'add_custom_block') );
		
	}
	
	public function add_custom_block() {
		add_action( 'enqueue_block_editor_assets', array($this, 'register_script' ));
	}
	
	public function register_script() {
		/* wp_enqueue_script(
		  'aphph-jquery',
		  APHPH_PLUGIN_URL . '/js/jquery-3.4.1.js'
	   ); */
		wp_enqueue_script(
		  'gutenberg-aphph-block',
		  APHPH_PLUGIN_URL . '/js/block.js?r='.time(),
		  array( 'wp-blocks', 'wp-editor','wp-element' ),
		  false, true
	   );
	}
}

?>