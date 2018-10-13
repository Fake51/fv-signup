<?php
    
setlocale(LC_TIME, "da_DK");
$translations_set = null;
register_shutdown_function('vcsys_shutdown_translations_check');

function cl_acf_set_language() 
{
    return acf_get_setting('default_language');
}
function get_global_option($name) 
{
	add_filter('acf/settings/current_language', 'cl_acf_set_language', 100);
	$option = get_field($name, 'option');
	remove_filter('acf/settings/current_language', 'cl_acf_set_language', 100);
	return $option;
}

function add_missing_translation($str){
    $filename = __DIR__."/missing.translations.xml";
    $translations = "";
    if (!$str)return;
    if (file_exists($filename))
        $translations = file_get_contents($filename);
    
    if (strpos($translations, $str) !== false)return;
    
    $translations .= "
<translation>
<da><![CDATA[".$str."]]></da>
<en><![CDATA[".$str."]]></en>
</translation>
    ";
    file_put_contents($filename, $translations);
}

function vcsys_load_translations() {
    $output = array();

    $translations_file = __DIR__ . '/translations.xml';

    if (!is_file($translations_file)) {
        return $output;
    }

    $dom = new DomDocument("1.0", "utf-8");
    $dom->loadXML(file_get_contents($translations_file));

    $xpath = new DOMXPath($dom);
    $list = $xpath->query('//translation');

    foreach ($list as $item) {
        if (!$item->hasChildNodes()) {
            continue;
        }

        $items = array();

        foreach ($item->childNodes as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            $items[$child->nodeName] = $child->textContent;
        }

        if ($item->hasAttribute('last-miss')) {
            $items['last-miss'] = $item->getAttribute('last-miss');
        }

        if ($item->hasAttribute('last-hit')) {
            $items['last-hit'] = $item->getAttribute('last-hit');
        }

        $lang = 'da';

        if (isset($items[$lang])) {
            $output[md5($items[$lang])] = $items;
        }
    }

    return $output;
}

function vcsys_save_translations($translations) {
    $dom = new DomDocument("1.0", "utf-8");

    $root = $dom->createElement('translations');
    $dom->appendChild($root);

    $languages = array('da', 'en');

    foreach ($translations as $translation) 
    {
        $item = $dom->createElement('translation');

        foreach ($languages as $language) {
            if (isset($translation[$language])) {
                $point = $dom->createElement($language);
                $point->appendChild($dom->createCDATASection($translation[$language]));

                $item->appendChild($dom->createTextNode("\n  "));
                $item->appendChild($point);
            }
        }

        if (isset($translation['last-hit'])) {
            $item->setAttribute('last-hit', $translation['last-hit']);
        }

        if (isset($translation['last-miss'])) {
            $item->setAttribute('last-miss', $translation['last-miss']);
        }

        $item->appendChild($dom->createTextNode("\n"));

        $root->appendChild($item);
        $root->appendChild($dom->createTextNode("\n"));
    }

//    file_put_contents(__DIR__ . '/translations.xml', $dom->saveXML());
}

function vcsys_shutdown_translations_check() {

    if (!empty($GLOBALS['translations_set'])) {
        vcsys_save_translations($GLOBALS['translations_set']);
    }
}

function __tm($key, $str_id=null)
{
    $org_key = $key;
    $lang = get_language();
    $key = $key."_".$lang;
    $id = 'field_5688e693158sadc1_'.md5($key);
    $name = 'fv_translate_'.md5($id);
    
    $translate = get_global_option($name);
    if ($translate=="")
    {
        add_missing_translation($key);
        // error... open up the translation error file and add me
        return $org_key;
    }
    else
        $str = $translate;
    
    if (is_user_logged_in()) {
        $variable = get_field('show_translations_on_signuppage', 'user_'.get_current_user_id());
        foreach($variable as $v){
            if ($v==1){
                $str = "[".$org_key."] ".$str;
            }
        }
    }
    
    return $str;
}

function __etm($str, $str_id=null){echo __tm($str);}


function get_language()
{
    $lang = "da";
    
    if (isset($_GET['lang']))
    {
        if ($_GET['lang']=="en")
            $lang = "en";
    }
    return $lang;
}


