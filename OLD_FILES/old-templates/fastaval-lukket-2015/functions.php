<?php

function template_title()
{
    return "Tilmeldingen er lukket - Fastaval";
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


