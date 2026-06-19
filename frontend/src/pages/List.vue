<script setup lang="ts">
import { onMounted } from 'vue'
import { useMotorcycle } from '../composables/useMotorcycle'
import MotorcycleCard from '../components/MotorcycleCard.vue'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'

const { motorcycles, loading, error, fetchAll, toggleFavorite } = useMotorcycle()

onMounted(async () => {
  await fetchAll()
})

function handleCardClick(moto: any) {
  window.location.href = `/motorcycles/${moto.id}`
}

function handleEdit(moto: any) {
  window.location.href = `/motorcycles/${moto.id}/edit`
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-neutral-900">Daftar Sepeda Motor</h1>
        <p class="text-xs text-neutral-500 mt-0.5">Kelola armada dan informasi detail motor Anda</p>
      </div>
      <button
        type="button"
        class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold rounded-lg transition-colors shadow-sm"
        @click="window.location.href = '/motorcycles/add'"
      >
        + Motor Baru
      </button>
    </div>

    <ErrorAlert v-if="error" :message="error" />

    <LoadingSpinner v-if="loading" label="Memuat daftar motor..." />

    <div v-else-if="motorcycles.length === 0" class="text-center py-20 border border-dashed border-neutral-300 rounded-2xl bg-white/50">
      <span class="text-5xl">🛵</span>
      <h3 class="text-lg font-bold text-neutral-800 mt-4">Belum ada sepeda motor</h3>
      <p class="text-neutral-500 text-xs mt-1.5 max-w-sm mx-auto leading-relaxed">
        Tambahkan motor pertama Anda untuk melacak jadwal servis oli, ban, mesin, dan diagnosa troubleshooting AI.
      </p>
      <button
        type="button"
        class="mt-5 px-4.5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold rounded-md text-xs transition-colors"
        @click="window.location.href = '/motorcycles/add'"
      >
        Tambah Motor Baru
      </button>
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <MotorcycleCard
        v-for="moto in motorcycles"
        :key="moto.id"
        :motorcycle="moto"
        @click="handleCardClick"
        @edit="handleEdit"
        @toggle-favorite="toggleFavorite"
      />
    </div>
  </div>
</template>
