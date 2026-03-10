# Frontend Structure Overview

This is a **React + Inertia.js + Laravel frontend** with TypeScript, using Tailwind CSS and Shadcn UI components.

## Root Files 📄

### `app.tsx`

- Entry point for the React application
- Sets up Inertia.js to resolve page components dynamically
- Initializes the theme (light/dark mode) on page load
- Configures app name and progress bar color

### `ssr.tsx`

- Server-side rendering configuration (if enabled)

---

## Actions 🎬

Generated proxy layer that mirrors Laravel controller structure for type-safe backend communication:

- `App/Http/Controllers/` - Maps to Laravel HTTP controllers
- `Illuminate/Routing/` - Routing utilities
- `Inertia/Controller.ts` - Inertia controller mappings
- `Laravel/Fortify/Http/` - Authentication action mappings

---

## Components 🧩

Reusable React components:

### Layout Components

- `app-shell.tsx` - Main layout wrapper with sidebar/header variants
- `app-layout.tsx` - Page layout wrapper
- `app-header.tsx`, `app-sidebar.tsx`, `app-sidebar-header.tsx`

### Common Components

- `app-logo.tsx`, `app-logo-icon.tsx` - Logo variants
- `breadcrumbs.tsx` - Navigation breadcrumbs
- `nav-main.tsx`, `nav-footer.tsx` - Navigation menus
- `user-menu-content.tsx` - User dropdown menu
- `user-info.tsx` - User profile display
- `input-error.tsx` - Form validation error display
- `text-link.tsx` - Link component
- `nav-user.tsx` - User navigation

### Specialized Components

- `delete-user.tsx` - User account deletion UI
- `two-factor-setup-modal.tsx` - 2FA setup wizard
- `two-factor-recovery-codes.tsx` - 2FA recovery codes display
- `appearance-tabs.tsx` - Theme/appearance selection
- `alert-error.tsx` - Error alert display

### UI Folder - Shadcn UI Components

Pre-built UI component library:

- **Form Basics**: `button.tsx`, `input.tsx`, `label.tsx`
- **Form Controls**: `select.tsx`, `checkbox.tsx`, `toggle.tsx`, `toggle-group.tsx`
- **Layout**: `card.tsx`, `breadcrumb.tsx`
- **Menus**: `dropdown-menu.tsx`, `navigation-menu.tsx`, `sheet.tsx`
- **Sidebar**: `sidebar.tsx`
- **Modals**: `dialog.tsx`, `alert.tsx`
- **Display**: `avatar.tsx`, `badge.tsx`, `skeleton.tsx`
- **Graphics**: `icon.tsx`, `placeholder-pattern.tsx`
- **Interactions**: `tooltip.tsx`, `collapsible.tsx`

---

## Hooks 🎣

Custom React hooks:

- **`use-appearance.tsx`** - Light/dark theme management with localStorage and system preference detection
- **`use-clipboard.ts`** - Copy to clipboard utility
- **`use-current-url.ts`** - Get current page URL
- **`use-initials.tsx`** - Extract initials from names
- **`use-mobile.tsx`** - Media query hook to detect mobile breakpoint (768px)
- **`use-mobile-navigation.ts`** - Mobile navigation state management
- **`use-two-factor-auth.ts`** - Two-factor authentication logic

---

## Layouts 🎨

### Main Layouts

- **`app-layout.tsx`** - Main app page wrapper
- **`auth-layout.tsx`** - Authentication pages wrapper

### app/ Subfolder - Application Layouts

- `app-header-layout.tsx` - Header-based navigation
- `app-sidebar-layout.tsx` - Sidebar-based navigation

### auth/ Subfolder - Authentication Page Templates

- `auth-card-layout.tsx` - Card-style auth form
- `auth-simple-layout.tsx` - Simple centered auth form
- `auth-split-layout.tsx` - Split-screen auth layout

### settings/ Subfolder

- `layout.tsx` - Settings page wrapper

---

## Lib 📚

### `utils.ts` - Utility Functions

- `cn()` - Tailwind CSS class merging (merges clsx + tailwind-merge)
- `toUrl()` - Convert Inertia link props to URL string

---

