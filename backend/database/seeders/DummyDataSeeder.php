<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vulnerability;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $pentester = User::firstOrCreate(
            ['email' => 'pentester@vulnmgmt.local'],
            ['name' => 'Rina Pratama', 'role' => 'pentester', 'password' => Hash::make('password123')]
        );

        $manager = User::firstOrCreate(
            ['email' => 'manager@vulnmgmt.local'],
            ['name' => 'Budi Santoso', 'role' => 'manager', 'password' => Hash::make('password123')]
        );

        $client = User::firstOrCreate(
            ['email' => 'client@vulnmgmt.local'],
            ['name' => 'PT Client Sejahtera', 'role' => 'client', 'password' => Hash::make('password123')]
        );

        $findings = [
            [
                'title' => 'SQL Injection pada Login Form',
                'description' => 'Parameter username pada form login rentan terhadap SQL Injection berbasis boolean, memungkinkan bypass autentikasi.',
                'vulnerability_type' => 'SQL Injection',
                'cwe_id' => 'CWE-89',
                'severity' => 'Critical',
                'cvss_score' => 9.8,
                'cvss_vector' => 'AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:H/A:H',
                'affected_asset' => 'https://app.client.local/login',
                'impact' => 'Penyerang dapat bypass autentikasi dan mengakses akun pengguna manapun tanpa mengetahui password.',
                'recommendation' => 'Gunakan prepared statements/parameterized query untuk semua interaksi database. Hindari string concatenation pada query SQL.',
                'status' => 'Open',
                'technical_details' => [
                    'injection_point' => 'POST parameter username',
                    'payload_used' => "admin' OR '1'='1",
                    'database_type' => 'MySQL 8.0',
                ],
            ],
            [
                'title' => 'Stored XSS pada Kolom Komentar',
                'description' => 'Input pada fitur komentar tidak di-sanitasi sebelum disimpan dan ditampilkan kembali ke pengguna lain.',
                'vulnerability_type' => 'Stored XSS',
                'cwe_id' => 'CWE-79',
                'severity' => 'High',
                'cvss_score' => 7.2,
                'cvss_vector' => 'AV:N/AC:L/PR:L/UI:R/S:C/C:L/I:L/A:N',
                'affected_asset' => 'https://app.client.local/posts/comment',
                'impact' => 'Script berbahaya akan dieksekusi di browser setiap pengguna yang melihat komentar tersebut, berpotensi mencuri session.',
                'recommendation' => 'Terapkan output encoding (HTML entity encoding) pada seluruh user-generated content sebelum dirender.',
                'status' => 'In Progress',
                'technical_details' => [
                    'injection_point' => 'POST parameter comment_body',
                    'payload_used' => '<img src=x onerror=alert(document.cookie)>',
                    'affected_parameter' => 'comment_body',
                    'persistence_location' => 'tabel comments, kolom body',
                ],
            ],
            [
                'title' => 'Broken Access Control pada Endpoint Admin',
                'description' => 'Endpoint /api/admin/users dapat diakses oleh user dengan role biasa karena tidak ada pengecekan role di middleware.',
                'vulnerability_type' => 'Broken Access Control',
                'cwe_id' => 'CWE-284',
                'severity' => 'High',
                'cvss_score' => 8.1,
                'cvss_vector' => 'AV:N/AC:L/PR:L/UI:N/S:U/C:H/I:H/A:N',
                'affected_asset' => 'https://app.client.local/api/admin/users',
                'impact' => 'User non-admin dapat melihat dan memodifikasi data seluruh pengguna lain di sistem.',
                'recommendation' => 'Tambahkan middleware pengecekan role pada seluruh route admin, lakukan authorization check di setiap endpoint sensitif.',
                'status' => 'Open',
                'technical_details' => [
                    'affected_role' => 'user biasa (customer)',
                    'expected_permission' => 'admin only',
                    'actual_permission' => 'semua role dapat mengakses',
                ],
            ],
            [
                'title' => 'IDOR pada Endpoint Invoice',
                'description' => 'Parameter invoice_id dapat diubah untuk mengakses invoice milik pengguna lain tanpa validasi kepemilikan.',
                'vulnerability_type' => 'Insecure Direct Object Reference (IDOR)',
                'cwe_id' => 'CWE-639',
                'severity' => 'Medium',
                'cvss_score' => 6.5,
                'cvss_vector' => 'AV:N/AC:L/PR:L/UI:N/S:U/C:H/I:N/A:N',
                'affected_asset' => 'https://app.client.local/invoices/{id}',
                'impact' => 'Pengguna dapat melihat data invoice dan informasi pembayaran milik pengguna lain.',
                'recommendation' => 'Validasi kepemilikan resource pada setiap request sebelum data dikembalikan ke pengguna.',
                'status' => 'Resolved',
                'resolved_at' => now()->subDays(3)->toDateString(),
                'technical_details' => [
                    'object_identifier' => 'invoice_id',
                    'manipulated_value' => 'invoice_id=1042 (bukan milik user login)',
                    'accessed_resource' => 'data invoice dan riwayat pembayaran',
                ],
            ],
            [
                'title' => 'Security Misconfiguration - Directory Listing Aktif',
                'description' => 'Web server mengizinkan directory listing pada folder /uploads, mengekspos seluruh file yang pernah diunggah.',
                'vulnerability_type' => 'Security Misconfiguration',
                'cwe_id' => 'CWE-548',
                'severity' => 'Low',
                'cvss_score' => 3.7,
                'cvss_vector' => 'AV:N/AC:L/PR:N/UI:N/S:U/C:L/I:N/A:N',
                'affected_asset' => 'https://app.client.local/uploads/',
                'impact' => 'Penyerang dapat melihat dan mengakses seluruh file yang pernah diunggah pengguna, termasuk yang tidak dimaksudkan untuk publik.',
                'recommendation' => 'Nonaktifkan directory listing pada konfigurasi web server (Apache: Options -Indexes).',
                'status' => 'Accepted Risk',
                'technical_details' => [
                    'misconfigured_component' => 'Apache mod_autoindex',
                    'default_value' => 'Options +Indexes',
                    'recommended_value' => 'Options -Indexes',
                ],
            ],
        ];

        foreach ($findings as $finding) {
            Vulnerability::create(array_merge($finding, [
                'discovered_by' => $pentester->id,
                'discovered_at' => now()->subDays(rand(5, 20))->toDateString(),
                'client_visible' => true,
            ]));
        }
    }
}