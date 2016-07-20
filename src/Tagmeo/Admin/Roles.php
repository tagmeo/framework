<?php

namespace Tagmeo\Admin;

class Roles
{
    public function __construct()
    {
        add_action('admin_init', function () {
            if (!is_a(get_role('developer'), 'WP_Role')) {
                global $wp_roles;

                $allRoles = $wp_roles->roles;

                $allCapabilities = [];

                foreach ($allRoles as $key => $role) {
                    $allCapabilities = array_merge($allCapabilities, $role['capabilities']);
                }

                foreach ($allCapabilities as $key => &$capability) {
                    $capability = 1;
                }

                $allCapabilities['develop'] = 1;

                add_role('developer', __('Developer'), $allCapabilities);
            }
        });

        add_action('admin_init', function () {
            if (!is_a(get_role('privileged_editor'), 'WP_Role')) {
                $editor = get_role('editor');

                $editorCapabilities = $editor->capabilities;

                $privilegedCapabilities = [
                    'active_plugins' => true,
                    'add_users' => true,
                    'create_users' => true,
                    'edit_theme_options' => true,
                    'edit_users' => true,
                    'export' => true,
                    'install_plugins' => true,
                    'list_users' => true,
                    'manage_options' => true,
                    'update_core' => true,
                    'update_plugins' => true
                ];

                $finalCapabilities = array_merge($editorCapabilities, $privilegedCapabilities);

                add_role('privileged_editor', __('Privileged Editor'), $finalCapabilities);
            }
        });
    }
}
