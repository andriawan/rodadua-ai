<script setup lang="ts">
import { onMounted, ref, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useMotorcycle } from '../composables/useMotorcycle'
import { useMaintenance } from '../composables/useMaintenance'
import type { Motorcycle } from '../types/motorcycle'
import BaseCard from '../components/BaseCard.vue'
import BaseButton from '../components/BaseButton.vue'
import BaseInput from '../components/BaseInput.vue'
import ErrorAlert from '../components/ErrorAlert.vue'

const route = useRoute()
const router = useRouter()
const { motorcycles, fetchAll } = useMotorcycle()
const { addMaintenance, loading, error } = useMaintenance()

const selectedMotorcycleId = ref<number | null>(
  route.query.motorcycle ? Number(route.query.motorcycle) : null
)

const form = reactive({
  type: 'oil_change',
  description: '',
  odometer_km: 0,
  maintenance_date: new Date().toISOString().substring(0, 10),
  cost: '',
  workshop: '',
  status: 'completed' as 'scheduled' | 'completed' | 'pending',
  next_maintenance_km: '',
  next_maintenance_date: '',
  notes: '',
})

onMounted(async () => {
  await fetchAll()
})

async function handleSubmit() {
  if (!selectedMotorcycleId.value) return

  try {
    await addMaintenance(selectedMotorcycleId.value, {
      type: form.type,
      description: form.description,
      odometer_km: form.odometer_km,
      maintenance_date: form.maintenance_date,
      cost: form.cost ? Number(form.cost) : undefined,
      workshop: form.workshop || undefined,
      status: form.status,
      next_maintenance_km: form.next_maintenance_km ? Number(form.next_maintenance_km) : undefined,
      next_maintenance_date: form.next_maintenance_date || undefined,
      notes: form.notes || undefined,
    })
    router.push(`/maintenance?motorcycle=${selectedMotorcycleId.value}`)
  } catch {
    // Error is handled by composable/store
  }
}

function handleCancel() {
  if (selectedMotorcycleId.value) {
    router.push(`/maintenance?motorcycle=${selectedMotorcycleId.value}`)
  } else {
    router.push('/maintenance')
  }
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <div>
      <h1 class="text-2xl font-bold text-neutral-900">Tambah Catatan Servis</h1>
      <p class="text-xs text-neutral-500 mt-0.5">Catat riwayat perawatan sepeda motor Anda.</p>
    </div>

    <ErrorAlert v-if="error" :message="error" />

    <BaseCard padding="lg" class="bg-white border-neutral-200">
      <form @submit.prevent="handleSubmit" class="flex flex-col gap-4">
        <!-- Select Motorcycle -->
        <div class="flex flex-col gap-1.5">
          <label class="text-sm font-medium text-neutral-700">Motorcycle <span class="text-danger-500">*</span></label>
          <select
            v-model="selectedMotorcycleId"
            class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900"
            required
          >
            <option v-for="moto in motorcycles" :key="moto.id" :value="moto.id">
              {{ moto.brand }} {{ moto.model }} ({{ moto.license_plate }})
            </option>
          </select>
        </div>

        <!-- Service Type & Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="flex flex-col gap-1.5">
            <label class="text-sm font-medium text-neutral-700">Service Type <span class="text-danger-500">*</span></label>
            <select
              v-model="form.type"
              class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900"
            >
              <option value="oil_change">Oil Change</option>
              <option value="tire_replacement">Tire Replacement</option>
              <option value="brake_service">Brake Service</option>
              <option value="chain_service">Chain Service</option>
              <option value="spark_plug">Spark Plug Replacement</option>
              <option value="coolant_flush">Coolant Flush</option>
              <option value="air_filter">Air Filter Replacement</option>
              <option value="general_service">General Service</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-sm font-medium text-neutral-700">Status</label>
            <select
              v-model="form.status"
              class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900"
            >
              <option value="completed">Completed</option>
              <option value="scheduled">Scheduled</option>
              <option value="pending">Pending</option>
            </select>
          </div>
        </div>

        <!-- Description -->
        <div class="flex flex-col gap-1.5">
          <label class="text-sm font-medium text-neutral-700">Description <span class="text-danger-500">*</span></label>
          <textarea
            v-model="form.description"
            rows="3"
            placeholder="Describe the service performed..."
            class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900 placeholder-neutral-400"
            required
          ></textarea>
        </div>

        <!-- Odometer, Date, Cost -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <BaseInput
            v-model.number="form.odometer_km"
            label="Odometer (km)"
            type="number"
            placeholder="e.g., 15000"
            required
          />
          <BaseInput
            v-model="form.maintenance_date"
            label="Service Date"
            type="date"
            required
          />
          <BaseInput
            v-model.number="form.cost"
            label="Cost (IDR)"
            type="number"
            placeholder="e.g., 150000"
          />
        </div>

        <!-- Workshop & Next Maintenance -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <BaseInput
            v-model="form.workshop"
            label="Workshop Name"
            placeholder="e.g., AHASS Ciputat"
          />
          <BaseInput
            v-model.number="form.next_maintenance_km"
            label="Next Service at (km)"
            type="number"
            placeholder="e.g., 20000"
          />
        </div>

        <!-- Next Maintenance Date -->
        <BaseInput
          v-model="form.next_maintenance_date"
          label="Next Service Date"
          type="date"
        />

        <!-- Notes -->
        <div class="flex flex-col gap-1.5">
          <label class="text-sm font-medium text-neutral-700">Notes (Optional)</label>
          <textarea
            v-model="form.notes"
            rows="2"
            placeholder="Additional notes..."
            class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900 placeholder-neutral-400"
          ></textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 border-t border-neutral-100 pt-4 mt-2">
          <BaseButton variant="outline" @click="handleCancel">Cancel</BaseButton>
          <BaseButton type="submit" :loading="loading">Save Service Record</BaseButton>
        </div>
      </form>
    </BaseCard>
  </div>
</template>
