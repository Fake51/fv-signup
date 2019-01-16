<?php
    
if (!class_exists("wear_v1")){
     class wear_v1
     {
            function ft_renderWearJSON($json, $show_only_required=false)
            {
                $language = "da";
                $user = null;
                
                if (isset($_GET['lang']))
                    $language = $_GET['lang'];
                 
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
                
                usort($my_wear, 'wear_v1_callablesorter');
                
                $last_filename = null;
            	foreach($my_wear as $wear) {
            	  $navn = $wear["title_".$language];
            	  $id = $wear["wear_id"];
            	  $sizerange = $wear["size_range"];
            	  $pris = $wear['price']['price'];
            	     
            	  $max = 8;
            	  $min = 0;
            	  $required_wear = true; // dunno if this is in use
            	     
								switch ($id){
									case 23: // crew herre rabat
									case 24: // crew dame rabat
									case 3: // junior barn
									case 21: // junior herre
									case 22: // junior dame
									case 30: // sild
										$min = 0;
										$max = 1;
								}
    
            	     if ($show_only_required && !$required_wear)
            	        continue;
            	     
            	     $id_size = "wear_".$id."_size";
            	     $id_count = "wear_".$id."_count";
            	     
            	     $filename = $wear['image'];
            	     if ($filename=="")
            	        $filename = $last_filename;
                    else
                        $last_filename = $filename;
            	     
            	     
            	     echo "<li class='wear-item'>";
            		 echo "<a href='" . $filename . "' data-lightbox='" . $filename . "'><img src='".$filename."' /></a>";
            	     echo "<span class='wear_name'>".$navn."</span>\n";
            	     echo "<span class='wear_price'>".$pris.",-</span>\n";
                     
            	    
        	    	$number_values = array();
            	    for ($i=$min;$i<=$max;$i++)
            	    {
                	    if ($i==0)$number_values[''] = __tm('nocat_41');
                	    else $number_values[''.$i] = $i;
            	    }
        			renderFieldByType(array(
            			'id'=>$id_count,
            			'input-type'=>'select',
            			'input-name'=>$id_count,
            			'text'=>'',
            			'value' => $number_values,
            			'class'=> array('fullsize-label','count-select', 'wear-select'),
        			));
            	     
            	     $sizearray = array(
            	     "XXS",
            	     "XS",
            	     "S",
            	     "M",
            	     "L",
            	     "XL",
            	     "2XL",
            	     "3XL",
            	     "4XL",
            	     "5XL",
            	     "6XL",
            	     "7XL",
            	     "8XL",
            	     "9XL",
            	     "10XL", 
            	     "2ÅR", 
            	     "4/6ÅR", 
            	     "8/10ÅR", 
            	     "12/14ÅR", 
            	     "JuniorXS", 
            	     '92CM', 
            	     '98CM', 
            	     '104CM', 
            	     '110CM', 
            	     '116CM', 
            	     '122CM', 
            	     '128CM', 
            	     '134CM', 
            	     '140CM', 
            	     '146CM', 
            	     '152CM'
            	     );
            	     $parts = explode("-",$sizerange);
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
                			'class'=> array('fullsize-label','size-select', 'wear-select'),
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
                    	padding-bottom:40px;
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
    		curl_setopt($c, CURLOPT_URL, get_infosys_url().$url);
    		curl_setopt($c, CURLOPT_HEADER, false);
    		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($c, CURLOPT_REFERER, '');
    		curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2');
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
    
    	function render($show_only_required=false)
    	{
        	$customer = $_SESSION['customer'];
            $this->brugertype = "Deltager";
    		if ($customer['participant']=="deltagerjunior")$this->brugertype = "Juniordeltager";
    		if ($customer['participant']=="forfatter")$this->brugertype = "Forfatter";
    		if ($customer['participant']=="arrangoer")$this->brugertype = "Arrangør";
    		if ($customer['participant']=="dirtbuster")$this->brugertype = "Dirtbuster";
    		if ($customer['participant']=="infonaut")$this->brugertype = "Infonaut";
    		if ($customer['participant']=="brandvagt")$this->brugertype = "Brandvagt";
    		if ($customer['participant']=="kioskninja")$this->brugertype = "Kioskninja";
    		if ($customer['participant']=="kaffekro")$this->brugertype = "Kaffekrotjener";
		if ($customer['participant']=="juniorarrangoer")$this->brugertype = "Fastaval Junior-arrangør";
    		
    		$json = $this->loadJSON("api/wear/*?brugertype=".$this->brugertype);
    		?>
    		<div style='clear:both'></div>
    		<ul class="formlist" id="wear">
                <?php
                    $this->ft_renderWearJSON($json, $show_only_required);
                ?>
    		</ul>
    	    <?php
    	    //$user = array();
    	}
    }
}
