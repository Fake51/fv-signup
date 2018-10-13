<?php
    
     class aktiviteter_v1
     {
        public function attends_con_at_numday($numday){
            if (isset($_SESSION['customer']['days_all']))
                if ($_SESSION['customer']['days_all']==1)
                    return true;
            
            if (isset($_SESSION['customer']['days_'.$numday]))
                if ($_SESSION['customer']['days_'.$numday]==1)
                    return true;
            
            return false;
        }
        

     	public function __construct(){
         	$this->language = "da";
         	$this->js_chunk_display = "";
         	$this->js_chunk_event = "";
     	}
	    public function getFields(){
			$fields = array();
			$aktiviteter = $this->loadAktiviteter();
			$afviklinger = $this->loadAfviklinger($aktiviteter);
			foreach($afviklinger as $afvikling){
				$fields[] = "event_".$afvikling['afvikling_id'];
			}
			$fields[] = "aktiviteter_is_spilleder";
			return $fields;
	     }
	     
	     public function validateFields()
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
	
		public function microtime_float()
		{
		    list($utime, $time) = explode(" ", microtime());
		    return ((float)$utime + (float)$time);
		}
	
		public function loadAktiviteter()
		{
			return $this->loadJSON("api/activities/*");
		}
		public function loadAfviklinger($aktiviteter){
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
		public function divideAfviklinger($afviklinger){
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
		public function sortAfviklinger($afviklinger)
		{ // these are all the same day
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
		
    	public function loadJSON($url){
    		$c = curl_init();
    		
//    		https://infosys.fastaval.dk/api/activities/*?birthdate=20100505

            $birthdate = date("Ymd");
            $year = $_SESSION['customer']['birthday-year']*1;
            $day = $_SESSION['customer']['birthday-day']*1;
            $month = $_SESSION['customer']['birthday-month']*1;
            if (is_numeric($year)&&is_numeric($month)&&is_numeric($day))
                $birthdate = $year."".($month<10?"0":"").$month."".($day<10?"0":"").$day;
    		curl_setopt($c, CURLOPT_URL, "http://127.0.0.1/".$url."?birthdate=".$birthdate);
    		curl_setopt($c, CURLOPT_HEADER, false);
    		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($c, CURLOPT_REFERER, '');
    		curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
    		curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: '.INFOSYS_HOSTNAME));
    		$data = curl_exec($c);
    		curl_close($c);
    		$json = json_decode($data,true);
         	return $json;
    	} 
         
        public function get_age(){
            $year = $_SESSION['customer']['birthday-year']*1;
            $day = $_SESSION['customer']['birthday-day']*1;
            $month = $_SESSION['customer']['birthday-month']*1;
            $age = 100;
            
            if (is_numeric($year)&&is_numeric($month)&&is_numeric($day))
            {
                $birthDate = $year."-".($month<10?"0":"").$month."-".($day<10?"0":"").$day;
                # object oriented
                $from = new DateTime($birthDate);
                $to   = new DateTime('today');
                return $from->diff($to)->y;
            }
            return $age;
        }

        public function ft_getCheckedEvent($name,$value)
        {
             $customer = $_SESSION['customer'];
             if ($customer[$name]==$value)return "checked";
             return "";
        }

        public function ft_renderEvent($name,$start,$end,$id,$day,$price,$ak_id,$multi,$type,$wp_id, $language=null, $text=null, $can_sign_up=0){
            $this->ft_renderEvent_add_array($name,$start,$end,$id,$day,$price,$ak_id,$multi,$type,$wp_id, array(), $language, $text, $can_sign_up);
        }
        
        public function ft_renderEvent_add_array($name,$start,$end,$id,$day,$price,$ak_id,$multi,$type,$wp_id, $add_array, $language, $text=null, $can_sign_up=0)
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
                'wp_id'=>$wp_id,
                'language'=>$language,
                'text'=>$text,
                'can_sign_up'=>$can_sign_up,
            );
            foreach($add_array as $key=>$value)
            {
                $array[$key] = $value;
            }
            
            $this->ft_renderEvent_array($array);
        }

        public function ft_renderEvent_array($arr)
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
            $text  = $arr['text'];
            $can_sign_up = $arr['can_sign_up'];
            
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
        	if ($type=="magic")	$explain = __tm('Magic');
        	if ($type=="ottoviteter")	$explain = __tm('Events & Workshops');
        	
        	$image = "<img style='float:left;padding-right:12px;height:12px;width:auto;' src='" . plugin_dir_url( __FILE__ ).'../images/flag-'.$arr['language'].'.jpg' . "' />";
        	
        	if (!$multi)
        	{	
             	?>
             	<tr class='row-with-game row-type-<?php echo $type;?>'>
        			<td class='caption'>
        				<div class='box_<?php echo $type?>' title='<?php echo $explain?>' style='top:0px;'>&nbsp;</div>
        				<!-- <a href="javascript:void(0)" onclick="popup('/tilmelding/templates/2012/explain.php?id=<?php echo $ak_id?>')"><?php echo utf8_encode($name)?></a>-->
        				<?php 
                        if ($wp_id!=0)
            			{
            				?><a class='event-link' href="/?page_id=<?php echo $wp_id?>" data-id='<?php echo $id;?>'><?php echo utf8_encode($name)?></a><?php 
            				echo $image;
                        }
                        else
                        {
            				?><a class='event-link' href="#" data-id='<?php echo $id;?>'><?php echo utf8_encode($name)?></a><?php 
            				echo $image;
        				}
        				
        				if ($price!=0)
        				{
        				    ?>
        				    (<?php echo $price; ?> <?php __etm('nocat_11')?>)
                            <?php
                        }
                        
        				?>
        			</td>
        			<?php for ($i=0;$i<$start*2;$i++){?><td colspan='1' class='noevent'>&nbsp;</td><?php }?>
        			<td colspan='<?php echo $length*2;?>' class='has-event'>
        				<div class='event <?php if ($can_sign_up!='1'){?> dead <?php }?> priority0 event_size_<?php echo ($length/2)?>' <?php if ($can_sign_up=='1'){?>onMouseDown='nextPriority(event,"<?php echo $id?>",<?php echo $start*2?>,<?php echo $end*2?>,<?php echo $day?>);'<?php }?> id='<?php echo $id?>'>
        				     <span id='<?php echo $id?>_caption'>&nbsp;</span>
        					<label for='<?php echo $id?>_0'><?php __etm('nocat_12')?></label><input type='radio' value='0' name='<?php echo $id?>' id='<?php echo $id?>_0' <?php echo $this->ft_getCheckedEvent($id,0)?>/>
        					<label for='<?php echo $id?>_1'><?php __etm('nocat_13')?></label><input type='radio' value='1' name='<?php echo $id?>' id='<?php echo $id?>_1' <?php echo $this->ft_getCheckedEvent($id,1)?>/>
        					<label for='<?php echo $id?>_2'><?php __etm('nocat_14')?></label><input type='radio' value='2' name='<?php echo $id?>' id='<?php echo $id?>_2' <?php echo $this->ft_getCheckedEvent($id,2)?>/>
        					<label for='<?php echo $id?>_3'><?php __etm('nocat_15')?></label><input type='radio' value='3' name='<?php echo $id?>' id='<?php echo $id?>_3' <?php echo $this->ft_getCheckedEvent($id,3)?>/>
        					
        					<?php 
            				if ($antal_spilledere != -1000)
            				{
                				?>
                                <label for='<?php echo $id?>_4'><?php __etm('nocat_16')?></label><input type='radio' value='4' name='<?php echo $id?>' id='<?php echo $id?>_4' <?php echo $this->ft_getCheckedEvent($id,4)?>/>
                                <label for='<?php echo $id?>_5'><?php __etm('nocat_17')?></label><input type='radio' value='5' name='<?php echo $id?>' id='<?php echo $id?>_5' <?php echo $this->ft_getCheckedEvent($id,5)?>/>
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
        			<?php for ($i=0;$i<$remainder*2;$i++){?><td colspan='1' class='noevent'>&nbsp;</td><?php }?>
             	</tr>
             	<tr class='row-with-explain row-with-game row-type-<?php echo $type;?>'' id='explain_<?php echo $id;?>' style='display:none;'>
                 	<td colspan='39'><div style='padding:1em;'><p><strong><?php echo utf8_encode($name);?></strong></p><?php echo str_replace("\n","<br>",$text);?></div></td>
             	</tr>
             	
             	<?php
             }
             else
             {
             	?>
             	<tr class='row-with-game row-type-<?php echo $type;?>'>
        			<td class='caption'><?php echo utf8_encode($name)?></a><?php echo $image; ?></td>
        			<?php for ($i=0;$i<$start*2;$i++){?><td colspan='1' class='noevent'>&nbsp;</td><?php }?>
        			<td colspan='<?php echo $length*2?>'>
        				<div class='event priority0 event_size_<?php echo ($length/2)?>' onMouseDown='nextPriority(event,"<?php echo $id;?>",<?php echo $start*2;?>,<?php echo $end*2;?>,<?php echo $day;?>);' id='<?php echo $id;?>_m<?php echo $multi;?>'>
        				     <span id='<?php echo $id?>_m<?php echo $multi?>_caption'>&nbsp;</span>
        					<?php 
        					$this->js_chunk_event .= "attachEvent('".$id."','".$multi."',".($start*2).",".($end*2).",".$day.");";
        					if (isset($customer[$id])&&($customer[$id]>0))
        					     $this->js_chunk_display .= "fixDisplay('".$id."_m".$multi."',".$customer[$id].");";
        					?>
        				</div>
        			</td>
        			<?php for ($i=0;$i<$remainder*2;$i++){?><td colspan='1' class='noevent'>&nbsp;</td><?php }?>
             	</tr>
             	<?php
             }
        }

        
        public function ft_aktiviteterByDayJSON($day, $caption, $afviklinger)
        {
            global $language;
            $start_hour = 10;
            $end_hour = 24+2+2;
            ?>
             
            <ul class='type-selector selector-day-<?php echo $day;?>' data-day='<?php echo $day;?>'>
                <li  data-day='<?php echo $day?>' data-type='' class='selected'><?php __etm('nocat_317');?><!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='rolle'><?php __etm('nocat_10');?><!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='braet'><?php __etm('nocat_18');?><!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='live'><?php __etm('nocat_313');?><!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='workshop'><?php __etm('nocat_314');?><!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='figur'><?php __etm('nocat_315');?><!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='magic'><?php __etm('nocat_318');?><!-- <span class='value-0'>0</span>--></li>
                <li  data-day='<?php echo $day?>' data-type='ottoviteter'><?php __etm('nocat_128');?><!-- <span class='value-0'>0</span>--></li>
            </ul>
             
             
            <table border='0' cellspacing='0' cellpadding='0' class='table-day table-day-<?php echo $day;?>'>
                <tr>
                    <th colspan='39' class='day'><?php echo __etm($caption)?> <a href='#' class='reset' onClick='resetDay(<?php echo $day?>);return false'><?php echo $language["reset day"]?></a></th>
                </tr>
                <tr>
                    <th class='caption'><?php __etm('nocat_300');?></th>
                    <?php
                    for($i=$start_hour;$i<=$end_hour;$i++)
                    {
                        $hour=$i;
                        if($hour>23)
                            $hour-=24;
                        ?><th colspan=2 class='time'><?php echo $hour?></th><?php
                    }
                    ?>
                </tr>
         		<?php
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
        	          	
    	          		$name = $aktivitet['info']['title_'.get_language()];
    	          		$text = $aktivitet['info']['text_'.get_language()];
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
                                                ),
                        	          			$aktivitet['info']['language'],
                        	          			$text,
                        	          			$aktivitet['info']['can_sign_up']
                         			);
    	          		}
    	          		else
    	          		{
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
                                                  ),
                         	          	   		  $aktivitet['info']['language'],
                        	          			  $text,
                         	          			  $aktivitet['info']['can_sign_up']
    	          		                    );
        	          		
    	          		}
    	     		}
             		
         		}
     		?>
            </table>
            <?php
        }
         
         
	
	
		public function render()
		{
			$aktiviteter = $this->loadAktiviteter();
			$afviklinger = $this->loadAfviklinger($aktiviteter);
			$afviklinger_daysplit = $this->divideAfviklinger($afviklinger);
			
			$afvikling_daysplit_sort = array();
			foreach($afviklinger_daysplit as $day=>$afviklinger){
				$sorted = $this->sortAfviklinger($afviklinger);
				$afvikling_daysplit_sort[$day] = $sorted;
			}
			$afviklinger_daysplit = $afvikling_daysplit_sort;
			
			?><div id='aktiviteter'><?php
    			
    		 if ($this->attends_con_at_numday(1))
		     $this->ft_aktiviteterByDayJSON(23,"Onsdag (23. marts 2016)",$afviklinger_daysplit[23]);
    		 if ($this->attends_con_at_numday(2))
		     $this->ft_aktiviteterByDayJSON(24,"Torsdag (24. marts 2016)",$afviklinger_daysplit[24]);
    		 if ($this->attends_con_at_numday(3))
		     $this->ft_aktiviteterByDayJSON(25,"Fredag (25. marts 2016)",$afviklinger_daysplit[25]);
    		 if ($this->attends_con_at_numday(4))
		     $this->ft_aktiviteterByDayJSON(26,"Lørdag (26. marts 2016)",$afviklinger_daysplit[26]);
    		 if ($this->attends_con_at_numday(5))
		     $this->ft_aktiviteterByDayJSON(27,"Søndag (27. marts 2016)",$afviklinger_daysplit[27]);
			?></div>
			
			<script type="text/javascript">
                var caption = ["&nbsp;", "<?php __etm("nocat_19")?>", "<?php __etm("nocat_20")?>", "<?php __etm("nocat_21")?>", "<?php __etm("nocat_22")?>", "<?php __etm("nocat_23")?>"];
                <?php include('aktiviteter_v1.js.php'); ?>
			</script>
			
			<script type="text/javascript">
    			/*******/
    			<?php echo $this->js_chunk_event;?>
    			/*******/
    			<?php echo $this->js_chunk_display;?>
    			/*******/
    			
    			
    			jQuery(document).ready(function(){
        			jQuery('.event-link').click(function(){
            			jQuery('.row-with-explain').hide();
            			var id=jQuery(this).data('id');
            			jQuery('#explain_'+id).show();
            			return false;
        			});
    			});
    			
			</script>
            <?php
		}
	
	}
