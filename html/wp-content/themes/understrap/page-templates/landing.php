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

<head>
<style>
  .jumbotron {
    background-image: url("/wp-content/uploads/2019/02/BBBS_Interactions010_Maurice-Malachi-1.jpg");
    background-size: cover;
    background-position: center 35%;
  }
</style>
</head>
<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <h1 class="jumbotron-heading">Be a Big!</h1>
      <p class="lead text-muted">Make a difference in the life of a child in your area! </p>
      <p>
        <a href="/register" class="btn btn-primary my-2">Get Started!</a>
      </p>
    </div>
  </section>

  <div class="album py-5 bg-light">
    <div class="container">

      <div class="row">
      <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
          <img src="<?php echo home_url(); ?>/wp-content/uploads/2019/02/BBBS_Interactions002_Sherry-Ankita-1.jpg">
            <div class="card-body">
              <p class="card-text">Spending time together in the community is primarily how Bigs and Littles develop their relationship</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
          <img src="<?php echo home_url(); ?>/wp-content/uploads/2019/02/BBBS_Interactions016_Maria-Jazmin-1.jpg">
            <div class="card-body">
              <p class="card-text">Learn and grow with your little!</p>
            </div>
          </div>
        </div>    
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
          <img src="<?php echo home_url(); ?>/wp-content/uploads/2019/02/Big-Sister-Rebecca-and-Little-Sister-Kaylin-WEDD3042-1.jpg">
            <div class="card-body">
              <p class="card-text">Be a positive impact on the lives of young children in the community!</p>
            </div>
          </div>
        </div>

</main>

<?php get_footer(); ?>
