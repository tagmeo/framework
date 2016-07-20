<?php

namespace Tagmeo\Theme;

class Cleanup
{
    public function __construct()
    {
        add_action('init', function () {
            remove_action('wp_head', 'feed_links_extra', 3);

            add_action('wp_head', 'ob_start', 1, 0);

            add_action('wp_head', function () {
                $pattern = '/.*'.preg_quote(
                    esc_url(get_feed_link('comments_'.get_default_feed())),
                    '/'
                ).'.*[\r\n]+/';

                echo preg_replace($pattern, '', ob_get_clean());
            }, 3, 0);

            remove_action('wp_head', 'rsd_link');
            remove_action('wp_head', 'wlwmanifest_link');
            remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
            remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
            remove_action('wp_head', 'wp_generator');
            remove_action('wp_head', 'feed_links', 2);
            remove_action('wp_head', 'index_rel_link');
            remove_action('wp_head', 'start_post_rel_link', 10, 0);
            remove_action('wp_head', 'parent_post_rel_link', 10, 0);
            remove_action('wp_head', 'wp_shortlink_wp_head', 10);
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
            remove_action('wp_head', 'wp_oembed_add_host_js');
            remove_action('wp_head', 'rest_output_link_wp_head', 10);
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('admin_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

            add_filter('use_default_gallery_style', function () {
                return false;
            });

            global $wp_widget_factory;

            if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
                remove_action('wp_head', [$wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style']);
            }

            if (!class_exists('WPSEO_Frontend')) {
                remove_action('wp_head', 'rel_canonical');

                add_action('wp_head', function () {
                    global $wp_the_query;

                    if (!is_singular()) {
                        return;
                    }

                    if (!$id = $wp_the_query->get_queried_object_id()) {
                        return;
                    }

                    $link = get_permalink($id);

                    echo '<link rel="canonical" href="'.$link.'">'."\n";
                });
            }
        });

        add_filter('the_generator', function () {
            return false;
        });

        add_filter('wp_default_scripts', function (&$scripts) {
            if (!is_admin()) {
                $scripts->remove('jquery');

                wp_deregister_style('dashicons');
                wp_deregister_style('editor-buttons');
            }
        });

        add_filter('style_loader_tag', function ($html, $handle, $href, $media) {
            if (!is_null($media)) {
                $media = ' media="'.$media.'" ';
            }

            return '<link rel="stylesheet" href="'.$href.'"'.$media.'id="'.$handle.'">'."\n";
        }, 10, 4);

        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            return '<script src="'.$src.'"></script>'."\n";
        }, 10, 3);

        add_filter('script_loader_src', function ($src) {
            return $src ? esc_url(remove_query_arg('ver', $src)): false;
        }, 15, 1);

        add_filter('style_loader_src', function ($src) {
            return $src ? esc_url(remove_query_arg('ver', $src)): false;
        }, 15, 1);

        add_filter('xmlrpc_methods', function ($methods) {
            unset($methods['pingback.ping']);

            return $methods;
        }, 10, 1);

        add_filter('wp_headers', function ($headers) {
            if (isset($headers['X-Pingback'])) {
                unset($headers['X-Pingback']);
            }

            return $headers;
        }, 10, 1);

        add_filter('rewrite_rules_array', function ($rules) {
            foreach ($rules as $rule => $rewrite) {
                if (preg_match('/trackback\/\?\$$/i', $rule)) {
                    unset($rules[$rule]);
                }
            }
        }, 10, 1);

        add_filter('bloginfo_url', function ($output, $show) {
            if ($show === 'pingback_url') {
                $output = '';
            }

            return $output;
        }, 10, 2);

        add_action('xmlrpc_call', function ($action) {
            if ($action === 'pingback.ping') {
                wp_die('Pingbacks are not supported', 'Method Not Allowed', [
                    'response' => 400
                ]);
            }
        }, 10, 1);

        add_filter('body_class', function ($classes) {
            if (is_single() || is_page() && !is_front_page()) {
                if (!in_array(basename(get_permalink()), $classes)) {
                    $classes[] = basename(get_permalink());
                }
            }

            $homeClass = 'page-id-'.get_option('page_on_front');

            $removeClasses = [
                'page-template-default',
                $homeClass
            ];

            $classes = array_diff($classes, $removeClasses);

            return $classes;
        }, 10, 1);

        add_filter('embed_oembed_html', function ($cache) {
            return '<div class="entry-content-asset">'.$cache.'</div>';
        }, 10, 1);

        add_action('admin_init', function () {
            remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
            remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
            remove_meta_box('dashboard_primary', 'dashboard', 'normal');
            remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
        }, 10, 0);

        add_filter('get_avatar', function ($input) {
            return str_replace(' />', '>', $input);
        }, 10, 1);

        add_filter('comment_id_fields', function ($input) {
            return str_replace(' />', '>', $input);
        }, 10, 1);

        add_filter('post_thumbnail_html', function ($input) {
            return str_replace(' />', '>', $input);
        }, 10, 1);

        add_filter('get_bloginfo_rss', function ($bloginfo) {
            $defaultTagline = 'Just another WordPress site';

            return ($bloginfo === $defaultTagline) ? '' : $bloginfo;
        }, 10, 1);
    }
}
