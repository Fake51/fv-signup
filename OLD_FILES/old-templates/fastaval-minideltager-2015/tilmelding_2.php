<?php 

/*
    Name: Tilmelding Side 2
    Class: DeltagerTilmeldingContactPage2
*/

    class DeltagerTilmeldingContactPage2 extends SignupPage
    {
        public function init()
        {
        }
        
        public function canShow()
        {
            return true;
        }
        
        public function render()
        {

            $errorHandler = array(
                'firstname' => array("nonempty"),
                'lastname' => array("nonempty"),
                'address1' => array("nonempty"),
                'address2' => array("nonempty"),
                'zipcode' => array("nonempty"),
                'city' => array("nonempty"),
                'email' => array("nonempty"),
            );

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
                    'rules'=>array(
                        array('digits'),
                        array('length-above-or-empty',7)
                    )
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
            
            
            
            
            <script>
                jQuery(document).ready(function(){
                    
                    jQuery('a.forgot-password').click(function(){
                        jQuery('div.forgot-password').show();
                        return false;
                    });
                    
                    jQuery('.load-member').click(function(){
                        jQuery.post(
                            "<?php echo admin_url('admin-ajax.php'); ?>", 
                            {
                                'action': 'tilmelding_login',
                                'username': jQuery('#load-username').val(),
                                'password': jQuery('#load-password').val()
                            }, 
                            function(response)
                            {
                                if (response=="3")
                                    alert("<?php __etm('En fejl opstod da tilmeldingen prøvede at koble sig på serveren. Prøv igen senere.');?>");
                                else if (response=="2")
                                    alert("<?php __etm('Der er muligvis en fejl i kombinationen af din email og kodeord. Prøv igen.');?>");
                                else if (response == "1"){
                                    location.reload();
                                }
                                else{
                                    alert("<?php __etm('En ukendt fejl opstod, prøv igen (fejlkode:');?> "+(response==""?"nil":response)+")");
                                }
                            }
                        );
                    });


                    jQuery('.forgot-member').click(function(){
                        jQuery.post(
                            "<?php echo admin_url('admin-ajax.php'); ?>", 
                            {
                                'action': 'tilmelding_forgot',
                                'username': jQuery('#forgot-username').val(),
                            }, 
                            function(response)
                            {
                                if (response=="3")
                                    alert("<?php __etm('En fejl opstod da tilmeldingen prøvede at koble sig på serveren. Prøv igen senere.');?>");
                                else if (response=="2")
                                    alert("<?php __etm('Der er muligvis en fejl i den indtastede email. Prøv igen.');?>");
                                else if (response == "1")
                                {
                                    alert("<?php __etm('Dine login-informationer bliver nu sendt.');?>");
                                    location.reload();
                                }
                                else{
                                    alert("<?php __etm('En ukendt fejl opstod, prøv igen (fejlkode:');?> "+(response==""?"nil":response)+")");
                                }
                            }
                        );
                    });

                });
            </script>
            
            
            
            
            
            

        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("Næste side");?>
                <?php tilm_form_prefields(); ?>
                
                <h1 class='entry-title'><?php __etm('Velkommen til Fastaval tilmeldingen')?></h1>
                
                
        		<div id='tilmelding-info'>
            		<h2><?php __etm('Kontaktoplysninger');?></h2>
            		<div class='fields fields_1'>
            			<?php
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
                    			'text'=>'Mobil:'
                    			
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
            		<h2><?php __etm('Vælg din deltager-kategori');?></h2>
            		<div class='fields fields_2'>
            			<p><?php __etm('Laver du flere ting? Vælg den kategori der matcher dig bedst <b>fra neden</b> -  hvis du f.eks. både er Forfatter og Brandvagt, vælg Brandvagt.');?></p>
            			<p><?php __etm('<strong>Er du ikke arrangør?</strong> Så marker feltet "Jeg er Deltager" og gå videre i tilmeldingen.');?></p>
            			<?php
        
                			renderFieldByType(array(
                    			'id'=>'field1_11',
                    			'input-type'=>'radio',
                    			'input-name'=>'participant',
                    			'text'=>'Din rolle',
                    			'value' => array(
                                			'deltager'=>'Jeg er Deltager',
                                			'--' => '<br>',
                                			'forfatter'=>'Jeg er Forfatter / Brætspilsdesigner',
                                			'arrangoer'=>'Jeg er Arrangør',
                                			'infonaut'=>'Jeg er Infonaut',
                                			'dirtbuster'=>'Jeg er Dirtbuster',
                                			'brandvagt'=>'Jeg er Brandvagt',
                                			'kioskninja'=>'Jeg er Kioskninja',
                                            ),
                                'value-default' => 'deltager',
                			));
            			?>
            		</div>
            		
            		
            		
            		<h2><?php __etm('Sygdomme & helbred');?></h2>
            		<div class='fields fields_2'>
                		<?php
                    		
            			renderFieldByType(array(
                			'id'=>'field2_1',
                			'input-type'=>'text',
                			'input-name'=>'alternative_phone',
                			'text'=>'Er der et telefonnummer vi kan ringe til i nødstilfælde (fx hvis du er kommmet til skade)?',
                			'class'=> array('fullsize-label'),
                			'caption'=>'Telefonnummeret må kun indeholde tal. Udenlandske numre kan ikke indtastes',
            			));
            			
            			renderFieldByType(array(
                			'id'=>'field2_2',
                			'input-type'=>'textarea',
                			'input-name'=>'other_health',
                			'text'=>'Hvis du lider af en sygdom, vi af særlige grunde skal kende til, så skriv det i nedenstående felt. Vi vil kun bruge denne information i nødstilfælde vedrørende dit helbred:',
                			'class'=> array('fullsize-label'),
            			));
            			
                        ?>
                        
            		</div>

                <?php tilm_form_postfields(); ?>
                    <?php render_next_button("Næste side");?>
                </div>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	
        	<?php            
        }
    }
    
    
	

