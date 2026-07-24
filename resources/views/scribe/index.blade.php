<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>SIG-Udeung — Dokumentasi API</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .php-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "https://127.0.0.1:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.11.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.11.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;php&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="php">php</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-pendahuluan" class="tocify-header">
                <li class="tocify-item level-1" data-unique="pendahuluan">
                    <a href="#pendahuluan">Pendahuluan</a>
                </li>
                                    <ul id="tocify-subheader-pendahuluan" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="base-url">
                                <a href="#base-url">Base URL</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="tentang-sistem">
                                <a href="#tentang-sistem">Tentang Sistem</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentikasi">
                                <a href="#autentikasi">Autentikasi</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="format-response">
                                <a href="#format-response">Format Response</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rate-limiting">
                                <a href="#rate-limiting">Rate Limiting</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="grup-endpoint">
                                <a href="#grup-endpoint">Grup Endpoint</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="implementasi-real">
                                <a href="#implementasi-real">Implementasi Real</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="webhook-callback">
                                <a href="#webhook-callback">Webhook & Callback</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="notifikasi">
                                <a href="#notifikasi">Notifikasi</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="struktur-data-penting">
                                <a href="#struktur-data-penting">Struktur Data Penting</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="informasi-teknis">
                                <a href="#informasi-teknis">Informasi Teknis</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="koleksi-postman-openapi">
                                <a href="#koleksi-postman-openapi">Koleksi Postman & OpenAPI</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-autentikasi-permintaan" class="tocify-header">
                <li class="tocify-item level-1" data-unique="autentikasi-permintaan">
                    <a href="#autentikasi-permintaan">Autentikasi Permintaan</a>
                </li>
                                    <ul id="tocify-subheader-autentikasi-permintaan" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="header">
                                <a href="#header">Header</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="alur-mendapatkan-token">
                                <a href="#alur-mendapatkan-token">Alur Mendapatkan Token</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="login-warga">
                                <a href="#login-warga">Login Warga</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="login-administrator">
                                <a href="#login-administrator">Login Administrator</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="logout">
                                <a href="#logout">Logout</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="bind-telegram">
                                <a href="#bind-telegram">Bind Telegram</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="catatan-penting">
                                <a href="#catatan-penting">Catatan Penting</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-autentikasi" class="tocify-header">
                <li class="tocify-item level-1" data-unique="autentikasi">
                    <a href="#autentikasi">Autentikasi</a>
                </li>
                                    <ul id="tocify-subheader-autentikasi" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="autentikasi-POSTapi-v1-auth-login-warga">
                                <a href="#autentikasi-POSTapi-v1-auth-login-warga">Memproses login warga menggunakan NIK dan Nomor Kartu Keluarga via API.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentikasi-POSTapi-v1-auth-login-admin">
                                <a href="#autentikasi-POSTapi-v1-auth-login-admin">Memproses login administrator menggunakan Username dan Password via API.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentikasi-POSTapi-v1-auth-logout">
                                <a href="#autentikasi-POSTapi-v1-auth-logout">Memproses keluar log (logout) dengan menghapus token akses saat ini.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentikasi-GETapi-v1-auth-profile">
                                <a href="#autentikasi-GETapi-v1-auth-profile">Mengambil detail informasi profil pengguna yang sedang login.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="autentikasi-POSTapi-v1-auth-bind-telegram">
                                <a href="#autentikasi-POSTapi-v1-auth-bind-telegram">Menghubungkan ID chat Telegram dengan akun warga.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-informasi-publik" class="tocify-header">
                <li class="tocify-item level-1" data-unique="informasi-publik">
                    <a href="#informasi-publik">Informasi Publik</a>
                </li>
                                    <ul id="tocify-subheader-informasi-publik" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="informasi-publik-GETapi-v1-informasi">
                                <a href="#informasi-publik-GETapi-v1-informasi">Mengambil daftar seluruh artikel informasi publik yang telah terbit.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="informasi-publik-GETapi-v1-informasi--slug-">
                                <a href="#informasi-publik-GETapi-v1-informasi--slug-">Menampilkan detail informasi publik tertentu berdasarkan slug.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-layanan-warga" class="tocify-header">
                <li class="tocify-item level-1" data-unique="layanan-warga">
                    <a href="#layanan-warga">Layanan Warga</a>
                </li>
                                    <ul id="tocify-subheader-layanan-warga" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="layanan-warga-GETapi-v1-surat-kategori">
                                <a href="#layanan-warga-GETapi-v1-surat-kategori">Mengambil daftar seluruh kategori surat yang aktif untuk diajukan.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="layanan-warga-GETapi-v1-surat-kategori--id-">
                                <a href="#layanan-warga-GETapi-v1-surat-kategori--id-">Mengambil spesifikasi skema data dan syarat dari satu kategori surat.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="layanan-warga-POSTapi-v1-surat-pengajuan">
                                <a href="#layanan-warga-POSTapi-v1-surat-pengajuan">Memproses pengiriman permohonan pengajuan surat pelayanan baru dari warga.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="layanan-warga-GETapi-v1-surat-pengajuan">
                                <a href="#layanan-warga-GETapi-v1-surat-pengajuan">Mengambil daftar riwayat permohonan surat milik warga yang sedang login.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="layanan-warga-GETapi-v1-surat-pengajuan--id-">
                                <a href="#layanan-warga-GETapi-v1-surat-pengajuan--id-">Mengambil detail dari permohonan surat tertentu beserta riwayat tracking-nya.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="layanan-warga-POSTapi-v1-mutasi">
                                <a href="#layanan-warga-POSTapi-v1-mutasi">Memproses pengajuan mutasi penduduk baru (kelahiran/kematian/kedatangan/kepindahan).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="layanan-warga-GETapi-v1-mutasi">
                                <a href="#layanan-warga-GETapi-v1-mutasi">Mengambil daftar riwayat mutasi milik warga yang sedang login.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-administrasi" class="tocify-header">
                <li class="tocify-item level-1" data-unique="administrasi">
                    <a href="#administrasi">Administrasi</a>
                </li>
                                    <ul id="tocify-subheader-administrasi" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="administrasi-pengajuan-surat">
                                <a href="#administrasi-pengajuan-surat">Pengajuan Surat</a>
                            </li>
                                                            <ul id="tocify-subheader-administrasi-pengajuan-surat" class="tocify-subheader">
                                                                            <li class="tocify-item level-3" data-unique="administrasi-GETapi-v1-admin-surat-pengajuan">
                                            <a href="#administrasi-GETapi-v1-admin-surat-pengajuan">Mengambil data seluruh pengajuan surat untuk kebutuhan panel admin desa.</a>
                                        </li>
                                                                            <li class="tocify-item level-3" data-unique="administrasi-POSTapi-v1-admin-surat-pengajuan--id--approve">
                                            <a href="#administrasi-POSTapi-v1-admin-surat-pengajuan--id--approve">Menyetujui pengajuan surat dan memicu proses tanda tangan digital PDF otomatis.</a>
                                        </li>
                                                                            <li class="tocify-item level-3" data-unique="administrasi-POSTapi-v1-admin-surat-pengajuan--id--reject">
                                            <a href="#administrasi-POSTapi-v1-admin-surat-pengajuan--id--reject">Menolak pengajuan permohonan surat warga dengan menyertakan catatan penolakan.</a>
                                        </li>
                                                                    </ul>
                                                                                <li class="tocify-item level-2" data-unique="administrasi-mutasi-penduduk">
                                <a href="#administrasi-mutasi-penduduk">Mutasi Penduduk</a>
                            </li>
                                                            <ul id="tocify-subheader-administrasi-mutasi-penduduk" class="tocify-subheader">
                                                                            <li class="tocify-item level-3" data-unique="administrasi-GETapi-v1-admin-mutasi">
                                            <a href="#administrasi-GETapi-v1-admin-mutasi">Mengambil seluruh data mutasi penduduk untuk panel admin desa.</a>
                                        </li>
                                                                            <li class="tocify-item level-3" data-unique="administrasi-POSTapi-v1-admin-mutasi--id--approve">
                                            <a href="#administrasi-POSTapi-v1-admin-mutasi--id--approve">Menyetujui pengajuan mutasi dan memperbarui status aktif/pasif kependudukan warga terkait.</a>
                                        </li>
                                                                            <li class="tocify-item level-3" data-unique="administrasi-POSTapi-v1-admin-mutasi--id--reject">
                                            <a href="#administrasi-POSTapi-v1-admin-mutasi--id--reject">Menolak pengajuan mutasi penduduk.</a>
                                        </li>
                                                                    </ul>
                                                                                <li class="tocify-item level-2" data-unique="administrasi-informasi">
                                <a href="#administrasi-informasi">Informasi</a>
                            </li>
                                                            <ul id="tocify-subheader-administrasi-informasi" class="tocify-subheader">
                                                                            <li class="tocify-item level-3" data-unique="administrasi-GETapi-v1-admin-informasi">
                                            <a href="#administrasi-GETapi-v1-admin-informasi">Mengambil daftar informasi publik untuk keperluan panel admin (termasuk draf).</a>
                                        </li>
                                                                            <li class="tocify-item level-3" data-unique="administrasi-POSTapi-v1-admin-informasi">
                                            <a href="#administrasi-POSTapi-v1-admin-informasi">Menyimpan artikel informasi publik baru ke database.</a>
                                        </li>
                                                                            <li class="tocify-item level-3" data-unique="administrasi-PUTapi-v1-admin-informasi--id-">
                                            <a href="#administrasi-PUTapi-v1-admin-informasi--id-">Memperbarui detail artikel informasi publik tertentu berdasarkan ID.</a>
                                        </li>
                                                                            <li class="tocify-item level-3" data-unique="administrasi-DELETEapi-v1-admin-informasi--id-">
                                            <a href="#administrasi-DELETEapi-v1-admin-informasi--id-">Menghapus artikel informasi publik tertentu dari database.</a>
                                        </li>
                                                                    </ul>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-statistik-verifikasi" class="tocify-header">
                <li class="tocify-item level-1" data-unique="statistik-verifikasi">
                    <a href="#statistik-verifikasi">Statistik & Verifikasi</a>
                </li>
                                    <ul id="tocify-subheader-statistik-verifikasi" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="statistik-verifikasi-GETapi-v1-statistik-demografi">
                                <a href="#statistik-verifikasi-GETapi-v1-statistik-demografi">Mengambil rangkuman data demografi kependudukan gampong.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="statistik-verifikasi-GETapi-v1-statistik-layanan">
                                <a href="#statistik-verifikasi-GETapi-v1-statistik-layanan">Mengambil rangkuman data statistik layanan administrasi persuratan.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="statistik-verifikasi-GETapi-v1-verifikasi--hash-">
                                <a href="#statistik-verifikasi-GETapi-v1-verifikasi--hash-">Memverifikasi tanda tangan digital surat berdasarkan hash QR Code.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="statistik-verifikasi-admin">
                                <a href="#statistik-verifikasi-admin">Admin</a>
                            </li>
                                                            <ul id="tocify-subheader-statistik-verifikasi-admin" class="tocify-subheader">
                                                                            <li class="tocify-item level-3" data-unique="statistik-verifikasi-POSTapi-v1-admin-statistik-clear-cache">
                                            <a href="#statistik-verifikasi-POSTapi-v1-admin-statistik-clear-cache">Membersihkan cache penyimpanan data statistik.</a>
                                        </li>
                                                                    </ul>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-integrasi-telegram" class="tocify-header">
                <li class="tocify-item level-1" data-unique="integrasi-telegram">
                    <a href="#integrasi-telegram">Integrasi Telegram</a>
                </li>
                                    <ul id="tocify-subheader-integrasi-telegram" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="integrasi-telegram-POSTapi-v1-telegram-webhook">
                                <a href="#integrasi-telegram-POSTapi-v1-telegram-webhook">Endpoint utama penerima payload webhook Telegram.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-lainnya" class="tocify-header">
                <li class="tocify-item level-1" data-unique="lainnya">
                    <a href="#lainnya">Lainnya</a>
                </li>
                                    <ul id="tocify-subheader-lainnya" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="lainnya-POSTapi-v1-whatsapp-webhook">
                                <a href="#lainnya-POSTapi-v1-whatsapp-webhook">POST api/v1/whatsapp/webhook</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Terakhir diperbarui: July 22, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="pendahuluan">Pendahuluan</h1>
