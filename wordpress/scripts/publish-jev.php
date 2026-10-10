<?php
/** Import the reviewed Jev article into the local WordPress authoring site. */
require dirname(__DIR__, 2) . '/.local-preview/public/blog/wp-load.php';

$slug = 'what-is-jev-typesafe-ai-system-one-model';
$existing = get_page_by_path($slug, OBJECT, 'post');
$excerpt = 'Learn what Jev is, how TypeSafe AI’s System One model works, and how typed decisions, probabilities, and confidence fit into software workflows.';
$post_id = wp_insert_post(wp_slash([
    'ID' => $existing ? $existing->ID : 0,
    'post_title' => 'What Is Jev, TypeSafe AI’s System One Model?',
    'post_name' => $slug,
    'post_excerpt' => $excerpt,
    'post_content' => file_get_contents(dirname(__DIR__) . '/content/' . $slug . '.html'),
    'post_status' => 'publish',
    'post_type' => 'post',
    'post_author' => 1,
    'post_date' => current_time('mysql'),
    'post_date_gmt' => current_time('mysql', true),
]), true);
if (is_wp_error($post_id)) {
    fwrite(STDERR, $post_id->get_error_message() . "\n");
    exit(1);
}
$category = term_exists('Artificial Intelligence', 'category');
if (!$category) {
    $category = wp_insert_term('Artificial Intelligence', 'category', ['slug' => 'artificial-intelligence']);
}
if (!is_wp_error($category)) {
    wp_set_post_categories($post_id, [(int) $category['term_id']]);
}
wp_set_post_tags($post_id, ['Jev', 'TypeSafe AI', 'System One Model', 'AI Architecture', 'AI Automation']);
update_post_meta($post_id, 'rank_math_title', 'What Is Jev? TypeSafe AI’s System One Model | Wasim Arshad');
update_post_meta($post_id, 'rank_math_description', $excerpt);
update_post_meta($post_id, 'rank_math_focus_keyword', 'what is Jev');
echo "Published local article #{$post_id}: {$slug}\n";
