<?php 

/*
    Name: Tilmelding Side 2
    Class: DeltagerTilmeldingContactPage2
*/

    class DeltagerTilmeldingContactPage2 extends SignupPage
    {
        public function getSlug()
        {
            return 'info';
        }
        
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
                        array('value-between',1900, date("Y"))
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
            
            
            
            
            <div class='load-deltager'>
                <h4><?php __etm('nocat_56');?></h4>
                <p><?php __etm('nocat_57');?></p>
                <p><label><?php __etm('nocat_58');?></label><input type='text' name='load-username' id='load-username' class='field'></p>
                <p><label><?php __etm('nocat_59');?></label><input type='password' name='load-password' id='load-password' class='field'></p>
                <p><button class='load-member'><?php __etm('nocat_60');?></button></p>
                <p style='text-align:center'><a href='#' class='forgot-password'><?php __etm('nocat_63_2');?></a></p>
                
                <div class='forgot-password' style='display:none;'>
                    <h4><?php __etm('nocat_63_2');?></h4>
                    <p><?php __etm('nocat_63_1');?></p>
                    <p><label><?php __etm('Email');?></label><input type='text' name='forgot-username' id='forgot-username' class='field'></p>
                    <p><button class='forgot-member'><?php __etm('nocat_63_4');?></button></p>
                </div>
            </div>
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
                                    alert("<?php __etm('nocat_61');?>");
                                else if (response=="2")
                                    alert("<?php __etm('nocat_62');?>");
                                else if (response == "1"){
                                    location.reload();
                                }
                                else{
                                        alert("<?php __etm('nocat_63');?> "+(response==""?"nil":response)+")");
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
                                    alert("<?php __etm('nocat_61');?>");
                                else if (response=="2")
                                    alert("<?php __etm('nocat_62');?>");
                                else if (response == "1")
                                {
                                    alert("<?php __etm('nocat_63_3');?>");
                                    location.reload();
                                }
                                else{
                                        alert("<?php __etm('nocat_63');?> "+(response==""?"nil":response)+")");
                                }
                            }
                        );
                    });

                });
            </script>
            
            
            
            
            
            

        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("general_next_page");?>
                <?php tilm_form_prefields(); ?>
                
                <h1 class='entry-title'><?php __etm('page2_text1')?></h1>
                
                
        		<div id='tilmelding-info'>
            		<h2><?php __etm('page2_text2');?></h2>
            		<div class='fields fields_1'>
            			<?php
                			renderFieldByType(array(
                    			'id'=>'field1_1',
                    			'input-type'=>'text',
                    			'input-name'=>'firstname',
                    			'text'=>'page2_text3',
                			));
                			renderFieldByType(array(
                    			'id'=>'field1_2',
                    			'input-type'=>'text',
                    			'input-name'=>'lastname',
                    			'text'=>'page2_text4',
                			));
                			
                			renderFieldByType(array(
                    			'id'=>'field1_3',
                    			'input-type'=>'text',
                    			'input-name'=>'address1',
                    			'text'=>'page2_text5',
                			));
                			renderFieldByType(array(
                    			'id'=>'field1_4',
                    			'input-type'=>'text',
                    			'input-name'=>'address2',
                    			'text'=>'page2_text6',
                			));
                			renderFieldByType(array(
                    			'id'=>'field1_5',
                    			'input-type'=>'text',
                    			'input-name'=>'zipcode',
                    			'text'=>'page2_text7',
                			));
                			renderFieldByType(array(
                    			'id'=>'field1_6',
                    			'input-type'=>'text',
                    			'input-name'=>'city',
                    			'text'=>'page2_text8',
                			));
                			
                			renderFieldByType(array(
                    			'id'=>'field1_7',
                    			'input-type'=>'select',
                    			'input-name'=>'country',
                    			'text'=>'page2_text9',
                    			'value' => array(
                                    "Danmark"=>"nocat_64",
                                    "Finland"=>"nocat_65",
                                    "Norge"=>"nocat_66",
                                    "Storbritannien"=>"nocat_67",
                                    "Sverige"=>"nocat_68",
                                    "Tyskland"=>"nocat_69",
                                    "USA"=>"nocat_70",
                                    "Albanien"=>"nocat_71",
                                    "Belgien"=>"nocat_72",
                                    "Bosnien-Hercegovina"=>"nocat_73",
                                    "Bulgarien"=>"nocat_74",
                                    "Canada"=>"nocat_75",
                                    "Cypern"=>"nocat_76",
                                    "Estland"=>"nocat_77",
                                    "Frankrig"=>"nocat_78",
                                    "Færøerne"=>"nocat_79",
                                    "Grækenland"=>"nocat_80",
                                    "Grønland"=>"nocat_81",
                                    "Holland"=>"nocat_82",
                                    "Irland"=>"nocat_83",
                                    "Island"=>"nocat_84",
                                    "Italien"=>"nocat_85",
                                    "Kroatien"=>"nocat_86",
                                    "Letland"=>"nocat_87",
                                    "Luxembourg"=>"nocat_88",
                                    "Malta"=>"nocat_89",
                                    "Montenegro"=>"nocat_90",
                                    "Polen"=>"nocat_91",
                                    "Portugal"=>"nocat_92",
                                    "Republikken Makedonien"=>"nocat_93",
                                    "Rumænien"=>"nocat_94",
                                    "Schweiz"=>"nocat_95",
                                    "Serbien"=>"nocat_96",
                                    "Slovakiet"=>"nocat_97",
                                    "Slovenien"=>"nocat_98",
                                    "Spanien"=>"nocat_99",
                                    "Tjekkiet"=>"nocat_100",
                                    "Tyrkiet"=>"nocat_101",
                                    "Ungarn"=>"nocat_102",
                                    "Østrig"=>"nocat_103"
                                            ),
                			));
                            
                			
                			renderFieldByType(array(
                    			'id'=>'field1_8',
                    			'input-type'=>'text',
                    			'input-name'=>'mobile',
                    			'text'=>'page2_text10'
                    			
                			));
                			renderFieldByType(array(
                    			'id'=>'field1_17',
                    			'input-type'=>'checkbox',
                    			'input-name'=>'bringing_mobile',
                    			'text'=>'page2_text12'
                			));
        
                			renderFieldByType(array(
                    			'id'=>'field1_18',
                    			'input-type'=>'select',
                    			'input-name'=>'sex',
                    			'text'=>'nocat_220',
                    			'value' => array(
                                			'Mand'=>'nocat_104',
                                			'Kvinde'=>'nocat_105',
                                			'Andet'=>'nocat_105_2',
                                    ),
                			));
        
                			renderFieldByType(array(
                    			'id'=>'field1_19',
                    			'input-type'=>'birthday',
                    			'input-name'=>'birthday',
                    			'text'=>'nocat_221',
                    			'caption'=>'page2_text15',
                			));
                			
                        ?>
            			<p class="field1_9"><label for="email"><?php __etm('nocat_58');?>:</label> <input class='tilmelding-input'  type="text" id='email' name="email" value="<?php  echo (gf("email"))?>" /><?php getError("email");?></p>
            			<p class="field1_10"><label for="email_repeat"><?php __etm('nocat_58_1');?>:</label> <input class='tilmelding-input' type="text" id='email_repeat' name="email_repeat" value="<?php  echo (gf("email_repeat"))?>" /><?php getError("email_repeat");?></p>
                        <?php
        
                			renderFieldByType(array(
                    			'id'=>'field1_15',
                    			'input-type'=>'checkbox',
                    			'input-name'=>'with_club',
                    			'text'=>'page2_text16',
                			));
                			renderFieldByType(array(
                    			'id'=>'field1_16',
                    			'input-type'=>'text',
                    			'input-name'=>'club_name',
                    			'text'=>'page2_text17',
                    			'class'=> array('fullsize-label'),
                			));
                            
                        ?>
            		</div>
            		<h2><?php __etm('page2_text18');?></h2>
            		<div class='fields fields_2'>
            			<p><?php __etm('page2_text19');?></p>
            			<p><?php __etm('page2_text20');?></p>
            			<?php
        
                			renderFieldByType(array(
                    			'id'=>'field1_11',
                    			'input-type'=>'radio',
                    			'input-name'=>'participant',
                    			'text'=>'nocat_112_1',
                    			'value' => array(
                                			'deltager'=>'nocat_106',
                                			'deltagerjunior'=>'nocat_deltagerjunior',
                                			'--' => "<br>".__tm('page2_text19')."<br><br>",
                                			'forfatter'=>'nocat_107',
                                			'arrangoer'=>'nocat_108',
                                			'infonaut'=>'nocat_109',
                                			'dirtbuster'=>'nocat_110',
                                			'brandvagt'=>'nocat_111',
                                			'kioskninja'=>'nocat_112',
                                			'kaffekro'=>'nocat_kaffekro',
                                            ),
                                'value-default' => 'deltager',
                			));
            			?>
            		</div>
            		
            		
            		
            		<h2><?php __etm('page2_text21');?></h2>
            		<div class='fields fields_2'>
                		<?php
                    		
            			renderFieldByType(array(
                			'id'=>'field2_1',
                			'input-type'=>'text',
                			'input-name'=>'alternative_phone',
                			'text'=>'page2_text22',
                			'class'=> array('fullsize-label'),
                			'caption'=>'page2_text11',
            			));
            			
            			renderFieldByType(array(
                			'id'=>'field2_2',
                			'input-type'=>'textarea',
                			'input-name'=>'other_health',
                			'text'=>'page2_text23',
                			'class'=> array('fullsize-label'),
            			));
            			
                        ?>
                        
            		</div>
                <?php tilm_form_postfields(); ?>
                    <?php render_next_button("general_next_page");?>
                </div>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	
        	<?php            
        }
    }
    
    
	

