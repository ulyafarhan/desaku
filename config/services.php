<?php

/**
 * KONFIGURASI LAYANAN PIHAK KETIGA — SIG-Udeung
 *
 * File ini menyimpan kredensial dan konfigurasi untuk berbagai layanan
 * eksternal yang digunakan oleh aplikasi, baik layanan pihak ketiga umum
 * (Mailgun, Postmark, AWS, Slack) maupun layanan khusus SIG-Udeung
 * (Telegram Bot, Gemini AI, API Kemendagri, dan OpenAI).
 *
 * Semua nilai kredensial diambil dari file .env melalui helper env().
 */

return [

    /*
    |--------------------------------------------------------------------------
    | LAYANAN PIHAK KETIGA UMUM
    |--------------------------------------------------------------------------
    |
    | Kredensial untuk layanan email, notifikasi, dan infrastruktur pihak ketiga
    | yang umum digunakan oleh paket Laravel.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | POSTMARK
    |--------------------------------------------------------------------------
    |
    | Layanan email transaksional. Token API digunakan untuk mengirim email
    | melalui API Postmark.
    |
    */
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | AWS SES (SIMPLE EMAIL SERVICE)
    |--------------------------------------------------------------------------
    |
    | Layanan email dari AWS. Membutuhkan key, secret, dan region.
    | Region default: us-east-1.
    |
    */
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | RESEND
    |--------------------------------------------------------------------------
    |
    | Layanan email modern sebagai alternatif Postmark/SES.
    |
    */
    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SLACK (NOTIFIKASI)
    |--------------------------------------------------------------------------
    |
    | Digunakan untuk mengirim notifikasi error dan alert ke channel Slack.
    | Membutuhkan OAuth token bot dan ID channel tujuan.
    |
    */
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | LAYANAN KHUSUS SIG-UDEUNG
    |--------------------------------------------------------------------------
    |
    | Berikut adalah konfigurasi untuk layanan-layanan yang terintegrasi
    | secara khusus dengan Sistem Informasi Gampong (SIG) Udeung.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | TELEGRAM BOT
    |--------------------------------------------------------------------------
    |
    | Bot Telegram digunakan untuk mengirim notifikasi dan informasi
    | kepada warga melalui grup Telegram. Konfigurasi meliputi token bot,
    | URL webhook untuk menerima update, dan ID grup chat tujuan.
    |
    */
    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'webhook_url' => env('TELEGRAM_WEBHOOK_URL'),
        'group_chat_id' => env('TELEGRAM_GROUP_CHAT_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | GOOGLE GEMINI AI
    |--------------------------------------------------------------------------
    |
    | Layanan AI dari Google yang digunakan untuk fitur chatbot/Asisten
    | Virtual Gampong. Model default: gemini-flash-lite-latest (ringan & cepat).
    |
    */
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-flash-lite-latest'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API KEMENDAGRI
    |--------------------------------------------------------------------------
    |
    | Integrasi dengan API Kementerian Dalam Negeri untuk sinkronisasi data
    | kependudukan dan kewilayahan (provinsi, kabupaten/kota, kecamatan,
    | gampong/desa).
    |
    */
    'kemendagri' => [
        'api_url' => env('KEMENDAGRI_API_URL'),
        'api_key' => env('KEMENDAGRI_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | LAYANAN AI (MULTI-PROVIDER)
    |--------------------------------------------------------------------------
    |
    | Konfigurasi multi-provider AI. Secara default menggunakan Gemini,
    | tetapi dapat dialihkan ke OpenAI dengan mengubah variabel AI_PROVIDER.
    |
    | Provider aktif ditentukan oleh: AI_PROVIDER (default: 'gemini')
    |
    | OpenAI:
    | - api_key  : Kunci API OpenAI
    | - model    : Model yang digunakan (default: gpt-4o-mini)
    | - base_url : Endpoint API, dapat diubah untuk proxy/kustom (default: https://api.openai.com/v1)
    |
    */
    'ai' => [
        'active_provider' => env('AI_PROVIDER', 'gemini'),
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        ],
    ],

];
