jQuery(function($){
    document.getElementById('img_file').addEventListener('change', function(e) {

        e.preventDefault();

        var data = new FormData();

        var file = $('input[name="input_file"]').prop('files')[0];
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
                document.getElementById('hidden-file-field').value = resp.data.url;
            }
        });

    })
});