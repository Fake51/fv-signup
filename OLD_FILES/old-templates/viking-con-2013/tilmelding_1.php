<?php 

	
	if ($_SESSION["registered"]=="1"){
	     $_SESSION["registered"]="0";
	     unset($_SESSION['customer']);
	}
     
     
     $errorHandler = array(
          'kontakt_1' => array("nonempty"),
          'kontakt_2' => array("nonempty"),
          'kontakt_3' => array("nonempty"),
          'kontakt_4' => array("nonempty"),
          'kontakt_6' => array("nonempty","digits"),
          'kontakt_9' => array("nonempty")
       
     );
     function errorHandler($name){
          global $errorHandler;
          $checks = $errorHandler[$name];
          for ($i=0;$i<count($checks);$i++){
               if ($checks=="nonempty"){
                    if (tm_getForm($name)==""){
                         // error, and callback
                    }
               }
               else if ($checks=="nonzero"){
                    if (tm_getForm($name)=="0"){
                         // error, and callback
                    }
               }
               else if ($checks=="digits"){
                    if (preg_replace("/[^0-9]/","",tm_getForm($name))!=tm_getForm($name)){
                         // error, and callback
                    }
               }
               else if ($checks=="email"){
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                         // error, and callback
                    }
               }
          }
     }

     function getError($str,$silent=false){
     }
     
