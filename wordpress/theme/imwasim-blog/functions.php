<?php
/** Theme setup and article visuals. */

if (!defined('ABSPATH')) {
    exit;
}

function imwasim_blog_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('automatic-feed-links');
    register_nav_menus(['primary' => __('Primary navigation', 'imwasim-blog')]);
}
add_action('after_setup_theme', 'imwasim_blog_setup');

function imwasim_blog_assets(): void {
    $version = wp_get_theme()->get('Version');
    wp_enqueue_style('imwasim-blog', get_stylesheet_uri(), [], $version);
    wp_enqueue_script('imwasim-blog', get_template_directory_uri() . '/assets/js/theme.js', [], $version, true);
}
add_action('wp_enqueue_scripts', 'imwasim_blog_assets');

function imwasim_document_title(string $title): string {
    if (!is_singular('post')) {
        return $title;
    }
    $titles = [
        'what-is-jev-typesafe-ai-system-one-model' => 'What Is Jev? TypeSafe AI’s System One Model | Wasim Arshad',
        'model-context-protocol-mcp-introduction' => 'Model Context Protocol (MCP): AI Agents Guide | Wasim Arshad',
        'software-architecture-guide' => 'Software Architecture Guide: Scalable Systems | Wasim Arshad',
        'ai-impact-on-software-development' => 'AI Impact on Software Development: 2026 Guide | Wasim Arshad',
        'software-testing-strategies' => 'Software Testing Strategies: Practical Guide | Wasim Arshad',
    ];
    $slug = get_post_field('post_name', get_queried_object_id());
    return $titles[$slug] ?? $title;
}
add_filter('pre_get_document_title', 'imwasim_document_title');

function imwasim_social_meta(): void {
    $is_article = is_singular('post');
    $title = $is_article ? get_the_title() : get_bloginfo('name');
    $description = $is_article
        ? (get_the_excerpt() ?: wp_trim_words(wp_strip_all_tags(get_the_content()), 30))
        : get_bloginfo('description');
    $url = $is_article ? get_permalink() : home_url('/');
    $article_images = [
        'model-context-protocol-mcp-introduction' => 'https://imwasim.com/img/blog/mcp-architecture-editorial.webp',
        'software-architecture-guide' => 'https://imwasim.com/img/blog/software-architecture-editorial.webp',
        'ai-impact-on-software-development' => 'https://imwasim.com/img/blog/ai-software-development-editorial.webp',
        'software-testing-strategies' => 'https://imwasim.com/img/blog/software-testing-team.webp',
    ];
    $slug = $is_article ? get_post_field('post_name', get_queried_object_id()) : '';
    $image = $article_images[$slug] ?? 'https://imwasim.com/img/muhammad-wasim-arshad-720.webp';
    $image_alt = $is_article ? $title : 'Muhammad Wasim Arshad, AI Solution Architect and Engineering Leader';
    ?>
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:type" content="<?php echo $is_article ? 'article' : 'website'; ?>">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <meta property="og:site_name" content="Wasim Arshad">
    <meta property="og:image" content="<?php echo esc_url($image); ?>">
    <meta property="og:image:alt" content="<?php echo esc_attr($image_alt); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($image); ?>">
    <?php
    if ($is_article) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $title,
            'description' => $description,
            'datePublished' => get_the_date(DATE_W3C),
            'dateModified' => get_the_modified_date(DATE_W3C),
            'mainEntityOfPage' => $url,
            'articleSection' => wp_get_post_categories(get_the_ID(), ['fields' => 'names']),
            'keywords' => wp_get_post_tags(get_the_ID(), ['fields' => 'names']),
            'author' => ['@type' => 'Person', 'name' => 'Muhammad Wasim Arshad', 'url' => 'https://imwasim.com/'],
            'publisher' => ['@type' => 'Person', 'name' => 'Muhammad Wasim Arshad', 'url' => 'https://imwasim.com/'],
            'image' => $image,
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    } else {
        echo '<link rel="canonical" href="' . esc_url(home_url('/')) . '">' . "\n";
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Blog',
            'name' => $title,
            'description' => $description,
            'url' => $url,
            'publisher' => ['@type' => 'Person', 'name' => 'Muhammad Wasim Arshad', 'url' => 'https://imwasim.com/'],
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}
add_action('wp_head', 'imwasim_social_meta', 2);

