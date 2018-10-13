<?php
/*
    Name: Tilmelding Side 1
    Class: DeltagerTilmeldingIntroPage1
*/

    class DeltagerTilmeldingIntroPage1 extends SignupPage
    {
        public function init()
        {
        }
        
        public function canShow()
        {
            return true;
        }
        
        public function render()
        {
            unset($_SESSION['has_sent_email']);
            unset($_SESSION['saved_customer_email']);
            unset($_SESSION['saved_password']);
            unset($_SESSION['is_tilmeldt']);
            
            if ($_SESSION["registered"]=="1")
            {
                 $_SESSION["registered"]="0";
                 unset($_SESSION['customer']);
            }
            
            ?>
            
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("Næste side");?>
                <?php tilm_form_prefields(); ?>
                
            	<input type='hidden' name='expect_checkbox' value=''>
                <h1 class='entry-title'><?php __etm('Velkommen til den store tilmelding for Fastaval 2015')?></h1>
                <p><?php __etm('Den store tilmelding for fastavals deltagere er nu åben. Du kan tilmelde dig hele Fastaval 2015 fra den 1. februar til og med den 8. marts'); ?>
                <p><?php __etm('Når du ikke at tilmelde dig inden 8. marts, kan du bruge dig den begrænsede tilmelding. Her kan du ikke vælge mad eller aktiviteter. Den begrænsede tilmelding er åben indtil d. 31 marts.'); ?>
                
                <h2><?php __etm('Vigtigt !');?></h2>
                <p><?php __etm('Du er først registreret som deltager på Fastaval, når du modtager din registrerings-mail. Hvis du IKKE modtager en registreringsmail fra os, så skriv til os, så kan vi eftersende den, eller finde ud af hvad fejlen kan være. Du kan skrive til os på <a href="mailto:info@fastaval.dk">info@fastaval.dk</a>.');?></p>
        
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("Næste side");?>
                
        	</form><?php
        }
    }
    
