<?php

namespace Database\Seeders;

use App\Models\AiPrompt;
use Illuminate\Database\Seeder;

class AiPromptSeeder extends Seeder
{
    public function run(): void
    {
        $prompts = [
            // === Troubleshooting ===
            [
                'key' => 'troubleshooting.analyze',
                'version' => 1,
                'description' => 'Analyze motorcycle problem and symptoms, return diagnosis and solutions',
                'is_active' => true,
                'content' => <<<'TXT'
Anda adalah asisten perawatan motor Indonesia yang ahli. Seorang pengendara melaporkan masalah pada motornya:

Motor: {{brand}} {{model}} ({{year}})
Odometer: {{odometer_km}} km
Transmisi: {{transmission}}
Bahan Bakar: {{fuel_type}}

Deskripsi Masalah: {{problem_description}}

Gejala yang dialami: {{symptoms}}

Riwayat Perawatan Terakhir: {{maintenance_history}}

Berikan analisis dalam format berikut:
1. **Diagnosa Awal**: Kemungkinan penyebab masalah
2. **Tingkat Keparahan**: (Rendah/Sedang/Tinggi/Kritis)
3. **Solusi yang Disarankan**: Langkah-langkah perbaikan dari yang termudah ke tersulit
4. **Estimasi Biaya**: Perkiraan biaya perbaikan di bengkel umum
5. **Rekomendasi**: Apakah bisa diperbaiki sendiri atau perlu bengkel

Gunakan bahasa Indonesia yang mudah dipahami. Hindari istilah teknis yang terlalu rumit.
TXT
            ],

            // === Maintenance Recommendations ===
            [
                'key' => 'maintenance.recommend',
                'version' => 1,
                'description' => 'Generate personalized maintenance schedule for a motorcycle',
                'is_active' => true,
                'content' => <<<'TXT'
Anda adalah asisten perawatan motor Indonesia yang ahli. Buatkan jadwal perawatan yang dipersonalisasi untuk motor berikut:

Motor: {{brand}} {{model}} ({{year}})
Odometer Saat Ini: {{odometer_km}} km
Transmisi: {{transmission}}
Bahan Bakar: {{fuel_type}}

Berdasarkan data motor ini, buat rekomendasi perawatan dalam format berikut:
1. **Perawatan Rutin (setiap 1-3 bulan)**: Oli, rantai, filter udara
2. **Perawatan Berkala (setiap 6 bulan)**: Busi, kampas rem, coolant
3. **Perawatan Tahunan**: V-belt (matic), aki, bearing roda
4. **Berdasarkan Odometer**: Rekomendasi spesifik berdasarkan kilometer saat ini
5. **Tips Tambahan**: Saran sesuai kondisi lalu lintas dan cuaca Indonesia

Sertakan estimasi biaya untuk setiap item perawatan dalam Rupiah (IDR).
TXT
            ],

            // === Maintenance Prediction ===
            [
                'key' => 'maintenance.predict',
                'version' => 1,
                'description' => 'Predict next maintenance date/km based on history',
                'is_active' => true,
                'content' => <<<'TXT'
Anda adalah asisten perawatan motor Indonesia yang ahli. Berdasarkan riwayat perawatan berikut, prediksikan kapan perawatan selanjutnya:

Motor: {{brand}} {{model}} ({{year}})
Odometer: {{odometer_km}} km

Riwayat Perawatan:
{{maintenance_history}}

Berdasarkan pola perawatan sebelumnya:
1. Kapan perawatan selanjutnya sebaiknya dilakukan? (berdasarkan tanggal DAN kilometer)
2. Jenis perawatan apa yang paling mungkin diperlukan?
3. Apakah ada interval perawatan yang terlewat?
4. Saran untuk menjaga jadwal perawatan yang konsisten

Berikan jawaban dalam Bahasa Indonesia.
TXT
            ],

            // === Comparison ===
            [
                'key' => 'comparison.compare',
                'version' => 1,
                'description' => 'Compare two motorcycles across specifications and use cases',
                'is_active' => true,
                'content' => <<<'TXT'
Anda adalah asisten pembelian motor Indonesia yang ahli. Bandingkan dua motor berikut:

Motor 1: {{brand1}} {{model1}} ({{year1}})
- Mesin: {{engine_cc1}}cc, {{transmission1}}
- Odometer: {{odometer_km1}} km
- Bahan Bakar: {{fuel_type1}}

Motor 2: {{brand2}} {{model2}} ({{year2}})
- Mesin: {{engine_cc2}}cc, {{transmission2}}
- Odometer: {{odometer_km2}} km
- Bahan Bakar: {{fuel_type2}}

Bandingkan kedua motor dalam format berikut:
1. **Perbedaan Utama**: Spesifikasi, performa, dan fitur
2. **Kelebihan & Kekurangan**: Masing-masing motor
3. **Biaya Perawatan**: Perkiraan biaya perawatan tahunan
4. **Konsumsi BBM**: Perbandingan efisiensi bahan bakar
5. **Cocok untuk**: Jenis pengguna yang cocok untuk masing-masing motor
6. **Rekomendasi**: Motor mana yang lebih baik dan alasannya

Gunakan Bahasa Indonesia yang natural dan relevan untuk pasar Indonesia.
TXT
            ],

            // === Specification Extraction ===
            [
                'key' => 'specification.extract',
                'version' => 1,
                'description' => 'Extract structured specs from free-text motorcycle description',
                'is_active' => true,
                'content' => <<<'TXT'
Extract motorcycle specifications from the following text description. Return ONLY valid JSON with these fields (use null for unknown values):

- brand (string)
- model (string)
- year (integer)
- engine_cc (integer)
- engine_type (string)
- transmission (string: "Automatic", "Manual", or "Semi-Automatic")
- fuel_type (string)
- color (string)

Description:
{{description}}
TXT
            ],

            // === Specification Validation ===
            [
                'key' => 'specification.validate',
                'version' => 1,
                'description' => 'Validate motorcycle spec consistency',
                'is_active' => true,
                'content' => <<<'TXT'
Validate the following motorcycle specifications for consistency and accuracy. Identify any potential issues.

Specifications:
- Brand: {{brand}}
- Model: {{model}}
- Year: {{year}}
- Engine: {{engine_cc}}cc, {{engine_type}}
- Transmission: {{transmission}}
- Fuel: {{fuel_type}}

Check for:
1. Does the engine size match known specifications for this model/year?
2. Is the transmission type correct for this model?
3. Is the fuel type appropriate?
4. Any other inconsistencies?

Respond in Bahasa Indonesia. If everything looks correct, confirm that. If there are issues, explain what seems wrong.
TXT
            ],
        ];

        foreach ($prompts as $prompt) {
            AiPrompt::updateOrCreate(
                ['key' => $prompt['key']],
                $prompt
            );
        }
    }
}
