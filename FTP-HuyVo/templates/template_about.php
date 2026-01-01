<?php

/**
 * Template Name: About
 */

global $post;
$about_hero = get_field('about_hero', $post->ID);
$about_identity = get_field('about_identity', $post->ID);
$about_timeline = get_field('about_timeline', $post->ID);
$about_explore = get_field('about_explore', $post->ID);

get_header();
?>
<div class="wrapper-gap-top">
    <section class="section-about-hero">
        <h1 class="about-title" data-aos="fade-up"><?php the_title(); ?></h1>
        <div class="global-quote-section" data-aos="fade-up" data-aos-delay="200">
            <div class="quote-wrapper">
                <div class="quote-icon icon-left"><img class="quote-left-icon" src="<?php echo get_template_directory_uri(); ?>/img/quote.svg"></div>
                <h2 class="quote-text"><?php echo !empty($about_hero['quote_text']) ? $about_hero['quote_text'] : ''; ?>
                </h2>
                <div class="quote-icon icon-right"><img class="quote-right-icon" src="<?php echo get_template_directory_uri(); ?>/img/quote-right.svg"></div>
                <div class="author-text">
                    <p>- <?php echo !empty($about_hero['quote_author']) ? $about_hero['quote_author'] : ''; ?> -</p>
                </div>
            </div>
        </div>
        <div class="hero-banner" data-aos="fade-up" data-aos-delay="400">
            <?php if (!empty($about_hero['hero_banner']['url'])) : ?>
                <div class="img-wrapper"><img src="<?php echo $about_hero['hero_banner']['url']; ?>" alt="<?php echo $about_hero['hero_banner']['alt'] ?? get_the_title(); ?>">
                </div>
            <?php endif; ?>
        </div>
    </section>
    <section class="section-about-identity">
        <div class="identity-banner">
            <div class="bg-image">
                <?php if (!empty($about_identity['identity_bg_image']['url'])) : ?>
                    <img src="<?php echo $about_identity['identity_bg_image']['url']; ?>" alt="<?php echo $about_identity['identity_bg_image']['alt'] ?? get_the_title(); ?>">
                <?php endif; ?>
            </div>
            <div class="overlay-content">
                <div class="content-box" data-aos="fade-up">
                    <div class="svg-wrapper">
                        <?php if (!empty($about_identity['identity_logo']['url'])) : ?>
                            <img src="<?php echo $about_identity['identity_logo']['url']; ?>" alt="<?php echo $about_identity['identity_logo']['alt'] ?? get_the_title(); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="desc-text">
                        <?php echo !empty($about_identity['identity_description']) ? apply_filters('the_content', $about_identity['identity_description']) : ''; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-about-timeline" id="timeline-section">
        <div class="max-w-full container-fluid">
            <div class="timeline-wrapper" data-aos="fade-up">
                <div class="swiper timeline-slider">
                    <div class="swiper-wrapper">
                        <?php if (!empty($about_timeline['timeline_items'])) : ?>
                            <?php foreach ($about_timeline['timeline_items'] as $timeline_item) : ?>
                                <div class="swiper-slide">
                                    <div class="slide-grid">
                                        <div class="grid-left">
                                            <div class="content-wrap">
                                                <h3 class="slide-title"><?php echo !empty($timeline_item['timeline_title']) ? $timeline_item['timeline_title'] : ''; ?></h3>
                                                <div class="slide-info">
                                                    <div class="year-label"><?php echo !empty($timeline_item['timeline_year']) ? $timeline_item['timeline_year'] : ''; ?></div>
                                                    <p><?php echo !empty($timeline_item['timeline_description']) ? apply_filters('the_content', $timeline_item['timeline_description']) : ''; ?></p>
                                                </div>
                                                <div class="bg-year-watermark">2012</div>
                                            </div>
                                            <div class="polaroid-group">
                                                <?php if (!empty($timeline_item['timeline_polaroid_image']['url'])) : ?>
                                                    <div class="wrapper-ratio"><img class="lozad undefined" data-src="<?php echo $timeline_item['timeline_polaroid_image']['url']; ?>" alt="<?php echo $timeline_item['timeline_polaroid_image']['alt'] ?? get_the_title(); ?>">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid-right">
                                        <?php if (!empty($timeline_item['timeline_main_image']['url'])) : ?>
                                            <div class="main-image"><img src="<?php echo $timeline_item['timeline_main_image']['url']; ?>" alt="<?php echo $timeline_item['timeline_main_image']['alt'] ?? get_the_title(); ?>">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="timeline-nav-container container max-w-full" data-aos="fade-up">
                    <div class="dots-list">
                        <div class="timeline-track"></div>
                        <div class="timeline-progress"></div>
                        <?php if (!empty($about_timeline['timeline_items'])) : ?>
                            <?php foreach ($about_timeline['timeline_items'] as $key => $timeline_item) : ?>
                                <div class="dot-item current active" data-index="<?php echo $key; ?>"><span class="year"><?php echo !empty($timeline_item['timeline_year']) ? $timeline_item['timeline_year'] : ''; ?></span>
                                    <div class="dot-circle"></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="slider-controls">
                        <div class="btn-nav prev"><i class="fa-light fa-chevron-left"></i></div>
                        <div class="btn-nav next"><i class="fa-light fa-chevron-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php if (!empty($about_explore['explore_items'])) :
        $args_collection = array(
            'post_type' => 'collection',
            'post__in' => !empty($about_explore['explore_items']) ? $about_explore['explore_items'] : array(),
            'orderby' => 'post__in',
            'order' => 'ASC',
        );
        $collections = new WP_Query($args_collection);
    ?>
        <section class="section-about-explore">
            <div class="container max-w-full">
                <h2 class="explore-title" data-aos="fade-up">EXPLORE THE WORLD OF HUY VO</h2>
                <div class="explore-list">
                    <?php if ($collections->have_posts()) : ?>
                        <?php while ($collections->have_posts()) : $collections->the_post(); ?>
                            <?php get_template_part('components/content-collecttion'); ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</div>
<?php

get_footer();
