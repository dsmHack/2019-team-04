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
          {% include icons/placeholder.svg width="100%" height="225" background="#55595c" color="#eceeef" class="card-img-top" text="Thumbnail" %}
           <img source=<?php wp_get_attachment_image_src(22)?>/>
            <div class="card-body">
              <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            {% include icons/placeholder.svg width="100%" height="225" background="#55595c" color="#eceeef" class="card-img-top" text="Thumbnail" %}
            <div class="card-body">
              <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            {% include icons/placeholder.svg width="100%" height="225" background="#55595c" color="#eceeef" class="card-img-top" text="Thumbnail" %}
            <div class="card-body">
              <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
          </div>
        </div>

</main>

<?php get_footer(); ?>