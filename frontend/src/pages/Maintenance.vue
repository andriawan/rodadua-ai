<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useMotorcycle } from '../composables/useMotorcycle'
import { useMaintenance } from '../composables/useMaintenance'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'
import MaintenanceCard from '../components/MaintenanceCard.vue'

const route = useRoute()
const selectedMotorcycleId = ref<number | null>(null)

const { motorcycles, fetchAll } = useMotorcycle()
const { maintenance, loading, error, fetchForMotorcycle } = useMaintenance()

onMounted(async () => {
  await fetchAll()
  
  // Set selected motorcycle based on route query parameter or default to the first one
  const queryId = route.query.motorcycle ? Number(route.query.motorcycle) : null
  if (queryId) {
    selectedMotorcycleId.value = queryId
  } else if (motorcycles.value.length > 0) {
    selectedMotorcycleId.value = motorcycles.value[0].id
  }
})

// Fetch maintenance records whenever the selected motorcycle changes
watch(selectedMotorcycleId, async (newVal) => {
  if (newVal) {
    await fetchForMotorcycle(newVal)
  }
})
</script>

<template>
  <div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-2xl font-bold text-neutral-900">Perawatan & Log Servis</h1>
        <p class="text-xs text-neutral-500 mt-0.5">Catat histori perawatan mesin, ban, filter, oli, dll.</p>
      </div>

      <!-- Select Motorcycle Dropdown -->
      <div v-if="motorcycles.length > 0" class="flex items-center gap-2">
        <label class="text-xs font-bold text-neutral-500 uppercase tracking-wide">Pilih Motor:</label>
        <select
          v-model="selectedMotorcycleId"
          class="px-3 py-2 border border-neutral-300 rounded-md bg-white text-sm font-semibold text-neutral-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        >
          <option v-for="moto in motorcycles" :key="moto.id" :value="moto.id">
            {{ moto.brand }} {{ moto.model }} ({{ moto.license_plate }})
          </option>
        </select>
      </div>
    </div>

    <ErrorAlert v-if="error" :message="error" />

    <div v-if="!selectedMotorcycleId" class="text-center py-16 bg-white border border-neutral-200 rounded-xl text-neutral-500 text-sm">
      Silakan tambahkan motor terlebih dahulu di dashboard.
    </div>

    <div v-else class="flex flex-col gap-4">
      <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold text-neutral-900">Histori Servis</h2>
        <button
          type="button"
          class="px-3.5 py-1.5 bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold rounded-lg transition-colors"
          @click="window.location.href = `/maintenance/add?motorcycle=${selectedMotorcycleId}`"
        >
          + Tambah Catatan Servis
        </button>
      </div>

      <LoadingSpinner v-if="loading" label="Memuat histori log servis..." />

      <div v-else-if="maintenance.length === 0" class="text-center py-16 border border-dashed border-neutral-300 rounded-xl bg-white/50">
        <span class="text-3xl">📝</span>
        <h3 class="text-base font-bold text-neutral-800 mt-3">Belum ada catatan servis</h3>
        <p class="text-neutral-500 text-xs mt-1 max-w-xs mx-auto">
          Catat log servis rutin Anda agar asisten pintar RodaduaAI dapat memprediksi penggantian onderdil berikutnya!
        </p>
      </div>

      <div v-else class="flex flex-col gap-4">
        <MaintenanceCard
          v-for="record in maintenance"
          :key="record.id"
          :record="record"
        />
      </div>
    </div>
  </div>
</template>
