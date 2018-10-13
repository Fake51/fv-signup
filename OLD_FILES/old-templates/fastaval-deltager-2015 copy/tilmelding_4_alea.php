<?php
    
    class DeltagerTilmeldingAleaPage4 extends SignupPage
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

        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("Næste side");?>
                <?php tilm_form_prefields(); ?>

                <h1 class='entry-title'><?php __etm('Meld dig ind i Alea og få rabat på indgangen');?></h1>
                <div id='tilmelding-info'>
                    <p><?php __etm('<br/>
<br/>
ALEA er foreningen bag Fastaval. Ud over det praktiske, er foreningen også til for at videregive erfaringer og ressourcer fra år til år og sikre at der er en Fastaval.
<br><br>
Medlemsskab koster 75 kroner, og giver rabat på billetten til Fastaval.');?></p>
                    
                    <?php
        			renderFieldByType(array(
            			'id'=>'field1_17',
            			'input-type'=>'checkbox',
            			'input-name'=>'new_alea',
            			'text'=>'Ja tak, jeg vil gerne melde mig ind i ALEA, for 75 kr. Min indgangsbillet vil derfor koste 75 + 85 = 160 kr (normalt 210 kroner). Mit medlemskab af ALEA gælder indtil dagen før Fastaval næste år.',
            			'class'=>array('fullwidth-checkbox')
        			));
                    ?>                 
                     
                    <p><?php __etm('Hvis du ikke ønsker at være medlem af ALEA, skal du trykke på "Næste side"');?></p>
                    <h2><?php __etm('Hvorfor melde sig ind i Alea?');?></h2>
                    <p><?php __etm('Udover den dejlige rabat, hjælper du også Fastaval på den lange bane.
<br/>
<br/>
I år modtager Fastaval ikke kommunale støttekroner. Hvis deltagerne på Fastaval er medlemmer af foreningen som arrangerer kongressen, giver det på sigt mulighed for at søge et betragteligt kommunalt aktivitetstilskud. Med 500 medlemmer ville ALEA/Fastaval forhåbentligt også få større gennemslagskraft i forbindelse med vores samarbejde med kommunerne. Dette skulle gerne på sigt sikre dig som deltager en bedre (og måske billigere) Fastaval i fremtiden.');?></p>
                    <p><?php __etm('<a href="http://www.fastaval.dk/alea-det-med-smaat/" target="_blank">Læs alt det med småt</a>. Du kan også <a href="/om-fastaval/om-alea/" target="_blank">læse mere om Alea.</a>');?></p>
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