<p>Dokumentasi resmi API <strong>Sistem Informasi Gampong Udeung (SIG-Udeung)</strong> — platform digital administrasi desa untuk <strong>Gampong Udeung, Kecamatan Bandar Baru, Kabupaten Pidie Jaya, Provinsi Aceh</strong>.</p>
<hr />
<h2 id="base-url">Base URL</h2>
<pre><code>https://gampong.web.id/api/v1</code></pre>
<p>Seluruh endpoint menggunakan prefiks <code>/api/v1</code>. Contoh:</p>
<ul>
<li><code>https://gampong.web.id/api/v1/auth/login/warga</code></li>
<li><code>https://gampong.web.id/api/v1/surat/kategori</code></li>
<li><code>https://gampong.web.id/api/v1/admin/surat/pengajuan</code></li>
</ul>
<hr />
<h2 id="tentang-sistem">Tentang Sistem</h2>
<p>SIG-Udeung adalah sistem informasi desa terpadu yang mencakup:</p>
<table>
<thead>
<tr>
<th>Modul</th>
<th>Fungsi</th>
<th>Teknologi</th>
</tr>
</thead>
<tbody>
<tr>
<td><strong>Portal Publik</strong></td>
<td>Halaman beranda, profil desa, berita, statistik</td>
<td>Vue 3 + Inertia.js</td>
</tr>
<tr>
<td><strong>Layanan Warga</strong></td>
<td>Pengajuan surat online, mutasi penduduk</td>
<td>Vue 3 + Inertia.js</td>
</tr>
<tr>
<td><strong>Panel Admin</strong></td>
<td>Verifikasi pengajuan, manajemen konten, statistik</td>
<td>Filament PHP 5</td>
</tr>
<tr>
<td><strong>Notifikasi WhatsApp</strong></td>
<td>Status surat &amp; broadcast berita ke warga</td>
<td>WA Gateway (Baileys) + Fonnte (fallback)</td>
</tr>
<tr>
<td><strong>Notifikasi Telegram</strong></td>
<td>Notifikasi surat ke personal chat &amp; broadcast berita ke grup</td>
<td>Telegram Bot API</td>
</tr>
<tr>
<td><strong>Chatbot AI</strong></td>
<td>Asisten virtual warga via Telegram</td>
<td>Google Gemini / OpenAI</td>
</tr>
<tr>
<td><strong>Verifikasi QR</strong></td>
<td>Validasi keaslian dokumen surat via QR Code</td>
<td>SHA-256 + QR Code</td>
</tr>
</tbody>
</table>
<hr />
<h2 id="autentikasi">Autentikasi</h2>
<h3 id="login-warga">Login Warga</h3>
<p>Warga login menggunakan <strong>NIK</strong> (16 digit) dan <strong>Nomor Kartu Keluarga</strong> (16 digit):</p>
<pre><code class="language-http">POST https://gampong.web.id/api/v1/auth/login/warga
Content-Type: application/json

{
  "nik": "1118060512900001",
  "no_kk": "1118060001000001"
}</code></pre>
<p><strong>Response:</strong></p>
<pre><code class="language-json">{
  "message": "Login berhasil",
  "data": {
    "user": {
      "nik": "1118060512900001",
      "nama_lengkap": "Muhammad Ali",
      "jenis_kelamin": "L",
      "tempat_lahir": "Udeung",
      "tanggal_lahir": "1990-12-05",
      "alamat": "Jl. Gampong Udeung No. 10",
      "agama": "Islam",
      "pekerjaan": "Petani",
      "telegram_chat_id": null
    },
    "token": "1|abc123def456..."
  }
}</code></pre>
<h3 id="login-administrator">Login Administrator</h3>
<p>Admin login menggunakan <strong>username</strong> dan <strong>password</strong>:</p>
<pre><code class="language-http">POST https://gampong.web.id/api/v1/auth/login/admin
Content-Type: application/json

{
  "username": "operator",
  "password": "********"
}</code></pre>
<p><strong>Response:</strong></p>
<pre><code class="language-json">{
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "username": "operator",
      "nama_lengkap": "Operator Desa"
    },
    "token": "1|xyz789..."
  }
}</code></pre>
<h3 id="menggunakan-token">Menggunakan Token</h3>
<p>Setelah login, sertakan token sebagai <strong>Bearer Token</strong> di header <code>Authorization</code> setiap request:</p>
<pre><code class="language-http">Authorization: Bearer 1|abc123def456...</code></pre>
<p>Token berlaku selama sesi aktif. Setiap user hanya dapat memiliki <strong>satu token aktif</strong> — token sebelumnya otomatis dicabut saat login baru.</p>
<hr />
<h2 id="format-response">Format Response</h2>
<h3 id="response-berhasil">Response Berhasil</h3>
<pre><code class="language-json">{
  "message": "String pesan deskriptif",
  "data": {
    ...
  }
}</code></pre>
<h3 id="response-validasi-gagal-422">Response Validasi Gagal (422)</h3>
<pre><code class="language-json">{
  "message": "Validasi gagal",
  "errors": {
    "nik": ["NIK harus 16 digit"],
    "no_kk": ["Nomor KK wajib diisi"]
  }
}</code></pre>
<h3 id="response-tidak-ditemukan-404">Response Tidak Ditemukan (404)</h3>
<pre><code class="language-json">{
  "message": "Data tidak ditemukan"
}</code></pre>
<h3 id="response-error-server-500">Response Error Server (500)</h3>
<pre><code class="language-json">{
  "message": "Terjadi kesalahan internal server"
}</code></pre>
<h3 id="response-autentikasi-gagal-401">Response Autentikasi Gagal (401)</h3>
<pre><code class="language-json">{
  "message": "Unauthenticated"
}</code></pre>
<h3 id="response-tidak-diizinkan-403">Response Tidak Diizinkan (403)</h3>
<pre><code class="language-json">{
  "message": "Forbidden — anda tidak memiliki akses ke sumber daya ini"
}</code></pre>
<hr />
<h2 id="rate-limiting">Rate Limiting</h2>
<table>
<thead>
<tr>
<th>Endpoint</th>
<th>Batas</th>
<th>Periode</th>
</tr>
</thead>
<tbody>
<tr>
<td>Login warga &amp; admin</td>
<td><strong>5 request</strong></td>
<td>per menit per IP</td>
</tr>
<tr>
<td>Webhook Telegram</td>
<td><strong>60 request</strong></td>
<td>per menit</td>
</tr>
<tr>
<td>Seluruh endpoint lainnya</td>
<td><strong>60 request</strong></td>
<td>per menit per IP</td>
</tr>
</tbody>
</table>
<p>Saat batas terlampaui, response <code>429 Too Many Requests</code>:</p>
<pre><code class="language-json">{
  "message": "Too Many Attempts."
}</code></pre>
<hr />
<h2 id="grup-endpoint">Grup Endpoint</h2>
<table>
<thead>
<tr>
<th>Grup</th>
<th>Endpoint</th>
<th>Autentikasi</th>
</tr>
</thead>
<tbody>
<tr>
<td><strong>Autentikasi</strong></td>
<td>Login warga, login admin, logout, profil, bind Telegram</td>
<td>Bervariasi</td>
</tr>
<tr>
<td><strong>Informasi Publik</strong></td>
<td>Berita, pengumuman desa</td>
<td>Publik (read-only)</td>
</tr>
<tr>
<td><strong>Layanan Warga</strong></td>
<td>Kategori surat, pengajuan surat, mutasi penduduk</td>
<td>Token warga</td>
</tr>
<tr>
<td><strong>Administrasi</strong></td>
<td>Verifikasi pengajuan, manajemen informasi, statistik</td>
<td>Token admin</td>
</tr>
<tr>
<td><strong>Statistik &amp; Verifikasi</strong></td>
<td>Data demografi, statistik layanan, verifikasi QR</td>
<td>Publik + admin</td>
</tr>
<tr>
<td><strong>Integrasi Telegram</strong></td>
<td>Webhook incoming dari Telegram Bot</td>
<td>Publik (via secret)</td>
</tr>
</tbody>
</table>
<hr />
<h2 id="implementasi-real">Implementasi Real</h2>
<h3 id="contoh-alur-pengajuan-surat-lengkap">Contoh: Alur Pengajuan Surat Lengkap</h3>
<p>Berikut alur lengkap dari warga mengajukan surat hingga mendapat notifikasi:</p>
<pre><code class="language-php">// === Step 1: Login warga ===
use Illuminate\Support\Facades\Http;

$login = Http::post('https://gampong.web.id/api/v1/auth/login/warga', [
    'nik' =&gt; '1118060512900001',
    'no_kk' =&gt; '1118060001000001',
]);

$token = $login['data']['token'];

// === Step 2: Ambil daftar kategori surat ===
$kategori = Http::withToken($token)
    -&gt;get('https://gampong.web.id/api/v1/surat/kategori')
    -&gt;json();

// Pilih kategori, misal SKD (Surat Keterangan Domisili)
$kategoriId = $kategori['data'][0]['id'];

// === Step 3: Ajukan surat ===
$pengajuan = Http::withToken($token)
    -&gt;post('https://gampong.web.id/api/v1/surat/pengajuan', [
        'kategori_surat_id' =&gt; $kategoriId,
        'data_isian' =&gt; [
            'alamat_sekarang' =&gt; 'Gampong Udeung, Kec. Bandar Baru',
            'lama_menetap' =&gt; '5 Tahun',
            'status_tempat_tinggal' =&gt; 'Milik Sendiri',
            'keperluan' =&gt; 'Pendaftaran BPJS',
        ],
        'file_syarat' =&gt; [
            'KTP' =&gt; 'https://storage.gampong.web.id/uploads/ktp.jpg',
            'Kartu Keluarga' =&gt; 'https://storage.gampong.web.id/uploads/kk.jpg',
        ],
    ]);

