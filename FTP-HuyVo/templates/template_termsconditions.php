<?php

/**
 * Template Name: Terms & Conditions
 */

get_header();
?>
<div class="policy-page wrapper-gap-top">
    <div class="container max-w-full">
        <?php get_template_part('components/section-breadcrumb'); ?>
        <div class="policy-header">
            <div class="container"><?php the_title(); ?></div>
        </div>
    </div>
    <div class="policy-content">
        <div class="content-container">
            <nav class="policy-sidebar">
                <?php wp_nav_menu(array('theme_location' => 'menu-policy', 'menu_class' => 'policy-menu', 'container' => false, 'walker' => new Walker_Menu_Policy())); ?>
            </nav>
            <div class="policy-main">
                <div class="policy-body">
                    <?php the_content(); ?>
                    <p class="date-label"><?php _e('Date', 'huyvo'); ?></p>
                    <p class="date-value"><?php printf(__('This statement was created on %s.', 'huyvo'), get_the_date()); ?></p>
                </div>
                <div class="policy-form-wrapper">
                    <h3 class="form-title"><?php _e('SEND MESSAGE', 'huyvo'); ?></h3>
                    <div class="wpcf7">
                        <?php echo do_shortcode('[contact-form-7 id="fb93f1b" title="Form Send MESSAGE"]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>