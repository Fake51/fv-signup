<?php
 class wear_v1
 {
     
        function ft_renderWearJSON($json)
        {
            $language = "da";
            $user = 
             
        	$bucket = array();
    		foreach($json as $wear){
    			// for hver wear, tjek om min type er der
    			for($i=0;$i<count($wear['prices']);$i++){
    				$price = $wear['prices'][$i];
					if (!isset($bucket[$price['wear_id']])){
						$bucket[$price['wear_id']] = $price;
					}
					else{
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
             
            $last_filename = null;
        	foreach($my_wear as $wear)
        	{
        	     $navn = $wear["title_".$language];
        	     $id = $wear["wear_id"];
        	     $sizerange = $wear["size_range"];
        	     $pris = $wear['price']['price'];
        	     
        	     
        	     
        	     $id_size = $prefix."wear_".$id."_size";
        	     $id_count = $prefix."wear_".$id."_count";
        	     
        	     $filename = $wear['image'];
        	     if ($filename=="")
        	        $filename = $last_filename;
                else
                    $last_filename = $filename;
        	     
        	     
        	     echo "<li class='wear-item'>";
        		 echo "<a href='" . $filename . "' data-lightbox='" . $filename . "'><img src='".$filename."' /></a>";
        	     echo "<span class='wear_name'>".$navn."</span>\n";
        	     echo "<span class='wear_price'>".$pris.",-</span>\n";
                 
        	     $max = 8;
        	     $min = 0;
        	     
        	     if ($id==3) // infonaut
        	     {
            	     $max = 1;
            	     $min = 1;
        	     }
        	     else if ($id==4) // kioskninja
        	     {
            	     $max = 1;
            	     $min = 1;
        	     }
    	    	$number_values = array();
        	    for ($i=$min;$i<=$max;$i++)
        	    {
            	    if ($i==0)$number_values[''] = __tm('Antal');
            	    else $number_values[''.$i] = $i;
        	    }
    			renderFieldByType(array(
        			'id'=>$id_count,
        			'input-type'=>'select',
        			'input-name'=>$id_count,
        			'text'=>'',
        			'value' => $number_values,
        			'class'=> array('fullsize-label','count-select'),
    			));
        	     
        	     
        	     $sizearray = array("XS","S","M","L","XL","2XL","3XL","4XL","5XL","6XL","7XL","8XL","9XL","10XL", '92CM', '98CM', '104CM', '110CM', '116CM', '122CM', '128CM', '134CM', '140CM', '146CM', '152CM');
        	     $parts = split("-",$sizerange);
        	     $size_count=0;
        	     for ($i=array_search($parts[0],$sizearray);$i<=array_search($parts[1],$sizearray);$i++)
        	     {
        	     	$size_count++;
        	     }
                 
     	    	 if ($size_count>1)
    	    	 {
        	    	$select_values = array('' => __tm('nocat_42'));
        		    for ($i=array_search($parts[0],$sizearray);$i<=array_search($parts[1],$sizearray);$i++)
        		    {
        		        $select_values[$sizearray[$i]] = $sizearray[$i];
        		    }
        			renderFieldByType(array(
            			'id'=>$id_size,
            			'input-type'=>'select',
            			'input-name'=>$id_size,
            			'text'=>'',
            			'value' => $select_values,
            			'class'=> array('fullsize-label','size-select'),
        			));
        		}
        	    echo "</li>";
        	}
        	?>
        	<style>
            	#wear{
                	list-style:none;
                	margin:0;
                	padding:0;
                    overflow:auto;
            	}
            	#wear li{
                	width:25%;
                	float:left;
                	text-align: center;
            	}
            	#wear li img{
                	width:80%;
                	margin-left:10%;
            	}
            	#wear li span{
                    display:block;
                }
            	#wear li p{margin:0;}
            	#wear li:nth-child(4n+1){
                	clear:left;
            	}
            </style>
        	<?php
        }
     
     
	function loadJSON($url)
	{
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, "http://127.0.0.1/".$url);
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

     function validateFields(){
     	$customer = $_SESSION['tilmelding']['customer'];
     	$no="Husk st&oslash;rrelserne p&aring; alt dit wear";
		$json = loadJSON("api/wear/*");
		foreach($json as $wear){
			if (isset($customer['wear_'.$wear['wear_id'].'_count']))
				if (isset($customer['wear_'.$wear['wear_id'].'_size']))
     				if (($customer['wear_'.$wear['wear_id'].'_count']>0)&&($customer['wear_'.$wear['wear_id'].'_size']=="")){
     					return $no;
     				}
     	}
     	return true;
     }

	function render()
	{
    	$customer = $_SESSION['customer'];
        $this->brugertype = "Deltager";
		if ($customer['participant']=="forfatter")$this->brugertype = "Forfatter";
		if ($customer['participant']=="arrangoer")$this->brugertype = "Arrangør";
		if ($customer['participant']=="dirtbuster")$this->brugertype = "Dirtbuster";
		if ($customer['participant']=="infonaut")$this->brugertype = "Infonaut";
		if ($customer['participant']=="brandvagt")$this->brugertype = "Brandvagt";
		if ($customer['participant']=="kioskninja")$this->brugertype = "Kioskninja";
		
		$json = $this->loadJSON("api/wear/*?brugertype=".$this->brugertype);
		?>
		<div style='clear:both'></div>
		<ul class="formlist" id="wear">
            <?php
                $this->ft_renderWearJSON($json);
            ?>
		</ul>
	    <?php
	    //$user = array();
	}
}
