<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package huyvo
 */

get_header();
?>

<div class="policy-page wrapper-gap-top">
	<div class="container max-w-full">
		<div class="breadcrumb-wrapper">
			<?php get_template_part('componets/section-breadcrumb'); ?>
		</div>
		<div class="policy-header">
			<div class="container">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</div>
		</div>
		<div class="policy-content">
			<div class="content-container">
				<div class="policy-main">
					<div class="policy-body">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
