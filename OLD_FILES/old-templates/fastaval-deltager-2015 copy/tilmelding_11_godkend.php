<?php 

    class DeltagerTilmeldingPratiskGodkend11 extends SignupPage
    {
        public function init()
        {
        }
        
        public function canShow()
        {
            return true;
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
            	                if (($editlink>2)&&($_SESSION['customer']['participant']=="deltager"))
            	                    $editlink--;
            	                    
                                $link = "step-".$editlink."?lang=".get_language();
            	                ?>
                                <a href='<?php echo $link;?>'><?php __etm('Rediger');?></a>
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
        	        $this->renderText($navn,gf($id_count)." stk. ".gf($id_size)." (".$p.")");
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
            $this->priser = array();
            $this->priser["wear"] = 0;
            $this->priser["overnatning"] = 0;
            $this->priser["alea"] = 0;
            $this->priser["mad"]=0;
            $this->priser["fest"]=0;
		    $this->priser['alea_rabat']=0;
            $this->priser['andet'] = 0;
			$this->priser["aktiviteter"]=0;
            
            $morgenmad_pris = 25;
            $aftensmad_normal = 55;
            $aftensmad_vegetar = 55;
            
            
            $alea_pris = 75;
            
            $entre_deltager_partout = 210;
            $entre_ung_partout = 160;
            $entre_arrangor_partout = 150;
            
            $entre_alea_deltager_partout = 85;
            $entre_alea_ung_partout = 35;
            $entre_alea_arrangor_partout = 25;
            
            $is_ung = false;
            $age = $this->calculate_age(gf('birthday-year').'-'.gf('birthday-month').'-'.gf('birthday-day'));
            if ($age<21)$is_ung = true;
            
            
            $entre_dagspris = 55;
            
            $party_pris = 80;
            $party_pris_champagne = 100;
            
            
            $overnatning_partout = 175;
            $overnatning_partout_arrangoer = 100;
            $overnatning_dagspris = 60;
            $leje_madras = 100;

            $build_board_game = 20;
            
            $rig_onkel = 300;
            
            
            $this->brugertype = "Deltager";
    		if (gf('participant')=="forfatter")$this->brugertype = "Forfatter";
    		if (gf('participant')=="arrangoer")$this->brugertype = "Arrangør";
    		if (gf('participant')=="dirtbuster")$this->brugertype = "Dirtbuster";
    		if (gf('participant')=="infonaut")$this->brugertype = "Infonaut";
    		if (gf('participant')=="brandvagt")$this->brugertype = "Brandvagt";
    		if (gf('participant')=="kioskninja")$this->brugertype = "Kioskninja";
            

			?>
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("Tilmeld");?>
                <?php tilm_form_prefields(); ?>
                
                <table id='info-table'>
    			<?php
    			$this->renderHeadline(__tm('Godkend tilmelding'), 'large');
                
                
    			$this->renderHeadline(__tm('Personlige oplysninger'),false, 2);
    		    $this->renderLine(__tm("Fornavn:"),"firstname");
    		    $this->renderLine(__tm("Efternavn:"),"lastname");
    		    $this->renderLine(__tm("Adresse:"),"address1");
    		    $this->renderLine("","address2");
    		    $this->renderLine(__tm("Postnr.:"),"zipcode");
    		    $this->renderLine(__tm("By:"),"city");
    		    $this->renderLine(__tm("Land:"),"country");
    		    $this->renderLine(__tm("Mobil:"),"mobile");
    		    $this->renderLine(__tm("Alternativt telefonnummer:"),"alternative_phone");
    		    $this->renderLine(__tm("Medbringer mobil og må kontaktes"),"bringing_mobile",array("Nej","Ja"),false,0);
    		    $this->renderLine(__tm("Køn:"),"sex");
    		    $this->renderLine(__tm("Fødselsdato (dag):"),"birthday-day");
    		    $this->renderLine(__tm("Fødselsdato (måned):"),"birthday-month");
    		    $this->renderLine(__tm("Fødselsdato (år):"),"birthday-year");
    		    $this->renderLine(__tm("Email:"),"email");
    		    $this->renderLine(__tm("Med en klub/ungdomsskole:"),"with_club",array("Nej","Ja"),false,0);
    		    $this->renderLine(__tm("Navn på klub/ungdomsskole:"),"club_name");
    		    
    		     
    		    $this->renderHeadline(__tm("Alea"),"kvit_day", 4);
    		    $this->priser["alea"] += $this->renderLine(__tm("Vil gerne meldes in i Alea"),"new_alea",array("Nej","Ja ("+$alea_pris+" kr)"),false,0) * $alea_pris;
    			
    			
    		    $vip = 0;
    		    if (gf('participant')!='deltager')
    		    {
    
        		    $this->renderHeadline(__tm("Arrangør information"), "kvit_day", 3);
        		    $vip += $this->renderLine(__tm("Deltagertype"),"participant" , array(
        		        'arrangoer'=>'Arrangør',
        		        'forfatter'=>'Forfatter',
        		        'dirtbuster'=>'Dirtbuster',
        		        'infonaut'=>'Infonaut',
        		        'kioskninja'=>'Brandvagt',
        		        'kioskninja'=>'Kioskninja',
        		    ),false,0)*1;
        		    
        		    $this->renderLine(__tm("Mit arbejdsområde"),"special_area");
        		    $this->renderLine(__tm("Mit scenarie/spil"),"special_game");
        		    $this->renderLine(__tm("Plads i arrangørsovesal"),"special_sleeping", array(__tm("Nej"),__tm("Ja"))  );
    		    }
    		    
    		    
    		    
    		    
    			$this->renderHeadline(__tm('Praktisk'), 'large');
    	        $this->renderHeadline(__tm("Indgang"),"kvit_day", 5);
                if ( gf ( 'days_all' ) == "1" )
                {
                    $the_price = 0;
                    if ( gf ( 'new_alea' ) )
                    {
                        $the_price = $entre_alea_deltager_partout;
                        if ($vip)
                        {
                            $the_price = $entre_alea_arrangor_partout;
                        }
                        else if($is_ung){
                            $the_price = $entre_alea_ung_partout;
                        }
                    }
                    else
                    {
                        $the_price = $entre_deltager_partout;
                        if ($vip){
                            $the_price = $entre_arrangor_partout;
                        }
                        else if($is_ung){
                            $the_price = $entre_ung_partout;
                        }
                    }
                    
                    $this->priser["entre"] += $this->renderLine(__tm("Partout (alle dage)"),"days_all", array('',__tm("Ja  (".$the_price." kr)")),false,0) * $the_price;
                }
              	
                if ($this->priser["entre"]==0) // Vi har ikke købt noget, så vi må have enkeltdags billet
                {
                    $this->priser["entre"] += $this->renderLine(__tm("Onsdag"),"days_1",array(__tm("Nej"),__tm("Ja")))  * $entre_dagspris;
                    $this->priser["entre"] += $this->renderLine(__tm("Torsdag"),"days_2",array(__tm("Nej"),__tm("Ja"))) * $entre_dagspris;
                    $this->priser["entre"] += $this->renderLine(__tm("Fredag"),"days_3",array(__tm("Nej"),__tm("Ja")))  * $entre_dagspris;
                    $this->priser["entre"] += $this->renderLine(__tm("Lørdag"),"days_4",array(__tm("Nej"),__tm("Ja")))  * $entre_dagspris;
                    $this->priser["entre"] += $this->renderLine(__tm("Søndag"),"days_5",array(__tm("Nej"),__tm("Ja")))  * $entre_dagspris;
                }
                
                
    		    
    		    
                if ($vip)
                {
                    $this->priser["overnatning"] += $this->renderLine(__tm("Sover på hele fastaval"),"days_sleeping",array("Nej","Ja (".$overnatning_partout_arrangoer." kr)"),false,0) * $overnatning_partout_arrangoer;
                }
                else
                {
                    $this->priser["overnatning"] += $this->renderLine(__tm("Sover på hele fastaval"),"days_sleeping",array("Nej","Ja (".$overnatning_partout." kr)"),false,0) * $overnatning_partout;
                }
                
                if ($this->priser["overnatning"] == 0)
                {
                    $this->priser["overnatning"] += $this->renderLine(__tm("Sover Onsdag/Torsdag"),"days_sleeping_1",array(__tm("Nej"),__tm("Ja"))) *   $overnatning_dagspris;
                    $this->priser["overnatning"] += $this->renderLine(__tm("Sover Torsdag/Fredag"),"days_sleeping_2",array(__tm("Nej"),__tm("Ja"))) *   $overnatning_dagspris;
                    $this->priser["overnatning"] += $this->renderLine(__tm("Sover Fredag/Lørdag"),"days_sleeping_3",array(__tm("Nej"),__tm("Ja")))  *   $overnatning_dagspris;
                    $this->priser["overnatning"] += $this->renderLine(__tm("Sover Lørdag/Søndag"),"days_sleeping_4",array(__tm("Nej"),__tm("Ja")))  *   $overnatning_dagspris;
                    $this->priser["overnatning"] += $this->renderLine(__tm("Sover Søndag/Mandag"),"days_sleeping_5",array(__tm("Nej"),__tm("Ja")))  *   $overnatning_dagspris;
                }
                
                $this->priser["overnatning"] += $this->renderLine(__tm("Leje af madras"),"days_rent_madras",array(__tm("Nej"),__tm("Ja (".$leje_madras.")")),false) * $leje_madras;
    		    
    		    
    		     
                
                $mad_array = array();
                $mad_json = $this->loadJSON("api/food/*");
                foreach($mad_json as $mad)
                {
                    foreach($mad['tider'] as $madtid)
                    {
                        $mad_array[$madtid['madtid_id']] = $madtid;
                    }
                }
                
                $food_conversion = array();
                $food_conversion[''] = "Nej tak";
                $food_conversion['0'] = "Nej tak";
                foreach($mad_json as $mad)
                {
                    foreach($mad['tider'] as $madtid)
                    {
                        
                        $title = $mad['info']['title_'.get_language()]." (".$mad['info']['price']." kr)";
                        $food_conversion[$madtid['madtid_id']] = $title;
                    }
                }
                
                
                $this->renderHeadline("Mad","kvit_day",6);
                $this->renderLine(__tm("Aftensmad onsdag")  ,"brainfood_dinner_1", $food_conversion);
                $this->renderLine(__tm("Morgenmad torsdag") ,"breakfast_2", $food_conversion);
                $this->renderLine(__tm("Aftensmad torsdag") ,"brainfood_dinner_2", $food_conversion);
                $this->renderLine(__tm("Morgenmad fredag")  ,"breakfast_3", $food_conversion);
                $this->renderLine(__tm("Aftensmad fredag")  ,"brainfood_dinner_3", $food_conversion);
                $this->renderLine(__tm("Morgenmad lørdag")  ,"breakfast_4", $food_conversion);
                $this->renderLine(__tm("Aftensmad lørdag")  ,"brainfood_dinner_4", $food_conversion);
                $this->renderLine(__tm("Morgenmad søndag")  ,"breakfast_5", $food_conversion);
                $this->renderLine(__tm("Aftensmad søndag")  ,"brainfood_dinner_5", $food_conversion);

                // ottofest                
                $this->renderLine(__tm("Ottofest"), "otto_party", array(__tm("Nej tak"),__tm("Ja tak"),__tm("Ja tak")));
                $this->renderLine(__tm("+ mousserende vin"), "otto_party", array(__tm("Nej tak"),__tm("Nej tak"),__tm("Ja tak")));
                
                if (gf('otto_party')==1)
                {
                    $this->priser["fest"] += $party_pris;
                }
                else if (gf('otto_party')==2)
                {
                    $this->priser["fest"] += $party_pris;
                    $this->priser["fest"] += $party_pris_champagne;
                }
    		    

                if(gf("breakfast_2")>0) $this->priser["mad"] += $morgenmad_pris;
                if(gf("breakfast_3")>0) $this->priser["mad"] += $morgenmad_pris;
                if(gf("breakfast_4")>0) $this->priser["mad"] += $morgenmad_pris;
                if(gf("breakfast_5")>0) $this->priser["mad"] += $morgenmad_pris;
                if(gf("brainfood_dinner_1")>0) $this->priser["mad"] += $aftensmad_normal;
                if(gf("brainfood_dinner_2")>0) $this->priser["mad"] += $aftensmad_normal;
                if(gf("brainfood_dinner_3")>0) $this->priser["mad"] += $aftensmad_normal;
                if(gf("brainfood_dinner_4")>0) $this->priser["mad"] += $aftensmad_normal;
                if(gf("brainfood_dinner_5")>0) $this->priser["mad"] += $aftensmad_normal;
    			
    			$this->renderHeadline(__tm('Aktiviteter'),'large');
    		     
    
                $aktiviteter_by_day = array();
                $afviklinger = $this->getAfvikling("*");
                foreach($afviklinger as $afvikling)
                {
                    
                    if ( gf('event_'.$afvikling['afvikling_id']) )
                    {
                        
                        if ( gf('event_'.$afvikling['afvikling_id']) != 0 )
                        {
                            $aktivitet = $this->getAktivitet($afvikling['aktivitet_id']); // load aktiviteten
                            $aktivitet = $aktivitet[0];
                            
                            $afvikling_parts = $this->getAfvikling($afvikling['afvikling_id']); // load afvikling med linked
                            
                            foreach($afvikling_parts as $single)
                            {
    
                                $single['prioritet'] = gf ( 'event_' . $afvikling['afvikling_id'] );
                                $single['aktivitet'] = $aktivitet;
                                $date = strtotime($single['start']['date']);
                                if (!isset($aktiviteter_by_day[$date]))
                                {
                                    $aktiviteter_by_day[$date] = array();
                                }
                                
                                $aktiviteter_by_day[$date][] = $single;
                            }
                        }
                    }
                }
    			
    			
    			function cmpDays($a, $b){return $a>$b;}
    			uksort($aktiviteter_by_day, "cmpDays");
    			
    			
    			$these_months = split(",","januar,februar,marts,april,maj,juni,juli,august,september,oktober,november,december");
    			$these_days = split(",","mandag,tirsdag,onsdag,torsdag,fredag,lørdag,søndag");
         		foreach ( array_keys($aktiviteter_by_day) as $day_index )
         		{
         			$afviklinger = $aktiviteter_by_day[$day_index];
         			
    				$this_year = date("Y",$day_index);
    				$this_day = date("j",$day_index);
    				
    				$this_month = $these_months[date("n",$day_index)-1];
    				$this_day_text = $these_days[date("N",$day_index)-1];
    
    				$day_text = ($this_day_text)." (".$this_day.". ".$this_month." ".$this_year.")";
    
                    $this->renderHeadline($day_text,"kvit_day", 7);
                    foreach($afviklinger as $afvikling)
                    {
                        $key = "event_".$afvikling['afvikling_id'];
                        $label = $afvikling['aktivitet']['info']['title_'.get_language()]; // så er den gratis
                        
                        if ($afvikling['linked']!=0) // det er bare en linked en
                        {
                            $key = "event_".$afvikling['linked'];
                            $this->renderLine($label,$key,array("Ikke valgt","1. prioritet", "2. prioritet","3. prioritet","4.+ prioritet","Spilleder"));
                        }
                        else // der skal betales for denne her
                        {
                            if (gf($key)==1) // der betales for 1. prio
                            {
                                $this->priser["aktiviteter"] += $afvikling['aktivitet']['info']['price'];
                                $label .= " (".$afvikling['aktivitet']['info']['price']." kr)";
                            }
                            $this->renderLine($label,$key,array("Ikke valgt","1. prioritet", "2. prioritet","3. prioritet","4.+ prioritet","Spilleder"));
                        }
                    }
         		}
    		     
                $this->renderSpace();
                $this->renderHeadline("Andet","kvit_day", 7);
                $this->renderLine(__tm("Maksimum antal scenarier jeg vil spille (0 er ingen grænse)"),"max_games");
                
                $this->renderLine(__tm("Jeg vil gerne melde mig til scenarieskrivningskonkurrencen"), "scenarieskrivningskonkurrence", array("Nej","Ja"));
                $this->renderLine(__tm("Jeg vil gerne være med til HelCon"),"event_helcon", array("Nej","Ja"));
                $aktivitet_ekstra_1 = $this->renderLine(__tm("Jeg vil gerne melde mig til byg et brætspil (20kr)"),"boardgame_competition", array("Nej","Ja")) * $build_board_game;
                
                $aktivitet_ekstra =  $aktivitet_ekstra_1;
                
                $this->renderLine(__tm("Fastaval må kontakte mig om spilleder-poster"),"may_contact",array("Nej","Ja"));
    			$this->priser["aktiviteter"] += $aktivitet_ekstra;
    		     
    		     
    		     
    			$this->renderHeadline(__tm('GDS'), false, 8);
    			if (gf('user_gds')!="")
    			{
    				foreach(gf('user_gds') as $tjans)
    				{
    					list($gds_id,$gds_time) = explode("|",$tjans);
    					list($gds_time,$periode) = explode(" ",$gds_time);
    					$time = strtotime($gds_time);
    					
    					$periode = str_replace("04-12","Morgen", $periode);
    					$periode = str_replace("12-17","Middag", $periode);
    					$periode = str_replace("17-04","Aften", $periode);
    					
    					$gds = $this->getGDS($gds_id);
    					$title = $gds[0]['category_'.get_language()];
    					$day = $these_days[date("N",$time)-1];
    					
    					$this->renderText(__tm('GDS-vagt ').' '.$day.":",$title." (".$gds_time." ".$periode.")");
    				}
    			}
    			
                for ($i=1;$i<=6;$i++)
                {
                    if (gf("gds_".$i."_3_select"))
                    {
                        $value = gf("gds_".$i."_3_select");
                        
                        $days = array(
                            1=>__tm("Onsdag"),
                            2=>__tm("Torsdag"),
                            3=>__tm("Fredag"),
                            4=>__tm("Lørdag"),
                            5=>__tm("Søndag")
                        );
                        
                        $vagt = $this->loadJSON("api/gdsshift/".$value);
                        $gds = $this->loadJSON("api/gds/".$vagt[0]['gds_id']);
                        $day = $days[$vagt[0]['start']['day']];
                        $time = $vagt[0]['start']['h']." - ".$vagt[0]['end']['h'];
                        $navn = $gds[0]["info"]["title_".get_language()];
                        
                        $this->renderText($day.":",$navn." (".$time.")");
                    }
                }
                $this->renderLine(__tm("Jeg er super GDS"),"super_gds",array(__tm("Nej"),__tm("Ja")),false,0);
                $this->renderLine(__tm("Fastaval må kontakte mig om GDS"),"more_gds",array(__tm("Nej"),__tm("Ja")),false,0);
                
    			$this->renderHeadline(__tm('Wear'), false, 9);
    		     
                $wear=$this->loadJSON("api/wear/*?brugertype=".$this->brugertype);
    	     	$this->renderWearJSON($wear);
    			
    
    			$this->renderHeadline(__tm('Andet'),'large');
    			$this->renderHeadline(__tm('&nbsp;'),false, 10);
    
                $this->renderLine(__tm("International gæst"),"other_international",array(__tm("Nej"),__tm("Ja")));
                $this->renderLine(__tm("Jeg vil spille på dansk"),"other_dansk",array(__tm("Nej"),__tm("Ja")),false,0);
                $this->renderLine(__tm("Jeg vil spille på engelsk"),"other_engelsk",array(__tm("Nej"),__tm("Ja")),false,0);
                $this->renderLine(__tm("Jeg vil spille på skandinavisk"),"other_scandinavisk",array(__tm("Nej"),__tm("Ja")),false,0);
                $this->renderLine(__tm("Jeg vil være simultantolk"),"simultantolk",array(__tm("Nej"),__tm("Ja")));
                $this->renderLine(__tm("Jeg vil arrangere Fastaval 2016"),"other_2010",array(__tm("Nej"),__tm("Ja")));
                $this->renderLine(__tm("Jeg vil rydde op tirsdag"),"oprydning_tirsdag",array(__tm("Nej"),__tm("Ja")));
                $this->renderLine(__tm("Klargøre skolen fra mandag"),"ready_mandag",array(__tm("Nej"),__tm("Ja")));
                $this->renderLine(__tm("Klargøre skolen fra tirsdag"),"ready_tirsdag",array(__tm("Nej"),__tm("Ja")));
    		     
                if ($this->renderLine(__tm("Jeg er en rig Fasta-onkel/tante"),"other_richbastard",array(__tm("Nej"),__tm("Ja")))==1)
                    $this->priser['andet']+=$rig_onkel;
                if ($this->renderLine(__tm("Jeg er en hemmelig rig Fasta-onkel/tante"),"other_secretbastard",array(__tm("Nej"),__tm("Ja")))==1)
                    $this->priser['andet']+=$rig_onkel;
    		     
                $this->renderLine(__tm("Special skills"),"special_skills");
                $this->renderLine(__tm("Andre kommentarer"),"other_comments");
                $this->renderLine(__tm("Helbredsinformation"),"other_health");

    			
    			$this->renderHeadline(__tm('Pris'), 'large');
    			
                $this->renderText(__tm("Entré"), $this->priser["entre"]);
                $this->renderText(__tm("Overnatning"), $this->priser["overnatning"]);
                $this->renderText(__tm("Alea indmeldelse"), $this->priser["alea"]);
                $this->renderText(__tm("Mad"), $this->priser["mad"]);
                $this->renderText(__tm("Fest"), $this->priser["fest"]);
                $this->renderText(__tm("Aktiviteter"), $this->priser["aktiviteter"]);
                $this->renderText(__tm("Wear"), $this->priser["wear"]);
                $this->renderText(__tm("Andet"), $this->priser["andet"]);
    		  
    		     
    			$total_pris = 0;
    			foreach($this->priser as $key=>$pris)
    			{
        			$total_pris += $pris;
    			}
    			
    		    if ($total_pris<0)
    		        $total_pris=0;
    		    
    		    $_SESSION['customer']['__original_price'] = $total_pris;
    		    
    		    $this->renderText(__tm("I alt"),$total_pris,'total');
    		    ?></table>
    		    <br><?php
    		    $_SESSION["tilmelding_pers"]['total_price'] = ($total_pris)*1;	
                ?>
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("Tilmeld");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("Forrige side");?>
        	</form>
        	<?php
		}


    }
    
    
    
    
	/*

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

*/
