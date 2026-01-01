<?php

/**
 * 
 * Template Name: Cart
 * 
 * The template for displaying the cart page
 *
 * This is the template that displays the cart page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package huyvo
 */

get_header();
?>
<section class="section-cart section-py">
    <div class="container">
        <?php the_content(); ?>
    </div>
</section>
<?php get_footer(); ?>