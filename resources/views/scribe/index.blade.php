<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>SIG-Udeung API Documentation</title>

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
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.10.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.10.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authentication" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authentication">
                    <a href="#authentication">Authentication</a>
                </li>
                                    <ul id="tocify-subheader-authentication" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="authentication-POSTapi-v1-auth-login-warga">
                                <a href="#authentication-POSTapi-v1-auth-login-warga">Login Warga (NIK)</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-v1-auth-login-admin">
                                <a href="#authentication-POSTapi-v1-auth-login-admin">Login Admin</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-v1-auth-logout">
                                <a href="#authentication-POSTapi-v1-auth-logout">Logout</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-GETapi-v1-auth-profile">
                                <a href="#authentication-GETapi-v1-auth-profile">Get Profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-v1-auth-bind-telegram">
                                <a href="#authentication-POSTapi-v1-auth-bind-telegram">Bind Telegram</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-informasi-publik" class="tocify-header">
                <li class="tocify-item level-1" data-unique="informasi-publik">
                    <a href="#informasi-publik">Informasi Publik</a>
                </li>
                                    <ul id="tocify-subheader-informasi-publik" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="informasi-publik-GETapi-v1-informasi">
                                <a href="#informasi-publik-GETapi-v1-informasi">Daftar Informasi Publik</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="informasi-publik-GETapi-v1-informasi--slug-">
                                <a href="#informasi-publik-GETapi-v1-informasi--slug-">Detail Informasi Publik</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="informasi-publik-GETapi-v1-admin-informasi">
                                <a href="#informasi-publik-GETapi-v1-admin-informasi">[Admin] Daftar Semua Informasi</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="informasi-publik-POSTapi-v1-admin-informasi">
                                <a href="#informasi-publik-POSTapi-v1-admin-informasi">[Admin] Buat Informasi</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="informasi-publik-PUTapi-v1-admin-informasi--id-">
                                <a href="#informasi-publik-PUTapi-v1-admin-informasi--id-">[Admin] Update Informasi</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="informasi-publik-DELETEapi-v1-admin-informasi--id-">
                                <a href="#informasi-publik-DELETEapi-v1-admin-informasi--id-">[Admin] Hapus Informasi</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-mutasi-penduduk" class="tocify-header">
                <li class="tocify-item level-1" data-unique="mutasi-penduduk">
                    <a href="#mutasi-penduduk">Mutasi Penduduk</a>
                </li>
                                    <ul id="tocify-subheader-mutasi-penduduk" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="mutasi-penduduk-POSTapi-v1-mutasi">
                                <a href="#mutasi-penduduk-POSTapi-v1-mutasi">Buat Pengajuan Mutasi</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="mutasi-penduduk-GETapi-v1-mutasi">
                                <a href="#mutasi-penduduk-GETapi-v1-mutasi">Daftar Mutasi Saya</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="mutasi-penduduk-GETapi-v1-admin-mutasi">
                                <a href="#mutasi-penduduk-GETapi-v1-admin-mutasi">[Admin] Daftar Semua Mutasi</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="mutasi-penduduk-POSTapi-v1-admin-mutasi--id--approve">
                                <a href="#mutasi-penduduk-POSTapi-v1-admin-mutasi--id--approve">[Admin] Setujui Mutasi</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="mutasi-penduduk-POSTapi-v1-admin-mutasi--id--reject">
                                <a href="#mutasi-penduduk-POSTapi-v1-admin-mutasi--id--reject">[Admin] Tolak Mutasi</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-pengajuan-surat" class="tocify-header">
                <li class="tocify-item level-1" data-unique="pengajuan-surat">
                    <a href="#pengajuan-surat">Pengajuan Surat</a>
                </li>
                                    <ul id="tocify-subheader-pengajuan-surat" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="pengajuan-surat-GETapi-v1-surat-kategori">
                                <a href="#pengajuan-surat-GETapi-v1-surat-kategori">Daftar Kategori Surat</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="pengajuan-surat-GETapi-v1-surat-kategori--id-">
                                <a href="#pengajuan-surat-GETapi-v1-surat-kategori--id-">Detail Kategori Surat</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="pengajuan-surat-POSTapi-v1-surat-pengajuan">
                                <a href="#pengajuan-surat-POSTapi-v1-surat-pengajuan">Buat Pengajuan Surat</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="pengajuan-surat-GETapi-v1-surat-pengajuan">
                                <a href="#pengajuan-surat-GETapi-v1-surat-pengajuan">Daftar Pengajuan Surat Saya</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="pengajuan-surat-GETapi-v1-surat-pengajuan--id-">
                                <a href="#pengajuan-surat-GETapi-v1-surat-pengajuan--id-">Detail Pengajuan Surat</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="pengajuan-surat-GETapi-v1-admin-surat-pengajuan">
                                <a href="#pengajuan-surat-GETapi-v1-admin-surat-pengajuan">[Admin] Daftar Semua Pengajuan</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="pengajuan-surat-POSTapi-v1-admin-surat-pengajuan--id--approve">
                                <a href="#pengajuan-surat-POSTapi-v1-admin-surat-pengajuan--id--approve">[Admin] Setujui Pengajuan</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="pengajuan-surat-POSTapi-v1-admin-surat-pengajuan--id--reject">
                                <a href="#pengajuan-surat-POSTapi-v1-admin-surat-pengajuan--id--reject">[Admin] Tolak Pengajuan</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-statistik" class="tocify-header">
                <li class="tocify-item level-1" data-unique="statistik">
                    <a href="#statistik">Statistik</a>
                </li>
                                    <ul id="tocify-subheader-statistik" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="statistik-GETapi-v1-statistik-demografi">
                                <a href="#statistik-GETapi-v1-statistik-demografi">Statistik Demografi</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="statistik-GETapi-v1-statistik-layanan">
                                <a href="#statistik-GETapi-v1-statistik-layanan">Statistik Layanan</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="statistik-POSTapi-v1-admin-statistik-clear-cache">
                                <a href="#statistik-POSTapi-v1-admin-statistik-clear-cache">[Admin] Clear Cache Statistik</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-telegram-webhook" class="tocify-header">
                <li class="tocify-item level-1" data-unique="telegram-webhook">
                    <a href="#telegram-webhook">Telegram Webhook</a>
                </li>
                                    <ul id="tocify-subheader-telegram-webhook" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="telegram-webhook-POSTapi-v1-telegram-webhook">
                                <a href="#telegram-webhook-POSTapi-v1-telegram-webhook">Handle Telegram Webhook</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-verifikasi" class="tocify-header">
                <li class="tocify-item level-1" data-unique="verifikasi">
                    <a href="#verifikasi">Verifikasi</a>
                </li>
                                    <ul id="tocify-subheader-verifikasi" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="verifikasi-GETapi-v1-verifikasi--hash-">
                                <a href="#verifikasi-GETapi-v1-verifikasi--hash-">Verifikasi QR Code TTE</a>
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
        <li>Last updated: June 1, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>API Documentation untuk Sistem Informasi Gampong (SIG) Udeung - Gampong Udeung, Kec. Bandar Baru, Kab. Pidie Jaya, Provinsi Aceh</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>Dokumentasi ini menyediakan semua informasi yang Anda butuhkan untuk bekerja dengan API SIG-Udeung.

