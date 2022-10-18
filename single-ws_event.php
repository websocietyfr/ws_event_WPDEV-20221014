<?php get_header(); ?>
<?php
    $event = get_post_custom();
    $fullwidth = get_option( 'wsevent_fullwidth' );
?>
<section class="<?php if(!$fullwidth) echo 'container' ?>">
    <div class="row">
        <div class="col-sm">
            <h1><?php the_title(); ?></h1>
        </div>
        <div class="col-sm">
            <?php the_post_thumbnail(); ?>
        </div>
    </div>
</section>
<div class="<?php if(!$fullwidth) echo 'container' ?>">
    <div class="row align-center">
        <div class="col-sm">
            <strong>Date de début :</strong><br>
            <?php if(isset($event['startDate'])) echo date_format(date_create($event['startDate'][0]), 'd/m/Y') ?>
        </div>
        <div class="col-sm">
            <strong>Date de fin :</strong><br>
            <?php // if(isset($event['endDate'])) echo date_format(date_create($event['endDate'][0]), 'd/m/Y') ?>
            <?php if(get_post_meta(get_the_ID(), 'endDate', true)) echo date_format(date_create(get_post_meta(get_the_ID(), 'endDate', true)), 'd/m/Y') ?>
        </div>
    </div>
</div>
<section class="<?php if(!$fullwidth) echo 'container' ?>">
    <p><?php the_content(); ?></p>
</section>
<div class="<?php if(!$fullwidth) echo 'container' ?>">
    <div class="row align-center">
        <div class="col-sm">
            <?php if(isset($event['eventLink'])): ?>
                <a href="<?php echo $event['eventLink'][0]; ?>" target="_blank"><strong>Cliquez ici pour accéder à plus d'infos</strong></a>
            <?php endif; ?>
        </div>
        <div class="col-sm">
            <?php if(isset($event['eventLink'])): ?>
                <a href="<?php echo $event['subscriptionLink'][0]; ?>" target="_blank"><strong>Cliquez ici pour vous inscrire</strong></a>
            <?php endif; ?>
        </div>
        <div class="col-sm">
            <?php if(isset($event['eventLink'])): ?>
                <a href="<?php echo $event['noticeLink'][0]; ?>" target="_blank"><strong>Cliquez ici pour acéder aux infos pratiques</strong></a>
            <?php endif; ?>
        </div>
    </div>
</div>
<section class="archives">
    <?php
        echo do_shortcode('[wsevent]');
    ?>
</section>
<?php get_footer();