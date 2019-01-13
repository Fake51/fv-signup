<?php
    
    class DeltagerTilmeldingArrangoerPage2 extends SignupPage
    {
        public function getSlug()
        {
            return 'arrangoer';
        }
        
        public function getTitle(){
            return __tm('navigation_4');
        }
        
        public function init()
        {
        }
        
        public function canShow()
        {
            if ($_SESSION['customer']['participant']=='deltagerjunior')return false;

            if (!in_array($_SESSION['customer']['participant'], array('deltagerjunior', 'deltager')))
                return true;
            return false;
        }
        
        public function render()
        {
            $my_errorHandler = array(
                'special_area' => array(
                    'rules'=>"nonempty"
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
        
        
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("general_next_page");?>
                <?php tilm_form_prefields(); ?>
                
                <h1 class='entry-title'><?php __etm('page2b_text1')?></h1>
                <div id='tilmelding-info'>
                    <p><?php __etm('page2b_text2'); ?></p>
                    
            		<?php
                		
                		renderFieldByType(array(
                			'id'=>'field1_1',
                			'input-type'=>'text',
                			'input-name'=>'special_area',
                			'text'=>'page2b_text2_1',
                			'class' => array('fullsize-label'),
                		));
                		
                		renderFieldByType(array(
                			'id'=>'field1_2',
                			'input-type'=>'text',
                			'input-name'=>'special_game',
                			'text'=>'page2b_text3',
                			'class' => array('fullsize-label'),
                		));
            		
                    ?>
                
                		<h2><?php __etm('page2_text18');?></h2>
            			<p><?php __etm('page2_text19');?></p>
                    <?php
                			renderFieldByType(array(
                    			'id'=>'field1_11',
                    			'input-type'=>'radio',
                    			'input-name'=>'participant',
                    			'text'=>'nocat_112_1',
                    			'value' => array(
                                			'arrangoer'=>'nocat_108',
                                			'forfatter'=>'nocat_107',
                                			'infonaut'=>'nocat_109',
                                			'dirtbuster'=>'nocat_110',
                                			'brandvagt'=>'nocat_111',
                                			'kioskninja'=>'nocat_112',
                                			'kaffekro'=>'nocat_kaffekro',
                                			'juniorarrangoer'=>'nocat_arrangoerjunior',
                                ),
                                'value-default' => 'deltager',
                			));
                    ?>
                    
                    
                    <h2><?php __etm('page2b_text4'); ?></h2>
                    
                    <p><?php __etm('page2b_text5');?></p>
                    
                    
                    <p><strong><?php __etm('page2b_text5_1');?></strong></p>
                    <ul>
                        <li><?php __etm('page2b_text6');?></li>
                        <li><?php __etm('page2b_text7');?></li>
                    </ul>
                    
                    
                    <p><strong><?php __etm('page2b_text8');?></strong></p>
                    <ul>
                        <li><?php __etm('page2b_text9');?></li>
                    </ul>
                    
            
                    <h2><?php __etm('page2b_text10'); ?></h2>
                    <p><?php __etm('page2b_text11');?></p>
                    
                    
                    
                    <h2><?php __etm('page2b_text12'); ?></h2>
                    <p><?php __etm('page2b_text13');?></p>
        
            		<?php
                		
                		renderFieldByType(array(
                			'id'=>'field1_3',
                			'input-type'=>'checkbox',
                			'input-name'=>'special_sleeping',
                			'text'=>'page2b_text14',
                		));
            		
                    ?>
                        
                </div>
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("general_next_page");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	<?php
        }
    }


