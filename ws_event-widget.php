<?php

class WS_Widget extends WP_Widget {
    function __construct()
    {
        parent::__construct(
			'ws-event-widget',
			__('Event Widget')
		);
    }

    public function widget($args, $instance)
    {
        var_dump($instance);
        $category_id = $instance['category_id'] ? $instance['category_id'] : null;
        echo '<h3>Les événements: </h3>';
        if($category_id) {
            echo do_shortcode('[wsevent categ='.$category_id.']');
        } else {
            echo do_shortcode('[wsevent]');
        }
    }

    public function form($instance)
    {
        $categories = get_categories();
        $option = isset($instance['category_id']) && !empty($instance['category_id']) ? $instance['category_id'] : null;
        var_dump($instance);
        ?>
            <p>
                <label for="category_id">Choisissez une catégorie ou laissez vide:</label>
                <select id="category_id" name="category_id">
                    <option>-- Toutes les catégories --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->term_id; ?>" <?php if($option == $category->term_id) echo 'selected' ?>><?php echo $category->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['category_id'] = strip_tags($new_instance['category_id']);
        $instance['test'] = strip_tags($new_instance['category_id']);

        return $instance;
    }
}

$my_widget = new WS_Widget();