?>
    <script>
         function validate_required(field,alerttxt)
         {
         	var value = jQuery('#'+field).val();
              if ((value==null)||(value==""))
              {
              	alert(alerttxt);
              	return false;
              }
              else 
              	return true;
         }
         
	     function validate_form(thisform)
	     {
            var nonempty = new Array('kontakt_1','kontakt_3','kontakt_4','kontakt_6','kontakt_7','kontakt_9');
            var nonempty_names = new Array("Fornavn","Efternavn","Adresse","Postnummer","By","E-mail");
            for (var i=0;i<nonempty.length;i++)
            {
                var field = nonempty[i];
                var text = "Udfyld venligst '"+nonempty_names[i]+"' feltet";
                if (!validate_required(field,text))
                {   
                    jQuery('#'+field).focus();
                    return false;
                }
            }
            return true;
	     }

     
        jQuery(document).ready(function(){
            jQuery(window).on('scroll',function() 
            {
                var scrolltop = jQuery(this).scrollTop();
                if(scrolltop >= 315) 
                {
                    jQuery('#fixedImage').removeClass("stay");
                    jQuery('#fixedImage').addClass("follow");
                }
                else if(scrolltop < 315) {
                    jQuery('#fixedImage').removeClass("follow");
                    jQuery('#fixedImage').addClass("stay");
                }
            });            
        })
    </script>


	<style>
	    form {position: relative;width:960px;}
    	p label, ul li{font-size:14px;}
    	h2{font-size:16px;padding-top:15px;}
    	p input{height:25px !important;font-size:12px;}
    	p.field1_1 input{width:408px;}
    	p.field1_2 input{width:200px;}
    	p.field1_4 input{width:100px;}
    	p.field3_4 input{width:300px;}
    	
    	#tilmelding-info{
    	    width:560px;
    	}
    	#tilmelding-image{
    	    position:absolute;
    	    right:0px;
    	    top:150px;
    	    width:400px;
        }
    	#tilmelding-image
    	{
        	width:400px;
    	}
    	
    	/*
    	#fixedImage.follow{
        	position:fixed;
    	}
    	#fixedImage.stay{
        	position:relative;
    	}*/
	</style>
	<form method="post" action="./step-2" onSubmit='return validate_form(this);'>
    	<input type='hidden' name='expect_checkbox' value='day_1,day_2,day_3,food_1,food_2,blok_1,blok_2,blok_3,blok_4,email_info'>
	
        <h1>Tilmeld dig Con2 (1/2)</h1>
        <p style='text-align:right;font-style:italic'>Forhåndstilmeldingen slutter d. <?php echo strftime("%e. %B %Y",$stopTime);?>, eller når vi når 65 deltagere</p>
		<?php 
    		function gf($name){
    			return tm_getForm($name);
    			//return preg_replace("\"","&quot;",stripslashes(tm_getForm($name)));
    		}
		?>
		
		<div id='tilmelding-image'>
    		<img src='/images/con2-2014.png' style='float:right;width:400px;' id='fixedImage'>
        </div>
		
		<div id='tilmelding-info'>
    		<h2>Kontaktoplysninger</h2>
    		<div>
    			<p class="field1_2"><label for="kontakt_1">Fornavn</label> <input type="text" id='kontakt_1' name="kontakt_1" value="<?php  echo gf('kontakt_1')?>" /><?php getError("kontakt_1");?></p>
    			<p class="field1_2"><label for="kontakt_3">Efternavn</label> <input type="text" id='kontakt_3' name="kontakt_3" value="<?php  echo gf("kontakt_3")?>" /><?php getError("kontakt_3");?></p>
    			<p class="field1_1"><label for="kontakt_4">Adresse</label> <input type="text" id='kontakt_4' name="kontakt_4" value="<?php  echo (gf("kontakt_4"))?>" /><?php getError("kontakt_4");?></p>
    			<p class="field1_1"><label for="kontakt_5" class='hide'>Adresse</label> <input type="text" id='kontakt_5' name="kontakt_5" value="<?php  echo (gf("kontakt_5"))?>" /><?php getError("kontakt_5");?></p>
    			<p class="field1_4"><label for="kontakt_6">Postnr.</label> <input type="text" id='kontakt_6' name="kontakt_6" value="<?php  echo (gf("kontakt_6"))?>" /><?php getError("kontakt_6");?></p>
    			<p class="field3_4"><label for="kontakt_7">By</label> <input type="text" id='kontakt_7' name="kontakt_7" value="<?php  echo (gf("kontakt_7"))?>" /><?php getError("kontakt_7");?></p>
    			<p class="field1_1"><label for="kontakt_8">Telefon</label> <input type="text" id='kontakt_8' name="kontakt_8" value="<?php  echo (gf("kontakt_8"))?>" /><?php getError("kontakt_8");?></p>
    			<p class="field1_1"><label for="kontakt_9">E-mail</label> <input type="text" id='kontakt_9' name="kontakt_9" value="<?php  echo (gf("kontakt_9"))?>" /><?php getError("kontakt_9");?></p>
    		</div>
    					
            <h2>Jeg vil gerne være på Con2:</h2>
            <ul style='list-style:none;margin:0;padding:0;'>
                <li><input type='checkbox' value='1' name='day_1' id='day_1' <?php echo tm_getForm("day_1")=="1"?" checked":"";?>> Fredag (35 kr)</li>
                <li><input type='checkbox' value='1' name='day_2' id='day_2' <?php echo tm_getForm("day_2")=="1"?" checked":"";?>> Lørdag (60 kr)</li>
                <li><input type='checkbox' value='1' name='day_3' id='day_3' <?php echo tm_getForm("day_3")=="1"?" checked":"";?>> Søndag (35 kr)</li>
            </ul>
            
            <h2>Jeg vil gerne spise:</h2>
            <ul style='list-style:none;margin:0;padding:0;'>
                <li><input type='checkbox' value='1' name='food_1' id='food_1' <?php echo tm_getForm("food_1")=="1"?" checked":"";?>> Fredag (30 kr) Risret med porrer og muskatnød, kylling til de kødspisende og friskbagt brød</li>
                <li><input type='checkbox' value='1' name='food_2' id='food_2' <?php echo tm_getForm("food_2")=="1"?" checked":"";?>> Lørdag (35 kr) Pasta Carbonara og Pasta med Champignon og friskbagt brød</li>
            </ul>
            
            <h2>Hvilke dage vil du spille rollespil:</h2>
            <ul style='list-style:none;margin:0;padding:0;'>
                <li><input type='checkbox' value='1' name='blok_1' id='blok_1' <?php echo tm_getForm("blok_1")=="1"?" checked":"";?>> Blok 1 (fredag aften)</li>
                <li><input type='checkbox' value='1' name='blok_2' id='blok_2' <?php echo tm_getForm("blok_2")=="1"?" checked":"";?>> Blok 2 (lørdag dag)</li>
                <li><input type='checkbox' value='1' name='blok_3' id='blok_3' <?php echo tm_getForm("blok_3")=="1"?" checked":"";?>> Blok 3 (lørdag aften)</li>
                <li><input type='checkbox' value='1' name='blok_4' id='blok_4' <?php echo tm_getForm("blok_4")=="1"?" checked":"";?>> Blok 4 (søndag dag)</li>
            </ul>
            
            <h2>Antal aktiviteter</h2>
            <p>Hvor mange rollespil/figurspil vil du gerne spille i løbet af weekenden? </p>
            <select name='aktiviter_num'>
                <option value='0'>0
                <option value='1'>1
                <option value='2'>2
                <option value='3'>3
                <option value='4'>4
            </select>
            
            <h2>Aktivitet: Rollespil</h2>
            <ul style='list-style:none;margin:0;padding:0;'>
            <?php
                $args = array(
                    'aktivitetstype' => 'rollespil',
                    'post_type' => 'vc_turneringer2013',
                    'post_status' => 'publish',
                    'posts_per_page' => -1
                );
                $posts = get_posts($args); 
                foreach($posts as $post){
                    ?>
                    <li>
                        <select style='margin-right:20px;' name='prio_<?php echo $post->ID?>' id='prio_<?php echo $post->ID?>'>
                            <option value=''> Ingen prioritet
                            <?
                                for($i=1;$i<=10;$i++){
                                    $selected = tm_getForm("prio_".$post->ID)==$i?" selected":"";
                                    echo "<option value='".$i."'".$selected.">".$i;
                                }
                            ?>
                        </select>
                        <?php
                        echo $post->post_title;
                        ?>   
                    </li> 
                    <?php
                }
            ?>
            </ul>
            <h2>Aktivitet: Figurspil</h2>
            <ul style='list-style:none;margin:0;padding:0;'>
            <?php
                $args = array(
                    'aktivitetstype' => 'figurspil',
                    'post_type' => 'vc_turneringer2013',
                    'post_status' => 'publish',
                    'posts_per_page' => -1
                );
                $posts = get_posts($args); 
                foreach($posts as $post){
                    ?>
                    <li>
                            <select style='margin-right:20px;' name='prio_<?php echo $post->ID?>' id='prio_<?php echo $post->ID?>'>
                            <option value=''> Ingen prioritet
                            <?
                                for($i=1;$i<=10;$i++){
                                    $selected = tm_getForm("prio_".$post->ID)==$i?" selected":"";
                                    echo "<option value='".$i."'".$selected.">".$i;
                                }
                            ?>
                        </select>
                        <?php
                        echo $post->post_title;
                        ?>   
                    </li> 
                    <?php
                }
            ?>
            </ul>
            
            
            <h2>Sjov under maden</h2>
            <p>Vil du være med til sjov og halløj lørdag aften under maden?</p>
            <p><input type='checkbox' value='1' name='fun_food' id='fun_food' <?php echo tm_getForm("fun_food")=="1"?" checked":"";?>> Ja tak!</p>
            
            
            
            <img src='/images/con2.jpg' style='float:right;width:400px;position:absolute;right:0px;'>
            
            <h2>Vigtig information om dig til os</h2>
            <p>Har du allergier eller andet som arrangørerne skal være opmærksomme på?<br/> 
    Eller lyst til at skrive et smukt, lille udpluk fra din fantastiske ConTrafakriske <br/>rejse til Con2, som den berømte ConTrafaktiske personlighed du er?</p>
            <textarea id='vigtig_info' name='vigtig_info' style='width:400px;height:150px;'><?php  echo gf('vigtig_info')?></textarea>
            
            <h2>Meld dig som GM!</h2>
            <p><strong>CON2 har brug for dig! Du er vores pokemon!</strong> (Hvilke scenarier vil du evt. køre?)</p>
            <textarea id='gm_tjans' name='gm_tjans' style='width:400px;height:150px;'><?php  echo gf('gm_tjans')?></textarea>
            
            <br/>
            <br/>
            <p><input type='checkbox' value='1' name='email_info'> Jeg vil gerne holdes orienteret om fremtidige Con2-arrangementer</p>
            
            
            <p><input class="button next" type="submit" name='action2' value="Tilmeld mig!" /></p>
        </div>
	</form><?php

