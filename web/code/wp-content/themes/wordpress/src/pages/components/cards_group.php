<?php

  if($args['0']) {

  } else {

  }

  $post_args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 2,
    'orderby'        => 'date',
    'order'          => 'DESC',
  );

  $post_query = new WP_Query( $post_args );

  if( $post_query->have_posts() ) {

?>

  <div class="container my-5">
    <div class="row">
      <div class="card-group">
        <?php while( $post_query->have_posts() ) { $post_query->the_post(); ?>
          <div class="card">
            <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>" class="card-img-top" alt="...">
            <div class="card-body">
              <h5 class="card-title"><?php echo get_the_title(); ?></h5>
              <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
              <p class="card-text"><small class="text-muted">Last updated <?php echo meks_time_ago(); ?></small></p>
            </div>
          </div>
        <?php } ?>
        <!-- <div class="card">
          <img src="..." class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
          </div>
        </div>

        <div class="card">
          <img src="..." class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
          </div>
        </div> -->

      </div>
    </div>
  </div>

<?php } wp_reset_postdata(); ?>