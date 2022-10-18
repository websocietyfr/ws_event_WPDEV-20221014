<?php

function wsevent_media_upload() {
    if ( !check_ajax_referer('media_upload', 'nonce', false)) {
        wp_send_json_error(['error' => 'nonce failed']);
    }

    if (isset($_FILES['input_file']) && !empty($_FILES['input_file']['name']) && $_FILES['input_file']['error'] <= 0){

        $temp = explode('.', $_FILES['input_file']['name']);
        $extension = end($temp);
        // move file on upload folder
    } else {
        wp_send_json_error(['error' => 'input file failed']);
    }
}

add_action('wp_ajax_media_upload', 'wsevent_media_upload');