function imwasim_reading_time(int $post_id = 0): string {
    $content = get_post_field('post_content', $post_id ?: get_the_ID());
    $words = str_word_count(wp_strip_all_tags(strip_shortcodes((string) $content)));
    return max(1, (int) ceil($words / 220)) . ' min read';
}

function imwasim_menu_fallback(): void {
    echo '<ul class="nav-links" id="site-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Articles</a></li>';
    echo '<li><a href="' . esc_url(home_url('/#topics')) . '">Topics</a></li>';
    echo '<li><a href="' . esc_url('https://imwasim.com/#about') . '">About</a></li>';
    echo '<li><a href="' . esc_url('https://imwasim.com/#contact') . '">Contact</a></li>';
    echo '</ul>';
}

function imwasim_archive_title(string $title): string {
    return preg_replace('/^(Category|Tag|Author):\s*/', '', $title) ?: $title;
}
add_filter('get_the_archive_title', 'imwasim_archive_title');

function imwasim_related_articles(int $post_id): string {
    $related = new WP_Query([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'post__not_in' => [$post_id],
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
    ]);
    if (!$related->have_posts()) {
        return '';
    }
    ob_start(); ?>
    <aside class="related-articles" aria-labelledby="related-articles-title">
        <div class="container">
            <div class="section-heading"><div><p class="eyebrow">// Keep exploring</p><h2 id="related-articles-title">Related articles</h2></div><p>More practical architecture and AI guidance.</p></div>
            <div class="related-grid">
                <?php while ($related->have_posts()) : $related->the_post(); ?>
                <article class="related-card">
                    <p class="related-card__meta"><?php echo esc_html(get_the_category()[0]->name ?? 'Perspective'); ?> · <?php echo esc_html(imwasim_reading_time()); ?></p>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    <a class="related-card__link" href="<?php the_permalink(); ?>" aria-label="Read <?php echo esc_attr(get_the_title()); ?>">Read article <span aria-hidden="true">↗</span></a>
                </article>
                <?php endwhile; ?>
            </div>
        </div>
    </aside>
    <?php
    wp_reset_postdata();
    return (string) ob_get_clean();
}

