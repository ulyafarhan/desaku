# Implementation Plan: Modern Desaku Frontend

## Overview

This implementation plan breaks down the Modern Desaku Frontend feature into discrete, actionable coding tasks. The feature is a Vue 3 + Inertia.js progressive web application with three portals (Public, Citizen, Admin) that communicates with the Laravel backend via REST API.

The implementation follows a Mobile-First approach with strong emphasis on accessibility, performance optimization, and reusable component architecture. Tasks are organized to build the foundation first (design system, core components), then implement each portal incrementally, and finally add cross-cutting concerns (PWA, testing, optimization).

## Tasks

- [ ] 1. Project setup and design token configuration
  - Initialize Vite project with Vue 3 and Inertia.js
  - Install and configure Tailwind CSS v4
  - Configure design tokens in `tailwind.config.js` (colors, typography, spacing per design.md)
  - Set up project structure (`Components/`, `Layouts/`, `Pages/`, `Composables/`, `Utils/`)
  - Install dependencies (Axios, Lucide Vue, Vue Test Utils, Vitest)
  - Create environment variable template (`.env.example`) with `VITE_API_BASE_URL`
  - _Requirements: 1.1, 1.2, 1.3, 1.5, 1.6_

- [ ] 2. Implement core component library - Base components
  - [ ] 2.1 Create AppButton component with all variants
    - Implement `AppButton.vue` with props (variant, size, loading, disabled, fullWidth, icon, iconPosition)
    - Add visual states (default, hover, active, disabled, loading) with Tailwind classes
    - Implement accessibility attributes (aria-disabled, aria-busy, minimum 44x44px touch target)
    - _Requirements: 2.1, 2.9, 2.10, 15.3_

  - [ ]* 2.2 Write unit tests for AppButton component
    - Test rendering, variant styles, states, click events, disabled behavior
    - _Requirements: 2.1, 2.9, 2.10_

  - [ ] 2.3 Create AppCard component
    - Implement `AppCard.vue` with props (variant, padding, hoverable, clickable)
    - Add border, shadow, and transition effects
    - Implement responsive padding logic
    - _Requirements: 2.2_

  - [ ] 2.4 Create FormInput component with validation
    - Implement `FormInput.vue` with props (modelValue, label, type, placeholder, required, error, disabled, maxLength, autocomplete)
    - Add label with required indicator, focus ring styling, error state with message
    - Implement password visibility toggle for password type
    - Add proper inputmode attributes for mobile keyboards
    - _Requirements: 2.3, 5.8, 15.7_

  - [ ]* 2.5 Write unit tests for FormInput component
    - Test v-model binding, validation display, password toggle, error states
    - _Requirements: 2.3_

  - [ ] 2.6 Create FormSelect component
    - Implement `FormSelect.vue` with props (modelValue, label, options, placeholder, required, error, disabled)
    - Style native select with dropdown arrow indicator
    - Ensure styling consistency with FormInput
    - _Requirements: 2.4_

  - [ ] 2.7 Create SkeletonLoader component
    - Implement `SkeletonLoader.vue` with props (variant, count, width, height, className)
    - Add pulse animation with variant-specific shapes
    - _Requirements: 2.5, 3.7, 13.11_

  - [ ] 2.8 Create StatusBadge component
    - Implement `StatusBadge.vue` with props (status, size)
    - Add color mapping (pending: yellow, diproses: blue, selesai: green, ditolak: red)
    - Include icon + text label with pill shape styling
    - _Requirements: 2.8, 6.4_

  - [ ] 2.9 Create EmptyState component
    - Implement `EmptyState.vue` with props (illustration, title, description, actionLabel, actionIcon)
    - Add centered layout with responsive sizing
    - _Requirements: 2.10, 6.9_