// Response: {"message": "Pengajuan surat berhasil dibuat", "data": {...}}

// === Step 4: Cek status pengajuan (dari dashboard warga) ===
$riwayat = Http::withToken($token)
    -&gt;get('https://gampong.web.id/api/v1/surat/pengajuan')
    -&gt;json();</code></pre>
<h3 id="contoh-admin-approve-surat-dari-sistem-eksternal">Contoh: Admin Approve Surat (dari sistem eksternal)</h3>
<pre><code class="language-php">// Login admin
$login = Http::post('https://gampong.web.id/api/v1/auth/login/admin', [
    'username' =&gt; 'keuchik',
    'password' =&gt; '********',
]);
$adminToken = $login['data']['token'];

// Approve pengajuan
$approve = Http::withToken($adminToken)
    -&gt;post('https://gampong.web.id/api/v1/admin/surat/pengajuan/{id}/approve')
    -&gt;json();

// Sistem otomatis:
// 1. Generate nomor surat (format: SKD/001/VI/2026)
// 2. Generate QR hash (SHA-256)
// 3. Update status menjadi 'Selesai'
// 4. Kirim notifikasi WhatsApp ke warga (via WA Gateway / Fonnte)
// 5. Kirim notifikasi Telegram ke personal chat warga (jika terdaftar)</code></pre>
<h3 id="contoh-javascript-fetch-api-untuk-integrasi-frontend">Contoh: JavaScript (Fetch API) untuk Integrasi Frontend</h3>
<pre><code class="language-javascript">// Login warga
async function loginWarga(nik, no_kk) {
  const res = await fetch('https://gampong.web.id/api/v1/auth/login/warga', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ nik, no_kk })
  });
  const { data } = await res.json();
  return data.token;
}

// Ambil kategori surat
async function getKategoriSurat(token) {
  const res = await fetch('https://gampong.web.id/api/v1/surat/kategori', {
    headers: { 'Authorization': `Bearer ${token}` }
  });
  return await res.json();
}

// Ajukan surat
async function ajukanSurat(token, kategoriId, dataIsian, fileSyarat) {
  const res = await fetch('https://gampong.web.id/api/v1/surat/pengajuan', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    },
    body: JSON.stringify({
      kategori_surat_id: kategoriId,
      data_isian: dataIsian,
      file_syarat: fileSyarat
    })
  });
  return await res.json();
}</code></pre>
<h3 id="contoh-curl-untuk-scripting">Contoh: cURL untuk Scripting</h3>
<pre><code class="language-bash">#!/bin/bash

# Login warga
TOKEN=$(curl -s -X POST https://gampong.web.id/api/v1/auth/login/warga \
  -H "Content-Type: application/json" \
  -d '{"nik":"1118060512900001","no_kk":"1118060001000001"}' \
  | jq -r '.data.token')

# Ambil kategori surat
curl -s https://gampong.web.id/api/v1/surat/kategori \
  -H "Authorization: Bearer $TOKEN" \
  | jq

# Ajukan surat baru
curl -s -X POST https://gampong.web.id/api/v1/surat/pengajuan \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "kategori_surat_id": "01AR...",
    "data_isian": {
      "keperluan": "Pendaftaran BPJS",
      "alamat_sekarang": "Gampong Udeung"
    },
    "file_syarat": {
      "KTP": "https://storage.gampong.web.id/uploads/ktp.jpg"
    }
  }' | jq</code></pre>
<hr />
<h2 id="webhook-callback">Webhook &amp; Callback</h2>
<h3 id="telegram-webhook">Telegram Webhook</h3>
<p>Sistem menerima pesan dari Telegram Bot melalui endpoint:</p>
<pre><code>POST https://gampong.web.id/api/v1/telegram/webhook</code></pre>
<p>Bot Telegram akan mengirimkan update secara real-time ke endpoint ini. Sistem memproses:</p>
<ol>
<li><strong>Pesan teks biasa</strong> — dicocokkan dengan knowledge base FAQ, kemudian diproses oleh AI (Gemini/OpenAI) jika tidak ada kecocokan</li>
<li><strong>Perintah <code>/start</code></strong> — mengirim pesan selamat datang</li>
<li><strong>Perintah <code>/bind</code></strong> — instruksi menghubungkan akun Telegram ke NIK</li>
</ol>
<h3 id="whatsapp-webhook-wa-gateway">WhatsApp Webhook (WA Gateway)</h3>
<p>WA Gateway mengirimkan status pesan dan pesan masuk ke:</p>
<pre><code>POST https://gampong.web.id/api/v1/whatsapp/webhook</code></pre>
<p>Webhook ini digunakan untuk memantau status pengiriman (terkirim/gagal) dan menerima pesan masuk dari warga.</p>
<hr />
<h2 id="notifikasi">Notifikasi</h2>
<h3 id="whatsapp-dual-provider">WhatsApp — Dual Provider</h3>
<table>
<thead>
<tr>
<th>Provider</th>
<th>Status</th>
<th>Cara Kerja</th>
</tr>
</thead>
<tbody>
<tr>
<td><strong>WA Gateway</strong> (primary)</td>
<td>Self-hosted Node.js + Baileys</td>
<td>QR pairing, anti-ban engine, SOCKS5 proxy</td>
</tr>
<tr>
<td><strong>Fonnte</strong> (fallback)</td>
<td>SaaS, token-only</td>
<td>Auto-fallback jika primary gagal</td>
</tr>
</tbody>
</table>
<p>Konfigurasi di <code>.env</code>:</p>
<pre><code class="language-env">WHA_PROVIDER=wa-gateway        # atau 'fonnte'
WHA_GATEWAY_URL=https://wa.gampong.web.id
WHA_API_KEY=***
WHA_SESSION_ID=sig-udeung
FONNTE_TOKEN=***                # diisi untuk fallback</code></pre>
<p><strong>Auto-fallback:</strong> Jika wa-gateway gagal mengirim dan <code>FONNTE_TOKEN</code> terisi, sistem otomatis mengirim ulang via Fonnte tanpa intervensi manual.</p>
<h3 id="telegram">Telegram</h3>
<table>
<thead>
<tr>
<th>Jenis Notifikasi</th>
<th>Tujuan</th>
<th>Trigger</th>
</tr>
</thead>
<tbody>
<tr>
<td>Status surat</td>
<td>Personal chat warga</td>
<td>Submit, approve, reject</td>
</tr>
<tr>
<td>Berita baru</td>
<td>Grup @kabargampongudeung</td>
<td>Publikasi berita oleh admin</td>
</tr>
<tr>
<td>Chatbot AI</td>
<td>Personal chat warga</td>
<td>Pesan masuk ke bot</td>
</tr>
</tbody>
</table>
<hr />
<h2 id="struktur-data-penting">Struktur Data Penting</h2>
<h3 id="pengajuan-surat">Pengajuan Surat</h3>
<pre><code class="language-json">{
  "id": "01AR...",
  "nomor_registrasi": "20260723-0001",
  "nik_pemohon": "1118060512900001",
  "kategori_surat_id": "01AR...",
  "data_isian": { ... },
  "file_syarat": [ ... ],
  "status": "Pending | Disetujui | Ditolak | Selesai",
  "nomor_surat": "SKD/001/VII/2026",
  "qr_hash": "a1b2c3d4e5f6...",
  "file_pdf_url": "/warga/pengajuan/01AR.../print",
  "catatan_penolakan": null,
  "diverifikasi_oleh": 1,
  "created_at": "2026-07-23T10:00:00",
  "updated_at": "2026-07-23T14:30:00"
}</code></pre>
<p><strong>Status Flow:</strong></p>
<pre><code>Pending → Diproses → Disetujui → Selesai
                  → Ditolak</code></pre>
<h3 id="kategori-surat">Kategori Surat</h3>
<pre><code class="language-json">{
  "id": "01AR...",
  "kode_surat": "SKD",
  "nama_surat": "Surat Keterangan Domisili",
  "template_view": "domisili",
  "body_content": "Bahwa yang bersangkutan merupakan benar warga yang berdomisili...",
  "schema_isian": [
    { "field": "keperluan", "label": "Keperluan Surat", "type": "text", "required": true }
  ],
  "syarat_dokumen": ["KTP", "KK"],
  "is_active": true
}</code></pre>
<hr />
<h2 id="informasi-teknis">Informasi Teknis</h2>
<h3 id="encoding-format">Encoding &amp; Format</h3>
<table>
<thead>
<tr>
<th>Aspek</th>
<th>Spesifikasi</th>
</tr>
</thead>
<tbody>
<tr>
<td>Request/Response</td>
<td>JSON (<code>application/json</code>)</td>
</tr>
<tr>
<td>Encoding</td>
<td>UTF-8</td>
</tr>
<tr>
<td>Auth</td>
<td>Bearer Token (Sanctum)</td>
</tr>
<tr>
<td>Pagination</td>
<td><code>?page=1&amp;per_page=10</code> (default: 10)</td>
</tr>
</tbody>
</table>
<h3 id="cors">CORS</h3>
<p>API mengizinkan request dari:</p>
<ul>
<li><code>https://gampong.web.id</code></li>
<li><code>http://localhost:*</code> (development)</li>
<li>Aplikasi mobile dan PWA</li>
</ul>
<h3 id="keamanan">Keamanan</h3>
<ul>
<li>Rate limiting pada endpoint login (5/menit) untuk mencegah brute force</li>
<li>Validasi input ketat di semua endpoint</li>
<li>Role-based access control: warga vs admin</li>
<li>Verifikasi dokumen via QR Code + hash SHA-256</li>
<li>Webhook Telegram/WhatsApp divalidasi dengan secret header</li>
<li>Semua file upload divalidasi tipe dan ukuran</li>
</ul>
<hr />
<h2 id="koleksi-postman-openapi">Koleksi Postman &amp; OpenAPI</h2>
<p>Dokumentasi ini tersedia dalam format:</p>
<ul>
<li><strong>Postman Collection:</strong> <code>https://gampong.web.id/docs.postman</code></li>
<li><strong>OpenAPI/Swagger:</strong> <code>https://gampong.web.id/docs.openapi</code></li>
</ul>
<p>Koleksi Postman dapat diimpor langsung ke aplikasi Postman untuk pengujian endpoint secara interaktif.</p>

        <h1 id="autentikasi-permintaan">Autentikasi Permintaan</h1>