function imwasim_architecture_tradeoff_map(): string {
    ob_start(); ?>
    <figure class="interactive-figure diagram-figure">
        <div class="diagram-scroll" role="region" aria-label="Scrollable software architecture pattern trade-off map" tabindex="0">
            <svg class="architecture-diagram architecture-pattern-map" viewBox="0 0 1200 590" role="img" aria-labelledby="pattern-map-title pattern-map-desc">
                <title id="pattern-map-title">Software architecture pattern trade-off map</title>
                <desc id="pattern-map-desc">A comparison of layered architecture, modular monoliths, microservices, event-driven architecture, and clean or hexagonal architecture, showing their strongest use cases and primary costs.</desc>
                <defs><pattern id="pattern-paper-dots" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.2" fill="#d8d4ca" opacity=".62"/></pattern><filter id="pattern-marker-rough" x="-10%" y="-10%" width="120%" height="120%"><feTurbulence type="fractalNoise" baseFrequency=".018" numOctaves="2" seed="19" result="noise"/><feDisplacementMap in="SourceGraphic" in2="noise" scale="1.2"/></filter><style>.pm-title{font:800 32px "Comic Sans MS","Marker Felt",cursive;fill:#172033}.pm-label{font:800 19px "Comic Sans MS","Marker Felt",cursive;fill:#172033}.pm-small{font:700 13px "Comic Sans MS","Marker Felt",cursive;fill:#536070}.pm-code{font:700 12px "JetBrains Mono",monospace;fill:#334155}.pm-box{fill:#fffdf7;stroke-width:4;filter:url(#pattern-marker-rough)}</style></defs>
                <rect width="1200" height="590" rx="22" fill="#fffdf7"/><rect width="1200" height="590" rx="22" fill="url(#pattern-paper-dots)"/>
                <text class="pm-title" x="48" y="58">CHOOSE A PATTERN BY THE FORCE IT MUST HANDLE</text><text class="pm-small" x="49" y="88">Patterns are constraints with benefits and costs — they are not maturity levels.</text>
                <g transform="translate(48 125)"><rect class="pm-box" width="200" height="320" rx="20" stroke="#2563eb"/><text class="pm-label" x="20" y="42">LAYERED</text><text class="pm-small" x="20" y="76">BEST WHEN</text><text class="pm-code" x="20" y="101">familiar domain</text><text class="pm-code" x="20" y="123">stable workflow</text><text class="pm-small" x="20" y="166">OPTIMIZES</text><text class="pm-code" x="20" y="191">simplicity</text><text class="pm-code" x="20" y="213">clear tiers</text><text class="pm-small" x="20" y="256">WATCH</text><text class="pm-code" x="20" y="281">cross-layer change</text></g>
                <g transform="translate(274 125)"><rect class="pm-box" width="200" height="320" rx="20" stroke="#059669"/><text class="pm-label" x="20" y="42">MODULAR</text><text class="pm-label" x="20" y="66">MONOLITH</text><text class="pm-small" x="20" y="101">BEST WHEN</text><text class="pm-code" x="20" y="126">one deployment</text><text class="pm-code" x="20" y="148">strong modules</text><text class="pm-small" x="20" y="191">OPTIMIZES</text><text class="pm-code" x="20" y="216">delivery speed</text><text class="pm-code" x="20" y="238">low operations</text><text class="pm-small" x="20" y="281">WATCH</text><text class="pm-code" x="20" y="306">boundary erosion</text></g>
                <g transform="translate(500 125)"><rect class="pm-box" width="200" height="320" rx="20" stroke="#7c3aed"/><text class="pm-label" x="20" y="42">MICROSERVICES</text><text class="pm-small" x="20" y="76">BEST WHEN</text><text class="pm-code" x="20" y="101">independent scale</text><text class="pm-code" x="20" y="123">team autonomy</text><text class="pm-small" x="20" y="166">OPTIMIZES</text><text class="pm-code" x="20" y="191">deploy isolation</text><text class="pm-code" x="20" y="213">fault boundaries</text><text class="pm-small" x="20" y="256">WATCH</text><text class="pm-code" x="20" y="281">distributed cost</text></g>
                <g transform="translate(726 125)"><rect class="pm-box" width="200" height="320" rx="20" stroke="#d97706"/><text class="pm-label" x="20" y="42">EVENT-DRIVEN</text><text class="pm-small" x="20" y="76">BEST WHEN</text><text class="pm-code" x="20" y="101">async reactions</text><text class="pm-code" x="20" y="123">many consumers</text><text class="pm-small" x="20" y="166">OPTIMIZES</text><text class="pm-code" x="20" y="191">decoupling</text><text class="pm-code" x="20" y="213">burst handling</text><text class="pm-small" x="20" y="256">WATCH</text><text class="pm-code" x="20" y="281">ordering • replay</text></g>
                <g transform="translate(952 125)"><rect class="pm-box" width="200" height="320" rx="20" stroke="#ef4444"/><text class="pm-label" x="20" y="42">CLEAN /</text><text class="pm-label" x="20" y="66">HEXAGONAL</text><text class="pm-small" x="20" y="101">BEST WHEN</text><text class="pm-code" x="20" y="126">rich domain logic</text><text class="pm-code" x="20" y="148">replaceable edges</text><text class="pm-small" x="20" y="191">OPTIMIZES</text><text class="pm-code" x="20" y="216">testability</text><text class="pm-code" x="20" y="238">technology change</text><text class="pm-small" x="20" y="281">WATCH</text><text class="pm-code" x="20" y="306">extra abstraction</text></g>
                <path d="M64 493 C326 478 679 506 1136 488" fill="none" stroke="#2563eb" stroke-width="4" stroke-linecap="round" stroke-dasharray="11 10"/><text class="pm-label" x="48" y="540">DECISION RULE:</text><text class="pm-code" x="215" y="540">choose the simplest structure that protects today’s critical quality attributes and leaves a credible path to evolve.</text>
            </svg>
        </div>
        <figcaption>Pattern map: select architecture by workload forces and trade-offs. A more distributed pattern is useful only when its benefits exceed its operational cost.</figcaption>
    </figure>
    <?php return (string) ob_get_clean();
}
add_shortcode('architecture_tradeoff_map', 'imwasim_architecture_tradeoff_map');

