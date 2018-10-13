<?php 

	
    if ($_SESSION["registered"]=="1")
    {
         $_SESSION["registered"]="0";
         unset($_SESSION['customer']);
    }
    
    $errorHandler = array(
        'firstname' => array("nonempty"),
        'lastname' => array("nonempty"),
        'address1' => array("nonempty"),
        'address2' => array("nonempty"),
        'zipcode' => array("nonempty"),
        'city' => array("nonempty"),
        'email' => array("nonempty"),
    );

?>

<?php
    $my_errorHandler = array(
        'firstname' => array(
            'rules'=>"nonempty"
        ),
        'lastname' => array(
            'rules'=>"nonempty"
        ),
        'address1' => array(
            'rules'=>"nonempty"
        ),
        'zipcode' => array(
            'rules'=>"nonempty"
        ),
        'city' => array(
            'rules'=>"nonempty"
        ),
        'country' => array(
            'rules'=>"nonempty"
        ),
        'mobile' => array(
        ),
        'birthday-day' => array(
            'rules'=>array(
                array('nonempty'),
                array('digits'),
                array('value-between',1,31)
            )
        ),
        
        'birthday-month' => array(
            'rules'=>array(
                array('nonempty'),
                array('digits'),
                array('value-between',1,12)
            )
        ),
        'birthday-year' => array(
            'rules'=>array(
                array('nonempty'),
                array('digits'),
                array('value-between',1900,2014)
            )
        ),
        
        'email' => array(
            'rules'=>"nonempty,email"
        ),
        'email_repeat' => array(
            'rules'=>array(
                array("must-equal-field","email"),
            )
        ),
    );

 
