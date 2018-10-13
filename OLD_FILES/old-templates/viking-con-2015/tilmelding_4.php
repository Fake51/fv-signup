<form method="post" action='/'>
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
    
	function createPinCode($seed){
		srand($seed);
		$val = (rand())%9999;
		while ($val<1000)$val = (rand())%9999;
		return $val;
	}
	
	$do_mail = false;
	if ($_SESSION['registered']!="1")
	{
		
		$title = tm_getForm("kontakt_1")." ".(tm_getForm("kontakt_2")!=""?tm_getForm("kontakt_2")." ":"").tm_getForm("kontakt_3")." (".tm_getForm("kontakt_9").")";
		$title = str_repeat('=',5-strlen($vcnr)).$vcnr." ".$title;
		
		$my_post = array(
			'post_type' => getDefines("TABLE_TILMELDINGER"),
			'post_title' => $title,
			'post_content' => '',
			'post_status' => 'publish',
			'post_author' => 1
		);
		
		$post_id = wp_insert_post( $my_post );
		$tilm_kode = createPinCode( $post_id );
		
		add_post_meta($post_id,'session_data',json_encode($_SESSION['customer']));
		
		add_post_meta($post_id,'fornavn',tm_getForm("kontakt_1"));
		add_post_meta($post_id,'mellemnavn',tm_getForm("kontakt_2"));
		add_post_meta($post_id,'efternavn',tm_getForm("kontakt_3"));
		
		add_post_meta($post_id,'adresse1',tm_getForm("kontakt_4"));
		add_post_meta($post_id,'adresse2',tm_getForm("kontakt_5"));
		
		add_post_meta($post_id,'adresse_postnr',tm_getForm("kontakt_6"));
		add_post_meta($post_id,'adresse_by',tm_getForm("kontakt_7"));
		
		add_post_meta($post_id,'telefon',tm_getForm("kontakt_8"));
		add_post_meta($post_id,'email',tm_getForm("kontakt_9"));

		add_post_meta($post_id,'day_1',tm_getForm("day_1"));
		add_post_meta($post_id,'day_2',tm_getForm("day_2"));
		add_post_meta($post_id,'day_3',tm_getForm("day_3"));
		
		add_post_meta($post_id,'food_1',tm_getForm("food_1"));
		add_post_meta($post_id,'food_2',tm_getForm("food_2"));


		add_post_meta($post_id,'blok_1',tm_getForm("blok_1"));
		add_post_meta($post_id,'blok_2',tm_getForm("blok_2"));
		add_post_meta($post_id,'blok_3',tm_getForm("blok_3"));
		add_post_meta($post_id,'blok_4',tm_getForm("blok_4"));
		
		add_post_meta($post_id,'fun_food',tm_getForm("fun_food"));
		add_post_meta($post_id,'vigtig_info',tm_getForm("vigtig_info"));
		add_post_meta($post_id,'gm_tjans',tm_getForm("gm_tjans"));


		add_post_meta($post_id,'help_food',tm_getForm("help_food"));
		add_post_meta($post_id,'text_note1',tm_getForm("text_note1"));
		add_post_meta($post_id,'text_note2',tm_getForm("text_note2"));
		add_post_meta($post_id,'text_note3',tm_getForm("text_note3"));
		
		add_post_meta($post_id,'email_info',tm_getForm("email_info"));
                
        
		add_post_meta($post_id,'kode',$tilm_kode);
		add_post_meta($post_id,'price',tm_getForm("price"));

        $args = array(
            'post_type' => 'vc_turneringer2015',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $turneringer = get_posts($args); 
        foreach($turneringer as $turnering_post)
        {
            if (tm_getForm("prio_".$turnering_post->ID)!=""){
                add_post_meta($post_id, "prio_".$turnering_post->ID, tm_getForm("prio_".$turnering_post->ID));
            }
        }
        
        
        $do_mail = true;

		ob_start();
		?>
		<h2>Du har forh&aring;ndstilmeldt dig til Con2!</h2>
		<fieldset id="data">
			<legend>Dine oplysninger:</legend>
			<p>
		     <?php  echo tm_getForm("kontakt_1")?> <?php  echo tm_getForm("kontakt_2")?> <?php  echo tm_getForm("kontakt_3")?><br />
		     <?php  echo tm_getForm("kontakt_4")?><br />
		     <?php if (tm_getForm("kontakt_5")!=""){?>
		          <?php  echo tm_getForm("kontakt_5")?><br />
		     <?php }?>
		     <?php  echo tm_getForm("kontakt_6")?> <?php  echo tm_getForm("kontakt_7")?><br />
		     Tlf.: <?php  echo tm_getForm("kontakt_8")?><br />
			<?php if (tm_getForm("kontakt_9")!=""){
		     	echo tm_getForm("kontakt_9");
			}?>
			<p>&nbsp;</p>
	
			<p><strong>Prioritetliste:</strong></p>
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

    	<div style='clear:both'></div>
        <h2>Dage tilmeldt con2:</h2>
        <table>
    	<?
            $entre = array(
                array('day_1','Indgang fredag', 'ja'),
                array('day_2','Indgang lørdag', 'ja'),
                array('day_3','Indgang søndag', 'ja'),
            );
        	renderRows($entre,"","");
    	?>
    	</table>

    	<div style='clear:both'></div>
        <h2>Mad tilmeldt con2:</h2>
        <table>
    	<?
            $entre = array(
                array('food_1','Mad fredag', 'ja'),
                array('food_2','Mad lørdag', 'ja'),
            );
        	renderRows($entre,"","");
    	?>
    	</table>


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
    	
    	<h2>Antal aktiviteter i løbet af weekenden</h2>
    	<p><?php echo tm_getForm("aktiviter_num");?></p>
    	<div style='clear:both'></div>
    	
    	<h2>Sjov under maden</h2>
    	<p><?php echo (tm_getForm("fun_food")==1?"Ja":"Nej");?></p>
    	<div style='clear:both'></div>
    	
    	<h2>Jeg kommer tidligere og hjælper med maden</h2>
    	<p><?php echo (tm_getForm("help_food")==1?"Ja":"Nej");?></p>
    	<div style='clear:both'></div>
    	
    	<h2>Vigtig information om dig til os</h2>
    	<p><?php echo tm_getForm("vigtig_info");?></p>
    	<div style='clear:both'></div>

    
    	<h2>Tager figurspil/terræn med på Con2?</h2>
    	<p><?php echo tm_getForm("text_note1");?></p>
    	<div style='clear:both'></div>
    	
    	<h2>Vil du hjælpe crew'et med at tage en info/opsynsvagt i løbet af connen?</h2>
    	<p><?php echo tm_getForm("text_note2");?></p>
    	<div style='clear:both'></div>
    	
    	<h2>Kan du være brandvagt?</h2>
    	<p><?php echo tm_getForm("text_note3");?></p>
    	<div style='clear:both'></div>
        	
    	
        <h2>Meld dig som GM!</h2>
    	<p><?php echo tm_getForm("gm_tjans");?></p>
    	<div style='clear:both'></div>





			<div style='clear:both'></div>
		</fieldset>
		
		<fieldset id="pay">
			<legend>Betaling</legend>
			
			<h2>Du skal betale: <?php echo $_SESSION['customer']['price']?> kr</h2>
			
			<?php /*<p>Dit Giro-kort nummer er:</p>
			<p style='background-color:white;border:2px solid black;text-align:center;padding:5px;'><?php echo displayOCR($vc_ocr); ?></p> <?php */?>
		</fieldset>
	
		<fieldset id="pay">
			<legend>S&aring;dan betaler du</legend>

            <p>Du betaler din tilmelding til Con2 via bankoverførsel (husk at anføre navnet i beskedfeltet):</p>
            <ul>
                <li><strong>Reg. nr.: 0400</strong></li>
                <li><strong>Konto nr: 4015748228</strong></li>
            </ul>
            <p>Betaling gennem netbank er sædvanligvis gratis, hvorimod det som regel koster lidt at betale i selve banken - alt efter dit pengeinstitut. N&aring;r du tilmelder dig vil du f&aring; en kvittering via email med disse informationer.</p>
            <p>&nbsp;</p>
            <p><strong>HUSK:</strong> Hvis du ikke har modtaget din registreringsmail, så kig i din spam/junk folder - ellers skriv til con2 (ha@con2.dk)</p>
		</fieldset>
		<br>
		<br>
		<br>
		<br>
		<?php
		
		$html_content = ob_get_contents();
		ob_end_clean();
 		echo $html_content;
	    $_SESSION['registered'] = 1;
	    $_SESSION['html_content'] = $html_content;
		add_post_meta($post_id,'email_html_content',$html_content);
     }
     else{
     	$html_content = $_SESSION['html_content'];
     	echo $html_content;
     }
     
	if ($do_mail)
	{
         include('phpmailer/class.phpmailer.php');
	     $mail = new PHPMailer();
//	     $mail->IsSMTP(); // telling the class to use SMTP
//	     $mail->Host       = "localhost"; // SMTP server
//	     $mail->IsQmail(); // telling the class to use Qmail
	     $mail->IsSendmail(); // telling the class to use Sendmail
	     $mail->CharSet = "UTF-8";
	     $mail->From       = "ha@con2.dk";
	     $mail->FromName   = "Con2";
	     $mail->Subject    = "[Con2] Tilmelding til Con2";
	     $mail->AltBody    = "Denne besked kr&aelig;ver at du kan se HTML. Kontakt ha@con2.dk hvis du ikke kan læse denne besked"; // optional, comment out and test
	     $mail->MsgHTML("<html><body>".$html_content."</body></html>");
	     $mail->AddAddress("ha@con2.dk", "Con2");
	     $mail->AddAddress(tm_getForm('kontakt_9'), "Con2 Deltager");
	     $mail->Send();
	}
     
     
?>
<p class="right"><input class="button send" type="submit" name='action2' value="Til forsiden" /></p>
</form>