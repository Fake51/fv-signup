<?php

class InfosysConnect
{
    function __construct(){
        $this->cookie_jar = null;
    }
    
    function create_cookiejar()
    {
        $this->cookie_jar = tempnam('/tmp','tilmelding-cookie-'.rand());
    }
    
    function destroy_cookiejar()
    {
        if ($this->cookie_jar)
            unlink($this->cookie_jar);
    }
    
    function app_get_token()
    {
        $infosys_url_auth = "api/auth";
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, INFOSYS_URL.$infosys_url_auth);
    	curl_setopt($curl, CURLOPT_HEADER, false);
    	curl_setopt($curl, CURLOPT_REFERER, '');
    	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
    	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: '.INFOSYS_HOSTNAME));
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	if ($this->cookie_jar!=null)
    	{
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_jar);
    	}
    	if (!$data = curl_exec($curl)) {
    	    return "server failed";
    	}
    	curl_close($curl);
    	$parsed_data = json_decode($data,true);
        return $parsed_data['token'];
    }

    function app_auth($token)
    {
        $shared_secret_token = "ohqu2oaRik0Foh2cai0chaeyaejev3th";
        $send_data = array(
            'user' => "Fastaval Deltager Tilmelding",
            'token' => md5($shared_secret_token . $token)
        );
        $infosys_url_auth = "api/auth";
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, INFOSYS_URL.$infosys_url_auth);
    	curl_setopt($curl, CURLOPT_HEADER, false);
    	curl_setopt($curl, CURLOPT_REFERER, '');
    	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
    	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: '.INFOSYS_HOSTNAME));
    	if ($this->cookie_jar!=null)
    	{
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_jar);
    	}
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
    
    function user_login($username, $password)
    {
        $infosys_url_auth = "api/v2/user-data/".$username."?pass=".$password;
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, INFOSYS_URL.$infosys_url_auth);
    	curl_setopt($curl, CURLOPT_HEADER, false);
    	curl_setopt($curl, CURLOPT_REFERER, '');
    	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
    	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: '.INFOSYS_HOSTNAME));
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	if ($this->cookie_jar!=null)
    	{
            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_jar);
    	}
    	if (!$data = curl_exec($curl)) {
        	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    	    return "server failed";
    	}
    	curl_close($curl);
    	$parsed_data = json_decode($data,true);
        return $parsed_data;
    }
    
    
}

