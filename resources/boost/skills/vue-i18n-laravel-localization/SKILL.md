---
name: vue-i18n-laravel-localization
description: "Handles frontend and backend localization using vue-i18n and Laravel language files. Activates when working on translations, localized strings, translating UI text, setting up multi-language support, or working with i18n/translation JSON/PHP files."
license: MIT
metadata:
  author: laravel
---

# Vue i18n & Laravel Localization

Use this skill when adding, editing, or refactoring user-facing text strings in both the frontend (Vue) and backend (PHP), translating text files, integrating `vue-i18n` in Vue components, or running the translation compiler.

## Rules & Best Practices

### 1. English by Default
All user-facing strings must be in **English by default**. Never hardcode Spanish or other languages directly in the templates or source files. Always use translation keys.

### 2. Vue i18n (Frontend Translation)
When writing frontend text in Vue components, use `vue-i18n` instead of static strings.

#### Inside Script Tag (`<script setup>`)
Import and use the `useI18n` composable:
```vue
<script setup>
import { useI18n } from "vue-i18n";

const { t } = useI18n();

// Example usage in script logic
const title = t("Alert");
const message = t("Welcome to the dashboard");
</script>
```

#### Inside Template Tag (`<template>`)
Use the `$t()` helper directly in HTML tags or attributes:
```vue
<template>
    <div>
        <h2>{{ $t("Success") }}</h2>
        <p>{{ $t('The "{feature}" feature is active.', { feature: "Billing" }) }}</p>
    </div>
</template>
```

### 3. Translation Storage Strategy
Ensure you place translation keys in the correct file based on their scope to leverage the `vue-i18n` generator plugin.

#### Global Strings (e.g. "Alert", "Success", "Status", "Cancel")
- Store global strings inside `lang/en.json` (English) and `lang/es.json` (Spanish).
- Example:
  ```json
  {
      "Alert": "Alert",
      "Success": "Success"
  }
  ```

#### Specific/Contextual Strings (e.g. `plan.title`, `webhook.status`)
- Store highly specific or domain-scoped strings in their respective PHP files within the `lang/{locale}/` directories (e.g., `lang/en/plans.php`, `lang/en/features.php`).
- Example:
  ```php
  // lang/en/plans.php
  return [
      'title' => 'Premium Membership Plan',
      'features' => 'Advanced routing features',
  ];
  ```

### 4. Laravel Backend Translation Helper
All user-facing strings returned by the backend (such as validator errors, controller flash messages, or email notifications) must be wrapped using the Laravel `__('string')` helper.
- Global keys: `__('My message')`
- Specific keys: `__('plans.title')`

---

## Compiling Translations

After adding or editing translation files under `lang/`, the artisan generator command compiles them to JavaScript/TypeScript for the frontend.

### 1. Determining Environment (Sail vs. PHP)
Before executing commands, check if the project uses **Laravel Sail**:
- Look for `vendor/bin/sail` or `docker-compose.yml` in the project root, or check if `"laravel/sail"` is present in the `require-dev` block of `composer.json`.
- If Sail is used and docker containers are running, prefix all artisan commands with `./vendor/bin/sail`. Otherwise, run them using `php` directly.

### 2. Generating Translations
Run the generation command:
```bash
# If using Laravel Sail:
./vendor/bin/sail artisan vue-i18n:generate

# If using local PHP directly:
php artisan vue-i18n:generate
```

### 3. Output Formats
Specify output formats using the `--format` option (defaults to `ts`):

| Format | Output Extension | Syntax / Description |
| --- | --- | --- |
| `ts` *(Default)* | `.ts` | `export default { ... } as const;` (Provides type-safety and editor autocomplete). |
| `es6` | `.js` | `export default { ... }` (Standard ES6 JS Module export). |
| `umd` | `.js` | UMD wrapper (for browser script tags, Node, etc.). |
| `json` | `.json` | Raw JSON object output. |

Generate specific formats:
```bash
php artisan vue-i18n:generate --format=ts
php artisan vue-i18n:generate --format=json
```

Generate multiple split files (e.g., for lazy loading):
```bash
# Split by language files
php artisan vue-i18n:generate --multi

# Split by locales
php artisan vue-i18n:generate --multi-locales
```

---

## Verification

1. Run the generation command: `php artisan vue-i18n:generate` or `./vendor/bin/sail artisan vue-i18n:generate`.
2. Confirm the destination file is created (defaults to `/resources/js/vue-i18n-locales.ts`).
3. If using TypeScript format, verify the file ends with `as const;` and compile it with the frontend build (`npm run dev` or `vite`).
