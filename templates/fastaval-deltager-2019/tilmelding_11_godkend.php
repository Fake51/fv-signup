<?php 

    class DeltagerTilmeldingPratiskGodkend11 extends SignupPage
    {
        public function getSlug()
        {
            return 'godkend';
        }
        
        public function getTitle(){
            return __tm('navigation_11');
        }
        
        public function init()
        {
        }
        
        public function canShow()
        {
            return true;
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
        
    	function loadJSON($url){
    		$c = curl_init();
    		curl_setopt($c, CURLOPT_URL, get_infosys_url().$url);
    		curl_setopt($c, CURLOPT_HEADER, false);
    		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($c, CURLOPT_REFERER, '');
    		curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
    		$data = curl_exec($c);
    		var_dump($data);
    		curl_close($c);
    		$json = json_decode($data,true);
         	return $json;
    	} 

		function getAfvikling($a_id)
		{
			$json = $this->loadJSON("api/schedules/".$a_id);
			return $json;
		}

		function getAktivitet($a_id)
		{
			$json = $this->loadJSON("api/activities/".$a_id);
			return $json;
		}

		function getGDS($a_id)
		{
			$json = $this->loadJSON("api/gdscategories/".$a_id);
			return $json;
		}

		function getWear($a_id)
		{
			$json = $this->loadJSON("api/wear/".$a_id);
			return $json;
		}


        function renderLine($text,$key,$options=null,$class=false,$force=false)
        {
            if (gf($key)=="")
            {
	          	if ($force!==false)
	          	{
                    $value = $options[$force];
                    if (!is_numeric($value))
                        $value = $value;
                    $this->renderText($text,$value,$class);
	          	}
                return 0;
            }
            
            $value = gf($key);
            if ($options!=null)
                if (isset($options[gf($key)]))
                    $value = $options[gf($key)];
	             
            if (!is_numeric($value))
                $value = $value;
            
            $value = htmlentities(stripslashes($value),ENT_COMPAT | ENT_HTML401,"UTF-8");
            /* ?><tr><th<?php echo ($class?" class='".$class."'":"")?>><?php echo $text?></th><td<?php echo ($class?" class='".$class."'":"")?>><?php echo $value?></td></tr><?php */
            $this->renderText($text,$value,$class);
            return 1;
        }
        function renderSpace()
        {
            ?>
            <tr>
                <td>&nbsp;</td><td>&nbsp;</td>
            </tr>
            <?php
        }
        function renderText($name,$text,$class=false)
        {
            if (substr($name, -1)!=":")
            {
                $name = $name.":";
            }
            ?>
            <tr>
                <td valign='top' colspan='1' class='text' <?php echo ($class?" class='".$class."'":"")?>><?php echo $name;?></td>
                <td valign='top' colspan='1' class='value' <?php echo ($class?" class='".$class."'":"")?>><?php echo $text;?></td>
            </tr>
            <?php
        }
	     function renderHeadline($tekst, $class=false, $editlink=null){
	          ?>
	          <tr>
	               <th<?php echo ($class?" class='".$class."'":"")?> colspan='1' class='headline'><?php echo $tekst?></th>
	               <th<?php echo ($class?" class='".$class."'":"")?> colspan='1' class='headline'>
    	               <?php 
        	                if ($editlink)
        	                {
            	                if (($editlink>2) && ($_SESSION['customer']['participant']=="deltager"))
            	                    $editlink--;
            	                    
                                $link = "step-".$editlink."?lang=".get_language();
            	                ?>
                                <a href='<?php echo $link;?>'><?php __etm('nocat_132');?></a>
                                <?php
        	                }
        	           ?>
    	           </th>
	          </tr>
	          <?php
	     }

		function renderWearJSON($json){
			
			$bucket = array();
			foreach($json as $wear)
			{
				for($i=0;$i<count($wear['prices']);$i++)
				{
					$price = $wear['prices'][$i];
					if (!isset($bucket[$price['wear_id']]))
					{
						$bucket[$price['wear_id']] = $price;
					}
					else
					{
						if ($bucket[$price['wear_id']]['price'] > $price['price'])
							$bucket[$price['wear_id']] = $price;
					}
				}
			}
			$my_wear = array();
			foreach($json as $wear)
			{
				foreach ($bucket as $key=>$b)
				{
					if ($b['wear_id']==$wear['wear_id']){
						$wear['price'] = $b;
						$my_wear[]=$wear;
					}
				}	
			} 			
			
			
			foreach($my_wear as $wear)
			{
			     //$navn = translate("wear","navn",$wear['wear_id'],$wear["title_dk"]);
			     $navn = $wear["title_".get_language()];
			     $id = $wear["wear_id"];
			     $sizerange = $wear["size_range"];
			     $pris = $wear['price']['price'];
			     $id_size = $prefix."wear_".$id."_size";
			     $id_count = $prefix."wear_".$id."_count";
			     
				if (gf($id_count)>0)
				{
					$navn = $wear['title_'.get_language()];
					$p = $pris."kr x ".gf($id_count)." = ".(gf($id_count)*$pris)."kr";
        	        $this->renderText($navn,gf($id_count)." ".__tm('stk')." ".gf($id_size)." (".$p.")");
         	        $this->priser["wear"] += gf($id_count)*$pris;
				}
			}
		} 


        function calculate_age($birthday)
        {
            $today = new DateTime();
            $diff = $today->diff(new DateTime($birthday));
            if ($diff->y)
            {
                return $diff->y;
            }
            return 0;
        }
        
		function render()
		{
    		
    		
    		?>
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("nocat_215");?>
                <?php tilm_form_prefields(); ?>
            <h1 class='entry-title'><?php __etm('nocat_216');?></h1>
                <?php echo $this->submit_tilmelding_get_html(); ?>
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("nocat_215");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
    		<?php
		}


        function is_deltager(){
            if ($this->brugertype == "Deltager")
                return true;
            if ($this->brugertype == "Juniordeltager")
                return true;
            return false;
        }


        
        function submit_tilmelding_get_html()
        {
    		$is_tilmeldt = false;
    		$show_tilmeldt = true;
			$this->brugertype = "Deltager";
			
            $customer = $_SESSION['customer'];
			
			if ($customer['participant']=="deltagerjunior") $this->brugertype = "Juniordeltager";
			if ($customer['participant']=="forfatter")      $this->brugertype = "Forfatter";
			if ($customer['participant']=="arrangoer")      $this->brugertype = "Arrangør";
			if ($customer['participant']=="dirtbuster")     $this->brugertype = "Dirtbuster";
			if ($customer['participant']=="infonaut")       $this->brugertype = "Infonaut";
			if ($customer['participant']=="brandvagt")      $this->brugertype = "Brandvagt";
			if ($customer['participant']=="kioskninja")     $this->brugertype = "Kioskninja";
  			if ($customer['participant']=="kaffekro")       $this->brugertype = "Kaffekrotjener";
  			if ($customer['participant']=="juniorarrangoer")$this->brugertype = "Fastaval Junior-arrangør";
  			
  			
    		
            $is_ung = false;
            $age = $this->calculate_age(gf('birthday-year').'-'.gf('birthday-month').'-'.gf('birthday-day'));
            if ($age<21)$is_ung = true;
    		
			$sprog = array();
			if ($customer['other_dansk'])$sprog[]='dansk';
			if ($customer['other_scandinavisk'])$sprog[]='skandinavisk';
			if ($customer['other_engelsk'])$sprog[]='engelsk';
    		
			$signup_data = array(
//			    'id'          => $user_id,
			    'participant' => array(
    			    
    			    // side 2
			        'fornavn'                       => $customer['firstname'],
			        'efternavn'                     => $customer['lastname'],
			        'gender'                        => $customer['sex']=="Mand" ? "m" : ($customer['sex']=="Kvinde"?"k" : "a"),
			        'birthdate'                     => ($customer['birthday-year']*1)."-".($customer['birthday-month']*1<10?"0":"").($customer['birthday-month']*1)."-".($customer['birthday-day']*1<10?"0":"").($customer['birthday-day']*1),
			        'email'                         => $customer['email'],
			        'tlf'                           => $customer['alternative_phone'],
			        'mobiltlf'                      => $customer['mobile'],
			        'adresse1'                      => $customer['address1'],
			        'adresse2'                      => $customer['address2'],
			        'postnummer'                    => $customer['zipcode'],
			        'by'                            => $customer['city'],
			        'land'                          => $customer['country'],
			        'medbringer_mobil'              => $customer['bringing_mobile']?"ja":"nej",
			        'brugertype'                    => $this->brugertype,
			        'ungdomsskole'                  => $customer['club_name'],
			        'medical_note'                  => $customer['other_health'],
			        
                     // side 3
			        'forfatter'                     => $customer['particpant2']?"ja":"nej",
			        'arbejdsomraade'                => $customer['special_area']?$customer['special_area']:"",
			        'scenarie'            	  	    => $customer['special_game'],
			        'sovesal'                       => $customer['special_sleeping']?'ja':'nej',
			        
			        // side 4 alea
			        
			        // side 5 indgang
			        
			        // side 6 mad
			        
			        // side 7 aktiviteter
			        'desired_activities'            => $customer['max_games'],
			        'supergm'                       => $customer['may_contact']?"ja":"nej",
			        
			        // side 8 GDS
			        'flere_gdsvagter'               => $customer['more_gds']?"ja":"nej",
			        'supergds'                      => $customer['super_gds']?"ja":"nej",
			        'desired_diy_shifts'            => intval($customer['desired_diy_shifts']),
			        
			        
			        // side 9 wear
			        
			        // side 10 mere information
			        'sprog'                         => implode(",",$sprog),
			        'interpreter'                   => $customer['simultantolk']?"ja":"nej", 
			        'international'                 => $customer['other_international']?"ja":"nej",
			        'arrangoer_naeste_aar'          => $customer['other_2010']?"ja":"nej",
//			        'rig_onkel'                     => $customer['other_richbastard']?"ja":"nej",
//			        'hemmelig_onkel'                => $customer['other_secretbastard']?"ja":"nej",
			        'ready_mandag'                  => $customer['ready_mandag']?"ja":"nej",
			        'ready_tirsdag'                 => $customer['ready_tirsdag']?"ja":"nej",
			        'skills'                        => $customer['special_skills'],
			        'deltager_note'                 => implode(array($customer['other_comments'],$customer['gds_additional_notes']),"\n\n"),
			        'original_price'                => $customer['__original_price'],
			        
			    ),
		        'session'                       => serialize($_SESSION['customer']),
			 );
			
			$signup_data['entrance'] = array();
			/* ENTRANCE */
			if ($customer['new_alea']>0)
			{
			    $signup_data['entrance'][] = array('entrance_id'=>20); // alea medlemskab
			    
			    if ($this->brugertype=="Juniordeltager")
			    {
					$signup_data['entrance'][] = array('entrance_id'=>45); // partout - junior (ingen single dage)
			    }
				else if ($customer['days_all'])
				{
    				if (!$this->is_deltager()) 
    					$signup_data['entrance'][] = array('entrance_id'=>23); // partout alea arrangør
    				else
    				{
        				if ($this->get_age()<13)
        					$signup_data['entrance'][] = array('entrance_id'=>39); // under 13
        				else if ($is_ung)
        					$signup_data['entrance'][] = array('entrance_id'=>15); // partout alea ung 
        				else
        					$signup_data['entrance'][] = array('entrance_id'=>2); // partout alea deltager
    				}
				}
			}
			else
			{
			    if ($this->brugertype=="Juniordeltager")
			    {
					$signup_data['entrance'][] = array('entrance_id'=>45); // partout - junior (ingen single dage)
			    }
				else if ($customer['days_all'])
				{
    				if (!$this->is_deltager()) 
    				    $signup_data['entrance'][] = array('entrance_id'=>22); // partout ikke alea arrangør
    				else
    				{
        				if ($this->get_age()<13)
        					$signup_data['entrance'][] = array('entrance_id'=>39); // under 13
        				else if ($is_ung)
        				    $signup_data['entrance'][] = array('entrance_id'=>16); // partout ikke alea ung
        				else
        				    $signup_data['entrance'][] = array('entrance_id'=>1); // partout ikke alea deltager
    				}
				}
			}

			if ($this->brugertype != "Juniordeltager") // Junior er tvangs-partout
			{
    			if ($customer['days_1'])$signup_data['entrance'][] = array('entrance_id'=>3);
    			if ($customer['days_2'])$signup_data['entrance'][] = array('entrance_id'=>4);
    			if ($customer['days_3'])$signup_data['entrance'][] = array('entrance_id'=>5);
    			if ($customer['days_4'])$signup_data['entrance'][] = array('entrance_id'=>6);
    			if ($customer['days_5'])$signup_data['entrance'][] = array('entrance_id'=>7);
			}
            $signup_data['entrance'][] = array('entrance_id'=>40); // gebyr
			if ($customer['days_camping'])
			{
				if ( !$this->is_deltager() ) // arrangør overnatning
    				$signup_data['entrance'][] = array('entrance_id'=>44);
				else
    				$signup_data['entrance'][] = array('entrance_id'=>43);
			}
			 
            // RICH bastards
			if ($customer['other_richbastard'])
			{
			    $signup_data['entrance'][] = array('entrance_id'=>$customer['other_richbastard']); // Rig mongol 1
			}
			if ($customer['other_secretbastard'])
			{
			    $signup_data['entrance'][] = array('entrance_id'=>$customer['other_secretbastard']); // Rig mongol 2
			}
			


			
			/* SLEEPING */
			if ($customer['days_sleeping'])
			{
				if ( !$this->is_deltager() ) // arrangør overnatning
					$signup_data['entrance'][] = array('entrance_id'=>19);
				else
					$signup_data['entrance'][] = array('entrance_id'=>8);
			}
			if ($customer['days_sleeping_1'])$signup_data['entrance'][] = array('entrance_id'=>9);
			if ($customer['days_sleeping_2'])$signup_data['entrance'][] = array('entrance_id'=>10);
			if ($customer['days_sleeping_3'])$signup_data['entrance'][] = array('entrance_id'=>11);
			if ($customer['days_sleeping_4'])$signup_data['entrance'][] = array('entrance_id'=>12);
			if ($customer['days_sleeping_5'])$signup_data['entrance'][] = array('entrance_id'=>13);
            
			if ($customer['days_rent_madras'])
				$signup_data['entrance'][] = array('entrance_id'=>14);
			if ($customer['days_camping'])
			{
				if ( !$this->is_deltager() ) // arrangør overnatning
    				$signup_data['entrance'][] = array('entrance_id'=>115);
				else
    				$signup_data['entrance'][] = array('entrance_id'=>190);
			}
			
			
			/* OTTO PARTY */
			if ($customer['otto_party']==1)
			{
				$signup_data['entrance'][] = array('entrance_id'=>21);
			}
			else if ($customer['otto_party']==2)
			{
				$signup_data['entrance'][] = array('entrance_id'=>21);
				$signup_data['entrance'][] = array('entrance_id'=>24);
			}
			else if ($customer['otto_party']==3) // hvidvin
			{
				$signup_data['entrance'][] = array('entrance_id'=>21);
				$signup_data['entrance'][] = array('entrance_id'=>41);
			}
			else if ($customer['otto_party']==4) // rødvin
			{
				$signup_data['entrance'][] = array('entrance_id'=>21);
				$signup_data['entrance'][] = array('entrance_id'=>42);
			}
            
            
            
            
            
            /* FOOD */
			$signup_data['food'] = array();
			if (gf('breakfast_2')!=0)$signup_data['food'][] = array('madtid_id'=>gf('breakfast_2'));
			if (gf('breakfast_3')!=0)$signup_data['food'][] = array('madtid_id'=>gf('breakfast_3'));
			if (gf('breakfast_4')!=0)$signup_data['food'][] = array('madtid_id'=>gf('breakfast_4'));
			if (gf('breakfast_5')!=0)$signup_data['food'][] = array('madtid_id'=>gf('breakfast_5'));
			if (gf('brainfood_dinner_1')!=0)$signup_data['food'][] = array('madtid_id'=>gf('brainfood_dinner_1'));
			if (gf('brainfood_dinner_2')!=0)$signup_data['food'][] = array('madtid_id'=>gf('brainfood_dinner_2'));
			if (gf('brainfood_dinner_3')!=0)$signup_data['food'][] = array('madtid_id'=>gf('brainfood_dinner_3'));
			if (gf('brainfood_dinner_4')!=0)$signup_data['food'][] = array('madtid_id'=>gf('brainfood_dinner_4'));
			if (gf('brainfood_dinner_5')!=0)$signup_data['food'][] = array('madtid_id'=>gf('brainfood_dinner_5'));
            
            /* WEAR */            
			$signup_data['wear'] = array();
			for ($i=0;$i<1000;$i++){
				if (isset($customer['wear_'.$i.'_count'])&&($customer['wear_'.$i.'_count']!=0)&&($customer['wear_'.$i.'_count']!=""))
				{
    				$size = $customer['wear_'.$i.'_size'];
    				if ($size==null)$size = "M";
    				
					$signup_data['wear'][] = array(
						'id' => $i,
						'size' => $size,
						'amount' => $customer['wear_'.$i.'_count']
					);
					
				}
			}
			
			
			
			
			/* GDS */
			$signup_data['gds'] = array();
			if ((isset($customer['user_gds']))&&(is_array($customer['user_gds'])))
			{
				foreach($customer['user_gds'] as $tjans)
				{
    				if ($tjans!=""){
    					list($gds_id,$gds_time) = explode("|",$tjans);
    					$signup_data['gds'][] = array(
    						'kategori_id' => $gds_id,
    						'period' => $gds_time
    					);
    				}
				}
			}
			if (isset($customer['ligeglad_gds']))
			{
    			 $signup_data['participant']['package_gds'] = 1;
			}
            
			
			
			
			/* AKTIVITETER */
			$signup_data['activity'] = array();
			
			/*
			if ($customer['event_helcon']==1)
				$signup_data['activity'][] = array('schedule_id' => 117,'priority' => 1,'type' => "spiller");
			*/
				
			if ($customer['scenarieskrivningskonkurrence']==1)
				$signup_data['activity'][] = array('schedule_id' => 205,'priority' => 1,'type' => "spiller");
				$signup_data['activity'][] = array('schedule_id' => 206,'priority' => 1,'type' => "spiller");
				$signup_data['activity'][] = array('schedule_id' => 207,'priority' => 1,'type' => "spiller");
				
			if ($customer['boardgame_competition']==1)
				$signup_data['activity'][] = array('schedule_id' => 170,'priority' => 1,'type' => "spiller");

			for ($i=0;$i<1000;$i++)
			{
				if (
				    (gf('event_'.$i)!=0) &&
				    (gf('event_'.$i)!="")
				   )
				   {
					$type = "spiller";
					$prio = gf('event_'.$i);
					if ($prio==5)
					{
						$prio = 1;
						$type = "spilleder";
					}
					// TODO "spilleder el. 1.prio"
					
					$signup_data['activity'][] = array(
						'schedule_id' => $i,
						'priority' => $prio,
						'type' => $type
					);
				}
			}
			
            
            $signup_api = 'api/v3/confirmation-data';
            if (isset($_GET['lang']))$signup_api .= "?lang=".$_GET['lang'];
            
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, get_infosys_url().$signup_api);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_REFERER, '');
			curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, array('data' => json_encode($signup_data)));
			$return_data = curl_exec($curl);
			curl_close($curl);
            return $return_data;
        }


    }
    