- [ ] 3. Implement core component library - Interactive components
  - [ ] 3.1 Create Toast notification system with useToast composable
    - Implement `useToast.js` composable with state management (toasts array, showToast, removeToast methods)
    - Create `Toast.vue` component with auto-dismiss logic and slide-in animation
    - Add type-based styling (success: green, error: red, info: blue, warning: yellow)
    - Implement maximum 3 toasts visible simultaneously
    - _Requirements: 2.6, 17.1, 17.2, 17.3, 17.4_

  - [ ]* 3.2 Write unit tests for Toast component
    - Test toast creation, auto-dismiss timing, manual dismiss, type styling
    - _Requirements: 2.6_

  - [ ] 3.3 Create Modal component with focus trap
    - Implement `Modal.vue` with props (modelValue, title, size, closeOnBackdrop, showCloseButton)
    - Add backdrop overlay, slide-up animation, ESC key handler
    - Implement focus trap and body scroll lock
    - Add accessibility attributes (role="dialog", aria-modal="true")
    - _Requirements: 2.7, 15.2_

  - [ ]* 3.4 Write unit tests for Modal component
    - Test open/close behavior, backdrop click, ESC key, focus trap
    - _Requirements: 2.7_

  - [ ] 3.5 Create StepIndicator component for multi-step forms
    - Implement `StepIndicator.vue` with props (steps, currentStep, clickable)
    - Add step states (completed: green check, active: primary color, pending: gray)
    - Implement responsive layout (horizontal on desktop, vertical on mobile <768px)
    - Add connecting lines between steps
    - _Requirements: 2.8, 7.2, 16.3_

- [ ] 4. Implement composables for shared logic
  - [ ] 4.1 Create useApi composable with Axios interceptors
    - Implement `useApi.js` with configured Axios instance (baseURL, timeout, headers)
    - Add request interceptor to attach Bearer token from localStorage
    - Add response interceptor with error handling (401, 403, 422, 429, 500, network errors)
    - Integrate Toast notifications for errors
    - _Requirements: 14.1, 14.2, 14.3, 14.4, 14.5, 14.6, 14.7, 14.8_

  - [ ] 4.2 Create useForm composable for form handling
    - Implement `useForm.js` with state (formData, errors, processing)
    - Add methods (setError, clearError, clearErrors, handleValidationErrors, submit)
    - Implement 422 validation error mapping to form fields
    - _Requirements: 5.9, 7.9, 7.10, 7.11, 7.12, 14.5, 15.10_

  - [ ] 4.3 Create useAuth composable for authentication state
    - Implement `useAuth.js` with user state, login, logout, and token management
    - Add methods to get current user from localStorage/session
    - Implement logout with token cleanup and redirect
    - _Requirements: 5.4, 5.5, 9.7, 19.1, 19.4, 19.8_

  - [ ] 4.4 Create useNetworkStatus composable for offline detection
    - Implement `useNetworkStatus.js` with online status tracking
    - Add event listeners for online/offline events
    - Implement offline banner auto-dismiss when back online
    - _Requirements: 17.7, 17.8, 18.9_

  - [ ] 4.5 Create utility functions
    - Implement `formatters.js` (date, number, text formatting functions)
    - Implement `validators.js` (NIK validation, email validation, required field checks)
    - Implement `constants.js` (API endpoints, status mappings, default values)
    - _Requirements: 5.2, 6.4_

- [ ] 5. Checkpoint - Core foundation complete
  - Ensure all tests pass, verify component library is functional, ask the user if questions arise.

- [ ] 6. Implement layout components
  - [ ] 6.1 Create PublicLayout component
    - Implement `PublicLayout.vue` with sticky header, main content slot, footer
    - Create `AppHeader.vue` with logo, navigation items, responsive hamburger menu
    - Create `AppFooter.vue` with village info and links
    - Implement responsive navigation (drawer menu on mobile <768px)
    - _Requirements: 3.1, 16.2_

  - [ ] 6.2 Create CitizenLayout component
    - Implement `CitizenLayout.vue` with authenticated header and main content area
    - Create `AuthHeader.vue` with user info display and logout button
    - Add container with responsive padding
    - _Requirements: 6.1, 9.7_

  - [ ] 6.3 Create AdminLayout component with sidebar navigation
    - Implement `AdminLayout.vue` with responsive sidebar/drawer pattern
    - Create `AdminSidebar.vue` with collapsible navigation menu
    - Create `AdminHeader.vue` with hamburger toggle and user menu
    - Implement desktop sidebar (collapsible) and mobile drawer (overlay)
    - Add route-based active menu highlighting
    - Persist sidebar collapsed state in localStorage
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5, 9.6, 9.8, 16.2_

