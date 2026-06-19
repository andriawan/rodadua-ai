<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const { user, logout } = useAuth()
const isMobileMenuOpen = ref(false)

const navigation = [
  { name: 'Dashboard', href: '/' },
  { name: 'Motorcycles', href: '/motorcycles' },
  { name: 'Maintenance', href: '/maintenance' },
  { name: 'Troubleshooting', href: '/troubleshooting' },
  { name: 'Workshops', href: '/workshops' },
]

async function handleLogout() {
  await logout()
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen bg-neutral-50 flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white border-b border-neutral-200 sticky top-0 z-30 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Brand Logo -->
            <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" @click="router.push('/')">
              <span class="text-2xl">🏍️</span>
              <span class="text-xl font-bold text-neutral-900 tracking-tight">
                Rodadua<span class="text-primary-500">AI</span>
              </span>
            </div>
            
            <!-- Desktop Navigation Links -->
            <div class="hidden sm:ml-8 sm:flex sm:space-x-4 items-center">
              <router-link
                v-for="item in navigation"
                :key="item.name"
                :to="item.href"
                custom
                v-slot="{ href, navigate, isActive }"
              >
                <a
                  :href="href"
                  @click="navigate"
                  :class="[
                    isActive
                      ? 'border-primary-500 text-primary-600 bg-primary-50/50'
                      : 'border-transparent text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900',
                    'inline-flex items-center px-3 py-2 rounded-md text-sm font-medium border-b-2 transition-all duration-200'
                  ]"
                >
                  {{ item.name }}
                </a>
              </router-link>
            </div>
          </div>

          <!-- User Info and Logout -->
          <div class="hidden sm:ml-6 sm:flex sm:items-center gap-4">
            <div v-if="user" class="flex flex-col text-right">
              <span class="text-sm font-semibold text-neutral-800">{{ user.name }}</span>
              <span class="text-xs text-neutral-400 capitalize">{{ user.role }}</span>
            </div>
            
            <button
              type="button"
              class="px-3.5 py-1.5 text-xs font-semibold rounded-md border border-neutral-300 text-neutral-700 bg-white hover:bg-neutral-50 active:scale-95 transition-all duration-150"
              @click="handleLogout"
            >
              Logout
            </button>
          </div>

          <!-- Mobile menu button -->
          <div class="-mr-2 flex items-center sm:hidden">
            <button
              type="button"
              class="inline-flex items-center justify-center p-2 rounded-md text-neutral-400 hover:text-neutral-500 hover:bg-neutral-100 focus:outline-none"
              @click="isMobileMenuOpen = !isMobileMenuOpen"
            >
              <span class="sr-only">Open main menu</span>
              <svg
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  v-if="!isMobileMenuOpen"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  v-else
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile menu -->
      <div v-show="isMobileMenuOpen" class="sm:hidden bg-white border-b border-neutral-200">
        <div class="pt-2 pb-3 space-y-1 px-4">
          <router-link
            v-for="item in navigation"
            :key="item.name"
            :to="item.href"
            custom
            v-slot="{ href, navigate, isActive }"
          >
            <a
              :href="href"
              @click="navigate; isMobileMenuOpen = false"
              :class="[
                isActive
                  ? 'bg-primary-50 border-primary-500 text-primary-700'
                  : 'border-transparent text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900',
                'block pl-3 pr-4 py-2 border-l-4 text-base font-medium'
              ]"
            >
              {{ item.name }}
            </a>
          </router-link>
        </div>
        
        <div class="pt-4 pb-4 border-t border-neutral-200 px-4 flex items-center justify-between">
          <div v-if="user" class="flex flex-col">
            <span class="text-sm font-semibold text-neutral-800">{{ user.name }}</span>
            <span class="text-xs text-neutral-400 capitalize">{{ user.role }}</span>
          </div>
          <button
            type="button"
            class="px-3.5 py-1.5 text-xs font-semibold rounded-md border border-neutral-300 text-neutral-700 bg-white hover:bg-neutral-50 active:scale-95 transition-all duration-150"
            @click="handleLogout"
          >
            Logout
          </button>
        </div>
      </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <slot>
        <router-view />
      </slot>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-neutral-200 py-6 mt-auto">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-neutral-400 font-medium">
        &copy; {{ new Date().getFullYear() }} RodaduaAI. Hak Cipta Dilindungi.
      </div>
    </footer>
  </div>
</template>