<p>Seluruh endpoint yang memerlukan autentikasi menggunakan <strong>Bearer Token</strong> (Laravel Sanctum).</p>
<h2 id="header">Header</h2>
<pre><code class="language-http">Authorization: Bearer 1|abc123def456...
Content-Type: application/json
Accept: application/json</code></pre>
<h2 id="alur-mendapatkan-token">Alur Mendapatkan Token</h2>
<pre><code class="language-mermaid">sequenceDiagram
    participant Client
    participant API as api.gampong.web.id
    participant DB as Database

    Client-&gt;&gt;API: POST /api/v1/auth/login/warga
    Note over Client: { nik, no_kk }
    API-&gt;&gt;DB: Verifikasi NIK &amp; KK
    DB--&gt;&gt;API: Valid
    API--&gt;&gt;Client: { message, data: { user, token } }

    Client-&gt;&gt;API: GET /api/v1/surat/kategori
    Note over Client: Authorization: Bearer {token}
    API--&gt;&gt;Client: Daftar kategori surat</code></pre>
<h2 id="login-warga">Login Warga</h2>
<pre><code class="language-http">POST https://gampong.web.id/api/v1/auth/login/warga
Content-Type: application/json

{
  "nik": "1118060512900001",
  "no_kk": "1118060001000001"
}</code></pre>
<p><strong>Response:</strong></p>
<pre><code class="language-json">{
  "message": "Login berhasil",
  "data": {
    "user": {
      "nik": "1118060512900001",
      "nama_lengkap": "Muhammad Ali",
      "jenis_kelamin": "L",
      "tempat_lahir": "Udeung",
      "tanggal_lahir": "1990-12-05",
      "alamat": "Jl. Gampong Udeung No. 10",
      "agama": "Islam",
      "pekerjaan": "Petani",
      "telegram_chat_id": null
    },
    "token": "1|abc123def456..."
  }
}</code></pre>
<h2 id="login-administrator">Login Administrator</h2>
<pre><code class="language-http">POST https://gampong.web.id/api/v1/auth/login/admin
Content-Type: application/json

{
  "username": "keuchik",
  "password": "********"
}</code></pre>
<p><strong>Response:</strong></p>
<pre><code class="language-json">{
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "username": "keuchik",
      "nama_lengkap": "Tgk. Muhammad",
      "jabatan": "Keuchik"
    },
    "token": "1|xyz789..."
  }
}</code></pre>
<h2 id="logout">Logout</h2>
<pre><code class="language-http">POST https://gampong.web.id/api/v1/auth/logout
Authorization: Bearer 1|abc123def456...</code></pre>
<p>Mencabut token yang sedang aktif. Response: <code>204 No Content</code>.</p>
<h2 id="bind-telegram">Bind Telegram</h2>
<p>Menghubungkan akun Telegram ke akun warga:</p>
<pre><code class="language-http">POST https://gampong.web.id/api/v1/auth/bind-telegram
Authorization: Bearer 1|abc123def456...
Content-Type: application/json

{
  "telegram_chat_id": "123456789"
}</code></pre>
<p>Response:</p>
<pre><code class="language-json">{
  "message": "Akun Telegram berhasil dihubungkan"
}</code></pre>
<h2 id="catatan-penting">Catatan Penting</h2>
<ul>
<li>Token berlaku selama <strong>sesi aktif</strong> di server</li>
<li>Simpan token di tempat aman, jangan bagikan ke publik</li>
<li>Jika token kedaluwarsa, lakukan login ulang</li>
<li>Setiap user hanya bisa memiliki <strong>1 token aktif</strong> (token sebelumnya otomatis revoked saat login baru)</li>
</ul>

        <h1 id="autentikasi">Autentikasi</h1>

    

                                <h2 id="autentikasi-POSTapi-v1-auth-login-warga">Memproses login warga menggunakan NIK dan Nomor Kartu Keluarga via API.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-login-warga">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/auth/login/warga" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"nik\": \"vwgshlzaedjxauui\",
    \"no_kk\": \"zxgnxorrobcytoxm\"
}"
</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/auth/login/warga';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'nik' =&gt; 'vwgshlzaedjxauui',
            'no_kk' =&gt; 'zxgnxorrobcytoxm',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/auth/login/warga"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "nik": "vwgshlzaedjxauui",
    "no_kk": "zxgnxorrobcytoxm"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-login-warga">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Login berhasil&quot;,
    &quot;user&quot;: {
        &quot;nik&quot;: &quot;1118060512900001&quot;,
        &quot;nama_lengkap&quot;: &quot;Ahmad Fauzi&quot;,
        &quot;no_kk&quot;: &quot;1118060512900002&quot;,
        &quot;status_mutasi&quot;: &quot;Tetap&quot;
    },
    &quot;token&quot;: &quot;1|abcdefghijklmnopqrstuvwxyz123456&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-login-warga" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-login-warga"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-login-warga"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-login-warga" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-login-warga">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-login-warga" data-method="POST"
      data-path="api/v1/auth/login/warga"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-login-warga', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-login-warga"
                    onclick="tryItOut('POSTapi-v1-auth-login-warga');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-login-warga"
                    onclick="cancelTryOut('POSTapi-v1-auth-login-warga');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-login-warga"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/login/warga</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-login-warga"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-login-warga"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nik</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nik"                data-endpoint="POSTapi-v1-auth-login-warga"
               value="vwgshlzaedjxauui"
               data-component="body">
    <br>
<p>Kolom value harus berukuran 16 karakter. Example: <code>vwgshlzaedjxauui</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>no_kk</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="no_kk"                data-endpoint="POSTapi-v1-auth-login-warga"
               value="zxgnxorrobcytoxm"
               data-component="body">
    <br>
<p>Kolom value harus berukuran 16 karakter. Example: <code>zxgnxorrobcytoxm</code></p>
        </div>
        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data profil warga yang berhasil login.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Token autentikasi Bearer.</p>
        </div>
                        <h2 id="autentikasi-POSTapi-v1-auth-login-admin">Memproses login administrator menggunakan Username dan Password via API.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-login-admin">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/auth/login/admin" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"username\": \"architecto\",
    \"password\": \";]KXrp\"
}"
</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/auth/login/admin';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'username' =&gt; 'architecto',
            'password' =&gt; ';]KXrp',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/auth/login/admin"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "username": "architecto",
    "password": ";]KXrp"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-login-admin">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Login berhasil&quot;,
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;username&quot;: &quot;admin_desa&quot;,
        &quot;nama_lengkap&quot;: &quot;Administrator Desa&quot;
    },
    &quot;token&quot;: &quot;2|abcdefghijklmnopqrstuvwxyz123456&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-login-admin" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-login-admin"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-login-admin"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-login-admin" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-login-admin">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-login-admin" data-method="POST"
      data-path="api/v1/auth/login/admin"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-login-admin', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-login-admin"
                    onclick="tryItOut('POSTapi-v1-auth-login-admin');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-login-admin"
                    onclick="cancelTryOut('POSTapi-v1-auth-login-admin');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-login-admin"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/login/admin</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-login-admin"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-login-admin"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>username</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="username"                data-endpoint="POSTapi-v1-auth-login-admin"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-auth-login-admin"
               value=";]KXrp"
               data-component="body">
    <br>
