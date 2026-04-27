<?php

if (! defined('ABSPATH')) {
    exit;
}

class Engintenia_Platform
{
    private static $instance = null;

    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('init', [$this, 'load_textdomain']);
        add_action('init', [$this, 'register_roles']);
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_post_engintenia_review_subscription', [$this, 'handle_subscription_review']);
        add_action('template_redirect', [$this, 'handle_forms']);

        add_shortcode('eng_register_form', [$this, 'render_register_form']);
        add_shortcode('eng_projects_list', [$this, 'render_projects_list']);
        add_shortcode('eng_project_submit', [$this, 'render_project_submit']);
        add_shortcode('eng_subscription_form', [$this, 'render_subscription_form']);
        add_shortcode('eng_company_dashboard', [$this, 'render_company_dashboard']);
        add_shortcode('eng_contractor_dashboard', [$this, 'render_contractor_dashboard']);
        add_shortcode('eng_contractors_list', [$this, 'render_contractors_list']);
        add_shortcode('eng_notifications', [$this, 'render_notifications']);
        add_shortcode('eng_language_switcher', [$this, 'render_language_switcher']);

        register_activation_hook(ENGINTENIA_PLATFORM_PATH . 'engintenia-platform.php', [$this, 'activate']);
        register_deactivation_hook(ENGINTENIA_PLATFORM_PATH . 'engintenia-platform.php', [$this, 'deactivate']);
    }

    public function load_textdomain()
    {
        load_plugin_textdomain('engintenia-platform', false, dirname(plugin_basename(ENGINTENIA_PLATFORM_PATH . 'engintenia-platform.php')) . '/languages');
    }

    public function activate()
    {
        $this->register_roles();
        $this->register_post_types();
        $this->register_taxonomies();
        flush_rewrite_rules();
    }

    public function deactivate()
    {
        flush_rewrite_rules();
    }

    public function register_roles()
    {
        add_role('eng_client', __('Client', 'engintenia-platform'), [
            'read' => true,
            'upload_files' => true,
        ]);

        add_role('eng_contractor', __('Contractor', 'engintenia-platform'), [
            'read' => true,
            'upload_files' => true,
        ]);
    }

    public function register_post_types()
    {
        register_post_type('eng_project', [
            'labels' => [
                'name' => __('Projects', 'engintenia-platform'),
                'singular_name' => __('Project', 'engintenia-platform'),
            ],
            'public' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'author', 'thumbnail'],
            'rewrite' => ['slug' => 'projects'],
            'has_archive' => true,
        ]);

        register_post_type('eng_proposal', [
            'labels' => [
                'name' => __('Proposals', 'engintenia-platform'),
                'singular_name' => __('Proposal', 'engintenia-platform'),
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => ['title', 'editor', 'author'],
        ]);

        register_post_type('eng_subscription', [
            'labels' => [
                'name' => __('Subscriptions', 'engintenia-platform'),
                'singular_name' => __('Subscription', 'engintenia-platform'),
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => ['title', 'author'],
        ]);

        register_post_type('eng_message', [
            'labels' => [
                'name' => __('Messages', 'engintenia-platform'),
                'singular_name' => __('Message', 'engintenia-platform'),
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => ['editor', 'author'],
        ]);

        register_post_type('eng_review', [
            'labels' => [
                'name' => __('Reviews', 'engintenia-platform'),
                'singular_name' => __('Review', 'engintenia-platform'),
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => ['editor', 'author'],
        ]);
    }

    public function register_taxonomies()
    {
        register_taxonomy('eng_category', 'eng_project', [
            'labels' => [
                'name' => __('Categories', 'engintenia-platform'),
                'singular_name' => __('Category', 'engintenia-platform'),
            ],
            'public' => true,
            'show_in_rest' => true,
            'hierarchical' => true,
        ]);
    }

    public function enqueue_assets()
    {
        wp_enqueue_style('engintenia-platform', ENGINTENIA_PLATFORM_URL . 'assets/css/platform.css', [], ENGINTENIA_PLATFORM_VERSION);
    }

    private function is_subscribed($user_id)
    {
        return 'approved' === get_user_meta($user_id, 'eng_subscription_status', true);
    }

    private function can_view_client_details($project_id)
    {
        if (! is_user_logged_in()) {
            return false;
        }

        $user_id = get_current_user_id();
        if (current_user_can('manage_options') || (int) get_post_field('post_author', $project_id) === $user_id) {
            return true;
        }

        return $this->is_subscribed($user_id);
    }

    public function handle_forms()
    {
        if (! isset($_POST['eng_action']) || ! is_user_logged_in()) {
            return;
        }

        $action = sanitize_text_field(wp_unslash($_POST['eng_action']));

        if ('submit_project' === $action) {
            $this->handle_project_submission();
        } elseif ('submit_proposal' === $action) {
            $this->handle_proposal_submission();
        } elseif ('submit_subscription' === $action) {
            $this->handle_subscription_submission();
        } elseif ('submit_message' === $action) {
            $this->handle_message_submission();
        }
    }

    private function redirect_back($query = [])
    {
        $url = wp_get_referer() ? wp_get_referer() : home_url('/');
        wp_safe_redirect(add_query_arg($query, $url));
        exit;
    }

    private function handle_project_submission()
    {
        check_admin_referer('eng_submit_project');

        if (! in_array('eng_client', wp_get_current_user()->roles, true)) {
            $this->redirect_back(['eng_error' => 'not_client']);
        }

        $project_id = wp_insert_post([
            'post_type' => 'eng_project',
            'post_status' => 'publish',
            'post_title' => sanitize_text_field(wp_unslash($_POST['project_title'] ?? '')),
            'post_content' => sanitize_textarea_field(wp_unslash($_POST['project_description'] ?? '')),
            'post_author' => get_current_user_id(),
        ]);

        if ($project_id && ! is_wp_error($project_id)) {
            update_post_meta($project_id, 'eng_budget', sanitize_text_field(wp_unslash($_POST['project_budget'] ?? '')));
            update_post_meta($project_id, 'eng_duration', sanitize_text_field(wp_unslash($_POST['project_duration'] ?? '')));
            update_post_meta($project_id, 'eng_country', sanitize_text_field(wp_unslash($_POST['project_country'] ?? '')));
            update_post_meta($project_id, 'eng_client_contact', sanitize_text_field(wp_unslash($_POST['project_contact'] ?? '')));
            wp_set_object_terms($project_id, array_map('intval', $_POST['project_category'] ?? []), 'eng_category');
            $this->redirect_back(['eng_success' => 'project_created']);
        }

        $this->redirect_back(['eng_error' => 'project_failed']);
    }

    private function handle_proposal_submission()
    {
        check_admin_referer('eng_submit_proposal');
        $project_id = (int) ($_POST['project_id'] ?? 0);

        if (! $this->is_subscribed(get_current_user_id())) {
            $this->redirect_back(['eng_error' => 'subscription_required']);
        }

        $proposal_id = wp_insert_post([
            'post_type' => 'eng_proposal',
            'post_status' => 'publish',
            'post_title' => sprintf(__('Proposal for #%d', 'engintenia-platform'), $project_id),
            'post_content' => sanitize_textarea_field(wp_unslash($_POST['proposal_message'] ?? '')),
            'post_author' => get_current_user_id(),
        ]);

        if ($proposal_id && ! is_wp_error($proposal_id)) {
            update_post_meta($proposal_id, 'eng_project_id', $project_id);
            update_post_meta($proposal_id, 'eng_quote', sanitize_text_field(wp_unslash($_POST['proposal_quote'] ?? '')));
            update_post_meta($proposal_id, 'eng_status', 'pending');
            $project_owner = (int) get_post_field('post_author', $project_id);
            $this->create_notification($project_owner, __('New proposal received for your project.', 'engintenia-platform'));
            $this->redirect_back(['eng_success' => 'proposal_sent']);
        }

        $this->redirect_back(['eng_error' => 'proposal_failed']);
    }

    private function handle_subscription_submission()
    {
        check_admin_referer('eng_submit_subscription');

        $subscription_id = wp_insert_post([
            'post_type' => 'eng_subscription',
            'post_status' => 'publish',
            'post_title' => sprintf(__('Subscription request: %s', 'engintenia-platform'), wp_get_current_user()->display_name),
            'post_author' => get_current_user_id(),
        ]);

        if (! $subscription_id || is_wp_error($subscription_id)) {
            $this->redirect_back(['eng_error' => 'subscription_failed']);
        }

        update_post_meta($subscription_id, 'eng_receipt_name', sanitize_text_field(wp_unslash($_POST['receipt_name'] ?? '')));
        update_post_meta($subscription_id, 'eng_receipt_amount', sanitize_text_field(wp_unslash($_POST['receipt_amount'] ?? '20')));
        update_post_meta($subscription_id, 'eng_receipt_date', sanitize_text_field(wp_unslash($_POST['receipt_date'] ?? '')));
        update_post_meta($subscription_id, 'eng_subscription_status', 'pending');
        update_user_meta(get_current_user_id(), 'eng_subscription_status', 'pending');

        if (! empty($_FILES['receipt_image']['name'])) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $attachment_id = media_handle_upload('receipt_image', $subscription_id);
            if (! is_wp_error($attachment_id)) {
                update_post_meta($subscription_id, 'eng_receipt_image', $attachment_id);
            }
        }

        $admins = get_users(['role' => 'administrator']);
        foreach ($admins as $admin) {
            $this->create_notification($admin->ID, __('New subscription receipt submitted.', 'engintenia-platform'));
        }

        $this->redirect_back(['eng_success' => 'subscription_submitted']);
    }

    private function handle_message_submission()
    {
        check_admin_referer('eng_send_message');
        if (! $this->is_subscribed(get_current_user_id())) {
            $this->redirect_back(['eng_error' => 'subscription_required']);
        }

        $receiver_id = (int) ($_POST['receiver_id'] ?? 0);
        $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

        $message_id = wp_insert_post([
            'post_type' => 'eng_message',
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'post_content' => $message,
        ]);

        if ($message_id && ! is_wp_error($message_id)) {
            update_post_meta($message_id, 'eng_receiver_id', $receiver_id);
            $this->create_notification($receiver_id, __('You have a new message on Engintenia.', 'engintenia-platform'));
        }

        $this->redirect_back(['eng_success' => 'message_sent']);
    }

    public function register_admin_menu()
    {
        add_menu_page(
            __('Engintenia Subscriptions', 'engintenia-platform'),
            __('Engintenia', 'engintenia-platform'),
            'manage_options',
            'engintenia-subscriptions',
            [$this, 'render_admin_subscriptions'],
            'dashicons-businessman'
        );
    }

    public function render_admin_subscriptions()
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        $subscriptions = get_posts([
            'post_type' => 'eng_subscription',
            'numberposts' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        echo '<div class="wrap"><h1>' . esc_html__('Subscription Approvals', 'engintenia-platform') . '</h1>';
        echo '<table class="widefat"><thead><tr><th>' . esc_html__('User', 'engintenia-platform') . '</th><th>' . esc_html__('Amount', 'engintenia-platform') . '</th><th>' . esc_html__('Date', 'engintenia-platform') . '</th><th>' . esc_html__('Receipt', 'engintenia-platform') . '</th><th>' . esc_html__('Status', 'engintenia-platform') . '</th><th>' . esc_html__('Action', 'engintenia-platform') . '</th></tr></thead><tbody>';
        foreach ($subscriptions as $subscription) {
            $status = get_post_meta($subscription->ID, 'eng_subscription_status', true);
            $receipt_image = wp_get_attachment_url((int) get_post_meta($subscription->ID, 'eng_receipt_image', true));
            $user_id = (int) $subscription->post_author;
            $user = get_userdata($user_id);

            echo '<tr>';
            echo '<td>' . esc_html($user ? $user->display_name : '-') . '</td>';
            echo '<td>$' . esc_html(get_post_meta($subscription->ID, 'eng_receipt_amount', true)) . '</td>';
            echo '<td>' . esc_html(get_post_meta($subscription->ID, 'eng_receipt_date', true)) . '</td>';
            echo '<td>' . ($receipt_image ? '<a href="' . esc_url($receipt_image) . '" target="_blank" rel="noopener">' . esc_html__('View', 'engintenia-platform') . '</a>' : '-') . '</td>';
            echo '<td>' . esc_html($status) . '</td>';
            echo '<td>';
            echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
            wp_nonce_field('eng_review_subscription');
            echo '<input type="hidden" name="action" value="engintenia_review_subscription">';
            echo '<input type="hidden" name="subscription_id" value="' . esc_attr((string) $subscription->ID) . '">';
            echo '<button class="button button-primary" name="decision" value="approved">' . esc_html__('Approve', 'engintenia-platform') . '</button> ';
            echo '<button class="button" name="decision" value="rejected">' . esc_html__('Reject', 'engintenia-platform') . '</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }

    public function handle_subscription_review()
    {
        if (! current_user_can('manage_options')) {
            wp_die(esc_html__('Unauthorized', 'engintenia-platform'));
        }

        check_admin_referer('eng_review_subscription');

        $subscription_id = (int) ($_POST['subscription_id'] ?? 0);
        $decision = sanitize_text_field(wp_unslash($_POST['decision'] ?? 'rejected'));
        $user_id = (int) get_post_field('post_author', $subscription_id);

        update_post_meta($subscription_id, 'eng_subscription_status', $decision);
        update_user_meta($user_id, 'eng_subscription_status', $decision);

        if ('approved' === $decision) {
            $this->create_notification($user_id, __('Your subscription has been approved. You can now submit proposals and view contacts.', 'engintenia-platform'));
        }

        wp_safe_redirect(admin_url('admin.php?page=engintenia-subscriptions'));
        exit;
    }

    public function render_register_form()
    {
        if (is_user_logged_in()) {
            return '<p>' . esc_html__('You are already logged in.', 'engintenia-platform') . '</p>';
        }

        ob_start();
        ?>
        <form method="post" action="<?php echo esc_url(wp_registration_url()); ?>" class="eng-card">
            <h3><?php esc_html_e('Create account', 'engintenia-platform'); ?></h3>
            <p><?php esc_html_e('Use WordPress native registration and choose your role in profile after admin assignment.', 'engintenia-platform'); ?></p>
            <a class="eng-btn" href="<?php echo esc_url(wp_registration_url()); ?>"><?php esc_html_e('Register', 'engintenia-platform'); ?></a>
            <a class="eng-btn eng-btn-ghost" href="<?php echo esc_url(wp_login_url()); ?>"><?php esc_html_e('Login', 'engintenia-platform'); ?></a>
        </form>
        <?php
        return (string) ob_get_clean();
    }

    public function render_projects_list()
    {
        $country = sanitize_text_field($_GET['country'] ?? '');
        $budget = sanitize_text_field($_GET['budget'] ?? '');
        $category = (int) ($_GET['category'] ?? 0);

        $meta_query = ['relation' => 'AND'];
        if ($country) {
            $meta_query[] = ['key' => 'eng_country', 'value' => $country, 'compare' => '='];
        }
        if ($budget) {
            $meta_query[] = ['key' => 'eng_budget', 'value' => $budget, 'compare' => 'LIKE'];
        }

        $tax_query = [];
        if ($category) {
            $tax_query[] = [
                'taxonomy' => 'eng_category',
                'field' => 'term_id',
                'terms' => $category,
            ];
        }

        $projects = get_posts([
            'post_type' => 'eng_project',
            'posts_per_page' => 12,
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
        ]);

        $countries = $this->get_countries();
        $categories = get_terms(['taxonomy' => 'eng_category', 'hide_empty' => false]);

        ob_start();
        include ENGINTENIA_PLATFORM_PATH . 'templates/projects-list.php';
        return (string) ob_get_clean();
    }

    public function render_project_submit()
    {
        if (! is_user_logged_in() || ! in_array('eng_client', wp_get_current_user()->roles, true)) {
            return '<p>' . esc_html__('Only clients can post projects.', 'engintenia-platform') . '</p>';
        }

        $countries = $this->get_countries();
        $categories = get_terms(['taxonomy' => 'eng_category', 'hide_empty' => false]);

        ob_start();
        include ENGINTENIA_PLATFORM_PATH . 'templates/project-submit.php';
        return (string) ob_get_clean();
    }

    public function render_subscription_form()
    {
        if (! is_user_logged_in() || ! in_array('eng_contractor', wp_get_current_user()->roles, true)) {
            return '<p>' . esc_html__('Only contractors can request subscription approval.', 'engintenia-platform') . '</p>';
        }

        $status = get_user_meta(get_current_user_id(), 'eng_subscription_status', true);

        ob_start();
        include ENGINTENIA_PLATFORM_PATH . 'templates/subscription-form.php';
        return (string) ob_get_clean();
    }

    public function render_company_dashboard()
    {
        if (! is_user_logged_in() || ! in_array('eng_client', wp_get_current_user()->roles, true)) {
            return '<p>' . esc_html__('Client dashboard only.', 'engintenia-platform') . '</p>';
        }

        $projects = get_posts([
            'post_type' => 'eng_project',
            'author' => get_current_user_id(),
            'posts_per_page' => -1,
        ]);

        $project_ids = wp_list_pluck($projects, 'ID');
        $proposals = [];

        if (! empty($project_ids)) {
            $proposals = get_posts([
                'post_type' => 'eng_proposal',
                'posts_per_page' => -1,
                'meta_query' => [[
                    'key' => 'eng_project_id',
                    'value' => $project_ids,
                    'compare' => 'IN',
                ]],
            ]);
        }

        ob_start();
        include ENGINTENIA_PLATFORM_PATH . 'templates/company-dashboard.php';
        return (string) ob_get_clean();
    }

    public function render_contractor_dashboard()
    {
        if (! is_user_logged_in() || ! in_array('eng_contractor', wp_get_current_user()->roles, true)) {
            return '<p>' . esc_html__('Contractor dashboard only.', 'engintenia-platform') . '</p>';
        }

        $status = get_user_meta(get_current_user_id(), 'eng_subscription_status', true);
        $proposals = get_posts([
            'post_type' => 'eng_proposal',
            'author' => get_current_user_id(),
            'posts_per_page' => -1,
        ]);

        $notifications = get_user_meta(get_current_user_id(), 'eng_notifications', true);
        if (! is_array($notifications)) {
            $notifications = [];
        }

        ob_start();
        include ENGINTENIA_PLATFORM_PATH . 'templates/contractor-dashboard.php';
        return (string) ob_get_clean();
    }

    public function render_contractors_list()
    {
        $contractors = get_users(['role' => 'eng_contractor']);

        ob_start();
        include ENGINTENIA_PLATFORM_PATH . 'templates/contractors-list.php';
        return (string) ob_get_clean();
    }

    public function render_notifications()
    {
        if (! is_user_logged_in()) {
            return '';
        }

        $notifications = get_user_meta(get_current_user_id(), 'eng_notifications', true);
        if (! is_array($notifications)) {
            $notifications = [];
        }

        ob_start();
        echo '<div class="eng-card"><h3>' . esc_html__('Notifications', 'engintenia-platform') . '</h3><ul>';
        foreach ($notifications as $notice) {
            echo '<li>' . esc_html($notice['text']) . ' <small>(' . esc_html($notice['date']) . ')</small></li>';
        }
        echo '</ul></div>';
        return (string) ob_get_clean();
    }

    public function render_language_switcher()
    {
        $languages = [
            'ar' => __('Arabic', 'engintenia-platform'),
            'en_US' => __('English', 'engintenia-platform'),
            'fr_FR' => __('French', 'engintenia-platform'),
            'es_ES' => __('Spanish', 'engintenia-platform'),
            'de_DE' => __('German', 'engintenia-platform'),
            'tr_TR' => __('Turkish', 'engintenia-platform'),
            'ru_RU' => __('Russian', 'engintenia-platform'),
            'it_IT' => __('Italian', 'engintenia-platform'),
            'zh_CN' => __('Chinese', 'engintenia-platform'),
            'hi_IN' => __('Hindi', 'engintenia-platform'),
        ];

        $current = determine_locale();
        $is_rtl = is_rtl();

        ob_start();
        echo '<div class="eng-card"><h3>' . esc_html__('Language-ready setup', 'engintenia-platform') . '</h3>';
        echo '<p>' . esc_html__('This platform is translation-ready. Connect with Polylang/WPML/Loco Translate to publish multilingual content.', 'engintenia-platform') . '</p>';
        echo '<p><strong>' . esc_html__('Current locale:', 'engintenia-platform') . '</strong> ' . esc_html($current) . ' | <strong>RTL:</strong> ' . ($is_rtl ? 'Yes' : 'No') . '</p><ul>';
        foreach ($languages as $code => $name) {
            echo '<li>' . esc_html($name) . ' (' . esc_html($code) . ')</li>';
        }
        echo '</ul></div>';
        return (string) ob_get_clean();
    }

    private function create_notification($user_id, $text)
    {
        $notifications = get_user_meta($user_id, 'eng_notifications', true);
        if (! is_array($notifications)) {
            $notifications = [];
        }

        $notifications[] = [
            'text' => $text,
            'date' => current_time('mysql'),
        ];

        update_user_meta($user_id, 'eng_notifications', $notifications);
    }

    public function register_rest_routes()
    {
        register_rest_route('engintenia/v1', '/projects', [
            'methods' => 'GET',
            'callback' => function () {
                $projects = get_posts(['post_type' => 'eng_project', 'posts_per_page' => 20]);
                $data = [];
                foreach ($projects as $project) {
                    $data[] = [
                        'id' => $project->ID,
                        'title' => $project->post_title,
                        'budget' => get_post_meta($project->ID, 'eng_budget', true),
                        'country' => get_post_meta($project->ID, 'eng_country', true),
                    ];
                }

                return rest_ensure_response($data);
            },
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('engintenia/v1', '/notifications', [
            'methods' => 'GET',
            'callback' => function () {
                if (! is_user_logged_in()) {
                    return new WP_Error('unauthorized', __('Login required', 'engintenia-platform'), ['status' => 401]);
                }

                return rest_ensure_response(get_user_meta(get_current_user_id(), 'eng_notifications', true));
            },
            'permission_callback' => fn() => is_user_logged_in(),
        ]);
    }

    private function get_countries()
    {
        return [
            'United Arab Emirates', 'Saudi Arabia', 'Qatar', 'Kuwait', 'Bahrain', 'Oman', 'Jordan', 'Egypt',
            'Germany', 'France', 'Italy', 'Spain', 'Netherlands', 'Belgium', 'Sweden', 'Norway',
            'United States', 'Canada',
            'Nigeria', 'South Africa', 'Kenya', 'Morocco', 'Ghana', 'Algeria',
        ];
    }
}
