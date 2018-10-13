<?php
    
    class DeltagerTilmeldingMadPage6 extends SignupPage
    {
        public function getSlug()
        {
            return 'mad';
        }
        
        public function getTitle(){
            return __tm('navigation_6');
        }
        
        public function init()
        {
        }
        
        public function canShow()
        {
            if ($_SESSION['customer']['participant']=='deltagerjunior')return false;

            if (isset($_SESSION['customer']['is_package']) && ($_SESSION['customer']['is_package']==1))
                return false;
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
        
        public function attends_con_at_numday($numday)
        {
            if (isset($_SESSION['customer']['days_all']))
                if ($_SESSION['customer']['days_all']==1)
                    return true;
            
            if (isset($_SESSION['customer']['days_'.$numday]))
                if ($_SESSION['customer']['days_'.$numday]==1)
                    return true;
            
            if ( $_SESSION['customer']['participant'] == 'deltagerjunior' )
                if ( $numday == 3 )
                    return true;
            
            return false;
        }
        
        private function food_cells($name, $values)
        {
            foreach($values as $value)
            {
                $set_value = gf($name);
                if (!$set_value)$set_value = 0;
                ?>
                	<td><input value='<?=$value?>' id='<?=$name;?>' name='<?=$name;?>' type='radio' <?=($set_value==$value?"checked":"")?>></td>
                <?php
            }
        }
        
        private function breakfast_cell($name, $values)
        {
            foreach($values as $value)
            {
                $set_value = gf($name);
                if (!$set_value)$set_value = 0;
                ?>
                	<td><input value='<?=$value?>' id='<?=$name;?>' name='<?=$name;?>' type='checkbox' <?=($set_value==$value?"checked":"")?>></td>
                <input type='hidden' value='<?=$name;?>' name='expected-checkboxes[]'>
                <?php
            }
        }
        private function renderFood($title, $segments){
            ?>
                <tr>
                    <td class='title' colspan='6'><?=$title;?></td>
                </tr>
            <?php
            foreach($segments['entries'] as $entry){
                ?>
                <tr>
                    <?php
                        if (!$entry['title']){
                            ?><td><em><?=__tm('text_none');?></em></td><?php
                        }else{
                            ?><td> - <?=$entry['title'];?></td><?php
                        }
                    ?>
                    
                    <?php
                        for ($i=0;$i<5;$i++)
                        {
                            
                            $value_selected = gf($segments['keys'][$i]);
                            $value = $entry['days'][$i];
                            
                            if ($value != -1)
                            {
                                $checked = false;
                                
                                if ((!$value) && (!$value_selected) ){
                                    $checked = true;
                                }
                                else if ($value == $value_selected) {
                                    $checked = true;
                                }
                                
                                if ( $this->attends_con_at_numday($i+1) )
                                {
                                    ?>
                                    <td> <?=$segment['keys'][$i];?> <input type='radio' name='<?=$segments['keys'][$i];?>' value='<?=$value?>' <?=($checked?" checked":"")?>></td>
                                    <?php
                                }
                                else
                                {
                                    ?><td>&nbsp;</td><?php
                                }
                            }
                            else{
                                ?><td>&nbsp;</td><?php
                            }
                        }
                    ?>
                </tr>
                <?php
            }
            
        }
            
        
        public function render()
        {
            ?>
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	
        	
        	
        	<form method="post" action="<?php echo get_next_step_name();?>" onSubmit='return validate_form(this);'>
                <?php render_next_button("general_next_page");?>
                <?php tilm_form_prefields(); ?>
                
                <h1 class='entry-title'><?php __etm('nocat_1');?></h1>
                <div id='tilmelding-info'>

                    <p><?php __etm('nocat_151');?></p>
                    <p><?php __etm('nocat_152');?></p>


                	<table id='food'>
                    	<thead>
                            <tr>
                                <td></td>
                                <td><span><?php __etm('nocat_2');?></span></td>
                                <td><span><?php __etm('nocat_3');?></span></td>
                                <td><span><?php __etm('nocat_4');?></span></td>
                                <td><span><?php __etm('nocat_5');?></span></td>
                                <td><span><?php __etm('nocat_6');?></span></td>
                            </tr>
                    	</thead>
                    	<tbody>
                        	
                        <?php
                          
                          $food = array(
                              
                              'breakfast' => array(
                                  'keys' => array(
                                      'food_1',
                                      'food_2',
                                      'food_3',
                                      'food_4',
                                      'food_5',
                                  ),
                                  'entries' => array(
                                      array(
                                          'title' => null,
                                          'days' => array(-1,0,0,0,0),
                                      ),
                                      array(
                                          'title' => __tm('text_food_full_english'),//'Full English (45kr)',
                                          'days' => array(-1, 244, 245, 246, 247),
                                      ),
                                      array(
                                          'title' => __tm('text_food_big_veggie'),//'Big Veggie (45kr)',
                                          'days' => array(-1, 248, 249, 250, 251),
                                      ),
                                      array(
                                          'title' => __tm('text_food_grease_n_stuff'),//'Grød & Yoghurt menu (32kr)',
                                          'days' => array(-1, 252, 253, 254, 255),
                                      ),
                                    ),
                              ),
                              'lunch' => array(
                                  'keys' => array(
                                      'food_6',
                                      'food_7',
                                      'food_8',
                                      'food_9',
                                      'food_10',
                                  ),
                                  'entries' => array(
                                      array(
                                          'title' => null,
                                          'days' => array(-1,0,0,0,0),
                                      ),
                                      array(
                                          'title' => __tm('text_food_lunch_burger'),//'Frokostburger m. okse eller vegetarbøf (45kr)',
                                          'days' => array(-1, 257, 258, 259, 260),
                                      ),
                                      array(
                                          'title' => __tm('text_food_lunch_sandwich'),//'Sæsonens sandwich (45kr)',
                                          'days' => array(-1, 266, 267, 268, 269),
                                      ),
                                      array(
                                          'title' => __tm('text_food_lunch_other_language_food'),//'Quinoa Tabbouleh (45kr)',
                                          'days' => array(-1, 270, 271, 272, 273),
                                      ),
                                      array(
                                          'title' => __tm('text_food_lunch_carrots'),//'Sprød Cæsar salat (45kr)',
                                          'days' => array(-1, 274, 275, 276, 277),
                                      ),
                                      array(
                                          'title' => __tm('text_food_lunch_fish'),//'Egen tunsalat m. pocheret æg (45kr)',
                                          'days' => array(-1, 278, 279, 280, 281),
                                      ),
                                  ),
                              ),
                              'dinner' => array(
                                  'keys' => array(
                                      'food_11',
                                      'food_12',
                                      'food_13',
                                      'food_14',
                                      'food_15',
                                  ),
                                  'entries' => array(
                                      array(
                                          'title' => null,
                                          'days' => array(0,0,0,0,0),
                                      ),
                                      array(
                                          'title' => __tm('text_food_dinner_bouef'),//'Boeuf Bourguignon (68kr)',
                                          'days' => array(194, 195, 196, 197, 198),
                                      ),
                                      array(
                                          'title' => __tm('text_food_dinner_pizza'),//'Klassisk Pizza (68kr)',
                                          'days' => array(199, 200, 201, 202, 203),
                                      ),
                                      array(
                                          'title' => __tm('text_food_dinner_no_killing'),//'Batat Chili Sin Carne (68kr)',
                                          'days' => array(204, 205, 206, 207, 208),
                                      ),
                                      array(
                                          'title' => __tm('text_food_dinner_food_from_that_rat_movie'),//'Ratatouille (68kr)',
                                          'days' => array(209, 210, 211, 212, 213),
                                      ),
                                      array(
                                          'title' => __tm('text_food_dinner_mcdonalds'),//'Klassisk burger (68kr)',
                                          'days' => array(219, 220, 221, 222, 223),
                                      ),
                                      array(
                                          'title' => __tm('text_food_dinner_brokeback_mountain'),//'Cowboy steg m. rodfrugter (68kr)',
                                          'days' => array(224, 225, 226, 227, 228),
                                      ),
                                      array(
                                          'title' => __tm('text_food_dinner_imperial_tards'),//'Shepards/Cottage Pie (68kr)',
                                          'days' => array(229, 230, 231, 232, 233),
                                      ),
                                  ),
                              ),
                              
                          );  
                            
                            
                            $this->renderFood(__tm("text_food_morgenmad"), $food['breakfast']);
                            ?><tr><td colspan='6' class='spacer'>&nbsp;</td></tr><?php
                            $this->renderFood(__tm("text_food_frokost"), $food['lunch']);
                            ?><tr><td colspan='6' class='spacer'>&nbsp;</td></tr><?php
                            $this->renderFood(__tm("text_food_aftensmad"), $food['dinner']);
                        ?>                        	
                                                	
                        	
                    	</tbody>
                	</table>
                    <style>
                        #food .title{
                            font-weight:bold;
                        }
                    </style>
<!--
                	<table id='food'>
                    	<thead>
                            <tr>
                                <td></td>
                                <td><span>Morgenmad (29kr)</span></td>
                                <td><span>Ingen aftensmad</span></td>
                                <td><span>Aftensmad med kød (29kr)</span></td>
                                <td><span>Aftensmad uden kød (29kr)</span></td>
                            </tr>
                    	</thead>
                    	<tbody>
                        
                        	
                        	
                        <?php if ($this-> attends_con_at_numday(1)) {?>
                        	<tr class='day'><td><h3><?php __etm('nocat_2');?></h3></td><td></td><td></td><td></td><td></td></tr>
                        	<tr>
                            	<td>
                        			<?=__tm('nocat_157')?><br>
                        			<?=__tm('nocat_158')?>
                            	</td>
                            	<td>&nbsp;</td>
                            	<?php $this->food_cells('brainfood_dinner_1', array(0,145,150)); ?>
                        	</tr>
                        	<?php }?>
                        	
                        	
                        	
                        <?php if ($this-> attends_con_at_numday(2)) {?>
                        	<tr class='day'><td><h3><?php __etm('nocat_3');?></h3></td><td></td><td></td><td></td><td></td></tr>
                        	<tr>
                            	<td>
                        			<?=__tm('nocat_160_1')?><br>
                        			<?=__tm('nocat_161')?>
                            	</td>
                            	<?php $this->breakfast_cell('breakfast_2', array(160)); ?>
                            	<?php $this->food_cells('brainfood_dinner_2', array(0,146,151)); ?>
                        	</tr>
                        	<?php }?>
                        	
                        	
                        	
                        <?php if ($this-> attends_con_at_numday(3)){?>
                        	<tr class='day'><td><h3><?php __etm('nocat_4');?></h3></td><td></td><td></td><td></td><td></td></tr>
                        	<tr>
                            	<td>
                        			<?=__tm('nocat_162')?><br>
                        			<?=__tm('nocat_163')?>
                            	</td>
                            	<?php $this->breakfast_cell('breakfast_3', array(161)); ?>
                            	<?php $this->food_cells('brainfood_dinner_3', array(0,147,152)); ?>
                        	</tr>
                        	<?php }?>
                        
                        
                        
                        <?php if ($this-> attends_con_at_numday(4)){?>
                        	<tr class='day'><td><h3><?php __etm('nocat_5');?></h3></td><td></td><td></td><td></td><td></td></tr>
                        	<tr>
                            	<td>
                        			<?=__tm('nocat_164')?><br>
                        			<?=__tm('nocat_165')?>
                            	</td>
                            	<?php $this->breakfast_cell('breakfast_4', array(162)); ?>
                            	<?php $this->food_cells('brainfood_dinner_4', array(0,148,153)); ?>
                        	</tr>
                        	<?php }?>
                        
                        
                        	
                        <?php if ($this-> attends_con_at_numday(5)){?>
                        	<tr class='day'><td><h3><?php __etm('nocat_6');?></h3></td><td></td><td></td><td></td><td></td></tr>
                        	<tr>
                            	<td>
                        			<?=__tm('nocat_166')?><br>
                        			<?=__tm('nocat_167')?>
                            	</td>
                            	<?php $this->breakfast_cell('breakfast_5', array(163)); ?>
                            	<?php $this->food_cells('brainfood_dinner_5', array(0,149,154)); ?>
                        	</tr>
                        	<?php }?>
                        
                        
                        	
                    	</tbody>
                	</table>
                -->
                
                    <?php 
                        if ($this->get_age()<16)
                        {
                            // nooothing
                        }
                        else if ($this->get_age()<18)
                        {
                            ?>
                            <h2><?php __etm('nocat_168');?></h2>
                            <p><?php __etm('nocat_169');?></p>
                            <p><?php __etm('nocat_170');?></p>
                            <?php
                			renderFieldByType(array(
                    			'id'=>'otto_party',
                    			'input-type'=>'select',
                    			'input-name'=>'otto_party',
                    			'text'=>'nocat_171',
                    			'value' => array(
                        			'0' => 'nocat_154',
                        			'1' => 'nocat_172',
                    			),
                    			'class'=> array('fullsize-label quickfix-for-idiot-window-machine'),
                			));
                        }
                        else
                        {
                            ?>
                            <h2><?php __etm('nocat_168');?></h2>
                            <p><?php __etm('nocat_169');?></p>
                            <p><?php __etm('nocat_170');?></p>
                            <?php
                                
                            if ($this->get_age() < 18){
                    			renderFieldByType(array(
                        			'id'=>'otto_party',
                        			'input-type'=>'select',
                        			'input-name'=>'otto_party',
                        			'text'=>'nocat_171',
                        			'value' => array(
                            			'0' => 'nocat_154',
                            			'1' => 'nocat_172',
                        			),
                        			'class'=> array('fullsize-label','quickfix-for-idiot-window-machine'),
                    			));
                            }
                            else{
                    			renderFieldByType(array(
                        			'id'=>'otto_party',
                        			'input-type'=>'select',
                        			'input-name'=>'otto_party',
                        			'text'=>'nocat_171',
                        			'value' => array(
                            			'0' => 'nocat_154',
                            			'1' => 'nocat_172',
                            			'2' => 'nocat_173',
                        			),
                        			'class'=> array('fullsize-label','quickfix-for-idiot-window-machine'),
                    			));
                            }
                                
                        }
                    ?>
                </div>
                <style>
                    .quickfix-for-idiot-window-machine select{
                        width:50% !important;
                        min-width:50% !important;
                    }
                    
                </style>
                <?php tilm_form_postfields(); ?>
                <?php render_next_button("general_next_page");?>
        	</form>
        	
        	<form method="post" action="<?php echo get_previous_step_name();?>" class='prev-form'>
                <?php tilm_form_prev_fields(); ?>
                <?php render_previous_button("general_previous_page");?>
        	</form>
        	<?php

        }
    }


