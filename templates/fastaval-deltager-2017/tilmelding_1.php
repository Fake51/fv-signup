<?php
/*
    Name: Tilmelding Side 1
    Class: DeltagerTilmeldingIntroPage1
*/

    class DeltagerTilmeldingIntroPage1 extends SignupPage
    {
        public function getSlug()
        {
            return '';
        }
        
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
            
            if ((isset($_SESSION["registered"])) && ($_SESSION["registered"]=="1"))
            {
                 $_SESSION["registered"]="0";
                 unset($_SESSION['customer']);
            }
            
            ?>
            
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("general_next_page");?>
                <?php tilm_form_prefields(); ?>
                
            	<input type='hidden' name='expect_checkbox' value=''>
                <h1 class='entry-title'><?php __etm('page1_text1')?></h1>
                <p><?php __etm('page1_text4'); ?>
                <p><?php __etm('nocat_124'); ?>
                
                <h2><?php __etm('page1_text2');?></h2>
                <p><?php __etm('page1_text3');?></p>
        
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("general_next_page");?>
                
        	</form><?php
        }
    }
    
