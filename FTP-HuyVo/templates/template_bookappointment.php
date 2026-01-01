<?php

/**
 * Template Name: Book Appointment
 */

get_header();
?>
<section class="section-book-appointment wrapper-gap-top">
    <div class="container">
        <h1 class="title" data-aos="fade-up"><?php the_title(); ?></h1>
        <div class="appointment-form" data-aos="fade-up" data-aos-delay="200">
            <?php echo do_shortcode('[contact-form-7 id="9ae8f1d" title="Book Appointment"]'); ?>
        </div>
    </div>
</section>
<?php

get_footer();
