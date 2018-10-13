<?php 

	
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
            return true;
	     }
    </script>
    
	<form method="post" action="./step-5" onSubmit='return validate_form(this);'>
    	
        <h1><?php __etm('Godkend din tilmelding')?></h1>
        <div id='tilmelding-info'>
            <h2><?php __etm('Vigtigt! Din tilmelding er IKKE registreret endnu. Gå til næste side for at registrere dig. På denne side verificerer og godkender du din tilmelding.<br><br>Du kan benytte knappen "Forrige side" nederst for at gå tilbage og rette.');?></h2>
            
            <?php
                
                function render_headline($text){
                    ?>
                    <tr>
                        <td valign='top' colspan='2' class='headline'><?php __etm($text);?></td>
                    </tr>
                    <?php
                }
                
                function render_value($args){
                    
                    $text = $args['text'];
                    $value = $args['value'];
                    
                    
                    $visible = true;
                    if (isset($args['visible']))$visible=($args['visible']=="1");
                    
                    $value_translate = array();
                    if (isset($args['value-translate']))$value_translate=$args['value-translate'];
                    if (isset($value_translate[$value]))
                        $value = $value_translate[$value];
                    
                    $value_translate_default = null;
                    if (isset($args['value-translate-default']))$value_translate_default = $args['value-translate-default'];
                    if ((trim($value)=="")||($value==null))
                        $value = $value_translate_default;

                    if ($visible)
                    {
                        ?>
                        <tr>
                            <td valign='top' colspan='1' class='text'><?php __etm($text);?></td>
                            <td valign='top' colspan='1' class='value'><?php __etm($value);?></td>
                        </tr>
                        <?php
                    }
                }
                
            ?>
            <table id='info-table'>
                <?php
                    render_headline("Kontaktoplysninger");
                    
                    
                    render_value(array(
                        'text'=>'Fornavn',
                        'value'=>gf('firstname'),
                    ));
                    render_value(array(
                        'text'=>'Efternavn',
                        'value'=>gf('lastname'),
                    ));
                    render_value(array(
                        'text'=>'Adresse',
                        'value'=>gf('address1')."<br/>".gf('address2'),
                    ));
                    render_value(array(
                        'text'=>'Postnr',
                        'value'=>gf('zipcode'),
                    ));
                    render_value(array(
                        'text'=>'By',
                        'value'=>gf('city'),
                    ));
                    render_value(array(
                        'text'=>'Land',
                        'value'=>gf('country'),
                    ));
                    render_value(array(
                        'text'=>'Mobil',
                        'value'=>gf('mobile'),
                    ));
                    
                    
                    
                    render_value(array(
                        'text'=>'Medbringer Mobil',
                        'value'=>gf('bringing_mobile'),
                        'value-translate'=>array('1'=>'Ja'),
                        'value-translate-default'=>'Nej',
                    ));
                    render_value(array(
                        'text'=>'Køn',
                        'value'=>gf('sex'),
                    ));
                    render_value(array(
                        'text'=>'Fødselsdag',
                        'value'=>gf('birthday-day')."/".gf('birthday-month')."/".gf('birthday-year'),
                    ));
                    render_value(array(
                        'text'=>'E-mail',
                        'value'=>gf('email'),
                    ));
                    
                    
/*
                    
                    render_value(array(
                        'text'=>'Jeg er arrangør',
                        'value'=>gf('participant1'),
                        'visible'=>gf('participant1'),
                        'value-translate'=>array('1'=>'Ja'),
                    ));
                    render_value(array(
                        'text'=>'Jeg er forfatter',
                        'value'=>gf('participant2'),
                        'visible'=>gf('participant2'),
                        'value-translate'=>array('1'=>'Ja'),
                    ));
                    render_value(array(
                        'text'=>'Jeg er Infonaut',
                        'value'=>gf('participant3'),
                        'visible'=>gf('participant3'),
                        'value-translate'=>array('1'=>'Ja'),
                    ));
                    render_value(array(
                        'text'=>'Jeg er Dirtbuster',
                        'value'=>gf('participant4'),
                        'visible'=>gf('participant4'),
                        'value-translate'=>array('1'=>'Ja'),
                    ));
*/
                    
                    render_value(array(
                        'text'=>'Min rolle',
                        'value'=>gf('participant'),
                        'value-translate'=>array(
                    			'arrangoer'=>'Jeg er arrangør',
                    			'forfatter'=>'Jeg er forfatter / brætspilsdesigner',
                    			'infonaut'=>'Jeg er Infonaut',
                    			'dirtbuster'=>'Jeg er Dirtbuster',
                    			'brandvagt'=>'Jeg er Brandvagt',
                    			'kioskninja'=>'Jeg er Kioskninja',
                        ),
                    ));
                    
                    
                    render_value(array(
                        'text'=>'På Fastaval med klub/ungdomsskole',
                        'value'=>gf('with_club'),
                        'visible'=>gf('with_club'),
                        'value-translate'=>array('1'=>'Ja'),
                    ));
                    render_value(array(
                        'text'=>'Navn på klub/ungdomsskole',
                        'value'=>gf('club_name'),
                        'visible'=>gf('with_club'),
                    ));
                    
                    
                    
                    render_value(array(
                        'text'=>'Telefonnummer til nødstilfælde',
                        'value'=>gf('alternative_phone'),
                    ));
                    render_value(array(
                        'text'=>'Information til nødstilfælde',
                        'value'=>gf('other_health'),
                    ));

                    
                    
                    render_headline("Specielt for arrangører, forfattere og designere");
                    render_value(array(
                        'text'=>'Dit arbejdsområde',
                        'value'=>gf('special_area'),
                    ));
                    render_value(array(
                        'text'=>'Titel på dit spil/scenarie',
                        'value'=>gf('special_game'),
                    ));
                    render_value(array(
                        'text'=>'Plads i arrangørsovesal',
                        'value'=>gf('special_sleeping'),
                        'visible'=>gf('special_sleeping'),
                        'value-translate'=>array('1'=>'Ja'),
                    ));
                    
                    
                ?>
            </table>
            
        </div>
        <?php render_next_button("Tilmeld");?>
	</form>
	
	<form method="post" action="./step-3">
        <?php render_previous_button("Forrige side");?>
	</form><?php

