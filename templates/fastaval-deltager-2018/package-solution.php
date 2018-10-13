<?php
    
    
    session_start();
    
    
    $food = $_POST['food'];
    $wear = $_POST['wear'];
    
    
    foreach($wear as $w)
    {
        $id = $w['id'];
        $count = $w['count'];
        $size = $w['size'];
        if ($count>0)
        {
            $_SESSION['customer']["wear_".$id."_size"] = $size;
            $_SESSION['customer']["wear_".$id."_count"] = $count;
        }
    }
    
    $_SESSION['customer']['is_package'] = 1;
    

/*
    if ($_POST['other_engelsk']==1)
        $_SESSION['customer']['other_engelsk'] = 1;
    else
        unset($_SESSION['customer']['other_engelsk']);
    
    
    if ($_POST['other_dansk']==1)
        $_SESSION['customer']['other_dansk'] = 1;
    else
        unset($_SESSION['customer']['other_dansk']);
  */  

    
    $_SESSION['customer']['new_alea'] = 1;
    $_SESSION['customer']['days_all'] = 1;
    $_SESSION['customer']['days_sleeping'] = 1;
    
    
    if ($food == "normal") // normal
    {
        $_SESSION['customer']['brainfood_dinner_1'] = 145; // onsdag

        $_SESSION['customer']['breakfast_2'] = 160;
        $_SESSION['customer']['brainfood_dinner_2'] = 146; // torsdag

        if ($_SESSION['customer']['participant'] == "deltagerjunior")
        {
            unset( $_SESSION['customer']['breakfast_3'] );
            unset( $_SESSION['customer']['brainfood_dinner_3'] );
        }
        else
        {
            $_SESSION['customer']['breakfast_3'] = 161;
            $_SESSION['customer']['brainfood_dinner_3'] = 147; // fredag
        }

        $_SESSION['customer']['breakfast_4'] = 162;
        $_SESSION['customer']['brainfood_dinner_4'] = 148; // lørdag

        $_SESSION['customer']['breakfast_5'] = 163;
        $_SESSION['customer']['brainfood_dinner_5'] = 149; // søndag

        $_SESSION['customer']['otto_party'] = 1;
    }
    else // vegetar
    {
        $_SESSION['customer']['brainfood_dinner_1'] = 150; // onsdag

        $_SESSION['customer']['breakfast_2'] = 160;
        $_SESSION['customer']['brainfood_dinner_2'] = 151; // torsdag

        if ($_SESSION['customer']['participant'] == "deltagerjunior")
        {
            unset( $_SESSION['customer']['breakfast_3'] );
            unset( $_SESSION['customer']['brainfood_dinner_3'] );
        }
        else
        {
            $_SESSION['customer']['breakfast_3'] = 161;
            $_SESSION['customer']['brainfood_dinner_3'] = 152; // fredag
        }

        $_SESSION['customer']['breakfast_4'] = 162;
        $_SESSION['customer']['brainfood_dinner_4'] = 153; // lørdag

        $_SESSION['customer']['breakfast_5'] = 163;
        $_SESSION['customer']['brainfood_dinner_5'] = 154; // søndag

        $_SESSION['customer']['otto_party'] = 1;
    }
    
    
    if (($_SESSION['customer']['participant'] == "deltagerjunior") || ($_SESSION['customer']['participant'] == "deltager"))
    {
        $_SESSION['customer']['ligeglad_gds'] = 1;
    }
    
    
    
//    var_dump($_POST);
