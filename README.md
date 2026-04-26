# Engintenia WordPress Platform Plugin

This repository now contains a complete custom WordPress plugin for **Engintenia**, a marketplace connecting companies and subcontractors globally.

## Plugin location

```text
wp-content/plugins/engintenia-platform/
```

## File structure

```text
wp-content/plugins/engintenia-platform/
├─ engintenia-platform.php
├─ assets/
│  └─ css/
│     └─ style.css
├─ includes/
│  ├─ class-engintenia-plugin.php
│  ├─ class-engintenia-roles.php
│  ├─ class-engintenia-post-types.php
│  ├─ class-engintenia-subscriptions.php
│  ├─ class-engintenia-proposals.php
│  ├─ class-engintenia-notifications.php
│  ├─ class-engintenia-dashboard.php
│  ├─ class-engintenia-rest.php
│  └─ class-engintenia-shortcodes.php
└─ languages/
```

## Included features

- User roles: **Company**, **Subcontractor**, **Admin**.
- Authentication-ready registration form via shortcode.
- Contractor project posting with budget/location/category.
- Public project visibility with hidden contact details.
- Subscription workflow: **$20/month**, **Bank Transfer only**, receipt upload, manual admin approval.
- Contact details visible only to approved subscribers (or project owner/admin).
- Proposal submission with message and quotation file upload.
- Email + dashboard notifications for:
  - New projects
  - New proposals
  - Subscription approval
- Multi-language-ready via WordPress i18n (`__`, `esc_html__`, text domain):
  - English
  - Arabic
  - French
  - Spanish
  - Turkish
- Company dashboard:
  - Manage/create projects
  - View proposals
- Subcontractor dashboard:
  - Subscription status
  - Submitted offers
  - Notifications
- Admin tools:
  - Subscription approval screen
  - User/project/proposal moderation through WP admin post types/users
- REST API endpoints:
  - `GET /wp-json/engintenia/v1/projects`
  - `GET /wp-json/engintenia/v1/notifications`
- Modern, clean, mobile-responsive starter UI CSS.

## Shortcodes

Use these shortcodes on pages:

- `[eng_register_form]`
- `[eng_projects_list]`
- `[eng_project_submit]`
- `[eng_subscription_form]`
- `[eng_company_dashboard]`
- `[eng_subcontractor_dashboard]`

## Bluehost installation instructions

1. Log in to Bluehost cPanel / WordPress Admin.
2. In File Manager (or FTP), upload folder:
   - `wp-content/plugins/engintenia-platform`
3. Go to WordPress Admin → **Plugins**.
4. Activate **Engintenia Platform**.
5. Go to **Settings → Permalinks** and click **Save** once (refresh rewrite rules).
6. Create pages and place shortcodes:
   - Register page: `[eng_register_form]`
   - Projects page: `[eng_projects_list]`
   - Company Dashboard: `[eng_company_dashboard]`
   - Subcontractor Dashboard: `[eng_subcontractor_dashboard]`
7. Ensure media uploads are enabled (for receipt/quotation uploads).
8. Configure outgoing email in Bluehost (SMTP recommended plugin) so notifications are delivered reliably.
9. Admin approvals:
   - Use **Eng Subscriptions** in wp-admin to approve/reject transfer receipts.

## Recommended production hardening

- Add CAPTCHA and email verification on registration.
- Add explicit login/reset-password templates.
- Add saved-projects persistence as user meta or custom table.
- Add stronger validation/rate limiting for forms.
- Use WPML/Polylang + translation files in `languages/` for full multilingual UI.
- Add payment reconciliation workflow and receipts audit logs.
