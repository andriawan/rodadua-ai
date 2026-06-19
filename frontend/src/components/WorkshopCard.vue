<script setup lang="ts">
import { computed } from 'vue'
import type { Workshop } from '../types/workshop'
import BaseCard from './BaseCard.vue'

interface Props {
  workshop: Workshop
}

const props = defineProps<Props>()

const emit = defineEmits<{
  (e: 'click', workshop: Workshop): void
  (e: 'contact', workshop: Workshop): void
}>()

const stars = computed(() => {
  const fullStars = Math.floor(props.workshop.rating)
  const hasHalfStar = props.workshop.rating % 1 >= 0.5
  return {
    full: fullStars,
    half: hasHalfStar ? 1 : 0,
    empty: 5 - fullStars - (hasHalfStar ? 1 : 0),
  }
})
</script>

<template>
  <BaseCard
    hoverable
    padding="md"
    class="flex flex-col justify-between h-full border border-neutral-200 hover:border-primary-200 hover:ring-1 hover:ring-primary-100 transition-all duration-300"
    @click="emit('click', workshop)"
  >
    <div class="flex flex-col gap-3">
      <!-- Title & Rating -->
      <div>
        <div class="flex items-start justify-between gap-2">
          <h3 class="font-bold text-neutral-900 text-lg hover:text-primary-600 transition-colors duration-200 leading-snug">
            {{ workshop.name }}
          </h3>
          <!-- Specialist Indicator -->
          <span
            v-if="workshop.specialist_motorcycle_count > 5"
            class="flex-shrink-0 text-[10px] bg-primary-50 text-primary-700 px-2 py-0.5 font-bold uppercase rounded border border-primary-200 tracking-wider"
          >
            Top Spec
          </span>
        </div>

        <!-- Rating Stars -->
        <div class="flex items-center gap-1.5 mt-1">
          <div class="flex items-center text-amber-500">
            <!-- Full Stars -->
            <svg
              v-for="i in stars.full"
              :key="`full-${i}`"
              class="h-4 w-4 fill-current"
              viewBox="0 0 20 20"
            >
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            <!-- Half Star -->
            <svg
              v-for="i in stars.half"
              :key="`half-${i}`"
              class="h-4 w-4 text-amber-500"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <defs>
                <linearGradient id="halfGrad">
                  <stop offset="50%" stop-color="currentColor" />
                  <stop offset="50%" stop-color="#e5e5e5" stop-opacity="1" />
                </linearGradient>
              </defs>
              <path fill="url(#halfGrad)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            <!-- Empty Stars -->
            <svg
              v-for="i in stars.empty"
              :key="`empty-${i}`"
              class="h-4 w-4 text-neutral-200 fill-current"
              viewBox="0 0 20 20"
            >
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </div>
          <span class="text-xs font-bold text-neutral-800">{{ workshop.rating.toFixed(1) }}</span>
          <span class="text-xs text-neutral-400">({{ workshop.total_reviews }} reviews)</span>
        </div>
      </div>

      <p v-if="workshop.description" class="text-xs text-neutral-500 line-clamp-2 leading-relaxed">
        {{ workshop.description }}
      </p>

      <!-- Location & Operating Hours -->
      <div class="flex flex-col gap-1.5 text-xs text-neutral-600 mt-1.5">
        <span class="flex items-start gap-1.5">
          <span class="text-neutral-400">📍</span>
          <span class="leading-tight">{{ workshop.address }}, {{ workshop.city }}</span>
        </span>
        <span class="flex items-center gap-1.5">
          <span class="text-neutral-400">🕒</span>
          <span>{{ workshop.operating_hours }}</span>
        </span>
        <span v-if="workshop.is_open_weekends" class="flex items-center gap-1.5">
          <span class="text-neutral-400">📅</span>
          <span class="text-secondary-600 font-semibold">Open on weekends</span>
        </span>
      </div>

      <!-- Services List -->
      <div class="flex flex-wrap gap-1 mt-2">
        <span
          v-for="service in workshop.services_offered.slice(0, 3)"
          :key="service"
          class="bg-neutral-100/80 text-neutral-600 px-2 py-0.5 rounded text-[10px] font-medium capitalize"
        >
          {{ service.replace('_', ' ') }}
        </span>
        <span
          v-if="workshop.services_offered.length > 3"
          class="bg-neutral-100 text-neutral-500 px-2 py-0.5 rounded text-[10px] font-bold"
        >
          +{{ workshop.services_offered.length - 3 }} more
        </span>
      </div>
    </div>

    <!-- Actions Area -->
    <div class="flex gap-2.5 mt-4 pt-3.5 border-t border-neutral-100 justify-end">
      <button
        type="button"
        class="text-xs font-bold text-neutral-500 hover:text-neutral-700 hover:underline transition-colors focus:outline-none"
        @click.stop="emit('contact', workshop)"
      >
        Contact Info
      </button>
    </div>
  </BaseCard>
</template>