<p>Example: <code>;]KXrp</code></p>
        </div>
        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data profil admin yang berhasil login.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Token autentikasi Bearer.</p>
        </div>
                        <h2 id="autentikasi-POSTapi-v1-auth-logout">Memproses keluar log (logout) dengan menghapus token akses saat ini.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-auth-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/auth/logout" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/auth/logout';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/auth/logout"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-logout">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Logout berhasil&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-logout" data-method="POST"
      data-path="api/v1/auth/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-logout"
                    onclick="tryItOut('POSTapi-v1-auth-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-logout"
                    onclick="cancelTryOut('POSTapi-v1-auth-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-auth-logout"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                        <h2 id="autentikasi-GETapi-v1-auth-profile">Mengambil detail informasi profil pengguna yang sedang login.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-auth-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/auth/profile" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/auth/profile';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/auth/profile"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-auth-profile">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;user&quot;: {
        &quot;nik&quot;: &quot;1118060512900001&quot;,
        &quot;nama_lengkap&quot;: &quot;Ahmad Fauzi&quot;,
        &quot;no_kk&quot;: &quot;1118060512900002&quot;,
        &quot;status_mutasi&quot;: &quot;Tetap&quot;,
        &quot;keluarga&quot;: {
            &quot;no_kk&quot;: &quot;1118060512900002&quot;,
            &quot;nama_kepala_keluarga&quot;: &quot;Ahmad Fauzi&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-auth-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-auth-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-auth-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-auth-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-auth-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-auth-profile" data-method="GET"
      data-path="api/v1/auth/profile"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-auth-profile"
                    onclick="tryItOut('GETapi-v1-auth-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-auth-profile"
                    onclick="cancelTryOut('GETapi-v1-auth-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-auth-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/auth/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-auth-profile"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-auth-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-auth-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data profil pengguna yang sedang login.</p>
        </div>
                        <h2 id="autentikasi-POSTapi-v1-auth-bind-telegram">Menghubungkan ID chat Telegram dengan akun warga.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-auth-bind-telegram">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/auth/bind-telegram" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"telegram_chat_id\": \"architecto\"
}"
</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/auth/bind-telegram';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'telegram_chat_id' =&gt; 'architecto',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/auth/bind-telegram"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "telegram_chat_id": "architecto"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-bind-telegram">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Telegram berhasil terhubung&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Hanya warga yang dapat bind Telegram&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-bind-telegram" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-bind-telegram"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-bind-telegram"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-bind-telegram" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-bind-telegram">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-bind-telegram" data-method="POST"
      data-path="api/v1/auth/bind-telegram"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-bind-telegram', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-bind-telegram"
                    onclick="tryItOut('POSTapi-v1-auth-bind-telegram');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-bind-telegram"
                    onclick="cancelTryOut('POSTapi-v1-auth-bind-telegram');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-bind-telegram"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/bind-telegram</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-auth-bind-telegram"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-bind-telegram"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-bind-telegram"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>telegram_chat_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="telegram_chat_id"                data-endpoint="POSTapi-v1-auth-bind-telegram"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                    <h1 id="informasi-publik">Informasi Publik</h1>

    

                                <h2 id="informasi-publik-GETapi-v1-informasi">Mengambil daftar seluruh artikel informasi publik yang telah terbit.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-informasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/informasi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/informasi';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/informasi"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-informasi">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;judul&quot;: &quot;Pengumuman Pemilihan Keuchik 2026&quot;,
            &quot;slug&quot;: &quot;pengumuman-pemilihan-keuchik-2026&quot;,
            &quot;konten&quot;: &quot;Diumumkan kepada seluruh warga Gampong Udeung...&quot;,
            &quot;kategori&quot;: &quot;Pengumuman&quot;,
            &quot;cover_image&quot;: &quot;images/pemilihan.jpg&quot;,
            &quot;author&quot;: {
                &quot;id&quot;: 1,
                &quot;nama_lengkap&quot;: &quot;Administrator Desa&quot;
            }
        }
    ],
    &quot;per_page&quot;: 10
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-informasi" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-informasi"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-informasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-informasi" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-informasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-informasi" data-method="GET"
      data-path="api/v1/informasi"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-informasi', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-informasi"
                    onclick="tryItOut('GETapi-v1-informasi');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-informasi"
                    onclick="cancelTryOut('GETapi-v1-informasi');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-informasi"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/informasi</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-informasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-informasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Halaman saat ini.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Daftar artikel informasi publik.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Judul artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Slug unik artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>konten</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Isi konten artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Kategori artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>author</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data penulis artikel.</p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah item per halaman (10).</p>
        </div>
                        <h2 id="informasi-publik-GETapi-v1-informasi--slug-">Menampilkan detail informasi publik tertentu berdasarkan slug.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-informasi--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/informasi/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/informasi/architecto';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/informasi/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-informasi--slug-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;judul&quot;: &quot;Pengumuman Pemilihan Keuchik 2026&quot;,
        &quot;slug&quot;: &quot;pengumuman-pemilihan-keuchik-2026&quot;,
        &quot;konten&quot;: &quot;Diumumkan kepada seluruh warga Gampong Udeung...&quot;,
        &quot;kategori&quot;: &quot;Pengumuman&quot;,
        &quot;cover_image&quot;: &quot;images/pemilihan.jpg&quot;,
        &quot;is_published&quot;: true,
        &quot;author&quot;: {
            &quot;id&quot;: 1,
            &quot;nama_lengkap&quot;: &quot;Administrator Desa&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-informasi--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-informasi--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-informasi--slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-informasi--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-informasi--slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-informasi--slug-" data-method="GET"
      data-path="api/v1/informasi/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-informasi--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-informasi--slug-"
                    onclick="tryItOut('GETapi-v1-informasi--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-informasi--slug-"
                    onclick="cancelTryOut('GETapi-v1-informasi--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-informasi--slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/informasi/{slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-informasi--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-informasi--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="GETapi-v1-informasi--slug-"
               value="architecto"
               data-component="url">
    <br>
<p>The slug of the informasi. Example: <code>architecto</code></p>
            </div>
                    </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Detail artikel informasi publik.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Judul artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Slug unik artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>konten</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Isi konten artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Kategori artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>author</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data penulis artikel.</p>
                    </div>
                                    </details>
        </div>
                    <h1 id="layanan-warga">Layanan Warga</h1>

    

                                <h2 id="layanan-warga-GETapi-v1-surat-kategori">Mengambil daftar seluruh kategori surat yang aktif untuk diajukan.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-surat-kategori">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/surat/kategori" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/surat/kategori';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/surat/kategori"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-surat-kategori">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;nama_surat&quot;: &quot;Surat Keterangan Domisili&quot;,
            &quot;deskripsi&quot;: &quot;Surat keterangan tempat tinggal warga&quot;,
            &quot;status&quot;: &quot;active&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-surat-kategori" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-surat-kategori"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-surat-kategori"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-surat-kategori" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-surat-kategori">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-surat-kategori" data-method="GET"
      data-path="api/v1/surat/kategori"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-surat-kategori', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-surat-kategori"
                    onclick="tryItOut('GETapi-v1-surat-kategori');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-surat-kategori"
                    onclick="cancelTryOut('GETapi-v1-surat-kategori');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-surat-kategori"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/surat/kategori</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-surat-kategori"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-surat-kategori"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-surat-kategori"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Daftar kategori surat yang aktif.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID kategori surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nama_surat</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Nama jenis surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>deskripsi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Deskripsi singkat surat.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="layanan-warga-GETapi-v1-surat-kategori--id-">Mengambil spesifikasi skema data dan syarat dari satu kategori surat.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-surat-kategori--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/surat/kategori/architecto" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/surat/kategori/architecto';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/surat/kategori/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-surat-kategori--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;nama_surat&quot;: &quot;Surat Keterangan Domisili&quot;,
        &quot;deskripsi&quot;: &quot;Surat keterangan tempat tinggal warga&quot;,
        &quot;skema_data&quot;: {
            &quot;alamat&quot;: {
                &quot;type&quot;: &quot;text&quot;,
                &quot;label&quot;: &quot;Alamat Lengkap&quot;
            }
        },
        &quot;syarat&quot;: [
            &quot;Foto KTP&quot;,
            &quot;Foto KK&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-surat-kategori--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-surat-kategori--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-surat-kategori--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-surat-kategori--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-surat-kategori--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-surat-kategori--id-" data-method="GET"
      data-path="api/v1/surat/kategori/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-surat-kategori--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-surat-kategori--id-"
                    onclick="tryItOut('GETapi-v1-surat-kategori--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-surat-kategori--id-"
                    onclick="cancelTryOut('GETapi-v1-surat-kategori--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-surat-kategori--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/surat/kategori/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-surat-kategori--id-"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-surat-kategori--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-surat-kategori--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-surat-kategori--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the kategori. Example: <code>architecto</code></p>
            </div>
                    </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Detail kategori surat beserta skema data dan syarat.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID kategori surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nama_surat</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Nama jenis surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>skema_data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Skema data isian yang diperlukan.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>syarat</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Daftar syarat dokumen yang harus dilampirkan.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="layanan-warga-POSTapi-v1-surat-pengajuan">Memproses pengiriman permohonan pengajuan surat pelayanan baru dari warga.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-surat-pengajuan">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/surat/pengajuan" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"kategori_surat_id\": \"architecto\",
    \"data_isian\": [],
    \"file_syarat\": []
}"
</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/surat/pengajuan';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'kategori_surat_id' =&gt; 'architecto',
            'data_isian' =&gt; [],
            'file_syarat' =&gt; [],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/surat/pengajuan"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "kategori_surat_id": "architecto",
    "data_isian": [],
    "file_syarat": []
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-surat-pengajuan">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Pengajuan surat berhasil dibuat&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;nik_pemohon&quot;: &quot;1118060512900001&quot;,
        &quot;kategori_surat_id&quot;: 1,
        &quot;nomor_registrasi&quot;: &quot;REG/2026/0001&quot;,
        &quot;status&quot;: &quot;Pending&quot;,
        &quot;data_isian&quot;: {
            &quot;alamat&quot;: &quot;Jl. Merdeka No. 10&quot;
        },
        &quot;file_syarat&quot;: [
            &quot;uploads/ktp.jpg&quot;
        ],
        &quot;kategori&quot;: {
            &quot;id&quot;: 1,
            &quot;nama_surat&quot;: &quot;Surat Keterangan Domisili&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-surat-pengajuan" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-surat-pengajuan"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-surat-pengajuan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-surat-pengajuan" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-surat-pengajuan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-surat-pengajuan" data-method="POST"
      data-path="api/v1/surat/pengajuan"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-surat-pengajuan', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-surat-pengajuan"
                    onclick="tryItOut('POSTapi-v1-surat-pengajuan');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-surat-pengajuan"
                    onclick="cancelTryOut('POSTapi-v1-surat-pengajuan');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-surat-pengajuan"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/surat/pengajuan</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-surat-pengajuan"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-surat-pengajuan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-surat-pengajuan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>kategori_surat_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="kategori_surat_id"                data-endpoint="POSTapi-v1-surat-pengajuan"
               value="architecto"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>data_isian</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="data_isian"                data-endpoint="POSTapi-v1-surat-pengajuan"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>file_syarat</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="file_syarat"                data-endpoint="POSTapi-v1-surat-pengajuan"
               value=""
               data-component="body">
    <br>

        </div>
        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data pengajuan surat yang berhasil dibuat.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID pengajuan surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nomor_registrasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Nomor registrasi unik pengajuan.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status pengajuan awal ("Pending").</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Informasi kategori surat terkait.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="layanan-warga-GETapi-v1-surat-pengajuan">Mengambil daftar riwayat permohonan surat milik warga yang sedang login.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-surat-pengajuan">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/surat/pengajuan" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/surat/pengajuan';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/surat/pengajuan"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-surat-pengajuan">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;nik_pemohon&quot;: &quot;1118060512900001&quot;,
            &quot;nomor_registrasi&quot;: &quot;REG/2026/0001&quot;,
            &quot;status&quot;: &quot;Disetujui&quot;,
            &quot;kategori&quot;: {
                &quot;id&quot;: 1,
                &quot;nama_surat&quot;: &quot;Surat Keterangan Domisili&quot;
            },
            &quot;tracking&quot;: [
                {
                    &quot;status_baru&quot;: &quot;Pending&quot;,
                    &quot;keterangan_update&quot;: &quot;Pengajuan surat dibuat&quot;
                }
            ]
        }
    ],
    &quot;per_page&quot;: 10,
    &quot;total&quot;: 1
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-surat-pengajuan" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-surat-pengajuan"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-surat-pengajuan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-surat-pengajuan" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-surat-pengajuan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-surat-pengajuan" data-method="GET"
      data-path="api/v1/surat/pengajuan"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-surat-pengajuan', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-surat-pengajuan"
                    onclick="tryItOut('GETapi-v1-surat-pengajuan');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-surat-pengajuan"
                    onclick="cancelTryOut('GETapi-v1-surat-pengajuan');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-surat-pengajuan"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/surat/pengajuan</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-surat-pengajuan"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-surat-pengajuan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-surat-pengajuan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Halaman saat ini.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Daftar pengajuan surat milik warga.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID pengajuan surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nomor_registrasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Nomor registrasi pengajuan.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status pengajuan saat ini.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Informasi kategori surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>tracking</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Riwayat perubahan status pengajuan.</p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah item per halaman (10).</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>total</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Total seluruh pengajuan.</p>
        </div>
                        <h2 id="layanan-warga-GETapi-v1-surat-pengajuan--id-">Mengambil detail dari permohonan surat tertentu beserta riwayat tracking-nya.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-surat-pengajuan--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/surat/pengajuan/architecto" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/surat/pengajuan/architecto';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/surat/pengajuan/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-surat-pengajuan--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;nik_pemohon&quot;: &quot;1118060512900001&quot;,
        &quot;nomor_registrasi&quot;: &quot;REG/2026/0001&quot;,
        &quot;status&quot;: &quot;Disetujui&quot;,
        &quot;pemohon&quot;: {
            &quot;nik&quot;: &quot;1118060512900001&quot;,
            &quot;nama_lengkap&quot;: &quot;Ahmad Fauzi&quot;
        },
        &quot;kategori&quot;: {
            &quot;id&quot;: 1,
            &quot;nama_surat&quot;: &quot;Surat Keterangan Domisili&quot;
        },
        &quot;tracking&quot;: [
            {
                &quot;status_sebelumnya&quot;: null,
                &quot;status_baru&quot;: &quot;Pending&quot;,
                &quot;keterangan_update&quot;: &quot;Pengajuan surat dibuat&quot;,
                &quot;diupdate_oleh&quot;: null
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-surat-pengajuan--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-surat-pengajuan--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-surat-pengajuan--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-surat-pengajuan--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-surat-pengajuan--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-surat-pengajuan--id-" data-method="GET"
      data-path="api/v1/surat/pengajuan/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-surat-pengajuan--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-surat-pengajuan--id-"
                    onclick="tryItOut('GETapi-v1-surat-pengajuan--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-surat-pengajuan--id-"
                    onclick="cancelTryOut('GETapi-v1-surat-pengajuan--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-surat-pengajuan--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/surat/pengajuan/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-surat-pengajuan--id-"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-surat-pengajuan--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-surat-pengajuan--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-surat-pengajuan--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the pengajuan. Example: <code>architecto</code></p>
            </div>
                    </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Detail lengkap pengajuan surat.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID pengajuan surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nomor_registrasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Nomor registrasi pengajuan.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status pengajuan saat ini.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>pemohon</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data pemohon (warga).</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Informasi kategori surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>tracking</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Riwayat perubahan status beserta info pengupdate.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="layanan-warga-POSTapi-v1-mutasi">Memproses pengajuan mutasi penduduk baru (kelahiran/kematian/kedatangan/kepindahan).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-mutasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/mutasi" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"nik\": \"architecto\",
    \"jenis_mutasi\": \"Kepindahan\",
    \"tanggal_mutasi\": \"2026-07-22T20:40:21\",
    \"keterangan\": \"architecto\",
    \"dokumen_bukti\": \"architecto\"
}"
</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/mutasi';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'nik' =&gt; 'architecto',
            'jenis_mutasi' =&gt; 'Kepindahan',
            'tanggal_mutasi' =&gt; '2026-07-22T20:40:21',
            'keterangan' =&gt; 'architecto',
            'dokumen_bukti' =&gt; 'architecto',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/mutasi"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "nik": "architecto",
    "jenis_mutasi": "Kepindahan",
    "tanggal_mutasi": "2026-07-22T20:40:21",
    "keterangan": "architecto",
    "dokumen_bukti": "architecto"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-mutasi">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Pengajuan mutasi berhasil dibuat&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;nik&quot;: &quot;1118060512900001&quot;,
        &quot;jenis_mutasi&quot;: &quot;Kepindahan&quot;,
        &quot;tanggal_mutasi&quot;: &quot;2026-07-10&quot;,
        &quot;keterangan&quot;: &quot;Pindah domisili ke Kota Banda Aceh&quot;,
        &quot;dokumen_bukti&quot;: &quot;documents/surat_pindah.pdf&quot;,
        &quot;status_verifikasi&quot;: &quot;Pending&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-mutasi" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-mutasi"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-mutasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-mutasi" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-mutasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-mutasi" data-method="POST"
      data-path="api/v1/mutasi"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-mutasi', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-mutasi"
                    onclick="tryItOut('POSTapi-v1-mutasi');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-mutasi"
                    onclick="cancelTryOut('POSTapi-v1-mutasi');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-mutasi"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/mutasi</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-mutasi"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-mutasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-mutasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nik</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nik"                data-endpoint="POSTapi-v1-mutasi"
               value="architecto"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>jenis_mutasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="jenis_mutasi"                data-endpoint="POSTapi-v1-mutasi"
               value="Kepindahan"
               data-component="body">
    <br>
<p>Example: <code>Kepindahan</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Kelahiran</code></li> <li><code>Kematian</code></li> <li><code>Kedatangan</code></li> <li><code>Kepindahan</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>tanggal_mutasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tanggal_mutasi"                data-endpoint="POSTapi-v1-mutasi"
               value="2026-07-22T20:40:21"
               data-component="body">
    <br>
<p>Kolom value bukan tanggal yang valid. Example: <code>2026-07-22T20:40:21</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>keterangan</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="keterangan"                data-endpoint="POSTapi-v1-mutasi"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dokumen_bukti</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="dokumen_bukti"                data-endpoint="POSTapi-v1-mutasi"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data pengajuan mutasi yang berhasil dibuat.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID pengajuan mutasi.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nik</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>NIK penduduk.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>jenis_mutasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jenis mutasi yang diajukan.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status_verifikasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status verifikasi awal ("Pending").</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="layanan-warga-GETapi-v1-mutasi">Mengambil daftar riwayat mutasi milik warga yang sedang login.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-mutasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/mutasi" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/mutasi';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/mutasi"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-mutasi">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;nik&quot;: &quot;1118060512900001&quot;,
            &quot;jenis_mutasi&quot;: &quot;Kepindahan&quot;,
            &quot;tanggal_mutasi&quot;: &quot;2026-07-10&quot;,
            &quot;status_verifikasi&quot;: &quot;Pending&quot;,
            &quot;penduduk&quot;: {
                &quot;nik&quot;: &quot;1118060512900001&quot;,
                &quot;nama_lengkap&quot;: &quot;Ahmad Fauzi&quot;
            }
        }
    ],
    &quot;per_page&quot;: 10
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-mutasi" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-mutasi"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-mutasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-mutasi" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-mutasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-mutasi" data-method="GET"
      data-path="api/v1/mutasi"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-mutasi', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-mutasi"
                    onclick="tryItOut('GETapi-v1-mutasi');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-mutasi"
                    onclick="cancelTryOut('GETapi-v1-mutasi');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-mutasi"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/mutasi</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-mutasi"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-mutasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-mutasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Halaman saat ini.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Daftar pengajuan mutasi milik warga.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID pengajuan mutasi.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nik</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>NIK penduduk.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>jenis_mutasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jenis mutasi.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status_verifikasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status verifikasi saat ini.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>penduduk</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data penduduk terkait.</p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah item per halaman (10).</p>
        </div>
                    <h1 id="administrasi">Administrasi</h1>

    

                        <h2 id="administrasi-pengajuan-surat">Pengajuan Surat</h2>
                                                    <h2 id="administrasi-GETapi-v1-admin-surat-pengajuan">Mengambil data seluruh pengajuan surat untuk kebutuhan panel admin desa.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-admin-surat-pengajuan">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/admin/surat/pengajuan" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/surat/pengajuan';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/surat/pengajuan"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-surat-pengajuan">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;nik_pemohon&quot;: &quot;1118060512900001&quot;,
            &quot;nomor_registrasi&quot;: &quot;REG/2026/0001&quot;,
            &quot;status&quot;: &quot;Pending&quot;,
            &quot;kategori&quot;: {
                &quot;id&quot;: 1,
                &quot;nama_surat&quot;: &quot;Surat Keterangan Domisili&quot;
            },
            &quot;pemohon&quot;: {
                &quot;nik&quot;: &quot;1118060512900001&quot;,
                &quot;nama_lengkap&quot;: &quot;Ahmad Fauzi&quot;
            }
        }
    ],
    &quot;per_page&quot;: 20
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-surat-pengajuan" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-surat-pengajuan"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-surat-pengajuan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-surat-pengajuan" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-surat-pengajuan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-surat-pengajuan" data-method="GET"
      data-path="api/v1/admin/surat/pengajuan"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-surat-pengajuan', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-surat-pengajuan"
                    onclick="tryItOut('GETapi-v1-admin-surat-pengajuan');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-surat-pengajuan"
                    onclick="cancelTryOut('GETapi-v1-admin-surat-pengajuan');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-surat-pengajuan"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/surat/pengajuan</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-surat-pengajuan"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-surat-pengajuan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-surat-pengajuan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Halaman saat ini.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Daftar pengajuan surat untuk admin.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID pengajuan surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nomor_registrasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Nomor registrasi pengajuan.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status pengajuan.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Informasi kategori surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>pemohon</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data pemohon.</p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah item per halaman (20).</p>
        </div>
                        <h2 id="administrasi-POSTapi-v1-admin-surat-pengajuan--id--approve">Menyetujui pengajuan surat dan memicu proses tanda tangan digital PDF otomatis.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-admin-surat-pengajuan--id--approve">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/admin/surat/pengajuan/architecto/approve" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/surat/pengajuan/architecto/approve';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/surat/pengajuan/architecto/approve"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-surat-pengajuan--id--approve">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Pengajuan berhasil disetujui&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;nomor_registrasi&quot;: &quot;REG/2026/0001&quot;,
        &quot;status&quot;: &quot;Disetujui&quot;,
        &quot;diverifikasi_oleh&quot;: 1,
        &quot;nik_pemohon&quot;: &quot;1118060512900001&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-surat-pengajuan--id--approve" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-surat-pengajuan--id--approve"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-surat-pengajuan--id--approve"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-surat-pengajuan--id--approve" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-surat-pengajuan--id--approve">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-surat-pengajuan--id--approve" data-method="POST"
      data-path="api/v1/admin/surat/pengajuan/{id}/approve"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-surat-pengajuan--id--approve', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-surat-pengajuan--id--approve"
                    onclick="tryItOut('POSTapi-v1-admin-surat-pengajuan--id--approve');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-surat-pengajuan--id--approve"
                    onclick="cancelTryOut('POSTapi-v1-admin-surat-pengajuan--id--approve');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-surat-pengajuan--id--approve"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/surat/pengajuan/{id}/approve</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--approve"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--approve"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--approve"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--approve"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the pengajuan. Example: <code>architecto</code></p>
            </div>
                    </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data pengajuan surat yang telah disetujui.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status baru ("Disetujui").</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>diverifikasi_oleh</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID admin yang menyetujui.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="administrasi-POSTapi-v1-admin-surat-pengajuan--id--reject">Menolak pengajuan permohonan surat warga dengan menyertakan catatan penolakan.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-admin-surat-pengajuan--id--reject">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/admin/surat/pengajuan/architecto/reject" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"catatan_penolakan\": \"architecto\"
}"
</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/surat/pengajuan/architecto/reject';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'catatan_penolakan' =&gt; 'architecto',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/surat/pengajuan/architecto/reject"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "catatan_penolakan": "architecto"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-surat-pengajuan--id--reject">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Pengajuan ditolak&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;nomor_registrasi&quot;: &quot;REG/2026/0001&quot;,
        &quot;status&quot;: &quot;Ditolak&quot;,
        &quot;catatan_penolakan&quot;: &quot;Berkas tidak lengkap&quot;,
        &quot;diverifikasi_oleh&quot;: 1
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-surat-pengajuan--id--reject" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-surat-pengajuan--id--reject"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-surat-pengajuan--id--reject"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-surat-pengajuan--id--reject" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-surat-pengajuan--id--reject">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-surat-pengajuan--id--reject" data-method="POST"
      data-path="api/v1/admin/surat/pengajuan/{id}/reject"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-surat-pengajuan--id--reject', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-surat-pengajuan--id--reject"
                    onclick="tryItOut('POSTapi-v1-admin-surat-pengajuan--id--reject');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-surat-pengajuan--id--reject"
                    onclick="cancelTryOut('POSTapi-v1-admin-surat-pengajuan--id--reject');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-surat-pengajuan--id--reject"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/surat/pengajuan/{id}/reject</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--reject"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--reject"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--reject"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--reject"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the pengajuan. Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>catatan_penolakan</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="catatan_penolakan"                data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--reject"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data pengajuan surat yang telah ditolak.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status baru ("Ditolak").</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>catatan_penolakan</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Catatan alasan penolakan.</p>
                    </div>
                                    </details>
        </div>
                                    <h2 id="administrasi-mutasi-penduduk">Mutasi Penduduk</h2>
                                                    <h2 id="administrasi-GETapi-v1-admin-mutasi">Mengambil seluruh data mutasi penduduk untuk panel admin desa.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-admin-mutasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/admin/mutasi" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/mutasi';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/mutasi"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-mutasi">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;nik&quot;: &quot;1118060512900001&quot;,
            &quot;jenis_mutasi&quot;: &quot;Kepindahan&quot;,
            &quot;tanggal_mutasi&quot;: &quot;2026-07-10&quot;,
            &quot;status_verifikasi&quot;: &quot;Pending&quot;,
            &quot;penduduk&quot;: {
                &quot;nik&quot;: &quot;1118060512900001&quot;,
                &quot;nama_lengkap&quot;: &quot;Ahmad Fauzi&quot;
            },
            &quot;verifikator&quot;: null
        }
    ],
    &quot;per_page&quot;: 20
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-mutasi" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-mutasi"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-mutasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-mutasi" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-mutasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-mutasi" data-method="GET"
      data-path="api/v1/admin/mutasi"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-mutasi', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-mutasi"
                    onclick="tryItOut('GETapi-v1-admin-mutasi');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-mutasi"
                    onclick="cancelTryOut('GETapi-v1-admin-mutasi');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-mutasi"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/mutasi</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-mutasi"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-mutasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-mutasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Halaman saat ini.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Daftar pengajuan mutasi untuk admin.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID pengajuan mutasi.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nik</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>NIK penduduk.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>jenis_mutasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jenis mutasi.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status_verifikasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status verifikasi.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>penduduk</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data penduduk terkait.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>verifikator</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data admin verifikator (jika sudah diverifikasi).</p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah item per halaman (20).</p>
        </div>
                        <h2 id="administrasi-POSTapi-v1-admin-mutasi--id--approve">Menyetujui pengajuan mutasi dan memperbarui status aktif/pasif kependudukan warga terkait.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-admin-mutasi--id--approve">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/admin/mutasi/architecto/approve" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/mutasi/architecto/approve';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/mutasi/architecto/approve"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-mutasi--id--approve">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Mutasi berhasil disetujui&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;nik&quot;: &quot;1118060512900001&quot;,
        &quot;jenis_mutasi&quot;: &quot;Kepindahan&quot;,
        &quot;status_verifikasi&quot;: &quot;Disetujui&quot;,
        &quot;diverifikasi_oleh&quot;: 1
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-mutasi--id--approve" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-mutasi--id--approve"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-mutasi--id--approve"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-mutasi--id--approve" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-mutasi--id--approve">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-mutasi--id--approve" data-method="POST"
      data-path="api/v1/admin/mutasi/{id}/approve"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-mutasi--id--approve', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-mutasi--id--approve"
                    onclick="tryItOut('POSTapi-v1-admin-mutasi--id--approve');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-mutasi--id--approve"
                    onclick="cancelTryOut('POSTapi-v1-admin-mutasi--id--approve');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-mutasi--id--approve"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/mutasi/{id}/approve</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-mutasi--id--approve"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-mutasi--id--approve"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-mutasi--id--approve"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="POSTapi-v1-admin-mutasi--id--approve"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the mutasi. Example: <code>architecto</code></p>
            </div>
                    </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data pengajuan mutasi yang telah disetujui.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status_verifikasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status verifikasi baru ("Disetujui").</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>diverifikasi_oleh</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID admin yang menyetujui.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="administrasi-POSTapi-v1-admin-mutasi--id--reject">Menolak pengajuan mutasi penduduk.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-admin-mutasi--id--reject">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/admin/mutasi/architecto/reject" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/mutasi/architecto/reject';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/mutasi/architecto/reject"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-mutasi--id--reject">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Mutasi ditolak&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;nik&quot;: &quot;1118060512900001&quot;,
        &quot;jenis_mutasi&quot;: &quot;Kepindahan&quot;,
        &quot;status_verifikasi&quot;: &quot;Ditolak&quot;,
        &quot;diverifikasi_oleh&quot;: 1
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-mutasi--id--reject" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-mutasi--id--reject"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-mutasi--id--reject"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-mutasi--id--reject" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-mutasi--id--reject">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-mutasi--id--reject" data-method="POST"
      data-path="api/v1/admin/mutasi/{id}/reject"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-mutasi--id--reject', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-mutasi--id--reject"
                    onclick="tryItOut('POSTapi-v1-admin-mutasi--id--reject');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-mutasi--id--reject"
                    onclick="cancelTryOut('POSTapi-v1-admin-mutasi--id--reject');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-mutasi--id--reject"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/mutasi/{id}/reject</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-mutasi--id--reject"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-mutasi--id--reject"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-mutasi--id--reject"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="POSTapi-v1-admin-mutasi--id--reject"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the mutasi. Example: <code>architecto</code></p>
            </div>
                    </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data pengajuan mutasi yang telah ditolak.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status_verifikasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status verifikasi baru ("Ditolak").</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>diverifikasi_oleh</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID admin yang menolak.</p>
                    </div>
                                    </details>
        </div>
                                    <h2 id="administrasi-informasi">Informasi</h2>
                                                    <h2 id="administrasi-GETapi-v1-admin-informasi">Mengambil daftar informasi publik untuk keperluan panel admin (termasuk draf).</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-admin-informasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/admin/informasi" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/informasi';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/informasi"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-informasi">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;judul&quot;: &quot;Pengumuman Pemilihan Keuchik 2026&quot;,
            &quot;slug&quot;: &quot;pengumuman-pemilihan-keuchik-2026&quot;,
            &quot;is_published&quot;: true,
            &quot;kategori&quot;: &quot;Pengumuman&quot;,
            &quot;author&quot;: {
                &quot;id&quot;: 1,
                &quot;nama_lengkap&quot;: &quot;Administrator Desa&quot;
            }
        }
    ],
    &quot;per_page&quot;: 20
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-informasi" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-informasi"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-informasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-informasi" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-informasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-informasi" data-method="GET"
      data-path="api/v1/admin/informasi"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-informasi', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-informasi"
                    onclick="tryItOut('GETapi-v1-admin-informasi');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-informasi"
                    onclick="cancelTryOut('GETapi-v1-admin-informasi');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-informasi"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/informasi</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-informasi"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-informasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-informasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Halaman saat ini.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Daftar artikel informasi publik untuk admin.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Judul artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>is_published</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status publikasi artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>author</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data penulis artikel.</p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah item per halaman (20).</p>
        </div>
                        <h2 id="administrasi-POSTapi-v1-admin-informasi">Menyimpan artikel informasi publik baru ke database.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-admin-informasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/admin/informasi" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"judul\": \"vwgshl\",
    \"konten\": \"architecto\",
    \"kategori\": \"wgshlz\",
    \"cover_image\": \"architecto\",
    \"is_published\": false
}"
</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/informasi';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'judul' =&gt; 'vwgshl',
            'konten' =&gt; 'architecto',
            'kategori' =&gt; 'wgshlz',
            'cover_image' =&gt; 'architecto',
            'is_published' =&gt; false,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/informasi"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "judul": "vwgshl",
    "konten": "architecto",
    "kategori": "wgshlz",
    "cover_image": "architecto",
    "is_published": false
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-informasi">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Informasi berhasil dibuat&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;judul&quot;: &quot;Pengumuman Pemilihan Keuchik 2026&quot;,
        &quot;slug&quot;: &quot;pengumuman-pemilihan-keuchik-2026&quot;,
        &quot;konten&quot;: &quot;Diumumkan kepada seluruh warga Gampong Udeung...&quot;,
        &quot;kategori&quot;: &quot;Pengumuman&quot;,
        &quot;cover_image&quot;: &quot;images/pemilihan.jpg&quot;,
        &quot;is_published&quot;: true,
        &quot;author_id&quot;: 1
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-informasi" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-informasi"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-informasi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-informasi" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-informasi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-informasi" data-method="POST"
      data-path="api/v1/admin/informasi"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-informasi', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-informasi"
                    onclick="tryItOut('POSTapi-v1-admin-informasi');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-informasi"
                    onclick="cancelTryOut('POSTapi-v1-admin-informasi');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-informasi"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/informasi</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-informasi"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-informasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-informasi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="judul"                data-endpoint="POSTapi-v1-admin-informasi"
               value="vwgshl"
               data-component="body">
    <br>
