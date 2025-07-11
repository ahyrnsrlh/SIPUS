<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rack;
use App\Models\SubRack;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample racks for PLN
        $racks = [
            [
                'name' => 'Dokumen Legal',
                'description' => 'Kontrak, perjanjian, dan dokumen hukum lainnya',
                'sub_racks' => [
                    ['name' => 'Kontrak Kerja', 'description' => 'Kontrak kerja karyawan'],
                    ['name' => 'Perjanjian Vendor', 'description' => 'Perjanjian dengan supplier dan vendor'],
                    ['name' => 'Perizinan', 'description' => 'Dokumen perizinan dan regulasi'],
                ]
            ],
            [
                'name' => 'Dokumen Teknis',
                'description' => 'Spesifikasi teknis, manual, dan prosedur operasional',
                'sub_racks' => [
                    ['name' => 'Manual Operasi', 'description' => 'Manual operasional peralatan'],
                    ['name' => 'Spesifikasi Teknis', 'description' => 'Spek teknis peralatan dan infrastruktur'],
                    ['name' => 'SOP', 'description' => 'Standard Operating Procedures'],
                ]
            ],
            [
                'name' => 'Dokumen Keuangan',
                'description' => 'Laporan keuangan, invoice, dan dokumen akuntansi',
                'sub_racks' => [
                    ['name' => 'Laporan Bulanan', 'description' => 'Laporan keuangan bulanan'],
                    ['name' => 'Invoice', 'description' => 'Invoice dan faktur'],
                    ['name' => 'Anggaran', 'description' => 'Perencanaan dan realisasi anggaran'],
                ]
            ],
            [
                'name' => 'Dokumen SDM',
                'description' => 'Dokumen kepegawaian dan sumber daya manusia',
                'sub_racks' => [
                    ['name' => 'Data Karyawan', 'description' => 'Data personal karyawan'],
                    ['name' => 'Training', 'description' => 'Materi dan sertifikat pelatihan'],
                    ['name' => 'Evaluasi Kinerja', 'description' => 'Penilaian kinerja karyawan'],
                ]
            ],
            [
                'name' => 'Dokumen Proyek',
                'description' => 'Dokumentasi proyek dan pengembangan infrastruktur',
                'sub_racks' => [
                    ['name' => 'Proposal Proyek', 'description' => 'Proposal dan rencana proyek'],
                    ['name' => 'Progress Report', 'description' => 'Laporan kemajuan proyek'],
                    ['name' => 'Dokumentasi Selesai', 'description' => 'Dokumentasi proyek yang telah selesai'],
                ]
            ],
        ];

        foreach ($racks as $rackData) {
            $rack = Rack::create([
                'name' => $rackData['name'],
                'description' => $rackData['description'],
                'created_by' => 1, // Admin user
            ]);

            // Create sub-racks
            if (isset($rackData['sub_racks'])) {
                foreach ($rackData['sub_racks'] as $subRackData) {
                    SubRack::create([
                        'name' => $subRackData['name'],
                        'description' => $subRackData['description'],
                        'rack_id' => $rack->id,
                        'created_by' => 1,
                    ]);
                }
            }
        }
    }
}
