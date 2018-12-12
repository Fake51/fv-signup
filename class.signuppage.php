<?php
    /* Base class for all pages in signup */
    class SignupPage
    {
        public function getSlug(){return null;}
        public function init(){}
        public function canShow(){return false;}
        public function render(){}
        public function render_with_fusion_row(){
            echo '<div class="fusion-builder-row fusion-row ">';
            $this->render();
            echo '</div>';
        }
        public function renderHeader()
        {
            ?>
            <script>
                jQuery(document).ready(function()
                {
                    <?php
                        if (get_language()=="en")
                        {
                            $en_url = $_SERVER["REQUEST_URI"];
                            $da_url = str_replace("?lang=en","",$en_url);
                            ?>
                            jQuery('#lang_sel_footer a:not(.lang_sel_sel)').attr('href','<?php echo $da_url;?>');
                            jQuery('#lang_sel_footer a.lang_sel_sel').attr('href','<?php echo $en_url;?>');
                            <?php
                        }
                        else
                        {
                            $da_url = $_SERVER["REQUEST_URI"];
                            $en_url = $da_url."?lang=en";
                            ?>
                            jQuery('#lang_sel_footer a:not(.lang_sel_sel)').attr('href','<?php echo $en_url;?>');
                            jQuery('#lang_sel_footer a.lang_sel_sel').attr('href','<?php echo $da_url;?>');
                            <?php
                        }
                    ?>
                    
                })
            </script>
            <?php
        }
        
        public function renderFooter(){}
        public function validate(){return true;}
    }
    
    
    