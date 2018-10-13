<?php
    
    class DeltagerTilmeldingMoreInfoPage10 extends SignupPage
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
                <?php render_next_button("Gå til godkendelse");?>
                <?php tilm_form_prefields(); ?>
                
                <h1 class='entry-title'><?php __etm('Ekstra informationer');?></h1>
                
                <div id='tilmelding-info'>
                    
                    <h3><?php __etm('Sprog og oprindelse');?></h3>
                    <p><?php __etm('Hvilke sprog vil du gerne deltage i aktiviteter på:');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'other_dansk',
            			'input-type'=>'checkbox',
            			'input-name'=>'other_dansk',
            			'text'=>'Dansk',
        			));
        			renderFieldByType(array(
            			'id'=>'other_engelsk',
            			'input-type'=>'checkbox',
            			'input-name'=>'other_engelsk',
            			'text'=>'Engelsk',
        			));
        			renderFieldByType(array(
            			'id'=>'other_scandinavisk',
            			'input-type'=>'checkbox',
            			'input-name'=>'other_scandinavisk',
            			'text'=>'Skandinavisk generelt (forstår dansk/svensk/norsk)',
        			));
        			renderFieldByType(array(
            			'id'=>'simultantolk',
            			'input-type'=>'checkbox',
            			'input-name'=>'simultantolk',
            			'text'=>'Jeg vil gerne hjælpe med at simultantolke aktiviteter på dansk/engelsk for dem, der ikke kan begge sprog. Fastaval må gerne kontakte mig efter behov.',
        			));
        			renderFieldByType(array(
            			'id'=>'other_international',
            			'input-type'=>'checkbox',
            			'input-name'=>'other_international',
            			'text'=>'Jeg er international gæst (dvs. ikke dansk) og vil gerne være med i aktiviteter for internationale gæster.',
        			));
        			?>
        			
        			
                    
                    <h3><?php __etm('Fastaval 2016, rige onkler og andet');?></h3>
                    <?php
        			renderFieldByType(array(
            			'id'=>'other_2010',
            			'input-type'=>'checkbox',
            			'input-name'=>'other_2010',
            			'text'=>'Jeg har lyst til at være med til at arrangere Fastaval 2016',
        			));
        			renderFieldByType(array(
            			'id'=>'other_richbastard',
            			'input-type'=>'checkbox',
            			'input-name'=>'other_richbastard',
            			'text'=>'Jeg vil gerne være en af Fastavals rige tanter og onkler, og støtter hermed kongressen med 300 kr ekstra! Beløbet vil blive lagt til min endelige pris, og jeg vil blive nævnt med tak på Fastaval.dk samt på infoskærme under fastaval',
        			));
        			renderFieldByType(array(
            			'id'=>'other_secretbastard',
            			'input-type'=>'checkbox',
            			'input-name'=>'other_secretbastard',
            			'text'=>'Jeg vil gerne være en af Fastavals hemmelige rige tanter og onkler, og støtter hermed kongressen med 300 kr ekstra! Beløbet vil blive lagt til min endelige pris. Kun de indviede ved, hvor sej jeg er.',
        			));
        			
        			/*
        			renderFieldByType(array(
            			'id'=>'game_reallocation_participant',
            			'input-type'=>'checkbox',
            			'input-name'=>'game_reallocation_participant',
            			'text'=>'Jeg medbringer et par bræt- eller rollespil at give væk til "The Fastaval Re-Allocation Project" ',
        			));
        			*/
        			?>
                    
                    
                    
                    
                    <h3><?php __etm('Giv en hånd til klargøring');?></h3>
                    <?php
        			renderFieldByType(array(
            			'id'=>'ready_mandag',
            			'input-type'=>'checkbox',
            			'input-name'=>'ready_mandag',
            			'text'=>'Jeg vil gerne være med til at klargøre skolen fra mandag d. 30. marts kl. 12:00',
        			));
        			renderFieldByType(array(
            			'id'=>'ready_tirsdag',
            			'input-type'=>'checkbox',
            			'input-name'=>'ready_tirsdag',
            			'text'=>'Jeg vil gerne være med til at klargøre skolen fra tirsdag d. 31. marts kl. 12:00',
        			));
        			?>
                    
                    
                    
                    
                    <h3><?php __etm('Særlige skills');?></h3>
                    <p><?php __etm('Har du skills eller ting, som du gerne vil stille til rådighed for Fastaval undervejs? Har du eksempelvis håndværkerfaring, kan du køre truck eller lastbil, er du læge, sygeplejeske, falckredder eller brandmand? Har du en bil, som du godt vil køre folk på skadestuen i? Er du dygtig til et eller andet vi ikke anede vi kunne få brug for? Så skriv det her. Vi kontakter dig, hvis det brænder på.');?></p>
                    <p><?php __etm('Hvis du har nogle særlige skills du mener vi bør bruge lige nu og her, så skriv til os med det samme på <a href="mailto:info@fastaval.dk">info@fastaval.dk</a>');?></p>
        			<?php
            			renderFieldByType(array(
                			'id'=>'special_skills',
                			'input-type'=>'textarea',
                			'input-name'=>'special_skills',
                			'text'=>'',
            			));
                    ?>
                    
                    
                    
                    <h3><?php __etm('Bemærkninger');?></h3>
                    <?php __etm('<p>Her kan du skrive beskeder til Fastaval. Det kunne f.eks. være:</p>
<ul><li>Hvis du har kommentarer til dine GDS prioriteringer</li><li>Eller hvis du blot har lyst til at fortælle hvor meget du glæder dig til Fastaval</li></ul><p>Vi kigger først bemærkningerne igennem efter tilmeldingen er lukket. Så har du spørgsmål eller lignende, så send dem per mail på info@fastaval.dk eller stil dem på www.facebook.dk/fastaval. Ikke i dette felt.</p>');?>
        			<?php
            			renderFieldByType(array(
                			'id'=>'other_comments',
                			'input-type'=>'textarea',
                			'input-name'=>'other_comments',
                			'text'=>'',
            			));
                    ?>
                    
                </div>
                
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("Gå til godkendelse"); ?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	<?php

        }
    }



