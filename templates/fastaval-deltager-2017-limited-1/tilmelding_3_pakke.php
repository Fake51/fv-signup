<?php
    
    class DeltagerTilmeldingPakkePage extends SignupPage
    {
        public function getSlug()
        {
            return 'pakkeloesning';
        }
        
        public function init()
        {
        }
        
        public function canShow()
        {
            if ($_SESSION['customer']['participant']=='deltagerjunior')
                return false;
            
            return true;
        }
        
        public function render()
        {
            include("modules/wear_v1.php");
            $my_errorHandler = array(
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
                <?php /* render_next_button("general_next_page"); */?>
                <?php tilm_form_prefields(); ?>
                
                <h1 class='entry-title'><?=__etm('pakke_page_headline');?></h1>
                <div id='tilmelding-info'>
                    <p><?=__etm('pakke_page_tekst1');?></p>
                    <p><?=__etm('pakke_page_tekst2');?></p>
                


                    <div class='box'>
                        <h3 style='margin-top:0;'><?=__etm('pakke_package_headline2');?></h3>
                        <input type='hidden' value='0' id='is_package' name='is_package'>
                        <p><?=__etm('pakke_package_text2');?></p>
                        <p>
                            <?php tilm_form_postfields(); ?>
                            <?php render_next_button("general_next_page");?>
                        </p>
                    </div>
                    


                    
                    <div class='box'>
                        <h3 style='margin-top:0;'><?=__etm('pakke_package_headline');?></h3>
                        <p><?=__etm('pakke_page_tekst4');?></p>
                        <p>
                            <?=__etm('pakke_package_food_text');?>
                            <select id='package_food'>
                                <option value='normal'><?=__etm('pakke_package_food_normal');?></option>
                                <option value='vegetar'><?=__etm('pakke_package_food_vegetarian');?></option>
                            </select>
                        </p>
                        
                        <?php
                            if ( in_array($_SESSION['customer']['participant'], array('kioskninja', 'deltagerjunior', 'kaffekro', 'infonaut' )) ){
                                ?>
                                <h4><?=__etm('pakke_package_wear');?></h4>
                                <p><?=__etm('pakke_package_text');?></p>
                                <?php
                            }
                            
                            
                            $wear_v1 = new wear_v1();
                            $wear_v1->render(true);
                            
                        ?>
                        
                        <h4><?=__etm('pakke_package_activity');?></h4>
                        <p><?=__etm('pakke_package_activity_text1');?></p>
                        <?php /*
                        <p><?php __etm('nocat_199');?></p>
            			<div style='position:relative;top:-1.75em;'>
                        <?php
            			renderFieldByType(array(
                			'id'=>'other_dansk',
                			'input-type'=>'checkbox',
                			'input-name'=>'other_dansk',
                			'text'=>'nocat_46',
            			));?>
                			<div style='position:relative;top:-1.75em;'>
                			<?php
                			renderFieldByType(array(
                    			'id'=>'other_engelsk',
                    			'input-type'=>'checkbox',
                    			'input-name'=>'other_engelsk',
                    			'text'=>'nocat_47',
                			));
                            ?>
                			</div>
            			</div>
            			*/ ?>
            			<p>&nbsp;</p>

                        <div style='float:right;margin-top:-50px;'>
                            <input class="button" id='package' type="button" value="<?=__etm('pakke_package_button');?>">
                        </div>
                        <script>
                            jQuery(document).ready(function(){
                                jQuery('#package').click(function(){
                                    
                                    var wear_arr = [];
                                    
                                    for(i=0;i<100;i++){
                                        if (jQuery('#wear_'+i+'_count').length>0){
                                            wear_arr[wear_arr.length] = {
                                                    id : i,
                                                    count : jQuery('#wear_'+i+'_count').val(), 
                                                    size : jQuery('#wear_'+i+'_size').val()
                                                };
                                        }
                                    }
                                    
                                    jQuery.post('<?php echo plugin_dir_url(__FILE__);?>package-solution.php', {
                                        wear : wear_arr,
                                        food : jQuery('#package_food').val()
                                    },
                                    function(response) 
                                    {
                                        window.location.href = "./godkend<?php
                                            if (get_language()!="da")
                                                echo "?lang=".get_language();
                                        ?>";
                                    });
                                });
                            });
                        </script>
                    </div>
                    
                    
                    
                </div>
                
                <style>
                    .box{
                        border:1px solid lightgrey;
                        padding: 20px;
                        padding-bottom:0;
                        margin-bottom:25px;
                    }
                    .box .button{
                        margin-top:-60px;
                        width:200px;
                    }
                </style>
                
                <?php tilm_form_postfields(); ?>
                <?php /* render_next_button("general_next_page"); */?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	<?php
        }
    }


