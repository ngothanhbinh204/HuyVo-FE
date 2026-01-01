<?php

/**
 * Template Name: Sustainability
 */
global $post;
$sustainability_pdf_groups = get_field('sustainability_pdf_groups', $post->ID);

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
                        <?php if ($sustainability_pdf_groups) : ?>
                            <?php foreach ($sustainability_pdf_groups as $sustainability_pdf_group) : ?>
                                <div class="pdf-group">
                                    <h3 class="pdf-group-title"><?php echo !empty($sustainability_pdf_group['group_title']) ? $sustainability_pdf_group['group_title'] : ''; ?></h3>
                                    <div class="pdf-list">
                                        <?php foreach ($sustainability_pdf_group['pdf_items'] as $pdf) : ?>
                                            <div class="pdf-item">
                                                <div class="pdf-icon">
                                                    <?php if (!empty($pdf['pdf_icon'])) : ?>
                                                        <img src="<?php echo $pdf['pdf_icon']['url']; ?>" alt="<?php echo $pdf['pdf_icon']['alt']; ?>">
                                                    <?php else : ?>
                                                        <img src="<?php echo get_template_directory_uri(); ?>/img/pdf-icon.svg" alt="PDF">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="pdf-info"><span class="pdf-title"><?php echo !empty($pdf['pdf_title']) ? $pdf['pdf_title'] : ''; ?></span></div>
                                                <?php if (!empty($pdf['pdf_file']['url'])) : ?>
                                                    <a class="pdf-download" href="<?php echo $pdf['pdf_file']['url']; ?>" download><?php _e('Download', 'huyvo'); ?></a>
                                                <?php else : ?>
                                                    <a class="pdf-download" href="#"><?php _e('Download', 'huyvo'); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>