<?php // Navigation //

  function themeLogo() {
    $theme_logo_URL = 'http://placehold.it/50x50';

    if ($theme_logo_URL) {
      echo '<img src="'.$theme_logo_URL.'" alt="Logo Image">';
    }
    else {
      echo '<h1>Dockerized WP</h1>';
    }
  }

?>

<header class="navigation" role="banner">
  <div class="navigation-wrapper">
    <a href="<?php echo site_url(); ?>" class="logo">
      <?php themeLogo(); ?>
    </a>

    <a href="#" class="navigation-menu-button" id="js-mobile-menu">Menu</a>

    <nav role="navigation">
      <?php
        // wp_nav_menu( array(
        //     'theme_location'	 => 'header-menu',
        //     'container'			   => 'div',
        //     'container_class'	 => 'nav-menu-container',
        //     'menu_class'		   => 'navigation-menu show',
        //     'menu_id'          => 'js-navigation-menu'
        //   )
        // );
      ?>
    </nav>
  </div>
</header>
