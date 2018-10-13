<?php

function template_title()
{
    return "Den lille deltagertilmelding - Fastaval";
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

function myfunc_get_pages()
{
    global $myfunc_pages;
    
    include("tilmelding_1.php");
    myfunc_add_page(new DeltagerTilmeldingIntroPage1());
    include("tilmelding_2.php");
    myfunc_add_page(new DeltagerTilmeldingContactPage2());    
    include("tilmelding_3.php");
    myfunc_add_page(new DeltagerTilmeldingArrangoerPage2());
    include("tilmelding_4_alea.php");
    myfunc_add_page(new DeltagerTilmeldingAleaPage4());
    include("tilmelding_5_praktisk.php");
    myfunc_add_page(new DeltagerTilmeldingPratiskPage5());
    include("tilmelding_6_gds.php");
    myfunc_add_page(new DeltagerTilmeldingGDSPage8());
    include("tilmelding_7_moreinfo.php");
    myfunc_add_page(new DeltagerTilmeldingMoreInfoPage10());
    include("tilmelding_8_godkend.php");
    myfunc_add_page(new DeltagerTilmeldingPratiskGodkend11());
    include("tilmelding_9_tilmeldt.php");
    myfunc_add_page(new DeltagerTilmeldingTilmeldt12());
    
    return $myfunc_pages;
}
    
function get_current_step(){
    $page_num_to_show = 1;
    for ($i=1;$i<100;$i++)
    {
        if (tm_is_path("step-".$i))
            $page_num_to_show = $i;
    }
    return $page_num_to_show * 1;
}

function get_next_step_name(){
    $lang_param = "";
    $lang = get_language();
    if ($lang!="da")
        $lang_param = "?lang=".$lang;
    return "./step-".(get_current_step()+1).$lang_param;
}

function get_previous_step_name(){
    $lang_param = "";
    $lang = get_language();
    if ($lang!="da")
        $lang_param = "?lang=".$lang;
    return "./step-".(get_current_step()-1).$lang_param;
}


