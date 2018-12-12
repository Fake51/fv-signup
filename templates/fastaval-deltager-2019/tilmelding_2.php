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
        
        public function getTitle(){
            return __tm('navigation_2');
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
"Denmark" => "Denmark",
"Germany" => "Germany",
"Norway" => "Norway",
"Sweden" => "Sweden",
"United Kingdom" => "United Kingdom",
"Australia" => "Australia",
"Austria" => "Austria",
"Finland" => "Finland",
"Netherlands" => "Netherlands",
"Poland" => "Poland",
"Russia" => "Russia",
"USA" => "USA",
"Abkhazia" => "Abkhazia",
"Afghanistan" => "Afghanistan",
"Albania" => "Albania",
"Algeria" => "Algeria",
"Andorra" => "Andorra",
"Angola" => "Angola",
"Antigua and Barbuda" => "Antigua and Barbuda",
"Argentina" => "Argentina",
"Armenia" => "Armenia",
"Artsakh" => "Artsakh",
"Azerbaijan" => "Azerbaijan",
"Bahamas" => "Bahamas",
"Bahrain" => "Bahrain",
"Bangladesh" => "Bangladesh",
"Barbados" => "Barbados",
"Belarus" => "Belarus",
"Belgium" => "Belgium",
"Belize" => "Belize",
"Benin" => "Benin",
"Bhutan" => "Bhutan",
"Bolivia" => "Bolivia",
"Bosnia and Herzegovina" => "Bosnia and Herzegovina",
"Botswana" => "Botswana",
"Brazil" => "Brazil",
"Brunei" => "Brunei",
"Bulgaria" => "Bulgaria",
"Burkina Faso" => "Burkina Faso",
"Burundi" => "Burundi",
"Cambodia" => "Cambodia",
"Cameroon" => "Cameroon",
"Canada" => "Canada",
"Cape Verde" => "Cape Verde",
"Central African Republic" => "Central African Republic",
"Chad" => "Chad",
"Chile" => "Chile",
"China" => "China",
"Colombia" => "Colombia",
"Comoros" => "Comoros",
"Cook Islands" => "Cook Islands",
"Costa Rica" => "Costa Rica",
"Croatia" => "Croatia",
"Cuba" => "Cuba",
"Cyprus" => "Cyprus",
"Czech Republic" => "Czech Republic",
"Djibouti" => "Djibouti",
"Dominica" => "Dominica",
"Dominican Republic" => "Dominican Republic",
"East Timor" => "East Timor",
"Ecuador" => "Ecuador",
"Egypt" => "Egypt",
"El Salvador" => "El Salvador",
"Equatorial Guinea" => "Equatorial Guinea",
"Eritrea" => "Eritrea",
"Estonia" => "Estonia",
"Ethiopia" => "Ethiopia",
"Fiji" => "Fiji",
"France" => "France",
"Gabon" => "Gabon",
"Gambia" => "Gambia",
"Georgia" => "Georgia",
"Ghana" => "Ghana",
"Greece" => "Greece",
"Grenada" => "Grenada",
"Guatemala" => "Guatemala",
"Guinea" => "Guinea",
"Guinea-Bissau" => "Guinea-Bissau",
"Guyana" => "Guyana",
"Haiti" => "Haiti",
"Honduras" => "Honduras",
"Hungary" => "Hungary",
"Iceland" => "Iceland",
"India" => "India",
"Indonesia" => "Indonesia",
"Iran" => "Iran",
"Iraq" => "Iraq",
"Ireland" => "Ireland",
"Israel" => "Israel",
"Italy" => "Italy",
"Ivory Coast" => "Ivory Coast",
"Jamaica" => "Jamaica",
"Japan" => "Japan",
"Jordan" => "Jordan",
"Kazakhstan" => "Kazakhstan",
"Kenya" => "Kenya",
"Kiribati" => "Kiribati",
"Kosovo" => "Kosovo",
"Kuwait" => "Kuwait",
"Kyrgyzstan" => "Kyrgyzstan",
"Laos" => "Laos",
"Latvia" => "Latvia",
"Lebanon" => "Lebanon",
"Lesotho" => "Lesotho",
"Liberia" => "Liberia",
"Libya" => "Libya",
"Liechtenstein" => "Liechtenstein",
"Lithuania" => "Lithuania",
"Luxembourg" => "Luxembourg",
"Macedonia" => "Macedonia",
"Madagascar" => "Madagascar",
"Malawi" => "Malawi",
"Malaysia" => "Malaysia",
"Maldives" => "Maldives",
"Mali" => "Mali",
"Malta" => "Malta",
"Marshall Islands" => "Marshall Islands",
"Mauritania" => "Mauritania",
"Mauritius" => "Mauritius",
"Mexico" => "Mexico",
"Micronesia" => "Micronesia",
"Moldova" => "Moldova",
"Monaco" => "Monaco",
"Mongolia" => "Mongolia",
"Montenegro" => "Montenegro",
"Morocco" => "Morocco",
"Mozambique" => "Mozambique",
"Myanmar" => "Myanmar",
"Namibia" => "Namibia",
"Nauru" => "Nauru",
"Nepal" => "Nepal",
"New Zealand" => "New Zealand",
"Nicaragua" => "Nicaragua",
"Niger" => "Niger",
"Nigeria" => "Nigeria",
"Niue" => "Niue",
"Northern Cyprus" => "Northern Cyprus",
"Oman" => "Oman",
"Pakistan" => "Pakistan",
"Palau" => "Palau",
"Palestine" => "Palestine",
"Panama" => "Panama",
"Papua New Guinea" => "Papua New Guinea",
"Paraguay" => "Paraguay",
"Peru" => "Peru",
"Philippines" => "Philippines",
"Portugal" => "Portugal",
"Qatar" => "Qatar",
"Romania" => "Romania",
"Rwanda" => "Rwanda",
"Saint Kitts and Nevis" => "Saint Kitts and Nevis",
"Saint Lucia" => "Saint Lucia",
"Saint Vincent and the Grenadines" => "Saint Vincent and the Grenadines",
"Samoa" => "Samoa",
"San Marino" => "San Marino",
"Saudi Arabia" => "Saudi Arabia",
"Senegal" => "Senegal",
"Serbia" => "Serbia",
"Seychelles" => "Seychelles",
"Sierra Leone" => "Sierra Leone",
"Singapore" => "Singapore",
"Slovakia" => "Slovakia",
"Slovenia" => "Slovenia",
"Solomon Islands" => "Solomon Islands",
"Somalia" => "Somalia",
"Somaliland" => "Somaliland",
"South Africa" => "South Africa",
"South Korea" => "South Korea",
"South Ossetia" => "South Ossetia",
"South Sudan" => "South Sudan",
"Spain" => "Spain",
"Sri Lanka" => "Sri Lanka",
"Sudan" => "Sudan",
"Suriname" => "Suriname",
"Swaziland" => "Swaziland",
"Switzerland" => "Switzerland",
"Syria" => "Syria",
"Taiwan" => "Taiwan",
"Tajikistan" => "Tajikistan",
"Tanzania" => "Tanzania",
"Thailand" => "Thailand",
"Togo" => "Togo",
"Tonga" => "Tonga",
"Transnistria" => "Transnistria",
"Trinidad and Tobago" => "Trinidad and Tobago",
"Tunisia" => "Tunisia",
"Turkey" => "Turkey",
"Turkmenistan" => "Turkmenistan",
"Tuvalu" => "Tuvalu",
"Uganda" => "Uganda",
"Ukraine" => "Ukraine",
"United Arab Emirates" => "United Arab Emirates",
"Uruguay" => "Uruguay",
"Uzbekistan" => "Uzbekistan",
"Vanuatu" => "Vanuatu",
"Vatican City" => "Vatican City",
"Venezuela" => "Venezuela",
"Vietnam" => "Vietnam",
"Yemen" => "Yemen",
"Zambia" => "Zambia",
"Zimbabwe" => "Zimbabwe",
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
                                			'--' => "<br>",
                                			'arrangoer'=>'nocat_108',
                                			
                                			/*
                                			'--' => "<br>".__tm('page2_text19')."<br><br>",
                                			'forfatter'=>'nocat_107',
                                			'arrangoer'=>'nocat_108',
                                			'infonaut'=>'nocat_109',
                                			'dirtbuster'=>'nocat_110',
                                			'brandvagt'=>'nocat_111',
                                			'kioskninja'=>'nocat_112',
                                			'kaffekro'=>'nocat_kaffekro',
                                			*/
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
    
    
	

