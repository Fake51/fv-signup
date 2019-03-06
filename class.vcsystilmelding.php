<?php

class VCSys_Tilmelding
{
    function add_template($url,$template){
        $this->templates[$url] = $template;
    }
    
	function class_init()
	{
    	
    	$this->current_template_folder = null;
		add_action('parse_request', array(&$this,'url_handler'));
		$this->templates = array();
		
		
		$tilmelding_live = get_field("tilmelding_live", 'option');
		
		
		
		if ($tilmelding_live == "ja")
		{
        	$this->add_template(get_field('tilmelding_url', 'option'), "templates/".get_field('tilmelding_template_navn','option')."/");
        	$this->add_template(get_field('tilmelding_test_url', 'option'), "templates/".get_field('tilmelding_test_template_navn','option')."/");
            $this->include_all_ajax($this->templates);
		}
		
		if ($tilmelding_live == "nej")
		{
        	$this->add_template(get_field('tilmelding_test_url', 'option'), "templates/".get_field('tilmelding_test_template_navn','option')."/");
            $this->include_all_ajax($this->templates);
    	}
		
		/*
        if (strtotime("03-02-2018 20:00:00") - strtotime("now") < 0)
		{
            if (strtotime("09-03-2017 01:00:01") - strtotime("now") < 0)
        		{
            		// the server is an hour behind. So.. thats why 04
            		$this->add_template("/tilmelding/", "templates/fastaval-deltager-2018/");
        		}
        		else
        		{
            		$this->add_template("/tilmelding/", "templates/fastaval-deltager-2018/");
        		}
		} 
    		$this->add_template("/tilmelding-giga-mega-secret/", "templates/fastaval-deltager-2018/");
		*/
     	// $this->include_all_ajax($this->templates);
	}

	function url_handler($request) 
	{
        	
		foreach($this->templates as $url_prefix => $template_folder)
		{
			if (substr($_SERVER['REQUEST_URI'],0,strlen($url_prefix))==$url_prefix)
			{
				$this->current_template_folder = $template_folder;
				$this->include_template_functions();
				add_filter('body_class',array(&$this,'add_body_class_filter'));	
				add_filter('wp_title', array(&$this,'set_title_filter'));
				add_action('wp_enqueue_scripts', array(&$this,'add_stylesheet'));
				add_action('wp_enqueue_scripts', array(&$this,'add_scripts'));
				add_action('wp' , array(&$this,'handle_template'));
				add_action('wp_head' , array(&$this,'extra_headers'));
				
				// correcting language selector references to the current page
				$this->page_url = $url_prefix;
				add_filter('wpml_ls_language_url', array(&$this,'url_filter'));

				// this is to avoid a 404 error
				$request->query_vars = array();		
				return $request;
			}
		}
	}

	/**
	 * Function to correctly format the url for the language picker
	 */
	function url_filter($url){
		$split = preg_split("/\/?\?/", $url, 2); // matches "/?" or just "?"
		$split[1] = isset($split[1]) ? "?".$split[1] : ""; // add the qeustionmark back to the querry or add an empty string
		$url = $split[0].$this->page_url.$split[1]; //insert the current page url
		return $url;
	}
	
	function extra_headers(){
		?> <meta name="robots" content="noindex" /> <?php
	}

	function handle_template(){
		handle_wp_templates();
	}
	
    function get_template_folder()
    {
        return $this->current_template_folder;
    }
    
    function include_template_functions()
    {
        $template_folder = $this->get_template_folder();
        $file_functions_php = dirname(__FILE__)."/".$template_folder."functions.php";
        if (file_exists($file_functions_php)){
            require_once($file_functions_php);
        }
    }
    
    function include_all_ajax($templates)
    {
    	foreach($templates as $url_prefix => $template_folder)
    	{
            $file_ajax_php = dirname(__FILE__)."/".$template_folder."ajax.php";
            if (file_exists($file_ajax_php))
                require_once($file_ajax_php);
    	}
    }
	
	
	function add_stylesheet() 
	{
	    wp_enqueue_style( 'vcsys-lightbox', plugins_url('lightbox/css/lightbox.css', __FILE__) );
		wp_enqueue_style( 'prefix-style', plugins_url($this->get_template_folder().'tilmelding.css', __FILE__) );
		wp_enqueue_style( 'fusion-dynamic-css-css', plugins_url($this->get_template_folder().'copy_of_fusion_dynamic.min.css ', __FILE__) );
	    wp_enqueue_style( 'signup-responsive', plugins_url($this->get_template_folder().'responsive.css', __FILE__) );
	}

	function add_scripts(){
		// load lightbox
		wp_enqueue_script( 'vcsys-lightbox-js', plugins_url('lightbox/js/lightbox.min.js', __FILE__));
		
		if (is_callable('add_template_scripts')){
			add_template_scripts();
		}
	}
	
	function set_title_filter($content)
	{
    	if (function_exists("template_title"))
    	    return template_title();
		return "";
	}
	
	function add_body_class_filter($classes = '') {
		$classes[] = 'tilmelding';
		$classes[] = 'wide';
		return $classes;
	}
}
