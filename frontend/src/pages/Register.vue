<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'
import BaseInput from '../components/BaseInput.vue'
import BaseButton from '../components/BaseButton.vue'
import ErrorAlert from '../components/ErrorAlert.vue'

const router = useRouter()
const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const formError = ref('')

const { register, loading, error } = useAuth()

async function handleRegister() {
  formError.value = ''
  if (!name.value || !email.value || !password.value) {
    formError.value = 'Semua field wajib diisi.'
    return
  }
  if (password.value !== confirmPassword.value) {
    formError.value = 'Password konfirmasi tidak cocok.'
    return
  }

  const result = await register(name.value, email.value, password.value)
  if (result.success) {
    router.push('/login')
  }
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <div class="text-center">
      <h1 class="text-2xl font-bold text-neutral-900">Buat Akun Baru</h1>
      <p class="text-xs text-neutral-500 mt-1">Daftar untuk mulai memantau performa motor Anda</p>
    </div>

    <ErrorAlert v-if="error || formError" :message="(error || formError) as string" />

    <form @submit.prevent="handleRegister" class="flex flex-col gap-4">
      <BaseInput
        v-model="name"
        label="Nama Lengkap"
        placeholder="Budi Santoso"
        :required="true"
      />

      <BaseInput
        v-model="email"
        label="Email"
        type="email"
        placeholder="nama@email.com"
        :required="true"
      />

      <BaseInput
        v-model="password"
        label="Password"
        type="password"
        placeholder="Min. 8 karakter"
        :required="true"
      />

      <BaseInput
        v-model="confirmPassword"
        label="Konfirmasi Password"
        type="password"
        placeholder="Ulangi password"
        :required="true"
      />

      <BaseButton type="submit" :loading="loading" class="w-full mt-2">
        Daftar
      </BaseButton>
    </form>

    <div class="text-center text-xs text-neutral-500 border-t border-neutral-100 pt-4">
      Sudah punya akun?
      <router-link to="/login" class="text-primary-600 font-bold hover:underline">
        Masuk Di Sini
      </router-link>
    </div>
  </div>
</template>
