<?php
    
/* Handles the pages moving back and forth, including $_POST data */
class SignupHandler
{
    public function class_init()
    {
        $this->current_signuppage = null;
        $this->page_shown = false;
        $this->page_num_to_show = -1;
        $this->signuppage_to_load_saved = null;
        
        $this->signuppages = array();
        if (function_exists("myfunc_get_pages"))
            $this->signuppages = myfunc_get_pages(array());
    }

    public function init_signuppages()
    {
    }

    // PREVIOUS
    public function get_previous_step_obj($force_path = "")
    {
        $previous = null;
        
        foreach($this->signuppages as $page)
        {
            if ($page->canShow())
            {
                if (tm_is_path( $page->getSlug() , $force_path ))
                {
                    return $previous;
                }
                
                $previous = $page;
            }
        }
        return $previous;
    }
    
    public function get_previous_step_name()
    {
        $obj = $this->get_previous_step_obj();
        return "prev";// $obj->getSlug();
    }

    // NEXT
    public function get_next_step_obj($force_path="")
    {
        $page_num_to_show = 0;
        foreach($this->signuppages as $page)
        {
            if ($page->canShow())
            {
                if (tm_is_path( $page->getSlug(), $force_path ))
                {
                    $page_num_to_show = 1;
                }
                else if ($page_num_to_show==1){
                    return $page;
                }
            }
        }
        return null;
    }
    
    public function get_next_step_name()
    {
        return "next";//$this->get_next_step_obj()->getSlug();
    }
    
    // current
    public function get_step_obj($force_path="")
    {
        $page_num_to_show = 0;
        foreach($this->signuppages as $page)
        {
            if ($page->canShow())
            {
                if (tm_is_path( $page->getSlug(), $force_path ))
                {
                    return $page;
                }
            }
        }
        return null;
    }
    
    
    
    public function signuppage_to_show()
    {
        if ($this->signuppages==null)
            return null;
        
        if ($this->signuppage_to_load_saved)
            return $this->signuppage_to_load_saved;
        
        $actual_page = null;
        foreach($this->signuppages as $page)
        {
            $page->init();
            if ($page->canShow())
            {
                if (tm_is_path($page->getSlug() ))
                {
                    $this->signuppage_to_load_saved = $page;
                    $this->page_obj_to_show = $page;
                    $actual_page = $page;
                }
            }
        }

        

        // first, validate the one we came from
        $posting_step = $this->get_posting_step_signuppage();
        if ($posting_step)
        {
            $posting_step->validate();
        }

        return $actual_page;
    }
    
    public function is_signuppage_shown()
    {
        return $this->page_shown;
    }
    
    public function show_signuppage()
    {
        $page = $this->signuppage_to_show();
        
        if ($page!=null)
        {
            $page->renderHeader();
            //$page->render();
            $page->render_with_fusion_row();
            $page->renderFooter();
            $this->page_shown = true;
        }
    }
    
    public function get_current_signuppage()
    {
        return $this->current_signuppage;
    }

    function handle_signuppage_post()
    {
        if ($_POST)
        {
        	if (!isset($_SESSION['customer']))
        		$_SESSION['customer'] = array();
        	
        	foreach($_POST as $key=>$value)
        	{
        		$_SESSION['customer'][$key] = $value;
        	}
        	
        	if (isset($_POST['expected-checkboxes']))
        	{
        		$fields = $_POST['expected-checkboxes'];
        		foreach($fields as $field)
        		{
        			if (!isset($_POST[$field]))
        				unset($_SESSION['customer'][$field]);
        		}
        	}
        	
        	unset($_SESSION['customer']['action1']);
        	unset($_SESSION['customer']['action2']);
        	unset($_SESSION['customer']['action3']);
        }
    }

    function set_cookie_header()
    {
        $session_expiration = time() + 3600 * 24 * 14; // 14 days
        session_set_cookie_params($session_expiration);
        session_start();
    }


    function output_form_prefields()
    {
        $this_signuppage_name = $this->signuppage_to_load_saved;
        $my_signuppage_name = get_class($this_signuppage_name);
        ?>
        <input type='hidden' name='posting-step' id='posting-step' value='<?=$this_signuppage_name->getSlug();?>'>
        <?php
    }
    function output_form_postfields()
    {
    }

    function output_form_prev_fields(){

        static $field_call_form_postfields;
        
        $this_signuppage_name = $this->signuppage_to_load_saved;
        
        if ($field_call_form_postfields!==TRUE)
        {
            $field_call_form_postfields = true;
            ?>
            <script>
                var contentChangedInDocument = false;
                
                jQuery(document).ready(function()
                {
                    jQuery('input:text, input:radio, input:checkbox, select').each(function() 
                    {
                        var elem = jQuery(this);
                        elem.data('oldVal', elem.val());
                        
                        elem.bind("propertychange change click keyup input paste", function(event)
                        {
                            if (elem.data('oldVal') != elem.val()) 
                            {
                                contentChangedInDocument = true;
                            }
                        });
                    });            
                    
                    
                    jQuery('.prev-form input[type=submit]').click(function()
                    {
                        if (contentChangedInDocument)
                        {
                            return confirm("<?php __etm('Du har foretaget ændringer på siden. Disse ændringer gemmes ikke, hvis du går til forrige side. Tryk Annuller hvis du vil blive på siden, ellers tryk OK.');?>");
                        }
                            
                        return true;
                    })
                    
                });
            </script>
            <?php
        }

        ?>
        <input type='hidden' name='posting-step' id='posting-step' value='<?=$this_signuppage_name->getSlug();?>'>
        <?php
    }

    
    function get_posting_step()
    {
        if (!isset($_SESSION['customer']))
            return null;
        
        if (!isset($_SESSION['customer']['posting-step']))
            return null;
        
        return $_SESSION['customer']['posting-step'];
    }
    
    function get_posting_step_signuppage()
    {
        if (!function_exists("myfunc_get_pages"))
            return null;
        
        $posting_step = $this->get_posting_step();
        
        if ( $posting_step != null )
        {
            
            foreach ( $this->signuppages as $page )
            {
                if (get_class($page) == $posting_step)
                
                    return $page;
            }
            
        }
        return null;
    }
    
}