?>
    <script>
        function validate_email(email) { 
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        } 
        
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
    	     jQuery('input.has-error').removeClass('has-error');
    	     <?php
        	     renderErrorHandlerAsJavascript($my_errorHandler);
    	     ?>
            return true;
	     }
    </script>
    
    
    
    
	<form method="post" action="./step-3" onSubmit='return validate_form(this);'>
    	
        <h1><?php __etm('Velkommen til fastaval-tilmeldingen')?></h1>
        
		<div id='tilmelding-info'>
    		<h2>Kontaktoplysninger</h2>
    		<div class='fields fields_1'>
    			
    			
    			<?php
        			/*
        			<p class="field1_1"><label for="firstname">Fornavn:</label> <input class='tilmelding-input' type="text" id='firstname' name="firstname" value="<?php  echo gf('firstname')?>" /><?php getError("firstname");?></p>
                    <p class="field1_2"><label for="lastname">Efternavn:</label> <input class='tilmelding-input' type="text" id='lastname' name="lastname" value="<?php  echo gf("lastname")?>" /><?php getError("lastname");?></p>
        			<p class="field1_3"><label for="address1">Adresse:</label> <input class='tilmelding-input' type="text" id='address1' name="address1" value="<?php  echo (gf("address1"))?>" /><?php getError("address1");?></p>
        			<p class="field1_4"><label for="address2" class='hide'>&nbsp;</label> <input class='tilmelding-input' type="text" id='address2' name="address2" value="<?php  echo (gf("address2"))?>" /><?php getError("address2");?></p>
                    <p class="field1_5"><label for="zipcode">Postnr.:</label> <input class='tilmelding-input' type="text" id='zipcode' name="zipcode" value="<?php  echo (gf("zipcode"))?>" /><?php getError("zipcode");?></p>
                    <p class="field3_6"><label for="city">By:</label> <input class='tilmelding-input' type="text" id='city' name="city" value="<?php  echo (gf("city"))?>" /><?php getError("city");?></p>
                    <p class="field3_7"><label for="country">Land:</label> <input class='tilmelding-input' type="text" id='country' name="country" value="<?php  echo (gf("country"))?>" /><?php getError("country");?></p>
                    <p class="field1_8"><label for="mobile">Mobil:</label> <input class='tilmelding-input' type="text" id='mobile' name="mobile" value="<?php  echo (gf("mobile"))?>" /><?php getError("mobile");?></p>
        			*/
            			
        			renderFieldByType(array(
            			'id'=>'field1_1',
            			'input-type'=>'text',
            			'input-name'=>'firstname',
            			'text'=>'Fornavn:',
        			));
        			renderFieldByType(array(
            			'id'=>'field1_2',
            			'input-type'=>'text',
            			'input-name'=>'lastname',
            			'text'=>'Efternavn:',
        			));
        			renderFieldByType(array(
            			'id'=>'field1_3',
            			'input-type'=>'text',
            			'input-name'=>'address1',
            			'text'=>'Adresse:',
        			));
        			renderFieldByType(array(
            			'id'=>'field1_4',
            			'input-type'=>'text',
            			'input-name'=>'address2',
            			'text'=>'&nbsp;',
        			));
        			renderFieldByType(array(
            			'id'=>'field1_5',
            			'input-type'=>'text',
            			'input-name'=>'zipcode',
            			'text'=>'Postnr.:',
        			));
        			renderFieldByType(array(
            			'id'=>'field1_6',
            			'input-type'=>'text',
            			'input-name'=>'city',
            			'text'=>'By:',
        			));
        			
        			renderFieldByType(array(
            			'id'=>'field1_7',
            			'input-type'=>'select',
            			'input-name'=>'country',
            			'text'=>'Land:',
            			'value' => array(
                            "Danmark"=>"Danmark",
                            "Finland"=>"Finland",
                            "Norge"=>"Norge",
                            "Storbritannien"=>"Storbritannien",
                            "Sverige"=>"Sverige",
                            "Tyskland"=>"Tyskland",
                            "USA"=>"USA",
                            "Albanien"=>"Albanien",
                            "Belgien"=>"Belgien",
                            "Bosnien-Hercegovina"=>"Bosnien-Hercegovina",
                            "Bulgarien"=>"Bulgarien",
                            "Canada"=>"Canada",
                            "Cypern"=>"Cypern",
                            "Estland"=>"Estland",
                            "Frankrig"=>"Frankrig",
                            "Færøerne"=>"Færøerne",
                            "Grækenland"=>"Grækenland",
                            "Grønland"=>"Grønland",
                            "Holland"=>"Holland",
                            "Irland"=>"Irland",
                            "Island"=>"Island",
                            "Italien"=>"Italien",
                            "Kroatien"=>"Kroatien",
                            "Letland"=>"Letland",
                            "Luxembourg"=>"Luxembourg",
                            "Malta"=>"Malta",
                            "Montenegro"=>"Montenegro",
                            "Polen"=>"Polen",
                            "Portugal"=>"Portugal",
                            "Republikken Makedonien"=>"Republikken Makedonien",
                            "Rumænien"=>"Rumænien",
                            "Schweiz"=>"Schweiz",
                            "Serbien"=>"Serbien",
                            "Slovakiet"=>"Slovakiet",
                            "Slovenien"=>"Slovenien",
                            "Spanien"=>"Spanien",
                            "Tjekkiet"=>"Tjekkiet",
                            "Tyrkiet"=>"Tyrkiet",
                            "Ungarn"=>"Ungarn",
                            "Østrig"=>"Østrig"
                                    ),
        			));

        			
        			renderFieldByType(array(
            			'id'=>'field1_8',
            			'input-type'=>'text',
            			'input-name'=>'mobile',
            			'text'=>'Mobil:',
            			'caption'=>'Telefonnummeret må kun indeholde tal. Udenlandske numre kan ikke indtastes',
            			
        			));
        			renderFieldByType(array(
            			'id'=>'field1_17',
            			'input-type'=>'checkbox',
            			'input-name'=>'bringing_mobile',
            			'text'=>'Jeg medbringer mobil på Fastaval og må gerne kontaktes via opkald/SMS (fx. med spil-info og nyheder)'
        			));

        			renderFieldByType(array(
            			'id'=>'field1_18',
            			'input-type'=>'select',
            			'input-name'=>'sex',
            			'text'=>'Køn',
            			'value' => array(
                        			'Mand'=>'Mand',
                        			'Kvinde'=>'Kvinde'
                            ),
        			));

        			renderFieldByType(array(
            			'id'=>'field1_19',
            			'input-type'=>'birthday',
            			'input-name'=>'birthday',
            			'text'=>'Fødselsdato:',
            			'caption'=>'dag, måned, år - f.eks. 29,7,1980',
        			));
        			
                ?>
    			<p class="field1_9"><label for="email">E-mail:</label> <input class='tilmelding-input'  type="text" id='email' name="email" value="<?php  echo (gf("email"))?>" /><?php getError("email");?></p>
    			<p class="field1_10"><label for="email_repeat">E-mail (gentag):</label> <input class='tilmelding-input' type="text" id='email_repeat' name="email_repeat" value="<?php  echo (gf("email_repeat"))?>" /><?php getError("email_repeat");?></p>
                <?php

        			renderFieldByType(array(
            			'id'=>'field1_15',
            			'input-type'=>'checkbox',
            			'input-name'=>'with_club',
            			'text'=>'Jeg er på Fastaval med min klub/ungdomsskole',
        			));
        			renderFieldByType(array(
            			'id'=>'field1_16',
            			'input-type'=>'text',
            			'input-name'=>'club_name',
            			'text'=>'Navn på klub/ungdomsskole:',
            			'class'=> array('fullsize-label'),
        			));
                    
                ?>
    		</div>
    		<h2>Vælg arrangør-kategori</h2>
    		<div class='fields fields_2'>
    			<p>Vælg den arrangør-kategori der matcher dig bedst - selvom der er flere kategorier der matcher skal du kun vælge en. Som udgangspunkt skal du vælge den nederste kategori der matcher (hvis du f.eks. både er Infonaut og Brandvagt, vælg Brandvagt).</p>
    			<?php

        			renderFieldByType(array(
            			'id'=>'field1_11',
            			'input-type'=>'radio',
            			'input-name'=>'participant',
            			'text'=>'Din rolle',
            			'value' => array(
                        			'forfatter'=>'Jeg er Forfatter / Brætspilsdesigner',
                        			'arrangoer'=>'Jeg er Arrangør',
                        			'infonaut'=>'Jeg er Infonaut',
                        			'dirtbuster'=>'Jeg er Dirtbuster',
                        			'brandvagt'=>'Jeg er Brandvagt',
                        			'kioskninja'=>'Jeg er Kioskninja',
                                    ),
                        'value-default' => 'arrangoer',
        			));
    			?>
    		</div>
    		
    		
    		
    		<h2>Sygdomme & helbred</h2>
    		<div class='fields fields_2'>
        		<?php
            		
    			renderFieldByType(array(
        			'id'=>'field2_1',
        			'input-type'=>'text',
        			'input-name'=>'alternative_phone',
        			'text'=>'Er der et telefonnummer vi kan ringe til i nødstilfælde (fx at du er kommmet til skade) angiv det her:',
        			'class'=> array('fullsize-label'),
        			'caption'=>'Telefonnummeret må kun indeholde tal. Udenlandske numre kan ikke indtastes',
    			));
    			
    			renderFieldByType(array(
        			'id'=>'field2_2',
        			'input-type'=>'textarea',
        			'input-name'=>'other_health',
        			'text'=>'Hvis du lider af en sygdom, vi af særlige grunde skal kende til, så skriv det i nedenstående felt. Vi vil kun bruge denne information i nødstilfælde vedrørende dit helbred.:',
        			'class'=> array('fullsize-label'),
    			));
    			
                ?>
                
    		</div>
            <?php render_next_button("Næste side");?>
        </div>
	</form>
	
	<form method="post" action="./step-1">
        <?php render_previous_button("Forrige side");?>
	</form><?php
