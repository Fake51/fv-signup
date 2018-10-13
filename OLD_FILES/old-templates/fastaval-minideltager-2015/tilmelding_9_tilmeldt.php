<?php 
  
    class DeltagerTilmeldingTilmeldt12 extends SignupPage
    {
        public function init()
        {
        }
        
        public function canShow()
        {
            return true;
        }
        
        function request_email_by_email($email)
        {
            $create_api = 'participant/request-signup-emails';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1/".$create_api);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_REFERER, '');
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: infosys.fastaval.dk'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array('email' => $email));
            curl_exec($curl);
        	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($httpcode==200)
                return true;
            return false;
        }
        
        function request_email_by_user_id($user_id)
        {
            $create_api = 'participant/request-signup-emails';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1/".$create_api);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_REFERER, '');
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: infosys.fastaval.dk'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array('user_id' => $user_id));
            curl_exec($curl);
        	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($httpcode==200)
                return true;
            return false;
        }
        
        function create_user($email, $brugertype){
            $create_api = 'api/participant/create';
            $create_data = array(
                'email'                         => $customer['email'],
                'brugertype'                    => $brugertype,
            );
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1/".$create_api);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_REFERER, '');
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: infosys.fastaval.dk'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array('data' => json_encode($create_data)));
            if (!$data = curl_exec($curl)) {
                die('failed to create participant');
            }
            curl_close($curl);
            $parsed_data = json_decode($data,true);
            return $parsed_data;
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
    		$is_tilmeldt = false;
    		if (isset($_SESSION['is_tilmeldt']))
    		    $is_tilmeldt = true;
    		
    		$show_tilmeldt = true;
    		
    		if (!$is_tilmeldt)
    		{
    			$this->brugertype = "Deltager";
    			if (gf('participant')=="forfatter")$this->brugertype = "Forfatter";
    			if (gf('participant')=="arrangoer")$this->brugertype = "Arrangør";
    			if (gf('participant')=="dirtbuster")$this->brugertype = "Dirtbuster";
    			if (gf('participant')=="infonaut")$this->brugertype = "Infonaut";
    			if (gf('participant')=="brandvagt")$this->brugertype = "Brandvagt";
    			if (gf('participant')=="kioskninja")$this->brugertype = "Kioskninja";
        		
        		
                $is_ung = false;
                $age = $this->calculate_age(gf('birthday-year').'-'.gf('birthday-month').'-'.gf('birthday-day'));
                if ($age<21)$is_ung = true;
        		
        		$is_logged_in = gf('__logged_in');
        		if ($is_logged_in)
        		{
            		$username = gf('__username');
            		$password = gf('__password');
            		
                    $ic = new InfosysConnect();
                    $ic->create_cookiejar();
                    $token = $ic->app_get_token();
                    $logged_in = $ic->app_auth($token);
                    if ($logged_in)
                    {
                        $user_login = $ic->user_login($username, $password);
                        $user_id = $user_login['id'];
                		$betalings_url = $user_login['payment_url'];
            		}
            		else
            		{
                		// could not log in? wtf.. 
                		// create a new..
                		$parsed_data = $this->create_user(gf('email'),$this->brugertype);
                		$user_id = $parsed_data['id'];
                		$betalings_url = $parsed_data['payment_url'];
                		$username = gf('email');
                		$password = $parsed_data['password'];
            		}
            		
        		}
        		else // create one..
        		{
            		$parsed_data = $this->create_user(gf('email'),$this->brugertype);
            		$user_id = $parsed_data['id'];
            		$betalings_url = $parsed_data['payment_url'];
            		$username = gf('email');
            		$password = $parsed_data['password'];
            		
        		}

                $customer = $_SESSION['customer'];
    
    			$sprog = array();
    			if ($customer['other_dansk'])$sprog[]='dansk';
    			if ($customer['other_scandinavisk'])$sprog[]='skandinavisk';
    			if ($customer['other_engelsk'])$sprog[]='engelsk';
        		
    			$signup_data = array(
    			    'id'          => $user_id,
    			    'participant' => array(
        			    
        			    // side 2
    			        'fornavn'                       => $customer['firstname'],
    			        'efternavn'                     => $customer['lastname'],
    			        'gender'                        => $customer['sex']=="Mand"?"m":"k",
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
    			        'desired_activities'            => null,
    			        'supergm'                       => "nej",
    			        
    			        // side 8 GDS
    			        'flere_gdsvagter'               => $customer['more_gds']?"ja":"nej",
    			        'supergds'                      => $customer['super_gds']?"ja":"nej",
    			        
    			        // side 9 wear
    			        
    			        // side 10 mere information
    			        'sprog'                         => implode(",",$sprog),
    			        'interpreter'                  => $customer['simultantolk']?"ja":"nej", 
    			        'international'                 => $customer['other_international']?"ja":"nej",
    			        'arrangoer_naeste_aar'          => $customer['other_2010']?"ja":"nej",
    			        'rig_onkel'                     => $customer['other_richbastard']?"ja":"nej",
    			        'hemmelig_onkel'                => $customer['other_secretbastard']?"ja":"nej",
    			        'ready_mandag'                  => $customer['ready_mandag']?"ja":"nej",
    			        'ready_tirsdag'                 => $customer['ready_tirsdag']?"ja":"nej",
    			        'skills'                        => $customer['special_skills'],
    			        'deltager_note'                 => $customer['other_comments'],
    			        'original_price'                => $customer['__original_price'],
    			        
    			    ),
    		        'session'                       => serialize($_SESSION['customer']),
    			 );
    			
    			
    			$signup_data['entrance'] = array();
    			/* ENTRANCE */
    			if ($customer['new_alea'])
    			{
    			    $signup_data['entrance'][] = array('entrance_id'=>20); // alea medlemskab
    			    
    				if ($customer['days_all'])
    				{
        				if ($this->brugertype!="Deltager")
        					$signup_data['entrance'][] = array('entrance_id'=>23); // partout alea arrangør
        				else
        				{
            				if ($is_ung)
            					$signup_data['entrance'][] = array('entrance_id'=>15); // partout alea ung 
            				else
            					$signup_data['entrance'][] = array('entrance_id'=>2); // partout alea deltager
        				}
    				}
    			}
    			else
    			{
    				if ($customer['days_all'])
    				{
        				if ($this->brugertype!="Deltager") 
        				    $signup_data['entrance'][] = array('entrance_id'=>22); // partout ikke alea arrangør
        				else
        				{
            				if ($is_ung)
            				    $signup_data['entrance'][] = array('entrance_id'=>16); // partout ikke alea ung
            				else
            				    $signup_data['entrance'][] = array('entrance_id'=>1); // partout ikke alea deltager
        				}
    				}
    			}
    			if ($customer['days_1'])$signup_data['entrance'][] = array('entrance_id'=>3);
    			if ($customer['days_2'])$signup_data['entrance'][] = array('entrance_id'=>4);
    			if ($customer['days_3'])$signup_data['entrance'][] = array('entrance_id'=>5);
    			if ($customer['days_4'])$signup_data['entrance'][] = array('entrance_id'=>6);
    			if ($customer['days_5'])$signup_data['entrance'][] = array('entrance_id'=>7);
    			
    			
    
    			
    			
    			/* SLEEPING */
    			if ($customer['days_sleeping'])
    			{
    				if ($this->brugertype!="Deltager") // arrangør overnatning
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
                
                
                
                
                
    			
    			
    			/* GDS */
    			$signup_data['gds'] = array();
    			if ((isset($customer['user_gds']))&&(is_array($customer['user_gds'])))
    			{
    				foreach($customer['user_gds'] as $tjans)
    				{
    					list($gds_id,$gds_time) = explode("|",$tjans);
    					$signup_data['gds'][] = array(
    						'kategori_id' => $gds_id,
    						'period' => $gds_time
    					);
    				}
    			}
                
    			
                
    			    
                $signup_api = 'api/participant/signup';
    			$curl = curl_init();
    			curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1/".$signup_api);
    			curl_setopt($curl, CURLOPT_HEADER, false);
    			curl_setopt($curl, CURLOPT_REFERER, '');
    			curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
    			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: infosys.fastaval.dk'));
    			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    			curl_setopt($curl, CURLOPT_POST, true);
    			curl_setopt($curl, CURLOPT_POSTFIELDS, array('data' => json_encode($signup_data)));
    			$return_data = curl_exec($curl);
    			curl_close($curl);

                $return_obj = json_decode($return_data);
                if ($return_obj->status=="fail"){
                    ?>
                    <h1 class='entry-title'><?php __etm('Oups !');?></h1>
                    <div id="tilmelding-info">
                        <p><?php __etm('Du bliver desværre nok nødt til at starte helt forfra. Prøv at kontakte info@fastaval.dk - det kan være en generel fejl i hele tilmdeldingen');?></p>
                    </div>
                    <?php
                        $show_tilmeldt = false;
                }
    		}
            
            if ($show_tilmeldt)
            {
                
                //$sent_email = $this->request_email_by_email($customer['email']);
                //$sent_email = $this->request_email_by_user_id($user_id);
                $sent_email = true;
        		?>
                <h1 class='entry-title'><?php __etm('Du er nu tilmeldt!');?></h1>
                <?php
                    
                    if ($sent_email===FALSE)
                    {
                        echo "<p>";
                        __etm('<strong>Der var problemer med at sende dig din tilmeldingsemail. Kontakt info@fastaval.dk</strong>');
                        echo "</p>";
                    }
                    
                ?>
                <div id="tilmelding-info">
                    <p><?php __etm('Din tilmelding er blevet registreret. Du burde inden længe modtage en email med dine tilmeldte oplysninger! Nu mangler du bare at betale.');?></p>
                    
                    <h2><?php __etm('Betal med kort med det samme');?></h2>
                    <p><?php __etm('Du kan betale for din tilmelding med det samme ved at benytte online-betalingen:');?> <strong style="font-size: 1.1em"><a href="<?php echo $betalings_url;?>"><?php __etm('betal for tilmeldingen');?></a></strong>. <?php __etm('Vær opmærksom på at selve betalingen foregår på rollespil.dk.');?></p>
                    <p><?php __etm('Det er muligt at betale med de mest gængse kort-typer: dankort, visa, mastercard, etc. Vær opmærksom på at der bliver lagt et gebyr på betalingen fra Rollespil.dk - på dankort mellem 3 og 14 kr. Derudover er der et dankort-gebyr fra indløseren på mellem 0,50 og 2 kr. På udenlandsk udstedte kort er gebyret 3.75% af beløbet.');?></p>
                    <p><?php __etm('Online-betaling er kommet på plads i et samarbejde med <a href="http://www.rollespil.dk/" target="_blank"><img src="http://www.fastaval.dk/wp-content/uploads/2015/01/logo_rollespil_blad.jpg" alt=""></a> - mange tak til dem!');?></p>
                    <p><?php __etm('Efter betaling vil du få en bekræftelsesmail fra rollespil.dk - det er vigtigt at du gemmer denne, da den indeholder link til kvittering og dermed bevis på din betaling.');?></p>
                    
                    <h3><?php __etm('Betal inden d. 30/3');?></h3>
                    <p><?php __etm('Hvis du ikke kan betale din tilmelding med det samme kan du gøre det senere. Den mail du modtager med bekræftelse af din tilmelding indeholder også det ovenstående samme link til online-betaling. Du kan dermed også betale senere, nemt og bekvemt.');?></p>
                    
                    <p><?php __etm('Du skal dog huske at betale inden d. 30/3. Tilmeldinger der ikke er betalt d. 30/3 vil blive annulleret. Dette er en ændring fra tidligere år. Fastaval har i en række år blandt andet haft problemer med wear og mad, der bliver bestilt, men ikke betalt og afhentet, hvilket koster penge. Derfor bliver vi nødt til at bede om at du betaler på forhånd. På den måde kan vi mindske vores spild, så vi kan bruge pengene på noget sjovere.');?></p>
                    <p><?php __etm('Derfor tillader vi os også at påminde dig om betaling inden fristen udløber, hvis betalingen ikke er gennemført.');?></p>
                    
                    <h2><?php __etm('Betal med bank overførsel');?></h2>
                    <p><?php __etm('Har du ikke mulighed for at betale online via betalings-siden, så er det også muligt at betale via bank-overførsel. Vær opmærksom på at 14-dages fristen for betaling også gælder her.');?></p>
                    <p><?php __etm('Her skal du dog være opmærksom på at der er et gebyr på 20 kr. per overførsel pga det ekstra arbejde der ligger i at udrede overførslerne - det tager meget lang tid at gå igennem. Dette gebyr skal du selv lægge til beløbet du overfører - ellers vil du blive afkrævet det ved check-in på Fastaval.');?></p>
                    <p><?php __etm('Bank overførsel foregår på følgende vis');?></p>
                    <ul>
                        <li><?php __etm('Senest 30/3 skal pengene være overført til Fastavals konto - ellers annulleres tilmeldingen');?></li>
                        <li><?php __etm('Ved indbetaling på indbetalingskort: Kortart +73 Kreditornr 84520098');?></li>
                        <li><?php __etm('Ved bankoverførsel til vores bankkonto: Reg.: 9314 / kontonummer: 4573884464');?></li>
                        <li><?php __etm('Ved indbetaling fra en bank UDENFOR Danmark kontakt venligst bunker@fastaval.dk');?></li>
                    </ul>
                    <p><?php __etm('Ved alle muligheder skal du HUSKE at anføre dit NAVN og DELTAGERNUMMER - ellers risikerer du at vi ikke kan henføre betalingen til lige præcis din tilmelding (og så er det ikke så meget en betaling men bare en donation til Fastaval).');?></p>
                    <br>
                    <p><?php __etm('Vi ses på Fastaval!');?></p>
                </div>
                <?php
                    
                    
                unset($_SESSION['customer']);
                $_SESSION['is_tilmeldt'] = true;
            }
        }
    }
  

