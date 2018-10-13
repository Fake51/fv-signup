<?php
    
    unset($_SESSION['has_sent_email']);
    unset($_SESSION['saved_customer_email']);
    unset($_SESSION['saved_password']);
    
?>

	<form method="post" action="./step-2" onSubmit='return validate_form(this);'>
    	<input type='hidden' name='expect_checkbox' value=''>
        <h1><?php __etm('Velkommen til fastaval-tilmeldingen for arrangører')?></h1>
        <p><?php __etm('Tilmeldingen for fastavals arrangører er nu åben. Denne tilmelding er hovedsageligt for at for-registrere alle arrangører på fastaval, således at vi kan jonglere rundt med vores arbejdesgruppe. '); ?>
        <p><?php __etm('Den tilmelding du laver nu som arrangør, er altså en forhåndstilmelding, som du senere får mulighed for at lave om, når den officielle Fastaval-tilmelding åbner. Dette gøres ved hjælp af den email-adresse du angiver, samt et kodeord du får sidst i tilmeldingen.'); ?>
        
        <?php render_next_button("Næste side");?>
        
	</form><?php

