<article>
    <a href="<?php echo get_the_permalink(); ?>">
        <?php the_title("<h2>", "</h2>"); ?>
    </a>
    <?php the_content(); ?>
    <br>
    <?php the_content(); ?>
    <br>
    <br>
</article>