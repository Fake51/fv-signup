<?php
    $my_errorHandler = array(
        'special_area' => array(
            'rules'=>"nonempty"
        ),
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


	<form method="post" action="./step-4" onSubmit='return validate_form(this);'>
    	
        <h1><?php __etm('Specielt for arrangører, forfattere og designere')?></h1>
        <div id='tilmelding-info'>
            <p><?php __etm('På vegne af alle deltagerne: Tusind tak for dit engagement i Fastaval 2015! Vi elsker dig!'); ?></p>
            
    		<?php
        		
        		renderFieldByType(array(
        			'id'=>'field1_1',
        			'input-type'=>'text',
        			'input-name'=>'special_area',
        			'text'=>'Hvad er dit arbejdsområde?:',
        			'class' => array('fullsize-label'),
        		));
        		
        		renderFieldByType(array(
        			'id'=>'field1_2',
        			'input-type'=>'text',
        			'input-name'=>'special_game',
        			'text'=>'Hvis du er forfatter/brætspilsdesigner, hvad er titlen på dit scenarie/spil:',
        			'class' => array('fullsize-label'),
        		));
    		
            ?>
        
            
            
            <h2><?php __etm('Om rabatterne'); ?></h2>
            
            <p><?php __etm('Selvom Fastaval aldrig vil kunne tilbagebetale alt det fantastiske det arbejde som alle vores mange forfattere og arrangører lægger for dagen for at skabe en fed oplevelse for alle deltagerne, så har vi alligevel valgt at yde en mindre kompensation til dem der lægger rigtigt mange timer i Fastaval.');?></p>
            
            
            <p><strong><?php __etm('ALLE arrangører og forfattere modtager:');?></strong></p>
            <ul>
                <li><?php __etm('ID kort der giver rabat i kiosk, café og bar');?></li>
                <li><?php __etm('Wear til indkøbspris og med ”CREW” logo');?></li>
            </ul>
            
            
            <p><strong><?php __etm('Arrangører og forfattere der arbejder mere end 40 timer før/efter Fastaval og/eller 24 timer på Fastaval modtager derudover:');?></strong></p>
            <ul>
                <li><?php __etm('Rabat på indgang samt overnatning på Fastaval');?></li>
            </ul>
            
            <p><strong><?php __etm('Infonauter modtager derudover også:');?></strong></p>
            <ul>
                <li><?php __etm('En tjekket, orange Infonaut T-shirt');?></li>
            </ul>
            
            
            <h2><?php __etm('Et scenarie, flere forfatterne?'); ?></h2>
            <p><?php __etm('Vi kan desværre kun tilbyde rabat på indgang og overnatning til en person per scenarie eller brætspil, men I kan frit dele byttet mellem jer. Alle forfattere/designere får rabatkort og merchandise til indkøbspris, og megen kærlighed fra Fastaval. Det er vigtigt at I alle tilmelder jer som forfattere/designere, ellers går det i kuk. Vi skal nok efterregulere jeres pris.');?></p>
            
            
            
            <h2><?php __etm('Arrangørsovesalen'); ?></h2>
            <p><?php __etm('Vi har som altid begrænset plads i Arrangørsovesalene, og derfor fordeles pladserne efter størst behov. Sovepladserne er fortrinsvis til folk, der på grund af nattevagter eller lignende har brug for ro til at sove på vilkårlige tidspunkter af døgnet, såsom kioskpersonale, Infonauter og bar- eller cafépersonale.');?></p>

    		<?php
        		
        		renderFieldByType(array(
        			'id'=>'field1_3',
        			'input-type'=>'checkbox',
        			'input-name'=>'special_sleeping',
        			'text'=>'Jeg vil gerne have en plads i arrangørsovesalen:',
        		));
    		
            ?>
                
        </div>
        <?php render_next_button("Næste side");?>
	</form>
	
	<form method="post" action="./step-2">
        <?php render_previous_button("Forrige side");?>
	</form><?php

