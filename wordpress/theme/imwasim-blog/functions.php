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

function imwasim_social_meta(): void {
    $is_article = is_singular('post');
    $title = $is_article ? get_the_title() : get_bloginfo('name');
    $description = $is_article
        ? (get_the_excerpt() ?: wp_trim_words(wp_strip_all_tags(get_the_content()), 30))
        : get_bloginfo('description');
    $url = $is_article ? get_permalink() : home_url('/');
    $image = 'https://imwasim.com/img/muhammad-wasim-arshad-720.webp';
    ?>
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:type" content="<?php echo $is_article ? 'article' : 'website'; ?>">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <meta property="og:site_name" content="Wasim Arshad">
    <meta property="og:image" content="<?php echo esc_url($image); ?>">
    <meta property="og:image:alt" content="Muhammad Wasim Arshad, AI Solution Architect and Engineering Leader">
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
            'author' => ['@type' => 'Person', 'name' => 'Muhammad Wasim Arshad', 'url' => 'https://imwasim.com/'],
            'publisher' => ['@type' => 'Person', 'name' => 'Muhammad Wasim Arshad', 'url' => 'https://imwasim.com/'],
            'image' => $image,
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
            <button class="primitive" type="button" data-detail="Prompts are reusable interaction templates that users deliberately select, such as a clinical handoff summary or architecture review checklist."><span>USER-CONTROLLED</span><h4>Prompts</h4><p>Reusable workflows and instructions.</p></button>
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
    if (!is_single('model-context-protocol-mcp-introduction')) {
        return;
    }
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            ['@type' => 'Question', 'name' => 'What is Model Context Protocol?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Model Context Protocol is an open standard for connecting AI applications to external tools, data, and reusable prompts through a consistent interface.']],
            ['@type' => 'Question', 'name' => 'Does MCP replace APIs?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'No. MCP commonly sits above existing APIs and data systems, giving AI applications a standard way to discover and use their capabilities.']],
            ['@type' => 'Question', 'name' => 'Is MCP safe for healthcare data?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'MCP can be part of a secure healthcare architecture, but compliance depends on authentication, authorization, consent, data minimization, audit logging, vendor controls, and the underlying systems.']],
        ],
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
    echo "> Practical writing about AI-powered healthcare systems, software architecture, and engineering leadership.\n\n";
    echo "## Articles\n\n";
    foreach (get_posts(['numberposts' => 50, 'post_status' => 'publish']) as $post) {
        echo '- [' . get_the_title($post) . '](' . get_permalink($post) . '): ' . wp_strip_all_tags(get_the_excerpt($post)) . "\n";
    }
    exit;
}
add_action('template_redirect', 'imwasim_llms_txt');
