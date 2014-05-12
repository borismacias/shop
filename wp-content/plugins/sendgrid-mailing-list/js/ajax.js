jQuery(function() {
    var $ = jQuery,
            $containers = $('.SGML_form_container');

    $containers.each(function() {
        var $this = $(this),
                $form = $this.find('.SGML_form'),
                $btn = $this.find('.SGML_submit_button'),
                $response = $this.find('.SGML_response');


        $btn.click(function(e) {
            e.preventDefault();
            $btn.attr('disabled',true);
            var data = $form.serialize(),
                    url = SendgridMailingList_ajax_script.ajaxurl;
            $response.hide();
            jQuery.ajax({
                type: 'post',
                data: data,
                url: url,
                success: function(data, textStatus, jqXHR) {
                    console.log('data', data);

                    if (data.response === true) {
                        $response
                                .removeClass('SGML_response-error')
                                .addClass('SGML_response-success');
                    } else {
                        $response
                                .removeClass('SGML_response-success')
                                .addClass('SGML_response-error');
                    }
                    
                    $response
                            .html(data.response_message)
                            .show();
                },
                error: function(jqXHR, status, error) {
                    $response.show();
                    $response.html('ERROR: Invalid or Improper Email Address.');
                },
                complete: function(){
                    $btn.removeAttr('disabled');
                }
            });
        });

    });
    
    
});