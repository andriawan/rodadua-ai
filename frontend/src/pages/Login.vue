<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'
import BaseInput from '../components/BaseInput.vue'
import BaseButton from '../components/BaseButton.vue'
import ErrorAlert from '../components/ErrorAlert.vue'

const router = useRouter()
const email = ref('')
const password = ref('')
const formError = ref('')

const { login, loading, error } = useAuth()

async function handleLogin() {
  formError.value = ''
  if (!email.value || !password.value) {
    formError.value = 'Please enter both email and password.'
    return
  }

  const result = await login(email.value, password.value)
  if (result.success) {
    router.push('/')
  }
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <div class="text-center">
      <h1 class="text-2xl font-bold text-neutral-900">Selamat Datang Kembali</h1>
      <p class="text-xs text-neutral-500 mt-1">Masuk untuk mengelola motor Anda</p>
    </div>

    <ErrorAlert v-if="error || formError" :message="(error || formError) as string" />

    <form @submit.prevent="handleLogin" class="flex flex-col gap-4">
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
        placeholder="••••••••"
        :required="true"
      />

      <BaseButton type="submit" :loading="loading" class="w-full mt-2">
        Masuk
      </BaseButton>
    </form>

    <div class="text-center text-xs text-neutral-500 border-t border-neutral-100 pt-4">
      Belum punya akun?
      <router-link to="/register" class="text-primary-600 font-bold hover:underline">
        Daftar Sekarang
      </router-link>
    </div>
  </div>
</template>