## Base URL
```
https://your-domain.com/api/v1
```

## Authentication
API ini menggunakan Laravel Sanctum untuk autentikasi. Sebagian besar endpoint memerlukan token autentikasi.

### Untuk Warga
Login menggunakan NIK (16 digit):
```bash
POST /auth/login/warga
{
  "nik": "1234567890123456"
}
```

### Untuk Admin
Login menggunakan username dan password:
```bash
POST /auth/login/admin
{
  "username": "operator",
  "password": "password123"
}
```

Setelah login berhasil, Anda akan menerima token yang harus disertakan di header setiap request:
```
Authorization: Bearer {token}
```

## Response Format
Semua response menggunakan format JSON dengan struktur standar:

**Success Response:**
```json
{
  "message": "Success message",
  "data": { ... }
}
```

**Error Response:**
```json
{
  "message": "Error message",
  "errors": {
    "field": ["Error detail"]
  }
}
```

## Rate Limiting
API ini menggunakan rate limiting standar Laravel (60 requests per menit per IP).

&lt;aside&gt;Kode contoh untuk bekerja dengan API tersedia di area gelap di sebelah kanan (atau sebagai bagian dari konten di mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_AUTH_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>Untuk mendapatkan token, login terlebih dahulu menggunakan endpoint <code>POST /api/v1/auth/login/warga</code> (untuk warga) atau <code>POST /api/v1/auth/login/admin</code> (untuk admin). Token yang didapat kemudian digunakan sebagai Bearer token di header Authorization.</p>

        <h1 id="authentication">Authentication</h1>

    <p>APIs untuk autentikasi warga dan admin</p>

                                <h2 id="authentication-POSTapi-v1-auth-login-warga">Login Warga (NIK)</h2>

<p>
</p>

<p>Login menggunakan NIK untuk warga gampong.</p>

<span id="example-requests-POSTapi-v1-auth-login-warga">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/login/warga" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"nik\": \"1234567890123456\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/login/warga"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "nik": "1234567890123456"
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
        &quot;nik&quot;: &quot;1234567890123456&quot;,
        &quot;nama_lengkap&quot;: &quot;John Doe&quot;,
        &quot;tempat_lahir&quot;: &quot;Jakarta&quot;,
        &quot;tanggal_lahir&quot;: &quot;1990-01-01&quot;,
        &quot;jenis_kelamin&quot;: &quot;L&quot;,
        &quot;agama&quot;: &quot;Islam&quot;,
        &quot;pendidikan&quot;: &quot;S1&quot;,
        &quot;pekerjaan&quot;: &quot;Programmer&quot;,
        &quot;status_perkawinan&quot;: &quot;Belum Kawin&quot;,
        &quot;status_keluarga&quot;: &quot;Anak&quot;,
        &quot;status_mutasi&quot;: &quot;Tetap&quot;
    },
    &quot;token&quot;: &quot;1|abcdefghijklmnopqrstuvwxyz&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;nik&quot;: [
            &quot;NIK tidak ditemukan atau tidak aktif.&quot;
        ]
    }
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
               value="1234567890123456"
               data-component="body">
    <br>
<p>NIK warga (16 digit). Example: <code>1234567890123456</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-v1-auth-login-admin">Login Admin</h2>

<p>
</p>

<p>Login menggunakan username dan password untuk admin (Keuchik, Sekdes, Operator).</p>

<span id="example-requests-POSTapi-v1-auth-login-admin">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/login/admin" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"username\": \"operator\",
    \"password\": \"password123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/login/admin"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "username": "operator",
    "password": "password123"
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
        &quot;username&quot;: &quot;operator&quot;,
        &quot;role&quot;: &quot;operator&quot;,
        &quot;created_at&quot;: &quot;2024-01-01T00:00:00.000000Z&quot;
    },
    &quot;token&quot;: &quot;2|abcdefghijklmnopqrstuvwxyz&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;username&quot;: [
            &quot;Username atau password salah.&quot;
        ]
    }
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
               value="operator"
               data-component="body">
    <br>
<p>Username admin. Example: <code>operator</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-auth-login-admin"
               value="password123"
               data-component="body">
    <br>
