<script setup lang="ts">
import { computed } from 'vue'
import type { MaintenanceRecommendation } from '../types/maintenance'
import BaseCard from './BaseCard.vue'

interface Props {
  recommendations: MaintenanceRecommendation[]
  loading?: boolean
}

defineProps<Props>()

const emit = defineEmits<{
  (e: 'action', recommendation: MaintenanceRecommendation): void
}>()

function getPriorityBadgeClasses(priority: string) {
  switch (priority) {
    case 'critical':
      return 'bg-danger-50 text-danger-700 border-danger-500/20'
    case 'high':
      return 'bg-warning-50 text-warning-700 border-warning-500/20'
    case 'medium':
      return 'bg-info-50 text-info-700 border-info-500/20'
    case 'low':
    default:
      return 'bg-neutral-50 text-neutral-600 border-neutral-200'
  }
}

function formatCost(cost: number) {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(cost)
}

function getRecommendationIcon(type: string) {
  // Return different SVG or simple emoji icons based on recommendation type
  const t = type.toLowerCase()
  if (t.includes('oil')) return '💧'
  if (t.includes('tire') || t.includes('ban')) return '🛞'
  if (t.includes('brake') || t.includes('rem')) return '🛑'
  if (t.includes('spark') || t.includes('busi')) return '⚡'
  if (t.includes('chain') || t.includes('rantai')) return '⛓️'
  return '🔧'
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <div v-if="loading" class="flex flex-col gap-3">
      <div v-for="i in 3" :key="i" class="h-28 bg-neutral-100 animate-pulse rounded-lg border border-neutral-200"></div>
    </div>

    <div v-else-if="recommendations.length === 0" class="text-center py-10 border border-dashed border-neutral-300 rounded-lg bg-neutral-50/50">
      <p class="text-neutral-500 text-sm">No maintenance recommendations available at the moment.</p>
    </div>

    <div v-else class="flex flex-col gap-3">
      <BaseCard
        v-for="(rec, idx) in recommendations"
        :key="idx"
        padding="md"
        class="transition-all duration-200"
      >
        <div class="flex gap-4 items-start">
          <div class="h-10 w-10 flex items-center justify-center bg-neutral-50 rounded-lg text-lg border border-neutral-100 flex-shrink-0">
            {{ getRecommendationIcon(rec.type) }}
          </div>

          <div class="flex-grow flex flex-col gap-1.5 min-w-0">
            <div class="flex items-center justify-between gap-2 flex-wrap sm:flex-nowrap">
              <h4 class="font-bold text-neutral-900 text-base truncate capitalize">
                {{ rec.type.replace('_', ' ') }}
              </h4>
              <span :class="['px-2.5 py-0.5 text-xs font-semibold rounded-full border uppercase tracking-wider', getPriorityBadgeClasses(rec.priority)]">
                {{ rec.priority }}
              </span>
            </div>

            <p class="text-sm text-neutral-600 leading-relaxed">
              {{ rec.description }}
            </p>

            <div class="flex flex-wrap items-center gap-x-6 gap-y-1.5 text-xs font-medium text-neutral-500 mt-1 border-t border-neutral-50 pt-2">
              <span class="flex items-center gap-1">
                💰 Estimated Cost: <span class="text-neutral-900 font-semibold">{{ formatCost(rec.estimated_cost) }}</span>
              </span>
              <span class="flex items-center gap-1">
                ⏱️ Timing: <span class="text-neutral-900 font-semibold">{{ rec.estimated_timing }}</span>
              </span>
            </div>
          </div>
        </div>

        <template #footer>
          <button
            type="button"
            class="text-xs font-bold text-primary-600 hover:text-primary-700 transition-colors focus:outline-none"
            @click="emit('action', rec)"
          >
            Mark as Scheduled/Completed
          </button>
        </template>
      </BaseCard>
    </div>
  </div>
</template>
