<?php
    
    class DeltagerTilmeldingAktiviteterPage7 extends SignupPage
    {
        public function __construct(){
            include("modules/aktiviteter_v1.php");
        }
        public function init()
        {
        }
        
        public function validate()
        {
            $aktiviteter_v1 = new aktiviteter_v1();
            
			$aktiviteter = $aktiviteter_v1->loadAktiviteter();
			$afviklinger = $aktiviteter_v1->loadAfviklinger($aktiviteter);
			
			$_SESSION['customer']['aktiviteter_is_spilleder'] = 0;
			
			foreach($afviklinger as $afvikling)
			{
				if ($_SESSION['customer']['event_'.$afvikling['afvikling_id']]==5)
					$_SESSION['customer']['aktiviteter_is_spilleder'] = 1;
			}
			
            return true;
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
                
                <h1 class='entry-title'><?php __etm('Aktiviteter');?></h1>
                <div id='tilmelding-info'>
                    
                    <?php
                        __etm('<p>Nu bliver det langhåret! Her skal du vælge og prioritere de aktiviteter du er interesseret i at deltage i på Fastaval.</p>
<h3>Typer af aktiviteter</h3>
<p>Hver aktivitet på oversigten har en farvekode:</p>
<ul>
<li>Grøn = Bordrollespil</li>
<li>Lilla = Liverollespil</li>
<li>Rød = figurspil</li>
<li>Blå = Brætspil</li>
<li>Mørkegrøn = Magic</li>
<li>Gul = Events & Workshops.</li>
</ul>');
                    ?>

<?php __etm('<h3>Hvordan du prioriterer</h3>
<ul>
<li>Klik på de enkelte aktivitetsnavne for at se en foromtale/beskrivelse</li>
<li>Du prioriterer ved at klikke på på det grå felt i perioden</li>
<li>1-4 indikerer din prioritering (1 er højest, 3 lavest, 4 betyder \'hvis jeg ikke kommer på noget af det andet). \'SL\' betyder at du gerne vil være spilleder på aktiviteten.</li>
<li>For hver blok kan du vælge en 1., 2. 3. prioritet.</li>
<li>Du kan vælge så mange 4+ prioriteter du har lyst til</li>
<li>Der er heller ingen begrænsning på hvor mange gange du vælger at være spilleder på en dag. Vi elsker spilledere! Du kan dig sætte et max. på (se længere nede)</li>
<li>Forvirret? <a href="http://www.fastaval.dk/ofte-stillede-spoergsmaal-om-tilmeldingen/" target="_blank" style="color:#b00;">Der er hjælp at hente her (åbner i nyt vindue)</a></li>
</ul>');?>

<?php 
__etm('<h3>Tænker du på at være spilleder?</h3>
<p>Otto elsker spilledere. Derfor får alle Fastavals spilledere en gave i Check-In når de ankommer til Fastaval. Derudover får man en BONUS VOUCHER for hvert scenarie/aktivitet man spilleder, eller event/workshop man afholder. Denne kan på Fastaval indløses til én af følgende goder:</p>
<ul>
<li>Frokost: 1 sandwich + 1 sodavand (Kiosk)</li>
<li>Sukkerchok: 1 toast + 1 chokobar el. slikpose + 1 sodavand (Kiosk)</li>
<li>Tømmermænd: 1 øl (Bar)</li>
<li>+ en krammer fra nærmeste Fastavalgænger (kan indløses hvor som helst)</li>
</ul>');
?>



<script>
    jQuery(document).ready(function()
    {
        
        jQuery(".type-selector").each(function(){
            var day = jQuery(this).data("day");
            var all_types = ['rolle','braet','live','workshop','figur', 'ottoviteter'];
            
            for(i=0;i<all_types.length;i++)
            {
                var this_type = all_types[i];
                var type_count = jQuery('#aktiviteter table.table-day-'+day+' tr.row-type-'+this_type).size();
                if (type_count==0){
                    jQuery('.type-selector.selector-day-'+day+' li[data-type="'+this_type+'"]').hide();
                }
            }
        })
        
        jQuery(".type-selector li").click(function()
        {
            var day = jQuery(this).data("day");
            var all_types = ['rolle','braet','live','workshop','figur','ottoviteter'];
            var show_type = jQuery(this).data('type');
            
            jQuery('.type-selector.selector-day-'+day+' li').removeClass('selected');
            jQuery('.type-selector.selector-day-'+day+' li[data-type="'+show_type+'"]').addClass('selected');
            
            
            if (show_type==""){
                jQuery('#aktiviteter table.table-day-'+day+' tr.row-with-game').removeClass('hidden');
            }
            else
            {
                jQuery('#aktiviteter table.table-day-'+day+' tr.row-with-game').addClass('hidden');
                jQuery('#aktiviteter table.table-day-'+day+' tr.row-type-'+show_type).removeClass('hidden');
            }
            
            /*
            for(i=0;i<all_types.length;i++)
            {
                var this_type = all_types[i];
                if ((this_type==show_type)||(show_type==""))
                {
                    jQuery('#aktiviteter table.table-day-'+day+' tr.row-type-'+this_type).removeClass('hidden');
                }
                else{
                    jQuery('#aktiviteter table.table-day-'+day+' tr.row-type-'+this_type).addClass('hidden');
                }
            }
            */
        });
    });
</script>

<?php
    $aktiviteter_v1 = new aktiviteter_v1();
    $aktiviteter_v1->render();
?>



                    <p><?php __etm('Der er i år mulighed for at vælge et maksimalt antal rollespil og brætspil man kommer til at spille:');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'max_games',
            			'input-type'=>'select',
            			'input-name'=>'max_games',
            			'text'=>'Antal spil jeg højst vil spille:',
            			'value' => array(
                			'0' => 'Ingen grænse',
                			'1' => 'Maksimum 1',
                			'2' => 'Maksimum 2',
                			'3' => 'Maksimum 3',
                			'4' => 'Maksimum 4',
                			'5' => 'Maksimum 5',
                			'6' => 'Maksimum 6',
                			'7' => 'Maksimum 7',
                			'8' => 'Maksimum 8',
                			'9' => 'Maksimum 9',
                			'10' => 'Maksimum 10',
            			),
            			'class'=> array('fullsize-label'),
        			));
                    ?>
                    
                    
                    <h2><?php __etm('HelCon');?></h2>
                    <p><?php __etm('I år vender vi blikket mod middelalderens europa, hvor rigfolk og magthavere kæmper om magten, mens paven kæmper mod dæmoner om de levendes sjæle! ');?></p>
                    <p><?php __etm('Dette års HelCon ligger i og udenfor spilblokke. Da man spiller i hold og andre afhænger af en forventes det at man som minimum møder op til disse fastsatte tidspunkter (onsdag aften og lørdag eftermiddag), dertil kan man spille videre udenfor blokkene så meget som man lyster.');?></p>
                    <p><?php __etm('Læs mere på <a href="http://www.fastaval.dk/aktivitet/helcon-komme-dit-rige/" target="_blank">www.fastaval.dk/aktivitet/helcon-komme-dit-rige/</a>');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'event_helcon',
            			'input-type'=>'checkbox',
            			'input-name'=>'event_helcon',
            			'text'=>'Ja, jeg vil gerne deltage i årets HelCon',
        			));
        			?>
                    
                    
                    <h2><?php __etm('Scenarieskrivningskonkurrence');?></h2>
                    <p><?php __etm('Hvor svært kan det være at skrive et scenarie? Vi gør det lidt sværere – skriv et scenarie under Fastaval med bundne krav til form og indhold. Der er en præmie for bedste scenarie, og detaljeret feedback til alle, der afleverer. Tør du stille op? <a href="http://www.fastaval.dk/aktivitet/fastaval-scenarieskrivningskonkurrence-2015/" target="_blank">Læs mere her.</a>');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'scenarieskrivningskonkurrence',
            			'input-type'=>'checkbox',
            			'input-name'=>'scenarieskrivningskonkurrence',
            			'text'=>'Ja, jeg vil gerne være med i scenarieskrivningskonkurrencen',
        			));
        			?>
                    
                    
                    
                    <h2><?php __etm('Byg et brætspil');?></h2>
                    <p><?php __etm('Hvor svært kan det være at lave et brætspil? Find ud af det på Fastaval! <a href="http://www.fastaval.dk/aktivitet/byg-et-braetspil-3/" target="_blank">Læs mere her</a>');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'boardgame_competition',
            			'input-type'=>'checkbox',
            			'input-name'=>'boardgame_competition',
            			'text'=>'Ja, jeg vil gerne prøve at bygge et brætspil (20 kr)',
        			));
        			?>
                     
                    
                    <h2><?php __etm('Er du karma-hardcore spilleder?');?></h2>
                    <?php
        			renderFieldByType(array(
            			'id'=>'may_contact',
            			'input-type'=>'checkbox',
            			'input-name'=>'may_contact',
            			'text'=>'Ja, absolut. Fastaval må gerne kontakte mig, hvis der mangler ekstra spilledere (kan vælges uanset om du allerede har valgt at være spilleder i oversigten)',
        			));
        			?>
        			
                </div>
                <?php render_next_button("Næste side"); ?>
                <?php tilm_form_postfields(); ?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	<?php

        }
    }


