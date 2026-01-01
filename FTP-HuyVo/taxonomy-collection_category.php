<?php

/**
 * Term Template for Collection Category
 * @package huyvo
 */

$term = get_queried_object();
$author = get_field('author', $term);
$thumb = get_field('thumb', $term);
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
get_header();
?>
<section class="section-collections-list wrapper-gap-top">
    <div class="container max-w-full">
        <div class="breadcrumb-wrapper">
            <?php get_template_part('components/section-breadcrumb'); ?>

        </div>
        <div class="global-quote-section manifesto-quote">
            <div class="quote-wrapper">
                <div class="quote-icon icon-left"><img class="quote-left-icon" src="<?php echo get_template_directory_uri(); ?>/img/quote.svg"></div>
                <h2 class="quote-text"><?php echo !empty($term->description) ? $term->description : ''; ?>
                </h2>
                <div class="quote-icon icon-right"><img class="quote-right-icon" src="<?php echo get_template_directory_uri(); ?>/img/quote-right.svg"></div>
                <div class="author-text">
                    <p>- HUY VO -</p>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-image-section">
        <div class="container max-w-full">
            <?php if (!empty($thumb['url'])) : ?>
                <div class="hero-image-wrapper">
                    <img class="lozad undefined" data-src="<?php echo $thumb['url']; ?>" alt="<?php echo $thumb['alt'] ?? $term->name; ?>" />
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="collections-list-wrapper">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <div class="collection-banner-item">
                    <div class="banner-wrapper">
                        <div class="banner-image"><img class="lozad undefined" data-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php echo get_the_title(); ?>" />
                        </div>
                        <div class="banner-content">
                            <div class="container">
                                <div class="content-inner">
                                    <h2 class="banner-heading"><?php the_title(); ?></h2><a class="btn-primary-3" href="<?php the_permalink(); ?>"><?php _e('EXPLORE COLLECTION', 'huyvo'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
        <div class="pagination-wrapper">
            <?php
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('Previous', 'huyvo'),
                'next_text' => __('Next', 'huyvo'),
            ));
            ?>
        </div>
    </div>
</section>
<?php
get_footer();
