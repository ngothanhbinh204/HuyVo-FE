<?php

/**
 * Template Name: Contact
 */

global $post;
$contact_hero_banner = get_field('contact_hero_banner', $post->ID);
$contact_showrooms = get_field('contact_showrooms', $post->ID);
$head_office = get_field('head_office', $post->ID);
$socials = get_field('socials', $post->ID);
$store_appointment_link = get_field('store_appointment_link', $post->ID);

get_header();
?>
<div class="contact-page wrapper-gap-top">
    <div class="container max-w-full">
        <div class="breadcrumb-wrapper">
            <?php get_template_part('components/section-breadcrumb'); ?>
        </div>
        <div class="policy-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
        </div>
        <div class="contact-hero-banner">
            <div class="banner-wrapper">
                <?php if (!empty($contact_hero_banner['url'])) : ?>
                    <img class="lozad undefined" data-src="<?php echo $contact_hero_banner['url']; ?>" alt="<?php echo $contact_hero_banner['alt'] ?? get_the_title(); ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if (!empty($contact_showrooms)) : ?>
        <?php foreach ($contact_showrooms as $key => $contact_showroom) :

        ?>
            <section class="contact-showroom <?php echo $key % 2 != 0 ? 'reversed' : ''; ?>">
                <div class="showroom-grid">
                    <div class="showroom-image">
                        <div class="img-wrapper">
                            <?php if (!empty($contact_showroom['showroom_image']['url'])) : ?>
                                <img class="lozad undefined" data-src="<?php echo $contact_showroom['showroom_image']['url']; ?>" alt="<?php echo $contact_showroom['showroom_image']['alt'] ?? get_the_title(); ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="showroom-content">
                        <div class="content-inner space-y-5">
                            <h2 class="showroom-title"><?php echo !empty($contact_showroom['showroom_title']) ? $contact_showroom['showroom_title'] : $contact_showroom['title']; ?></h2>
                            <div class="contact-info-list">
                                <div class="contact-info-item">
                                    <div class="info-icon"><i class="fa-light fa-phone"></i></div>
                                    <div class="info-content"><span class="info-label"><?php _e('Hotline:', 'huyvo'); ?></span><a class="info-value" href="tel:<?php echo !empty($contact_showroom['showroom_phone']) ? $contact_showroom['showroom_phone'] : $contact_showroom['head_office_phone']; ?>"><?php echo !empty($contact_showroom['showroom_phone']) ? $contact_showroom['showroom_phone'] : $contact_showroom['head_office_phone']; ?></a>
                                    </div>
                                </div>
                                <div class="contact-info-item">
                                    <div class="info-icon"><i class="fa-light fa-location-dot"></i></div>
                                    <div class="info-content"><span class="info-label"></span><a class="info-value" href="<?php echo !empty($contact_showroom['showroom_map_link']) ? $contact_showroom['showroom_map_link'] : '#'; ?>"><?php echo !empty($contact_showroom['showroom_address']) ? $contact_showroom['showroom_address'] : ''; ?></a>
                                    </div>
                                </div>
                            </div><a class="btn-direction" href="<?php echo !empty($contact_showroom['showroom_map_link']) ? $contact_showroom['showroom_map_link'] : '#'; ?>" target="_blank"><span><?php _e('GET DIRECTIONS', 'huyvo'); ?></span><i class="fa-regular fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

    <section class="contact-center-info">
        <div class="container">
            <div class="center-content">
                <div class="info-block">
                    <h3 class="block-title"><?php _e('HEAD OFFICE', 'huyvo'); ?></h3>
                    <div class="block-content">
                        <p><?php echo !empty($head_office['head_office_hours']) ? $head_office['head_office_hours'] : ''; ?></p>
                        <p><?php echo !empty($head_office['head_office_address']) ? $head_office['head_office_address'] : ''; ?></p>
                        <p><?php echo !empty($head_office['head_office_phone']) ? $head_office['head_office_phone'] : ''; ?></p>
                        <a href="mailto:<?php echo !empty($head_office['head_office_email']) ? $head_office['head_office_email'] : ''; ?>">
                            <?php echo !empty($head_office['head_office_email']) ? $head_office['head_office_email'] : ''; ?>
                        </a>
                    </div>
                </div>
                <div class="info-block">
                    <h3 class="block-title"><?php _e('SOCIAL', 'huyvo'); ?></h3>
                    <div class="block-content">
                        <?php foreach ($socials as $social) : ?>
                            <a href="<?php echo !empty($social['social']['url']) ? $social['social']['url'] : '#'; ?>"
                                target="<?php echo !empty($social['social']['target']) ? $social['social']['target'] : '_self'; ?>"
                                rel="<?php echo !empty($social['social']['rel']) ? $social['social']['rel'] : 'nofollow'; ?>">
                                <?php echo !empty($social['social']['title']) ? $social['social']['title'] : ''; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="info-block">
                    <h3 class="block-title"><?php _e('STORE APPOINTMENT', 'huyvo'); ?></h3>
                    <div class="block-content">
                        <a class="link-underline" href="<?php echo !empty($store_appointment_link['url']) ? $store_appointment_link['url'] : '#'; ?>" target="<?php echo !empty($store_appointment_link['target']) ? $store_appointment_link['target'] : '_self'; ?>" rel="<?php echo !empty($store_appointment_link['rel']) ? $store_appointment_link['rel'] : 'nofollow'; ?>"><?php echo !empty($store_appointment_link['title']) ? $store_appointment_link['title'] : ''; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php get_footer(); ?>