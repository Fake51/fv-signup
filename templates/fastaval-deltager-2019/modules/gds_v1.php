<?php
     class gds_v1
     {
        public function attends_con_at_numday($numday)
        {
            if (isset($_SESSION['customer']['days_all']))
                if ($_SESSION['customer']['days_all']==1)
                    return true;
            
            if (isset($_SESSION['customer']['days_'.$numday]))
                if ($_SESSION['customer']['days_'.$numday]==1)
                    return true;
            
            if (isset($_SESSION['customer']['participant']) == 'deltagerjunior')
                if ($numday==3)
                    return true;
            
            return false;
        }
         
        function ProperCase($string)
        {
         	$string = strtolower($string);
    		$string = substr_replace($string, strtoupper(substr($string, 0, 1)), 0, 1);
    		return $string;
        }
         
    	function loadJSON($url)
    	{
//        	echo time().": ";
        	if (isset($this->cache[ md5($url) ]))
        	{
//            	echo "cache hit ($url)<br>";
        	    return $this->cache[ md5($url) ];
        	}
//        	echo "non cache hit ($url)<br>";
        	
    		$c = curl_init();
    		curl_setopt($c, CURLOPT_URL, INFOSYS_URL.$url);
    		curl_setopt($c, CURLOPT_HEADER, false);
    		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($c, CURLOPT_REFERER, '');
    		curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
    		curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: '.INFOSYS_HOSTNAME));
    		$data = curl_exec($c);
    		curl_close($c);
    		$json = json_decode($data,true);
    		
    		$this->cache[ md5($url) ] = $json;
    		
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
        
     
      	function __construct(){
          	$this->cache = array();
     	}
     	
	    function getFields()
	    {
			$result = array();
			$result[] = 'user_gds';
			return $result;
	     }
	     function validateFields()
	     {
			$customer = $_SESSION['customer'];
		     
	     	if ($customer['participant']!='deltager')
	     		return true;
	     	
	     	if ($this->get_age()<13)
	     		return true;
	     	
	     	if ($customer['aktiviteter_is_spilleder']>0)
	     		return true;
	     	
		     if (count($customer['user_gds'])<3)
		     {
				return __etm("nocat_178")."<br><br>";
		     }
		     
	     	return true;
	     }
	
		function getGDSCategory($a_id)
		{
			$json = $this->loadJSON("api/gdscategories/".$a_id);
			return $json;
		}

		function render(){
			$customer = $_SESSION['customer'];
			
			$days = array();
			
		     $json = $this->loadJSON("api/gds/*");
		     $vagter = array();
		     $lang = get_language();

		     foreach($json as $tjans)
		     {
		     	$gds_id = $tjans['category_id'];
		     	$title = $tjans['info']['title_'.$lang];
		     	$category_gds = $this->getGDSCategory($gds_id);
		     	$title = $category_gds[0]['category_'.$lang];
		     	
		     	
		     	$description = $tjans['info']['description_'.$lang];
		     	$vagter = $tjans['vagter'];
		     	foreach($vagter as $vagt)
		     	{
		     		$periode = $vagt['period'];
		     		
		     		$day = substr($periode,0,strpos($periode," "));
		     		$daypart = substr($periode,strpos($periode," ")+1,strlen($periode));
		     		$time = strtotime($day);
		     		if (!isset($days[$time])){
			     		$days[$time][$gds_id] = array();
		     		}
		     		$days[$time][$gds_id]['gds_id'] = $gds_id;
		     		$days[$time][$gds_id]['title'] = $title;
		     		$days[$time][$gds_id]['description'] = $description;
		     		$days[$time][$gds_id]['day'] = $day;
		     		$days[$time][$gds_id]['parts'][] = $daypart;
		     	}
		     	
		     }
			?>
			<div id='aktiviteter'>     
				<?php
				
				function cmpDays($a, $b)
				{
				    return $a>$b;
				}

				
//				var_dump($_SESSION['customer']['aktiviteter_is_spilleder']);
				
				uksort($days, "cmpDays");
                $day_count = 1;
				foreach($days as $time=>$day)
				{
					if ($this->attends_con_at_numday($day_count))
					{
    					$these_months = explode(",","januar,februar,marts,april,maj,juni,juli,august,september,oktober,november,december");
    					$these_days = explode(",","mandag,tirsdag,onsdag,torsdag,fredag,lørdag,søndag");
    					$this_year = date("Y",$time);
    					$this_day = date("j",$time);
    					$this_month = $these_months[date("n",$time)-1];
    					$this_day_text = $these_days[date("N",$time)-1];
    					
    					?>
    					<table border=0 cellspacing=0 cellpadding=0 style='border:1px solid black;margin-bottom:20px;'>
    					<tr>
    						<th colspan='4' class='day'><?php echo __etm($this->ProperCase($this_day_text))?> (<?php echo $this_day?>. <?php echo $this_month?> <?php echo $this_year?>)</th>
    					</tr>
    					<tr>
    						<th class='caption'><?php __etm('Aktiviteter');?></th>
    						<th style='width:150px;' class='time'><?php echo __etm('nocat_307')?></th>
    						<th style='width:150px;' class='time'><?php echo __etm('nocat_308')?></th>
    						<th style='width:150px;' class='time'><?php echo __etm('nocat_309')?></th>
    					</tr>
    					<?php
    					foreach($day as $gds)
    					{
    						?>
    						<tr>
    							<td>&nbsp;<?php echo $gds['title']?></td>
    							<?php
    							
    							$arr = array("04-12","12-17","17-04");
    							foreach($arr as $daypart)
    							{
    								if (in_array($daypart,$gds['parts']))
    								{
    									$gds_id = $gds['gds_id'];
    									$value = $gds['gds_id']."|".$gds['day']." ".$daypart;
    									$checked = true;
    									
                                    if ($_SESSION['customer']['participant'] != "deltager"){
        									$checked = false;
                                    }
    									
    									if (isset($customer['user_gds']))
    									{
    										$checked = in_array($value, $customer['user_gds']);
    									}
    									?><td>
    										<div class='event priority0' style='width:148px;text-align:center;'>
    											<input type='checkbox' value='<?php echo $value;?>' style='' name='user_gds[]'<?php echo $checked?" checked":""?>>
    										</div>
    									</td><?php
    								}
    								else{
    									?><td>&nbsp;</td><?php
    								}
    							}
    							?>
    						</tr>
    						<?php
    					}
					}
                    $day_count++;
				}
				?>
				</table>			
			</div>
			
		     <div class='error' id='GDS_v1'><?php echo getError("GDS_v1");?></div></li>
		     
			<?php
		}
	}