## Pages 📑

Page components (auto-routed by Inertia):

### Main Pages

- **`dashboard.tsx`** - Main dashboard with placeholder cards
- **`welcome.tsx`** - Landing page with login/register links

### auth/ Subfolder - Authentication Pages

- `login.tsx` - Login form
- `register.tsx` - Registration form with role selector
- `forgot-password.tsx` - Password reset request
- `reset-password.tsx` - Password reset form
- `verify-email.tsx` - Email verification page
- `two-factor-challenge.tsx` - 2FA verification
- `confirm-password.tsx` - Password confirmation for sensitive operations

### settings/ Subfolder - Settings Pages

- `profile.tsx` - Profile settings
- `password.tsx` - Change password
- `appearance.tsx` - Theme/appearance settings
- `two-factor.tsx` - Two-factor setup

---

## Routes 🛣️

Type-safe route definitions (auto-generated from Laravel routes):

- **`index.ts`** - Main routes (login, logout, register, dashboard)
- **`login/index.ts`** - Login endpoint
- **`register/index.ts`** - Registration endpoint
- **`password/`** - Password reset routes
    - `confirm/index.ts` - Confirm password
- **`profile/index.ts`** - Profile management routes
- **`appearance/index.ts`** - Theme preference routes
- **`user-password/index.ts`** - User password routes
- **`verification/index.ts`** - Email verification routes
- **`two-factor/index.ts`** - 2FA routes
    - `login/` - 2FA login challenge
- **`storage/`** - File storage routes
    - `local/...` - Local storage access

---

## Types 🏷️

TypeScript type definitions:

- **`index.ts`** - Main export file (re-exports all types)
- **`auth.ts`** - Authentication types:
    - `User` - User model
    - `Auth` - Auth context type
    - `TwoFactorSetupData` - 2FA setup info
    - `TwoFactorSecretKey` - 2FA secret key

- **`navigation.ts`** - UI navigation types:
    - `BreadcrumbItem` - Breadcrumb data
    - `NavItem` - Navigation menu item

- **`ui.ts`** - UI component types

- **`global.d.ts`** - Global type declarations
- **`vite-env.d.ts`** - Vite environment types

---

## Wayfinder 🧭

Route query parameter builder (auto-generated):

### `index.ts` - Core Functionality

- `queryParams()` - Converts typed objects to URL query strings
- `RouteDefinition` - Route method/URL definition
- `RouteFormDefinition` - Form action/method definition
- Handles nested parameters and array values

---

## Architecture Summary 🏗️

```
Frontend Stack:
├── React 19 (UI library)
├── Inertia.js (Server-side adapter)
├── TypeScript (Type safety)
├── Tailwind CSS (Styling)
├── Shadcn UI (Component library)
└── Lucide React (Icons)

Data Flow:
Laravel Backend → Inertia Routes → React Pages
                       ↓
                  Type-safe Props
                       ↓
                  Components/Hooks
                       ↓
                    DOM Render
```

---

## Key Features ✨

✅ **Full Authentication** - Login, register, password reset, 2FA, email verification  
✅ **Settings Pages** - Profile, password, appearance/theme  
✅ **Type Safety** - End-to-end TypeScript from Laravel to React  
✅ **Responsive** - Mobile-first design with sidebar/header variants  
✅ **Dark Mode** - System preference + manual toggle  
✅ **Auto-routing** - Pages auto-routed based on file structure  
✅ **Pre-built UI** - Comprehensive Shadcn UI component library

---

## Technology Stack 🛠️

| Technology         | Purpose                         |
| ------------------ | ------------------------------- |
| **React 19**       | UI library                      |
| **Inertia.js**     | Server-side adapter for Laravel |
| **TypeScript**     | Type safety                     |
| **Tailwind CSS**   | Utility-first CSS framework     |
| **Shadcn UI**      | Component library               |
| **Lucide React**   | Icon library                    |
| **Vite**           | Build tool                      |
| **clsx**           | Class name utility              |
| **tailwind-merge** | Merge Tailwind classes          |

---

This is a **modern, full-featured Laravel + React admin dashboard** with authentication, settings, and theming built-in! 🚀
