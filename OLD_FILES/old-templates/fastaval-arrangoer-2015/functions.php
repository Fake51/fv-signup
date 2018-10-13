<?php
    
    function template_title()
    {
        return "Tilmelding - ";
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