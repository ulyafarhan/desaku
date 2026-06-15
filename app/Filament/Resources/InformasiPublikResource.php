<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InformasiPublikResource\Pages;
use App\Models\InformasiPublik;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InformasiPublikResource extends Resource
{
    protected static ?string $model = InformasiPublik::class;

    protected static ?string $recordTitleAttribute = 'judul';

    public static function getGloballySearchableAttributes(): array
    {
        return ['judul', 'kategori'];
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Informasi Publik';

    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        $count = InformasiPublik::query()->published()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Konten Artikel')
                ->description('Tulis judul, isi, dan gambar cover artikel.')
                ->icon('heroicon-o-pencil-square')
                ->schema([
                    TextInput::make('judul')
                        ->label('Judul Artikel')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->prefixIcon('heroicon-o-document-text')
                        ->placeholder('Tulis judul yang menarik...')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, $set, $record) {
                            if (!$record || empty($record->slug)) {
                                $set('slug', \Illuminate\Support\Str::slug($state));
                            }
                        }),
                    TextInput::make('slug')
                        ->label('Slug URL')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-link')
                        ->placeholder('Otomatis dari judul')
                        ->required()
                        ->unique(ignoreRecord: true),
                    TextInput::make('kategori')
                        ->label('Kategori')
                        ->required()
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-tag')
                        ->placeholder('Contoh: Pengumuman, Berita'),
                    FileUpload::make('cover_image_file')
                        ->label('Upload Gambar Cover')
                        ->image()
                        ->disk('public')
                        ->directory('informasi')
                        ->columnSpanFull()
                        ->imagePreviewHeight('200')
                        ->afterStateHydrated(function ($component, $state, $record) {
                            if ($record && $record->cover_image && !str_starts_with($record->cover_image, 'http')) {
                                $component->state($record->cover_image);
                            }
                        })
                        ->saveUploadedFileUsing(function (\Livewire\Features\SupportFileUploads\TemporaryUploadedFile $file) {
                            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $filename = \Illuminate\Support\Str::slug($filename);
                            $webpName = $filename . '-' . time() . '.webp';
                            $destinationPath = 'informasi/' . $webpName;

                            $tempPath = $file->getRealPath();
                            $processedLocalPath = \App\Services\ImageService::compressToWebP($tempPath, null, 1600, 80);

                            if ($processedLocalPath) {
                                \Illuminate\Support\Facades\Storage::disk('public')->put($destinationPath, file_get_contents($processedLocalPath));
                                return $destinationPath;
                            }

                            return $file->storeAs('informasi', $webpName, 'public');
                        }),
                    TextInput::make('cover_image_url')
                        ->label('Atau URL Gambar Cover (CDN/External)')
                        ->columnSpanFull()
                        ->prefixIcon('heroicon-o-photo')
                        ->placeholder('https://example.com/gambar.jpg')
                        ->afterStateHydrated(function ($component, $state, $record) {
                            if ($record && $record->cover_image && str_starts_with($record->cover_image, 'http')) {
                                $component->state($record->cover_image);
                            }
                        }),
                    RichEditor::make('konten')
                        ->label('Isi Artikel')
                        ->required()
                        ->columnSpanFull()
                        ->hintAction(
                            \Filament\Actions\Action::make('fixWithAi')
                                ->label('Perbaiki dengan AI')
                                ->icon('heroicon-m-sparkles')
                                ->action(function ($state, $set, $get) {
                                    $judul = $get('judul');
                                    $trimmedState = trim(strip_tags($state ?? ''));

                                    if (empty($trimmedState) && empty($judul)) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Tulis judul artikel terlebih dahulu jika ingin membuat konten dari awal')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

                                    $isGenerating = empty($trimmedState);

                                    \Filament\Notifications\Notification::make()
                                        ->title($isGenerating ? 'Sedang memproses penulisan artikel dengan AI...' : 'Sedang memproses perbaikan tulisan...')
                                        ->info()
                                        ->send();

                                    $ai = app(\App\Services\Contracts\AiProviderInterface::class);
                                    $fixedText = $ai->fixCopywriting($state ?? '', $judul);

                                    if ($fixedText) {
                                        $set('konten', $fixedText);
                                        \Filament\Notifications\Notification::make()
                                            ->title($isGenerating ? 'Artikel berhasil ditulis dengan AI!' : 'Tulisan berhasil diperbaiki dengan AI!')
                                            ->success()
                                            ->send();
                                    } else {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Gagal menghubungi AI. Silakan coba beberapa saat lagi.')
                                            ->danger()
                                            ->send();
                                    }
                                })
                        ),
                ])->columns(1)->columnSpanFull(),

            Section::make('Optimasi SEO')
                ->description('Kelola meta deskripsi dan kata kunci artikel untuk meningkatkan performa SEO di mesin pencari.')
                ->icon('heroicon-o-globe-alt')
                ->schema([
                    TextInput::make('meta_description')
                        ->label('Meta Deskripsi (SEO)')
                        ->placeholder('Deskripsi ringkas artikel untuk hasil pencarian (maksimal 160 karakter)...')
                        ->maxLength(160)
                        ->columnSpanFull()
                        ->prefixIcon('heroicon-o-chat-bubble-bottom-center-text'),
                    TextInput::make('kata_kunci')
                        ->label('Kata Kunci SEO')
                        ->placeholder('Kata kunci dipisahkan dengan koma. Contoh: berita gampong, pembangunan jalan, dana desa')
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->prefixIcon('heroicon-o-key')
                        ->hintAction(
                            \Filament\Actions\Action::make('generateSeo')
                                ->label('Rekomendasikan SEO dengan AI')
                                ->icon('heroicon-m-sparkles')
                                ->action(function ($get, $set) {
                                    $judul = $get('judul');
                                    $konten = $get('konten');

                                    if (empty($judul) || empty($konten)) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Tulis judul dan isi artikel terlebih dahulu')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

                                    \Filament\Notifications\Notification::make()
                                        ->title('Sedang memproses rekomendasi SEO...')
                                        ->info()
                                        ->send();

                                    $ai = app(\App\Services\Contracts\AiProviderInterface::class);
                                    $seo = $ai->generateSeoMetadata($judul, $konten);

                                    if ($seo) {
                                        $set('meta_description', $seo['meta_description']);
                                        $set('kata_kunci', $seo['kata_kunci']);
                                        \Filament\Notifications\Notification::make()
                                            ->title('SEO berhasil dioptimalkan dengan AI!')
                                            ->success()
                                            ->send();
                                    } else {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Gagal menghubungi AI. Silakan coba beberapa saat lagi.')
                                            ->danger()
                                            ->send();
                                    }
                                })
                        ),
                ])->columns(1)->columnSpanFull(),

            Section::make('Pengaturan Publikasi')
                ->description('Atur status publikasi dan penulis artikel.')
                ->icon('heroicon-o-cog-6-tooth')
                ->schema([
                    Toggle::make('is_published')
                        ->label('Publikasikan')
                        ->default(false)
                        ->onIcon('heroicon-m-check')
                        ->offIcon('heroicon-m-x-mark')
                        ->onColor('success'),
                    Select::make('author_id')
                        ->label('Penulis')
                        ->relationship('author', 'username')
                        ->default(fn () => auth('admin')->id())
                        ->prefixIcon('heroicon-o-user'),
                ])->columns(1)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('15s')
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul')
                    ->sortable()
                    ->limit(45)
                    ->tooltip(fn ($record) => $record->judul)
                    ->weight('bold'),
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),
                IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('author.username')
                    ->label('Penulis')
                    ->toggleable(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInformasiPublik::route('/'),
        ];
    }
}
