<?php

  // global $isPageBuilder;

  if( $args['0'] ) {
    $image = get_sub_field('image');
  } else {
    $image = get_field('image');
  }

?>

  <div class="container my-5">
    <div class="row">
      <div class="media">
        <img src="<?php echo $image['url']; ?>" class="mr-3" alt="<?php echo $image['alt']; ?>">
        <div class="media-body">
          <h5 class="mt-0">Media heading</h5>
          Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
        </div>
      </div>
    </div>
  </div>
