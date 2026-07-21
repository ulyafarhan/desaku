<template>
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
        <slot name="image" />
        <div class="p-5">
            <slot name="content" />
            <slot />
        </div>
    </div>
</template>
