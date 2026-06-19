<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  hoverable?: boolean
  padding?: 'none' | 'sm' | 'md' | 'lg'
  bgClass?: string
  borderClass?: string
}

const props = withDefaults(defineProps<Props>(), {
  hoverable: false,
  padding: 'md',
  bgClass: 'bg-white',
  borderClass: 'border-neutral-200',
})

const paddingClasses = computed(() => {
  switch (props.padding) {
    case 'none':
      return ''
    case 'sm':
      return 'p-3'
    case 'lg':
      return 'p-6'
    case 'md':
    default:
      return 'p-4'
  }
})
</script>

<template>
  <div
    :class="[
      'rounded-lg border shadow-sm transition-all duration-300 overflow-hidden',
      bgClass,
      borderClass,
      hoverable ? 'hover:shadow-md hover:-translate-y-0.5 cursor-pointer' : '',
    ]"
  >
    <!-- Card Header Slot -->
    <div
      v-if="$slots.header"
      class="px-4 py-3 border-b border-neutral-100 flex items-center justify-between"
    >
      <slot name="header" />
    </div>

    <!-- Card Content -->
    <div :class="paddingClasses">
      <slot />
    </div>

    <!-- Card Footer Slot -->
    <div
      v-if="$slots.footer"
      class="px-4 py-3 border-t border-neutral-100 bg-neutral-50/50 flex items-center justify-end gap-2"
    >
      <slot name="footer" />
    </div>
  </div>
</template>
