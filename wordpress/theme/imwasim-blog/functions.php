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
        'model-context-protocol-mcp-introduction' => 'Model Context Protocol (MCP): AI Agents Guide | Wasim Arshad',
        'software-architecture-guide' => 'Software Architecture Guide: Scalable Systems | Wasim Arshad',
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

function imwasim_mcp_whiteboard(): string {
    ob_start(); ?>
    <figure class="interactive-figure whiteboard-figure">
        <div class="whiteboard-scroll" role="region" aria-label="Scrollable MCP architecture diagram" tabindex="0">
            <svg class="mcp-whiteboard" viewBox="0 0 1200 720" role="img" aria-labelledby="mcp-whiteboard-title mcp-whiteboard-desc">
                <title id="mcp-whiteboard-title">Model Context Protocol architecture and request process</title>
                <desc id="mcp-whiteboard-desc">A whiteboard diagram showing a user request entering an AI host and MCP client, crossing the MCP protocol boundary to servers that expose tools, resources, and prompts, then returning a structured result through an authorized and observable process.</desc>
                <defs>
                    <pattern id="paper-dots" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.2" fill="#d8d4ca" opacity=".62"/></pattern>
                    <filter id="marker-rough" x="-10%" y="-10%" width="120%" height="120%"><feTurbulence type="fractalNoise" baseFrequency=".018" numOctaves="2" seed="7" result="noise"/><feDisplacementMap in="SourceGraphic" in2="noise" scale="1.4"/></filter>
                    <marker id="arrow-blue" markerWidth="12" markerHeight="12" refX="9" refY="4" orient="auto"><path d="M0,0 L10,4 L0,8" fill="none" stroke="#2563eb" stroke-width="2"/></marker>
                    <marker id="arrow-green" markerWidth="12" markerHeight="12" refX="9" refY="4" orient="auto"><path d="M0,0 L10,4 L0,8" fill="none" stroke="#059669" stroke-width="2"/></marker>
                    <style>
                        .wb-title{font:800 34px "Comic Sans MS","Marker Felt",cursive;fill:#172033}.wb-sub{font:700 16px "Comic Sans MS","Marker Felt",cursive;fill:#536070}.wb-label{font:800 20px "Comic Sans MS","Marker Felt",cursive;fill:#172033}.wb-small{font:700 13px "Comic Sans MS","Marker Felt",cursive;fill:#536070}.wb-code{font:700 12px "JetBrains Mono",monospace;fill:#334155}.wb-box{fill:#fffdf7;stroke-width:4;filter:url(#marker-rough)}.wb-line{fill:none;stroke-width:4;stroke-linecap:round;stroke-linejoin:round;filter:url(#marker-rough)}
                    </style>
                </defs>
                <rect width="1200" height="720" rx="22" fill="#fffdf7"/><rect width="1200" height="720" rx="22" fill="url(#paper-dots)"/>
                <text class="wb-title" x="48" y="58">MODEL CONTEXT PROTOCOL — THE COMPLETE PATH</text>
                <path class="wb-line" d="M48 72 C285 64 510 78 782 69" stroke="#f59e0b" opacity=".75"/>
                <text class="wb-sub" x="49" y="99">A standard contract between an AI application and external capabilities</text>

                <g aria-label="User intent"><circle cx="88" cy="220" r="43" fill="#fef3c7" stroke="#d97706" stroke-width="4" filter="url(#marker-rough)"/><circle cx="88" cy="207" r="11" fill="none" stroke="#92400e" stroke-width="3"/><path d="M66 246c6-27 38-27 44 0" fill="none" stroke="#92400e" stroke-width="3"/><text class="wb-label" x="48" y="290">USER</text><text class="wb-small" x="31" y="314">intent + approval</text></g>
                <path class="wb-line" d="M135 220 C165 210 180 213 207 218" stroke="#2563eb" marker-end="url(#arrow-blue)"/>

                <g aria-label="MCP host"><rect class="wb-box" x="220" y="132" width="270" height="184" rx="22" stroke="#2563eb"/><text class="wb-label" x="248" y="172">AI HOST</text><text class="wb-small" x="248" y="198">Chat • IDE • Agent app</text><rect x="248" y="218" width="110" height="64" rx="12" fill="#dbeafe" stroke="#2563eb" stroke-width="2"/><text class="wb-small" x="268" y="246">LLM / agent</text><text class="wb-small" x="278" y="267">loop</text><rect x="373" y="218" width="92" height="64" rx="12" fill="#ede9fe" stroke="#7c3aed" stroke-width="2"/><text class="wb-small" x="390" y="246">policy +</text><text class="wb-small" x="391" y="267">consent</text></g>

                <path class="wb-line" d="M490 220 C515 209 530 213 552 218" stroke="#2563eb" marker-end="url(#arrow-blue)"/>
                <g aria-label="MCP client"><rect class="wb-box" x="566" y="151" width="184" height="140" rx="22" stroke="#7c3aed"/><text class="wb-label" x="592" y="190">MCP CLIENT</text><text class="wb-small" x="592" y="218">1 connection</text><text class="wb-small" x="592" y="240">per server</text><text class="wb-code" x="592" y="267">JSON-RPC 2.0</text></g>

                <path class="wb-line" d="M752 220 C785 203 805 208 837 218" stroke="#059669" marker-end="url(#arrow-green)"/>
                <text class="wb-small" x="760" y="185">stdio or</text><text class="wb-small" x="755" y="201">Streamable HTTP</text>

                <g aria-label="MCP servers"><rect class="wb-box" x="852" y="116" width="300" height="238" rx="22" stroke="#059669"/><text class="wb-label" x="880" y="156">MCP SERVER(S)</text><text class="wb-small" x="880" y="180">Adapters over real systems</text><rect x="880" y="201" width="76" height="55" rx="11" fill="#dcfce7" stroke="#059669" stroke-width="2"/><text class="wb-small" x="899" y="224">TOOLS</text><text class="wb-code" x="893" y="244">tools/call</text><rect x="966" y="201" width="82" height="55" rx="11" fill="#dbeafe" stroke="#2563eb" stroke-width="2"/><text class="wb-small" x="975" y="224">RESOURCES</text><text class="wb-code" x="978" y="244">read</text><rect x="1058" y="201" width="67" height="55" rx="11" fill="#f3e8ff" stroke="#7c3aed" stroke-width="2"/><text class="wb-small" x="1066" y="224">PROMPTS</text><text class="wb-code" x="1074" y="244">get</text><path class="wb-line" d="M887 295 C948 278 1054 279 1114 297" stroke="#059669"/><text class="wb-small" x="884" y="326">APIs • files • databases • SaaS</text></g>

                <path class="wb-line" d="M1090 367 C1007 401 883 406 780 398 C664 389 541 391 421 401 C299 411 192 408 112 374" stroke="#059669" stroke-dasharray="10 11" marker-end="url(#arrow-green)"/>
                <text class="wb-small" x="493" y="380">structured content blocks / errors / metadata</text>

                <text class="wb-label" x="48" y="456">THE REQUEST LIFECYCLE</text>
                <path class="wb-line" d="M48 466 C218 458 371 471 528 464" stroke="#ef4444" opacity=".72"/>
                <g transform="translate(48 496)">
                    <g transform="translate(0 0)"><circle cx="30" cy="30" r="27" fill="#dbeafe" stroke="#2563eb" stroke-width="3"/><text class="wb-label" x="23" y="38">1</text><text class="wb-small" x="0" y="80">CONNECT</text><text class="wb-code" x="0" y="101">negotiate</text></g>
                    <path class="wb-line" d="M70 30H142" stroke="#2563eb" marker-end="url(#arrow-blue)"/>
                    <g transform="translate(158 0)"><circle cx="30" cy="30" r="27" fill="#ede9fe" stroke="#7c3aed" stroke-width="3"/><text class="wb-label" x="23" y="38">2</text><text class="wb-small" x="0" y="80">DISCOVER</text><text class="wb-code" x="0" y="101">*/list</text></g>
                    <path class="wb-line" d="M229 30H301" stroke="#7c3aed" marker-end="url(#arrow-blue)"/>
                    <g transform="translate(317 0)"><circle cx="30" cy="30" r="27" fill="#fef3c7" stroke="#d97706" stroke-width="3"/><text class="wb-label" x="23" y="38">3</text><text class="wb-small" x="0" y="80">SELECT</text><text class="wb-code" x="0" y="101">schema match</text></g>
                    <path class="wb-line" d="M388 30H460" stroke="#d97706" marker-end="url(#arrow-blue)"/>
                    <g transform="translate(476 0)"><circle cx="30" cy="30" r="27" fill="#fee2e2" stroke="#ef4444" stroke-width="3"/><text class="wb-label" x="23" y="38">4</text><text class="wb-small" x="0" y="80">AUTHORIZE</text><text class="wb-code" x="0" y="101">policy + user</text></g>
                    <path class="wb-line" d="M547 30H619" stroke="#ef4444" marker-end="url(#arrow-green)"/>
                    <g transform="translate(635 0)"><circle cx="30" cy="30" r="27" fill="#dcfce7" stroke="#059669" stroke-width="3"/><text class="wb-label" x="23" y="38">5</text><text class="wb-small" x="0" y="80">EXECUTE</text><text class="wb-code" x="0" y="101">call/read/get</text></g>
                    <path class="wb-line" d="M706 30H778" stroke="#059669" marker-end="url(#arrow-green)"/>
                    <g transform="translate(794 0)"><circle cx="30" cy="30" r="27" fill="#cffafe" stroke="#0891b2" stroke-width="3"/><text class="wb-label" x="23" y="38">6</text><text class="wb-small" x="0" y="80">RETURN</text><text class="wb-code" x="0" y="101">typed result</text></g>
                    <path class="wb-line" d="M865 30H937" stroke="#0891b2" marker-end="url(#arrow-blue)"/>
                    <g transform="translate(953 0)"><circle cx="30" cy="30" r="27" fill="#e0e7ff" stroke="#4f46e5" stroke-width="3"/><text class="wb-label" x="23" y="38">7</text><text class="wb-small" x="0" y="80">OBSERVE</text><text class="wb-code" x="0" y="101">trace + audit</text></g>
                </g>
                <rect x="48" y="635" width="1104" height="50" rx="12" fill="#f8fafc" stroke="#94a3b8" stroke-width="2" stroke-dasharray="8 7"/><text class="wb-small" x="72" y="666">CONTROL BOUNDARY → The host owns model context and consent. The server validates every request and protects the system behind it.</text>
            </svg>
        </div>
        <figcaption>Whiteboard map: intent enters the host, capabilities cross the MCP boundary, and a governed result returns to the agent loop. Swipe horizontally on smaller screens.</figcaption>
    </figure>
    <?php return (string) ob_get_clean();
}
add_shortcode('mcp_whiteboard', 'imwasim_mcp_whiteboard');

function imwasim_software_architecture_whiteboard(): string {
    ob_start(); ?>
    <figure class="interactive-figure whiteboard-figure">
        <div class="whiteboard-scroll" role="region" aria-label="Scrollable software architecture decision map" tabindex="0">
            <svg class="architecture-whiteboard" viewBox="0 0 1200 720" role="img" aria-labelledby="architecture-board-title architecture-board-desc">
                <title id="architecture-board-title">Software architecture decision map</title>
                <desc id="architecture-board-desc">A whiteboard diagram showing how business goals, users, constraints, and risks become architecture decisions about boundaries, data, communication, and deployment, which are validated against quality attributes and production feedback.</desc>
                <defs>
                    <pattern id="architecture-paper-dots" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.2" fill="#d8d4ca" opacity=".62"/></pattern>
                    <filter id="architecture-marker-rough" x="-10%" y="-10%" width="120%" height="120%"><feTurbulence type="fractalNoise" baseFrequency=".018" numOctaves="2" seed="11" result="noise"/><feDisplacementMap in="SourceGraphic" in2="noise" scale="1.4"/></filter>
                    <marker id="architecture-arrow-blue" markerWidth="12" markerHeight="12" refX="9" refY="4" orient="auto"><path d="M0,0 L10,4 L0,8" fill="none" stroke="#2563eb" stroke-width="2"/></marker>
                    <marker id="architecture-arrow-green" markerWidth="12" markerHeight="12" refX="9" refY="4" orient="auto"><path d="M0,0 L10,4 L0,8" fill="none" stroke="#059669" stroke-width="2"/></marker>
                    <style>
                        .ab-title{font:800 34px "Comic Sans MS","Marker Felt",cursive;fill:#172033}.ab-sub{font:700 16px "Comic Sans MS","Marker Felt",cursive;fill:#536070}.ab-label{font:800 20px "Comic Sans MS","Marker Felt",cursive;fill:#172033}.ab-small{font:700 13px "Comic Sans MS","Marker Felt",cursive;fill:#536070}.ab-code{font:700 12px "JetBrains Mono",monospace;fill:#334155}.ab-box{fill:#fffdf7;stroke-width:4;filter:url(#architecture-marker-rough)}.ab-line{fill:none;stroke-width:4;stroke-linecap:round;stroke-linejoin:round;filter:url(#architecture-marker-rough)}
                    </style>
                </defs>
                <rect width="1200" height="720" rx="22" fill="#fffdf7"/><rect width="1200" height="720" rx="22" fill="url(#architecture-paper-dots)"/>
                <text class="ab-title" x="48" y="58">SOFTWARE ARCHITECTURE — FROM NEED TO OPERATING SYSTEM</text>
                <path class="ab-line" d="M48 72 C310 63 563 79 875 69" stroke="#f59e0b" opacity=".75"/>
                <text class="ab-sub" x="49" y="99">Architecture is the set of consequential decisions that shape change, scale, and risk.</text>

                <g aria-label="Architecture inputs"><rect class="ab-box" x="48" y="140" width="250" height="250" rx="22" stroke="#d97706"/><text class="ab-label" x="76" y="181">1 / DRIVERS</text><text class="ab-small" x="76" y="216">BUSINESS</text><text class="ab-code" x="76" y="238">outcomes • cost • time</text><text class="ab-small" x="76" y="276">USERS + WORKLOAD</text><text class="ab-code" x="76" y="298">traffic • latency • data</text><text class="ab-small" x="76" y="336">CONSTRAINTS + RISKS</text><text class="ab-code" x="76" y="358">skills • law • legacy</text></g>
                <path class="ab-line" d="M300 260 C340 244 365 247 399 257" stroke="#2563eb" marker-end="url(#architecture-arrow-blue)"/>

                <g aria-label="Architecture decisions"><rect class="ab-box" x="414" y="126" width="366" height="278" rx="24" stroke="#2563eb"/><text class="ab-label" x="444" y="167">2 / STRUCTURAL DECISIONS</text><rect x="444" y="194" width="140" height="70" rx="13" fill="#dbeafe" stroke="#2563eb" stroke-width="2"/><text class="ab-small" x="469" y="224">BOUNDARIES</text><text class="ab-code" x="465" y="246">modules • services</text><rect x="610" y="194" width="140" height="70" rx="13" fill="#dcfce7" stroke="#059669" stroke-width="2"/><text class="ab-small" x="648" y="224">DATA</text><text class="ab-code" x="631" y="246">ownership • model</text><rect x="444" y="288" width="140" height="70" rx="13" fill="#f3e8ff" stroke="#7c3aed" stroke-width="2"/><text class="ab-small" x="466" y="318">INTERACTION</text><text class="ab-code" x="458" y="340">sync • async • API</text><rect x="610" y="288" width="140" height="70" rx="13" fill="#fee2e2" stroke="#ef4444" stroke-width="2"/><text class="ab-small" x="632" y="318">DEPLOYMENT</text><text class="ab-code" x="634" y="340">runtime • topology</text></g>
                <path class="ab-line" d="M782 260 C818 244 842 247 876 257" stroke="#059669" marker-end="url(#architecture-arrow-green)"/>

                <g aria-label="Quality attributes"><rect class="ab-box" x="892" y="140" width="260" height="250" rx="22" stroke="#059669"/><text class="ab-label" x="920" y="181">3 / FITNESS</text><text class="ab-small" x="920" y="216">RELIABILITY</text><text class="ab-code" x="1022" y="216">SLO • recovery</text><text class="ab-small" x="920" y="251">SECURITY</text><text class="ab-code" x="1010" y="251">identity • data</text><text class="ab-small" x="920" y="286">PERFORMANCE</text><text class="ab-code" x="1022" y="286">p95 • capacity</text><text class="ab-small" x="920" y="321">CHANGEABILITY</text><text class="ab-code" x="1034" y="321">lead time</text><text class="ab-small" x="920" y="356">COST</text><text class="ab-code" x="975" y="356">build + operate</text></g>

                <path class="ab-line" d="M1020 409 C1025 444 995 455 958 461 C769 491 410 480 222 456 C173 450 143 432 150 405" stroke="#ef4444" stroke-dasharray="10 11" marker-end="url(#architecture-arrow-blue)"/>
                <text class="ab-small" x="462" y="448">evidence changes the design</text>

                <text class="ab-label" x="48" y="505">THE ARCHITECTURE LOOP</text>
                <path class="ab-line" d="M48 515 C231 507 390 520 563 513" stroke="#ef4444" opacity=".72"/>
                <g transform="translate(48 548)">
                    <g><circle cx="30" cy="30" r="27" fill="#fef3c7" stroke="#d97706" stroke-width="3"/><text class="ab-label" x="23" y="38">1</text><text class="ab-small" x="0" y="80">UNDERSTAND</text><text class="ab-code" x="0" y="101">context</text></g><path class="ab-line" d="M72 30H146" stroke="#d97706" marker-end="url(#architecture-arrow-blue)"/>
                    <g transform="translate(162)"><circle cx="30" cy="30" r="27" fill="#dbeafe" stroke="#2563eb" stroke-width="3"/><text class="ab-label" x="23" y="38">2</text><text class="ab-small" x="0" y="80">PRIORITIZE</text><text class="ab-code" x="0" y="101">quality goals</text></g><path class="ab-line" d="M234 30H308" stroke="#2563eb" marker-end="url(#architecture-arrow-blue)"/>
                    <g transform="translate(324)"><circle cx="30" cy="30" r="27" fill="#ede9fe" stroke="#7c3aed" stroke-width="3"/><text class="ab-label" x="23" y="38">3</text><text class="ab-small" x="5" y="80">DESIGN</text><text class="ab-code" x="0" y="101">options</text></g><path class="ab-line" d="M396 30H470" stroke="#7c3aed" marker-end="url(#architecture-arrow-green)"/>
                    <g transform="translate(486)"><circle cx="30" cy="30" r="27" fill="#dcfce7" stroke="#059669" stroke-width="3"/><text class="ab-label" x="23" y="38">4</text><text class="ab-small" x="0" y="80">VALIDATE</text><text class="ab-code" x="0" y="101">prototype • test</text></g><path class="ab-line" d="M558 30H632" stroke="#059669" marker-end="url(#architecture-arrow-green)"/>
                    <g transform="translate(648)"><circle cx="30" cy="30" r="27" fill="#cffafe" stroke="#0891b2" stroke-width="3"/><text class="ab-label" x="23" y="38">5</text><text class="ab-small" x="0" y="80">DOCUMENT</text><text class="ab-code" x="0" y="101">ADR + diagrams</text></g><path class="ab-line" d="M720 30H794" stroke="#0891b2" marker-end="url(#architecture-arrow-blue)"/>
                    <g transform="translate(810)"><circle cx="30" cy="30" r="27" fill="#fee2e2" stroke="#ef4444" stroke-width="3"/><text class="ab-label" x="23" y="38">6</text><text class="ab-small" x="0" y="80">OBSERVE</text><text class="ab-code" x="0" y="101">measure • evolve</text></g>
                </g>
                <rect x="48" y="672" width="1104" height="30" rx="10" fill="#f8fafc" stroke="#94a3b8" stroke-width="2" stroke-dasharray="8 7"/><text class="ab-code" x="72" y="692">GOOD ARCHITECTURE = explicit trade-offs + enforceable boundaries + production evidence</text>
            </svg>
        </div>
        <figcaption>Whiteboard map: architecture turns business drivers into structural decisions, tests them against measurable quality attributes, and evolves through production feedback.</figcaption>
    </figure>
    <?php return (string) ob_get_clean();
}
add_shortcode('software_architecture_whiteboard', 'imwasim_software_architecture_whiteboard');

function imwasim_architecture_tradeoff_map(): string {
    ob_start(); ?>
    <figure class="interactive-figure whiteboard-figure">
        <div class="whiteboard-scroll" role="region" aria-label="Scrollable software architecture pattern trade-off map" tabindex="0">
            <svg class="architecture-whiteboard architecture-pattern-map" viewBox="0 0 1200 590" role="img" aria-labelledby="pattern-map-title pattern-map-desc">
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
