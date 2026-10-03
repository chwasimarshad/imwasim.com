<?php get_header(); ?>
<main id="main">
    <section class="blog-hero"><div class="container"><p class="eyebrow">// Browse</p><h1><?php echo esc_html(is_archive() ? get_the_archive_title() : 'Articles'); ?></h1><?php if (is_archive()) : ?><p class="blog-hero__lead"><?php echo wp_kses_post(get_the_archive_description()); ?></p><?php endif; ?></div></section>
    <section class="blog-grid"><div class="container"><div class="posts">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article <?php post_class('post-card'); ?>><div class="post-card__meta"><span class="tag"><?php echo esc_html(get_the_category()[0]->name ?? 'Perspective'); ?></span><span><?php echo esc_html(get_the_date('M j, Y')); ?></span></div><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html(get_the_excerpt()); ?></p><div class="post-card__footer"><span><?php echo esc_html(imwasim_reading_time()); ?></span><span class="post-card__arrow">↗</span></div></article>
        <?php endwhile; else : ?><div class="empty-state">No articles found.</div><?php endif; ?>
    </div><div class="pagination"><?php the_posts_pagination(); ?></div></div></section>
</main>
<?php get_footer(); ?>
