# agents.md

## Overview
This project is maintained by a human developer and assisted by AI agents (e.g., ChatGPT).  
Agents are expected to follow the conventions, constraints, and workflows defined in this document.

The goal is to ensure consistency, maintainability, and minimal back-and-forth.

---

## Core Principles

- Follow WordPress best practices and coding standards (WPCS)
- Prefer maintainable, scalable solutions over quick fixes
- Use native WordPress functionality before introducing custom logic
- Avoid unnecessary plugins or dependencies
- Match existing project patterns before introducing new ones

---

## Theme Structure

- Use proper WordPress template hierarchy
- Keep templates focused on presentation only
- Move business logic to functions or helper files
- Use partials (`get_template_part`) for reusable components

---

## Coding Standards

### PHP

- Follow WordPress Coding Standards (WPCS)
- Escape all output (`esc_html`, `esc_attr`, `esc_url`, etc.)
- Sanitize all input
- Use WordPress APIs whenever possible
- Prefer named functions over anonymous functions
- Use PHPDoc where helpful

---

### JavaScript

- Use Vanilla JS or jQuery (if already present)
- Avoid introducing frameworks
- Keep code modular and scoped
- Do not pollute global namespace
- Use event delegation when appropriate

---

### CSS / SCSS / LESS

- Follow existing naming conventions
- Scope styles to components or templates
- Avoid inline styles
- Reuse variables, mixins, and utilities where available
- Prefer extending Bootstrap 4.6 instead of overriding heavily

---

## WordPress Best Practices

- Use hooks (actions/filters) instead of modifying core or plugins
- Register scripts and styles properly (`wp_enqueue_*`)
- Avoid hardcoding URLs, paths, or IDs
- Use `wp_get_environment_type()` for environment-specific logic
- Use nonces for AJAX and form handling
- Keep logic out of templates when possible

---

## ACF Usage

- Use ACF for structured content when appropriate
- Do not hardcode content that should be editable
- Use clear field naming conventions
- Validate and escape ACF output properly

---

## WooCommerce (When Applicable)

- Use WooCommerce hooks instead of template overrides when possible
- Only override templates when necessary
- Keep customizations update-safe
- Avoid modifying core WooCommerce behavior unless required

---

## Performance & Caching

- Be aware of:
    - Page caching
    - Object caching
    - Cloudflare/CDN caching

- Do not introduce:
    - Unnecessary AJAX requests
    - Blocking scripts/styles
    - Cache-breaking logic unless required

---

## Security

- Sanitize all input
- Escape all output
- Use nonces for all form/AJAX actions
- Validate user permissions where applicable

---

## When Making Changes

- Change only what is necessary
- Match existing code style and patterns
- Do not refactor unrelated code unless asked
- Prefer minimal, targeted solutions

---

## When Unsure

- Ask for clarification instead of guessing
- Do not invent APIs, endpoints, or data structures
- Do not assume plugins or tools exist unless mentioned

---

## Disallowed Behavior

- Large refactors without request
- Introducing new frameworks/libraries unnecessarily
- Overengineering simple solutions
- Ignoring existing patterns in the codebase

---

## Developer Preferences

- Follow WPCS strictly
- Prefer single-line function calls when possible
- Avoid unnecessary abstraction
- Prioritize backend solutions over frontend workarounds
- Use named functions rather than closures

---

## Response Style

- Concise and practical
- Code-first when appropriate
- Minimal explanation unless needed
- Match developer tone

---

## Tooling / Build Process

- SCSS compilation is handled locally via PhpStorm file watchers
- Do not manually compile SCSS/CSS assets; update the `.scss` source only unless explicitly asked otherwise
- CSS output is automatically generated from SCSS (no manual compilation)
- JavaScript and CSS minification are handled via PhpStorm file watchers
- Do not introduce additional build tools (e.g., webpack, gulp) unless explicitly required
- Assume compiled `.css` and minified `.js` files are the deployable assets