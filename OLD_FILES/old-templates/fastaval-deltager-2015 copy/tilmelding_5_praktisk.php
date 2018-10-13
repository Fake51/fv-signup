<?php
    
    class DeltagerTilmeldingPratiskPage5 extends SignupPage
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
            ?>
            <script>
                function validate_form(the_form){
                    if (
                        (jQuery('#days_all').attr('checked')=="checked")   ||
                        (jQuery('#days_1').attr('checked')=="checked")   ||
                        (jQuery('#days_2').attr('checked')=="checked")   ||
                        (jQuery('#days_3').attr('checked')=="checked")   ||
                        (jQuery('#days_4').attr('checked')=="checked")   ||
                        (jQuery('#days_5').attr('checked')=="checked")
                    )
                        return true;
                    alert('<?php __etm('Vælg hvilke dage du kommer til fastaval')?>');
                    return false;
                }
            </script>
            
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("Næste side");?>
                <?php tilm_form_prefields(); ?>
                
                <h1 class='entry-title'><?php __etm('Praktisk');?></h1>
                <div id='tilmelding-info'>
                    
                    <h2><?php __etm('Entre (hvilke dage er du på Fastaval)');?></h2>
                    
                    
                    <?php
        			renderFieldByType(array(
            			'id'=>'field_days_all',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_all',
            			'text'=>'Partout - Alle dage. Priserne er:',
        			));
        			?>
        			<script>
            			jQuery(document).ready(function()
            			{
                			function days_logic()
                			{
                    			if (jQuery('#days_all').attr('checked'))
                    			{
                        			jQuery('#days_1').attr("disabled", true);
                        			jQuery('#days_2').attr("disabled", true);
                        			jQuery('#days_3').attr("disabled", true);
                        			jQuery('#days_4').attr("disabled", true);
                        			jQuery('#days_5').attr("disabled", true);
                        			
                        			jQuery('.field_days_1 label').addClass('disabled');
                        			jQuery('.field_days_2 label').addClass('disabled');
                        			jQuery('.field_days_3 label').addClass('disabled');
                        			jQuery('.field_days_4 label').addClass('disabled');
                        			jQuery('.field_days_5 label').addClass('disabled');
                    			}
                    			else{
                        			jQuery('#days_1').attr("disabled", false);
                        			jQuery('#days_2').attr("disabled", false);
                        			jQuery('#days_3').attr("disabled", false);
                        			jQuery('#days_4').attr("disabled", false);
                        			jQuery('#days_5').attr("disabled", false);
                        			jQuery('.field_days_1 label').removeClass('disabled');
                        			jQuery('.field_days_2 label').removeClass('disabled');
                        			jQuery('.field_days_3 label').removeClass('disabled');
                        			jQuery('.field_days_4 label').removeClass('disabled');
                        			jQuery('.field_days_5 label').removeClass('disabled');
                    			}
                			}
                			jQuery('#days_all').click(function()
                			{
                    			days_logic();
                			});
                			days_logic();


                            jQuery('.link-previous').click(function()
                            {
                                jQuery("form.prev-form:first-child input[type='submit']").click();
                                //alert("aa");
                            });

            			});
            			
            			
                    </script>
        			<?php
        			// http://infosys.fastaval.dk/api/entrance/*
        			
                    if (!isset($_SESSION['customer']['new_alea']))
                    {
                        ?><p class='indent'><?php 
                        __etm('Almindelig billet (210 kr)<br/>Ungdomsbillet (160 kr)<br/>Arrangør (150 kr)<br/><br/><strong>OBS: Du kan spare penge på din indgangsbillet hvis du melder dig ind i ALEA. Tryk på <span class="link-previous">[Forrige side]</span> længere nede for at gå tilbage og melde dig ind.</strong>');
                        ?></p><?php
                    }
                    
                    if (isset($_SESSION['customer']['new_alea']))
                    {
                        ?><p class='indent'><?php 
                        __etm('Almindelig billet (85 kr)<br/>Ungdomsbillet (35 kr)<br/>Arrangør (25 kr)');
                        ?></p><?php 
                    }
                    
                    ?><div class='grouped'><?php
        			renderFieldByType(array(
            			'id'=>'field_days_1',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_1',
            			'text'=>'Onsdag (55 kr)',
        			));
        			renderFieldByType(array(
            			'id'=>'field_days_2',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_2',
            			'text'=>'Torsdag (55 kr)',
        			));
        			renderFieldByType(array(
            			'id'=>'field_days_3',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_3',
            			'text'=>'Fredag (55 kr)',
        			));
        			renderFieldByType(array(
            			'id'=>'field_days_4',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_4',
            			'text'=>'Lørdag (55 kr)',
        			));
        			renderFieldByType(array(
            			'id'=>'field_days_5',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_5',
            			'text'=>'Søndag (55 kr)',
        			));
                    ?></div><?php
                    
                    ?><h2><?php __etm('Overnatning');?></h2><?php
                        
        			renderFieldByType(array(
            			'id'=>'field_days_sleeping',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_sleeping',
            			'text'=>'Jeg overnatter alle dage på Fastaval (175 kr)',
        			));
        			?>
        			<script>
            			
            			function sleeping_logic()
            			{
                			if (jQuery('#days_sleeping').attr('checked')){
                    			jQuery('#days_sleeping_1').attr("disabled", true);
                    			jQuery('#days_sleeping_2').attr("disabled", true);
                    			jQuery('#days_sleeping_3').attr("disabled", true);
                    			jQuery('#days_sleeping_4').attr("disabled", true);
                    			jQuery('#days_sleeping_5').attr("disabled", true);
                    			
                    			jQuery('.field_days_sleeping_1 label').addClass('disabled');
                    			jQuery('.field_days_sleeping_2 label').addClass('disabled');
                    			jQuery('.field_days_sleeping_3 label').addClass('disabled');
                    			jQuery('.field_days_sleeping_4 label').addClass('disabled');
                    			jQuery('.field_days_sleeping_5 label').addClass('disabled');
                			}
                			else{
                    			jQuery('#days_sleeping_1').attr("disabled", false);
                    			jQuery('#days_sleeping_2').attr("disabled", false);
                    			jQuery('#days_sleeping_3').attr("disabled", false);
                    			jQuery('#days_sleeping_4').attr("disabled", false);
                    			jQuery('#days_sleeping_5').attr("disabled", false);
                    			jQuery('.field_days_sleeping_1 label').removeClass('disabled');
                    			jQuery('.field_days_sleeping_2 label').removeClass('disabled');
                    			jQuery('.field_days_sleeping_3 label').removeClass('disabled');
                    			jQuery('.field_days_sleeping_4 label').removeClass('disabled');
                    			jQuery('.field_days_sleeping_5 label').removeClass('disabled');
                			}
            			}
            			jQuery(document).ready(function()
            			{
                			jQuery('#days_sleeping').click(function()
                			{
                    			sleeping_logic();
                			});
                			
                			sleeping_logic();
            			});
                    </script>
        			<?php
        			
        			if ($_SESSION['customer']['participant']!="deltager")
        			{
            			?><p><strong><?php
                        __etm('Bemærk: Hvis du skal sove i arrangørsovesalen skal du også afkrydse ovenstående felt. Prisen for overnatning som arrangør er 100 kr for alle dage');
            			?></strong></p><?php
                    }
        			?>
                    <?php
                    ?><div class='grouped'><?php
        			renderFieldByType(array(
            			'id'=>'field_days_sleeping_1',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_sleeping_1',
            			'text'=>'Jeg overnatter onsdag/torsdag (60 kr)',
        			));
        			renderFieldByType(array(
            			'id'=>'field_days_sleeping_2',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_sleeping_2',
            			'text'=>'Jeg overnatter torsdag/fredag (60 kr)',
        			));
        			renderFieldByType(array(
            			'id'=>'field_days_sleeping_3',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_sleeping_3',
            			'text'=>'Jeg overnatter fredag/lørdag (60 kr)',
        			));
        			renderFieldByType(array(
            			'id'=>'field_days_sleeping_4',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_sleeping_4',
            			'text'=>'Jeg overnatter lørdag/søndag (60 kr)',
        			));
        			renderFieldByType(array(
            			'id'=>'field_days_sleeping_5',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_sleeping_5',
            			'text'=>'Jeg overnatter søndag/mandag (60 kr)',
        			));
                    ?></div><?php
                    ?>
                    
                    <p><?php __etm('<strong>Madras</strong><br>Du kan reservere en madras til brug under hele Fastaval. Prisen er 100 kr i leje for hele Fastaval. <strong>Bemærk: madrassen kan ikke medtages i arrangørsovesal eller ungdomssovesalen.</strong>');?></p>
                    
                    <?php
        			renderFieldByType(array(
            			'id'=>'field_days_rent_madras',
            			'input-type'=>'checkbox',
            			'input-name'=>'days_rent_madras',
            			'text'=>'Ja tak, jeg vil gerne leje en madras for 100 kr',
        			));
        			?>
                    
                    
                    
                    <p><?php __etm('');?></p>
                </div>
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("Næste side");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	<?php

        }
    }


