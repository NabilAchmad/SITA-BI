<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TugasAkhirSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker::create('id_ID');

        $mahasiswaList = DB::table('mahasiswa')->get();
        $statusList = ['diajukan', 'draft', 'revisi', 'disetujui', 'lulus_tanpa_revisi', 'lulus_dengan_revisi', 'ditolak'];

        $judulTemplates = [
            'The Effectiveness of Using {media} to Improve Students\' {skill} Skills',
            'An Analysis of {phenomenon} among English Department Students',
            'Students\' Perception on the Use of {media} in Learning English',
            'The Correlation Between {factor1} and {skill} Mastery in English Learners',
            'Teaching {skill} Using {method}: A Case Study at {school}',
            'The Impact of {media} Integration in {skill} Classrooms',
            'A Study on Common Errors in {skill} among 4th Semester Students',
            'Developing {media} to Enhance English {skill} for EFL Students',
            'The Influence of {method} on Students\' Motivation in Learning English',
            'A Comparative Study of {method1} and {method2} in Teaching {skill}'
        ];

        $skills = ['Speaking', 'Listening', 'Reading', 'Writing', 'Grammar', 'Vocabulary'];
        $media = ['YouTube Videos', 'Podcasts', 'Mobile Applications', 'Flashcards', 'Interactive Games', 'e-Learning Platforms'];
        $methods = ['Communicative Language Teaching', 'Task-Based Learning', 'Audio-Lingual Method', 'Project-Based Learning', 'Blended Learning'];
        $phenomena = ['Code Switching', 'Language Anxiety', 'Translanguaging', 'Spelling Mistakes', 'Pronunciation Errors'];
        $factors = ['Learning Motivation', 'Self-Efficacy', 'Study Habit', 'Class Participation', 'Internet Access'];
        $schools = ['SMAN 1 Jakarta', 'SMA Harapan Bangsa', 'Universitas XYZ', 'English Language Center'];

        foreach ($mahasiswaList as $mhs) {
            $status = $faker->randomElement($statusList);

            $judul = str_replace(
                ['{media}', '{skill}', '{phenomenon}', '{factor1}', '{method}', '{school}', '{method1}', '{method2}'],
                [
                    $faker->randomElement($media),
                    $faker->randomElement($skills),
                    $faker->randomElement($phenomena),
                    $faker->randomElement($factors),
                    $faker->randomElement($methods),
                    $faker->randomElement($schools),
                    $faker->randomElement($methods),
                    $faker->randomElement($methods),
                ],
                $faker->randomElement($judulTemplates)
            );

            $similarityScore = match ($status) {
                'disetujui', 'lulus_tanpa_revisi', 'lulus_dengan_revisi' => rand(0, 25) + rand(0, 99) / 100,
                'revisi', 'draft' => rand(20, 40) + rand(0, 99) / 100,
                'ditolak' => rand(50, 90) + rand(0, 99) / 100,
                default => null,
            };

            DB::table('tugas_akhir')->insert([
                'mahasiswa_id' => $mhs->id,
                'judul' => $judul,
                'abstrak' => $faker->paragraph(3),
                'status' => $status,
                'tanggal_pengajuan' => now()->subDays(rand(5, 30)),
                'similarity_score' => $similarityScore,
                'alasan_penolakan' => $status === 'ditolak' ? $faker->sentence() : null,
                'terakhir_dicek' => $status === 'diajukan' ? null : now()->subDays(rand(1, 7)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
