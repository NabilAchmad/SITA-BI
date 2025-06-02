<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('en_US');

        // Daftar nama dosen Bahasa Inggris yang realistis
        $namaDosen = [
            'Dr. Elizabeth Thompson, M.A.',
            'Prof. Richard Wilson, Ph.D.',
            'Dr. Sarah Johnson, M.Ed.',
            'Prof. David Brown, Ph.D.',
            'Dr. Emily Davis, M.A.',
            'Prof. Michael Anderson, Ed.D.',
            'Dr. Jennifer Martinez, M.A.',
            'Prof. Christopher Lee, Ph.D.',
            'Dr. Amanda White, M.Ed.',
            'Prof. Daniel Harris, Ph.D.',
            'Dr. Jessica Clark, M.A.',
            'Prof. Matthew Lewis, Ed.D.',
            'Dr. Olivia Walker, M.A.',
            'Prof. Andrew Hall, Ph.D.',
            'Dr. Sophia Young, M.Ed.'
        ];

        // Bidang keahlian dalam linguistik dan sastra Inggris
        $bidangKeahlian = [
            'Applied Linguistics',
            'English Literature',
            'English Language Teaching',
            'Cognitive Linguistics',
            'Discourse Analysis',
            'Phonology',
            'Sociolinguistics',
            'Psycholinguistics',
            'Cultural Studies',
            'American Literature',
            '19th Century English Literature',
            'English for Specific Purposes',
            'TESOL',
            'Pragmatics',
            'Semantics'
        ];

        foreach ($namaDosen as $index => $nama) {
            $email = $this->generateEmail($nama);
            $nidn = $faker->unique()->numerify('##########');

            // 1. Check if user with email already exists
            $existingUser = DB::table('users')->where('email', $email)->first();
            if ($existingUser) {
                $userId = $existingUser->id;
            } else {
                // Insert new user
                $userId = DB::table('users')->insertGetId([
                    'name' => $nama,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => Hash::make('professor123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 2. Insert ke tabel dosen
            DB::table('dosen')->insert([
                'user_id' => $userId,
                'nidn' => $nidn,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Berikan role dosen
            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 50% kemungkinan dosen mengajukan topik penelitian
            if ($index % 2 === 0) {
                $this->buatTawaranTopikBahasaInggris($userId, $faker, $bidangKeahlian[$index]);
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
                'kuota' => $faker->numberBetween(1, 3),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
