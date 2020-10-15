<?php

  $parent_terms = get_terms(array(
                  'taxonomy'   => 'brand_model',
                  'hide_empty' => false,
                  'parent'     => 0
  ));

  printr($parent_terms);

  if($parent_terms){
    echo '<ul class="list-group">';
    foreach($parent_terms as $parent_term){
      echo '<li class="list-group-item">' .$parent_term->name . '</li>';
      // echo '<p>Parent ID : ' . $parent_term->term_id . '</p>';
      $first_terms = get_terms(array(
                      'taxonomy'   => 'brand_model',
                      'hide_empty' => false,
                      'parent'     => $parent_term->term_id,
                    ));
      if($first_terms){
        echo '<ul class="list-group">';
        foreach($first_terms as $first_term) {
          echo '<li class="list-group-item">' . $first_term->name . '</li>';
          $second_terms = get_terms(array(
                      'taxonomy'   => 'brand_model',
                      'hide_empty' => false,
                      'parent'     => $first_term->term_id,
                    ));
          if($second_terms){
            echo '<ul class="list-group">';
            foreach($second_terms as $second_term){
              echo '<li class="list-group-item">' . $second_term->name . '</li>';
            }
            echo '</ul>';
          }

        }
        echo '</ul>';
      }
      echo '</ul>';
    }
    echo '</ul>';
  }
  
?>

Brand Taxonomy Template
