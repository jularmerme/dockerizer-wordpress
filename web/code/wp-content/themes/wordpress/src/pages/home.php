<?php // Homepage //

  $domain = site_url(); echo $domain;

  echo absint(20.33);            // 20
  echo absint(-20.33);           // 20
  echo absint(false);            // 0
  echo absint(true);             // 1
  echo absint(array(10,20,30));   // 1
  echo absint(NULL);             // 0

  echo addslashes_gpc( $domain );

  get_template_part('src/pages/components/page_builder');

?>