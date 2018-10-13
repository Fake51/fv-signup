<?php 

    /*
        REGISTER
        
        AND SEND EMAIL 
        
        
        
    */




	     function email($to,$mail_body=""){
	          $subject = utf8_decode("Fastaval arrangør-tilmelding 2015");
	          $header = "From: Fastaval <info@fastaval.dk>\r\n";
	          if (!mail($to, $subject, $mail_body, $header))
	               echo "An error occoured while trying to send you an email.. please contact info@fastaval.dk for help.";
	     }
	
	     function signup($customer,$price="",$password="")
	     {
			$brugertype = "Deltager";
			if ($customer['participant']=="forfatter")$brugertype = "Forfatter";
			if ($customer['participant']=="arrangoer")$brugertype = "Arrangør";
			if ($customer['participant']=="dirtbuster")$brugertype = "Dirtbuster";
			if ($customer['participant']=="infonaut")$brugertype = "Infonaut";
			if ($customer['participant']=="brandvagt")$brugertype = "Brandvagt";
			if ($customer['participant']=="kioskninja")$brugertype = "Kioskninja";
			


			$create_api = 'api/participant/create';
			$signup_api = 'api/participant/signup';

            
            
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
			
			
			$signup_data = array(
			    'id'          => $parsed_data['id'],
			    'participant' => array(
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
			        'brugertype'                    => $brugertype,
			        'forfatter'                     => $customer['particpant2']?"ja":"nej",
			        'arbejdsomraade'                => $customer['special_area']?$customer['special_area']:"",
			        'scenarie'            		=> $customer['special_game'],
			        'sovesal'                       => $customer['special_sleeping']?'ja':'nej',
			        'ungdomsskole'                  => $customer['club_name']
			    ));
			
			/*
			echo "<a href='#' onClick='jQuery(\"#show_pre\").toggle();return false;'>Vis post</a><br>";
			echo "<pre id='show_pre' style='display:none;'>";
			print_r($signup_data);
			echo "</pre>";
			*/
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1/".$signup_api);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_REFERER, '');
			curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: infosys.fastaval.dk'));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, array('data' => json_encode($signup_data)));
			
			
			curl_exec($curl);
			// jeg tjekker ikke her men det kunne jeg.. crack
			// string(33) "{"status":"ok","failReason":null}"
			curl_close($curl);
			return $parsed_data;
	     }      

	     /*
	     function saveData()
	     {
	     	$session = serialize($_SESSION['tilmelding']);
	     	$tilmelding = $this->ef->createEntity("tilmeldinger");
	     	$tilmelding->price = $_SESSION["tilmelding_pers"]['total_price'];
	     	$tilmelding->session = $session;
	     	$tilmelding->username = $_SESSION['tilmelding']['customer']['email'];
	     	$tilmelding->password = $this->makePassword($_SESSION['tilmelding']['customer']['email']);
	     	$tilmelding->update();
	     	$tilmelding->load($tilmelding->id);
	     	return $tilmelding;
	     }
	     */
		
		function makePassword($seed)
		{
			$str=md5("bananmos".$seed);
			$seed=0;for($i=0;$i<strlen($str);$i++)$seed+=ord(substr($str,$i,1));
			$seed*=99;$seed%=9988;$seed+=2;
			$len=strlen("".$seed);
			for($i=0;$i<4-$len;$i++)$seed="0".$seed;
			return $seed;
		}