<p>Password admin. Example: <code>password123</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-v1-auth-logout">Logout</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Logout dan hapus token akses saat ini.</p>

<span id="example-requests-POSTapi-v1-auth-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/logout" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/logout"
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

                    <h2 id="authentication-GETapi-v1-auth-profile">Get Profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Mendapatkan profil user yang sedang login (warga atau admin).</p>

<span id="example-requests-GETapi-v1-auth-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/auth/profile" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/profile"
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
        &quot;nik&quot;: &quot;1234567890123456&quot;,
        &quot;nama_lengkap&quot;: &quot;John Doe&quot;,
        &quot;tempat_lahir&quot;: &quot;Jakarta&quot;,
        &quot;tanggal_lahir&quot;: &quot;1990-01-01&quot;,
        &quot;jenis_kelamin&quot;: &quot;L&quot;,
        &quot;agama&quot;: &quot;Islam&quot;,
        &quot;pendidikan&quot;: &quot;S1&quot;,
        &quot;pekerjaan&quot;: &quot;Programmer&quot;,
        &quot;status_perkawinan&quot;: &quot;Belum Kawin&quot;,
        &quot;status_keluarga&quot;: &quot;Anak&quot;,
        &quot;status_mutasi&quot;: &quot;Tetap&quot;,
        &quot;keluarga&quot;: {
            &quot;no_kk&quot;: &quot;1234567890123456&quot;,
            &quot;alamat&quot;: &quot;Jl. Merdeka No. 123&quot;
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

                    <h2 id="authentication-POSTapi-v1-auth-bind-telegram">Bind Telegram</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Menghubungkan akun warga dengan Telegram Chat ID untuk notifikasi.</p>

<span id="example-requests-POSTapi-v1-auth-bind-telegram">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/auth/bind-telegram" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"telegram_chat_id\": \"123456789\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/auth/bind-telegram"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "telegram_chat_id": "123456789"
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
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;telegram_chat_id&quot;: [
            &quot;The telegram chat id has already been taken.&quot;
        ]
    }
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
               value="123456789"
               data-component="body">
    <br>
<p>Telegram Chat ID dari bot. Example: <code>123456789</code></p>
        </div>
        </form>

                <h1 id="informasi-publik">Informasi Publik</h1>

    <p>APIs untuk mengelola informasi publik gampong (berita, pengumuman, agenda)</p>

                                <h2 id="informasi-publik-GETapi-v1-informasi">Daftar Informasi Publik</h2>

<p>
</p>

<p>Mendapatkan daftar informasi publik yang sudah dipublikasikan.</p>

<span id="example-requests-GETapi-v1-informasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/informasi?kategori=Berita" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/informasi"
);

const params = {
    "kategori": "Berita",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

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
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;judul&quot;: &quot;Musyawarah Gampong 2026&quot;,
            &quot;slug&quot;: &quot;musyawarah-gampong-2026&quot;,
            &quot;konten&quot;: &quot;Akan dilaksanakan musyawarah...&quot;,
            &quot;kategori&quot;: &quot;Pengumuman&quot;,
            &quot;cover_image&quot;: &quot;https://storage.com/cover.jpg&quot;,
            &quot;is_published&quot;: true,
            &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;,
            &quot;author&quot;: {
                &quot;id&quot;: 1,
                &quot;username&quot;: &quot;operator&quot;
            }
        }
    ],
    &quot;links&quot;: {},
    &quot;meta&quot;: {}
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
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="kategori"                data-endpoint="GETapi-v1-informasi"
               value="Berita"
               data-component="query">
    <br>
<p>Filter berdasarkan kategori. Example: <code>Berita</code></p>
            </div>
                </form>

                    <h2 id="informasi-publik-GETapi-v1-informasi--slug-">Detail Informasi Publik</h2>

<p>
</p>

<p>Mendapatkan detail informasi publik berdasarkan slug.</p>

<span id="example-requests-GETapi-v1-informasi--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/informasi/musyawarah-gampong-2026" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/informasi/musyawarah-gampong-2026"
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
        &quot;judul&quot;: &quot;Musyawarah Gampong 2026&quot;,
        &quot;slug&quot;: &quot;musyawarah-gampong-2026&quot;,
        &quot;konten&quot;: &quot;Akan dilaksanakan musyawarah...&quot;,
        &quot;kategori&quot;: &quot;Pengumuman&quot;,
        &quot;cover_image&quot;: &quot;https://storage.com/cover.jpg&quot;,
        &quot;is_published&quot;: true,
        &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;,
        &quot;author&quot;: {
            &quot;id&quot;: 1,
            &quot;username&quot;: &quot;operator&quot;
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
               value="musyawarah-gampong-2026"
               data-component="url">
    <br>
<p>Slug informasi. Example: <code>musyawarah-gampong-2026</code></p>
            </div>
                    </form>

                    <h2 id="informasi-publik-GETapi-v1-admin-informasi">[Admin] Daftar Semua Informasi</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Mendapatkan daftar semua informasi termasuk draft (admin only).</p>

<span id="example-requests-GETapi-v1-admin-informasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/admin/informasi?is_published=1" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/informasi"
);

const params = {
    "is_published": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

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
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;judul&quot;: &quot;Musyawarah Gampong 2026&quot;,
            &quot;slug&quot;: &quot;musyawarah-gampong-2026&quot;,
            &quot;kategori&quot;: &quot;Pengumuman&quot;,
            &quot;is_published&quot;: true,
            &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;,
            &quot;author&quot;: {
                &quot;username&quot;: &quot;operator&quot;
            }
        }
    ],
    &quot;links&quot;: {},
    &quot;meta&quot;: {}
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
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>is_published</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-v1-admin-informasi" style="display: none">
            <input type="radio" name="is_published"
                   value="1"
                   data-endpoint="GETapi-v1-admin-informasi"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-v1-admin-informasi" style="display: none">
            <input type="radio" name="is_published"
                   value="0"
                   data-endpoint="GETapi-v1-admin-informasi"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filter berdasarkan status publikasi. Example: <code>true</code></p>
            </div>
                </form>

                    <h2 id="informasi-publik-POSTapi-v1-admin-informasi">[Admin] Buat Informasi</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Membuat informasi publik baru (admin only).</p>

