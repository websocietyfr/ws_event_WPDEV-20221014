<?php

function wsevent_init_admin_sections() {
    // Register a new setting for "wsevent" page.
	register_setting( 'wsevent', 'wsevent_fullwidth' );

	// Register a new section in the "wporg" page.
	add_settings_section(
		'wsevent_section_developers',
		__( 'Affichage des pages d\'événement' ),
        'wsevent_section_developers_callback',
		'wsevent'
	);

	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'wsevent_fullwidth', // As of WP 4.6 this value is used only internally.
		                        // Use $args' label_for to populate the id inside the callback.
			__( 'Plein écran' ),
		'wsevent_fullwidth_cb',
		'wsevent',
		'wsevent_section_developers',
		array(
			'label_for'         => 'wporg_field_pill',
			'class'             => 'wporg_row',
			// 'wporg_custom_data' => 'custom',
		)
	);
}

function wsevent_section_developers_callback($args) {
    ?>
	    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Paramétrez l\'affichage des pages d\'événements.' ); ?></p>
	<?php
}

function wsevent_fullwidth_cb( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$option = get_option( 'wsevent_fullwidth' );
	?>
        <label for="wsevent_fullwidth">Afficher les pages d'événements en mode plein écran ?</label>
        <input type="checkbox" name="wsevent_fullwidth" id="wsevent_fullwidth" <?php if($option) echo 'checked'; ?> />
        <p class="description">
            <?php esc_html_e( 'Cochez la case ici si vous souhaitez que votre page d\'événement soit affiché en mode plein écran.'); ?>
        </p>
	<?php
}

add_action('admin_init', 'wsevent_init_admin_sections');

function wsevent_setting_page() {
    ?>
        <div>
            <h1><?php echo get_admin_page_title(); ?></h1>
        </div>
        <form action="options.php" method="POST">
            <?php
                settings_fields( 'wsevent' );
                do_settings_sections( 'wsevent' );
                submit_button('Enregistrer les paramètres')
            ?>
        </form>
    <?php
}

function wsevent_setting_menu() {
    add_menu_page(
        __( 'Paramètres des événements' ),
        __( 'Paramètres des événements' ),
		'manage_options',
		'wsevent',
		'wsevent_setting_page',
		'',
		100
    );
}

add_action('admin_menu', 'wsevent_setting_menu');