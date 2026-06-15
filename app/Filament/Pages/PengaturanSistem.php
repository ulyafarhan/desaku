<?php

namespace App\Filament\Pages;

use App\Models\PengaturanGampong;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class PengaturanSistem extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Pengaturan Sistem';

    protected static ?string $navigationLabel = 'Pengaturan Sistem';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-vertical';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 9;

    protected static string $view = 'filament.pages.pengaturan-sistem';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'ai_active_provider' => PengaturanGampong::get('ai_active_provider', 'gemini'),
            'ai_gemini_key' => PengaturanGampong::get('ai_gemini_key'),
            'ai_openai_key' => PengaturanGampong::get('ai_openai_key'),
            'ai_openai_model' => PengaturanGampong::get('ai_openai_model', 'gpt-4o-mini'),
            'ai_openai_base_url' => PengaturanGampong::get('ai_openai_base_url', 'https://api.openai.com/v1'),
            
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Pengaturan')
                    ->tabs([
                        Tab::make('Koneksi AI')
                            ->icon('heroicon-o-cpu-chip')
                            ->schema([
                                Select::make('ai_active_provider')
                                    ->label('Provider AI Aktif')
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
                                    ->requiredIf('ai_active_provider', 'gemini')
                                    ->visible(fn ($get) => $get('ai_active_provider') === 'gemini'),
                                TextInput::make('ai_openai_key')
                                    ->label('OpenAI API Key')
                                    ->password()
                                    ->revealable()
                                    ->requiredIf('ai_active_provider', 'openai')
                                    ->visible(fn ($get) => $get('ai_active_provider') === 'openai'),
                                TextInput::make('ai_openai_model')
                                    ->label('OpenAI Model')
                                    ->default('gpt-4o-mini')
                                    ->requiredIf('ai_active_provider', 'openai')
                                    ->visible(fn ($get) => $get('ai_active_provider') === 'openai'),
                                TextInput::make('ai_openai_base_url')
                                    ->label('OpenAI Base URL')
                                    ->default('https://api.openai.com/v1')
                                    ->requiredIf('ai_active_provider', 'openai')
                                    ->visible(fn ($get) => $get('ai_active_provider') === 'openai'),
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
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            $type = is_bool($value) ? 'boolean' : 'string';
            if (is_numeric($value) && !is_string($value)) {
                $type = 'integer';
            }
            PengaturanGampong::set($key, $value, $type);
        }

        Notification::make()
            ->title('Konfigurasi Berhasil Disimpan')
            ->success()
            ->send();
    }

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
