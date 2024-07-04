<?php

namespace Database\Seeders;

use App\Models\Sample;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CadanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sample::create(['subdistrict_code' => '270', 'subdistrict_name' => 'ASEMROWO', 'village_name' => '', 'bs_code' => null, 'sample_unique_code' => '4188377', 'sample_name' => 'GOMIUM NATURAL INDONESIA', 'sample_address' => 'JL MARGOMULYO 44', 'type' => 'Cadangan', 'survey_id' => 1, 'job' => 'AKTIVITAS KEHUMASAN', 'category' => 'M', 'strata' => '70203', 'kbli' => '2', 'is_selected' => false,]);
        Sample::create(['subdistrict_code' => '280', 'subdistrict_name' => 'BENOWO', 'village_name' => '006', 'village_name' => 'KANDANGAN', 'bs_code' => null, 'sample_unique_code' => '6355300', 'sample_name' => 'BANGUN SARANA LISTRIK, PT', 'sample_address' => 'JL NGAGEL JAYA UTARA NO 137', 'type' => 'Cadangan', 'survey_id' => 1, 'job' => 'KONSTRUKSI GEDUNG HUNIAN', 'category' => 'F', 'strata' => '41011', 'kbli' => '5', 'is_selected' => false,]);
        Sample::create(['subdistrict_code' => '280', 'subdistrict_name' => 'BENOWO', 'village_name' => '006', 'village_name' => 'KANDANGAN', 'bs_code' => null, 'sample_unique_code' => '96813392', 'sample_name' => 'AVS PT', 'sample_address' => 'JL WISMA TENGGER XIX NO 7', 'type' => 'Cadangan', 'survey_id' => 1, 'job' => 'PERDAGANGAN ECERAN BARANG LOGAM UNTUK BAHAN KONSTRUKSI', 'category' => 'G', 'strata' => '47521', 'kbli' => '5', 'is_selected' => false,]);
        Sample::create(['subdistrict_code' => '280', 'subdistrict_name' => 'BENOWO', 'village_name' => '006', 'village_name' => 'KANDANGAN', 'bs_code' => null, 'sample_unique_code' => '96977305', 'sample_name' => 'BIRO JASA (EFFENDI)', 'sample_address' => 'KANDANGAN JAYA 2 NO 65', 'type' => 'Cadangan', 'survey_id' => 1, 'job' => 'AKTIVITAS JASA PERORANGAN LAINNYA YTDL', 'category' => 'S', 'strata' => '96990', 'kbli' => '5', 'is_selected' => false,]);
        Sample::create(['subdistrict_code' => '280', 'subdistrict_name' => 'BENOWO', 'village_name' => '008', 'village_name' => 'ROMOKALISARI', 'bs_code' => '012B', 'sample_unique_code' => '96845588', 'sample_name' => 'PT BADAK ARUN SOLUSI <RUDIASYAH>', 'sample_address' => 'PERGUDANGAN MASPION BLOK A1-A2', 'type' => 'Cadangan', 'survey_id' => 1, 'job' => 'PROSES PEMBUATAN VALUE LPG', 'category' => 'D', 'strata' => '35113', 'kbli' => '2', 'is_selected' => false,]);
        Sample::create(['subdistrict_code' => '281', 'subdistrict_name' => 'PAKAL', 'village_name' => '002', 'village_name' => 'PAKAL', 'bs_code' => '014B', 'sample_unique_code' => '96865171', 'sample_name' => 'ABPI PUTRA MANDIRI PT', 'sample_address' => 'RAYA REJOSARI NO 2', 'type' => 'Cadangan', 'survey_id' => 1, 'job' => 'PROPERTI', 'category' => 'G', 'strata' => '46639', 'kbli' => '3', 'is_selected' => false,]);
        Sample::create(['subdistrict_code' => '060', 'subdistrict_name' => 'GUNUNG ANYAR', 'village_name' => '', 'bs_code' => null, 'sample_unique_code' => '94411514', 'sample_name' => 'PT MULTI PRIMA UNIVERSAL, KANTOR CABANG SURABAYA', 'sample_address' => 'JL RUNGKUT INDUSTRI III NO 46, SURABAYA 60291', 'type' => 'Cadangan', 'survey_id' => 1, 'job' => 'JASA PENAMBANGAN (MINING SERVICES)', 'category' => 'B', 'strata' => '09900', 'kbli' => '5', 'is_selected' => false,]);
        Sample::create(['subdistrict_code' => '260', 'subdistrict_name' => 'KREMBANGAN', 'village_name' => '', 'bs_code' => null, 'sample_unique_code' => '96676178', 'sample_name' => 'TUJUH BELAS, PT', 'sample_address' => 'JL. IKAN MUNGSING IV/ 8', 'type' => 'Cadangan', 'survey_id' => 1, 'job' => 'KONSTRUKSI GEDUNG', 'category' => 'F', 'strata' => '41000', 'kbli' => '5', 'is_selected' => false,]);
        Sample::create(['subdistrict_code' => '070', 'subdistrict_name' => 'RUNGKUT', 'village_name' => '', 'bs_code' => null, 'sample_unique_code' => '94724983', 'sample_name' => 'PT. SURABAYA INDUSTRIAL ESTATE RUNGKUT, SARANA OLAHRAGA TENIS LAPANGAN', 'sample_address' => 'JL. RUNGKUT INDUSTRI RAYA NO 100', 'type' => 'Cadangan', 'survey_id' => 1, 'job' => 'KEGIATAN OLAHRAGA', 'category' => 'R', 'strata' => '93112', 'kbli' => '5', 'is_selected' => false,]);
    }
}
