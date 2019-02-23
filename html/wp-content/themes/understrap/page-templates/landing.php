<?php
/**
 * Template Name: Landing page
 *
 * Template for the landing page
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <h1 class="jumbotron-heading">Be a Big!</h1>
      <p class="lead text-muted">Make a difference in the life of child in your area! </p>
      <p>
        <a href="#" class="btn btn-primary my-2">Get Started!</a>
      </p>
    </div>
  </section>

  <div class="album py-5 bg-light">
    <div class="container">

      <div class="row">
      <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
          <img src="<?php echo home_url(); ?>/wp-content/uploads/2019/02/50771904_10157025737594630_2865750871658987520_n-Copy-Copy.jpg">
            <div class="card-body">
              <p class="card-text">Take part in fun activites!</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
          <img src="<?php echo home_url(); ?>/wp-content/uploads/2019/02/50771904_10157025737594630_2865750871658987520_n-Copy-Copy.jpg">
            <div class="card-body">
              <p class="card-text">Take part in fun activites!</p>
            </div>
          </div>
        </div>    
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
          <img src="<?php echo home_url(); ?>/wp-content/uploads/2019/02/50771904_10157025737594630_2865750871658987520_n-Copy-Copy.jpg">
            <div class="card-body">
              <p class="card-text">Take part in fun activites!</p>
            </div>
          </div>
        </div>

</main>

<?php get_footer(); ?>