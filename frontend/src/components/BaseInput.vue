<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  modelValue: string | number
  label?: string
  type?: string
  placeholder?: string
  error?: string
  helperText?: string
  disabled?: boolean
  required?: boolean
  id?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  placeholder: '',
  disabled: false,
  required: false,
  id: () => `input-${Math.random().toString(36).substr(2, 9)}`,
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number): void
  (e: 'blur', event: FocusEvent): void
  (e: 'focus', event: FocusEvent): void
}>()

const onInput = (event: Event) => {
  const target = event.target as HTMLInputElement
  emit('update:modelValue', props.type === 'number' ? Number(target.value) : target.value)
}

const inputClasses = computed(() => {
  const base = 'w-full px-3 py-2 border rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-50 disabled:bg-neutral-100 disabled:cursor-not-allowed text-neutral-900 bg-white placeholder-neutral-400'
  if (props.error) {
    return `${base} border-danger-500 focus:border-danger-500 focus:ring-danger-500`
  }
  return `${base} border-neutral-300 focus:border-primary-500 focus:ring-primary-500`
})
</script>

<template>
  <div class="w-full flex flex-col gap-1.5">
    <label
      v-if="label"
      :for="id"
      class="text-sm font-medium text-neutral-700 flex items-center gap-0.5"
    >
      {{ label }}
      <span v-if="required" class="text-danger-500 font-bold">*</span>
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :required="required"
      :class="inputClasses"
      @input="onInput"
      @blur="emit('blur', $event)"
      @focus="emit('focus', $event)"
    />
    <p v-if="error" class="text-xs text-danger-500 mt-0.5">
      {{ error }}
    </p>
    <p v-else-if="helperText" class="text-xs text-neutral-500 mt-0.5">
      {{ helperText }}
    </p>
  </div>
</template>
