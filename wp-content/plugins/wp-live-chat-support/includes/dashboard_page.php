<script>
  var nifty_api_key = '<?php echo get_option("wplc_node_server_secret_token"); ?>';



</script>

<?php
  $user = wp_get_current_user();

  global $wpdb;
  global $wplc_tblname_chats;
  $sql = "SELECT COUNT(id) as total_chats FROM `$wplc_tblname_chats` WHERE `agent_id` <> 0";
  $results = $wpdb->get_row( $sql );
  if ($results) {
    $total_count = $results->total_chats;
  } else {
    $total_count = 0;
  }
?>

<div class="wrap wplc_wrap">
  <h2 id="wplc_dashboard_page_title"><?php _e( 'WP Live Chat Support Dashboard', 'wplivechat' ) ?></h2>
  <div class="wplc_dashboard_container">
    

    <div class="wplc_dashboard_row">
      <div class="wplc_panel_col wplc_col_6">
        <div class="wplc_panel">
          <h3 class="wplc_panel_title"><?php printf( __( 'Welcome, %s', 'wplivechat' ), $user->display_name ); ?></h3>
        </div>
      </div>
      <div class="wplc_panel_col wplc_col_6">
        <div class="wplc_panel pull-right">
          <em style='dispaly:block; float:left; margin-top:0px; margin-right:5px;'>A product of </em><a href='https://bleeper.io/?utm_source=wplc&utm_medium=link&utm_campaign=dash' target='_BLANK' title='Bleeper.io' border='0'><img src='https://bleeper.io/assets/images/bleeper-logo.png' alt='Bleeper - A Customer Communication Tool for Startups and Founders' title='Bleeper - A Customer Communication Tool for Startups and Founders' border='0' style='height:20px;'/></a>
        </div>
      </div>
    </div>



    <div class="wplc_dashboard_row">
      <div class="wplc_panel_col wplc_col_12">
        <div class="wplc_panel">
          <div class="wplc_material_panel">
            
          
          <div class="wplc_panel_col wplc_col_4 wplc-center">
            <h4><?php _e("Actions","wplivechat"); ?></h4>
            <p><a href='admin.php?page=wplivechat-menu&subaction=override' class='button-primary'><?php echo __("Chat with Visitors","wplivechat"); ?></a></p>
            <p><a href='admin.php?page=wplivechat-menu-settings' class='button-secondary'><?php echo __("Settings","wplivechat"); ?></a></p>
          </div>

          <div class="wplc_panel_col wplc_col_4 wplc-center">
            <h4><?php _e("Active Visitors","wplivechat"); ?><br />&nbsp;</h4>
            <span class='wplc-stat' id='totalVisitors'>...</span>
            <p><a href='admin.php?page=wplivechat-menu&subaction=override' class='button-secondary'><?php echo __("Chat now","wplivechat"); ?></a></p>
          </div>
          
          <div class="wplc_panel_col wplc_col_4 wplc-center">
            <h4><?php _e("Conversations","wplivechat"); ?><br /><span class='smaller'><?php _e("Last 90 days","wplivechat"); ?></span></h4>
            <span class='wplc-stat'><?php echo $total_count; ?></span>
          </div>
          

          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      function resizeIframe(iframe) {
        setTimeout(function() {
          iframe.height = iframe.contentWindow.document.body.scrollHeight + "px";  
        },3000);
        
      }
    </script>   

    <div class="wplc_dashboard_row">
      <div class="wplc_panel_col wplc_col_12">
         <div class="wplc_panel">
          
              
              <div class="resp-container">
                  <iframe onload="resizeIframe(this)" id="idIframe" class="resp-iframe" width="100%" height="1300" src="https://bleeper.io/app/external-dashboard/"></iframe>
              </div>
            
          </div>
      </div>
    </div>
    
  </div>
</div>