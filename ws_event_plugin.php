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

add_action('wp_enqueue_scripts', 'wsevent_add_theme_scripts');

function wsevent_add_theme_scripts() {
    wp_enqueue_style('bootstrap-grid', 'https://cdn.jsdelivr.net/npm/bootstrap-v4-grid-only@1.0.0/dist/bootstrap-grid.min.css', [], '1.0');
    wp_enqueue_style('ws_event', plugin_dir_url(__FILE__) . '/assets/css/style.css', [], '1.0');
}

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

function wsevent_single_ws_event($link) {
    global $post;
    if (get_post_type() == 'ws_event') {
        $link = dirname(__FILE__) . '/single-ws_event.php';
    }
    return $link;
}
add_filter('single_template', 'wsevent_single_ws_event');

function wsevent_archive_ws_event($link) {
    global $post;
    if (get_post_type() == 'ws_event') {
        $link = dirname(__FILE__) . '/archive-ws_event.php';
    }
    return $link;
}
add_filter('archive_template', 'wsevent_archive_ws_event');

function wsevent_shortcode_displayLastsEvents($atts = [], $content = '') {
    $attributes = shortcode_atts( array(
        'categ' => null
    ), $atts);

    $arrayOfIds = [];
    if (isset($attributes['categ']) && $attributes['categ'] !== null)
        $arrayOfIds = explode(',', $attributes['categ']);

    $query = new WP_Query( array(
        'category__in' => $arrayOfIds,
        'post_type' => 'ws_event'
    ) );

    // Build content
    $content .= '<ul>';
    while($query->have_posts()) {
        $query->the_post();
        $content .= '<li><a href="'.get_the_permalink().'" target="_blank">'.get_the_title().'</a></li>';
    }
    $content .= '</ul>';
    // $content = '<html>sdkjshqdkljhdjk</html>';
    return $content;
}

add_shortcode('wsevent', 'wsevent_shortcode_displayLastsEvents');

include_once('ws_event-widget.php');
add_action( 'widgets_init', 'wsevent_register_widgets' );

function wsevent_register_widgets() {
	register_widget( 'WS_Widget' );
}

function wsevent_dash_widget_content() {
    $query = new WP_Query([
        'post_type' => 'ws_event',
        'meta_query' => array(
            array(
                'key'     => 'startDate',
                'value'   => date('Y-m-d'),
                'compare' => '>',
                'type'    => 'DATE'
            ),
        ),
        'limit' => 3
    ]);
    echo '<h2>Vos actions possibles</h2>';
    echo '<ul>';
    echo '<li><a href="'.get_admin_url(null, 'post-new.php?post_type=ws_event').'">Créer un nouvel événement</a></li>';
    echo '<li><a href="'.get_admin_url(null, 'edit-tags.php?taxonomy=category&post_type=ws_event').'">Gérez vos catégories</a></li>';
    echo '<li><a href="'.get_admin_url(null, 'edit.php?post_type=ws_event').'">Accéder à la liste de vos événements</a></li>';
    echo '</ul>';
    if(count($query->get_posts()) > 0) {
        echo '<h2>List des 3 prochains événements</h2>';
        echo '<ul>';
        while($query->have_posts()) {
            $query->the_post();
            echo '<li><a href="'.get_edit_post_link().'">'.get_the_title().'</a></li>';
        }
        echo '</ul>';
    }
}

function wsevent_dashboard_widget() {
    wp_add_dashboard_widget(
        'wsevent_dash',
        __('Vos événements'),
        'wsevent_dash_widget_content',
    );
}

add_action('wp_dashboard_setup', 'wsevent_dashboard_widget');

include_once('ws_event_plugin_settings.php');
