<?php
    include("modules/aktiviteter_v1.php");
    
    class DeltagerTilmeldingAktiviteterPage7 extends SignupPage
    {
        public function getSlug()
        {
            return 'aktiviteter';
        }
        
        public function getTitle(){
            return __tm('navigation_7');
        }
        
        public function __construct(){
        }
        
        public function init()
        {
        }

        public function get_scripts(){
            return array(
                array('name' => 'activities-layout', 'file' => 'activities_layout.js')
            );
        }
        
        public function validate()
        {
            $aktiviteter_v1 = new aktiviteter_v1();
            
			$aktiviteter = $aktiviteter_v1->loadAktiviteter();
			$afviklinger = $aktiviteter_v1->loadAfviklinger($aktiviteter);
			
			$_SESSION['customer']['aktiviteter_is_spilleder'] = 0;
			
			foreach($afviklinger as $afvikling)
			{
                if (!isset($_SESSION['customer']['event_'.$afvikling['afvikling_id']])) continue;
                if ($_SESSION['customer']['event_'.$afvikling['afvikling_id']]==5)
					$_SESSION['customer']['aktiviteter_is_spilleder'] = 1;
				if ($_SESSION['customer']['event_'.$afvikling['afvikling_id']]==4)
					$_SESSION['customer']['aktiviteter_is_spilleder'] = 1;
			}
			
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
        
        
        public function canShow()
        {
            // if ($_SESSION['customer']['participant']=='deltagerjunior')return false;

            if (isset($_SESSION['customer']['is_package']) && ($_SESSION['customer']['is_package']==1))
                return false;
            return true;
        }
        
        public function render()
        {
            /*
            $customer = $_SESSION['customer'];
			
			echo "<pre>";
			var_dump($customer);
			echo "</pre>";
            */
            $junior = $_SESSION['customer']['participant']=='deltagerjunior';
            ?>

        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("general_next_page");?>
                <?php tilm_form_prefields(); ?>
                
                <h1 class='entry-title'><?php __etm('nocat_300');?></h1>
                <div id='tilmelding-info'>
                    
                    <?php
                       if(!$junior) __etm('nocat_8');
                    ?>
                    
                    
                    <?php if(!$junior) { ?>
                    <h2>Filtrering</h2>
                    <script> selectFilterType = ''; </script>
                    <?php __etm('filtering_1010');?>
                    <?php
                        if ( gf('sub-package-selected') === "boardgame" )
                        {
                            ?><script>selectFilterType = 'braet';</script><?php
                        }
                        if ( gf('sub-package-selected') === "rpg" )
                        {
                            ?><script>selectFilterType = 'rolle';</script><?php
                        }
                    ?>
                    <div class='filters'>
                        <ul>
                            <li><div class='filter-click selected' data-type=''><?php __etm('nocat_317');?></div></li>
                        </ul>
                    </div>
                    
                    <?php } else {
                        ?><script>selectFilterType = 'junior';</script><?php
                    } ?>
                    
                    <?php __etm('nocat_9');?>
                    
                    <?php 
                        if (!$junior) __etm('nocat_9_2'); // no game masters for Junior
                    ?>


                    <script>
                        
                        function arr_unique(arr) {
                            var u = {}, a = [];
                            for(var i = 0, l = arr.length; i < l; ++i){
                                if(!u.hasOwnProperty(arr[i])) {
                                    a.push(arr[i]);
                                    u[arr[i]] = 1;
                                }
                            }
                            return a;
                        }                        
                        
                        var active_types_names = {};
                        active_types_names['rolle'] = '<?=__tm('nocat_10')?>';
                        active_types_names['braet'] = '<?=__tm('nocat_18')?>';
                        active_types_names['live'] = '<?=__tm('nocat_313')?>';
                        active_types_names['workshop'] = '<?=__tm('nocat_314')?>';
                        active_types_names['figur'] = '<?=__tm('nocat_315')?>';
                        active_types_names['magic'] = '<?=__tm('nocat_318')?>';
                        active_types_names['ottoviteter'] = '<?=__tm('nocat_128')?>';
                        active_types_names['junior'] = '<?=__tm('nocat_aktiviteter_junior')?>';
                        
                        var active_types = [];
                        jQuery(document).ready(function()
                        {
                            // fill
                            active_types = [];
                            jQuery(".type-selector").each(function(){
                                var day = jQuery(this).data("day");
                                var all_types = ['rolle','braet','live','workshop','figur', 'ottoviteter', 'magic', 'junior'];
                                
                                for(i=0;i<all_types.length;i++)
                                {
                                    var this_type = all_types[i];
                                    var type_count = jQuery('#aktiviteter table.table-day-'+day+' tr.row-type-'+this_type).size();
                                    if (type_count==0){
                                        jQuery('.type-selector.selector-day-'+day+' li[data-type="'+this_type+'"]').hide();
                                    }
                                    else{
                                        active_types.push( this_type );
                                    }
                                }
                            })
                            active_types = arr_unique(active_types);
                            active_types.forEach(function(i, e){
                                var name = active_types_names[i];
                                jQuery('.filters ul').append("<li><div class='filter-click' data-type='"+i+"' id='filter-"+i+"'>"+name+"</div></li>");
                            });
                            
                            
                            // events
                            
                            jQuery('.filters ul .filter-click').click(function(){
                                jQuery('.filters ul .filter-click').each(function(){jQuery(this).removeClass('selected');});
                                jQuery(this).addClass('selected');
                                
                                var type = jQuery(this).data('type');
                                
                                jQuery('#aktiviteter ul.type-selector').each(function(){
                                    jQuery(this).find('li[data-type="'+type+'"]').click();
                                }); 
                            });
                            
                            
                            jQuery(".type-selector li").click(function()
                            {
                                var day = jQuery(this).data("day");
                                var all_types = ['rolle','braet','live','workshop','figur','ottoviteter', 'magic', 'junior'];
                                var show_type = jQuery(this).data('type');
                                
                                jQuery('.type-selector.selector-day-'+day+' li').removeClass('selected');
                                jQuery('.type-selector.selector-day-'+day+' li[data-type="'+show_type+'"]').addClass('selected');
                                
                                
                                if (show_type==""){
                                    jQuery('#aktiviteter table.table-day-'+day+' tr.row-with-game').removeClass('hidden');
                                }
                                else
                                {
                                    jQuery('#aktiviteter table.table-day-'+day+' tr.row-with-game').addClass('hidden');
                                    jQuery('#aktiviteter table.table-day-'+day+' tr.row-type-'+show_type).removeClass('hidden');
                                }
                                
                            });
                        });
                    </script>

                    <?php
                        
                        $aktiviteter_obj = new aktiviteter_v1();
                        if ($aktiviteter_obj)
                        {
                            $aktiviteter_obj->render();
                        }
                        else
                        {
                            echo "Error. Unknown";
                        }
                    ?>
                    


                    <p><?php __etm('nocat_24');?></p>
                    <?php
        			renderFieldByType(array(
            			'id'=>'max_games',
            			'input-type'=>'select',
            			'input-name'=>'max_games',
            			'text'=>'nocat_25',
            			'value' => array(
                			'0' =>  'nocat_113',
                			'1' =>  'nocat_114',
                			'2' =>  'nocat_115',
                			'3' =>  'nocat_116',
                			'4' =>  'nocat_117',
                			'5' =>  'nocat_118',
                			'6' =>  'nocat_119',
                			'7' =>  'nocat_120',
                			'8' =>  'nocat_121',
                			'9' =>  'nocat_122',
                			'10' => 'nocat_123',
            			),
            			'class'=> array('fullsize-label'),
        			));
                    ?>
                    
                    <?php if (!$junior){ // No GM or Scenario competition for junior
                        if ($this->get_age()>=13){?>
                    
                            <h2><?php __etm('nocat_28');?></h2>
                            <p><?php __etm('nocat_29');?></p>
                            <?php
                            renderFieldByType(array(
                                'id'=>'scenarieskrivningskonkurrence',
                                'input-type'=>'checkbox',
                                'input-name'=>'scenarieskrivningskonkurrence',
                                'text'=>'nocat_250',
                            ));
                            ?>
                            
                            
                            <!-- 
                            <h2><?php __etm('nocat_31');?></h2>
                            <p><?php __etm('nocat_140');?></p>
                            <?php
                            renderFieldByType(array(
                                'id'=>'boardgame_competition',
                                'input-type'=>'checkbox',
                                'input-name'=>'boardgame_competition',
                                'text'=>'nocat_131',
                            ));
                            ?>
                            -->
                        <?php }?>
                            
                        <h2><?php __etm('nocat_32');?></h2>
                        <?php
                        renderFieldByType(array(
                        'id'=>'may_contact',
                            'input-type'=>'checkbox',
                            'input-name'=>'may_contact',
                            'text'=>'nocat_33',
                        ));
                    } ?>
                </div>
                <?php render_next_button("general_next_page"); ?>
                <?php tilm_form_postfields(); ?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>


            <script>
                jQuery(document).ready(function(){
                    
                    if (selectFilterType === "junior"){
                        //Hide everything that isn't related to junior activities
                        // hide type selector
                        jQuery(".type-selector").hide();
                        // hide tables for days without junior activities
                        jQuery(".table-day").each( function (){
                            if(jQuery(this).find("tr.row-type-junior").length === 0){
                                jQuery(this).hide();
                            }
                        });
                        // hide all activities and show junior activities
                        jQuery('#aktiviteter tr.row-with-game').addClass('hidden');
                        jQuery('#aktiviteter tr.row-type-junior').removeClass('hidden');
                    } else {
                        // hide junior activities for normal participants
                        jQuery('#aktiviteter tr.row-type-junior').addClass('hidden');
                        if (selectFilterType!=""){
                            // activate any filter set on a previous page
                            jQuery('#filter-'+selectFilterType).click();
                        }
                    }
                });
            </script>
                            
                            

        	<?php

        }
    }


