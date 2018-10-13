<?php
     class aktiviteter_v1
     {

        function ft_getCheckedEvent($name,$value)
        {
             $customer = $_SESSION['customer'];
             if ($customer[$name]==$value)return "checked";
             return "";
        }

        function ft_renderEvent($name,$start,$end,$id,$day,$price,$ak_id,$multi,$type,$wp_id){
            $this->ft_renderEvent_add_array($name,$start,$end,$id,$day,$price,$ak_id,$multi,$type,$wp_id, array());
        }
        
        function ft_renderEvent_add_array($name,$start,$end,$id,$day,$price,$ak_id,$multi,$type,$wp_id, $add_array)
        {
            $array = array(
                'name'=>$name,
                'start'=>$start,
                'end'=>$end,
                'id'=>$id,
                'day'=>$day,
                'price'=>$price,
                'ak_id'=>$ak_id,
                'multi'=>$multi,
                'type'=>$type,
                'wp_id'=>$wp_id
            );
            foreach($add_array as $key=>$value)
            {
                $array[$key] = $value;
            }
            
            $this->ft_renderEvent_array($array);
        }

        function ft_renderEvent_array($arr)
        {
            $name  = $arr['name'];
            $start = $arr['start'];
            $end   = $arr['end'];
            $id    = $arr['id'];
            $day   = $arr['day'];
            $price = $arr['price'];
            $ak_id = $arr['ak_id'];
            $multi = $arr['multi'];
            $type  = $arr['type'];
            $wp_id = $arr['wp_id'];
            
            $antal_spilledere = -1;
            if (isset($arr['spilledere']))
                $antal_spilledere = $arr['spilledere'];
                        
            
            $customer = $_SESSION['customer'];
            
            $real_start = $start;
            $real_end = $end;
         	$name = $name;
             
        	if ($start<10)$start+=24;
        	if ($end<10)$end+=24;
        	
        	$start-=10;
        	$end-=10;
        	
        	$length = ($end-$start);
        	$total_length = (24-10)+5;
        	$remainder = $total_length-$end;
        	
        	$explain = "";
        	if ($type=="rolle")	$explain = __tm('Rollespil');
        	if ($type=="braet")	$explain = __tm('Brætspil');
        	if ($type=="live")	$explain = __tm('Live-rollespil');
        	if ($type=="workshop") $explain = __tm('Workshop');
        	if ($type=="figur")	$explain = __tm('Figurspil');
        	if ($type=="ottoviteter")	$explain = __tm('Events & Workshops');
        	
        	if (!$multi)
        	{	
             	?>
             	<tr class='row-with-game row-type-<?php echo $type;?>'>
        			<td class='caption'>
        				<div class='box_<?php echo $type?>' title='<?php echo $explain?>'>&nbsp;</div>
        				<!-- <a href="javascript:void(0)" onclick="popup('/tilmelding/templates/2012/explain.php?id=<?php echo $ak_id?>')"><?php echo utf8_encode($name)?></a>-->
        				<?php 
                        if ($wp_id!=0)
            			{
            				?><a href="/?page_id=<?php echo $wp_id?>" target='_blank'><?php echo utf8_encode($name)?></a><?php 
                        }
                        else
                        {
        				    __etm(utf8_encode($name));
        				}
        				
        				if ($price!=0)
        				{
        				    ?>
        				    (<?php echo $price; ?> <?php __etm('DKK')?>)
                            <?php
                        }
                        
        				?>
        			</td>
        			<?php for ($i=0;$i<$start*2;$i++){?><td colspan='1' class='noevent'>&nbsp;</td><?php }?>
        			<td colspan='<?php echo $length*2;?>' class='has-event'>
        				<div class='event priority0 event_size_<?php echo ($length/2)?>'  onMouseDown='nextPriority(event,"<?php echo $id?>",<?php echo $start*2?>,<?php echo $end*2?>,<?php echo $day?>);' id='<?php echo $id?>'>
        				     <span id='<?php echo $id?>_caption'>&nbsp;</span>
        					<label for='<?php echo $id?>_0'><?php __etm('Ingen prioritet')?></label><input type='radio' value='0' name='<?php echo $id?>' id='<?php echo $id?>_0' <?php echo $this->ft_getCheckedEvent($id,0)?>/>
        					<label for='<?php echo $id?>_1'><?php __etm('1. prioritet')?></label><input type='radio' value='1' name='<?php echo $id?>' id='<?php echo $id?>_1' <?php echo $this->ft_getCheckedEvent($id,1)?>/>
        					<label for='<?php echo $id?>_2'><?php __etm('2. prioritet')?></label><input type='radio' value='2' name='<?php echo $id?>' id='<?php echo $id?>_2' <?php echo $this->ft_getCheckedEvent($id,2)?>/>
        					<label for='<?php echo $id?>_3'><?php __etm('3. prioritet')?></label><input type='radio' value='3' name='<?php echo $id?>' id='<?php echo $id?>_3' <?php echo $this->ft_getCheckedEvent($id,3)?>/>
        					<label for='<?php echo $id?>_4'><?php __etm('4. prioritet')?></label><input type='radio' value='4' name='<?php echo $id?>' id='<?php echo $id?>_4' <?php echo $this->ft_getCheckedEvent($id,4)?>/>
        					
        					<?php 
            				if ($antal_spilledere != -1000)
            				{
                				?>
                                <label for='<?php echo $id?>_5'><?php __etm('SL prioritet')?></label><input type='radio' value='5' name='<?php echo $id?>' id='<?php echo $id?>_5' <?php echo $this->ft_getCheckedEvent($id,5)?>/>
        					    <?php 
            				} 
            				?>
            				
        					<?php 
            				$has_gm = $antal_spilledere!=0?1:0;
            					
        					$this->js_chunk_event .= "addEvent( '".$id."' , ".($start*2)." , ".($end*2)." , ".$day." , ".$has_gm.");\n";
        					if (isset($customer[$id])&&($customer[$id]>0))
        					     $this->js_chunk_display .= "fixDisplay('".$id."',".$customer[$id].");\n";
        					?>
        				</div>
        			</td>
        			<?for ($i=0;$i<$remainder*2;$i++){?><td colspan='1' class='noevent'>&nbsp;</td><?php }?>
             	</tr>
             	<?
             }
             else
             {
             	?>
             	<tr class='row-with-game row-type-<?php echo $type;?>'>
        			<td class='caption'><?php echo utf8_encode($name)?></a></td>
        			<?php for ($i=0;$i<$start*2;$i++){?><td colspan='1' class='noevent'>&nbsp;</td><?php }?>
        			<td colspan='<?php echo $length*2?>'>
        				<div class='event priority0 event_size_<?php echo ($length/2)?>' onMouseDown='nextPriority(event,"<?php echo $id;?>",<?php echo $start*2;?>,<?php echo $end*2;?>,<?php echo $day;?>);' id='<?php echo $id;?>_m<?php echo $multi;?>'>
        				     <span id='<?php echo $id?>_m<?php echo $multi?>_caption'>&nbsp;</span>
        					<?php 
        					$this->js_chunk_event .= "attachEvent('".$id."',".$multi.",".($start*2).",".($end*2).",".$day.");";
        					if (isset($customer[$id])&&($customer[$id]>0))
        					     $this->js_chunk_display .= "fixDisplay('".$id."_m".$multi."',".$customer[$id].");";
        					?>
        				</div>
        			</td>
        			<?php for ($i=0;$i<$remainder*2;$i++){?><td colspan='1' class='noevent'>&nbsp;</td><?php }?>
             	</tr>
             	<?
             }
        }

        
        function ft_aktiviteterByDayJSON($day,$caption,$afviklinger){
             global $language;
             $start_hour = 10;
             $end_hour = 24+2+2;
             /*
             if ($_SERVER['REMOTE_ADDR']=="185.17.219.62"){
             	echo "<pre>";
             	foreach($afviklinger as $afvikling){
             		if ($afvikling['linked']!=0)
        		     	print_r($afvikling);
             			
             	}
             	echo "</pre>";
             }
             */
             ?>
             
            <ul class='type-selector selector-day-<?php echo $day;?>' data-day='<?php echo $day;?>'>
                <li  data-day='<?php echo $day?>' data-type='' class='selected'>Alle aktiviteter<!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='rolle'>Rollespil<!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='braet'>Brætspil<!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='live'>Live-rollespil<!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='workshop'>Workshop<!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='figur'>Figurspil<!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='ottoviteter'>Events & Workshops<!-- <span class='value-0'>0</span>--></li>
            </ul>
             
             
             <table border='0' cellspacing='0' cellpadding='0' class='table-day table-day-<?php echo $day;?>'>
             	<tr>
             		<th colspan='39' class='day'><?php echo $caption?> <a href='#' class='reset' onClick='resetDay(<?php echo $day?>);return false'><?php echo $language["reset day"]?></a></th>
             	</tr>
             	<tr>
             		<th class='caption'><?php __etm('Aktiviteter');?></th>
             		<?
             		for($i=$start_hour;$i<=$end_hour;$i++){
        	     		$hour=$i;
        	     		if($hour>23)$hour-=24;
        				?><th colspan=2 class='time'><?php echo $hour?></th><?
             		}
             		?>
             	</tr>
             		<?
             		if (count($afviklinger)!=0)
             		{
        	     		foreach($afviklinger as $afvikling)
        	     		{
        	          		$timestamp_start = $afvikling['start'];
        	          		$timestamp_end = $afvikling['end'];
        	          		$aktivitet = $afvikling['aktivitet'];
        	          		
        	     			$start = $timestamp_start['h'] + ($timestamp_start['m'] / 30 / 2);
        	     			$length = $aktivitet['info']['play_hours'];
        	          		$end = $start+$length;
        	          		
        	          		if ($length==0) // events with 0 time => nope..
            	          		continue;
        	          		
        	          		$name = $aktivitet['info']['title_'.$this->language];
        	          		if ($name=="")
        	          		  $name = $aktivitet['info']['title_da'];
        	          		if ($afvikling['linked']!=0)
        	          		{
            	          		
        	          			$this->ft_renderEvent_add_array(utf8_decode($name),
                            	          			$start,
                            	          			$end,
                            	          			"event_".$afvikling["linked"],
                            	          			$day,$aktivitet['info']["price"],
                            	          			$afvikling["aktivitet_id"],
                            	          			$afvikling["afvikling_id"],
                            	          			$aktivitet['info']['type'],
                            	          			$aktivitet['info']['wp_id'],
                            	          			
                            	          			array(
                            	          			  'spilledere' => $aktivitet['info']['gms']
                                                    )
                            	          			);
        	          		}
        	          		else{
        	          			$this->ft_renderEvent_add_array(utf8_decode($name),
        	          			                      $start,
        	          			                      $end,
        	          			                      "event_".$afvikling["afvikling_id"],
        	          			                      $day,
        	          			                      $aktivitet['info']["price"],
        	          			                      $afvikling["aktivitet_id"],
        	          			                      false,
        	          			                      $aktivitet['info']['type'],
        	          			                      $aktivitet['info']['wp_id'],
                                	          			array(
                                	          			  'spilledere'=>$aktivitet['info']['gms']
                                                        )
        	          			                      );
            	          		
        	          		}
        	     		}
                 		
             		}
             		?>
             </table>
             <?
        }
         
         
     	function __construct(){
         	$this->language = "da";
         	$this->js_chunk_display = "";
         	$this->js_chunk_event = "";
     	}
	     function getFields(){
			$fields = array();
			$aktiviteter = $this->loadAktiviteter();
			$afviklinger = $this->loadAfviklinger($aktiviteter);
			foreach($afviklinger as $afvikling){
				$fields[] = "event_".$afvikling['afvikling_id'];
			}
			$fields[] = "aktiviteter_is_spilleder";
			return $fields;
	     }
	     
	     function validateFields()
	     {
			$aktiviteter = $this->loadAktiviteter();
			$afviklinger = $this->loadAfviklinger($aktiviteter);
			$_SESSION['tilmelding']['customer']['aktiviteter_is_spilleder'] = 0;
			foreach($afviklinger as $afvikling){
				if ($_SESSION['tilmelding']['customer']['event_'.$afvikling['afvikling_id']]==5)
					$_SESSION['tilmelding']['customer']['aktiviteter_is_spilleder'] = 1;
			}
	     	return true;
	     }
	
		function microtime_float()
		{
		    list($utime, $time) = explode(" ", microtime());
		    return ((float)$utime + (float)$time);
		}
	
		function loadAktiviteter()
		{
			return $this->loadJSON("api/activities/*");
		}
		function loadAfviklinger($aktiviteter){
	     	$afviklinger = array();
	     	foreach($aktiviteter as $aktivitet){
	     		$afvs = $aktivitet['afviklinger'];
	     		foreach($afvs as $afv){
	     			$afv['aktivitet'] = $aktivitet;
	     			$afviklinger[] = $afv;
	     		}
	     	}
	     	return $afviklinger;
		}
		function divideAfviklinger($afviklinger){
			$divided = array();
			foreach($afviklinger as $afvikling){
				$timestamp_start = $afvikling['start'];
				$day = $timestamp_start['day'];
				if ($timestamp_start['h']<9)
					$day--;
				if (!isset($divided[$day]))$divided[$day]=array();
				$divided[$day][]=$afvikling;
			}
			return $divided;
		}
		function sortAfviklinger($afviklinger){ // these are all the same day
			if (!function_exists("day_type_sort"))
			{
				function day_type_sort($afv1,$afv2)
				{
					$t1 = $afv1['aktivitet']['info']['type'];
					$t2 = $afv2['aktivitet']['info']['type'];
					if ($t1==$t2)
					{
						if ($afv1['start']['day']<$afv2['start']['day'])
							return -1;
						else if ($afv1['start']['day']>$afv2['start']['day'])
							return 1;
						else
							return $afv1['start']['h']>$afv2['start']['h'];
					}
					else
						return -strcmp($t1,$t2);
				}
			}

			if (!function_exists("day_sort"))
			{
				function day_sort($afv1,$afv2)
				{
					if ($afv1['start']['day']<$afv2['start']['day'])
						return -1;
					else if ($afv1['start']['day']>$afv2['start']['day'])
						return 1;
					else
						return $afv1['start']['h']>$afv2['start']['h'];
				}
			}

			usort($afviklinger,"day_sort");
			return $afviklinger;
		}
		
    	function loadJSON($url){
    		$c = curl_init();
    		curl_setopt($c, CURLOPT_URL, "http://127.0.0.1/".$url);
    		curl_setopt($c, CURLOPT_HEADER, false);
    		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($c, CURLOPT_REFERER, '');
    		curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
    		curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: infosys.fastaval.dk'));
    		$data = curl_exec($c);
    		curl_close($c);
    		$json = json_decode($data,true);
         	return $json;
    	} 
	
		function render(){
			$aktiviteter = $this->loadAktiviteter();
			$afviklinger = $this->loadAfviklinger($aktiviteter);
			$afviklinger_daysplit = $this->divideAfviklinger($afviklinger);
			
			$afvikling_daysplit_sort = array();
			foreach($afviklinger_daysplit as $day=>$afviklinger){
				$sorted = $this->sortAfviklinger($afviklinger);
				$afvikling_daysplit_sort[$day] = $sorted;
			}
			$afviklinger_daysplit = $afvikling_daysplit_sort;
			
			?><div id='aktiviteter'><?
		     $this->ft_aktiviteterByDayJSON(1,"Onsdag (1. april 2015)",$afviklinger_daysplit[1]);
		     $this->ft_aktiviteterByDayJSON(2,"Torsdag (2. april 2015)",$afviklinger_daysplit[2]);
		     $this->ft_aktiviteterByDayJSON(3,"Fredag (3. april 2015)",$afviklinger_daysplit[3]);
		     $this->ft_aktiviteterByDayJSON(4,"Lørdag (4. april 2015)",$afviklinger_daysplit[4]);
		     $this->ft_aktiviteterByDayJSON(5,"Søndag (5. april 2015)",$afviklinger_daysplit[5]);
			?></div>
			
			<script type="text/javascript">
                var caption = ["&nbsp;", "<?php __etm("1. prio")?>", "<?php __etm("2. prio")?>", "<?php __etm("3. prio")?>", "<?php __etm("4. prio")?>", "<?php __etm("SL")?>"];
                <?php include('aktiviteter_v1.js.php'); ?>
			</script>
			
			<script type="text/javascript">
    			/*******/
    			<?php echo $this->js_chunk_event;?>
    			/*******/
    			<?php echo $this->js_chunk_display;?>
    			/*******/
			</script>
            <?php
		}
	}
