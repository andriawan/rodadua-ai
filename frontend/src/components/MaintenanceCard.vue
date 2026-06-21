<script setup lang="ts">
import { computed } from 'vue'
import type { Maintenance } from '../types/maintenance'
import BaseCard from './BaseCard.vue'

interface Props {
  record: Maintenance
}

const props = defineProps<Props>()

const formattedCost = computed(() => {
  if (props.record.cost === undefined || props.record.cost === null) return 'N/A'
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(props.record.cost)
})

const formattedDate = computed(() => {
  return new Date(props.record.maintenance_date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
})

const typeLabel = computed(() => {
  return props.record.type
    .split('_')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
})

const statusBadgeClasses = computed(() => {
  switch (props.record.status) {
    case 'completed':
      return 'bg-success-50 text-success-700 border-success-500/20'
    case 'scheduled':
      return 'bg-info-50 text-info-700 border-info-500/20'
    case 'pending':
      return 'bg-warning-50 text-warning-700 border-warning-500/20'
    default:
      return 'bg-neutral-50 text-neutral-600 border-neutral-200'
  }
})
</script>

<template>
  <BaseCard
padding="md" class="border-l-4" :class="[
    record.status === 'completed' ? 'border-l-success-500' : '',
    record.status === 'scheduled' ? 'border-l-info-500' : '',
    record.status === 'pending' ? 'border-l-warning-500' : '',
  ]">
    <template #header>
      <div class="flex items-center gap-2">
        <h4 class="font-bold text-neutral-900 text-base">
          {{ typeLabel }}
        </h4>
        <span :class="['px-2.5 py-0.5 text-xs font-semibold rounded-full border', statusBadgeClasses]">
          {{ record.status }}
        </span>
      </div>
      <span class="text-xs text-neutral-500 font-medium">
        {{ formattedDate }}
      </span>
    </template>

    <div class="flex flex-col gap-3">
      <p class="text-sm text-neutral-600 leading-relaxed">
        {{ record.description }}
      </p>

      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-neutral-50 rounded-lg p-3 text-xs">
        <div class="flex flex-col gap-0.5">
          <span class="text-neutral-400 font-medium">Odometer</span>
          <span class="font-semibold text-neutral-700">
            {{ new Intl.NumberFormat('id-ID').format(record.odometer_km) }} km
          </span>
        </div>

        <div class="flex flex-col gap-0.5">
          <span class="text-neutral-400 font-medium">Cost</span>
          <span class="font-semibold text-neutral-700">{{ formattedCost }}</span>
        </div>

        <div class="flex flex-col gap-0.5 col-span-2 sm:col-span-2">
          <span class="text-neutral-400 font-medium">Workshop</span>
          <span class="font-semibold text-neutral-700 truncate">
            {{ record.workshop || 'Self-maintained / Unknown' }}
          </span>
        </div>
      </div>

      <!-- Next maintenance reminder (if provided) -->
      <div
        v-if="record.next_maintenance_km || record.next_maintenance_date"
        class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs border-t border-neutral-100 pt-2.5 text-neutral-500"
      >
        <span class="font-medium text-neutral-400">Next Due:</span>
        <span v-if="record.next_maintenance_date">
          📅 {{ new Date(record.next_maintenance_date).toLocaleDateString('id-ID', { month: 'short', day: 'numeric', year: 'numeric' }) }}
        </span>
        <span v-if="record.next_maintenance_km">
          🛣️ {{ new Intl.NumberFormat('id-ID').format(record.next_maintenance_km) }} km
        </span>
      </div>
      
      <p v-if="record.notes" class="text-xs text-neutral-500 italic mt-1 border-t border-neutral-100/50 pt-2">
        Note: {{ record.notes }}
      </p>
    </div>
  </BaseCard>
</template>