function imwasim_mcp_architecture(): string {
    ob_start(); ?>
    <figure class="interactive-figure mcp-architecture" data-interactive="architecture">
        <div class="interactive-figure__top">
            <div><h3>MCP architecture at a glance</h3><p>Select a layer to see its responsibility.</p></div>
            <div class="figure-controls" aria-label="Architecture layers">
                <button type="button" aria-pressed="true" data-layer="host">Host</button>
                <button type="button" aria-pressed="false" data-layer="client">Client</button>
                <button type="button" aria-pressed="false" data-layer="server">Server</button>
            </div>
        </div>
        <svg class="mcp-map" viewBox="0 0 1000 330" role="img" aria-labelledby="mcp-map-title mcp-map-desc">
            <title id="mcp-map-title">Model Context Protocol architecture</title>
            <desc id="mcp-map-desc">An AI host connects through an MCP client to an MCP server, which exposes tools, resources, and prompts.</desc>
            <defs><linearGradient id="mcp-gradient" x1="0" x2="1"><stop stop-color="#10c98a"/><stop offset="1" stop-color="#60a5fa"/></linearGradient></defs>
            <path class="connector" d="M250 150H370M630 150H748"/>
            <circle class="packet" r="7"><animateMotion dur="3s" repeatCount="indefinite" path="M250 150H370"/></circle>
            <circle class="packet" r="7"><animateMotion dur="3.6s" repeatCount="indefinite" path="M630 150H748"/></circle>
            <g data-node="host"><rect class="node is-active" x="32" y="75" rx="22" width="218" height="150"/><text x="58" y="122" font-size="16" fill="#10c98a">01 / HOST</text><text x="58" y="158" font-size="25" font-weight="800">AI application</text><text x="58" y="190" font-size="14" fill="#94a3bf">Conversation + consent</text></g>
            <g data-node="client"><rect class="node" x="370" y="75" rx="22" width="260" height="150"/><text x="398" y="122" font-size="16" fill="#60a5fa">02 / CLIENT</text><text x="398" y="158" font-size="25" font-weight="800">Protocol bridge</text><text x="398" y="190" font-size="14" fill="#94a3bf">Connection + messages</text></g>
            <g data-node="server"><rect class="node" x="748" y="30" rx="22" width="220" height="240"/><text x="774" y="76" font-size="16" fill="#a78bfa">03 / SERVER</text><text x="774" y="110" font-size="25" font-weight="800">Capabilities</text><rect x="774" y="132" width="164" height="34" rx="9" fill="url(#mcp-gradient)" opacity=".18"/><text x="792" y="155" font-size="14">Tools</text><rect x="774" y="177" width="164" height="34" rx="9" fill="url(#mcp-gradient)" opacity=".18"/><text x="792" y="200" font-size="14">Resources</text><rect x="774" y="222" width="164" height="34" rx="9" fill="url(#mcp-gradient)" opacity=".18"/><text x="792" y="245" font-size="14">Prompts</text></g>
        </svg>
        <figcaption class="figure-note" aria-live="polite">The host owns the user experience and controls permissions, context, and consent.</figcaption>
    </figure>
    <?php return (string) ob_get_clean();
}
add_shortcode('mcp_architecture', 'imwasim_mcp_architecture');

