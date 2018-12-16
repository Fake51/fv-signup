<?php
//  ini_set('display_errors', 1);
//  ini_set('display_startup_errors', 1);
//  error_reporting(E_ALL);

/*
    Plugin Name: VCSys.Tilmelding
    Plugin URI: http://www.kmsconsult.dk/
    Description: Tilmeldingsplugin. 
    Version: 0.1.0.0
    Author: Kristoffer Mads Soerensen
    Author URI: http://www.kmsconsult.dk
*/


include("inc.render.php"); 
include("inc.language.php"); 
include("class.signuppage.php"); 
include("class.vcsystilmelding.php"); 
include("class.signuphandler.php");
include("class.infosysconnect.php");


add_action('init', function (){
//    acf_add_options_page("Tilmelding");
    
 	// add parent
	$parent = acf_add_options_page(array(
		'page_title' 	=> 'Tilmelding',
		'menu_title' 	=> 'Tilmelding',
		'redirect' 		=> false
	));
	
	
	// add sub page
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Translations',
		'menu_title' 	=> 'Translations',
		'parent_slug' 	=> $parent['menu_slug'],
	));    
});


global $vcsys_tilmelding;
$vcsys_tilmelding = new VCSys_Tilmelding();
$vcsys_tilmelding->class_init();
function VCSYS(){
    global $vcsys_tilmelding;
    return $vcsys_tilmelding;
}

global $signuphandler;
$signuphandler = new SignupHandler();
if (isset($_GET['reset']))
{
    unset($_SESSION['customer']);
}

$infosys_url = get_field('infosys_url','option');
function get_infosys_url(){
    global $infosys_url;
    return $infosys_url;
}




function gf($name)
{
	return tm_getForm($name);
	//return preg_replace("\"","&quot;",stripslashes(tm_getForm($name)));
}


function tm_is_path($what, $compare=null){
    
    if ($compare===null)
        $compare = $_SERVER['REQUEST_URI'];
    
	$url = strtolower($compare);
	if (strpos($url, "?"))
	{
    	$url = substr($url, 0, strpos($url, "?"));
	}
	
	$parts = explode("/",$url);
	if ($parts[count($parts)-1]  == $what)
		return true;
	return false;
}
function getError($str,$silent=false)
{
}
function tm_getForm($name){
    if (!isset($_SESSION['customer']))	return "";
    
    if (isset($_SESSION['customer'][$name]))
        return $_SESSION['customer'][$name];
    else
//        return 0; // changed from "" 14th january 2018
        return ""; // changed back to "" 25th of janary 2018
}

function VCTilm()
{
    global $vcsys_tilmelding;
    return $vcsys_tilmelding;
}

function SH()
{
    global $signuphandler;
    return $signuphandler;
}
function tilm_form_prefields(){SH()->output_form_prefields();}
function tilm_form_postfields(){SH()->output_form_postfields();}
function tilm_form_prev_fields(){SH()->output_form_prev_fields();}

function handle_wp_templates()
{
    global $signuphandler;
    $signuphandler->class_init();

    if (!file_exists(dirname(__FILE__)."/".VCTilm()->get_template_folder()))
    {
        get_header(); // wp
        echo "Template '".VCTilm()->get_template_folder()."' does not exist";
        get_footer(); // wp
    }
    

    SH()->set_cookie_header();
    SH()->handle_signuppage_post();
    
    if ( tm_is_path("next") )
    {
        
        $current = SH()->get_step_obj( $_POST['posting-step'] );
        $current->validate();
        
        $next = SH()->get_next_step_obj( $_POST['posting-step'] );
        header( "HTTP/1.1 303 See Other" );
        
        if (isset($_GET['lang']))
            header( "Location: ./".$next->getSlug()."?lang=".$_GET['lang'] );
        else
            header( "Location: ./".$next->getSlug() );
        die();
    }
    
    else if ( tm_is_path("prev") )
    {
        $prev = SH()->get_previous_step_obj( $_POST['posting-step'] );
        header( "HTTP/1.1 303 See Other" );
        if (isset($_GET['lang']))
            header( "Location: ./".$prev->getSlug()."?lang=".$_GET['lang'] );
        else
            header( "Location: ./".$prev->getSlug() );

        die();
    }
    
    get_header(); // wp
        
        render_navigation( SH()->signuppage_to_show() );

        SH()->show_signuppage();
        if (!SH()->is_signuppage_shown())
        {
            echo "Page not set";
        }
    
    get_footer(); // wp
    die();
}

register_activation_hook(__FILE__, 'vcsysActivationHook');
register_deactivation_hook(__FILE__, 'vcsysDeactivationHook');
register_uninstall_hook(__FILE__, 'vcsysUninstallHook');

function vcsysActivationHook() {
}

function vcsysDeactivationHook() {
}

function vcsysUninstallHook() {
}
