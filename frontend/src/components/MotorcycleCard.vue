<script setup lang="ts">
import { computed } from 'vue'
import type { Motorcycle } from '../types/motorcycle'
import BaseCard from './BaseCard.vue'

interface Props {
  motorcycle: Motorcycle
  loadingFavorite?: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  (e: 'click', motorcycle: Motorcycle): void
  (e: 'toggle-favorite', id: number): void
  (e: 'edit', motorcycle: Motorcycle): void
}>()

const formattedOdometer = computed(() => {
  return new Intl.NumberFormat('id-ID', { style: 'decimal' }).format(props.motorcycle.odometer_km) + ' km'
})

const transmissionLabel = computed(() => {
  return props.motorcycle.transmission === 'manual' ? 'Manual' : 'Automatic'
})

const statusBadgeClasses = computed(() => {
  switch (props.motorcycle.status) {
    case 'active':
      return 'bg-success-50 text-success-700 border-success-500/20'
    case 'inactive':
      return 'bg-neutral-100 text-neutral-600 border-neutral-300'
    case 'for_sale':
      return 'bg-info-50 text-info-700 border-info-500/20'
    default:
      return 'bg-neutral-50 text-neutral-600 border-neutral-200'
  }
})
</script>

<template>
  <BaseCard
    hoverable
    padding="none"
    class="flex flex-col h-full group"
    @click="emit('click', motorcycle)"
  >
    <!-- Card Top Header Image Area (Stylized Design) -->
    <div class="relative h-32 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-600 flex items-center justify-center text-white overflow-hidden">
      <!-- Grid Overlay -->
      <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px] opacity-10"></div>
      
      <!-- Abstract Motorcycle Icon -->
      <svg
        class="h-16 w-16 text-white/90 transform group-hover:scale-110 transition-transform duration-300"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="1.5"
          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
        />
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="1.5"
          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
        />
      </svg>

      <!-- Badges (Favorite & Status) -->
      <div class="absolute top-3 left-3">
        <span :class="['px-2.5 py-0.5 text-xs font-semibold rounded-full border', statusBadgeClasses]">
          {{ motorcycle.status.replace('_', ' ') }}
        </span>
      </div>

      <button
        type="button"
        class="absolute top-3 right-3 p-1.5 rounded-full bg-white/20 backdrop-blur-md text-white hover:bg-white/40 active:scale-95 transition-all duration-200"
        @click.stop="emit('toggle-favorite', motorcycle.id)"
      >
        <svg
          class="h-4.5 w-4.5"
          :class="motorcycle.is_favorite ? 'fill-danger-500 text-danger-500' : 'text-white'"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
          />
        </svg>
      </button>
    </div>

    <!-- Card Description Details -->
    <div class="p-4 flex-grow flex flex-col justify-between gap-3">
      <div>
        <h4 class="text-neutral-500 text-xs font-bold tracking-widest uppercase">
          {{ motorcycle.brand }}
        </h4>
        <h3 class="text-neutral-900 font-bold text-lg group-hover:text-primary-600 transition-colors duration-200">
          {{ motorcycle.model }} ({{ motorcycle.year }})
        </h3>
        <p class="text-xs text-neutral-400 mt-0.5 uppercase tracking-wide">
          {{ motorcycle.license_plate }}
        </p>
      </div>

      <div class="grid grid-cols-2 gap-2 text-xs border-t border-neutral-100 pt-3">
        <div class="flex flex-col">
          <span class="text-neutral-400">Odometer</span>
          <span class="font-semibold text-neutral-700">{{ formattedOdometer }}</span>
        </div>
        <div class="flex flex-col">
          <span class="text-neutral-400">Engine Size</span>
          <span class="font-semibold text-neutral-700">{{ motorcycle.engine_cc }} cc</span>
        </div>
        <div class="flex flex-col">
          <span class="text-neutral-400">Transmission</span>
          <span class="font-semibold text-neutral-700">{{ transmissionLabel }}</span>
        </div>
        <div class="flex flex-col">
          <span class="text-neutral-400">Engine Type</span>
          <span class="font-semibold text-neutral-700 capitalize">{{ motorcycle.engine_type.replace('_', ' ') }}</span>
        </div>
      </div>

      <div class="flex justify-end gap-2 mt-2 pt-2 border-t border-neutral-50">
        <button
          type="button"
          class="px-2.5 py-1 text-xs font-semibold rounded text-neutral-600 bg-neutral-50 hover:bg-neutral-100 transition-colors duration-150"
          @click.stop="emit('edit', motorcycle)"
        >
          Edit
        </button>
      </div>
    </div>
  </BaseCard>
</template>
