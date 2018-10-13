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
                <?php tilm_form_prefields(); ?>
                
            	<input type='hidden' name='expect_checkbox' value=''>
                
                <h1 class='entry-title'><?php __etm('Tilmeldingen er lukket for i år')?></h1>
                
                <p><?php __etm('Du kan stadig møde op i døren til Fastaval og købe en billet !'); ?>
                
                <h2><?php __etm('Vi ses til Fastaval 2015 !');?></h2>
                
                <?php tilm_form_postfields(); ?>
                
        	</form><?php
        }
    }
    