<span id="example-requests-POSTapi-v1-admin-informasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/admin/informasi" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"judul\": \"Musyawarah Gampong 2026\",
    \"konten\": \"&lt;p&gt;Akan dilaksanakan musyawarah...&lt;\\/p&gt;\",
    \"kategori\": \"Pengumuman\",
    \"cover_image\": \"https:\\/\\/storage.com\\/cover.jpg\",
    \"is_published\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/informasi"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "judul": "Musyawarah Gampong 2026",
    "konten": "&lt;p&gt;Akan dilaksanakan musyawarah...&lt;\/p&gt;",
    "kategori": "Pengumuman",
    "cover_image": "https:\/\/storage.com\/cover.jpg",
    "is_published": true
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
        &quot;judul&quot;: &quot;Musyawarah Gampong 2026&quot;,
        &quot;slug&quot;: &quot;musyawarah-gampong-2026&quot;,
        &quot;konten&quot;: &quot;&lt;p&gt;Akan dilaksanakan musyawarah...&lt;/p&gt;&quot;,
        &quot;kategori&quot;: &quot;Pengumuman&quot;,
        &quot;cover_image&quot;: &quot;https://storage.com/cover.jpg&quot;,
        &quot;is_published&quot;: true,
        &quot;author_id&quot;: 1,
        &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;
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
               value="Musyawarah Gampong 2026"
               data-component="body">
    <br>
<p>Judul informasi. Example: <code>Musyawarah Gampong 2026</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>konten</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="konten"                data-endpoint="POSTapi-v1-admin-informasi"
               value="<p>Akan dilaksanakan musyawarah...</p>"
               data-component="body">
    <br>
<p>Konten informasi (HTML). Example: <code>&lt;p&gt;Akan dilaksanakan musyawarah...&lt;/p&gt;</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="kategori"                data-endpoint="POSTapi-v1-admin-informasi"
               value="Pengumuman"
               data-component="body">
    <br>
<p>Kategori informasi. Example: <code>Pengumuman</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>cover_image</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="cover_image"                data-endpoint="POSTapi-v1-admin-informasi"
               value="https://storage.com/cover.jpg"
               data-component="body">
    <br>
<p>URL cover image. Example: <code>https://storage.com/cover.jpg</code></p>
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
<p>Status publikasi. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="informasi-publik-PUTapi-v1-admin-informasi--id-">[Admin] Update Informasi</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Mengupdate informasi publik (admin only).</p>

<span id="example-requests-PUTapi-v1-admin-informasi--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/v1/admin/informasi/1" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"judul\": \"Musyawarah Gampong 2026 (Updated)\",
    \"konten\": \"&lt;p&gt;Update konten...&lt;\\/p&gt;\",
    \"kategori\": \"Berita\",
    \"cover_image\": \"https:\\/\\/storage.com\\/cover-new.jpg\",
    \"is_published\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/informasi/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "judul": "Musyawarah Gampong 2026 (Updated)",
    "konten": "&lt;p&gt;Update konten...&lt;\/p&gt;",
    "kategori": "Berita",
    "cover_image": "https:\/\/storage.com\/cover-new.jpg",
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
        &quot;judul&quot;: &quot;Musyawarah Gampong 2026 (Updated)&quot;,
        &quot;slug&quot;: &quot;musyawarah-gampong-2026-updated&quot;,
        &quot;konten&quot;: &quot;&lt;p&gt;Update konten...&lt;/p&gt;&quot;,
        &quot;kategori&quot;: &quot;Berita&quot;,
        &quot;is_published&quot;: false,
        &quot;updated_at&quot;: &quot;2026-06-01T11:00:00.000000Z&quot;
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="1"
               data-component="url">
    <br>
<p>ID informasi. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>judul</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="judul"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="Musyawarah Gampong 2026 (Updated)"
               data-component="body">
    <br>
<p>Judul informasi. Example: <code>Musyawarah Gampong 2026 (Updated)</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>konten</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="konten"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="<p>Update konten...</p>"
               data-component="body">
    <br>
<p>Konten informasi (HTML). Example: <code>&lt;p&gt;Update konten...&lt;/p&gt;</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>kategori</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="kategori"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="Berita"
               data-component="body">
    <br>
<p>Kategori informasi. Example: <code>Berita</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>cover_image</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="cover_image"                data-endpoint="PUTapi-v1-admin-informasi--id-"
               value="https://storage.com/cover-new.jpg"
               data-component="body">
    <br>
<p>URL cover image. Example: <code>https://storage.com/cover-new.jpg</code></p>
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
<p>Status publikasi. Example: <code>false</code></p>
        </div>
        </form>

                    <h2 id="informasi-publik-DELETEapi-v1-admin-informasi--id-">[Admin] Hapus Informasi</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Menghapus informasi publik (admin only).</p>

<span id="example-requests-DELETEapi-v1-admin-informasi--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/v1/admin/informasi/1" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/informasi/1"
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-v1-admin-informasi--id-"
               value="1"
               data-component="url">
    <br>
<p>ID informasi. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="mutasi-penduduk">Mutasi Penduduk</h1>

    <p>APIs untuk mengelola mutasi penduduk (kelahiran, kematian, kedatangan, kepindahan)</p>

                                <h2 id="mutasi-penduduk-POSTapi-v1-mutasi">Buat Pengajuan Mutasi</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Submit pengajuan mutasi penduduk baru.</p>

