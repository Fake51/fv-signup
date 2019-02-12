<?php
function wear_v1_callablesorter($a, $b){
    $language = "da";
    $navn_a = $a["title_".$language];
    $navn_b = $b["title_".$language];
    return strcmp($navn_a, $navn_b);
}
    

function render_navigation($the_page){
    global $myfunc_pages;
    $hide=false;
    
    $language="";
    if (isset($_GET['lang']))
        $language = "?lang=".$_GET['lang'];
        
    ?>
    <div class='navigation'>
        <ul>
            <?php
                foreach($myfunc_pages as $page)
                {
                    if ($page->canShow())
                    {
                        if ($page==$the_page)
                        {
                            $hide = true;
                        }
                        
                        if ($hide){
                            ?><li class='hidden<?=($page==$the_page?" current":"")?>'><?=$page->getTitle();?></li><?php
                        }else{
                            ?><li><a href='<?=$page->getSlug();?><?=$language;?>'><?=$page->getTitle();?></a></li><?php
                        }
                    }
                }
            ?>
        </ul>
    </div>
    <?php
}


function template_title()
{
    return "Deltagertilmelding - Fastaval";
}


function render_next_button($text){
    ?>
    <p><input class="button next" type="submit" name='action2' value="<?php __etm($text);?>" /></p>
    <?php
}

function render_previous_button($text){
    ?>
    <p><input class="button prev" type="submit" name='action2' value="<?php __etm($text);?>" /></p>
    <?php
}


$myfunc_pages = array();
function myfunc_add_page($page){
    global $myfunc_pages;
    $myfunc_pages[] = $page;
}

function myfunc_get_pages($arr)
{
    global $myfunc_pages;
    $myfunc_pages = $arr;
    
    
    if (get_field('tilmelding_tilmelding_show_1', 'option') != "1"){
        require_once("tilmelding_1.php");
        $myfunc_pages[] = new DeltagerTilmeldingIntroPage1();
    }


    if (get_field('tilmelding_tilmelding_show_2', 'option') != "1"){
        require_once("tilmelding_2.php");
        $myfunc_pages[] = new DeltagerTilmeldingContactPage2();    
    }

    if (get_field('tilmelding_tilmelding_show_3', 'option') != "1"){
        require_once("tilmelding_3.php");
        $myfunc_pages[] = new DeltagerTilmeldingArrangoerPage2();
    }

    if (get_field('tilmelding_tilmelding_show_4', 'option') != "1"){
        require_once("tilmelding_3_fvjunior.php");
        $myfunc_pages[] = new FastavalJuniorPage();
    }

    if (get_field('tilmelding_tilmelding_show_5', 'option') != "1"){
        require_once("tilmelding_3_pakke.php");
        $myfunc_pages[] = new DeltagerTilmeldingPakkePage();
    }

    if (get_field('tilmelding_tilmelding_show_6', 'option') != "1"){
        require_once("tilmelding_5_praktisk.php");
        $myfunc_pages[] = new DeltagerTilmeldingPratiskPage5();
    }

    if (get_field('tilmelding_tilmelding_show_7', 'option') != "1"){
        require_once("tilmelding_6_mad.php");
        $myfunc_pages[] = new DeltagerTilmeldingMadPage6();
    }

    if (get_field('tilmelding_tilmelding_show_8', 'option') != "1"){
        require_once("tilmelding_7_aktiviteter.php");
        $myfunc_pages[] = new DeltagerTilmeldingAktiviteterPage7();
    }

    if (get_field('tilmelding_tilmelding_show_9', 'option') != "1"){
        require_once("tilmelding_8_gds.php");
        $myfunc_pages[] = new DeltagerTilmeldingGDSPage8();
    }

    if (get_field('tilmelding_tilmelding_show_10', 'option') != "1"){
        require_once("tilmelding_9_wear.php");
        $myfunc_pages[] = new DeltagerTilmeldingWearPage9();
    }

    if (get_field('tilmelding_tilmelding_show_11', 'option') != "1"){
        require_once("tilmelding_10_moreinfo.php");
        $myfunc_pages[] = new DeltagerTilmeldingMoreInfoPage10();
    }

    require_once("tilmelding_11_godkend.php");
    $myfunc_pages[] = new DeltagerTilmeldingPratiskGodkend11();

    require_once("tilmelding_12_tilmeldt.php");
    $myfunc_pages[] = new DeltagerTilmeldingTilmeldt12();

//  */  
    return $myfunc_pages;
}

add_filter('tilmelding_pages', 'myfunc_get_pages');
    

function get_next_step_name(){
    
    $lang_param = "";
    $lang = get_language();
    if ($lang!="da")
        $lang_param = "?lang=".$lang;
    return "./".SH()->get_next_step_name().$lang_param;
}

function get_previous_step_name(){
    $lang_param = "";
    $lang = get_language();
    if ($lang!="da")
        $lang_param = "?lang=".$lang;
//    return "./step-".(get_current_step()-1).$lang_param;

    return "./".SH()->get_previous_step_name().$lang_param;

}

function add_template_scripts($folder){
    // add scripts for all pages here
    
    // add page specific scripts
    $page = SH()->signuppage_to_show();
    if (is_callable(array($page, 'get_scripts')))
    {
        $scripts = $page->get_scripts();
        foreach($scripts as $script){
            wp_enqueue_script( $script['name'], plugins_url("$folder/scripts/$script[file]", __FILE__));
        }
    }
}


