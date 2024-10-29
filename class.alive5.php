<?php

if ( !class_exists( 'Alive5_Chat' ) ) {
    class Alive5_Chat{

        public $settings = array();

        public function __construct(){
            add_action('wp_head', array($this,'add_chatscript'));
            $this->settings = get_option( 'alive5_settings' );
        }

        public function add_chatscript(){
            
            if(isset($this->settings['enable_chatbot']) && !empty($this->settings['widget_id'])){
            ?>
                <script type="text/javascript">
                (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)){ return; }
                    js = d.createElement(s);
                    js.id = id;
                    js.async = true;
                    js.src="//alive5.com/js/a5app.js";
                    js.setAttribute("data-widget_code_id", "<?php echo $this->settings['widget_id']?>");
                    fjs.parentNode.insertBefore(js, fjs);}(document, "script", "a5widget"));
                </script>
            <?php
            }
        }
    }
    new Alive5_Chat();
}

?>