<span id="example-requests-POSTapi-v1-mutasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/mutasi" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"nik\": \"1234567890123456\",
    \"jenis_mutasi\": \"Kelahiran\",
    \"tanggal_mutasi\": \"2026-06-01\",
    \"keterangan\": \"Lahir di RSUD\",
    \"dokumen_bukti\": \"https:\\/\\/storage.com\\/akta.jpg\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/mutasi"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "nik": "1234567890123456",
    "jenis_mutasi": "Kelahiran",
    "tanggal_mutasi": "2026-06-01",
    "keterangan": "Lahir di RSUD",
    "dokumen_bukti": "https:\/\/storage.com\/akta.jpg"
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
        &quot;nik&quot;: &quot;1234567890123456&quot;,
        &quot;jenis_mutasi&quot;: &quot;Kelahiran&quot;,
        &quot;tanggal_mutasi&quot;: &quot;2026-06-01&quot;,
        &quot;keterangan&quot;: &quot;Lahir di RSUD&quot;,
        &quot;dokumen_bukti&quot;: &quot;https://storage.com/akta.jpg&quot;,
        &quot;status_verifikasi&quot;: &quot;Pending&quot;,
        &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;
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
               value="1234567890123456"
               data-component="body">
    <br>
<p>NIK penduduk yang mengalami mutasi. Example: <code>1234567890123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>jenis_mutasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="jenis_mutasi"                data-endpoint="POSTapi-v1-mutasi"
               value="Kelahiran"
               data-component="body">
    <br>
<p>Jenis mutasi. Example: <code>Kelahiran</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>tanggal_mutasi</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tanggal_mutasi"                data-endpoint="POSTapi-v1-mutasi"
               value="2026-06-01"
               data-component="body">
    <br>
<p>Tanggal terjadinya mutasi. Example: <code>2026-06-01</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>keterangan</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="keterangan"                data-endpoint="POSTapi-v1-mutasi"
               value="Lahir di RSUD"
               data-component="body">
    <br>
<p>Keterangan tambahan. Example: <code>Lahir di RSUD</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dokumen_bukti</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="dokumen_bukti"                data-endpoint="POSTapi-v1-mutasi"
               value="https://storage.com/akta.jpg"
               data-component="body">
    <br>
<p>URL dokumen bukti yang sudah diupload. Example: <code>https://storage.com/akta.jpg</code></p>
        </div>
        </form>

                    <h2 id="mutasi-penduduk-GETapi-v1-mutasi">Daftar Mutasi Saya</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Mendapatkan daftar mutasi penduduk milik user yang sedang login.</p>

<span id="example-requests-GETapi-v1-mutasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/mutasi" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/mutasi"
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
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;nik&quot;: &quot;1234567890123456&quot;,
            &quot;jenis_mutasi&quot;: &quot;Kelahiran&quot;,
            &quot;tanggal_mutasi&quot;: &quot;2026-06-01&quot;,
            &quot;status_verifikasi&quot;: &quot;Pending&quot;,
            &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;,
            &quot;penduduk&quot;: {
                &quot;nik&quot;: &quot;1234567890123456&quot;,
                &quot;nama_lengkap&quot;: &quot;John Doe&quot;
            }
        }
    ],
    &quot;links&quot;: {},
    &quot;meta&quot;: {}
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

                    <h2 id="mutasi-penduduk-GETapi-v1-admin-mutasi">[Admin] Daftar Semua Mutasi</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Mendapatkan daftar semua mutasi penduduk (admin only).</p>

<span id="example-requests-GETapi-v1-admin-mutasi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/admin/mutasi?status_verifikasi=Pending&amp;jenis_mutasi=Kelahiran" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/mutasi"
);

const params = {
    "status_verifikasi": "Pending",
    "jenis_mutasi": "Kelahiran",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

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
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;nik&quot;: &quot;1234567890123456&quot;,
            &quot;jenis_mutasi&quot;: &quot;Kelahiran&quot;,
            &quot;tanggal_mutasi&quot;: &quot;2026-06-01&quot;,
            &quot;status_verifikasi&quot;: &quot;Pending&quot;,
            &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;,
            &quot;penduduk&quot;: {
                &quot;nama_lengkap&quot;: &quot;John Doe&quot;
            },
            &quot;verifikator&quot;: null
        }
    ],
    &quot;links&quot;: {},
    &quot;meta&quot;: {}
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
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status_verifikasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status_verifikasi"                data-endpoint="GETapi-v1-admin-mutasi"
               value="Pending"
               data-component="query">
    <br>
<p>Filter berdasarkan status verifikasi. Example: <code>Pending</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>jenis_mutasi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="jenis_mutasi"                data-endpoint="GETapi-v1-admin-mutasi"
               value="Kelahiran"
               data-component="query">
    <br>
<p>Filter berdasarkan jenis mutasi. Example: <code>Kelahiran</code></p>
            </div>
                </form>

                    <h2 id="mutasi-penduduk-POSTapi-v1-admin-mutasi--id--approve">[Admin] Setujui Mutasi</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Menyetujui mutasi penduduk dan update status penduduk (admin only).</p>

<span id="example-requests-POSTapi-v1-admin-mutasi--id--approve">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/admin/mutasi/1/approve" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/mutasi/1/approve"
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
        &quot;nik&quot;: &quot;1234567890123456&quot;,
        &quot;jenis_mutasi&quot;: &quot;Kelahiran&quot;,
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-v1-admin-mutasi--id--approve"
               value="1"
               data-component="url">
    <br>
