<script setup lang="ts">
import { onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'
import { useMotorcycle } from '../composables/useMotorcycle'
import type { Motorcycle } from '../types/motorcycle'
import BaseCard from '../components/BaseCard.vue'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import MotorcycleCard from '../components/MotorcycleCard.vue'

const router = useRouter()
const { user } = useAuth()
const { motorcycles, loading, fetchAll, toggleFavorite } = useMotorcycle()

onMounted(async () => {
  await fetchAll()
})

const activeCount = computed(() => {
  return motorcycles.value.filter(m => m.status === 'active').length
})

const totalKm = computed(() => {
  return motorcycles.value.reduce((sum, m) => sum + m.odometer_km, 0)
})

function handleMotorcycleClick(moto: Motorcycle) {
  router.push(`/motorcycles/${moto.id}`)
}

function handleEdit(moto: Motorcycle) {
  router.push(`/motorcycles/${moto.id}/edit`)
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <!-- Header banner -->
    <div class="bg-gradient-to-r from-primary-800 to-primary-600 rounded-2xl p-6 md:p-8 text-white shadow-md relative overflow-hidden">
      <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px] opacity-10"></div>
      <div class="z-10 relative flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
            Halo, {{ user?.name || 'Rider' }}! 👋
          </h1>
          <p class="text-primary-100 text-sm md:text-base mt-1.5 font-medium max-w-lg">
            Semua motor dan jadwal servis Anda dalam satu genggaman pintar. Mari jaga mesin tetap prima!
          </p>
        </div>
        <button
          type="button"
          class="px-5 py-2.5 bg-white text-primary-600 hover:bg-neutral-50 active:scale-95 font-bold rounded-lg text-sm transition-all shadow-sm"
          @click="router.push('/motorcycles/add')"
        >
          + Tambah Motor
        </button>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <BaseCard padding="md" class="flex items-center gap-4 bg-white border border-neutral-200">
        <div class="h-12 w-12 rounded-xl bg-primary-50 flex items-center justify-center text-xl">🏍️</div>
        <div>
          <span class="text-xs text-neutral-400 font-semibold block tracking-wider uppercase">Total Motor</span>
          <span class="text-2xl font-extrabold text-neutral-900 leading-none mt-1 block">
            {{ motorcycles.length }}
          </span>
        </div>
      </BaseCard>

      <BaseCard padding="md" class="flex items-center gap-4 bg-white border border-neutral-200">
        <div class="h-12 w-12 rounded-xl bg-secondary-50 flex items-center justify-center text-xl">✅</div>
        <div>
          <span class="text-xs text-neutral-400 font-semibold block tracking-wider uppercase">Motor Aktif</span>
          <span class="text-2xl font-extrabold text-neutral-900 leading-none mt-1 block">
            {{ activeCount }}
          </span>
        </div>
      </BaseCard>

      <BaseCard padding="md" class="flex items-center gap-4 bg-white border border-neutral-200">
        <div class="h-12 w-12 rounded-xl bg-info-50 flex items-center justify-center text-xl">🛣️</div>
        <div>
          <span class="text-xs text-neutral-400 font-semibold block tracking-wider uppercase">Total Jarak Tempuh</span>
          <span class="text-2xl font-extrabold text-neutral-900 leading-none mt-1 block">
            {{ new Intl.NumberFormat('id-ID').format(totalKm) }} km
          </span>
        </div>
      </BaseCard>
    </div>

    <!-- Active Motorcycles Section -->
    <div>
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-neutral-900">Motor Garasi Anda</h2>
        <router-link to="/motorcycles" class="text-sm font-semibold text-primary-600 hover:text-primary-700 hover:underline">
          Lihat Semua
        </router-link>
      </div>

      <LoadingSpinner v-if="loading" label="Memuat garasi motor..." />

      <div v-else-if="motorcycles.length === 0" class="text-center py-12 border border-dashed border-neutral-300 rounded-2xl bg-white/50">
        <span class="text-4xl">🏜️</span>
        <h3 class="text-base font-bold text-neutral-800 mt-3">Belum ada motor terdaftar</h3>
        <p class="text-neutral-500 text-xs mt-1 max-w-xs mx-auto">
          Mulai tambahkan motor pertama Anda untuk mendapatkan saran perawatan pintar!
        </p>
        <button
          type="button"
          class="mt-4 px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-md text-xs transition-colors"
          @click="router.push('/motorcycles/add')"
        >
          Tambah Sekarang
        </button>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <MotorcycleCard
          v-for="moto in motorcycles.slice(0, 3)"
          :key="moto.id"
          :motorcycle="moto"
          @click="handleMotorcycleClick"
          @edit="handleEdit"
          @toggle-favorite="toggleFavorite"
        />
      </div>
    </div>
  </div>
</template>
