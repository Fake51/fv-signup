<form method="post" action="./step-4">

	<style>
    	p label, ul li, p, td{font-size:14px;}
    	h2{font-size:16px;padding-top:15px;}
    	p input{height:25px !important;font-size:12px;}
    	p.field1_1 input{width:408px;}
    	p.field1_2 input{width:200px;}
    	p.field1_4 input{width:100px;}
    	p.field3_4 input{width:300px;}
    	.edit{width:400px;text-align:right;font-size:x-small;}
    	.edit a{color:black;}
	</style>

<?php
    function renderRows($billet,$posfix1, $postfix2)
    {
        $price =0;
        foreach($billet as $info){
            $value = tm_getForm($info[0]);
            if ($value==1){
                ?>
                <tr>
                    <td><?php echo $info[1].$postfix1;?></td>
                    <td><?php echo $info[2].$postfix1;?></td>
                </tr>
                <?php
                $price += $info[2];
            }
        }
        return $price;
    }
    
?>

<h1>Tilmeld dig Con2 (2/2)</h1>
    <p style='text-align:right;font-style:italic'>Forhåndstilmeldingen slutter d. <?php echo strftime("%e. %B %Y",$stopTime);?>, eller når vi når 65 deltagere</p>

    <div style='border:1px solid black; padding:10px; width:400px;'>
    <p style='text-align:center'>OBS !</p>
    <p>Gennemse din tilmelding og tryk på "Bekræft tilmelding" hvis dine oplysninger passer!<br><strong>Din tilmelding er først gældende når den er bekræftet</strong></p></div>

    <h2>Dine registrerede oplysninger:</h2>
    <p>
    <?php  echo tm_getForm("kontakt_1")?> <?php  echo tm_getForm("kontakt_2")?> <?php  echo tm_getForm("kontakt_3")?><br />
    <?php  echo tm_getForm("kontakt_4")?><br />
    <?php if (tm_getForm("kontakt_5")!=""){?>
    <?php  echo tm_getForm("kontakt_5")?><br />
    <?php }?>
    <?php  echo tm_getForm("kontakt_6")?> <?php  echo tm_getForm("kontakt_7")?><br />
    Tlf.: <?php  echo tm_getForm("kontakt_8")?><br />
    <?php if (tm_getForm("kontakt_9")!=""){?>
    <?php  echo tm_getForm("kontakt_9")?>
    <?php }?>
    </p>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
    <div style='clear:both'></div>
	
	
	<h2>Dine aktivitets-prioriteter:</h2>
	<?php
	
    $args = array(
        'post_type' => 'vc_turneringer2015',
        'post_status' => 'publish',
        'posts_per_page' => -1
    );
    $posts = get_posts($args); 
    foreach($posts as $post)
    {
        if (tm_getForm("prio_".$post->ID)!=""){
            echo tm_getForm("prio_".$post->ID).". : ".$post->post_title;
            echo "<br>";
        }
    }
	?>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
	
	<div style='clear:both'></div>
    <h2>Hvilke dage vil du spille rollespil:</h2>
    <table>
	<?
        $entre = array(
            array('blok_1','Blok 1 (fredag aften)', 'ja'),
            array('blok_2','Blok 2 (lørdag dag)', 'ja'),
            array('blok_3','Blok 3 (lørdag aften)', 'ja'),
            array('blok_4','Blok 4 (søndag dag)', 'ja')
        );
    	renderRows($entre,"","");
    	
	?>
	</table>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
	
	<h2>Antal aktiviteter i løbet af weekenden</h2>
	<p><?php echo tm_getForm("aktiviter_num");?></p>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
	<div style='clear:both'></div>
	
	<h2>Sjov under maden</h2>
	<p><?php echo (tm_getForm("fun_food")==1?"Ja":"Nej");?></p>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
	<div style='clear:both'></div>
	
	<h2>Jeg kommer tidligere og hjælper med maden</h2>
	<p><?php echo (tm_getForm("help_food")==1?"Ja":"Nej");?></p>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
	<div style='clear:both'></div>
	
	<h2>Vigtig information om dig til os</h2>
	<p><?php echo tm_getForm("vigtig_info");?></p>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
	<div style='clear:both'></div>
	
	<h2>Tager figurspil/terræn med på Con2?</h2>
	<p><?php echo tm_getForm("text_note1");?></p>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
	<div style='clear:both'></div>
	
	<h2>Vil du hjælpe crew'et med at tage en info/opsynsvagt i løbet af connen?</h2>
	<p><?php echo tm_getForm("text_note2");?></p>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
	<div style='clear:both'></div>
	
	<h2>Kan du være brandvagt?</h2>
	<p><?php echo tm_getForm("text_note3");?></p>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
	<div style='clear:both'></div>
    
    
    
    
    
    
    
	
    <h2>Meld dig som GM!</h2>
	<p><?php echo tm_getForm("gm_tjans");?></p>
	<p class='edit'><a href='./'>Rediger oplysninger</a></p>
	<div style='clear:both'></div>
	
	<?php echo (tm_getForm("email_info")==1?"<p>Jeg vil gerne holdes orienteret om fremtidige Con2-arrangementer</p>":""); ?>
	
	<div style='clear:both'></div>
    <h2>Betaling</h2>
    <p>Du skal betale:</p>
    <table id="regning">
        <?php 
            $price=0;
            
            $billet = array(
                array('day_1','Indgang fredag', 35),
                array('day_2','Indgang lørdag', 60),
                array('day_3','Indgang søndag', 35)
            );
            $price += renderRows($billet,""," kr");
            
            $mad = array(
                array('food_1','Mad fredag', 30),
                array('food_2','Mad lørdag', 50)
            );
            $price += renderRows($mad,""," kr");
            
        ?>
        <tr>
        	<td>I alt</td>
        	<td><?php  echo $price?> DKK</td>
        </tr>
    </table>
<br>
<p><strong>OBS:</strong></p>
<p>Du betaler din tilmelding til Con2 via bankoverførsel.</p>
<p>Betaling gennem netbank er sædvanligvis gratis, hvorimod det som regel koster lidt at betale i selve banken - alt efter dit pengeinstitut. N&aring;r du tilmelder dig vil du f&aring; en kvittering via email med disse informationer.</p>
<p><input class="button send" type="submit" name='action2' value="Bekræft tilmelding!" /></p>


</form>
<?php
	$_SESSION['customer']['price'] = $price;

?>