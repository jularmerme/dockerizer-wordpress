<?php
/**
 * Page Builder component
 */

  // echo 'This is the Page Builder Component</br>';

  if ( have_rows( 'page_builder' ) ) {
    // $isPageBuilder = true;
    $isPageBuilder = ['true'];
    while ( have_rows( 'page_builder' ) ) {
      the_row();
      // get_theme_component( get_row_layout() );
      get_template_part( 'src/pages/components/' . get_row_layout(), '', $isPageBuilder );
    }
  }

?>