<p>ID mutasi penduduk. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="mutasi-penduduk-POSTapi-v1-admin-mutasi--id--reject">[Admin] Tolak Mutasi</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Menolak mutasi penduduk (admin only).</p>

<span id="example-requests-POSTapi-v1-admin-mutasi--id--reject">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/admin/mutasi/1/reject" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/mutasi/1/reject"
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
        &quot;nik&quot;: &quot;1234567890123456&quot;,
        &quot;jenis_mutasi&quot;: &quot;Kelahiran&quot;,
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-v1-admin-mutasi--id--reject"
               value="1"
               data-component="url">
    <br>
<p>ID mutasi penduduk. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="pengajuan-surat">Pengajuan Surat</h1>

    <p>APIs untuk mengelola pengajuan surat warga</p>

                                <h2 id="pengajuan-surat-GETapi-v1-surat-kategori">Daftar Kategori Surat</h2>

<p>
</p>

<p>Mendapatkan daftar kategori surat yang tersedia dan aktif.</p>

<span id="example-requests-GETapi-v1-surat-kategori">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/surat/kategori" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/surat/kategori"
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
            &quot;kode_surat&quot;: &quot;SKD&quot;,
            &quot;deskripsi&quot;: &quot;Surat keterangan tempat tinggal&quot;,
            &quot;template_path&quot;: &quot;pdf.surat.domisili&quot;,
            &quot;persyaratan&quot;: [
                &quot;KTP&quot;,
                &quot;KK&quot;
            ],
            &quot;field_isian&quot;: [
                &quot;keperluan&quot;,
                &quot;alamat_lengkap&quot;
            ],
            &quot;is_active&quot;: true
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
      data-authed="0"
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

                    <h2 id="pengajuan-surat-GETapi-v1-surat-kategori--id-">Detail Kategori Surat</h2>

<p>
</p>

<p>Mendapatkan detail kategori surat tertentu.</p>

<span id="example-requests-GETapi-v1-surat-kategori--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/surat/kategori/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/surat/kategori/1"
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

<span id="example-responses-GETapi-v1-surat-kategori--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;nama_surat&quot;: &quot;Surat Keterangan Domisili&quot;,
        &quot;kode_surat&quot;: &quot;SKD&quot;,
        &quot;deskripsi&quot;: &quot;Surat keterangan tempat tinggal&quot;,
        &quot;template_path&quot;: &quot;pdf.surat.domisili&quot;,
        &quot;persyaratan&quot;: [
            &quot;KTP&quot;,
            &quot;KK&quot;
        ],
        &quot;field_isian&quot;: [
            &quot;keperluan&quot;,
            &quot;alamat_lengkap&quot;
        ],
        &quot;is_active&quot;: true
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
      data-authed="0"
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-surat-kategori--id-"
               value="1"
               data-component="url">
    <br>
<p>ID kategori surat. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="pengajuan-surat-POSTapi-v1-surat-pengajuan">Buat Pengajuan Surat</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Submit pengajuan surat baru oleh warga.</p>

<span id="example-requests-POSTapi-v1-surat-pengajuan">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/surat/pengajuan" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"kategori_surat_id\": 1,
    \"data_isian\": {
        \"keperluan\": \"Melamar pekerjaan\",
        \"alamat_lengkap\": \"Jl. Merdeka No. 123\"
    },
    \"file_syarat\": {
        \"ktp\": \"https:\\/\\/storage.com\\/ktp.jpg\",
        \"kk\": \"https:\\/\\/storage.com\\/kk.jpg\"
    }
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/surat/pengajuan"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "kategori_surat_id": 1,
    "data_isian": {
        "keperluan": "Melamar pekerjaan",
        "alamat_lengkap": "Jl. Merdeka No. 123"
    },
    "file_syarat": {
        "ktp": "https:\/\/storage.com\/ktp.jpg",
        "kk": "https:\/\/storage.com\/kk.jpg"
    }
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
        &quot;nomor_registrasi&quot;: &quot;REG/2026/06/00001&quot;,
        &quot;nik_pemohon&quot;: &quot;1234567890123456&quot;,
        &quot;kategori_surat_id&quot;: 1,
        &quot;data_isian&quot;: {
            &quot;keperluan&quot;: &quot;Melamar pekerjaan&quot;
        },
        &quot;file_syarat&quot;: {
            &quot;ktp&quot;: &quot;https://storage.com/ktp.jpg&quot;
        },
        &quot;status&quot;: &quot;Pending&quot;,
        &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;,
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="kategori_surat_id"                data-endpoint="POSTapi-v1-surat-pengajuan"
               value="1"
               data-component="body">
    <br>
<p>ID kategori surat. Example: <code>1</code></p>
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
<p>Data isian sesuai field_isian kategori.</p>
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
<p>File persyaratan yang sudah diupload.</p>
        </div>
        </form>

                    <h2 id="pengajuan-surat-GETapi-v1-surat-pengajuan">Daftar Pengajuan Surat Saya</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Mendapatkan daftar pengajuan surat milik user yang sedang login.</p>

<span id="example-requests-GETapi-v1-surat-pengajuan">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/surat/pengajuan" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/surat/pengajuan"
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
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;nomor_registrasi&quot;: &quot;REG/2026/06/00001&quot;,
            &quot;nik_pemohon&quot;: &quot;1234567890123456&quot;,
            &quot;status&quot;: &quot;Pending&quot;,
            &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;,
            &quot;kategori&quot;: {
                &quot;nama_surat&quot;: &quot;Surat Keterangan Domisili&quot;
            },
            &quot;tracking&quot;: [
                {
                    &quot;status_baru&quot;: &quot;Pending&quot;,
                    &quot;keterangan_update&quot;: &quot;Pengajuan surat dibuat&quot;,
                    &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;
                }
            ]
        }
    ],
    &quot;links&quot;: {},
    &quot;meta&quot;: {}
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

                    <h2 id="pengajuan-surat-GETapi-v1-surat-pengajuan--id-">Detail Pengajuan Surat</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Mendapatkan detail pengajuan surat tertentu.</p>