/*		
		function render(){
			global $language;
			$this->customer = $_SESSION['tilmelding']['customer'];
			$this->customer_price = $_SESSION["tilmelding_pers"]['total_price'];
			
			$tilmelding = $this->saveData();
			$parsed_data = array('id'=>'(ikke gemt)','password'=>'123456');
	     	if ($this->customer['test_savetodatabase']){}
	     	else
	     		$parsed_data = $this->signup($this->customer);
	     	
			$this->customer_number = $parsed_data['id'];

			if ($this->customer['test_sendmail']){}
			else {
				$tilmelding_mail = file_get_contents("./tilmelding_mail.".$_SESSION['lang'].".html");
				$tilmelding_mail = str_replace("#CUSTOMER#",$this->customer_number,$tilmelding_mail);
				$tilmelding_mail = str_replace("#PRICE#",$this->customer_price,$tilmelding_mail);
		          $this->email($this->customer['email'],$tilmelding_mail);
		          $this->email("tilmelding@fastaval.dk",$tilmelding_mail);
		          $this->email("fastavaltilmelding@gmail.com",$tilmelding_mail);
		          $this->email("stofferm@gmail.com",$tilmelding_mail);
		          
		     }
		     
			$tak_for_tilmelding = file_get_contents("./tak-for-tilmelding.".$_SESSION['lang'].".html");
			$tak_for_tilmelding = str_replace("#CUSTOMER#",$this->customer_number,$tak_for_tilmelding);
			$tak_for_tilmelding = str_replace("#PRICE#",$this->customer_price,$tak_for_tilmelding);
			echo $tak_for_tilmelding;
			
			if ($this->customer['test_resetsystem']){}
			else {
			     $_SESSION["tilmelding_pers"]['saved']  = false;
			     $_SESSION["tilmelding_pers"]['customer_number'] = $this->customer_number;
			     unset($_SESSION['tilmelding']); 
			}
			
		}
*/



    if (!isset($_SESSION['has_sent_email']))
    {
        $password = makePassword( rand() );
        $parsed_data = signup( $_SESSION['customer'] , 0 , $password );
        $password = $parsed_data['password'];
        
        $_SESSION['saved_customer_email'] = "".$_SESSION['customer']['email'];
        $_SESSION['saved_password'] = "".$password;
    }


	
?>
    <h1><?php __etm('Du er nu tilmeldt!')?></h1>
    <div id='tilmelding-info'>
        <p><?php __etm('Din tilmelding er blevet registreret. Du burde inden længe modtage en email med dine tilmeldte oplysninger!');?></p>
        <p><?php __etm('Som arrangør vil du senere kunne logge ind senere, og genbruge dine oplysninger, så du kan fortsætte din tilmelding når den almindelige tilmelding åbner.');?></p>
        <p>
            <strong><?php __etm('Login-information:');?></strong><br/>
            <?php __etm('Brugernavn:');?> <?php echo $_SESSION['saved_customer_email'];?> <br/>
            <?php __etm('Kodeord:');?> <?php echo $_SESSION['saved_password'];?>
        </p>
        <p><?php __etm('Gem ovenstående login-informationer - det er dem du bruger når du vil fortsætte din tilmelding senere. Du burde også modtage dem med en email, men for en sikkerhedsskyld, skriv dem ned.');?></p>
        <h2><?php __etm('Husk!');?></h2>
        <p><?php __etm('Udfyld informationerne til dit id-kort - gå til <a href="http://www.fastaval.dk/idkort/" target="_blank">http://www.fastaval.dk/idkort/</a> inden det er for sent !');?></p>
        <br/>
        <p><?php __etm('Vi ses til den store tilmelding!');?></p>
    </div>
	
<?php

    $send_email = false;
    if (!isset($_SESSION['has_sent_email']))
        $send_email = true;

    if ($send_email){
        $tilmelding_mail = file_get_contents(dirname(__FILE__)."/"."tilmelding_mail.html");
        $tilmelding_mail = strip_tags($tilmelding_mail);
        $tilmelding_mail = utf8_decode($tilmelding_mail);
        $tilmelding_mail = str_replace("#EMAIL#",$_SESSION['customer']['email'],$tilmelding_mail);
        $tilmelding_mail = str_replace("#PASSWORD#",$password,$tilmelding_mail);
        email($_SESSION['customer']['email'],$tilmelding_mail);
        email("tilmelding@fastaval.dk",$tilmelding_mail);
        email("fastavaltilmelding@gmail.com",$tilmelding_mail);
        email("stofferm@gmail.com",$tilmelding_mail);
        $_SESSION['has_sent_email'] = 1;
    }

    unset($_SESSION['customer']);

