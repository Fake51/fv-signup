<?php
    session_start();
    header('Content-Type: text/html; charset=utf-8');
    
    function vcsys_load_translations($max_time_diff_in_seconds = 86400) 
    {
        $output = array();
    
        $translations_file = __DIR__ . '/translations.xml';
    
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
<h2>TRANSLATOR</h2>
<p><a href='?load'>Load file</a></p>

<button id='hide-translated'>Hide translated</button>
<button id='show-translated'>Show translated</button>
<script>
    jQuery('#hide-translated').click(function(){
        jQuery('tr.translated').hide();
    });
    jQuery('#show-translated').click(function(){
        jQuery('tr.translated').show();
    });
</script>


<form action='' method='post'>

<?php
    if ($_POST)
    {
        ?>
        <textarea style='height:80%'><?php
            
            define ("EOL","\n");
            echo htmlentities("<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">").EOL;
            echo htmlentities("<translations>").EOL;
            
            foreach($_POST as $key=>$text)
            {
                
                $da = htmlentities($text[0],ENT_QUOTES | ENT_IGNORE, "UTF-8");
                $en = htmlentities($text[1],ENT_QUOTES | ENT_IGNORE, "UTF-8");
                
                $last_miss = "";
                if ($text[2]!="") $last_miss = "last-miss='".$text[2]."'";
                
                $last_hit = "";
                if ($text[3]!="") $last_hit = "last-hit='".$text[3]."'";
                
                $str = htmlentities("<translation ".$last_miss." ".$last_hit.">").EOL;
                    $str .= htmlentities("<da><![CDATA[").$da.htmlentities("]]></da>").EOL;
                    $str .= htmlentities("<en><![CDATA[").$en.htmlentities("]]></en>").EOL;
                $str .= htmlentities("</translation>").EOL;
                echo $str;
            }
            echo htmlentities("</translations>").EOL;
            
        ?></textarea>
        <?php
    }
    else
    {
        ?>
        <table>
        <?php
            $language = $_SESSION['language'];
            foreach($language as $key=>$segment)
            {
                $en_tekst = "";
                $da_tekst = "";
                $last_miss = "";
                $last_hit = "";
                if (isset($segment['last-miss']))
                    $last_miss = $segment['last-miss'];
                if (isset($segment['last-hit']))
                    $last_hit = $segment['last-hit'];
                
                if (isset($segment['en']))
                {
                    $en_tekst = trim($segment['en']);
                }
                if (isset($segment['da']))
                {
                    $da_tekst = trim($segment['da']);
                }
                
                $classes = array();
                if (($en_tekst != "")&&($en_tekst!=null))
                {
                    $classes[] = 'translated';
                }
                
                
                ?>
                <tr class='<?php echo implode(" ",$classes)?>'>
                    <td>
                        <textarea name='<?php echo $key;?>[]'><?php echo htmlentities( $da_tekst );?></textarea>
                    </td>
                    
                    <td>
                        <textarea name='<?php echo $key;?>[]'><?php echo htmlentities( $en_tekst );?></textarea>
                    </td>
                    <input type='hidden' name='<?php echo $key;?>[]' value='<?php echo $last_miss?>'>
                    <input type='hidden' name='<?php echo $key;?>[]' value='<?php echo $last_hit?>'>
                </tr>
                <?php
            }
        ?>
        </table>
        <input type='submit' value='Lav til XML'>
        <?php
    }
    ?>
</form>
</body>
</html>