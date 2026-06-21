<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useWorkshop } from '../composables/useWorkshop'
import type { Workshop } from '../types/workshop'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'
import WorkshopCard from '../components/WorkshopCard.vue'
import BaseCard from '../components/BaseCard.vue'

const { workshops, loading, error, search } = useWorkshop()

const radius = ref(10)
const minRating = ref(4.0)
const selectedServices = ref<string[]>([])

const availableServices = [
  { value: 'oli', label: 'Ganti Oli' },
  { value: 'ban', label: 'Servis Ban & Velg' },
  { value: 'mesin', label: 'Tune Up Mesin' },
  { value: 'rem', label: 'Servis Rem' },
  { value: 'listrik', label: 'Kelistrikan' },
  { value: 'cvt', label: 'Servis CVT' },
]

onMounted(async () => {
  // Search with default mock location (Jakarta coordinates)
  await handleSearch()
})

async function handleSearch() {
  await search({
    latitude: -6.2088,
    longitude: 106.8456,
    radius_km: radius.value,
    min_rating: minRating.value,
    services: selectedServices.value,
  })
}

function handleWorkshopContact(ws: Workshop) {
  alert(`Hubungi ${ws.name}:\nTelepon: ${ws.phone}\nEmail: ${ws.email}`)
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <div>
      <h1 class="text-2xl font-bold text-neutral-900">Temukan Bengkel Terdekat</h1>
      <p class="text-xs text-neutral-500 mt-0.5">Cari bengkel spesialis tepercaya di sekitar lokasi Anda.</p>
    </div>

    <ErrorAlert v-if="error" :message="error" />

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Filter Sidebar -->
      <div class="lg:col-span-1 flex flex-col gap-4">
        <BaseCard padding="md" class="bg-white border-neutral-200">
          <template #header>
            <h2 class="font-bold text-neutral-900 text-sm tracking-wide">Filter Pencarian</h2>
          </template>

          <div class="flex flex-col gap-5">
            <!-- Radius slider -->
            <div class="flex flex-col gap-1.5">
              <div class="flex justify-between text-xs font-bold text-neutral-500 uppercase tracking-wide">
                <span>Jarak Radius</span>
                <span class="text-primary-600 lowercase">{{ radius }} km</span>
              </div>
              <input
                v-model.number="radius"
                type="range"
                min="1"
                max="50"
                class="w-full h-1.5 bg-neutral-200 rounded-lg appearance-none cursor-pointer accent-primary-600"
              />
            </div>

            <!-- Min Rating dropdown -->
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-bold text-neutral-500 uppercase tracking-wide">Rating Minimal</label>
              <select
                v-model.number="minRating"
                class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-sm text-neutral-800 focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
                <option :value="3.0">⭐⭐⭐ 3.0+</option>
                <option :value="4.0">⭐⭐⭐⭐ 4.0+</option>
                <option :value="4.5">⭐⭐⭐⭐💫 4.5+</option>
              </select>
            </div>

            <!-- Services checkbox list -->
            <div class="flex flex-col gap-2">
              <label class="text-xs font-bold text-neutral-500 uppercase tracking-wide">Layanan Tersedia</label>
              <div class="flex flex-col gap-2">
                <label
                  v-for="service in availableServices"
                  :key="service.value"
                  class="flex items-center gap-2.5 text-sm text-neutral-700 cursor-pointer select-none"
                >
                  <input
                    v-model="selectedServices"
                    type="checkbox"
                    :value="service.value"
                    class="rounded text-primary-600 focus:ring-primary-500 h-4 w-4 border-neutral-300"
                  />
                  <span>{{ service.label }}</span>
                </label>
              </div>
            </div>

            <button
              type="button"
              class="w-full py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold rounded-lg text-xs transition-colors mt-2"
              @click="handleSearch"
            >
              Cari Bengkel
            </button>
          </div>
        </BaseCard>
      </div>

      <!-- Workshop List Panel -->
      <div class="lg:col-span-3">
        <LoadingSpinner v-if="loading" label="Mencari bengkel di sekitar Anda..." />

        <div v-else-if="workshops.length === 0" class="text-center py-20 border border-dashed border-neutral-300 rounded-lg bg-neutral-50/50">
          <span class="text-4xl">📍</span>
          <h3 class="text-base font-bold text-neutral-800 mt-3">Tidak ada bengkel ditemukan</h3>
          <p class="text-neutral-500 text-xs mt-1.5 max-w-sm mx-auto leading-relaxed">
            Coba perbesar radius pencarian Anda atau hapus beberapa filter kategori layanan.
          </p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <WorkshopCard
            v-for="ws in workshops"
            :key="ws.id"
            :workshop="ws"
            @contact="handleWorkshopContact"
          />
        </div>
      </div>
    </div>
  </div>
</template>
