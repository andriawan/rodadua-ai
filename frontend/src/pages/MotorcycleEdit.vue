<script setup lang="ts">
import { onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useMotorcycle } from '../composables/useMotorcycle'
import BaseCard from '../components/BaseCard.vue'
import MotorcycleForm from '../components/MotorcycleForm.vue'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'

const route = useRoute()
const router = useRouter()
const motorcycleId = Number(route.params.id)

const { currentMotorcycle, loading, error, fetchById, update } = useMotorcycle()

onMounted(async () => {
  if (motorcycleId) {
    await fetchById(motorcycleId)
  }
})

async function handleSubmit(data: any) {
  try {
    await update(motorcycleId, data)
    router.push(`/motorcycles/${motorcycleId}`)
  } catch {
    // Error is handled by composable/store
  }
}

function handleCancel() {
  router.push(`/motorcycles/${motorcycleId}`)
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <div>
      <h1 class="text-2xl font-bold text-neutral-900">Edit Spesifikasi Motor</h1>
      <p class="text-xs text-neutral-500 mt-0.5">Perbarui data spesifikasi sepeda motor Anda.</p>
    </div>

    <ErrorAlert v-if="error" :message="error" />

    <LoadingSpinner v-if="loading && !currentMotorcycle" label="Memuat data motor..." />

    <BaseCard v-else-if="currentMotorcycle" padding="lg" class="bg-white border-neutral-200">
      <MotorcycleForm
        :motorcycle="currentMotorcycle"
        :loading="loading"
        @submit="handleSubmit"
        @cancel="handleCancel"
      />
    </BaseCard>
  </div>
</template>
