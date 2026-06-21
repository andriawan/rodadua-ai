<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useMotorcycle } from '../composables/useMotorcycle'
import { useMaintenance } from '../composables/useMaintenance'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'
import BaseCard from '../components/BaseCard.vue'
import MaintenanceCard from '../components/MaintenanceCard.vue'
import RecommendationList from '../components/RecommendationList.vue'

const route = useRoute()
const router = useRouter()
const motorcycleId = Number(route.params.id)

const { currentMotorcycle, loading, error, fetchById } = useMotorcycle()
const { maintenance, recommendations, loading: recsLoading, fetchForMotorcycle, fetchRecommendations } = useMaintenance()

onMounted(async () => {
  if (motorcycleId) {
    await fetchById(motorcycleId)
    await fetchForMotorcycle(motorcycleId)
    await fetchRecommendations(motorcycleId)
  }
})

function goBack() {
  router.push('/motorcycles')
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <!-- Breadcrumb / Back button -->
    <div class="flex items-center gap-2 text-sm text-neutral-500 font-medium">
      <button type="button" class="hover:text-primary-600 transition-colors" @click="goBack">
        &larr; Kembali ke Garasi
      </button>
    </div>

    <ErrorAlert v-if="error" :message="error" />

    <LoadingSpinner v-if="loading" label="Memuat informasi motor..." />

    <div v-else-if="currentMotorcycle" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Details Card -->
      <div class="lg:col-span-2 flex flex-col gap-6">
        <BaseCard padding="lg" class="bg-white border-neutral-200">
          <div class="flex flex-col sm:flex-row justify-between items-start gap-4 pb-6 border-b border-neutral-100">
            <div>
              <span class="text-xs font-bold text-primary-500 tracking-wider uppercase">
                {{ currentMotorcycle.brand }}
              </span>
              <h1 class="text-2xl md:text-3xl font-extrabold text-neutral-900 leading-tight">
                {{ currentMotorcycle.model }} ({{ currentMotorcycle.year }})
              </h1>
              <p class="text-xs text-neutral-400 font-medium mt-1 uppercase tracking-wide">
                Plat Nomor: {{ currentMotorcycle.license_plate }}
              </p>
            </div>
            <div class="flex gap-2">
              <button
                type="button"
                class="px-3.5 py-1.5 border border-neutral-300 rounded text-xs font-bold text-neutral-700 bg-white hover:bg-neutral-50"
                @click="router.push(`/motorcycles/${currentMotorcycle.id}/edit`)"
              >
                Edit Spesifikasi
              </button>
            </div>
          </div>

          <!-- Specs Info List -->
          <div class="grid grid-cols-2 md:grid-cols-3 gap-6 py-6 border-b border-neutral-100 text-sm">
            <div>
              <span class="text-neutral-400 block font-medium">Odometer Saat Ini</span>
              <span class="font-extrabold text-neutral-900 text-base mt-0.5 block">
                {{ new Intl.NumberFormat('id-ID').format(currentMotorcycle.odometer_km) }} km
              </span>
            </div>

            <div>
              <span class="text-neutral-400 block font-medium">Kapasitas Mesin</span>
              <span class="font-extrabold text-neutral-900 text-base mt-0.5 block">
                {{ currentMotorcycle.engine_cc }} cc
              </span>
            </div>

            <div>
              <span class="text-neutral-400 block font-medium">Transmisi</span>
              <span class="font-extrabold text-neutral-900 text-base mt-0.5 block capitalize">
                {{ currentMotorcycle.transmission }}
              </span>
            </div>

            <div>
              <span class="text-neutral-400 block font-medium">Tipe Mesin</span>
              <span class="font-extrabold text-neutral-900 text-base mt-0.5 block capitalize">
                {{ currentMotorcycle.engine_type.replace('_', ' ') }}
              </span>
            </div>

            <div>
              <span class="text-neutral-400 block font-medium">Bahan Bakar</span>
              <span class="font-extrabold text-neutral-900 text-base mt-0.5 block capitalize">
                {{ currentMotorcycle.fuel_type }}
              </span>
            </div>

            <div>
              <span class="text-neutral-400 block font-medium">Warna</span>
              <span class="font-extrabold text-neutral-900 text-base mt-0.5 block capitalize">
                {{ currentMotorcycle.color }}
              </span>
            </div>
          </div>

          <div v-if="currentMotorcycle.notes" class="pt-6">
            <h4 class="text-xs font-bold text-neutral-400 tracking-wider uppercase mb-2">Catatan Tambahan</h4>
            <p class="text-neutral-600 text-sm leading-relaxed">{{ currentMotorcycle.notes }}</p>
          </div>
        </BaseCard>

        <!-- Servis Histori Quick List -->
        <div>
          <div class="flex items-center justify-between mb-3.5">
            <h3 class="text-lg font-bold text-neutral-900">Riwayat Perawatan Terbaru</h3>
            <button
              type="button"
              class="text-xs font-bold text-primary-600 hover:underline"
              @click="router.push(`/maintenance?motorcycle=${currentMotorcycle.id}`)"
            >
              Lihat Histori Lengkap
            </button>
          </div>
          
          <div v-if="maintenance.length === 0" class="text-center py-6 bg-white border border-neutral-200 rounded-lg text-neutral-500 text-xs font-medium">
            Tidak ada catatan servis baru-baru ini.
          </div>
          <div v-else class="flex flex-col gap-3">
            <MaintenanceCard
              v-for="record in maintenance.slice(0, 3)"
              :key="record.id"
              :record="record"
            />
          </div>
        </div>
      </div>

      <!-- Recommendation Sidebar -->
      <div class="flex flex-col gap-6">
        <div>
          <h3 class="text-lg font-bold text-neutral-900 mb-3.5">Rekomendasi AI Pintar</h3>
          <RecommendationList :recommendations="recommendations" :loading="recsLoading" />
        </div>
      </div>
    </div>
  </div>
</template>
