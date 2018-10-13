<?php

function renderErrorHandlerAsJavascript($args)
{
    foreach($args as $field=>$params)
    {
        $rules = $params['rules'];
        
        if (is_array($rules)) // advanced
        {}else
        {
            $new_rules = array();
            $sub_rules = explode(",", $rules);
            foreach($sub_rules as $sub_rule){
                $sub_rule = trim($sub_rule);
                $new_rules[] = array(
                    $sub_rule
                );
            }
            $rules = $new_rules;
        }
        
        foreach ($rules as $rule)
        {
            $func = $rule[0];
            if ($func == "nonempty")
            {
                ?>
                if (!validate_required("<?php echo $field?>","<?php __etm('general_text1');?>"))
                {
                    jQuery('#<?php echo $field?>').focus();
                    jQuery('#<?php echo $field?>').addClass('has-error');
                    return false;
                }
               <?php
            }
            else if ($func == "digits")
            {
                ?>
                if (isNaN(jQuery("#<?php echo $field?>").val()))
                {
                    alert("<?php __etm('general_text2');?>");
                    jQuery('#<?php echo $field?>').focus();
                    jQuery('#<?php echo $field?>').addClass('has-error');
                    return false;
                }
                <?php
            }
            else if ($func == "must-equal-field")
            {
                $param1 = $rule[1];
                ?>
                if (jQuery("#<?php echo $field?>").val() != jQuery("#<?php echo $param1?>").val())
                {
                    alert("<?php __etm('general_text_9');?>");
                    jQuery('#<?php echo $field?>').focus();
                    jQuery('#<?php echo $field?>').addClass('has-error');
                    jQuery('#<?php echo $param1?>').addClass('has-error');
                    return false;
                }
                <?php
            }
            else if ($func == "value-between")
            {
                $param1 = $rule[1];
                $param2 = $rule[2];
                ?>
                if (
                    (jQuery("#<?php echo $field?>").val() < <?php echo $param1?>) || 
                    (jQuery("#<?php echo $field?>").val() > <?php echo $param2?>)
                )
                {
                    alert("<?php echo __tm('nocat_143')." ".$param1." ".__tm('nocat_144')." ".$param2;?>");
                    jQuery('#<?php echo $field?>').focus();
                    jQuery('#<?php echo $field?>').addClass('has-error');
                    return false;
                }
                <?php
            }
            else if ($func == "value-above")
            {
                $param1 = $rule[1];
                ?>
                if (
                    (jQuery("#<?php echo $field?>").val() < <?php echo $param1?>)
                )
                {
                    alert("<?php __etm('Tallet skal være større end '.$param1);?>");
                    jQuery('#<?php echo $field?>').focus();
                    jQuery('#<?php echo $field?>').addClass('has-error');
                    return false;
                }
                <?php
            }
            else if ($func == "value-below")
            {
                $param1 = $rule[1];
                ?>
                if (
                    (jQuery("#<?php echo $field?>").val() > <?php echo $param1?>)
                )
                {
                    alert("<?php echo __tm('Tallet skal være mindre end ').$param1;?>");
                    jQuery('#<?php echo $field?>').focus();
                    jQuery('#<?php echo $field?>').addClass('has-error');
                    return false;
                }
                <?php
            }
            else if($func == "email"){
                ?>
                if (!validate_email(jQuery("#<?php echo $field?>").val()))
                {
                    alert("<?php __etm('nocat_292');?>");
                    jQuery('#<?php echo $field?>').focus();
                    jQuery('#<?php echo $field?>').addClass('has-error');
                    return false;
                }
                <?php
            }
            else if ($func == "length-between")
            {
                $param1 = $rule[1];
                $param2 = $rule[2];
                ?>
                
                if (jQuery("#<?php echo $field?>").val()!="")
                {
                    if (
                        ( (""+jQuery("#<?php echo $field?>").val()) .length < <?php echo $param1?>) || 
                        ( (""+jQuery("#<?php echo $field?>").val()) .length > <?php echo $param2?>)
                    )
                    {
                        if ("<?php echo $param1?>" == "<?php echo $param2?>")
                            alert("<?php echo __tm('Antal tegn i feltet skal være ').$param1;?>");
                        else
                            alert("<?php echo __tm('Antal tegn i feltet skal være mellem ').$param1.__tm(' og ').$param2; ?>");
                        jQuery('#<?php echo $field?>').focus();
                        jQuery('#<?php echo $field?>').addClass('has-error');
                        return false;
                    }
                }
                <?php
            }
            else if ($func == "length-above")
            {
                $param1 = $rule[1];
                ?>
                if (
                    ( (""+jQuery("#<?php echo $field?>").val()) .length < <?php echo $param1?>)
                )
                {
                    alert("<?php echo __tm('Antal tegn i feltet skal være større end ').$param1; ?>");
                    jQuery('#<?php echo $field?>').focus();
                    jQuery('#<?php echo $field?>').addClass('has-error');
                    return false;
                }
                <?php
            }
            else if ($func == "length-above-or-empty")
            {
                $param1 = $rule[1];
                ?>
                
                if (
                    ( (""+jQuery("#<?php echo $field?>").val()) .length <= <?php echo $param1?>) &&
                    ( (""+jQuery("#<?php echo $field?>").val()) != "")
                )
                {
                    alert("<?php echo __tm('Antal tegn i feltet skal være større end ').$param1; ?>");
                    jQuery('#<?php echo $field?>').focus();
                    jQuery('#<?php echo $field?>').addClass('has-error');
                    return false;
                }
                <?php
            }
            else if ($func == "length-below")
            {
                $param1 = $rule[1];
                ?>
                if (
                    ( (""+jQuery("#<?php echo $field?>").val()) .length > <?php echo $param1?>)
                )
                {
                    alert("<?php echo __tm('Antal tegn i feltet skal være mindre end ').$param1; ?>");
                    jQuery('#<?php echo $field?>').focus();
                    jQuery('#<?php echo $field?>').addClass('has-error');
                    return false;
                }
                <?php
            }
        }
    }
    
}