- [ ] 7. Implement Public Portal pages
  - [ ] 7.1 Create public homepage with hero section
    - Implement `Public/Home.vue` page with PublicLayout
    - Create hero section with background image, title, and two CTA buttons
    - Implement lazy loading for hero image with compression
    - _Requirements: 3.1, 3.6, 3.8, 13.2_

  - [ ] 7.2 Create statistics section for homepage
    - Add statistics cards component displaying real-time data (jumlah penduduk, luas wilayah, jumlah dusun, layanan aktif)
    - Fetch data from Backend API `GET /api/public/statistik`
    - Display SkeletonLoader while loading
    - _Requirements: 3.2, 3.3, 3.7_

  - [ ] 7.3 Create news section for homepage
    - Add news grid component displaying latest 3-6 articles
    - Fetch data from Backend API `GET /api/public/informasi-publik`
    - Display card with title, excerpt, category, publish date
    - Display SkeletonLoader while loading
    - _Requirements: 3.4, 3.5, 3.7_

  - [ ] 7.4 Create village profile page
    - Implement `Public/Profile.vue` with sections (sejarah, visi-misi, demografi)
    - Fetch profile data from Backend API `GET /api/public/profil-gampong`
    - Implement responsive layout for content sections
    - _Requirements: 4.1, 4.2, 4.7_

  - [ ] 7.5 Create organizational structure page
    - Implement `Public/Organization.vue` with hierarchical diagram
    - Render as tree diagram on desktop (≥768px)
    - Convert to vertical card list on mobile (<768px)
    - Add embedded LeafletJS map with lazy loading
    - _Requirements: 4.3, 4.4, 4.5, 4.6, 16.3_

- [ ] 8. Checkpoint - Public portal complete
  - Ensure all tests pass, verify public pages are functional and responsive, ask the user if questions arise.

- [ ] 9. Implement Citizen Portal - Authentication
  - [ ] 9.1 Create login page for citizens
    - Implement `Citizen/Login.vue` with NIK and password fields
    - Add client-side validation (NIK: 16 digits format)
    - Implement password visibility toggle
    - Add security notice text below form
    - _Requirements: 5.1, 5.2, 5.7_

  - [ ] 9.2 Implement login form submission and error handling
    - POST credentials to Backend API `/api/citizen/login`
    - Store API token in localStorage on success
    - Display error Toast on failure with appropriate message
    - Disable submit button while request is pending
    - Display inline validation errors for form fields
    - Redirect to citizen dashboard on successful login
    - _Requirements: 5.3, 5.4, 5.5, 5.8, 5.9_

  - [ ]* 9.3 Write integration tests for login flow
    - Test successful login, validation errors, network errors, token storage
    - _Requirements: 5.3, 5.4, 5.5_

- [ ] 10. Implement Citizen Portal - Dashboard
  - [ ] 10.1 Create citizen dashboard page
    - Implement `Citizen/Dashboard.vue` with CitizenLayout
    - Create submission status list component
    - Create letter type catalog component (icon button grid)
    - _Requirements: 6.1, 6.5_

  - [ ] 10.2 Fetch and display submission list
    - Fetch submission data from Backend API `GET /api/citizen/pengajuan-surat`
    - Display each submission with nomor registrasi, jenis surat, tanggal pengajuan, StatusBadge
    - Display SkeletonLoader while loading
    - Display EmptyState when no submissions exist with CTA to create first submission
    - Implement auto-refresh when returning from form submission
    - _Requirements: 6.2, 6.3, 6.4, 6.8, 6.9, 6.10_

  - [ ] 10.3 Fetch and display letter type catalog
    - Fetch letter types from Backend API `GET /api/citizen/kategori-surat`
    - Display as icon button grid with letter names
    - Navigate to submission form when button clicked
    - _Requirements: 6.5, 6.6, 6.7_

  - [ ]* 10.4 Write integration tests for citizen dashboard
    - Test submission list rendering, empty state, letter catalog, navigation
    - _Requirements: 6.2, 6.5_

