<?php
    session_start();
    header('Content-Type: text/html; charset=utf-8');
    
    function vcsys_load_translations($max_time_diff_in_seconds = 86400, $filename) 
    {
        $output = array();
    
        $translations_file = __DIR__ . '/'.$filename;
    
        if (!is_file($translations_file)) {
            return $output;
        }
    
        $dom = new DomDocument("1.0", "utf-8");
        $dom->loadXML(file_get_contents($translations_file));
    
        $xpath = new DOMXPath($dom);
        $list = $xpath->query('//translation');
    
        foreach ($list as $item) {
            if (!$item->hasChildNodes()) {
                continue;
            }
    
            $items = array();
    
            foreach ($item->childNodes as $child) {
                if ($child->nodeType !== XML_ELEMENT_NODE) {
                    continue;
                }
    
                $items[$child->nodeName] = $child->textContent;
            }
    
            if ($item->hasAttribute('last-miss')) 
            {
                $items['last-miss'] = $item->getAttribute('last-miss');
            }
    
            if ($item->hasAttribute('last-hit')) 
            {
                $items['last-hit'] = $item->getAttribute('last-hit');
            }
            
            
            
            $lang = 'da';
    
            if (isset($items[$lang])) 
            {
                $skip = false;
                if (isset($items['last-hit']))
                {
                    $diff = time() - strtotime($items['last-hit']);
                    if ($diff>$max_time_diff_in_seconds)
                        $skip = true;
                }
                
                
                // always include full translations
                if ((isset($items["da"]))&&(isset($items["en"])))
                    $skip = false;
                
                if (!$skip)
                    $output[md5($items[$lang])] = $items;
            }
        }
    
        return $output;
    }
    
    if (isset($_GET['load']))
    {
        $_SESSION['language'] = vcsys_load_translations();
    }
  
  
    function find_in_files($text)
    {
        $files = array(
            'tilmelding_1.php',
            'tilmelding_2.php',
            'tilmelding_3.php',
            'tilmelding_4_alea.php',
            'tilmelding_5_praktisk.php',
            'tilmelding_6_mad.php',
            'tilmelding_7_aktiviteter.php',
            'tilmelding_8_gds.php',
            'tilmelding_9_wear.php',
            'tilmelding_10_moreinfo.php',
            'tilmelding_11_godkend.php',
            'tilmelding_12_tilmeld.php',
        );
        
        $fff = array();
        $text = strtolower($text);
        foreach($files as $file)
        {
            $needle = $text;
            $content = file_get_contents(__DIR__."/templates/fastaval-deltager-2015/".$file);
            
            $content = strtolower($content);
            
            if (strpos($content, $text) !== FALSE)
                $fff[] = $file;
        }
        
        return $fff;
    }
    
?>
<html>
<head>
    <script type='text/javascript' src='http://www.fastaval.dk/wp-includes/js/jquery/jquery.js?ver=1.11.1'></script>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <style>
        body{margin:0;}
        table{width:100%;border-collapse: collapse:}
        td{width:50%;}
        textarea{width:100%;height:125px;}
    </style>
</head>
<body>
    
<?php
    $language_org = vcsys_load_translations(86400, 'translations.xml');
    $language_update = vcsys_load_translations(86400, 'translations.xml.update');
    
    function get_texts_from_translation($translation)
    {
        $ret = array();
        foreach($translation as $key=>$arr)
        {
            $ret[] = array(
                'da' => $arr['da'],
                'en' => $arr['en']
                );
        }
        return $ret;
    }
    
    function text_exists_in_translation($text,$language,$translation)
    {
        foreach($translation as $key=>$arr)
        {
            if ($arr[$language]==$text)
                return true;
        }
        return false;
    }
    
    
    $changes = 0;
    
    $count = 0;
    $the_texts = get_texts_from_translation($language_update);
    $the_texts_org = get_texts_from_translation($language_org);
    echo "Reading translations.xml.update:";
    foreach($the_texts as $text)
    {
        $headline = false;
        $da_text = $text['da'];
        $en_text = $text['en'];
        
        if (!text_exists_in_translation($da_text,"da", $language_org))
        {
            echo "<hr>";
            echo "<h4>".$count."</h4>";
            $headline=true;
            echo "<strong>DANSK</strong>: Kunne ikke finde denne tekst i den gamle xml:";
            echo "<pre style='background:lightgrey'>";
            echo htmlentities($da_text);
            echo "</pre>";
            
            echo "<pre style='background:#ffdede'>";
            $org_text = $the_texts_org[$count];
            echo htmlentities($org_text['da']);
            echo "</pre>";
            
            
            $files = find_in_files($org_text['da']);
            foreach($files as $filename)
            {
                echo " - Edit this file: ".$filename."<br/>";
            }
            echo "<br/>";
            $changes++;
        }
        
        if (!text_exists_in_translation($en_text,"en", $language_org))
        {
            if (!$headline){
                echo "<hr>";
                echo "<h4>".$count."</h4>";
            }
            echo "<strong>ENGELSK</strong>: Kunne ikke finde denne tekst i den gamle xml:";
            echo "<pre>";
            echo htmlentities($en_text);
            echo "</pre>";
            $changes++;
        }
        
        $count++;
    }
    
    echo "<h1><em>Only ".$changes." changes to go.....</em></h1>";
    
    
?>
</body>
</html>