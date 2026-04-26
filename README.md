# Engintenia WordPress Platform (Plugin + Theme)

This repository now includes:

- A custom WordPress plugin for marketplace features.
- A native WordPress theme (not static HTML) for frontend presentation.

## Plugin location

```text
wp-content/plugins/engintenia-platform/
```

## Theme location

```text
wp-content/themes/engintenia-theme/
```

## Theme files included

```text
wp-content/themes/engintenia-theme/
├─ style.css
├─ functions.php
├─ header.php
├─ footer.php
├─ front-page.php
└─ index.php
```

## What the WordPress theme provides

- Proper WordPress theme header metadata in `style.css`.
- Standard template hierarchy entry points (`front-page.php`, `index.php`).
- Global reusable layout with `header.php` and `footer.php`.
- Menu registration (`Primary Menu`) and style enqueue in `functions.php`.
- Responsive starter styles for the marketplace landing UI.
- Compatibility with Engintenia plugin shortcodes on pages.

## Included plugin features

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

## Shortcodes (from plugin)

Use these shortcodes on pages:

- `[eng_register_form]`
- `[eng_projects_list]`
- `[eng_project_submit]`
- `[eng_subscription_form]`
- `[eng_company_dashboard]`
- `[eng_subcontractor_dashboard]`

## Installation on Bluehost

1. Upload plugin folder to:
   - `wp-content/plugins/engintenia-platform`
2. Upload theme folder to:
   - `wp-content/themes/engintenia-theme`
3. In WordPress Admin:
   - Activate plugin: **Engintenia Platform**
   - Activate theme: **Engintenia Theme**
4. Go to **Settings → Permalinks** and click **Save** once.
5. Create pages and place plugin shortcodes where needed.
6. Create/assign menu in **Appearance → Menus** to **Primary Menu**.
7. Configure SMTP for reliable notification emails.

## Recommended next steps

- Add custom page templates for dashboard and project archive views.
- Add full translation files under `languages/`.
- Add child theme strategy for safe customization.
- Add CAPTCHA + stronger validation on all public forms.