<p>Kolom value tidak boleh berukuran lebih besar dari 255 karakter. Example: <code>vwgshl</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>konten</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="konten"                data-endpoint="POSTapi-v1-admin-informasi"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="kategori"                data-endpoint="POSTapi-v1-admin-informasi"
               value="wgshlz"
               data-component="body">
    <br>
<p>Kolom value tidak boleh berukuran lebih besar dari 50 karakter. Example: <code>wgshlz</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>cover_image</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="cover_image"                data-endpoint="POSTapi-v1-admin-informasi"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_published</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-v1-admin-informasi" style="display: none">
            <input type="radio" name="is_published"
                   value="true"
                   data-endpoint="POSTapi-v1-admin-informasi"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-v1-admin-informasi" style="display: none">
            <input type="radio" name="is_published"
                   value="false"
                   data-endpoint="POSTapi-v1-admin-informasi"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data artikel yang berhasil dibuat.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Judul artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Slug unik artikel (otomatis dibuat dari judul).</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>is_published</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status publikasi artikel.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="administrasi-PUTapi-v1-admin-informasi--id-">Memperbarui detail artikel informasi publik tertentu berdasarkan ID.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-admin-informasi--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "https://127.0.0.1:8000/api/v1/admin/informasi/architecto" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"judul\": \"vwgshl\",
    \"konten\": \"architecto\",
    \"kategori\": \"wgshlz\",
    \"cover_image\": \"architecto\",
    \"is_published\": false
}"
</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/informasi/architecto';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'judul' =&gt; 'vwgshl',
            'konten' =&gt; 'architecto',
            'kategori' =&gt; 'wgshlz',
            'cover_image' =&gt; 'architecto',
            'is_published' =&gt; false,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/informasi/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "judul": "vwgshl",
    "konten": "architecto",
    "kategori": "wgshlz",
    "cover_image": "architecto",
    "is_published": false
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-admin-informasi--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Informasi berhasil diupdate&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;judul&quot;: &quot;Judul Baru&quot;,
        &quot;slug&quot;: &quot;judul-baru&quot;,
        &quot;konten&quot;: &quot;Konten artikel yang diperbarui...&quot;,
        &quot;kategori&quot;: &quot;Berita&quot;,
        &quot;is_published&quot;: true
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-admin-informasi--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-admin-informasi--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-admin-informasi--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-admin-informasi--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-admin-informasi--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-admin-informasi--id-" data-method="PUT"
      data-path="api/v1/admin/informasi/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-admin-informasi--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-admin-informasi--id-"
                    onclick="tryItOut('PUTapi-v1-admin-informasi--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-admin-informasi--id-"
                    onclick="cancelTryOut('PUTapi-v1-admin-informasi--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-admin-informasi--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/admin/informasi/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the informasi. Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="judul"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="vwgshl"
               data-component="body">
    <br>
