<div class="SGML_form_container">
    <form name="SGML_form" style="width:340px!important;" class="SGML_form" action="" method="post">
        <input type="email" name="SGML_email" placeholder="Ingresa tu correo" />
        <input name="action" type="hidden" value="the_ajax_hook" />
        <input name="SGML_action" type="hidden" value="subscribe" />
        <input name="SGML_list" type="hidden" value="<?= $list ?>" />
        <input name="SGML_redirect" type="hidden" value="<?= $redirect ?>" />
        <button name="submit" class="SGML_submit_button">Enviar! <img src="<?= plugin_dir_url(__DIR__); ?>img/spinner.gif" class="SGML_spinner" /></button>
    </form>
    <div class="SGML_response" style="display:none;"></div>
</div>