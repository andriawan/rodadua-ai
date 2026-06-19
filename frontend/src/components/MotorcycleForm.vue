<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import type { CreateMotorcycleInput, Motorcycle } from '../types/motorcycle'
import BaseInput from './BaseInput.vue'
import BaseButton from './BaseButton.vue'

interface Props {
  motorcycle?: Motorcycle | null
  loading?: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  (e: 'submit', data: any): void
  (e: 'cancel'): void
}>()

const form = reactive({
  brand: '',
  model: '',
  year: new Date().getFullYear(),
  color: '',
  license_plate: '',
  engine_cc: 150,
  engine_type: 'single_cylinder',
  transmission: 'manual',
  fuel_type: 'petrol',
  purchase_date: new Date().toISOString().substring(0, 10),
  odometer_km: 0,
  notes: '',
  status: 'active',
})

const errors = ref<Record<string, string>>({})

onMounted(() => {
  if (props.motorcycle) {
    form.brand = props.motorcycle.brand
    form.model = props.motorcycle.model
    form.year = props.motorcycle.year
    form.color = props.motorcycle.color
    form.license_plate = props.motorcycle.license_plate
    form.engine_cc = props.motorcycle.engine_cc
    form.engine_type = props.motorcycle.engine_type
    form.transmission = props.motorcycle.transmission
    form.fuel_type = props.motorcycle.fuel_type
    form.purchase_date = props.motorcycle.purchase_date.substring(0, 10)
    form.odometer_km = props.motorcycle.odometer_km
    form.notes = props.motorcycle.notes || ''
    form.status = props.motorcycle.status
  }
})

function validateForm(): boolean {
  errors.value = {}
  let isValid = true

  if (!form.brand.trim()) {
    errors.value.brand = 'Brand is required'
    isValid = false
  }
  if (!form.model.trim()) {
    errors.value.model = 'Model is required'
    isValid = false
  }
  if (!form.year || form.year < 1900 || form.year > new Date().getFullYear() + 1) {
    errors.value.year = 'Please enter a valid year'
    isValid = false
  }
  if (!form.license_plate.trim()) {
    errors.value.license_plate = 'License plate is required'
    isValid = false
  }
  if (!form.color.trim()) {
    errors.value.color = 'Color is required'
    isValid = false
  }
  if (form.engine_cc <= 0) {
    errors.value.engine_cc = 'Engine CC must be greater than 0'
    isValid = false
  }
  if (form.odometer_km < 0) {
    errors.value.odometer_km = 'Odometer cannot be negative'
    isValid = false
  }
  if (!form.purchase_date) {
    errors.value.purchase_date = 'Purchase date is required'
    isValid = false
  }

  return isValid
}

function handleSubmit() {
  if (validateForm()) {
    emit('submit', { ...form })
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="flex flex-col gap-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <BaseInput
        v-model="form.brand"
        label="Brand"
        placeholder="e.g., Honda, Yamaha"
        :error="errors.brand"
        required
      />

      <BaseInput
        v-model="form.model"
        label="Model"
        placeholder="e.g., Vario 150, NMAX"
        :error="errors.model"
        required
      />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <BaseInput
        v-model.number="form.year"
        label="Year"
        type="number"
        placeholder="e.g., 2021"
        :error="errors.year"
        required
      />

      <BaseInput
        v-model="form.color"
        label="Color"
        placeholder="e.g., Matte Black"
        :error="errors.color"
        required
      />

      <BaseInput
        v-model="form.license_plate"
        label="License Plate"
        placeholder="e.g., B 1234 ABC"
        :error="errors.license_plate"
        required
      />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <BaseInput
        v-model.number="form.engine_cc"
        label="Engine Size (CC)"
        type="number"
        :error="errors.engine_cc"
        required
      />

      <div class="flex flex-col gap-1.5">
        <label class="text-sm font-medium text-neutral-700">Transmission</label>
        <select
          v-model="form.transmission"
          class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900"
        >
          <option value="manual">Manual</option>
          <option value="automatic">Automatic</option>
        </select>
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="text-sm font-medium text-neutral-700">Engine Type</label>
        <select
          v-model="form.engine_type"
          class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900"
        >
          <option value="single_cylinder">Single Cylinder</option>
          <option value="twin_cylinder">Twin Cylinder</option>
          <option value="v_twin">V-Twin</option>
          <option value="inline_four">Inline Four</option>
        </select>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="flex flex-col gap-1.5">
        <label class="text-sm font-medium text-neutral-700">Fuel Type</label>
        <select
          v-model="form.fuel_type"
          class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900"
        >
          <option value="petrol">Petrol (Bensin)</option>
          <option value="diesel">Diesel</option>
          <option value="electric">Electric</option>
        </select>
      </div>

      <BaseInput
        v-model="form.purchase_date"
        label="Purchase Date"
        type="date"
        :error="errors.purchase_date"
        required
      />

      <BaseInput
        v-model.number="form.odometer_km"
        label="Odometer (km)"
        type="number"
        :error="errors.odometer_km"
        required
      />
    </div>

    <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium text-neutral-700">Status</label>
      <select
        v-model="form.status"
        class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900"
      >
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="for_sale">For Sale</option>
      </select>
    </div>

    <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium text-neutral-700">Notes (Optional)</label>
      <textarea
        v-model="form.notes"
        rows="3"
        placeholder="Add any specific details or remarks about this motorcycle..."
        class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-neutral-900 placeholder-neutral-400"
      ></textarea>
    </div>

    <div class="flex justify-end gap-3 border-t border-neutral-100 pt-4 mt-2">
      <BaseButton variant="outline" @click="emit('cancel')">Cancel</BaseButton>
      <BaseButton type="submit" :loading="loading">Save Motorcycle</BaseButton>
    </div>
  </form>
</template>
