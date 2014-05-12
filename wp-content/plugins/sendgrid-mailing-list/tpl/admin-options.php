
<div class="wrap">

    <?php screen_icon(); ?>

    <form action="options.php" method="post" id="<?php echo $plugin_id; ?>_options_form" name="<?php echo $plugin_id; ?>_options_form">

        <?php settings_fields($plugin_id . '_options'); ?>
        
        <h2>Sendgrid Mailing List &raquo; Settings</h2>
        
        <table class="widefat">
            <thead>
                   <tr>
                    <th><input type="submit" name="submit" value="Save Settings" class="button-primary" /></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><input type="submit" name="submit" value="Save Settings" class="button-primary" /></th>
                </tr>
            </tfoot>
            <tbody>                
                               <tr>
                    <td>
                        <label for="SGML_list">
                            <p>SendGrid Mailing List Name (Case Sensitive)</p>
                            <p><input type="text" style="width:272px;height:24px;" name="SGML_list" value="<?php echo get_option('SGML_list'); ?>" /></p>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="SGML_api_username">
                            <p>SendGrid API Username</p>
                            <p><input type="text" style="width:272px;height:24px;" name="SGML_api_username" value="<?php echo get_option('SGML_api_username'); ?>" /></p>
                        </label>
                    </td>
                </tr> 
                <tr>
                    <td>
                        <label for="SGML_api_password">
                            <p>SendGrid API Password</p>
                            <p><input type="text" style="width:272px;height:24px;" name="SGML_api_password" value="<?php echo get_option('SGML_api_password'); ?>" /></p>
                        </label>
                    </td>
                </tr>    
                <tr>
                    <td>
                        <label for="SGML_success_message">
                            <p>Message that gets display after a successful registration</p>
                            <p><input type="text" style="width:272px;height:24px;" name="SGML_success_message" value="<?php echo get_option('SGML_success_message'); ?>" /></p>
                        </label>
                    </td>
                </tr>                   
            </tbody>
        </table>

    </form>
    <h2>Usage</h2>
    <h3>Subscription Form</h3>
    <dl>
        <dt>Shortcode:</dt> 
        <dd>[sg-subscription-form]</dd>
        <dd>By default the shortcode will take options set in the plugin options</dd>
        <dt>Options:</dt> 
        <dd>Specify List: [sg-subscription-form list='Mailing List']</dd>
        <dd>Redirect to URL After Success: [sg-subscription-form redirect='http://www.google.com']</dd>
    </dl>
  
    
    
    
</div>