- [ ] 11. Implement Citizen Portal - Multi-step form for letter submission
  - [ ] 11.1 Create multi-step form structure
    - Implement `Citizen/SubmissionForm.vue` with 3 steps (Data Diri, Data Tambahan & Upload, Review)
    - Add StepIndicator at top of form
    - Implement step navigation with validation
    - _Requirements: 7.1, 7.2, 7.9_

  - [ ] 11.2 Implement Step 1: Auto-fill identity data
    - Auto-fill nama, NIK, tempat/tanggal lahir from authenticated user session
    - Lock auto-filled fields from editing
    - _Requirements: 7.3, 7.4_

  - [ ] 11.3 Implement Step 2: Dynamic form fields and file uploads
    - Dynamically render form fields based on letter type schema from Backend API
    - Implement file upload inputs for required documents
    - Validate file uploads (formats: PDF, JPG, PNG; max size: 2MB per file)
    - Display validation errors inline
    - _Requirements: 7.5, 7.6, 7.7_

  - [ ] 11.4 Implement Step 3: Review and submit
    - Display summary of all entered data and uploaded files
    - Implement submit button with loading state
    - POST form data to Backend API `/api/citizen/pengajuan-surat`
    - Display success Toast and redirect to dashboard on success
    - Display error Toast and allow retry on failure
    - _Requirements: 7.8, 7.10, 7.11, 7.12_

  - [ ]* 11.5 Write integration tests for multi-step form
    - Test step navigation, auto-fill, dynamic fields, file validation, submission
    - _Requirements: 7.1, 7.9, 7.10_

- [ ] 12. Implement Citizen Portal - Submission history and details
  - [ ] 12.1 Create submission detail page
    - Implement `Citizen/SubmissionDetail.vue` with detail view layout
    - Fetch submission detail from Backend API `GET /api/citizen/pengajuan-surat/{id}`
    - Display all form data, uploaded documents, and tracking timeline
    - _Requirements: 8.1, 8.2, 8.3_

  - [ ] 12.2 Implement document download and rejection display
    - Display download button for final PDF when status is "Selesai"
    - Implement PDF download from Backend API provided URL
    - Display rejection reason prominently when status is "Ditolak"
    - Add back navigation to dashboard
    - _Requirements: 8.4, 8.5, 8.6, 8.7_

- [ ] 13. Checkpoint - Citizen portal complete
  - Ensure all tests pass, verify citizen portal is functional end-to-end, ask the user if questions arise.

- [ ] 14. Implement Admin Dashboard - Data table for Penduduk
  - [ ] 14.1 Create penduduk data table page
    - Implement `Admin/Penduduk.vue` with AdminLayout
    - Create interactive data table component with columns (NIK, Nama, TTL, Jenis Kelamin, Alamat, Status)
    - Implement responsive card list layout on mobile (<768px)
    - _Requirements: 10.1, 10.6, 16.3_

  - [ ] 14.2 Implement search, filter, and pagination
    - Add search input with 300ms debounced search-as-you-type
    - Add filter dropdown for dusun/RT/RW
    - Implement pagination controls (previous, next, page numbers) without page refresh
    - Fetch data from Backend API `GET /api/admin/penduduk` with query params
    - Reset to page 1 when filter is applied
    - Display SkeletonLoader while loading
    - Display row count and total records
    - _Requirements: 10.2, 10.3, 10.4, 10.5, 10.7, 10.8, 10.9, 10.10, 13.9_

  - [ ]* 14.3 Write integration tests for penduduk table
    - Test search, filter, pagination, responsive layout
    - _Requirements: 10.1, 10.3, 10.5_

