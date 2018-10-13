<?php
    
    class DeltagerTilmeldingGDSPage8 extends SignupPage
    {
        public function __construct(){
            include("modules/gds_v1.php");
        }
        public function init()
        {
        }
        
        public function canShow()
        {
            return true;
        }

        public function get_age(){
            $year = $_SESSION['customer']['birthday-year']*1;
            $day = $_SESSION['customer']['birthday-day']*1;
            $month = $_SESSION['customer']['birthday-month']*1;
            $age = 100;
            
            if (is_numeric($year)&&is_numeric($month)&&is_numeric($day))
            {
                $birthDate = $year."-".($month<10?"0":"").$month."-".($day<10?"0":"").$day;
                # object oriented
                $from = new DateTime($birthDate);
                $to   = new DateTime('today');
                return $from->diff($to)->y;
            }
            return $age;
        }
        

        public function render()
        {
            
            ?>
            <script>
                function validate_form(what)
                {
                    var user_gds = jQuery("#aktiviteter input:checkbox:checked").map(function(){
                        return jQuery(this).val();
                    }).get(); // <----
                    
                    var limit = 3;
                    <?php
                    if ($_SESSION['customer']['participant']!="deltager"){
                        echo "limit = 0;\n";
                    }
                    else if ($this->get_age()<13){
                        echo "limit = 0;\n";
                    }
                    else if ($_SESSION['customer']['aktiviteter_is_spilleder'] > 0)
                    {
                        echo "limit = 0;\n";
                    }
                    ?>
                    
                    if (user_gds.length<limit)
                    {
                        alert('<?php __etm('nocat_34');?>');
                        return false;
                    }
                    return true;
                }
            </script>
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("general_next_page");?>
                <?php tilm_form_prefields(); ?>
                
                <h1 class='entry-title'><?php __etm('nocat_174');?></h1>
                <div id='tilmelding-info'>
                    
                    <p><?php __etm('nocat_175');?></p>
                    
                    
                    <?php 
                    if ($_SESSION['customer']['participant']!="deltager")
                    {
                        ?><p><?php __etm('nocat_176');?></p><?php 
                    } 
                    else if ($this->get_age()<13)
                    {
                        ?><p><?php __etm('nocat_176_2');?></p><?php 
                    } 
                    
                    if ($_SESSION['customer']['aktiviteter_is_spilleder'] > 0)
                    {
                        ?><p><?php __etm('nocat_176_3');?></p><?php 
                    } 
                    ?>

                    <h3><?php __etm('nocat_298');?></h3>
                    <?php
        			renderFieldByType(array(
            			'id'=>'boardgame_competition',
            			'input-type'=>'checkbox',
            			'input-name'=>'more_gds',
            			'text'=>'nocat_177',
        			));
        			?>
        			
                    <h3><?php __etm('nocat_299');?></h3>
                    <?php
        			renderFieldByType(array(
            			'id'=>'boardgame_competition',
            			'input-type'=>'checkbox',
            			'input-name'=>'super_gds',
            			'text'=>'nocat_178_1',
        			));
        			?>
        			
<?php

    $gds = new gds_v1();
    $gds->render();

?>
                    
        			
                </div>
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("general_next_page"); ?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	<?php

        }
    }


