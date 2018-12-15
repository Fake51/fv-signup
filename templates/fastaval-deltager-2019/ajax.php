<?php
    
/*
    
app -> infosys: api/auth (GET)
app <- infosys: response with token
app -> infosys: api/auth (POST med token + shared secret hash)
app <- infosys: status 200 for authentication

POST til api/auth.php skal være med body der indeholder:
{"user":"Fastaval app","token":"dec697dff8886816ab80e7cba333b4fd"}

(dvs. token skal være et hash af den token du får i første response samt shared secret)
    

*/



/*

function infosys_app_auth($cookie_jar)
{
    $infosys_url_auth = "api/auth";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, INFOSYS_URL.$infosys_url_auth);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_REFERER, '');
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: infosys.fastaval.dk'));
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	if (!$data = curl_exec($curl)) {
	    return "server failed";
	}
	curl_close($curl);
	$parsed_data = json_decode($data,true);
    return $parsed_data['token'];
}



function infosys_app_login($cookie_jar,$auth_token)
{
    $shared_secret_token = "ohqu2oaRik0Foh2cai0chaeyaejev3th";
    $send_data = array(
        'user' => "Fastaval Deltager Tilmelding",
        'token' => md5($shared_secret_token . $auth_token)
    );
    $infosys_url_auth = "api/auth";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, INFOSYS_URL.$infosys_url_auth);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_REFERER, '');
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: infosys.fastaval.dk'));
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, array('data' => json_encode($send_data)));
	curl_exec($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	if ($httpcode==200)
	    return true;
	return false;
}
*/


add_action( 'wp_ajax_tilmelding_login', 'ajax_tilmelding_login' );
add_action( 'wp_ajax_nopriv_tilmelding_login', 'ajax_tilmelding_login' );
function ajax_tilmelding_login()
{
    
    //$cookie_jar = tempnam('/tmp','tilmelding-cookie-'.rand());
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $ic = new InfosysConnect();
    $ic->create_cookiejar();
    $token = $ic->app_get_token();
    $logged_in = $ic->app_auth($token);
    
    
    if ($logged_in){
        // http://infosys.fastaval.dk/api/v2/user-data/ego@kristoffermads.dk?pass=369167
        $user_login = $ic->user_login($username, $password);
        if (is_array($user_login))
        {
            session_start();
            
            
            if ($user_login['session']=="")
            {
                $_SESSION['customer']['firstname'] = $user_login['fornavn'];
                $_SESSION['customer']['lastname'] = $user_login['efternavn'];
                $_SESSION['customer']['address1'] = $user_login['adresse1'];
                $_SESSION['customer']['address2'] = $user_login['adresse2'];
                $_SESSION['customer']['zipcode'] = $user_login['postnummer'];
                $_SESSION['customer']['city'] = $user_login['by'];
                $_SESSION['customer']['email'] = $user_login['email'];
                $_SESSION['customer']['email_repeat'] = $user_login['email'];
                $_SESSION['customer']['country'] = $user_login['land'];
                $_SESSION['customer']['mobile'] = $user_login['mobiltlf'];
                $_SESSION['customer']['bringing_mobile'] = $user_login['medbringer_mobil']=="nej"?0:1;
                $_SESSION['customer']['sex'] = $user_login['gender']=="m"?"Mand":"Kvinde";
                
                $_SESSION['customer']['with_club'] = $user_login['ungdomsskole']!=""?1:0;
                $_SESSION['customer']['club_name'] = $user_login['ungdomsskole'];
                
                $brugertype = "deltager";
        		if ($user_login['brugerkategori']=="Forfatter")$brugertype = "forfatter";
        		if ($user_login['brugerkategori']=="Arrangør")$brugertype = "arrangoer";
        		if ($user_login['brugerkategori']=="Dirtbuster")$brugertype = "dirtbuster";
        		if ($user_login['brugerkategori']=="Infonaut")$brugertype = "infonaut";
        		if ($user_login['brugerkategori']=="Brandvagt")$brugertype = "brandvagt";
        		if ($user_login['brugerkategori']=="Kioskninja")$brugertype = "kioskninja";
                $_SESSION['customer']['participant'] = $brugertype;
        
                $_SESSION['customer']['alternative_phone'] = $user_login['tlf'];
                $_SESSION['customer']['other_health'] = $user_login['medical_note'];
                
                $_SESSION['customer']['special_area'] = $user_login['arbejdsomraade'];
                $_SESSION['customer']['special_game'] = $user_login['scenarie'];
                
                $birthday = $user_login['birthdate'];
                $b_date = new DateTime($birthday);
                $_SESSION['customer']['birthday-day'] = date_format($b_date,"d")*1;
                $_SESSION['customer']['birthday-month'] = date_format($b_date,"m")*1;
                $_SESSION['customer']['birthday-year'] = date_format($b_date,"Y")*1;
             
                $_SESSION['customer']['special_sleeping'] = $user_login['sovesal']=="nej"?0:1;
            }
            else{
                $_SESSION['customer'] = unserialize($user_login['session']);
            }
            
            $_SESSION['customer']['__logged_in'] = 1;
            $_SESSION['customer']['__username'] = $username;
            $_SESSION['customer']['__password'] = $password;
            
            
            
            $ic->destroy_cookiejar();
            echo "1";
            die(1);
        }
        else
        {
            $ic->destroy_cookiejar();
            echo "2";
            die(2);
        }
    }
    else
    {
        $ic->destroy_cookiejar();
        echo "3";
        die(3);
    }
    
    $ic->destroy_cookiejar();
    echo "10";
    die(10);
}


add_action( 'wp_ajax_tilmelding_forgot', 'ajax_tilmelding_forgot' );
add_action( 'wp_ajax_nopriv_tilmelding_forgot', 'ajax_tilmelding_forgot' );
function ajax_tilmelding_forgot()
{
    
    $username = $_POST['username'];
    
    $url = "api/request-password-email";
	$c = curl_init();
    curl_setopt($c, CURLOPT_URL, INFOSYS_URL.$url);
    curl_setopt($c, CURLOPT_HEADER, false);
    curl_setopt($c, CURLOPT_REFERER, '');
    curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
    curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: '.INFOSYS_HOSTNAME));
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, array('data' => json_encode(array('email' => $username) )));
	$data = curl_exec($c);
	curl_close($c);
    echo "1";
    die();
}

