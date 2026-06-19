<script setup lang="ts">
// AuthLayout handles login/register page container wrappers
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-neutral-900 via-primary-950 to-neutral-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Glow abstract backgrounds -->
    <div class="absolute top-0 -left-4 w-72 h-72 bg-primary-600 rounded-full mix-blend-multiply filter blur-2xl opacity-10 animate-blob"></div>
    <div class="absolute bottom-0 -right-4 w-72 h-72 bg-secondary-600 rounded-full mix-blend-multiply filter blur-2xl opacity-10 animate-blob animation-delay-2000"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md z-10">
      <div class="flex items-center justify-center gap-2">
        <!-- Logo -->
        <span class="text-3xl">🏍️</span>
        <h2 class="text-center text-3xl font-extrabold text-white tracking-tight">
          Rodadua<span class="text-primary-500">AI</span>
        </h2>
      </div>
      <p class="mt-2 text-center text-sm text-neutral-400 font-medium">
        Asisten Motor AI Andalan Anda
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md z-10 px-4">
      <div class="bg-white py-8 px-4 shadow-xl rounded-lg sm:px-10 border border-neutral-100">
        <!-- Renders Login / Register components -->
        <slot>
          <router-view />
        </slot>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}

@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -30px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.95);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}
</style>
