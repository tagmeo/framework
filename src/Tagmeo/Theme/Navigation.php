<?php

namespace Tagmeo\Theme;

class Navigation extends \Walker_Nav_Menu
{
    // @codingStandardsIgnoreStart
    public function start_lvl(&$output, $depth = 0, $args = [])
    {
        $output .= '<ul class=" dropdown-menu" role="menu">';
    }

    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {
        if (strcasecmp($item->attr_title, 'divider') == 0 && $depth === 1) {
            $output .= '<li role="presentation" class="divider">';
        } elseif (strcasecmp($item->title, 'divider') == 0 && $depth === 1) {
            $output .= '<li role="presentation" class="divider">';
        } elseif (strcasecmp($item->attr_title, 'dropdown-header') == 0 && $depth === 1) {
            $output .= '<li role="presentation" class="dropdown-header">'.esc_attr($item->title);
        } elseif (strcasecmp($item->attr_title, 'disabled') == 0) {
            $output .= '<li role="presentation" class="disabled"><a href="#">'.esc_attr($item->title).'</a>';
        } else {
            $classNames = $value = '';

            $classes = empty($item->classes) ? [] : (array) $item->classes;

            $classes[] = 'menu-item-'.$item->ID;

            $classNames = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));

            if ($args->has_children) {
                $classNames .= ' dropdown';
            }

            if (in_array('current-menu-item', $classes)) {
                $classNames .= ' active';
            }

            $classNames = $classNames ? ' class="'.esc_attr($classNames).'"' : '';

            $id = apply_filters('nav_menu_item_id', 'menu-item-'.$item->ID, $item, $args);
            $id = $id ? ' id="'.esc_attr($id).'"' : '';

            $output .= '<li'.$id.$value.$classNames .'>';

            $atts = [];

            $atts['title'] = ! empty($item->title) ? $item->title : '';
            $atts['target'] = ! empty($item->target) ? $item->target : '';
            $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';

            if ($args->has_children && $depth === 0) {
                $atts['href'] = '#';
                $atts['data-toggle'] = 'dropdown';
                $atts['class'] = 'dropdown-toggle';
                $atts['aria-haspopup'] = 'true';
            } else {
                $atts['href'] = ! empty($item->url) ? $item->url : '';
            }

            $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

            $attributes = '';

            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                    $attributes .= ' '.$attr.'="'.$value.'"';
                }
            }

            $itemOutput = $args->before;

            if (!empty($item->attr_title)) {
                $itemOutput .= '<a'.$attributes .'><span class="glyphicon '.esc_attr($item->attr_title).'"></span>&nbsp;';
            } else {
                $itemOutput .= '<a'.$attributes .'>';
            }
            $itemOutput .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
            $itemOutput .= ($args->has_children && 0 === $depth) ? ' <span class="caret"></span></a>' : '</a>';
            $itemOutput .= $args->after;

            $output .= apply_filters('walker_nav_menu_start_el', $itemOutput, $item, $depth, $args);
        }
    }

    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
    {
        if (!$element) {
            return;
        }

        $id_field = $this->db_fields['id'];

        if (is_object($args[0])) {
            $args[0]->has_children = ! empty($children_elements[ $element->$id_field ]);
        }

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    public static function fallback($args)
    {
        if (current_user_can('manage_options')) {
            extract($args);

            $fbOutput = null;

            if ($container) {
                $fbOutput = '<'.$container;

                if ($container_id) {
                    $fbOutput .= ' id="'.$container_id.'"';
                }

                if ($container_class) {
                    $fbOutput .= ' class="'.$container_class.'"';
                }

                $fbOutput .= '>';
            }

            $fbOutput .= '<ul';

            if ($menu_id) {
                $fbOutput .= ' id="'.$menu_id.'"';
            }

            if ($menu_class) {
                $fbOutput .= ' class="'.$menu_class.'"';
            }

            $fbOutput .= '>';
            $fbOutput .= '<li><a href="'.admin_url('nav-menus.php').'">Add Menu</a></li>';
            $fbOutput .= '</ul>';

            if ($container) {
                $fbOutput .= '</'.$container.'>';
            }

            echo $fbOutput;
        }
    }
    // @codingStandardsIgnoreEnd
}
