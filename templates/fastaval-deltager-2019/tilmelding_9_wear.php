<?php
    
    class DeltagerTilmeldingWearPage9 extends SignupPage
    {
        public function getSlug()
        {
            return 'fastagear';
        }
        
        public function getTitle(){
            return __tm('navigation_9');
        }
        
        public function init()
        {
            include("modules/wear_v1.php");
        }
        
        public function canShow()
        {
            if (isset($_SESSION['customer']['is_package']) && ($_SESSION['customer']['is_package']==1))
                return false;
            
            return true;
        }
        
        public function render()
        {
            ?>
            <script>
                
                function validate_select(id_1, id_2)
                {
                    var value_to_return = true;
                    jQuery('.'+id_1+'-select select').each(function()
                    {
                        var select_value = jQuery(this).find('option:selected').val();
                        if (select_value!="")
                        {
                            // find the size version
                            var object_id = jQuery(this).attr('id');
                            object_id = object_id.replace('_'+id_1,'_'+id_2);
                            var other_select_value = jQuery("#"+object_id).find('option:selected').val();
                            if (other_select_value=="")
                            {
                                value_to_return = false;
                            }
                        }
                    });
                    return value_to_return;
                }
                
                function validate_form(what)
                {
                    var will_validate_1 = validate_select('count','size');
                    if (!will_validate_1)
                    {
                        alert('<?php __etm("nocat_38")?>');
                        return false;
                    }
                    
                    var will_validate_2 = validate_select('size','count');
                    if (!will_validate_2)
                    {
                        alert('<?php __etm("nocat_39")?>');
                        return false;
                    }
                    
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
                
                <h1 class='entry-title'><?php __etm('nocat_40');?></h1>
                <div id='tilmelding-info'>
                    
                    <?php  if($_SESSION['customer']['participant'] ===  "deltagerjunior") { 
                        // different text for junior that doesn't mention 
                        // echo "<p>".__etm('nocat_179_1')."</p>";
                    } else {
                        echo "<p>".__etm('nocat_179')."</p>";
                    }?>

                    <?php 
                    if (isset($_SESSION['customer']['participant']) && !in_array($_SESSION['customer']['participant'], array ("deltager", "deltagerjunior")))
                    { ?>
                        <p><?php __etm('nocat_180');?></p>
                    <?php } ?>

                    <p><?php __etm('nocat_181');?></p>
                    
                    <?php
                        $wear_v1 = new wear_v1();
                        $wear_v1->render();
                        
                    ?>
                    <p>&nbsp;</p>
                    <?php 
                    if ($_SESSION['customer']['participant']=="infonaut")
                    {
                        ?><p><?php __etm('nocat_181_1');?></p><?php 
                    } 
                    ?>
                    


                    
                </div>
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("general_next_page"); ?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	<?php

        }
    }