<p>Kolom value tidak boleh berukuran lebih besar dari 255 karakter. Example: <code>vwgshl</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>konten</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="konten"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="kategori"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="wgshlz"
               data-component="body">
    <br>
<p>Kolom value tidak boleh berukuran lebih besar dari 50 karakter. Example: <code>wgshlz</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>cover_image</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="cover_image"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_published</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-v1-admin-informasi--id-" style="display: none">
            <input type="radio" name="is_published"
                   value="true"
                   data-endpoint="PUTapi-v1-admin-informasi--id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-v1-admin-informasi--id-" style="display: none">
            <input type="radio" name="is_published"
                   value="false"
                   data-endpoint="PUTapi-v1-admin-informasi--id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data artikel yang diperbarui.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>ID artikel.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Judul artikel terbaru.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="administrasi-DELETEapi-v1-admin-informasi--id-">Menghapus artikel informasi publik tertentu dari database.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-admin-informasi--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "https://127.0.0.1:8000/api/v1/admin/informasi/architecto" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/informasi/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/informasi/architecto"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-admin-informasi--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Informasi berhasil dihapus&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-admin-informasi--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-admin-informasi--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-admin-informasi--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-admin-informasi--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-admin-informasi--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-admin-informasi--id-" data-method="DELETE"
      data-path="api/v1/admin/informasi/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-admin-informasi--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-admin-informasi--id-"
                    onclick="tryItOut('DELETEapi-v1-admin-informasi--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-admin-informasi--id-"
                    onclick="cancelTryOut('DELETEapi-v1-admin-informasi--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-admin-informasi--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/admin/informasi/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-admin-informasi--id-"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-admin-informasi--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-admin-informasi--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="DELETEapi-v1-admin-informasi--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the informasi. Example: <code>architecto</code></p>
            </div>
                    </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                    <h1 id="statistik-verifikasi">Statistik & Verifikasi</h1>

    

                                <h2 id="statistik-verifikasi-GETapi-v1-statistik-demografi">Mengambil rangkuman data demografi kependudukan gampong.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-statistik-demografi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/statistik/demografi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/statistik/demografi';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/statistik/demografi"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-statistik-demografi">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;total_penduduk&quot;: 2500,
        &quot;laki_laki&quot;: 1250,
        &quot;perempuan&quot;: 1250,
        &quot;kelompok_usia&quot;: [
            {
                &quot;kelompok&quot;: &quot;0-14&quot;,
                &quot;jumlah&quot;: 600
            },
            {
                &quot;kelompok&quot;: &quot;15-64&quot;,
                &quot;jumlah&quot;: 1700
            },
            {
                &quot;kelompok&quot;: &quot;65+&quot;,
                &quot;jumlah&quot;: 200
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-statistik-demografi" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-statistik-demografi"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-statistik-demografi"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-statistik-demografi" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-statistik-demografi">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-statistik-demografi" data-method="GET"
      data-path="api/v1/statistik/demografi"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-statistik-demografi', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-statistik-demografi"
                    onclick="tryItOut('GETapi-v1-statistik-demografi');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-statistik-demografi"
                    onclick="cancelTryOut('GETapi-v1-statistik-demografi');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-statistik-demografi"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/statistik/demografi</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-statistik-demografi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-statistik-demografi"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data statistik demografi kependudukan.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>total_penduduk</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Total jumlah penduduk.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>laki_laki</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah penduduk laki-laki.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>perempuan</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah penduduk perempuan.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>kelompok_usia</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Distribusi penduduk berdasarkan kelompok usia.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="statistik-verifikasi-GETapi-v1-statistik-layanan">Mengambil rangkuman data statistik layanan administrasi persuratan.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-statistik-layanan">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/statistik/layanan" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/statistik/layanan';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/statistik/layanan"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-statistik-layanan">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;total_pengajuan&quot;: 150,
        &quot;menunggu&quot;: 10,
        &quot;disetujui&quot;: 50,
        &quot;ditolak&quot;: 5,
        &quot;selesai&quot;: 85
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-statistik-layanan" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-statistik-layanan"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-statistik-layanan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-statistik-layanan" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-statistik-layanan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-statistik-layanan" data-method="GET"
      data-path="api/v1/statistik/layanan"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-statistik-layanan', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-statistik-layanan"
                    onclick="tryItOut('GETapi-v1-statistik-layanan');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-statistik-layanan"
                    onclick="cancelTryOut('GETapi-v1-statistik-layanan');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-statistik-layanan"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/statistik/layanan</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-statistik-layanan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-statistik-layanan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data statistik layanan persuratan.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>total_pengajuan</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Total seluruh pengajuan surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>menunggu</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah pengajuan dengan status Pending.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>disetujui</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah pengajuan dengan status Disetujui.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>ditolak</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah pengajuan dengan status Ditolak.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>selesai</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Jumlah pengajuan dengan status Selesai.</p>
                    </div>
                                    </details>
        </div>
                        <h2 id="statistik-verifikasi-GETapi-v1-verifikasi--hash-">Memverifikasi tanda tangan digital surat berdasarkan hash QR Code.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-verifikasi--hash-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://127.0.0.1:8000/api/v1/verifikasi/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/verifikasi/architecto';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/verifikasi/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-verifikasi--hash-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;valid&quot;: true,
    &quot;message&quot;: &quot;Dokumen valid&quot;,
    &quot;data&quot;: {
        &quot;nomor_registrasi&quot;: &quot;REG/2026/0001&quot;,
        &quot;jenis_surat&quot;: &quot;Surat Keterangan Domisili&quot;,
        &quot;nama_pemohon&quot;: &quot;Ahmad Fauzi&quot;,
        &quot;nik_pemohon&quot;: &quot;1118060512900001&quot;,
        &quot;tanggal_terbit&quot;: &quot;10-07-2026&quot;,
        &quot;diverifikasi_oleh&quot;: &quot;admin_desa&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;valid&quot;: false,
    &quot;message&quot;: &quot;Dokumen tidak ditemukan atau tidak valid&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;valid&quot;: false,
    &quot;message&quot;: &quot;Dokumen belum selesai diproses&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-verifikasi--hash-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-verifikasi--hash-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-verifikasi--hash-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-verifikasi--hash-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-verifikasi--hash-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-verifikasi--hash-" data-method="GET"
      data-path="api/v1/verifikasi/{hash}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-verifikasi--hash-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-verifikasi--hash-"
                    onclick="tryItOut('GETapi-v1-verifikasi--hash-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-verifikasi--hash-"
                    onclick="cancelTryOut('GETapi-v1-verifikasi--hash-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-verifikasi--hash-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/verifikasi/{hash}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-verifikasi--hash-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-verifikasi--hash-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>hash</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="hash"                data-endpoint="GETapi-v1-verifikasi--hash-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>valid</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status validitas dokumen.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil verifikasi.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Detail dokumen (hanya jika valid).</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nomor_registrasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Nomor registrasi pengajuan surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>jenis_surat</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Nama jenis surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nama_pemohon</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Nama lengkap pemohon surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nik_pemohon</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>NIK pemohon surat.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>tanggal_terbit</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Tanggal terbit surat (format: d-m-Y).</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>diverifikasi_oleh</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Username admin yang memverifikasi.</p>
                    </div>
                                    </details>
        </div>
                                    <h2 id="statistik-verifikasi-admin">Admin</h2>
                                                    <h2 id="statistik-verifikasi-POSTapi-v1-admin-statistik-clear-cache">Membersihkan cache penyimpanan data statistik.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-admin-statistik-clear-cache">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/admin/statistik/clear-cache" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/admin/statistik/clear-cache';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/admin/statistik/clear-cache"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-statistik-clear-cache">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Cache statistik berhasil dibersihkan&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-statistik-clear-cache" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-statistik-clear-cache"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-statistik-clear-cache"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-statistik-clear-cache" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-statistik-clear-cache">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-statistik-clear-cache" data-method="POST"
      data-path="api/v1/admin/statistik/clear-cache"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-statistik-clear-cache', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-statistik-clear-cache"
                    onclick="tryItOut('POSTapi-v1-admin-statistik-clear-cache');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-statistik-clear-cache"
                    onclick="cancelTryOut('POSTapi-v1-admin-statistik-clear-cache');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-statistik-clear-cache"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/statistik/clear-cache</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-statistik-clear-cache"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-statistik-clear-cache"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-statistik-clear-cache"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Pesan hasil operasi.</p>
        </div>
                    <h1 id="integrasi-telegram">Integrasi Telegram</h1>

    

                                <h2 id="integrasi-telegram-POSTapi-v1-telegram-webhook">Endpoint utama penerima payload webhook Telegram.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-telegram-webhook">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/telegram/webhook" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/telegram/webhook';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/telegram/webhook"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-telegram-webhook">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;ok&quot;: true
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-telegram-webhook" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-telegram-webhook"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-telegram-webhook"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-telegram-webhook" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-telegram-webhook">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-telegram-webhook" data-method="POST"
      data-path="api/v1/telegram/webhook"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-telegram-webhook', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-telegram-webhook"
                    onclick="tryItOut('POSTapi-v1-telegram-webhook');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-telegram-webhook"
                    onclick="cancelTryOut('POSTapi-v1-telegram-webhook');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-telegram-webhook"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/telegram/webhook</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-telegram-webhook"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-telegram-webhook"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

    <h3>Response</h3>
    <h4 class="fancy-heading-panel"><b>Response Fields</b></h4>
    <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ok</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Status penerimaan webhook.</p>
        </div>
                    <h1 id="lainnya">Lainnya</h1>

    

                                <h2 id="lainnya-POSTapi-v1-whatsapp-webhook">POST api/v1/whatsapp/webhook</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-whatsapp-webhook">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://127.0.0.1:8000/api/v1/whatsapp/webhook" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://127.0.0.1:8000/api/v1/whatsapp/webhook';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_AUTH_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://127.0.0.1:8000/api/v1/whatsapp/webhook"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-whatsapp-webhook">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;ok&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-whatsapp-webhook" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-whatsapp-webhook"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-whatsapp-webhook"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-whatsapp-webhook" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-whatsapp-webhook">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-whatsapp-webhook" data-method="POST"
      data-path="api/v1/whatsapp/webhook"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-whatsapp-webhook', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-whatsapp-webhook"
                    onclick="tryItOut('POSTapi-v1-whatsapp-webhook');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-whatsapp-webhook"
                    onclick="cancelTryOut('POSTapi-v1-whatsapp-webhook');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-whatsapp-webhook"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/whatsapp/webhook</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-whatsapp-webhook"
               value="Bearer {YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-whatsapp-webhook"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-whatsapp-webhook"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="php">php</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