- [ ] 15. Implement Admin Dashboard - Submission verification
  - [ ] 15.1 Create pengajuan surat queue list
    - Implement `Admin/PengajuanSurat.vue` with submission list
    - Add status filter tabs (Pending, Diproses, Selesai, Ditolak)
    - Fetch data from Backend API `GET /api/admin/pengajuan-surat`
    - _Requirements: 11.1, 11.2_

  - [ ] 15.2 Create verification view with split layout
    - Implement split layout on desktop (form data left, documents right)
    - Implement stacked layout on mobile (form data top, documents bottom)
    - Display applicant identity, submitted form data, uploaded document previews
    - _Requirements: 11.3, 11.4, 11.5, 16.4_

  - [ ] 15.3 Implement approve and reject actions
    - Add "Setujui" (approve) and "Tolak" (reject) buttons
    - Show Modal with textarea for rejection reason when "Tolak" clicked
    - Show confirmation Modal before approving
    - Send request to Backend API `PATCH /api/admin/pengajuan-surat/{id}/verify`
    - Display success Toast and update list on success
    - Display error Toast on failure
    - _Requirements: 11.6, 11.7, 11.8, 11.9, 11.10, 11.11_

  - [ ]* 15.4 Write integration tests for verification flow
    - Test status filtering, verification actions, modal interactions
    - _Requirements: 11.1, 11.9_

- [ ] 16. Implement Admin Dashboard - Content management for Informasi Publik
  - [ ] 16.1 Create informasi publik management page
    - Implement `Admin/InformasiPublik.vue` with article list and "Buat Baru" button
    - Fetch articles from Backend API `GET /api/admin/informasi-publik`
    - Display article list with edit and delete actions
    - _Requirements: 12.1, 12.2_

  - [ ] 16.2 Create article form with rich text editor
    - Implement article form with fields (judul, kategori, cover image upload, konten, is_published toggle)
    - Integrate rich text editor for konten field with basic formatting (bold, italic, list, link)
    - Support create mode and edit mode with pre-filled data
    - _Requirements: 12.3, 12.4, 12.5, 12.6_

  - [ ] 16.3 Implement article save and delete
    - POST/PUT to Backend API `/api/admin/informasi-publik` on form submit
    - Display success Toast and refresh list on save success
    - Show confirmation Modal before deleting article
    - DELETE via Backend API on confirmation
    - _Requirements: 12.7, 12.8, 12.9_

  - [ ]* 16.4 Write integration tests for article management
    - Test article CRUD operations, rich text editor, image upload
    - _Requirements: 12.2, 12.7_

- [ ] 17. Checkpoint - Admin dashboard complete
  - Ensure all tests pass, verify admin features are functional, ask the user if questions arise.

- [ ] 18. Implement performance and optimization features
  - [ ] 18.1 Configure code splitting and lazy loading
    - Configure Vite for code splitting and tree-shaking
    - Implement lazy loading for route components
    - Add dynamic imports for heavy components
    - _Requirements: 13.1, 13.3_

  - [ ] 18.2 Implement image optimization
    - Add lazy loading with placeholder blur effect for images
    - Compress images to WebP format with JPEG/PNG fallback
    - _Requirements: 13.2, 13.4_

  - [ ] 18.3 Configure request deduplication and caching
    - Implement request deduplication to prevent duplicate API calls
    - Add in-memory caching for API responses with configurable TTL
    - Implement cache invalidation on mutations
    - _Requirements: 13.10, 19.2, 19.3, 19.5_

  - [ ] 18.4 Optimize bundle size
    - Use native CSS transitions instead of animation libraries
    - Minimize CSS/JS bundle size with compression
    - Analyze bundle size and verify threshold compliance
    - _Requirements: 13.1, 13.7, 20.6_

  - [ ]* 18.5 Run Lighthouse performance audit
    - Generate Lighthouse report for mobile performance
    - Verify score above 90 for performance and accessibility
    - _Requirements: 13.5, 13.6_

