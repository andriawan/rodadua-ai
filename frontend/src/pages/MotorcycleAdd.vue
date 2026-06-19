<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useMotorcycle } from '../composables/useMotorcycle'
import BaseCard from '../components/BaseCard.vue'
import MotorcycleForm from '../components/MotorcycleForm.vue'
import ErrorAlert from '../components/ErrorAlert.vue'

const router = useRouter()
const { loading, error, create } = useMotorcycle()

async function handleSubmit(data: any) {
  try {
    await create(data)
    router.push('/motorcycles')
  } catch {
    // Error is handled by composable/store
  }
}

function handleCancel() {
  router.push('/motorcycles')
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <div>
      <h1 class="text-2xl font-bold text-neutral-900">Tambah Motor Baru</h1>
      <p class="text-xs text-neutral-500 mt-0.5">Lengkapi data spesifikasi sepeda motor Anda.</p>
    </div>

    <ErrorAlert v-if="error" :message="error" />

    <BaseCard padding="lg" class="bg-white border-neutral-200">
      <MotorcycleForm :loading="loading" @submit="handleSubmit" @cancel="handleCancel" />
    </BaseCard>
  </div>
</template>