<span id="example-requests-GETapi-v1-surat-pengajuan--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/surat/pengajuan/1" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/surat/pengajuan/1"
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
        &quot;nomor_registrasi&quot;: &quot;REG/2026/06/00001&quot;,
        &quot;nik_pemohon&quot;: &quot;1234567890123456&quot;,
        &quot;kategori_surat_id&quot;: 1,
        &quot;data_isian&quot;: {
            &quot;keperluan&quot;: &quot;Melamar pekerjaan&quot;
        },
        &quot;file_syarat&quot;: {
            &quot;ktp&quot;: &quot;https://storage.com/ktp.jpg&quot;
        },
        &quot;status&quot;: &quot;Disetujui&quot;,
        &quot;file_surat&quot;: &quot;https://storage.com/surat.pdf&quot;,
        &quot;qr_hash&quot;: &quot;abc123def456&quot;,
        &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;,
        &quot;kategori&quot;: {
            &quot;nama_surat&quot;: &quot;Surat Keterangan Domisili&quot;
        },
        &quot;pemohon&quot;: {
            &quot;nik&quot;: &quot;1234567890123456&quot;,
            &quot;nama_lengkap&quot;: &quot;John Doe&quot;
        },
        &quot;tracking&quot;: []
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-surat-pengajuan--id-"
               value="1"
               data-component="url">
    <br>
<p>ID pengajuan surat. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="pengajuan-surat-GETapi-v1-admin-surat-pengajuan">[Admin] Daftar Semua Pengajuan</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Mendapatkan daftar semua pengajuan surat (admin only).</p>

<span id="example-requests-GETapi-v1-admin-surat-pengajuan">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/admin/surat/pengajuan?status=Pending" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/surat/pengajuan"
);

const params = {
    "status": "Pending",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

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
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;nomor_registrasi&quot;: &quot;REG/2026/06/00001&quot;,
            &quot;nik_pemohon&quot;: &quot;1234567890123456&quot;,
            &quot;status&quot;: &quot;Pending&quot;,
            &quot;created_at&quot;: &quot;2026-06-01T10:00:00.000000Z&quot;,
            &quot;kategori&quot;: {
                &quot;nama_surat&quot;: &quot;Surat Keterangan Domisili&quot;
            },
            &quot;pemohon&quot;: {
                &quot;nama_lengkap&quot;: &quot;John Doe&quot;
            }
        }
    ],
    &quot;links&quot;: {},
    &quot;meta&quot;: {}
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
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-v1-admin-surat-pengajuan"
               value="Pending"
               data-component="query">
    <br>
<p>Filter berdasarkan status. Example: <code>Pending</code></p>
            </div>
                </form>

                    <h2 id="pengajuan-surat-POSTapi-v1-admin-surat-pengajuan--id--approve">[Admin] Setujui Pengajuan</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Menyetujui pengajuan surat dan memulai proses generate PDF (admin only).</p>

<span id="example-requests-POSTapi-v1-admin-surat-pengajuan--id--approve">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/admin/surat/pengajuan/1/approve" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/surat/pengajuan/1/approve"
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
        &quot;nomor_registrasi&quot;: &quot;REG/2026/06/00001&quot;,
        &quot;status&quot;: &quot;Disetujui&quot;,
        &quot;diverifikasi_oleh&quot;: 1
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--approve"
               value="1"
               data-component="url">
    <br>
<p>ID pengajuan surat. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="pengajuan-surat-POSTapi-v1-admin-surat-pengajuan--id--reject">[Admin] Tolak Pengajuan</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Menolak pengajuan surat dengan catatan penolakan (admin only).</p>

<span id="example-requests-POSTapi-v1-admin-surat-pengajuan--id--reject">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/admin/surat/pengajuan/1/reject" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"catatan_penolakan\": \"Dokumen persyaratan tidak lengkap\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/surat/pengajuan/1/reject"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "catatan_penolakan": "Dokumen persyaratan tidak lengkap"
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
        &quot;nomor_registrasi&quot;: &quot;REG/2026/06/00001&quot;,
        &quot;status&quot;: &quot;Ditolak&quot;,
        &quot;catatan_penolakan&quot;: &quot;Dokumen persyaratan tidak lengkap&quot;,
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--reject"
               value="1"
               data-component="url">
    <br>
<p>ID pengajuan surat. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>catatan_penolakan</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="catatan_penolakan"                data-endpoint="POSTapi-v1-admin-surat-pengajuan--id--reject"
               value="Dokumen persyaratan tidak lengkap"
               data-component="body">
    <br>
<p>Alasan penolakan. Example: <code>Dokumen persyaratan tidak lengkap</code></p>
        </div>
        </form>

                <h1 id="statistik">Statistik</h1>

    <p>APIs untuk mendapatkan statistik demografi dan layanan gampong</p>

                                <h2 id="statistik-GETapi-v1-statistik-demografi">Statistik Demografi</h2>

<p>
</p>

<p>Mendapatkan statistik demografi penduduk (jenis kelamin, agama, pendidikan, pekerjaan, dll).
Data di-cache selama 1 jam untuk performa optimal.</p>

