<?php 

  if( have_posts() ) {
    while( have_posts() ) {
      the_post();
      echo '<h1>' . get_the_title() . '</h1>';
      the_content();
    }
  }

  $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

  $args = array(
    'post_type'       => 'post',
    'post_status'     => 'publish',
    'order'           => 'DESC',
    'orderby'         => 'date',
    'posts_per_page'  => 6,
    'paged'           => $paged
  );

  $query = new WP_Query( $args );

  if( $query->have_posts() ) {
    while( $query->have_posts() ) {
      $query->the_post();
      echo '<a href="' . get_the_permalink() . '" >' . get_the_title() . '</a><br >';
    }
    echo '<div class="pagination">';
      echo paginate_links( array(
        'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
        'total'        => $query->max_num_pages,
        'current'      => max( 1, get_query_var( 'paged' ) ),
        'format'       => '?paged=%#%',
        'show_all'     => false,
        'type'         => 'plain',
        'end_size'     => 2,
        'mid_size'     => 1,
        'prev_next'    => true,
        'prev_text'    => sprintf( '<i></i> %1$s', __( 'Prev', 'text-domain' ) ),
        'next_text'    => sprintf( '%1$s <i></i>', __( 'Next', 'text-domain' ) ),
        'add_args'     => false,
        'add_fragment' => '',
      ) );
    echo '</div>';
    wp_reset_postdata();
  } else {
    echo '<p>' . _e( 'Sorry, no posts matched your criteria.' ) . '</p>';
  }

