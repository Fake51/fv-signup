<?php
    
    class FastavalJuniorPage extends SignupPage
    {
        public function getSlug()
        {
            return 'fastavaljunior';
        }
        
        public function getTitle(){
            return __tm('navigation_fastavaljunior');
        }
        
        public function init()
        {
        }
        
        public function canShow()
        {
            if (isset($_SESSION['customer']['participant']) && 
                    $_SESSION['customer']['participant']=='deltagerjunior')
                return true;
            
            return false;
        }
        
        public function render()
        {
            $my_errorHandler = array(
                'ward_name' => array(
                    'rules'=>"nonempty"
                ),
                'ward_phone' => array(
                    'rules'=>array(
                        array('digits'),
                        array('length-above',7)
                    )
                )
            )
            ?>

            <script>
                function validate_required(field,alerttxt) {
                    var value = jQuery('#'+field).val();
                    if ((value==null)||(value=="")) {
                        alert(alerttxt);
                        return false;
                    } else 
                        return true;
                }


                function validate_form(thisform)
                {
                    jQuery('input.has-error').removeClass('has-error');
                    <?php renderErrorHandlerAsJavascript($my_errorHandler); ?>
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
                
                <h1 class='entry-title'><?php __etm('fastaval_junior_headline_1');?></h1>
                <p><?php __etm('fastaval_junior_text_1');?></p>
                
                <h2><?php __etm('fastaval_junior_headline_2');?></h2>
                <p><?php __etm('fastaval_junior_text_2');?></p>
                
                <h2><?php __etm('fastaval_junior_headline_3');?></h2>
                <p><?php __etm('fastaval_junior_text_3');?></p>
                
                <h2><?php __etm('fastaval_junior_headline_4');?></h2>
                <p><?php __etm('fastaval_junior_text_4');?></p>

                <div id='tilmelding-info'>
                    <?php
            			renderFieldByType(array(
                			'id'=>'ward_name',
                			'input-type'=>'text',
                			'input-name'=>'ward_name',
                			'text'=>'fastaval_junior_felt_1',
            			));

                        renderFieldByType(array(
                			'id'=>'ward_phone',
                			'input-type'=>'text',
                			'input-name'=>'ward_phone',
                			'text'=>'fastaval_junior_felt_2',
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