function renderFieldByType($args)
{
    $class = array();
    if (isset($args['class']))
        $class = $args['class'];
    
    $caption = null;
    if (isset($args['caption']))
        $caption = $args['caption'];
    
	if ($args['input-type']=="checkbox")
	{
    	$do_default = gf($args['input-name']) === "" ? true:  false;
    	
    	
		?>
		<p class="<?php echo $args['id'];?> field-type-<?php echo $args['input-type'];?> <?php echo implode(" ", $class); ?>">
			<label for="<?php echo $args['input-name'];?>">
			    <?php echo __etm($args['text']);?>
			</label>
			<input 
    			class='tilmelding-input tilmelding-input-checkbox' 
    			type="checkbox" 
    			id='<?php echo $args['input-name'];?>' 
    			name="<?php echo $args['input-name'];?>" 
    			value="1"
    			<?php 
        			if (gf($args['input-name']))
        			    echo " checked";
        			 if (($do_default) && (isset($args['default-checked'])) )
        			    echo " checked";
    			?>
            />
            <input type='hidden' value='<?php echo $args['input-name'];?>' name='expected-checkboxes[]'>
			<?php getError($args['input-name']);?>
        </p>
		<?php
	}
	else if ($args['input-type']=="text")
	{
		?>
		<p class="<?php echo $args['id'];?> field-type-<?php echo $args['input-type'];?> <?php echo implode(" ", $class); ?>">
			<label for="<?php echo $args['input-name'];?>">
			    <?php echo __etm($args['text']);?>
			</label>
            <input 
                class='tilmelding-input' 
                type="text" 
    			id='<?php echo $args['input-name'];?>' 
    			name="<?php echo $args['input-name'];?>" 
                value="<?php echo gf($args['input-name']);?>" 
            />
            <?php if ($caption!=null){?><span class='caption'><?php __etm($caption);?></span><?php }?>
            <?php getError("input-name");?>
        </p>
		<?php
	}
	else if ($args['input-type']=="birthday")
	{
		?>
		<p class="<?php echo $args['id'];?> field-type-<?php echo $args['input-type'];?> <?php echo implode(" ", $class); ?>">
			<label>
			    <?php echo __etm($args['text']);?>
			</label>
            <input 
                class='tilmelding-input birthday-day' 
                type="text" 
                size='2'
    			id='<?php echo $args['input-name'];?>-day' 
    			name="<?php echo $args['input-name'];?>-day" 
                value="<?php echo gf($args['input-name'].'-day');?>" 
            />
            <input 
                class='tilmelding-input birthday-month' 
                type="text" 
                size='2'
    			id='<?php echo $args['input-name'];?>-month' 
    			name="<?php echo $args['input-name'];?>-month" 
                value="<?php echo gf($args['input-name'].'-month');?>" 
            />
            <input 
                class='tilmelding-input birthday-year' 
                type="text" 
                size='4'
    			id='<?php echo $args['input-name'];?>-year' 
    			name="<?php echo $args['input-name'];?>-year" 
                value="<?php echo gf($args['input-name'].'-year');?>" 
            />

            <?php if ($caption!=null){?><span class='caption'><?php __etm($caption);?></span><?php }?>
            <?php getError("input-name");?>
        </p>
		<?php
	}
	else if ($args['input-type']=="textarea")
	{
		?>
		<p class="<?php echo $args['id'];?> field-type-<?php echo $args['input-type'];?> <?php echo implode(" ", $class); ?>">
    		
    		<?php if ($args['text']!="") { ?>
			<label for="<?php echo $args['input-name'];?>">
			    <?php echo __etm($args['text']);?>
			</label>
			<?php } ?>
			
			<textarea 
			    class='tilmelding-input' 
    			id='<?php echo $args['input-name'];?>' 
    			name="<?php echo $args['input-name'];?>"><?php  echo gf($args['input-name']);?></textarea>
            <?php getError("input-name");?>
        </p>
		<?php
	}
	else if ($args['input-type']=="select")
	{
    	$default_value = null;

    	if (gf($args['input-name']))
    	    $default_value = gf($args['input-name']);

        else if (isset($args['default-value']))
            $default_value = $args['default-value'];

        else if (isset($args['value-default']))
            $default_value = $args['value-default'];
            
		?>
		<p class="<?php echo $args['id'];?> field-type-<?php echo $args['input-type'];?> <?php echo implode(" ", $class); ?>">
    		
    		<?php if ($args['text']!=""){?>
			<label for="<?php echo $args['input-name'];?>">
			    <?php echo __etm($args['text']);?>
			</label>
			<?php } ?>
			
			<select
			    class='tilmelding-input' 
    			id='<?php echo $args['input-name'];?>' 
    			name='<?php echo $args['input-name'];?>'
                >
                <?php
                    foreach($args['value'] as $value=>$text)
                    {
                        $selected ="";
            			if ($default_value==$value)
                            $selected = " selected";
                        
                        ?>
                        <option value='<?php echo $value;?>' <?php echo $selected;?>><?php echo __tm($text);?></option>
                        <?php
                    }
                ?>
			</select>
            <?php getError("input-name");?>
        </p>
		<?php
	}
	else if ($args['input-type']=="radio")
	{
		?>
		<p class="<?php echo $args['id'];?> field-type-<?php echo $args['input-type'];?> <?php echo implode(" ", $class); ?>">
        <?php
            $count=0;
            $selected_value = gf($args['input-name']);
            if (($selected_value=="")&&isset($args['value-default']))
                $selected_value = $args['value-default'];
            foreach($args['value'] as $value=>$text)
            {
                $selected ="";
    			if ($selected_value == $value)
                    $selected = " checked";
                
                if ($value=="--")
                {
                    echo $text;
                }
                else{
                    ?>
        			<label for="<?php echo $args['input-name'].'-'.$count;?>">
        			    <?php echo __tm($text);?>
        			</label>
                    <input 
                        type='radio' 
                        value='<?php echo $value;?>' 
                        <?php echo $selected;?>
            			id='<?php echo $args['input-name'].'-'.$count;?>' 
            			name='<?php echo $args['input-name'];?>'
                    >
                    <br/>
                    <?php
                }
                
                $count++;
            }
        ?>
        <?php getError("input-name");?>
        </p>
		<?php
	}
}
 
