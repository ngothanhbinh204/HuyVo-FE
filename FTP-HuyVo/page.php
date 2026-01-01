<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package huyvo
 */

get_header();
?>

<div class="page-main wrapper-gap-top">
	<div class="container">
		<div class="breadcrumb-wrapper">
			<?php get_template_part('componets/section-breadcrumb'); ?>
		</div>
		<div class="page-header text-center mb-10">
			<h1 class="page-title"><?php the_title(); ?></h1>
		</div>
		<div class="page-content">
			<?php the_content(); ?>
		</div>
	</div>
</div>

<?php
get_footer();
