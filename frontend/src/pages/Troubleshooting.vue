<script setup lang="ts">
import { onMounted, ref, reactive } from 'vue'
import { useMotorcycle } from '../composables/useMotorcycle'
import { useTroubleshooting } from '../composables/useTroubleshooting'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'
import BaseCard from '../components/BaseCard.vue'
import BaseButton from '../components/BaseButton.vue'
import BaseInput from '../components/BaseInput.vue'

const { motorcycles, fetchAll } = useMotorcycle()
const { analyze, analyzing, error } = useTroubleshooting()

const selectedMotorcycleId = ref<number | null>(null)
const symptom = ref('')
const problemDescription = ref('')
const analysisResult = ref<any>(null)

onMounted(async () => {
  await fetchAll()
  if (motorcycles.value.length > 0) {
    selectedMotorcycleId.value = motorcycles.value[0].id
  }
})

const symptomsList = [
  'Mesin brebet / tersendat-sendat',
  'Starter motor sulit menyala',
  'Bunyi tidak wajar di area CVT / rantai',
  'Aki cepat tekor / mati',
  'Tarikan gas berat / lemot',
  'Knalpot mengeluarkan asap pekat',
  'Rem kurang pakem / berdecit',
  'Setang terasa berat / tidak stabil',
]

async function handleAnalyze() {
  if (!selectedMotorcycleId.value || !problemDescription.value.trim() || !symptom.value) {
    alert('Harap lengkapi semua bidang sebelum memulai analisa.')
    return
  }

  try {
    analysisResult.value = await analyze(selectedMotorcycleId.value, {
      problem_description: problemDescription.value,
      symptom: symptom.value,
    })
  } catch (err) {
    console.error('Analysis failed:', err)
  }
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <div>
      <h1 class="text-2xl font-bold text-neutral-900">Troubleshooting AI Pintar</h1>
      <p class="text-xs text-neutral-500 mt-0.5">Konsultasikan keluhan motor Anda dan dapatkan analisis instan dari AI.</p>
    </div>

    <ErrorAlert v-if="error" :message="error" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Input Panel -->
      <div class="lg:col-span-1 flex flex-col gap-4">
        <BaseCard padding="md" class="bg-white border-neutral-200">
          <template #header>
            <h2 class="font-bold text-neutral-900 text-sm tracking-wide">Analisa Baru</h2>
          </template>

          <div class="flex flex-col gap-4">
            <!-- Select Motorcycle -->
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-bold text-neutral-500 uppercase tracking-wide">Pilih Motor</label>
              <select
                v-model="selectedMotorcycleId"
                class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-sm text-neutral-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              >
                <option v-for="moto in motorcycles" :key="moto.id" :value="moto.id">
                  {{ moto.brand }} {{ moto.model }}
                </option>
              </select>
            </div>

            <!-- Select Symptom -->
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-bold text-neutral-500 uppercase tracking-wide">Gejala Utama</label>
              <select
                v-model="symptom"
                class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-sm text-neutral-800 focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
                <option value="" disabled>Pilih Gejala Utama...</option>
                <option v-for="sym in symptomsList" :key="sym" :value="sym">
                  {{ sym }}
                </option>
              </select>
            </div>

            <!-- Problem Description Textarea -->
            <div class="flex flex-col gap-1.5">
              <label class="text-xs font-bold text-neutral-500 uppercase tracking-wide">Detail Keluhan</label>
              <textarea
                v-model="problemDescription"
                rows="4"
                placeholder="Jelaskan kondisi motor Anda (kapan mulai terjadi, bunyinya seperti apa, dll)..."
                class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm text-neutral-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
              ></textarea>
            </div>

            <BaseButton
              variant="primary"
              :loading="analyzing"
              class="w-full mt-2"
              @click="handleAnalyze"
            >
              Mulai Analisa AI
            </BaseButton>
          </div>
        </BaseCard>
      </div>

      <!-- Analysis Result Panel -->
      <div class="lg:col-span-2">
        <div v-if="analyzing" class="bg-white border border-neutral-200 rounded-lg p-10 flex flex-col items-center justify-center min-h-[300px]">
          <LoadingSpinner label="AI sedang menganalisa keluhan motor Anda..." />
        </div>

        <div v-else-if="analysisResult" class="flex flex-col gap-6">
          <BaseCard padding="lg" class="bg-white border-neutral-200">
            <template #header>
              <div class="flex items-center gap-2">
                <span class="text-lg">🤖</span>
                <h3 class="font-extrabold text-neutral-900 text-lg">Hasil Analisa AI</h3>
              </div>
              <span :class="[
                'px-2.5 py-0.5 text-xs font-semibold rounded-full border uppercase tracking-wider',
                analysisResult.severity === 'critical' || analysisResult.severity === 'high' ? 'bg-danger-50 text-danger-700 border-danger-500/20' : 'bg-warning-50 text-warning-700 border-warning-500/20'
              ]">
                Bahaya: {{ analysisResult.severity }}
              </span>
            </template>

            <!-- AI Diagnosis -->
            <div class="prose prose-sm text-neutral-700 leading-relaxed">
              <h4 class="text-sm font-bold text-neutral-800 mb-2">Diagnosis Masalah:</h4>
              <p>{{ analysisResult.ai_analysis }}</p>
            </div>

            <!-- Solutions Steps -->
            <div class="mt-6 border-t border-neutral-100 pt-6">
              <h4 class="text-sm font-bold text-neutral-800 mb-4">Langkah Solusi Rekomendasi:</h4>
              
              <div class="flex flex-col gap-4">
                <div
                  v-for="sol in analysisResult.suggested_solutions"
                  :key="sol.step"
                  class="flex gap-3 items-start border-b border-neutral-50 pb-4 last:border-b-0 last:pb-0"
                >
                  <div class="h-6 w-6 rounded-full bg-primary-50 text-primary-600 font-bold flex items-center justify-center text-xs flex-shrink-0 mt-0.5">
                    {{ sol.step }}
                  </div>
                  <div class="flex-grow">
                    <p class="text-sm font-semibold text-neutral-900">{{ sol.description }}</p>
                    <div class="flex gap-4 mt-1.5 text-xs text-neutral-500 font-medium">
                      <span>Kesulitan: <span class="text-neutral-700 capitalize">{{ sol.difficulty }}</span></span>
                      <span v-if="sol.estimated_cost">Perkiraan Biaya: <span class="text-neutral-700">Rp {{ new Intl.NumberFormat('id-ID').format(sol.estimated_cost) }}</span></span>
                      <span>Penanganan: <span class="text-neutral-700">{{ sol.requires_professional ? 'Bengkel Profesional' : 'Bisa Mandiri' }}</span></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </BaseCard>
        </div>

        <div v-else class="h-full min-h-[300px] border border-dashed border-neutral-300 rounded-lg bg-neutral-50/50 flex flex-col items-center justify-center p-8 text-center text-neutral-500">
          <span class="text-5xl">🤖</span>
          <h3 class="text-base font-bold text-neutral-800 mt-4">Menunggu Keluhan Anda</h3>
          <p class="text-xs text-neutral-400 max-w-sm mt-1">
            Gunakan panel kiri untuk mengisi motor, gejala, dan detail kerusakan untuk dianalisis oleh AI.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