- [ ] 19. Implement PWA functionality and offline support
  - [ ] 19.1 Configure service worker and PWA manifest
    - Register service worker for PWA functionality
    - Create web app manifest with app name, icons, theme colors
    - Cache static assets (CSS, JS, fonts, icons) for offline access
    - _Requirements: 18.1, 18.2, 18.3_

  - [ ] 19.2 Implement offline page caching and fallback
    - Cache previously viewed pages for offline viewing
    - Create custom offline fallback page for uncached pages
    - Display installable prompt for adding app to home screen
    - _Requirements: 18.4, 18.5, 18.7_

  - [ ] 19.3 Implement background sync for offline form submissions
    - Queue failed POST/PUT/PATCH requests when offline
    - Retry queued requests when back online (background sync)
    - Display offline notice when user is offline and disable form submissions
    - _Requirements: 18.6, 18.9_

- [ ] 20. Implement accessibility features
  - [ ] 20.1 Ensure semantic HTML and ARIA attributes
    - Use semantic HTML elements (header, nav, main, article, section, footer)
    - Add alt text for all informational images
    - Add ARIA labels and roles where semantic HTML is insufficient
    - _Requirements: 15.1, 15.2, 15.6_

  - [ ] 20.2 Implement keyboard navigation and focus management
    - Support keyboard navigation for all interactive elements
    - Provide visible focus indicators for keyboard navigation
    - Ensure minimum touch target size 44x44px on mobile
    - _Requirements: 15.3, 15.4, 15.5_

  - [ ] 20.3 Optimize typography and prevent layout shift
    - Ensure minimum body text size of 16px on mobile viewport
    - Prevent layout shift (CLS) with skeleton loaders and fixed dimensions
    - _Requirements: 15.7, 15.8_

- [ ] 21. Implement responsive design validation
  - [ ] 21.1 Test and refine Mobile-First layouts
    - Verify all pages adapt from sidebar to drawer at 768px breakpoint
    - Verify data tables convert to card lists at 768px breakpoint
    - Verify multi-column layouts become single column at 768px breakpoint
    - Test across viewport sizes (375px-767px mobile, 768px-1023px tablet, 1024px+ desktop)
    - _Requirements: 16.1, 16.2, 16.3, 16.4, 16.9_

  - [ ] 21.2 Optimize mobile touch interactions
    - Ensure no horizontal scrolling is required
    - Optimize spacing between tappable elements
    - Use appropriate input types for mobile keyboards (tel for NIK, email for email)
    - Verify layout adapts on device rotation without losing state
    - _Requirements: 16.5, 16.6, 16.7, 16.8_

- [ ] 22. Implement error handling and user feedback
  - [ ] 22.1 Add inline validation and error feedback
    - Display inline validation errors below form fields as user types or on blur
    - Display EmptyState with helpful message and illustration when no data
    - Provide clear call-to-action in error states (e.g., "Retry" button)
    - _Requirements: 17.5, 17.6, 17.9_

  - [ ] 22.2 Implement client-side error logging
    - Add global error handlers for uncaught errors and unhandled promise rejections
    - Log errors to console for debugging
    - _Requirements: 17.10_

- [ ] 23. Configure build and deployment
  - [ ] 23.1 Configure production build optimization
    - Use Vite with optimized production build configuration
    - Minify and compress all assets in production build
    - Generate source maps for debugging
    - Output static assets with content hash for cache busting
    - _Requirements: 20.1, 20.2, 20.3, 20.5_

  - [ ] 23.2 Configure environment variables
    - Use environment variables for configuration (API URL, app name)
    - Document required environment variables in `.env.example`
    - _Requirements: 20.4_

  - [ ] 23.3 Prepare deployment configuration
    - Configure static file deployment for CDN (Cloudflare Pages/Vercel/Netlify)
    - Create Dockerfile for VPS deployment if needed
    - Include production-ready nginx configuration if using Docker
    - _Requirements: 20.8, 20.9, 20.10_

  - [ ]* 23.4 Generate Lighthouse CI report
    - Integrate Lighthouse CI into build process
    - Analyze and document performance metrics
    - _Requirements: 20.7_

