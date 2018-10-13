<?php
    
    class DeltagerTilmeldingMadPage6 extends SignupPage
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
                
                <h1 class='entry-title'><?php __etm('Mad');?></h1>
                <div id='tilmelding-info'>
                    
                    <p><?php __etm('Der er mulighed for fuld forplejning på Fastaval. Vores trofaste morgenmadshold søger for, at du har mulighed for at købe  morgenmad hver eneste dag. Skulle du blive sulten ind i mellem, er der også en velforsynet kiosk, og et supermarked lige rundt om hjørnet (som har åbent hele påsken og holder meget af rollespillere).');?></p>
                    <p><?php __etm('Herunder kan du bestille morgenmad og aftensmad:');?></p>
                    
                    <h3><?php __etm('Onsdag');?></h3>
                    <p><?php __etm('Vælg din aftensmad (55 kr):');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'brainfood_dinner_1',
            			'input-type'=>'select',
            			'input-name'=>'brainfood_dinner_1',
            			'text'=>'',
            			'value' => array(
                        			'0'=>'Nej tak',
                        			'129'=>'Ja tak',
                        			'134'=>'Ja tak (vegetarisk)',
                            ),
        			));
                    ?>
                    
                    <ul>
                        <li><?php __etm('Menu: Lasagne – tilsat grøntsager');?></li>
                        <li><?php __etm('Menu (vegetarisk): Vegetarlasagne med bønner og spirer.');?></li>
                    </ul>
                    
                    <h3><?php __etm('Torsdag');?></h3>
                    <p><?php __etm('Morgenmad (25 kr): (cornflakes, havregryn, yoghurt, frugt, groft og alm. brød, pålæg, kaffe, te)');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'breakfast_2',
            			'input-type'=>'select',
            			'input-name'=>'breakfast_2',
            			'text'=>'',
            			'value' => array(
                        			'0'=>'Nej tak',
                        			'141'=>'Ja tak'
                            ),
        			));
                    ?>
                    <p><?php __etm('Vælg din aftensmad (55 kr):');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'brainfood_dinner_2',
            			'input-type'=>'select',
            			'input-name'=>'brainfood_dinner_2',
            			'text'=>'',
            			'value' => array(
                        			'0'=>'Nej tak',
                        			'130'=>'Ja tak',
                        			'135'=>'Ja tak (vegetar)',
                            ),
        			));
                    ?>
                    <ul>
                        <li><?php __etm('Menu: Grillet kyllingebryst med brune ris og paprikasauce, pastasalat med pesto.');?></li>
                        <li><?php __etm('Menu (Vegetarisk): Paneret stegt jordnødderulle med brune ris og paprikasauce');?></li>
                    </ul>
                    
                    
                    <h3><?php __etm('Fredag');?></h3>
                    <p><?php __etm('Morgenmad (25 kr): (cornflakes, havregryn, yoghurt, frugt, groft og alm. brød, pålæg, kaffe, te)');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'breakfast_3',
            			'input-type'=>'select',
            			'input-name'=>'breakfast_3',
            			'text'=>'',
            			'value' => array(
                        			'0'=>'Nej tak',
                        			'142'=>'Ja tak'
                            ),
        			));
                    ?>
                    <p><?php __etm('Vælg din aftensmad (55 kr):');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'brainfood_dinner_3',
            			'input-type'=>'select',
            			'input-name'=>'brainfood_dinner_3',
            			'text'=>'',
            			'value' => array(
                        			'0'=>'Nej tak',
                        			'131'=>'Ja tak',
                        			'136'=>'Ja tak (vegetarisk)',
                            ),
        			));
                    ?>
                    <ul>
                        <li><?php __etm('Menu: Hakkebøf med bløde løg og hvide kartofler');?></li>
                        <li><?php __etm('Menu (vegetarisk): Linsefrikadeller med stegte grøntsager, ristede kartofler og sauce.');?></li>
                    </ul>
                    
                    <h3><?php __etm('Lørdag');?></h3>
                    <p><?php __etm('Morgenmad (25 kr): (cornflakes, havregryn, yoghurt, frugt, groft og alm. brød, pålæg, kaffe, te)');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'breakfast_4',
            			'input-type'=>'select',
            			'input-name'=>'breakfast_4',
            			'text'=>'',
            			'value' => array(
                        			'0'=>'Nej tak',
                        			'143'=>'Ja tak'
                            ),
        			));
                    ?>
                    <p><?php __etm('Vælg din aftensmad (55 kr):');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'brainfood_dinner_4',
            			'input-type'=>'select',
            			'input-name'=>'brainfood_dinner_4',
            			'text'=>'',
            			'value' => array(
                        			'0'=>'Nej tak',
                        			'132'=>'Ja tak',
                        			'137'=>'Ja tak (vegetarisk)',
                            ),
        			));
                    ?>
                    <ul>
                        <li><?php __etm('Menu: Kylling i karry grøntsager, med løse brune ris');?>.</li>
                        <li><?php __etm('Menu (vegetarisk): Fyldt pandekage med nøddepostej og tomatsauce.');?></li>
                    </ul>
                    
                    <h3><?php __etm('Søndag');?></h3>
                    <p><?php __etm('Morgenmad (25 kr): (cornflakes, havregryn, yoghurt, frugt, groft og alm. brød, pålæg, kaffe, te)');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'breakfast_5',
            			'input-type'=>'select',
            			'input-name'=>'breakfast_5',
            			'text'=>'',
            			'value' => array(
                        			'0'=>'Nej tak',
                        			'144'=>'Ja tak'
                            ),
        			));
                    ?>
                    <p><?php __etm('Vælg din aftensmad (55 kr):');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'brainfood_dinner_5',
            			'input-type'=>'select',
            			'input-name'=>'brainfood_dinner_5',
            			'text'=>'',
            			'value' => array(
                        			'0'=>'Nej tak',
                        			'133'=>'Ja tak',
                        			'138'=>'Ja tak (vegetarisk)',
                            ),
        			));
                    ?>
                    <ul>
                        <li><?php __etm('Menu: Frikadelle med pestokartofler og sauce');?></li>
                        <li><?php __etm('Menu (vegetarisk): Fyldt peberfrugt med pesto, hertil bulgursalat med bønner og grønt.');?></li>
                    </ul>
                    
                    
                    <h2><?php __etm('Ottofesten');?></h2>
                    <p><?php __etm('Ottofesten er Fastavals klimaks - det er her Ottoerne uddeles, det er her vi hylder hinanden, og det er her vi fyrer den sidste energi af mens vi fester løs.');?></p>
                    <p><?php __etm('Der er mulighed for at bestille mousserende vin til festen, formedelst 100 kr ekstra.');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'otto_party',
            			'input-type'=>'select',
            			'input-name'=>'otto_party',
            			'text'=>'Jeg kommer til ottofesten:',
            			'value' => array(
                			'0' => 'Nej tak',
                			'1' => 'Ja tak, indgangsbillet (80kr)',
                			'2' => 'Ja tak, indgangsbillet (80kr) + mousserende vin (100kr)',
            			),
            			'class'=> array('fullsize-label'),
        			));
        			?>
                    
                    
                    

                    
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


