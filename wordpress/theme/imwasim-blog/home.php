<?php get_header(); ?>
<main id="main">
    <section class="blog-hero">
        <div class="container">
            <p class="eyebrow">// Field notes by Wasim Arshad</p>
            <h1>Architecture, AI &amp; engineering leadership.</h1>
            <p class="blog-hero__lead">Practical ideas for building dependable AI agents, scalable software platforms, strong engineering teams, and intelligent automation that matters.</p>
        </div>
    </section>
    <section class="blog-grid" id="topics">
        <div class="container">
            <div class="section-heading"><h2>Latest articles</h2><p>Ideas made useful.</p></div>
            <div class="posts">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article <?php post_class('post-card'); ?>>
                        <div class="post-card__meta"><span class="tag"><?php echo esc_html(get_the_category()[0]->name ?? 'Perspective'); ?></span><span><?php echo esc_html(get_the_date('M j, Y')); ?></span></div>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                        <div class="post-card__footer"><span><?php echo esc_html(imwasim_reading_time()); ?></span><span class="post-card__arrow" aria-hidden="true">↗</span></div>
                    </article>
                <?php endwhile; else : ?>
                    <div class="empty-state">The first article is being prepared.</div>
                <?php endif; ?>
            </div>
            <div class="pagination"><?php the_posts_pagination(['mid_size' => 1, 'prev_text' => '← Newer', 'next_text' => 'Older →']); ?></div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
