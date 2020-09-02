<?php

  // global $acf_fields, $isPageBuilder;
  // echo '<h1>Title Component</h1>';
  // printr( $args );
  // echo( $args['key-1']);

  if( $args['0'] ) {
    $title = get_sub_field('title');
  } else {
    $title = get_field('title');
  }

  echo '<div class="container my-5">';
  echo '<div class="row">';
  echo '<div class="alert alert-success mx-auto" role="alert">';
  echo '<h2>' . $title . '</h2>';
  echo '</div>';
  echo '</div>';
  echo '</div>';

  // echo '<h1>' . $args['key-2'] . '</h1>';

?>