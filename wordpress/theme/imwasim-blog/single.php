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
            <figure class="article-lead-media">
                <img src="/img/blog/mcp-architecture-editorial.webp" width="1600" height="900" alt="AI application connecting securely to code, data, business systems, and cloud services through a protocol gateway" decoding="async" fetchpriority="high">
                <figcaption>A practical view of MCP as a governed connection between an AI host and the systems it needs to use.</figcaption>
            </figure>
            <nav class="toc" aria-label="Article contents"><strong>In this article</strong><ol><li><a href="#technical-definition">Technical definition</a></li><li><a href="#why-mcp-exists">Why MCP exists</a></li><li><a href="#anthropic-openai">Anthropic and OpenAI</a></li><li><a href="#mental-model">Host, client, server</a></li><li><a href="#primitives">Core primitives</a></li><li><a href="#request-flow">Protocol process</a></li><li><a href="#transaction-walkthrough">Transaction walkthrough</a></li><li><a href="#adoption">Adoption checklist</a></li></ol></nav>
            <?php elseif (is_single('software-architecture-guide')) : ?>
            <figure class="article-lead-media">
                <img src="/img/blog/software-architecture-editorial.webp" width="1600" height="900" alt="Layered software architecture connecting applications, modular services, data stores, observability, and cloud infrastructure" decoding="async" fetchpriority="high">
                <figcaption>Architecture makes boundaries, dependencies, data ownership, and operational feedback visible.</figcaption>
            </figure>
            <nav class="toc" aria-label="Article contents"><strong>In this article</strong><ol><li><a href="#definition">Technical definition</a></li><li><a href="#quality-attributes">Quality attributes</a></li><li><a href="#principles">Architecture principles</a></li><li><a href="#patterns">Pattern comparison</a></li><li><a href="#design-process">Design process</a></li><li><a href="#scalability">Scalability</a></li><li><a href="#documentation">ADRs and diagrams</a></li><li><a href="#faq">Frequently asked questions</a></li></ol></nav>
            <?php elseif (is_single('ai-impact-on-software-development')) : ?>
            <figure class="article-lead-media">
                <img src="/img/blog/ai-software-development-editorial.webp" width="1600" height="900" alt="Software engineer directing AI assistance across planning, architecture, coding, testing, deployment, and operations" decoding="async" fetchpriority="high">
                <figcaption>AI can accelerate the delivery loop, while engineers remain responsible for judgment and production outcomes.</figcaption>
            </figure>
            <nav class="toc" aria-label="Article contents"><strong>In this article</strong><ol><li><a href="#direct-impact">Direct impact</a></li><li><a href="#lifecycle">AI across the SDLC</a></li><li><a href="#developer-role">Changing developer role</a></li><li><a href="#productivity">Productivity and delivery</a></li><li><a href="#risks">Risks and controls</a></li><li><a href="#adoption">Adoption playbook</a></li><li><a href="#faq">Frequently asked questions</a></li></ol></nav>
            <?php elseif (is_single('software-testing-strategies')) : ?>
            <figure class="article-lead-media">
                <img src="/img/blog/software-testing-team.webp" width="1600" height="900" alt="Software engineering team reviewing automated test evidence and release risks together" decoding="async" fetchpriority="high">
                <figcaption>A useful testing strategy turns release decisions into evidence the whole team can understand.</figcaption>
            </figure>
            <nav class="toc" aria-label="Article contents"><strong>In this article</strong><ol><li><a href="#risk">Risk-based testing</a></li><li><a href="#layers">Testing layers</a></li><li><a href="#quality">Quality attributes</a></li><li><a href="#pipeline">Delivery feedback</a></li><li><a href="#production">Production verification</a></li><li><a href="#faq">Frequently asked questions</a></li></ol></nav>
            <?php endif; ?>
            <div class="article-content"><?php the_content(); ?></div>
        </div>
        <?php echo imwasim_related_articles(get_the_ID()); ?>
    </article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
