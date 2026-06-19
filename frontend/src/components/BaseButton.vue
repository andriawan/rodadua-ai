<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  variant?: 'primary' | 'secondary' | 'danger' | 'warning' | 'success' | 'info' | 'outline' | 'neutral'
  size?: 'sm' | 'md' | 'lg'
  disabled?: boolean
  loading?: boolean
  type?: 'button' | 'submit' | 'reset'
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  size: 'md',
  disabled: false,
  loading: false,
  type: 'button',
})

const emit = defineEmits<{
  (e: 'click', event: MouseEvent): void
}>()

const baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed select-none rounded-md'

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'px-3 py-1.5 text-xs'
    case 'lg':
      return 'px-6 py-3 text-lg'
    case 'md':
    default:
      return 'px-4 py-2 text-sm'
  }
})

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'secondary':
      return 'bg-secondary-500 hover:bg-secondary-600 active:bg-secondary-700 text-white focus:ring-secondary-500'
    case 'danger':
      return 'bg-danger-500 hover:bg-danger-700 active:bg-danger-700 text-white focus:ring-danger-500'
    case 'warning':
      return 'bg-warning-500 hover:bg-warning-700 active:bg-warning-700 text-white focus:ring-warning-500'
    case 'success':
      return 'bg-success-500 hover:bg-success-700 active:bg-success-700 text-white focus:ring-success-500'
    case 'info':
      return 'bg-info-500 hover:bg-info-700 active:bg-info-700 text-white focus:ring-info-500'
    case 'outline':
      return 'border border-neutral-300 bg-transparent hover:bg-neutral-50 active:bg-neutral-100 text-neutral-700 focus:ring-neutral-500'
    case 'neutral':
      return 'bg-neutral-100 hover:bg-neutral-200 active:bg-neutral-300 text-neutral-800 focus:ring-neutral-500'
    case 'primary':
    default:
      return 'bg-primary-500 hover:bg-primary-600 active:bg-primary-700 text-white focus:ring-primary-500'
  }
})

function handleClick(event: MouseEvent) {
  if (!props.disabled && !props.loading) {
    emit('click', event)
  }
}
</script>

<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="[baseClasses, sizeClasses, variantClasses]"
    @click="handleClick"
  >
    <svg
      v-if="loading"
      class="animate-spin -ml-1 mr-2 h-4 w-4 text-current"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      />
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      />
    </svg>
    <slot />
  </button>
</template>
