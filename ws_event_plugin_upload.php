<?php

function wsevent_upload_directory_override( $dir ) {
    return array(
        'path'   => $dir['basedir'] . '/events',
        'url'    => $dir['baseurl'] . '/events',
        'subdir' => '/events',
    ) + $dir;
}

function wsevent_media_upload() {
    if ( !check_ajax_referer('upload_event_file', 'nonce', false)) {
        wp_send_json_error(['error' => 'nonce failed']);
    }

    if (isset($_FILES['input_file']) && !empty($_FILES['input_file']['name']) && $_FILES['input_file']['error'] <= 0){

        $temp = explode('.', $_FILES['input_file']['name']);
        // move file on upload folder
        if ($_FILES['input_file']['size'] >= 64000000) {
            wp_send_json_error(['error' => 'File size error']);
        }
        $uploadedFile = $_FILES['input_file'];
        $name = $_FILES['input_file']['name'];
        if (!function_exists('wp_handle_upload')) {
            require_once('wp-admin/includes/file.php');
        }
        add_filter('upload_dir', 'wsevent_upload_directory_override');
        $movedfile = wp_handle_upload($uploadedFile, [ 'test_form' => false ]);
        remove_filter('upload_dir', 'wsevent_upload_directory_override');
        if ($movedfile && !isset($movedfile['error'])) {
            if (!function_exists('wp_insert_attachment')) {
                require_once('wp-admin/includes/image.php');
            }
            // implement in case of images
            $file = $movedfile['file'];
            $type = $movedfile['type'];

            $attachment = [
                'post_mime_type' => $type,
                'post_title' => $name,
                'post_content' => 'File '.$name,
                'post_status' => 'inherit'
            ];

            $attach_id = wp_insert_attachment($attachment, $file, 0);
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            wp_update_attachment_metadata($attach_id, $attach_data);
            wp_send_json_success([
                "status" => 'success',
                'url' => $movedfile['url'],
                'attachment_id' => $attach_id,
            ]);
        } else {
            wp_send_json_error(['error' => 'moved file error', 'message' => $movedfile['error']]);
        }
    } else {
        wp_send_json_error(['error' => 'input file failed']);
    }
}

add_action('wp_ajax_media_upload', 'wsevent_media_upload');

function wsevent_media_deletion() {
    if ( !check_ajax_referer('upload_event_file', 'nonce', false)) {
        wp_send_json_error(['error' => 'nonce failed']);
    }
    wp_delete_attachment($_POST['attachment_id']);
    delete_post_meta($_POST['post_id'], $_POST['data-reference']);
    wp_send_json_success([
        'success' => true
    ]);
}

add_action('wp_ajax_media_delete', 'wsevent_media_deletion');
