<?php

global $post;
$collection_hero_image = get_field('collection_hero_image', $post->ID);
$sticky_images = get_field('sticky_images', $post->ID);
$sticky_content = get_field('sticky_content', $post->ID);
$backstage_moments = get_field('backstage_moments', $post->ID);

get_header();
?>
<section class="section-collections-detail wrapper-gap-top">
    <div class="container max-w-full">
        <div class="breadcrumb-wrapper">
            <?php get_template_part('components/section-breadcrumb'); ?>
        </div>
    </div>
    <h1 class="section-title"><?php the_title(); ?></h1>
    <div class="hero-image-section">
        <div class="container max-w-full">
            <?php if (!empty($collection_hero_image['url'])) : ?>
                <div class="hero-image-wrapper">
                    <img class="lozad undefined" data-src="<?php echo $collection_hero_image['url']; ?>" alt="<?php echo $collection_hero_image['alt'] ?? get_the_title(); ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="sticky-section just-image">
        <div class="container max-w-full">
            <div class="sticky-wrapper">
                <div class="sticky-left">
                    <div class="sticky-grid">
                        <?php if (!empty($sticky_images)) : ?>
                            <?php foreach ($sticky_images as $key => $sticky_image) : ?>
                                <div class="sticky-item">
                                    <div class="item-image">
                                        <?php if (!empty($sticky_image['sticky_image']['url'])) : ?>
                                            <img class="lozad undefined" data-src="<?php echo $sticky_image['sticky_image']['url']; ?>" alt="<?php echo $sticky_image['sticky_image']['alt'] ?? get_the_title(); ?>">
                                        <?php endif; ?>
                                        <div class="item-number"><?php echo $key + 1; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sticky-content">
                    <div class="content-inner">
                        <h2 class="section-heading"><?php echo !empty($sticky_content['sticky_heading']) ? $sticky_content['sticky_heading'] : ''; ?></h2>
                        <div class="section-desc">
                            <?php echo !empty($sticky_content['sticky_description']) ? apply_filters('the_content', $sticky_content['sticky_description']) : ''; ?>
                        </div>
                        <div class="sticky-action">
                            <?php if (!empty($sticky_content['sticky_button']['url'])) : ?>
                                <a class="btn-primary-1" href="<?php echo $sticky_content['sticky_button']['url']; ?>" target="<?php echo $sticky_content['sticky_button']['target'] ?? '_self'; ?>" rel="<?php echo $sticky_content['sticky_button']['rel'] ?? 'nofollow'; ?>"><?php echo $sticky_content['sticky_button']['title'] ?? ''; ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="backstage-moments">
                <div class="h2 heading-subtitle"><?php echo !empty($backstage_moments['backstage_heading']) ? $backstage_moments['backstage_heading'] : ''; ?></div>
                <div class="backstage-grid">
                    <?php if (!empty($backstage_moments['backstage_gallery'])) : ?>
                        <?php foreach ($backstage_moments['backstage_gallery'] as $backstage_image) : ?>
                            <div class="backstage-item">
                                <div class="item-image">
                                    <img class="lozad undefined" data-src="<?php echo $backstage_image['url']; ?>" alt="<?php echo $backstage_image['alt'] ?? get_the_title(); ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();
