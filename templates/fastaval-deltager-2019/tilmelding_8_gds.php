<?php
    
    class DeltagerTilmeldingGDSPage8 extends SignupPage
    {
        public function getSlug()
        {
            return 'gds';
        }
        
        public function getTitle(){
            return __tm('navigation_8');
        }
        
        public function __construct(){
            include("modules/gds_v1.php");
        }
        
        public function init()
        {
        }
        
        public function canShow()
        {
            if ($_SESSION['customer']['participant']=='deltagerjunior') return false;

            $numdays = 0;
            
            if (isset($_SESSION['customer']['days_1']) && ($_SESSION['customer']['days_1']>0))
                $numdays++;
                
            if (isset($_SESSION['customer']['days_2']) && ($_SESSION['customer']['days_2']>0))
                $numdays++;
                
            if (isset($_SESSION['customer']['days_3']) && ($_SESSION['customer']['days_3']>0))
                $numdays++;
                
            if (isset($_SESSION['customer']['days_4']) && ($_SESSION['customer']['days_4']>0))
                $numdays++;
                
            if (isset($_SESSION['customer']['days_5']) && ($_SESSION['customer']['days_5']>0))
                $numdays++;

            if (isset($_SESSION['customer']['days_all']) && ($_SESSION['customer']['days_all']>0))
                $numdays=5;
                

            if ($numdays<=1) return false;
            
            if (isset($_SESSION['customer']['is_package']) && ($_SESSION['customer']['is_package']==1))
                return false;
                
            if ($_SESSION['customer']['participant']=='deltagerjunior')
                return false;

            if ( $_SESSION['customer']['participant'] == 'deltagerjunior' )
                if ( $numday == 3 )
                    return true;
            
            //if (in_array($_SESSION['customer']['participant'], array('deltagerjunior', 'deltager')))
            //    return true;
            // return false;
            
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
            
            $single_days = array();
            for ($i=0;$i<=5;$i++) {
                if (isset($_SESSION['customer']['days_'.$i])){
                    if ( $_SESSION['customer']['days_'.$i] == "1" ){
                        $single_days[] = 'days_'.$i;
                    }
                }
            }
            ?>
        

            <script>
                function validate_form(what)
                {
                    var user_gds = jQuery("#aktiviteter input:checkbox:checked").map(function(){
                        return jQuery(this).val();
                    }).get(); // <----
                    
                    var limit = 3;
                    
                    if (jQuery('#ligeglad_gds:checked').length == 1)
                    {
                        limit = 0;
                    }
                    
                    <?php
                    if ($_SESSION['customer']['participant'] != "deltager"){
                        echo "limit = 0;\n";
                    }
                    else if ($this->get_age()<15){
                        echo "limit = 0;\n";
                    }
                    else if ($_SESSION['customer']['aktiviteter_is_spilleder'] > 0)
                    {
                        echo "limit = 0;\n";
                    }
                    if (count($single_days)==1){
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
                        
                    if (! in_array($_SESSION['customer']['participant'], array('deltager', 'deltagerjunior')))
                    {
                        ?><p><?php __etm('nocat_176');?></p><?php 
                    }
                    
                    if ($this->get_age()<15)
                    {
                        ?><p><?php __etm('nocat_176_2');?></p><?php 
                    } 
                    
                    if (count($single_days)==1){
                        ?><p><?php __etm('nocat_176_4');?></p><?php 
                    }

                    
                    if ($_SESSION['customer']['aktiviteter_is_spilleder'] > 0)
                    {
                        ?><p><?php __etm('gds_no_need');?></p><?php 
                    } 
                    ?>

                    <h3><?php __etm('gds_headline_ligeglad');?></h3>
                    <?php
        			renderFieldByType(array(
            			'id'=>'ligeglad_gds',
            			'input-type'=>'checkbox',
            			'input-name'=>'ligeglad_gds',
            			'text'=>'gds_text_ligeglad',
        			));
        			?>
        			
                    <!-- 
                    <h3><?php __etm('nocat_298');?></h3>
                    <?php
                        /*
        			renderFieldByType(array(
            			'id'=>'more_gds',
            			'input-type'=>'checkbox',
            			'input-name'=>'more_gds',
            			'text'=>'nocat_177',
        			));
        			*/
        			?>
        			-->
        			
                    <h3><?php __etm('nocat_299');?></h3>
                    <?php
        			renderFieldByType(array(
            			'id'=>'super_gds',
            			'input-type'=>'checkbox',
            			'input-name'=>'super_gds',
            			'text'=>'nocat_178_1',
        			));
        			?>
        			
                    <h3><?php __etm('gds_how_many_headline');?></h3>
                    <p><?php __etm('gds_how_many_text');?>
                    <?php
                        if (!isset($_SESSION['customer']['desired_diy_shifts'])) $_SESSION['customer']['desired_diy_shifts'] = 1;
                			renderFieldByType(array(
                    			'id'=>'desired_diy_shifts',
                    			'input-type'=>'text',
                    			'input-name'=>'desired_diy_shifts',
                    			'text' => __tm('gds_angiv_antal'),
                			));
            			?>
        			
                    <?php
                    
                        $gds = new gds_v1();
                        $gds->render();
                    
                    ?>
                    
                    <h3><?php __etm('gds_additional_notes_headline');?></h3>
                    <?php __etm('gds_additional_notes_text');?>
        			<?php
            			renderFieldByType(array(
                			'id'=>'gds_additional_notes',
                			'input-type'=>'textarea',
                			'input-name'=>'gds_additional_notes',
                			'text'=>'',
            			));
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