<span id="example-requests-GETapi-v1-statistik-demografi">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/statistik/demografi" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/statistik/demografi"
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
        &quot;total_penduduk&quot;: 1250,
        &quot;total_keluarga&quot;: 320,
        &quot;jenis_kelamin&quot;: {
            &quot;L&quot;: 625,
            &quot;P&quot;: 625
        },
        &quot;agama&quot;: {
            &quot;Islam&quot;: 1200,
            &quot;Kristen&quot;: 30,
            &quot;Katolik&quot;: 20
        },
        &quot;pendidikan&quot;: {
            &quot;SD&quot;: 300,
            &quot;SMP&quot;: 250,
            &quot;SMA&quot;: 400,
            &quot;S1&quot;: 200,
            &quot;S2&quot;: 50
        },
        &quot;pekerjaan&quot;: {
            &quot;Petani&quot;: 400,
            &quot;Wiraswasta&quot;: 300,
            &quot;PNS&quot;: 150,
            &quot;Pelajar&quot;: 250
        },
        &quot;status_perkawinan&quot;: {
            &quot;Belum Kawin&quot;: 500,
            &quot;Kawin&quot;: 650,
            &quot;Cerai Hidup&quot;: 50,
            &quot;Cerai Mati&quot;: 50
        }
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

                    <h2 id="statistik-GETapi-v1-statistik-layanan">Statistik Layanan</h2>

<p>
</p>

<p>Mendapatkan statistik layanan pengajuan surat dan mutasi penduduk.
Data di-cache selama 1 jam untuk performa optimal.</p>

<span id="example-requests-GETapi-v1-statistik-layanan">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/statistik/layanan" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/statistik/layanan"
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
        &quot;pengajuan_surat&quot;: {
            &quot;total&quot;: 150,
            &quot;pending&quot;: 10,
            &quot;diproses&quot;: 5,
            &quot;disetujui&quot;: 120,
            &quot;ditolak&quot;: 10,
            &quot;selesai&quot;: 115,
            &quot;per_kategori&quot;: {
                &quot;Surat Keterangan Domisili&quot;: 80,
                &quot;Surat Keterangan Tidak Mampu&quot;: 70
            }
        },
        &quot;mutasi_penduduk&quot;: {
            &quot;total&quot;: 25,
            &quot;pending&quot;: 3,
            &quot;disetujui&quot;: 20,
            &quot;ditolak&quot;: 2,
            &quot;per_jenis&quot;: {
                &quot;Kelahiran&quot;: 10,
                &quot;Kematian&quot;: 5,
                &quot;Kedatangan&quot;: 7,
                &quot;Kepindahan&quot;: 3
            }
        }
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

                    <h2 id="statistik-POSTapi-v1-admin-statistik-clear-cache">[Admin] Clear Cache Statistik</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Membersihkan cache statistik untuk refresh data (admin only).</p>

<span id="example-requests-POSTapi-v1-admin-statistik-clear-cache">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/admin/statistik/clear-cache" \
    --header "Authorization: Bearer {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/admin/statistik/clear-cache"
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

                <h1 id="telegram-webhook">Telegram Webhook</h1>

    <p>API untuk menerima webhook dari Telegram Bot (internal use only)</p>

                                <h2 id="telegram-webhook-POSTapi-v1-telegram-webhook">Handle Telegram Webhook</h2>

<p>
</p>

<p>Endpoint untuk menerima webhook dari Telegram Bot.
Menangani pesan masuk dan callback query dari user.
Terintegrasi dengan Gemini AI untuk chatbot otomatis.</p>

<span id="example-requests-POSTapi-v1-telegram-webhook">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/telegram/webhook" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"update_id\": 123456789,
    \"message\": {
        \"message_id\": 1,
        \"chat\": {
            \"id\": 123456
        },
        \"text\": \"\\/start\"
    },
    \"callback_query\": {
        \"id\": \"123\",
        \"data\": \"action_data\"
    }
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/telegram/webhook"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "update_id": 123456789,
    "message": {
        "message_id": 1,
        "chat": {
            "id": 123456
        },
        "text": "\/start"
    },
    "callback_query": {
        "id": "123",
        "data": "action_data"
    }
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
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
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>update_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="update_id"                data-endpoint="POSTapi-v1-telegram-webhook"
               value="123456789"
               data-component="body">
    <br>
<p>ID update dari Telegram. Example: <code>123456789</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="message"                data-endpoint="POSTapi-v1-telegram-webhook"
               value=""
               data-component="body">
    <br>
<p>Objek message dari Telegram.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>callback_query</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="callback_query"                data-endpoint="POSTapi-v1-telegram-webhook"
               value=""
               data-component="body">
    <br>
<p>Objek callback query dari button.</p>
        </div>
        </form>

                <h1 id="verifikasi">Verifikasi</h1>

    <p>API untuk verifikasi keaslian dokumen surat melalui QR Code TTE (Tanda Tangan Elektronik)</p>

                                <h2 id="verifikasi-GETapi-v1-verifikasi--hash-">Verifikasi QR Code TTE</h2>

<p>
</p>

<p>Memverifikasi keaslian dokumen surat berdasarkan hash QR Code.
Hash menggunakan SHA-256 untuk keamanan.</p>

<span id="example-requests-GETapi-v1-verifikasi--hash-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/verifikasi/abc123def456789" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/verifikasi/abc123def456789"
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
        &quot;nomor_registrasi&quot;: &quot;REG/2026/06/00001&quot;,
        &quot;jenis_surat&quot;: &quot;Surat Keterangan Domisili&quot;,
        &quot;nama_pemohon&quot;: &quot;John Doe&quot;,
        &quot;nik_pemohon&quot;: &quot;1234567890123456&quot;,
        &quot;tanggal_terbit&quot;: &quot;01-06-2026&quot;,
        &quot;diverifikasi_oleh&quot;: &quot;operator&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;valid&quot;: false,
    &quot;message&quot;: &quot;Dokumen belum selesai diproses&quot;
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
               value="abc123def456789"
               data-component="url">
    <br>
<p>Hash SHA-256 dari QR Code. Example: <code>abc123def456789</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
