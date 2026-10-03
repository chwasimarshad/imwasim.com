<?php get_header(); ?>
<main id="main">
<?php while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
        <header class="article-hero"><div class="container article-hero__inner">
            <div class="breadcrumbs"><a href="<?php echo esc_url(home_url('/')); ?>">Blog</a> / <?php echo esc_html(get_the_category()[0]->name ?? 'Article'); ?></div>
            <p class="eyebrow">// <?php echo esc_html(get_the_category()[0]->name ?? 'Perspective'); ?></p>
            <h1><?php the_title(); ?></h1>
            <p class="article-deck"><?php echo esc_html(get_the_excerpt()); ?></p>
            <div class="article-meta"><span>By Muhammad Wasim Arshad</span><span><?php echo esc_html(get_the_date('F j, Y')); ?></span><span><?php echo esc_html(imwasim_reading_time()); ?></span></div>
        </div></header>
        <div class="article-body">
            <?php if (is_single('model-context-protocol-mcp-introduction')) : ?>
            <?php echo do_shortcode('[mcp_whiteboard]'); ?>
            <nav class="toc" aria-label="Article contents"><strong>In this article</strong><ol><li><a href="#technical-definition">Technical definition</a></li><li><a href="#why-mcp-exists">Why MCP exists</a></li><li><a href="#anthropic-openai">Anthropic and OpenAI</a></li><li><a href="#mental-model">Host, client, server</a></li><li><a href="#primitives">Core primitives</a></li><li><a href="#request-flow">Protocol process</a></li><li><a href="#transaction-walkthrough">Transaction walkthrough</a></li><li><a href="#adoption">Adoption checklist</a></li></ol></nav>
            <?php endif; ?>
            <div class="article-content"><?php the_content(); ?></div>
        </div>
    </article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
