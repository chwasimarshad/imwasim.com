<!doctype html>
<html <?php language_attributes(); ?> data-theme="dark">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#080d18">
    <script>(function(){try{var t=localStorage.getItem('theme')||localStorage.getItem('imwasim-theme');if(t==='light'||t==='dark'){document.documentElement.setAttribute('data-theme',t);localStorage.setItem('theme',t);}}catch(e){}}());</script>
    <link rel="icon" href="<?php echo esc_url('https://imwasim.com/favicon.svg'); ?>" type="image/svg+xml">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>
<div class="reading-progress" aria-hidden="true"></div>
<header class="site-header">
    <nav class="nav container" aria-label="Primary navigation">
        <a class="brand" href="<?php echo esc_url('https://imwasim.com/'); ?>" aria-label="Wasim Arshad home">WA<span>.</span></a>
        <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'menu_class' => 'nav-links', 'menu_id' => 'site-menu', 'fallback_cb' => 'imwasim_menu_fallback']); ?>
        <button class="menu-toggle" type="button" aria-controls="site-menu" aria-expanded="false" aria-label="Open menu">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
        <button class="theme-toggle" type="button" aria-label="Toggle color theme">
            <svg class="icon-sun" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M19.1 4.9l-1.4 1.4M6.3 17.7l-1.4 1.4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <svg class="icon-moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 14.4A8 8 0 0 1 9.6 3.5 8.5 8.5 0 1 0 20.5 14.4Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
        </button>
    </nav>
</header>
