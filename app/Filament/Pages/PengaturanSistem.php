<?php

namespace App\Filament\Pages;

use App\Models\PengaturanGampong;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

/**
 * Halaman kustom Filament untuk mengelola pengaturan sistem terintegrasi.
 *
 * Mencakup konfigurasi identitas, kontak, visi-misi, AI, dan aset desa.
 */
class PengaturanSistem extends Page implements HasForms
{
    use InteractsWithForms;

    /**
     * Judul halaman yang ditampilkan di tab/heading browser.
     *
     * @var string|null
     */
    protected static ?string $title = 'Pengaturan Gampong / Desa';

    /**
     * Label navigasi yang tampil di menu sidebar panel admin.
     *
     * @var string|null
     */
    protected static ?string $navigationLabel = 'Pengaturan Gampong';

    /**
     * Ikon navigasi sidebar yang diambil dari heroicons.
     *
     * @var string|\BackedEnum|null
     */
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-vertical';

    /**
     * Grup navigasi tempat menu ini dikelompokkan di sidebar.
     *
     * @var string|\UnitEnum|null
     */
    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    /**
     * Urutan tampilan menu dalam grup navigasi.
     *
     * @var int|null
     */
    protected static ?int $navigationSort = 8;

    /**
     * Path ke file view yang merender halaman ini.
     *
     * @var string
     */
    protected string $view = 'filament.pages.pengaturan-sistem';

    /**
     * Data formulir yang digunakan untuk mengikat (binding) nilai input.
     *
     * @var array|null
     */
    public ?array $data = [];

