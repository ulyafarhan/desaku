<div x-data="{}" style="display: flex !important; flex-direction: row !important; align-items: center !important; gap: 10px !important; width: 100% !important; height: auto !important; overflow: hidden;">
    <!-- Native SVG building icon with strict dimensions -->
    <svg class="text-teal-600 transition-transform duration-300 group-hover:scale-110" 
         width="24" 
         height="24" 
         viewBox="0 0 24 24" 
         fill="none" 
         stroke="currentColor" 
         stroke-width="2" 
         stroke-linecap="round" 
         stroke-linejoin="round"
         style="width: 24px !important; height: 24px !important; min-width: 24px !important; min-height: 24px !important; flex-shrink: 0;"
    >
        <path d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.053.244-7.5 1.725V21" />
    </svg>
    
    <!-- Brand text block: safely hidden via Alpine when sidebar is collapsed on desktop -->
    <div class="leading-none" 
         x-show="typeof $store.sidebar !== 'undefined' ? $store.sidebar.isOpen : true"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 transform -translate-x-2"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         style="white-space: nowrap; display: flex; flex-direction: column; gap: 2px;"
    >
        <span class="text-sm font-bold tracking-tight text-slate-800 dark:text-white transition-colors duration-300 group-hover:text-teal-700" style="font-size: 14px; line-height: 1.1;">
            Desaku<span class="text-teal-600 font-black">Admin</span>
        </span>
        <p class="text-[8px] font-semibold tracking-wider text-teal-600 uppercase" style="font-size: 8px; line-height: 1; margin: 0;">Gampong Udeung</p>
    </div>
</div>
