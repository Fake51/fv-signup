<?php 

     function my_sorter($a,$b) {
     	
     	$a_start_module = $a['modules'][0];
     	$b_start_module = $b['modules'][0];
     	
     	$a_start_module_num = str_replace("S","3",str_replace("L","2",str_replace("F","1",$a_start_module)));
     	$b_start_module_num = str_replace("S","3",str_replace("L","2",str_replace("F","1",$b_start_module)));
     	
     	
          return $a_start_module_num>$b_start_module_num;
     }
	function listDB($type){
		
		$args = array(	'numberposts'=>-1,
				'order_by'=>'title',
				'order'=>'ASC',
				'post_type'=>getDefines("TABLE_TURNERINGER"),
				'taxonomy' => 'aktivitetstype',
				'term' => $type,
				);
		$games = get_posts($args);
		
		$events = array();
		
		foreach($games as $game)
		{
		    
		    $kan_tilmeldes = get_post_meta($game->ID,'kan-tilmeldes',true);
		    if ($kan_tilmeldes=="nej")continue;
		    
	     	$name = $game->post_title;
	     	$modules = json_decode(strtolower(get_post_meta($game->ID,'afviklinger',true)),false);
	     	$code = $game->ID;//get_post_meta($game->ID,'forkortelse',true);
			
			$content = $game->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
	     	
	     	$foromtale = $content;
	     	$foromtale_extra = "";
			$resource_url = "";
			
			foreach($modules as $day_module)
			{
				$event = array();
				$event['name'] = $name;
				$event['modules'] = $day_module;
				$event['code'] = $code;
				$event['foromtale'] = $foromtale;
				$event['foromtale_extra'] = $foromtale_extra;
				$event['resource_url'] = $resource_url;
				$event['type'] = $type;
				$events[] = $event;
			}
		}
	     usort($events, "my_sorter");
		
		foreach($events as $event)
		{
	     	renderGameRow($event['name'],$event['modules'],$event['code'],$event['type'],$event['foromtale'],$event['foromtale_extra'],$event['resource_url']);
	     }
	     
	}


	function renderPressBox($id,$part){
		?><div class='pressbox' onMouseDown='return false;' id='box_<?php  echo $id?>_<?php  echo $part?>'>&nbsp;</div><?php 
	}

	function renderGameRow($name,$modules,$code,$type,$wysiwyg="",$wysiwyg_extra="",$resource_url=null){
		$card_name = ('gamecard_'.$code);
		$star = false;
		$day = "";
		if (isset($_COOKIE[$card_name])&&($_COOKIE[$card_name]=="1"))
		{
			//$modules;
			if (preg_match("@[F]@",strtoupper(implode(",",$modules)))>0)$day='friday';
			else if (preg_match("@[L]@",strtoupper(implode(",",$modules)))>0)$day='saturday';
			else if (preg_match("@[S]@",strtoupper(implode(",",$modules)))>0)$day='sunday';
			$star = true;
		}
		
		?>
		<tr>
			<td class='<?php  echo $day?> name'>
				<!-- <a href='#' class='dropdown' title='Vis mere information' onClick='eventInformation("<?php  echo md5($code)?>");return false;'></a>-->
				<?php  echo ($star?"<img src='http://www.viking-con.dk/wp-content/themes/vikingcon/img/".$day."_checked.jpg'>":"")?>
				
				<?php
				if (($resource_url=="")||($resource_url==null)){
					?><a href='#' title='Vis mere information' onClick='eventInformation("<?php echo md5($code);?>");return false;'><?php echo $name;?></a><?php
				}else{
					?><a target='_blank' href='<?php  echo $resource_url?>'><?php  echo $name?></a><?php
				}
				
				if ($type=="rollespil")
				{
					?>
					<input type='checkbox' class='gm-selector' name='gm_<?php echo $code;?>' value='<?php echo $code;?>' id='gm_<?php echo $code;?>' style='float:right;margin-top:4px;margin-right:10px;'>
					<?php
				}
			?>
				
			</td>
			<?php 
			$layouts = array('f1','f2','f3','f4','pause','l1','l2','l3','l4','l5','l6','l7','l8','pause','s1','s2','s3','s4');
			foreach($layouts as $layout){
				$day = substr($layout,0,1);
				if ($layout=="pause"){
					?><td class='break'></td><?php 
				}else{
					?><td class='modul <?php  echo ($day=="f"?"mfriday":($day=="l"?"msaturday":($day=="s"?"msunday":"")))?> modul_<?php  echo $layout?>'><?php  echo (in_array($layout,$modules)?renderPressBox($code,$layout):"")?></td><?php 
				}
			}
			?>
		</tr>
		<tr>
			<td colspan='20' class='event-information' id='event_information_<?php  echo md5($code)?>' style='display:none;'><div class='w1'><div class='w2'>
				<div class='info-segment'>
					<?php  echo stripslashes($wysiwyg_extra)?>
				</div>
				<div class='content'>
					<h2><?php  echo $name?></h2>
					<?php  echo stripslashes($wysiwyg)?>
				</div>
				<div style='clear:both'></div>
			</div></div></td>
		</tr>
		<script>
			registerGame("<?php  echo $code?>");
		</script>
		<?php 
	}









