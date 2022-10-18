jQuery(function($){
    function uploadAjax(element,event){
        event.preventDefault();

        var data = new FormData();

        var file = $(element).prop('files')[0];
        data.append('input_file', file);

        data.append('nonce', global.nonce);
        data.append('action', 'media_upload')
        // var data_type = 'image';

        jQuery.ajax({
            url: global.ajax_url,
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();

                if ( myXhr.upload ) {
                    myXhr.upload.addEventListener( 'progress', function(e) {
                        $('#publish').prop("disabled",true);
                        $(element).prop("disabled",true);
                    }, false );
                }

                return myXhr;
            },
            type: 'POST',
            beforeSend: function() {
                // handle before send
            },
            success: function(resp) {
                // handle success
                // Save the result the url or attachment ID in a hidden input field and when the overall form is submitted, save it in the custom field.
                document.getElementById($(element).attr('name') + '_hidden').value = resp.data.attachment_id;
                $('#publish').prop("disabled",false);
                $('button[data-reference='+ $(element).attr('id') +']').addClass("active");
            }
        });
    }
    $('.input_file').each(function(element) {
        $(this).on('change', function(e) {
            uploadAjax(this,e);
        });
    });
    // remove attachment file
    function dropAjax(element,event){
        event.preventDefault();

        var data = new FormData();
        data.append('attachment_id', $(element).attr('value'));
        data.append('data-reference', $(element).attr('data-reference'));
        data.append('post_id', global.post_id);
        data.append('nonce', global.nonce);
        data.append('action', 'media_delete');

        jQuery.ajax({
            url: global.ajax_url,
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();

                if ( myXhr.upload ) {
                    myXhr.upload.addEventListener( 'progress', function(e) {
                        $('#publish').prop("disabled",true);
                        $(element).prop("disabled",true);
                    }, false );
                }

                return myXhr;
            },
            type: 'POST',
            beforeSend: function() {
                // handle before send
            },
            success: function(resp) {
                // handle success
                // Save the result the url or attachment ID in a hidden input field and when the overall form is submitted, save it in the custom field.
                document.getElementById($(element).attr('data-reference') + '_hidden').value = '';
                $('#publish').prop("disabled",false);
                $(element).prop("disabled",false);
                $('#' + $(element).attr('data-reference')).prop("disabled",false);
            }
        });
    }
    $('.input_file_reset').each(function(element) {
        $(this).on('click', function(e) {
            dropAjax(this,e);
        })
    });
});