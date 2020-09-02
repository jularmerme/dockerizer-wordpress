<?php  // Basic Page Template //

  echo 'Default Template';

if( have_posts() ) {
  while( have_posts() ) {
    the_post();
    echo '<h1>' . get_the_title() . '</h1>';
    the_content();
  }
}
