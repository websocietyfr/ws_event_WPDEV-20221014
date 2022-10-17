<?php

/**
 * Plugin Name: WS Event Plugin
 * Plugin URI: https://github.com/websocietyfr/ws_event
 * Description: Event content type implementation for EVENEMENT PARISIENS
 * Version: 1.0
 * Requires at least: 5.4
 * Requires PHP: 7.0
 * Author: WEB SOCIETY
 * Author URI: https://websociety.fr
 * License: GPL v2 or Later
 * Text Domain: ws_event_plugin
 * Domain Path: /ws_event
 */

add_action('init', 'wsevent_registering_custom_posttype');

function wsevent_registering_custom_posttype() {
    $labels = [
        'name' => __('Evénements'),// libellé du nom du type de contenu
		'singular_name' => __('Evénement'),// Libellé singulier du type de contenu
		'add_new' => __('Ajouter'),// Libellé du bouton d'ajout
		'add_new_item' => __('Ajouter un événement'),// Libellé du bouton d'ajout d'un item (menu)
		'edit_item' => __('Modifier un événement'),// libellé de modification d'un événement
		'new_item' => __('Nouvel événement'),// Libellé de l'indicateur de nouvel événement
		'view_item' => __('Voir l\'événement'),// Libellé de l'action permettant d'accéder à l'édition d'un adhérent
		'search_items' => __('Rechercher un événement'),// Libellé lié à la recherche sur ce type de contenu
		'not_found' => __('Aucun événement trouvé'),// Libellé lors de l'absence de contenu pour ce type
		'not_found_in_trash' => __('Aucun événement dans la corbeille'),// Libellé pour l'absence de contenu dans la corbeille
		'parent_item_colon' => __('Evénement parent :'),// Libellé pour la fonctionnaltié de contenu parent sur ce type de contenu
		'menu_name' => __('Evénements'),// Libellé du menu pour ce type de contenu
    ];

    register_post_type('ws_event', [
        'labels' => $labels,
        'hierarchical' => false,
        'description' => __('Liste des événements'),
        'supports' => array('title', 'editor', 'thumbnail', 'author', 'revision', 'excerpt'),
        'taxonomies' => array('category'),
        'public' => true,
        'show_in_menu' => true,
        'show_ui' => true,
        'menu_position' => 5,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'capability_type' => 'post',
        'rewrite' => [ "slug" => 'events' ]
    ]);
}

function wsevent_metabox() {
    add_meta_box('events_data', __('Paramètres de l\'événement'), 'wsevent_metabox_content', 'ws_event');
}

function wsevent_metabox_content() {
    global $post;
    $event = get_post_custom($post->ID);
    $startDate = '';
    $endDate = '';
    $eventLink = '';
    $subscriptionLink = '';
    $noticeLink = '';
    if (count($event) > 0) {
        if(isset($event['startDate'])) $startDate = $event['startDate'][0];
        if(isset($event['endDate'])) $endDate = $event['endDate'][0];
        if(isset($event['eventLink'])) $eventLink = $event['eventLink'][0];
        if(isset($event['subscriptionLink'])) $subscriptionLink = $event['subscriptionLink'][0];
        if(isset($event['noticeLink'])) $noticeLink = $event['noticeLink'][0];
    }
    ?>
        <p>
            <label for="startDate"><?php echo __('Date de début'); ?></label>
            <input type="date" name="startDate" id="startDate" value="<?php echo $startDate; ?>" required/>
        </p>
        <p>
            <label for="endDate"><?php echo __('Date de fin'); ?></label>
            <input type="date" name="endDate" id="endDate" value="<?php echo $endDate; ?>" required/>
        </p>
        <p>
            <label for="eventLink"><?php echo __('lien de l\'événement'); ?></label>
            <input type="url" name="eventLink" id="eventLink" value="<?php echo $eventLink; ?>" required/>
        </p>
        <p>
            <label for="subscriptionLink"><?php echo __('lien d\'inscription à l\'événement'); ?></label>
            <input type="url" name="subscriptionLink" id="subscriptionLink" value="<?php echo $subscriptionLink; ?>" required/>
        </p>
        <p>
            <label for="noticeLink"><?php echo __('lien vers le doc "Infos pratiques"'); ?></label>
            <input type="url" name="noticeLink" id="noticeLink" value="<?php echo $noticeLink; ?>" required/>
        </p>
    <?php
}

add_action('add_meta_boxes', 'wsevent_metabox');

function wsevent_post_persistence() {
    global $post;
    $post_type = get_post_type();
    if ($post_type == 'ws_event') {
        if (isset($_POST['startDate']))
            update_post_meta($post->ID, 'startDate', $_POST['startDate']);
        if (isset($_POST['endDate']))
            update_post_meta($post->ID, 'endDate', $_POST['endDate']);
        if (isset($_POST['eventLink']))
            update_post_meta($post->ID, 'eventLink', $_POST['eventLink']);
        if (isset($_POST['subscriptionLink']))
            update_post_meta($post->ID, 'subscriptionLink', $_POST['subscriptionLink']);
        if (isset($_POST['noticeLink']))
            update_post_meta($post->ID, 'noticeLink', $_POST['noticeLink']);
    }
}

add_action('save_post', 'wsevent_post_persistence');

function wsevent_columns() {
    return [
        'cb' => '<input type="checkbox" />',
        'title' => __('Titre'),
        'startDate' => __('Date de début'),
        'endDate' => __('Date de fin')
    ];
}
add_filter('manage_edit-ws_event_columns', 'wsevent_columns');

function wsevent_columns_customization($column) {
    global $post;
    $post_type = get_post_type();
    if($post_type == 'ws_event') {
        $event = get_post_custom($post->ID);
        if($column == 'startDate' && isset($event['startDate'])) {
            echo date_format(date_create($event['startDate'][0]), 'd/m/Y');
        }
        if($column == 'endDate' && isset($event['endDate'])) {
            echo date_format(date_create($event['endDate'][0]), 'd/m/Y');
        }
    }
}
add_action('manage_posts_custom_column', 'wsevent_columns_customization');
