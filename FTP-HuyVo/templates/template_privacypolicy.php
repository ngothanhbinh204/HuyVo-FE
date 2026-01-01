<?php

/**
 * Template Name: Privacy Policy
 */

get_header();
?>
<div class="policy-page wrapper-gap-top">
    <div class="container max-w-full">
        <div class="breadcrumb-wrapper">
            <?php get_template_part('components/section-breadcrumb'); ?>
        </div>
        <div class="policy-header">
            <div class="container">
                <h1 class="page-title"><?php the_title(); ?></h1>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>