?>
<h2>Forh&aring;ndstilmeld dig (Trin 2 af 3)</h2>
          <p style='text-align:right;font-style:italic'>Forhåndstilmeldingen slutter d. <?php echo strftime("%e. %B %Y",$stopTime);?></p>
<form method="post" action="./step-3">

<fieldset id="tournaments">
	<legend>Tilmeld dig aktiviteter - brætspil og rollespil</legend>
	<p>
		V&aelig;lg en aktivitet, enten rollespil eller brætspil, i tabellen nedenfor. Du har <strong>i alt 20 prioriteter</strong> som du kan fordele ud over de forskellige dage og på forskellige turneringer/scenarier.
	</p>
	<p>&nbsp;</p>
	<p>
		<strong>Fremgangsmåde</strong><br>
		Klik på en af de mørkegrå stribede bokse for at vælge en prioritet. Venstre klik nedprioriterer dit valg, og højre klik opprioriteter. "1." betyder at det er din første-prioritet at spille netop dette valg i den pågældende blok, og din "1." prioritet vil altid forsøges at blive fyldt op før din "2." prioritet. Ligeledes er "2." prioritet vigtigere end din "3." prioritet og så fremdeles.
	</p>
	<p>&nbsp;</p>
	<p>
		<strong>Få ét ønske opfyldt: </strong><br/>Hvis du tilmelder dig et hold som spilleder (rollespil) eller turneringsleder (brætspil(, får du en af dine 1. prioriteter (eller tilsvarende) opfyldt med garanti!
	</p>
	<p>&nbsp;</p>
	<p>
		<strong>Overblik:</strong><br/>
		Hvis du vil læse alle foromtalerne til scenarierne eller beskrivelserne af spillene, besøg nedenstående to sider:<br>
		<a href="http://www.viking-con.dk/aktiviteter/rollespil-pa-viking-con/" target="_blank">L&aelig;s foromtalerne til rollespil</a><br />
		<a href="http://www.viking-con.dk/aktiviteter/strategispil/" target="_blank">L&aelig;s beskrivelserne af strategispil</a>
	</p>
	<p>&nbsp;</p>
	
<script>
	
	var selectedGames = {};
	var gameList = Array();

	function nextValue(me){
		var myValue = selectedGames[me];
		if (myValue==20)return 0;
		
		var nextValue = myValue;
		for(var i=myValue+1;i<=21;i++){
			if (i==21)return 0;
			var stop = true;
			for(var j=0;j<gameList.length;j++){
				var id=gameList[j];
				var value = selectedGames[id];
				if (value==i)
					stop = false;
			}
			if (stop)
				return i;
		}
		
		if (nextValue==0)nextValue=0;
		return nextValue;
	}
	function prevValue(me){
		var myValue = selectedGames[me];
		if (myValue==0)return 20;
		
		var prevValue = myValue;
		for(i=myValue-1;i>=0;i--){
			if (i==0)return 0;
			var stop = true;
			for(var j=0;j<gameList.length;j++){
				var id=gameList[j];
				var value = selectedGames[id];
				if (value==i)
					stop = false;
			}
			if (stop)
				return i;
		}
		if (prevValue==20)prevValue=0;
		return prevValue;
	}
	function removeSpillederArray(code){
		var str = $('#choice_21').value;
		var sl_list = str.split(',');
		var new_list = Array();
		for(var i=0;i<sl_list.length;i++){
			if (sl_list[i]!=code)
				new_list[new_list.length] = sl_list[i];
		}
		$('#choice_21').value = new_list.join(",");
	}
	function addSpillederArray(code){
		var str = $('#choice_21').value;
		var str_list = Array();
		if (str!="")
			str_list = str.split(',');
		str_list[str_list.length] = code;
		$('#choice_21').value = str_list.join(",");
	}
	function clickPressBox(id,rightClick)
	{
		var current_prio = selectedGames[id];
		
		if (current_prio==21){
			removeSpillederArray(id);
		}
		else
			if ($('#choice_'+current_prio))
				$('#choice_'+current_prio).val("");
		
		if (rightClick)
			current_prio = prevValue(id);
		else
			current_prio = nextValue(id);

		if (current_prio>20)
			current_prio = 0;
		if (current_prio<0)
			current_prio = 20;
		
		selectedGames[id] = current_prio;
		
		if (selectedGames[id]==0)
			deselectPressBox(id);
		else{
			if (selectedGames[id]!=21)
				$('#choice_'+selectedGames[id]).val(id);
			else{
				// gør noget ved sl - den er lidt tricky...
				addSpillederArray(id);
			}
			selectPressBox(id);
		}
	}
	
	
	
	function registerGame(id)
	{
		gameList[gameList.length] = id;
		selectedGames[id] = 0;
		registerListener(id);
		
	}

	var con_blocks = Array('f1','f2','f3','f4','l1','l2','l3','l4','l5','l6','l7','l8','s1','s2','s3','s4');
	function registerListener(id)
	{
		for(var i=0;i<con_blocks.length;i++)
		{
			var obj = $("#box_"+id+"_"+con_blocks[i]);
			if (obj!=null)
			{
				$("#box_"+id+"_"+con_blocks[i]).bind("contextmenu",function(event)
				{
					clickPressBox(id,true);
				   	return false;
				});
                
				$("#box_"+id+"_"+con_blocks[i]).click(function(event)
				{
					clickPressBox(id,false);
					return false;
				});
			}
		}
	}
	function selectPressBox(id)
	{
		for(var i=0;i<con_blocks.length;i++){
			var obj = jQuery("#box_"+id+"_"+con_blocks[i]);
			if (obj!=null){
				if (selectedGames[id]==21){
					obj.html("SL");
					obj.addClass("selected");
					obj.addClass("gm");
				}
				else{
					obj.html(selectedGames[id]+".");
					obj.addClass("selected");
					obj.removeClass("gm");
				}
			}
		}
	}
	function deselectPressBox(id)
	{
		for(var i=0;i<con_blocks.length;i++){
			var obj = $("#box_"+id+"_"+con_blocks[i]);
			if (obj!=null){
				obj.html("&nbsp;");
				obj.removeClass("selected")
				obj.removeClass("gm");
			}
		}
	}

	

	
	function eventInformation(code){
		
		if ($("#event_information_"+code).is(':visible')){
			$("#event_information_"+code+" .w1").animate({ height: 'hide', opacity: 'hide' }, 'fast',function(){$("#event_information_"+code).hide();});
		}else{
			$("#event_information_"+code+" .w1").hide();
			$("#event_information_"+code).show();
			$("#event_information_"+code+" .w1").animate({ height: 'show', opacity: 'show' }, 'fast');
		}
		
	}
	
	
	jQuery(document).ready(function(){
		
		// fill from extra_gm_information
		var v = jQuery("#extra_gm_information").val();
		var parts=Array();
		if (v!="")parts=v.split(",");
		for(var i=0;i<parts.length;i++){
			jQuery("#gm_"+parts[i]).attr("checked","checked");
		}
		
		jQuery(".gm-selector").change(function(){
			var v = jQuery("#extra_gm_information").val();
			var parts=Array();
			if (v!="")parts=v.split(",");
			if (jQuery(this).attr("checked"))
				parts[parts.length] = jQuery(this).val();
			else{
				var res = Array();
				for(var i=0;i<parts.length;i++){
					if (parts[i]!=jQuery(this).val())
						res[res.length] = parts[i];
				}
				parts = res;
			}
			jQuery("#extra_gm_information").val(parts.join(","));
		});
	});
	
</script>
<?php 
?>


<input type='hidden' value='<?php echo tm_getForm("extra_gm_information");?>' name='extra_gm_information' id='extra_gm_information'>

<table cellspacing='0' cellpadding='0' border='0' id='aktivitet'>
	<tr>
		<th class='name'></th>
		<th colspan='4'>Fredag</th>
		<th class='break'></th>
		<th colspan='8'>L&oslash;rdag</th>
		<th class='break'></th>
		<th colspan='4'>S&oslash;ndag</th>
	</tr>
	<tr>
		<th class='name'>&nbsp;<span style='float:right;padding-right:5px;padding-top:15px;' title='Kryds felterne hvis du vil være GM'>GM</span></th>
		<th class='modul modul_f1'>F1<br/><small>19:30</small></th>
		<th class='modul modul_f2'>F2<br/><small>21:30</small></th>
		<th class='modul modul_f3'>F3<br/><small>24:00</small></th>
		<th class='modul modul_f4'>F4<br/><small>02:00</small></th>
		<th class='break'></th>
		<th class='modul modul_l1'>L1<br/><small>10:00</small></th>
		<th class='modul modul_l2'>L2<br/><small>12:00</small></th>
		<th class='modul modul_l3'>L3<br/><small>14:00</small></th>
		<th class='modul modul_l4'>L4<br/><small>16:30</small></th>
		<th class='modul modul_l5'>L5<br/><small>19:30</small></th>
		<th class='modul modul_l6'>L6<br/><small>21:30</small></th>
		<th class='modul modul_l7'>L7<br/><small>24:00</small></th>
		<th class='modul modul_l8'>L8<br/><small>02:00</small></th>
		<th class='break'></th>
		<th class='modul modul_g'>S1<br/><small>10:00</small></th>
		<th class='modul modul_h'>S2<br/><small>12:00</small></th>
		<th class='modul modul_g'>S3<br/><small>14:30</small></th>
		<th class='modul modul_g'>S4<br/><small>16:30</small></th>
	</tr>
	<tr><th colspan='19'>Rollespil</th></tr>
	<?php 
	listDB("rollespil");
	?>
	<tr>
		<th class='name'></th>
		<th colspan='4'>Fredag</th>
		<th class='break'></th>
		<th colspan='8'>L&oslash;rdag</th>
		<th class='break'></th>
		<th colspan='4'>S&oslash;ndag</th>
	</tr>
	<tr>
		<th class='name'>&nbsp;</th>
		<th class='modul modul_f1'>F1<br/><small>19:30</small></th>
		<th class='modul modul_f2'>F2<br/><small>21:30</small></th>
		<th class='modul modul_f3'>F3<br/><small>24:00</small></th>
		<th class='modul modul_f4'>F4<br/><small>02:00</small></th>
		<th class='break'></th>
		<th class='modul modul_l1'>L1<br/><small>10:00</small></th>
		<th class='modul modul_l2'>L2<br/><small>12:00</small></th>
		<th class='modul modul_l3'>L3<br/><small>14:00</small></th>
		<th class='modul modul_l4'>L4<br/><small>16:30</small></th>
		<th class='modul modul_l5'>L5<br/><small>19:30</small></th>
		<th class='modul modul_l6'>L6<br/><small>21:30</small></th>
		<th class='modul modul_l7'>L7<br/><small>24:00</small></th>
		<th class='modul modul_l8'>L8<br/><small>02:00</small></th>
		<th class='break'></th>
		<th class='modul modul_g'>S1<br/><small>10:00</small></th>
		<th class='modul modul_h'>S2<br/><small>12:00</small></th>
		<th class='modul modul_g'>S3<br/><small>14:30</small></th>
		<th class='modul modul_g'>S4<br/><small>16:30</small></th>
	</tr>
	<tr><th colspan='19'>Br&aelig;tspil</th></tr>
	<?php 
	listDB("braetspil");
	?>
</table>


<h2>Viking-Con mangler spilledere</h2>
<p class='field1_1'>Vi mangler spilledere til en r&aelig;kke rollespil p&aring; Viking-Con. Hvis du er interesseret i at v&aelig;re spilleder, m&aring; du meget gerne informere os i dette felt, ogs&aring; selvom det kun er eet bestemt rollespil:</p>
<p class='field1_1'><input type='text' name='tilm_oenskergm' id='tilm_oenskergm' value='<?php  echo tm_getForm("tilm_oenskergm")?>'></span>

<h2>Andre informationer</h2>
<p class="field1_1"><label for="">Hvis du gerne vil p&aring; de samme turneringer som resten af din gruppe, s&aring; angiv et gruppenavn </label><input type="text" name="choice_gruppenavn" value="<?php  echo tm_getForm("choice_gruppenavn")?>" /></p>
<div id="vip">
<p class="field1_1"><label for="">Jeg er turneringsleder/spilleder i f&oslash;lgende scenarier/turneringer</label><input type="text" name="choice_leder1"  value="<?php  echo tm_getForm("choice_leder1")?>"/></p>
<!-- <p class="field1_1"><label for="">Jeg vil gerne v&aelig;re spilleder i f&oslash;lgende scenarier</label><input type="text" name="choice_leder2" value="<?php  echo tm_getForm("choice_leder2")?>"/></p>-->
<p class="field1_1"><label for="">Jeg er forfatter til f&oslash;lgende scenarier</label><input type="text" name="choice_forfatter"  value="<?php  echo tm_getForm("choice_forfatter")?>"/></p>
</div>

</fieldset>

<script>
	function forceOneOption(num,code){
		
	}
	function forceOption(num){
		if (isNaN(num))
		{
			var codes = jQuery('#choice_21').val();
			var sl_list = codes.split(',');
			for(var i=0;i<sl_list.length;i++){
				var code = sl_list[i];
				selectedGames[code] = 21;
				selectPressBox(code);
			}
		}else{
			var code = jQuery('#choice_'+num).val();
			selectedGames[code] = num;
			gameList[gameList.length] = num;
			selectPressBox(code);
		}
	}
</script>

<div style='display:none;'>
	<?php 
	for($i=1;$i<=20;$i++){ ?>
		choice_<?php echo $i?>:<input type='text' value='<?php  echo tm_getForm("choice_".$i)?>' name='choice_<?php  echo $i?>' id='choice_<?php  echo $i?>'><br>
		<script>
			forceOption('<?php echo $i?>');
		</script>
		<?php 
	} ?>
	
	choice_21: <input type='text' value='<?php  echo tm_getForm("choice_21")?>' name='choice_21' id='choice_21'><br/>
	<script>
		forceOption('sl');
	</script>
</div>

<p class="right"><input class="button next" type="submit" name='action2' value="Videre" /></p>
</form>
<form action='./step-1' method='post'>
<p class="left"><input class="button next" type="submit" name='action1' value="Tilbage" /></p>
</form>
<div class="clear"/>