<?php // Taxonomy //

  $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

  $taxonomy_id = 'brand_model_' . $term->term_id; 

  $type = get_field('taxonomy_type', $taxonomy_id);
  
  get_taxonomy_template_type($type);

?>

