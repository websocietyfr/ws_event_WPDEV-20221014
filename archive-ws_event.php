<?php get_header(); ?>
<section class="container">
    <h1><?php echo get_the_archive_title(); ?></h1>
</section>
<section class="container">
    <div class="row">
        <?php while(have_posts()): ?>
            <?php the_post(); ?>
            <div class="col-sm">
                <div class="row">
                    <div class="col-sm">
                        <?php the_post_thumbnail(); ?>
                    </div>
                    <div class="col-sm">
                        <h2><?php echo the_title(); ?></h2>
                        <p>
                            <?php
                                $event = get_post_custom();
                            ?>
                            <strong>Date de début : </strong><?php if($event['startDate']) echo date_format(date_create($event['startDate'][0]), 'd/m/Y') ?><br>
                            <strong>Date de fin : </strong><?php if($event['endDate']) echo date_format(date_create($event['endDate'][0]), 'd/m/Y') ?>
                        </p>
                        <p>
                            <a href="<?php echo get_the_permalink(); ?>" class="btn btn-primary">Plus d'infos ici</a>
                        </p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>
<?php get_footer();