- [ ] 24. Final checkpoint and integration testing
  - [ ] 24.1 Run comprehensive end-to-end tests
    - Test complete user journeys (public browsing, citizen login and submission, admin verification)
    - Verify all three portals work seamlessly together
    - Test on real mobile devices with slow network (3G simulation)
    - _Requirements: All_

  - [ ]* 24.2 Perform manual accessibility testing
    - Test with screen readers (NVDA, JAWS, VoiceOver)
    - Verify keyboard-only navigation works throughout
    - Document accessibility compliance (WCAG AA)
    - _Requirements: 15.1, 15.2, 15.4_

  - [ ] 24.3 Final integration and wiring
    - Ensure all components are properly integrated with backend API
    - Verify all routes are configured correctly with Inertia.js
    - Test authentication flow end-to-end
    - Verify error handling works across all scenarios
    - _Requirements: All_

- [ ] 25. Documentation and handoff
  - Create README with setup instructions, environment variables, and deployment guide
  - Document component API and usage examples
  - Create developer guide for extending the application
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional test-related sub-tasks and can be skipped for faster MVP delivery
- Each task references specific requirements for traceability and validation
- Checkpoints (tasks 5, 8, 13, 17) ensure incremental validation and provide opportunities to address issues before proceeding
- The implementation follows a layered approach: foundation (design system, components) → portals (public, citizen, admin) → cross-cutting concerns (PWA, optimization, accessibility)
- Component tests validate individual behavior; integration tests validate data flow; E2E tests validate complete user journeys
- All form submissions maintain user data on validation failure to prevent data loss
- The mobile-first approach ensures optimal experience for the majority of users (>80% mobile access in rural Indonesia)
- Performance optimization (lazy loading, code splitting, caching) is critical for low-bandwidth 3G connections
- PWA functionality provides offline resilience for areas with unstable internet connectivity
- Accessibility compliance (WCAG AA) ensures inclusivity for all villagers

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1"] },
    { "id": 1, "tasks": ["2.1", "2.3", "2.7", "2.8", "2.9"] },
    { "id": 2, "tasks": ["2.2", "2.4", "2.6", "3.1", "3.3", "3.5"] },
    { "id": 3, "tasks": ["2.5", "3.2", "3.4", "4.1", "4.4", "4.5"] },
    { "id": 4, "tasks": ["4.2", "4.3"] },
    { "id": 5, "tasks": ["6.1", "6.2"] },
    { "id": 6, "tasks": ["6.3", "7.1"] },
    { "id": 7, "tasks": ["7.2", "7.3", "7.4", "7.5"] },
    { "id": 8, "tasks": ["9.1"] },
    { "id": 9, "tasks": ["9.2", "10.1"] },
    { "id": 10, "tasks": ["9.3", "10.2", "10.3"] },
    { "id": 11, "tasks": ["10.4", "11.1"] },
    { "id": 12, "tasks": ["11.2"] },
    { "id": 13, "tasks": ["11.3"] },
    { "id": 14, "tasks": ["11.4"] },
    { "id": 15, "tasks": ["11.5", "12.1"] },
    { "id": 16, "tasks": ["12.2"] },
    { "id": 17, "tasks": ["14.1"] },
    { "id": 18, "tasks": ["14.2", "15.1"] },
    { "id": 19, "tasks": ["14.3", "15.2"] },
    { "id": 20, "tasks": ["15.3", "16.1"] },
    { "id": 21, "tasks": ["15.4", "16.2"] },
    { "id": 22, "tasks": ["16.3"] },
    { "id": 23, "tasks": ["16.4", "18.1", "18.2"] },
    { "id": 24, "tasks": ["18.3", "18.4", "19.1", "20.1"] },
    { "id": 25, "tasks": ["18.5", "19.2", "20.2", "20.3"] },
    { "id": 26, "tasks": ["19.3", "21.1"] },
    { "id": 27, "tasks": ["21.2", "22.1"] },
    { "id": 28, "tasks": ["22.2", "23.1"] },
    { "id": 29, "tasks": ["23.2", "23.3"] },
    { "id": 30, "tasks": ["23.4", "24.1"] },
    { "id": 31, "tasks": ["24.2", "24.3"] }
  ]
}
```
