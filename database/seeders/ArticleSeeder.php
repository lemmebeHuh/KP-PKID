<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fungsi helper untuk mendapatkan data yang dibutuhkan
        $getCategoryId = function ($name) {
            return Category::where('name', $name)->where('type', 'article')->first()->id ?? null;
        };

        $getAdminAuthorId = function () {
            $adminUser = User::whereHas('role', function($q) { $q->where('name', 'Admin'); })->first();
            return $adminUser ? $adminUser->id : User::first()->id; // Fallback ke user pertama jika admin tidak ada
        };

        // Dapatkan ID kategori dan penulis sekali saja untuk efisiensi
        $catTips = $getCategoryId('Tips & Trik Komputer');
        $catReview = $getCategoryId('Review Hardware');
        $catKeamanan = $getCategoryId('Keamanan Siber');
        $catPanduan = $getCategoryId('Panduan Software');
        $authorId = $getAdminAuthorId();

        if (!$authorId) {
            $this->command->error('Tidak ada user yang bisa dijadikan penulis. Seeder Artikel dibatalkan.');
            return;
        }

        $articles = [
            [
                'title' => '5 Tanda Laptop Anda Perlu Ganti Thermal Paste',
                'category_id' => $catTips,
                'content' => "Suhu laptop yang semakin panas adalah masalah umum. Salah satu penyebabnya adalah thermal paste yang sudah kering. Thermal paste berfungsi sebagai penghantar panas dari prosesor ke heatsink. Jika sudah kering, transfer panas tidak optimal dan menyebabkan overheat. Tanda-tandanya termasuk: kipas berputar kencang terus-menerus, performa menurun saat menjalankan aplikasi berat, laptop tiba-tiba mati, suhu permukaan yang sangat panas, dan usia laptop yang sudah lebih dari 2 tahun tanpa perawatan. Mengganti thermal paste secara berkala adalah solusi efektif untuk menjaga kesehatan laptop Anda.",
                'excerpt' => 'Kenali 5 tanda utama bahwa thermal paste pada prosesor laptop Anda sudah kering dan perlu segera diganti untuk menghindari overheat dan kerusakan komponen.',
            ],
            [
                'title' => 'Review SSD NVMe Gen4: Apakah Layak untuk Upgrade?',
                'category_id' => $catReview,
                'content' => "SSD NVMe PCIe 4.0 menawarkan kecepatan baca/tulis yang luar biasa, bisa mencapai 7000MB/s, hampir dua kali lipat dari Gen3. Bagi para gamer, ini berarti waktu loading game yang lebih singkat. Bagi content creator, ini mempercepat proses rendering video dan transfer file besar. Namun, apakah upgrade ini layak untuk semua orang? Jika Anda masih menggunakan HDD atau SSD SATA, peningkatannya akan sangat terasa. Namun, jika Anda sudah menggunakan SSD NVMe Gen3, perbedaan untuk penggunaan sehari-hari mungkin tidak terlalu signifikan. Pertimbangkan juga apakah motherboard Anda sudah mendukung PCIe 4.0 untuk mendapatkan kecepatan maksimal.",
                'excerpt' => 'SSD NVMe Gen4 menawarkan kecepatan fantastis, tapi apakah benar-benar dibutuhkan? Simak ulasan lengkapnya untuk menentukan apakah ini upgrade yang tepat untuk Anda.',
            ],
            [
                'title' => 'Cara Melindungi Diri dari Serangan Phishing Email',
                'category_id' => $catKeamanan,
                'content' => "Phishing adalah salah satu ancaman siber paling umum. Pelaku mencoba memancing Anda untuk memberikan informasi sensitif seperti password atau data kartu kredit. Berikut cara melindunginya: 1. Periksa alamat email pengirim, jangan terkecoh dengan nama display. 2. Jangan klik link sembarangan. Arahkan mouse ke link untuk melihat URL aslinya sebelum mengklik. 3. Waspadai email yang menciptakan urgensi atau kepanikan. 4. Jangan pernah memberikan informasi pribadi melalui email. 5. Gunakan Autentikasi Dua Faktor (2FA) di semua akun penting Anda. 6. Pastikan Antivirus Anda selalu update.",
                'excerpt' => 'Phishing tetap menjadi ancaman besar. Pelajari langkah-langkah praktis untuk mengidentifikasi email phishing dan melindungi data sensitif Anda dari para penipu online.',
            ],
            // ... (7 artikel lainnya bisa ditambahkan dengan pola yang sama)
             [
                'title' => 'Tips Memilih RAM yang Tepat untuk PC Anda',
                'category_id' => $catTips,
                'content' => "Memilih RAM bisa membingungkan. Perhatikan tiga hal utama: Kapasitas, Kecepatan, dan Tipe. Untuk gaming dan penggunaan umum saat ini, 16GB (2x8GB) adalah standar yang baik. Untuk content creation, pertimbangkan 32GB atau lebih. Kecepatan (MHz) dan timing (CL) juga penting, sesuaikan dengan dukungan motherboard dan prosesor Anda. Pastikan Anda membeli tipe RAM yang benar (DDR4 atau DDR5) sesuai dengan platform PC Anda. Menggunakan dual-channel (memasang dua keping RAM identik) akan memberikan performa yang lebih baik daripada single-channel.",
                'excerpt' => 'Kapasitas, kecepatan, atau tipe? Jangan salah pilih! Ikuti panduan sederhana ini untuk memilih RAM yang paling sesuai dengan kebutuhan dan budget PC Anda.',
            ],
            [
                'title' => 'Panduan Membersihkan Laptop Anda dengan Aman',
                'category_id' => $catTips,
                'content' => "Membersihkan laptop secara rutin dapat memperpanjang usianya. Matikan laptop dan cabut semua kabel. Gunakan kain mikrofiber yang sedikit lembab (dengan air atau cairan pembersih khusus) untuk membersihkan layar. Untuk keyboard, gunakan kuas halus atau vacuum cleaner mini untuk mengangkat debu di sela-sela tombol, lalu lap permukaannya. Bersihkan body laptop dengan kain mikrofiber. Jangan semprotkan cairan langsung ke laptop. Untuk pembersihan internal (kipas dan heatsink), disarankan untuk diserahkan kepada profesional jika Anda tidak yakin.",
                'excerpt' => 'Jaga laptop Anda tetap bersih dan awet. Ikuti langkah-langkah aman untuk membersihkan layar, keyboard, dan body laptop tanpa merusaknya.',
            ],
            [
                'title' => 'Perbedaan Mendasar HDD vs SSD: Mana yang Anda Butuhkan?',
                'category_id' => $catReview,
                'content' => "HDD (Hard Disk Drive) adalah teknologi penyimpanan lama berbasis piringan magnetik. Kelebihannya adalah kapasitas besar dengan harga murah. Namun, karena ada komponen bergerak, ia lebih lambat, lebih berisik, dan lebih rentan terhadap guncangan. SSD (Solid-State Drive) menggunakan chip memori flash, tanpa komponen bergerak. Ini membuatnya jauh lebih cepat, senyap, dan tahan guncangan. Kelemahannya adalah harga per gigabyte-nya lebih mahal. Untuk sistem operasi dan aplikasi, SSD adalah pilihan wajib untuk performa responsif. HDD masih cocok untuk menyimpan data besar seperti film atau arsip.",
                'excerpt' => 'Sama-sama media penyimpanan, tapi performanya jauh berbeda. Pahami perbedaan utama antara HDD dan SSD untuk menentukan pilihan terbaik untuk PC atau laptop Anda.',
            ],
            [
                'title' => 'Cara Mengamankan Jaringan WiFi Rumah Anda',
                'category_id' => $catKeamanan,
                'content' => "Jaringan WiFi yang tidak aman bisa menjadi pintu masuk bagi pihak tak bertanggung jawab. Berikut langkah pengamanannya: 1. Ganti password admin router dari default. 2. Gunakan enkripsi WPA3 atau WPA2-AES. Hindari WEP atau WPA. 3. Gunakan password WiFi yang kuat dan unik. 4. Sembunyikan nama jaringan Anda (SSID Broadcast dinonaktifkan). 5. Aktifkan MAC Address Filtering untuk hanya mengizinkan perangkat terdaftar yang terhubung. 6. Matikan WPS (Wi-Fi Protected Setup) yang rentan. 7. Update firmware router Anda secara berkala.",
                'excerpt' => 'Jangan biarkan WiFi rumah Anda terbuka untuk umum. Ikuti 7 langkah penting ini untuk mengamankan jaringan nirkabel Anda dari penyusup dan pencurian data.',
            ],
            [
                'title' => 'Tutorial Dasar Menggunakan Microsoft Excel untuk Pemula',
                'category_id' => $catPanduan,
                'content' => "Microsoft Excel adalah tools spreadsheet yang sangat powerful. Untuk pemula, mulailah dengan memahami antarmukanya: cell, row, column, dan ribbon menu. Coba masukkan data ke dalam cell. Pelajari cara membuat formula dasar seperti SUM (untuk menjumlahkan), AVERAGE (rata-rata), MAX (nilai tertinggi), dan MIN (nilai terendah). Format cell Anda agar mudah dibaca (misalnya format mata uang atau tanggal). Coba buat tabel sederhana dan gunakan fitur Sort & Filter untuk mengelola data. Dengan menguasai dasar-dasar ini, Anda sudah bisa mulai memanfaatkan Excel untuk kebutuhan sehari-hari.",
                'excerpt' => 'Merasa terintimidasi oleh Excel? Jangan khawatir! Panduan ini akan memperkenalkan Anda pada konsep-konsep dasar untuk mulai menggunakan spreadsheet secara efektif.',
            ],
             [
                'title' => 'CPU Overheating: Penyebab dan Cara Mengatasinya',
                'category_id' => $catTips,
                'content' => "CPU overheating adalah kondisi di mana suhu prosesor melebihi batas amannya. Penyebab utamanya antara lain: thermal paste kering, penumpukan debu pada heatsink dan kipas, sirkulasi udara yang buruk di dalam casing, atau overclocking yang berlebihan. Gejalanya adalah performa yang lambat (throttling), PC sering restart atau mati sendiri, dan suara kipas yang sangat bising. Cara mengatasinya adalah dengan membersihkan debu, mengganti thermal paste, memastikan sirkulasi udara casing lancar, atau mengembalikan setting overclock ke default. Memantau suhu dengan software seperti HWMonitor juga sangat disarankan.",
                'excerpt' => 'PC Anda sering lambat atau mati sendiri? Bisa jadi karena CPU overheating. Kenali penyebab utamanya dan cara efektif untuk mengatasinya.',
            ],
            [
                'title' => 'Review Mousepad Gaming: Apakah Benar-Benar Berpengaruh?',
                'category_id' => $catReview,
                'content' => "Banyak yang menganggap mousepad gaming hanyalah gimmick. Namun, mousepad berkualitas benar-benar berpengaruh. Mousepad tipe 'Speed' memiliki permukaan yang sangat licin, memungkinkan gerakan mouse yang cepat dengan friksi minimal, cocok untuk game FPS cepat. Sebaliknya, tipe 'Control' memiliki permukaan yang lebih bertekstur, memberikan friksi lebih untuk kontrol dan presisi yang lebih tinggi, cocok untuk game yang butuh akurasi seperti game taktik atau pekerjaan desain. Ukuran juga penting; mousepad besar memberikan ruang gerak lebih leluasa. Jadi, ya, mousepad yang tepat bisa meningkatkan performa dan kenyamanan Anda.",
                'excerpt' => 'Speed vs Control, kain vs hard surface. Apakah mousepad gaming seharga ratusan ribu benar-benar sepadan? Simak bagaimana mousepad yang tepat dapat meningkatkan akurasi gaming Anda.',
            ],
        ];

        foreach ($articles as $articleData) {
            if (!Article::where('title', $articleData['title'])->exists()) {
                Article::create([
                    'title' => $articleData['title'],
                    'slug' => Str::slug($articleData['title'], '-'),
                    'category_id' => $articleData['category_id'],
                    'author_id' => $authorId,
                    'content' => $articleData['content'],
                    'excerpt' => $articleData['excerpt'],
                    'status' => 'published', // Langsung publish untuk contoh
                    'published_at' => now()->subDays(rand(1, 30)), // Set tanggal publish acak dalam 30 hari terakhir
                    'featured_image_path' => null, // Set null karena tidak ada gambar
                ]);
            }
        }
        $this->command->info('ArticleSeeder selesai dijalankan.');
    }
}