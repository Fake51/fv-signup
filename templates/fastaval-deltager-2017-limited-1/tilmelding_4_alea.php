<?php
    
    class DeltagerTilmeldingAleaPage4 extends SignupPage
    {
        public function getSlug()
        {
            return 'alea';
        }
        
        public function init()
        {
        }
        
        public function canShow()
        {
            if (isset($_SESSION['customer']['is_package']) && ($_SESSION['customer']['is_package']==1))
                return false;
            if ($_SESSION['customer']['participant']=='deltagerjunior')
                return false;
            
            return true;
        }
        
        public function render()
        {
            ?>

        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("general_next_page");?>
                <?php tilm_form_prefields(); ?>

                <h1 class='entry-title'><?php __etm('page3_text1');?></h1>
                <div id='tilmelding-info'>
                    <p><?php __etm('page3_text2');?></p>
                    
                    <?php
        			renderFieldByType(array(
            			'id'=>'field1_17',
            			'input-type'=>'checkbox',
            			'input-name'=>'new_alea',
            			'text'=>'page3_text3',
            			'class'=>array('fullwidth-checkbox')
        			));
                    ?>                 
                     
                    <p><?php __etm('page3_text4');?></p>
                    <h2><?php __etm('page3_text5');?></h2>
                    <p><?php __etm('page3_text6');?></p> 

<!--
                    <h2><?php __etm('Dit medlemskab styrker Fastaval på den lange bane');?></h2>
                    <p><?php __etm('Fastavals udgifter betales ikke kun af deltagerne, men også af de sponsorater og den støtte vi søger fra kommuner og virksomheder. Hvis deltagerne på Fastaval er medlemmer af foreningen som arrangerer kongressen, giver det bedre muligheder for at søge tilskud, samt større gennemslagskraft i forbindelse med vores samarbejde med kommunerne. Dette er med til at sikre Fastaval i fremtiden.');?></p>

-->
                    <p><?php __etm('nocat_142');?></p>
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


