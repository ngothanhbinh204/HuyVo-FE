<?php
$thumb_2 = get_field('thumb_2', get_the_ID());
?>
<div class="explore-item" data-aos="fade-up" data-aos-delay="0">
    <img src="<?php echo !empty($thumb_2['url']) ? $thumb_2['url'] : get_the_post_thumbnail_url(get_the_ID()); ?>" alt="<?php echo !empty($thumb_2['alt']) ? $thumb_2['alt'] : get_the_title(); ?>">
    <a class="btn-explore primary-white" href="<?php echo get_the_permalink(); ?>">
        <?php echo sprintf(__('EXPLORE %s', 'huyvo'), get_the_title()); ?>
    </a>
</div>