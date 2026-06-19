<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  title?: string
  message: string
  dismissible?: boolean
  retryAction?: () => void
  retryText?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Error Occurred',
  dismissible: false,
  retryText: 'Retry',
})

const emit = defineEmits<{
  (e: 'dismiss'): void
}>()
</script>

<template>
  <div
    class="flex gap-3 p-4 rounded-lg bg-danger-50 border border-danger-500/20 text-danger-700 shadow-sm transition-all duration-300"
    role="alert"
  >
    <!-- Error Icon -->
    <div class="flex-shrink-0 mt-0.5">
      <svg
        class="h-5 w-5 text-danger-500"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          fill-rule="evenodd"
          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
          clip-rule="evenodd"
        />
      </svg>
    </div>

    <!-- Error Text Content -->
    <div class="flex-grow flex flex-col gap-1">
      <h3 v-if="title" class="text-sm font-semibold tracking-wide leading-5">
        {{ title }}
      </h3>
      <div class="text-xs leading-relaxed opacity-95">
        {{ message }}
      </div>

      <!-- Optional Retry Button -->
      <div v-if="retryAction" class="mt-2.5">
        <button
          type="button"
          class="text-xs font-semibold underline hover:text-danger-500 transition-colors duration-150 focus:outline-none"
          @click="retryAction"
        >
          {{ retryText }}
        </button>
      </div>
    </div>

    <!-- Dismiss Button -->
    <div v-if="dismissible" class="flex-shrink-0">
      <button
        type="button"
        class="inline-flex rounded-md p-1.5 text-danger-500 hover:bg-danger-100/50 focus:outline-none transition-colors duration-200"
        @click="emit('dismiss')"
      >
        <span class="sr-only">Dismiss</span>
        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path
            fill-rule="evenodd"
            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
            clip-rule="evenodd"
          />
        </svg>
      </button>
    </div>
  </div>
</template>