function imwasim_mcp_primitives(): string {
    ob_start(); ?>
    <figure class="interactive-figure" data-interactive="primitives">
        <div class="interactive-figure__top"><div><h3>Three primitives, three kinds of context</h3><p>Explore what an MCP server can expose.</p></div></div>
        <div class="primitive-grid">
            <button class="primitive is-active" type="button" data-detail="Tools are callable actions such as searching records, running a calculation, or opening a ticket. The model can propose a call; the host enforces policy and consent."><span>MODEL-CONTROLLED</span><h4>Tools</h4><p>Actions with typed inputs and outputs.</p></button>
            <button class="primitive" type="button" data-detail="Resources are addressable context such as documents, schemas, policies, or read-only records. Applications decide how they enter the model context."><span>APP-CONTROLLED</span><h4>Resources</h4><p>Context addressed with a URI.</p></button>
            <button class="primitive" type="button" data-detail="Prompts are reusable interaction templates that users deliberately select, such as an incident summary or architecture review checklist."><span>USER-CONTROLLED</span><h4>Prompts</h4><p>Reusable workflows and instructions.</p></button>
        </div>
        <figcaption class="figure-note" aria-live="polite">Tools are callable actions. The model can propose a call; the host enforces policy and consent.</figcaption>
    </figure>
    <?php return (string) ob_get_clean();
}
add_shortcode('mcp_primitives', 'imwasim_mcp_primitives');

function imwasim_mcp_flow(): string {
    $steps = ['Discover', 'Understand', 'Authorize', 'Execute', 'Observe'];
    ob_start(); ?>
    <figure class="interactive-figure">
        <div class="interactive-figure__top"><div><h3>From intent to a governed action</h3><p>A reliable integration makes every boundary visible.</p></div></div>
        <svg class="mcp-flow" viewBox="0 0 1000 180" role="img" aria-label="Five steps in an MCP request flow">
            <path class="connector" d="M70 90H930"/>
            <circle class="flow-dot" r="7"/><circle class="flow-dot" r="6"/>
            <?php foreach ($steps as $index => $step) : $x = 70 + ($index * 215); ?>
                <g class="pulse" style="animation-delay:-<?php echo esc_attr((string) ($index * .35)); ?>s"><circle class="step" cx="<?php echo esc_attr((string) $x); ?>" cy="90" r="45"/><text x="<?php echo esc_attr((string) $x); ?>" y="95" font-size="14" font-weight="750" text-anchor="middle"><?php echo esc_html($step); ?></text><text x="<?php echo esc_attr((string) $x); ?>" y="155" font-size="12" text-anchor="middle" fill="#94a3bf">0<?php echo esc_html((string) ($index + 1)); ?></text></g>
            <?php endforeach; ?>
        </svg>
        <figcaption class="figure-note">Good MCP systems preserve traceability from capability discovery through authorization, execution, and audit.</figcaption>
    </figure>
    <?php return (string) ob_get_clean();
}
add_shortcode('mcp_flow', 'imwasim_mcp_flow');

