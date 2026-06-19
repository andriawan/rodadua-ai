<script setup lang="ts">
interface Props {
  size?: 'sm' | 'md' | 'lg' | 'xl'
  overlay?: boolean
  label?: string
}

withDefaults(defineProps<Props>(), {
  size: 'md',
  overlay: false,
  label: 'Loading...',
})
</script>

<template>
  <div
    :class="[
      'flex flex-col items-center justify-center gap-3',
      overlay ? 'fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50' : 'w-full py-8',
    ]"
  >
    <div class="relative flex items-center justify-center">
      <!-- Glow effect for a premium look -->
      <div
        :class="[
          'absolute rounded-full border-4 border-transparent border-t-primary-400/40 animate-pulse',
          size === 'sm' ? 'h-8 w-8' : '',
          size === 'md' ? 'h-12 w-12' : '',
          size === 'lg' ? 'h-16 w-16' : '',
          size === 'xl' ? 'h-24 w-24' : '',
        ]"
      ></div>
      
      <!-- Primary spinning arc -->
      <div
        :class="[
          'rounded-full border-4 border-neutral-200 border-t-primary-500 animate-spin',
          size === 'sm' ? 'h-6 w-6 border-2' : '',
          size === 'md' ? 'h-10 w-10' : '',
          size === 'lg' ? 'h-14 w-14' : '',
          size === 'xl' ? 'h-20 w-20 border-[6px]' : '',
        ]"
      ></div>
    </div>

    <!-- Optional Text Label -->
    <span
      v-if="label"
      :class="[
        'font-medium text-neutral-600 tracking-wide select-none',
        size === 'sm' ? 'text-xs' : 'text-sm',
        overlay ? 'text-white drop-shadow' : '',
      ]"
    >
      {{ label }}
    </span>
  </div>
</template>
