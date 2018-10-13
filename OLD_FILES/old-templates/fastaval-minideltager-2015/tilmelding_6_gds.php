<?php
    
    class DeltagerTilmeldingGDSPage8 extends SignupPage
    {
        public function __construct(){
            include("modules/gds_v1.php");
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
            
            ?>
            <script>
                function validate_form(what)
                {
                    var user_gds = jQuery("#aktiviteter input:checkbox:checked").map(function(){
                        return jQuery(this).val();
                    }).get(); // <----
                    
                    var limit = 3;
                    <?php
                    if ($_SESSION['customer']['participant']!="deltager"){
                        echo "limit = 0;\n";
                    }
                    else if ($_SESSION['customer']['aktiviteter_is_spilleder'] > 0)
                    {
                        echo "limit = 0;\n";
                    }
                    ?>
                    
                    if (user_gds.length<limit)
                    {
                        alert('<?php __etm('Husk at vælge 3 GDS tjanser i alt');?>');
                        return false;
                    }
                    return true;
                }
            </script>
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("Næste side");?>
                <?php tilm_form_prefields(); ?>
                
                <h1 class='entry-title'><?php __etm('GDS');?></h1>
                <div id='tilmelding-info'>
                    
                    <p><?php __etm('GDS står for Gør Det Selv. På Fastaval giver alle et nap med. GDS-tjanserne er nemme og overskuelige, og vi koordinerer det, så det ikke går ud over dine andre aktiviteter.<br><br>Du skal vælge mindst én GDS-tjans. Prioriter tre GDS-tjanser på nedenstående liste, så vi har noget at vælge imellem.</p><p>Læs om de forskellige <a href="http://www.fastaval.dk/gds-tjanser/" target="_blank" style="color:#b00;">GDS-tjanser her</a>');?></p>
                    
                    
                    <?php 
                    if ($_SESSION['customer']['participant']!="deltager")
                    {
                        ?><p><?php __etm('<b>Du er arrangør eller forfatter/brætspilsdesigner, og behøver derfor ikke tage en GDS-tjans</b> (men du må gerne!)');?></p><?php 
                    } 
                    ?>
                    
                                        <?php 
                    if ($_SESSION['customer']['aktiviteter_is_spilleder'] > 0)
                    {
                        ?><p><?php __etm('Du har allerede i din rolle som spilleder lagt meget tid i Fastaval og behøver derfor ikke tage en GDS-tjans. Hvis du alligevel lyster dette skal du dog være meget velkommen til at vælge nogle tjanser.');?></p><?php 
                    } 
                    ?>

                    <h3><?php __etm('Er du en helte-GDS?');?></h3>
                    <?php
        			renderFieldByType(array(
            			'id'=>'boardgame_competition',
            			'input-type'=>'checkbox',
            			'input-name'=>'more_gds',
            			'text'=>'Afgjort! Jeg kan godt overtales til at tage mere end én GDS-tjans',
        			));
        			?>
        			
                    <h3><?php __etm('Er du en Super-GDS?');?></h3>
                    <?php
        			renderFieldByType(array(
            			'id'=>'boardgame_competition',
            			'input-type'=>'checkbox',
            			'input-name'=>'super_gds',
            			'text'=>'Ja! Jeg er Fastavals svar på faldskærmstropper. I kan ringe til mig, når det hele er væltet og I akut har brug for en hjælpende GDS-hånd',
        			));
        			?>
        			
<?php

    $gds = new gds_v1();
    $gds->render();

?>
                    
        			
                </div>
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("Næste side"); ?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	<?php

        }
    }


