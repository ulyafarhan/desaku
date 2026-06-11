<style>
    /* Custom Filament Premium Styles override */
    
    /* Smooth page scroll & transitions */
    html {
        scroll-behavior: smooth;
    }
    
    /* Global scrollbar hiding for a clean visual presentation */
    * {
        -ms-overflow-style: none !important;  /* IE and Edge */
        scrollbar-width: none !important;  /* Firefox */
    }
    
    *::-webkit-scrollbar {
        display: none !important; /* Chrome, Safari, Opera */
        width: 0 !important;
        height: 0 !important;
    }

    /* 1. Glassmorphism Sidebar */
    .fi-sidebar {
        background-color: rgba(255, 255, 255, 0.96) !important;
        backdrop-filter: blur(16px) !important;
        border-right: 1px solid rgba(226, 232, 240, 0.8) !important;
    }
    
    .dark .fi-sidebar {
        background-color: rgba(15, 23, 42, 0.96) !important;
        border-right: 1px solid rgba(51, 65, 85, 0.8) !important;
    }

    /* Logo layout constraints for sidebar open/collapsed states */
    .fi-sidebar-header .fi-logo {
        height: auto !important;
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: flex-start !important;
        width: 100% !important;
        gap: 0.625rem !important;
    }

    /* Center logo when sidebar is collapsed */
    body:not(.fi-sidebar-open) .fi-sidebar-header .fi-logo {
        justify-content: center !important;
    }

    .fi-sidebar-header .fi-logo > div {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
    }
    
    /* Sidebar Navigation Items */
    .fi-sidebar-item-button {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border-radius: 0.75rem !important;
        margin: 0.25rem 0.75rem !important;
        padding-top: 0.625rem !important;
        padding-bottom: 0.625rem !important;
    }
    
    .fi-sidebar-item-button:hover {
        background-color: rgba(20, 184, 166, 0.08) !important;
    }
    
    .fi-sidebar-item-active {
        background: linear-gradient(135deg, rgba(13, 148, 136, 0.15), rgba(20, 184, 166, 0.05)) !important;
        border-left: 3px solid #0D9488 !important;
        font-weight: 700 !important;
    }
    
    .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #0D9488 !important;
    }
    
    /* 2. Premium Widget Stats Card Hover & Elevation */
    .fi-wi-stats-overview-stat {
        position: relative !important;
        overflow: hidden !important;
        background: #ffffff !important;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01) !important;
    }
    
    .dark .fi-wi-stats-overview-stat {
        background: #0f172a !important;
        border: 1px solid rgba(51, 65, 85, 0.5) !important;
    }
    
    /* 3. Table Rows & Custom scrollbars */
    .fi-ta-table {
        border-collapse: separate !important;
        border-spacing: 0 0.5rem !important;
    }
    
    .fi-ta-row {
        background-color: #ffffff !important;
        border-radius: 0.75rem !important;
        transition: all 0.2s ease-in-out !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.01) !important;
    }
    
    .dark .fi-ta-row {
        background-color: #1e293b !important;
    }
    
    .fi-ta-row:hover {
        background-color: rgba(20, 184, 166, 0.02) !important;
    }
    
    .fi-ta-header-cell,
    .fi-ta-actions-header-cell,
    .fi-ta-table th {
        background-color: rgba(248, 250, 252, 0.8) !important;
        text-transform: uppercase !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.05em !important;
        color: #475569 !important;
        border-bottom: 2px solid rgba(226, 232, 240, 0.8) !important;
    }
    
    .dark .fi-ta-header-cell,
    .dark .fi-ta-actions-header-cell,
    .dark .fi-ta-table th {
        background-color: rgba(15, 23, 42, 0.8) !important;
        color: #94a3b8 !important;
        border-bottom: 2px solid rgba(51, 65, 85, 0.8) !important;
    }
    
    /* Table scroll container overflow alignment */
    .fi-ta-content {
        overflow-x: auto !important;
    }
    
    /* 4. Form inputs focus glow */
    .fi-fo-component input:focus, 
    .fi-fo-component select:focus, 
    .fi-fo-component textarea:focus {
        border-color: #0D9488 !important;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1) !important;
        transition: all 0.2s ease !important;
    }
    
    /* Buttons Hover Lift */
    .fi-ac-action, .fi-fo-actions button, .fi-ta-actions button {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border-radius: 0.5rem !important;
    }
    


    /* Force show resource icons for grouped sidebar items instead of bullet dots */
    .fi-sidebar-item-icon {
        display: inline-block !important;
    }
    
    .fi-sidebar-item-grouped-border {
        display: none !important;
    }
</style>
