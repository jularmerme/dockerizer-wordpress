<?php

  if($args['0']){
    $carousel = get_sub_field('carousel');
  } else {
    $carousel = get_field('carousel');
  }

  $slideCounter = 0;

  if($carousel) {

?>

  <div class="container my-5">
    <div class="row">
      <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <?php foreach( $carousel as $image ) { ?>
          <div class="carousel-item <?php echo '0' == $slideCounter ? 'active' : ''; ?>">
            <img src="<?php echo $image['url']; ?>" class="d-block w-80" alt="<?php echo $image['alt']; ?>">
          </div>
          <?php $slideCounter++; } ?>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
  </div>
  
<?php } ?>