function imwasim_faq_schema(): void {
    if (!is_singular('post')) {
        return;
    }
    $faq_by_slug = [
        'model-context-protocol-mcp-introduction' => [
            ['@type' => 'Question', 'name' => 'What is Model Context Protocol?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Model Context Protocol is an open standard for connecting AI applications to external tools, data, and reusable prompts through a consistent interface.']],
            ['@type' => 'Question', 'name' => 'Does MCP replace APIs?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'No. MCP commonly sits above existing APIs and data systems, giving AI applications a standard way to discover and use their capabilities.']],
            ['@type' => 'Question', 'name' => 'What is the difference between MCP and RAG?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'RAG retrieves relevant information for a model context. MCP is a broader interoperability protocol that can expose resources, prompts, and callable tools. A system can use both together.']],
            ['@type' => 'Question', 'name' => 'Who created MCP, and does OpenAI support it?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'MCP was created and open-sourced by Anthropic in 2024. OpenAI is an early adopter and core contributor and supports MCP through ChatGPT integrations and the OpenAI Agents SDK.']],
        ],
        'software-architecture-guide' => [
            ['@type' => 'Question', 'name' => 'What is software architecture?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Software architecture is the set of structural decisions that defines a system’s major components, their responsibilities and interactions, data ownership, deployment model, and measurable quality attributes.']],
            ['@type' => 'Question', 'name' => 'What makes software architecture scalable?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Scalable architecture measures workload demand, removes shared bottlenecks, partitions state deliberately, uses caching and asynchronous work where appropriate, and scales only the components that need additional capacity.']],
            ['@type' => 'Question', 'name' => 'Should a new system start with microservices?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Usually only when independent deployment, scaling, fault isolation, or team autonomy justify the operational cost. A modular monolith is often a safer starting point when the domain and service boundaries are still evolving.']],
            ['@type' => 'Question', 'name' => 'What is an Architecture Decision Record?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'An Architecture Decision Record, or ADR, is a short document that records a consequential decision, its context, considered options, outcome, trade-offs, and consequences.']],
            ['@type' => 'Question', 'name' => 'How often should software architecture be reviewed?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Review architecture when business goals, workload assumptions, risks, team boundaries, or production evidence change, and at planned checkpoints for critical quality attributes.']],
        ],
        'ai-impact-on-software-development' => [
            ['@type' => 'Question', 'name' => 'How is AI changing software development?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'AI is shifting software development from manual code production toward intent definition, contextual generation, automated analysis, and faster feedback across requirements, design, coding, testing, review, delivery, and operations.']],
            ['@type' => 'Question', 'name' => 'Will AI replace software developers?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'AI will automate parts of software work, but developers remain responsible for product intent, architecture, security, validation, trade-offs, and production outcomes. The role is changing toward engineering judgment and system stewardship.']],
            ['@type' => 'Question', 'name' => 'Does AI improve developer productivity?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'AI can reduce time spent on search, boilerplate, tests, documentation, and routine changes, but organization-level gains depend on code quality, review capacity, platform engineering, workflow design, and trusted delivery practices.']],
            ['@type' => 'Question', 'name' => 'What are the risks of AI-generated code?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Risks include incorrect behavior, insecure dependencies, fabricated APIs, weak edge-case handling, license or provenance concerns, sensitive-data exposure, and code that passes superficial review without fitting the system architecture.']],
        ],
        'software-testing-strategies' => [
            ['@type' => 'Question', 'name' => 'What is a software testing strategy?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'A software testing strategy is a risk-based plan describing what a team will verify, at which testing layer, in which environment, with what data and automation, and which evidence is required before and after release.']],
            ['@type' => 'Question', 'name' => 'How many end-to-end tests should a project have?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'There is no universal number. Keep enough end-to-end tests to protect valuable cross-system journeys, while testing most rules and edge cases through faster component and integration tests.']],
            ['@type' => 'Question', 'name' => 'What should a team automate first?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Automate stable, repeated, high-value checks that produce clear results. Use human judgment for exploratory testing, usability assessment, and investigation.']],
        ],
    ];
    $slug = get_post_field('post_name', get_queried_object_id());
    if (!isset($faq_by_slug[$slug])) {
        return;
    }
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faq_by_slug[$slug],
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'imwasim_faq_schema');

function imwasim_llms_txt(): void {
    $path = trim((string) wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    if (!str_ends_with($path, 'blog/llms.txt')) {
        return;
    }
    status_header(200);
    header('Content-Type: text/plain; charset=utf-8');
    echo "# Wasim Arshad — Architecture, AI & Engineering Leadership\n\n";
    echo "> Practical writing about AI agents, software architecture, intelligent automation, and engineering leadership.\n\n";
    echo "## Articles\n\n";
    foreach (get_posts(['numberposts' => 50, 'post_status' => 'publish']) as $post) {
        echo '- [' . get_the_title($post) . '](' . get_permalink($post) . '): ' . wp_strip_all_tags(get_the_excerpt($post)) . "\n";
    }
    exit;
}
add_action('template_redirect', 'imwasim_llms_txt');