    /**
     * Mengisi data awal formulir dari penyimpanan pengaturan desa.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->form->fill([
            'nama_gampong' => PengaturanGampong::get('nama_gampong'),
            'kecamatan' => PengaturanGampong::get('kecamatan'),
            'kabupaten' => PengaturanGampong::get('kabupaten'),
            'provinsi' => PengaturanGampong::get('provinsi'),
            'kode_pos' => PengaturanGampong::get('kode_pos'),
            'email' => PengaturanGampong::get('email'),
            'telepon' => PengaturanGampong::get('telepon'),
            'nama_keuchik' => PengaturanGampong::get('nama_keuchik'),
            'nip_keuchik' => PengaturanGampong::get('nip_keuchik'),
            'visi' => PengaturanGampong::get('visi'),
            'misi' => PengaturanGampong::get('misi', []),

            'ai_active_provider' => PengaturanGampong::get('ai_active_provider', 'gemini'),
            'ai_gemini_key' => PengaturanGampong::get('ai_gemini_key'),
            'ai_openai_key' => PengaturanGampong::get('ai_openai_key'),
            'ai_openai_model' => PengaturanGampong::get('ai_openai_model', 'gpt-4o-mini'),
            'ai_openai_base_url' => PengaturanGampong::get('ai_openai_base_url', 'https://api.openai.com/v1'),
            'ai_providers_list' => PengaturanGampong::get('ai_providers_list', []),

            'storage_active_disk' => PengaturanGampong::get('storage_active_disk', 'public'),
            'storage_s3_key' => PengaturanGampong::get('storage_s3_key'),
            'storage_s3_secret' => PengaturanGampong::get('storage_s3_secret'),
            'storage_s3_bucket' => PengaturanGampong::get('storage_s3_bucket'),
            'storage_s3_region' => PengaturanGampong::get('storage_s3_region', 'us-east-1'),
            'storage_s3_endpoint' => PengaturanGampong::get('storage_s3_endpoint'),
            'storage_s3_url' => PengaturanGampong::get('storage_s3_url'),
            'storage_s3_use_path_style_endpoint' => PengaturanGampong::get('storage_s3_use_path_style_endpoint', '0'),

            'logo_gampong' => PengaturanGampong::get('logo_gampong'),
            'logo_fav' => PengaturanGampong::get('logo_fav'),
            'banner_gampong' => PengaturanGampong::get('banner_gampong'),
        ]);
    }

    /**
     * Mendefinisikan skema formulir pengaturan sistem.
     *
     * @param  Schema  $form Instance skema Filament.
     * @return Schema       Skema yang telah dilengkapi konfigurasi.
     */
    public function form(Schema $form): Schema
    {
        return $form
            ->components([
                Tabs::make('Pengaturan')
                    ->tabs([
                        Tab::make('Identitas Desa')
                            ->icon('heroicon-o-home-modern')
                            ->schema([
                                TextInput::make('nama_gampong')
                                    ->label('Nama Gampong / Desa')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('kabupaten')
                                    ->label('Kabupaten / Kota')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('provinsi')
                                    ->label('Provinsi')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('kode_pos')
                                    ->label('Kode Pos')
                                    ->required()
                                    ->maxLength(10),
                            ]),
                        Tab::make('Kontak & Pejabat')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                TextInput::make('nama_keuchik')
                                    ->label('Nama Keuchik (Kepala Desa)')
                                    ->required()
                                    ->maxLength(150),
                                TextInput::make('nip_keuchik')
                                    ->label('NIP Keuchik (Opsional jika PNS)')
                                    ->maxLength(50),
                                TextInput::make('email')
                                    ->label('Email Resmi Desa')
                                    ->email()
                                    ->maxLength(100),
                                TextInput::make('telepon')
                                    ->label('Nomor Telepon Kantor')
                                    ->maxLength(50),
                            ]),
                        Tab::make('Visi & Misi')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Textarea::make('visi')
                                    ->label('Visi Gampong / Desa')
                                    ->rows(3)
                                    ->maxLength(500),
                                Repeater::make('misi')
                                    ->label('Misi Gampong / Desa')
                                    ->simple(
                                        TextInput::make('misi_item')
                                            ->placeholder('Masukkan poin misi...')
                                            ->required()
                                    )
                                    ->helperText('Klik tombol "+ Tambah" di bawah untuk menambah poin misi gampong.')
                                    ->default([]),
                            ]),
                        Tab::make('Aset Visual Desa')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                FileUpload::make('logo_gampong')
                                    ->label('Logo Resmi Gampong')
                                    ->image()
                                    ->directory('gampong/logo')
                                    ->helperText('Unggah logo desa. Disarankan rasio 1:1 format PNG/SVG transparan.'),
                                FileUpload::make('logo_fav')
                                    ->label('Favicon Resmi Gampong')
                                    ->image()
                                    ->directory('gampong/favicon')
                                    ->helperText('Unggah favicon desa. Disarankan rasio 1:1 format SVG/PNG/ICO transparan.'),
                                FileUpload::make('banner_gampong')
                                    ->label('Foto/Banner Utama Desa')
                                    ->image()
                                    ->directory('gampong/banners')
                                    ->helperText('Unggah foto/banner pemandangan desa atau kantor keuchik untuk beranda publik.'),
                            ]),
                        Tab::make('Koneksi AI')
                            ->icon('heroicon-o-cpu-chip')
                            ->schema([
                                Select::make('ai_active_provider')
                                    ->label('Provider AI Aktif (Legacy)')
                                    ->options([
                                        'gemini' => 'Google Gemini AI',
                                        'openai' => 'OpenAI (atau kompatibel)',
                                    ])
                                    ->required()
                                    ->live(),
                                TextInput::make('ai_gemini_key')
                                    ->label('Gemini API Key')
                                    ->password()
                                    ->revealable()
                                    ->visible(fn ($get) => $get('ai_active_provider') === 'gemini'),
                                TextInput::make('ai_openai_key')
                                    ->label('OpenAI API Key')
                                    ->password()
                                    ->revealable()
                                    ->visible(fn ($get) => $get('ai_active_provider') === 'openai'),
                                TextInput::make('ai_openai_model')
                                    ->label('OpenAI Model')
                                    ->default('gpt-4o-mini')
                                    ->visible(fn ($get) => $get('ai_active_provider') === 'openai'),
                                TextInput::make('ai_openai_base_url')
                                    ->label('OpenAI Base URL')
                                    ->default('https://api.openai.com/v1')
                                    ->visible(fn ($get) => $get('ai_active_provider') === 'openai'),

                                Repeater::make('ai_providers_list')
                                    ->label('Daftar Prioritas & Fallback AI (Utama)')
                                    ->helperText('Jika daftar ini diisi, sistem akan menggunakan provider di bawah sesuai prioritas. Seret kartu untuk mengurutkan prioritas. Jika provider teratas limit/error, sistem otomatis fallback ke provider berikutnya.')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nama Pengenal')
                                            ->placeholder('Contoh: AI Utama (TokenRouter), Gemini Backup')
                                            ->required(),
                                        Select::make('provider_type')
                                            ->label('Tipe Provider')
                                            ->options([
                                                'gemini' => 'Google Gemini AI',
                                                'openai' => 'OpenAI (atau kompatibel)',
                                            ])
                                            ->required()
                                            ->live(),
                                        TextInput::make('api_key')
                                            ->label('API Key / Token')
                                            ->password()
                                            ->revealable(),
                                        TextInput::make('model')
                                            ->label('Model AI')
                                            ->placeholder('Contoh: gemini-flash-lite-latest atau deepseek-v4-flash')
                                            ->required(),
                                        TextInput::make('base_url')
                                            ->label('OpenAI Base URL')
                                            ->placeholder('https://api.openai.com/v1')
                                            ->visible(fn ($get) => $get('provider_type') === 'openai'),
                                        TextInput::make('priority')
                                            ->label('Urutan Prioritas')
                                            ->numeric()
                                            ->default(1)
                                            ->required(),
                                        Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true),
                                    ])
                                    ->columns(2)
                                    ->default([]),
                            ]),
                        Tab::make('Penyimpanan Awan')
                            ->icon('heroicon-o-cloud')
                            ->schema([
                                Select::make('storage_active_disk')
                                    ->label('Media Penyimpanan Utama')
                                    ->options([
                                        'public' => 'Penyimpanan Lokal (Server)',
                                        's3' => 'Penyimpanan Awan (AWS S3 / Cloudflare R2)',
                                    ])
                                    ->required()
                                    ->live(),
                                TextInput::make('storage_s3_key')
                                    ->label('Access Key ID')
                                    ->requiredIf('storage_active_disk', 's3')
                                    ->visible(fn ($get) => $get('storage_active_disk') === 's3'),
                                TextInput::make('storage_s3_secret')
                                    ->label('Secret Access Key')
                                    ->password()
                                    ->revealable()
                                    ->requiredIf('storage_active_disk', 's3')
                                    ->visible(fn ($get) => $get('storage_active_disk') === 's3'),
                                TextInput::make('storage_s3_bucket')
                                    ->label('Bucket Name')
                                    ->requiredIf('storage_active_disk', 's3')
                                    ->visible(fn ($get) => $get('storage_active_disk') === 's3'),
                                TextInput::make('storage_s3_region')
                                    ->label('Region')
                                    ->default('us-east-1')
                                    ->requiredIf('storage_active_disk', 's3')
                                    ->visible(fn ($get) => $get('storage_active_disk') === 's3'),
                                TextInput::make('storage_s3_endpoint')
                                    ->label('Endpoint URL (Diperlukan untuk Cloudflare R2)')
                                    ->placeholder('Contoh: https://<accountid>.r2.cloudflarestorage.com')
                                    ->visible(fn ($get) => $get('storage_active_disk') === 's3'),
                                TextInput::make('storage_s3_url')
                                    ->label('Custom Public URL')
                                    ->placeholder('Contoh: https://pub-xxxx.r2.dev')
                                    ->visible(fn ($get) => $get('storage_active_disk') === 's3'),
                                Select::make('storage_s3_use_path_style_endpoint')
                                    ->label('Gunakan Path-Style Endpoint')
                                    ->options([
                                        '0' => 'Tidak (Default S3)',
                                        '1' => 'Ya (MinIO / R2 Alternatif)',
                                    ])
                                    ->default('0')
                                    ->visible(fn ($get) => $get('storage_active_disk') === 's3'),
                            ]),
                    ])
            ])
            ->statePath('data');
    }

    /**
     * Menyimpan seluruh data formulir ke penyimpanan pengaturan desa.
     *
     * Setelah berhasil, notifikasi sukses akan dikirimkan ke antarmuka pengguna.
     *
     * @return void
     */
    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            $type = is_bool($value) ? 'boolean' : 'string';
            if (is_numeric($value) && !is_string($value)) {
                $type = 'integer';
            }
            if ($key === 'misi' || $key === 'ai_providers_list') {
                $type = 'json';
            }
            PengaturanGampong::set($key, $value, $type);
        }

        Notification::make()
            ->title('Konfigurasi Berhasil Disimpan')
            ->success()
            ->send();

        $this->redirect(static::getUrl(), navigate: false);
    }

    /**
     * Menjalankan migrasi file dari penyimpanan lokal ke penyimpanan cloud.
     *
     * Memanggil perintah Artisan `storage:migrate-to-cloud` dan menampilkan
     * hasilnya dalam bentuk notifikasi:
     * - Sukses   => menampilkan output migrasi.
     * - Gagal    => menampilkan pesan kesalahan umum.
     * - Exception => menampilkan pesan exception.
     *
     * @return void
     */
    public function runMigration(): void
    {
        try {
            $exitCode = Artisan::call('storage:migrate-to-cloud');
            $output = Artisan::output();

            if ($exitCode === 0) {
                Notification::make()
                    ->title('Migrasi Selesai')
                    ->body(nl2br(e($output)))
                    ->success()
                    ->persistent()
                    ->send();
            } else {
                Notification::make()
                    ->title('Migrasi Gagal')
                    ->body('Terjadi kesalahan selama proses migrasi data. Silakan periksa berkas log server.')
                    ->danger()
                    ->persistent()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Kesalahan Eksekusi')
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }
}