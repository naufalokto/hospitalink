<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $news = [
            [
                'slug' => 'flu-singapura-indonesia',
                'title' => 'Kasus FLU Singapura di Indonesia Meroket, Tembus hingga 5 Ribu',
                'image' => 'images/news/news-card1.png',
                'source' => 'SINDONEWS.com',
                'content' => '<p>Jakarta - Kasus flu Singapura atau Hand, Foot, and Mouth Disease (HFMD) di Indonesia dilaporkan mengalami peningkatan yang signifikan. Jumlah kasus telah menembus angka 5.000 dalam beberapa bulan terakhir.</p>

                <p>Menurut data dari Kementerian Kesehatan, peningkatan kasus terjadi terutama pada anak-anak usia di bawah 5 tahun. Penyakit ini sangat menular dan dapat menyebar dengan cepat di lingkungan seperti sekolah dan tempat penitipan anak.</p>

                <p>Gejala utama flu Singapura meliputi demam, sariawan, dan ruam pada tangan, kaki, serta mulut. Meskipun umumnya tidak berbahaya, penyakit ini dapat menyebabkan ketidaknyamanan yang signifikan pada anak-anak.</p>

                <p>Para ahli kesehatan menyarankan untuk menjaga kebersihan, sering mencuci tangan, dan mengisolasi anak yang terinfeksi untuk mencegah penyebaran virus lebih lanjut.</p>'
            ],
            [
                'slug' => 'kanker-usia-muda',
                'title' => 'Kebiasaan yang Tak Disadari Picu Kanker Usus Besar di Usia Muda',
                'image' => 'images/news/news-card2.jpg',
                'source' => 'Detikhealth.com',
                'content' => '<p>Kanker usus besar atau kolorektal semakin banyak ditemukan pada orang berusia muda. Beberapa kebiasaan sehari-hari yang tampaknya sepele ternyata dapat meningkatkan risiko penyakit ini.</p>

                <p>Para peneliti menemukan bahwa pola makan tidak sehat, kurang aktivitas fisik, dan gaya hidup sedentari menjadi faktor utama peningkatan kasus kanker usus besar pada usia muda. Konsumsi makanan tinggi lemak dan rendah serat, serta kebiasaan menghabiskan waktu lama duduk di depan layar, berkontribusi signifikan terhadap risiko ini.</p>

                <p>Dr. Sarah Johnson, seorang onkolog, menyarankan untuk meningkatkan konsumsi sayur dan buah, melakukan aktivitas fisik teratur, dan mengurangi waktu duduk yang berkepanjangan. Deteksi dini melalui pemeriksaan rutin juga sangat penting, terutama jika ada riwayat kanker dalam keluarga.</p>'
            ],
            [
                'slug' => 'olahraga-kanker',
                'title' => 'Olahraga Lari Bisa Turunkan Risiko Kanker dan Kematian Dini',
                'image' => 'images/news/news-card3.jpg',
                'source' => 'KOMPAS.com',
                'content' => '<p>Sebuah penelitian baru mengungkapkan bahwa olahraga lari secara rutin dapat menurunkan risiko berbagai jenis kanker dan kematian dini. Studi yang melibatkan lebih dari 100.000 peserta selama 10 tahun ini menunjukkan hasil yang sangat menjanjikan.</p>

                <p>Para peneliti menemukan bahwa mereka yang berlari setidaknya 30 menit sehari, tiga kali seminggu, memiliki risiko 27% lebih rendah terkena kanker dan 30% lebih rendah mengalami kematian dini dibandingkan mereka yang tidak berolahraga sama sekali.</p>

                <p>Manfaat lari tidak hanya terbatas pada pencegahan kanker. Aktivitas ini juga terbukti meningkatkan kesehatan jantung, memperkuat sistem kekebalan tubuh, dan membantu menjaga berat badan ideal. Para ahli menyarankan untuk memulai dengan intensitas rendah dan secara bertahap meningkatkan durasi serta intensitas lari.</p>'
            ],
        ];

        foreach ($news as $article) {
            News::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}
