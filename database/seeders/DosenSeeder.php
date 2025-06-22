<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DosenSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $gelarList = ['S.Pd.', 'M.Pd.', 'M.A.', 'Ph.D.'];

        for ($i = 1; $i <= 30; $i++) {
            // Ambil nama tanpa gelar
            $namaTanpaGelar = $faker->firstName() . ' ' . $faker->lastName();

            // Pilih gelar secara acak
            $gelar = $faker->randomElement($gelarList);

            // Tambahkan gelar di akhir nama
            $namaDosen = $namaTanpaGelar . ', ' . $gelar;

            // Buat email yang natural
            $emailUsername = Str::slug($namaTanpaGelar, '.'); // Contoh: agus.salim
            $email = $emailUsername . $i . '@example.com';

            $userId = DB::table('users')->insertGetId([
                'name' => $namaDosen,
                'email' => $email,
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 4, // dosen
            ]);

            // Tambahan peran kaprodi dan kajur
            if ($i <= 2) {
                DB::table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => 2, // kaprodi
                ]);
            } elseif ($i == 3) {
                DB::table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => 3, // kajur
                ]);
            }

            DB::table('dosen')->insert([
                'user_id' => $userId,
                'nidn' => 'NIDN' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 50% kemungkinan dosen mengajukan topik penelitian
            if ($i % 2 === 0) {
                // Pastikan $bidangKeahlian didefinisikan atau ganti dengan string default jika belum ada
                $bidangKeahlian = ['Applied Linguistics', 'Literature', 'Translation', 'Language Teaching', 'Sociolinguistics', 'Discourse Analysis'];
                $this->buatTawaranTopikBahasaInggris($userId, $faker, $bidangKeahlian[($i - 1) % count($bidangKeahlian)]);
            }
        }
    }

    /**
     * Generate email from name
     */
    protected function generateEmail($name)
    {
        $cleanName = strtolower(preg_replace('/[^a-zA-Z]/', '', $name));
        return $cleanName . '@englishdept.ac.id';
    }

    /**
     * Membuat tawaran topik penelitian bahasa Inggris
     */
    protected function buatTawaranTopikBahasaInggris($dosenId, $faker, $bidangKeahlian)
    {
        $pendekatan = [
            'Qualitative Analysis',
            'Case Study',
            'Experimental Research',
            'Contrastive Analysis',
            'Ethnographic Research',
            'Corpus Analysis',
            'Classroom Action Research',
            'Critical Discourse Analysis'
        ];

        $fokusPenelitian = [
            'Second Language Acquisition',
            'Learning Strategies',
            'Language Errors',
            'Reading Comprehension',
            'Writing Skills',
            'Communicative Competence',
            'Gender Representation',
            'Cultural Identity',
            'Learning Styles',
            'Instructional Media',
            'Language Assessment',
            'Language and Power'
        ];

        $konteks = [
            'in Higher Education',
            'among Adult Learners',
            'in Secondary Schools',
            'among Beginner Learners',
            'in EFL Context',
            'in ESL Context',
            'in Intensive Programs',
            'in Language Courses'
        ];

        // Buat 1-2 topik per dosen
        $jumlahTopik = rand(1, 2);

        for ($j = 0; $j < $jumlahTopik; $j++) {
            $pendekatanTerpilih = $faker->randomElement($pendekatan);
            $fokusTerpilih1 = $faker->randomElement($fokusPenelitian);
            $fokusTerpilih2 = $faker->randomElement($fokusPenelitian);
            $konteksTerpilih = $faker->randomElement($konteks);

            // Variasi pola judul yang natural untuk penelitian bahasa
            $polaJudul = rand(1, 5);
            switch ($polaJudul) {
                case 1:
                    $judul = "$pendekatanTerpilih on $fokusTerpilih1 $konteksTerpilih";
                    break;
                case 2:
                    $judul = "A $pendekatanTerpilih Study of $fokusTerpilih1 $konteksTerpilih";
                    break;
                case 3:
                    $judul = "$fokusTerpilih1: A $pendekatanTerpilih Approach $konteksTerpilih";
                    break;
                case 4:
                    $judul = "The Relationship Between $fokusTerpilih1 and $fokusTerpilih2 $konteksTerpilih";
                    break;
                default:
                    $judul = "Implementing $pendekatanTerpilih to Improve $fokusTerpilih1 $konteksTerpilih";
            }

            // Deskripsi yang spesifik untuk penelitian bahasa
            $deskripsi = "This research aims to ";
            $tujuan = [
                "investigate $fokusTerpilih1 using $pendekatanTerpilih approach $konteksTerpilih.",
                "examine the relationship between $fokusTerpilih1 and $fokusTerpilih2 $konteksTerpilih.",
                "analyze the effectiveness of different teaching methods on $fokusTerpilih1 $konteksTerpilih.",
                "explore the cultural aspects of $fokusTerpilih1 in contemporary society.",
                "develop a new framework for understanding $fokusTerpilih1 through $pendekatanTerpilih."
            ];

            $deskripsi .= $faker->randomElement($tujuan) . " ";
            $deskripsi .= "The study will contribute to the field of $bidangKeahlian by providing new insights into $fokusTerpilih1.";

            DB::table('tawaran_topik')->insert([
                'user_id' => $dosenId,
                'judul_topik' => $judul,
                'deskripsi' => $deskripsi,
                'kuota' => $faker->numberBetween(1, 2),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
