<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>

<footer class="bbbs-footer">
<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="row">

			<div class="col-md-12">

				<footer class="site-footer" id="colophon">

					<div class="site-info">
						<p>©2019 Big Brothers Big Sisters of Central Iowa. 9051 Swanson Blvd, Clive, IA 50325</p>
					</div>

				</footer>

			</div>

		</div>

	</div>

</div>

</div>

<?php wp_footer(); ?>
</footer>
</body>

</html>
