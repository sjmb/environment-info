# Sjmb_EnvironmentInfo

Displays a configurable visual environment indicator on every admin page to identify the current hostname.

## Overview

`Sjmb_EnvironmentInfo` injects a small UI element into the Magento 2 admin panel so developers and administrators can immediately identify which environment (staging, production, local) they are working in. The indicator is driven entirely by system configuration: it can be globally disabled, always enabled, or restricted to a list of specific hostnames. Three visual styles are available — a top bar, a corner badge, and a dismissible alert — each rendered via a dedicated template.

## Requirements

- Magento 2.4.x or higher
- PHP 8.3 or higher
- No external module dependencies declared

## Installation

### Composer installation

Run command to install module:

```bash
composer require --dev sjmb/module-environment-info
```

### Manual installation

Download module and place in directory `<magento-root>/app/code/Sjmb/EnvironmentInfo`

## Enable the module

```bash
php bin/magento module:enable Sjmb_EnvironmentInfo
php bin/magento setup:upgrade
php bin/magento cache:flush
```

## Configuration

### CLI

To enable display info can use CLI command:

```bash
php bin/magento config:set sjmb_env_info/general/mode 1
```

To save config to `app/etc/env.php` can add flag `--lock-env`:

```bash
php bin/magento config:set sjmb_env_info/general/mode 1 --lock-env
```

### env.php

To enable display by `app/etc/env.php` can add bellow code:

```php
...
'system' => [
    'default' => [
        'sjmb_env_info' => [
            'general' => [
                'mode' => '1'
            ]
        ]
    ]
]
...
```

### Admin panel

Navigate to **Stores > Configuration > SJMB > Environment Info > General Settings**.

| Config path | UI label | Type | Default | Description |
|-------------|----------|------|---------|-------------|
| `sjmb_env_info/general/mode` | Display Mode | select | `0` (Disabled) | Controls when the indicator is shown |
| `sjmb_env_info/general/display_type` | Display Type | select | `topbar` | Visual style of the indicator; hidden when mode is Disabled |
| `sjmb_env_info/general/allowed_domains` | Allowed Domains | dynamic rows (JSON) | — | List of hostnames; visible only when Display Mode is Selected Domains |

**Display Mode values**

| Value | Label | Behaviour |
|-------|-------|-----------|
| `0` | Disabled | Indicator is never rendered |
| `1` | Enabled | Indicator is rendered on every admin page |
| `2` | Selected Domains | Indicator is rendered only when `HTTP_HOST` matches an entry in Allowed Domains |

**Display Type values**

| Value | Template | Visual description |
|-------|----------|-------------------|
| `topbar` | `topbar.phtml` | Fixed full-width orange bar at the top of the page; JS adjusts `body` top margin to prevent overlap |
| `corner` | `corner.phtml` | Small purple badge fixed in the bottom-right corner; `pointer-events: none` |
| `alert` | `alert.phtml` | Dismissible orange bar fixed below the top navigation (`top: 60px`); includes a close button |

## Features

- Admin system configuration section under **Stores > Configuration > SJMB > Environment Info**.
- Three display modes: Disabled, Enabled, and Selected Domains.
- Three visual indicator styles: topbar, corner badge, dismissible alert.
- Selected Domains mode performs an exact `HTTP_HOST` match against a JSON-serialized list of domain rows configured via a dynamic-rows UI field.

## Usage

**Enable with topbar on all pages**

1. Go to Stores > Configuration > SJMB > Environment Info.
2. Set Display Mode to **Enabled**.
3. Set Display Type to **Topbar**.
4. Save configuration and flush cache.

**Restrict to specific environments by hostname**

1. Set Display Mode to **Selected Domains**.
2. In the Allowed Domains grid, add one row per hostname (e.g. `staging.example.com`).
3. Set Display Type to the desired style.
4. Save configuration and flush cache.

The indicator will appear only when the admin is accessed from a matching hostname.


# Info

* GIT repository: [https://github.com/sjmb/environment-info](https://github.com/sjmb/environment-info)

> Copyright (c) 2026 [SJMB](https://sjmb.pl) · Code in 🐻‍❄️ in 🇵🇱
