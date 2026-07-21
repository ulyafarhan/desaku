<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FasilitasDesaResource\Pages;
use App\Models\FasilitasDesa;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FasilitasDesaResource extends Resource
{
    protected static ?string $model = FasilitasDesa::class;
    protected static ?string $recordTitleAttribute = 'nama_fasilitas';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Fasilitas Desa';
    protected static string|\UnitEnum|null $navigationGroup = 'Konten';
    protected static ?int $navigationSort = 5;

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama_fasilitas', 'kategori', 'lokasi'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Fasilitas')
                ->icon('heroicon-o-building-library')
                ->schema([
                    TextInput::make('nama_fasilitas')
                        ->label('Nama Fasilitas')
                        ->required()
                        ->maxLength(150)
                        ->prefixIcon('heroicon-o-building-library')
                        ->columnSpanFull(),
                    Select::make('kategori')
                        ->label('Kategori')
                        ->required()
                        ->searchable()
                        ->options([
                            'Pendidikan' => 'Pendidikan',
                            'Kesehatan' => 'Kesehatan',
                            'Olahraga' => 'Olahraga',
                            'Ibadah' => 'Ibadah',
                            'Infrastruktur' => 'Infrastruktur',
                            'Umum' => 'Umum',
                        ])
                        ->columnSpanFull(),
                    TextInput::make('lokasi')
                        ->label('Lokasi')
                        ->maxLength(200)
                        ->prefixIcon('heroicon-o-map-pin')
                        ->columnSpanFull(),
                    Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'Aktif' => 'Aktif',
                            'Rusak Ringan' => 'Rusak Ringan',
                            'Rusak Berat' => 'Rusak Berat',
                            'Tidak Aktif' => 'Tidak Aktif',
                        ])
                        ->default('Aktif')
                        ->columnSpanFull(),
                ]),
            Section::make('Deskripsi dan Foto')
                ->icon('heroicon-o-photo')
                ->schema([
                    Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->rows(5)
                        ->columnSpanFull(),
                    FileUpload::make('foto')
                        ->label('Foto Fasilitas')
                        ->image()
                        ->disk('public')
                        ->directory('fasilitas-desa')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('/images/default-fasilitas.png'),
                TextColumn::make('nama_fasilitas')
                    ->label('Nama Fasilitas')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pendidikan' => 'info',
                        'Kesehatan' => 'danger',
                        'Olahraga' => 'success',
                        'Ibadah' => 'warning',
                        'Infrastruktur' => 'gray',
                        default => 'secondary',
                    }),
                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->searchable()
                    ->limit(30),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Rusak Ringan' => 'Rusak Ringan',
                        'Rusak Berat' => 'Rusak Berat',
                        'Tidak Aktif' => 'Tidak Aktif',
                    ]),
                TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->options([
                        'Pendidikan' => 'Pendidikan',
                        'Kesehatan' => 'Kesehatan',
                        'Olahraga' => 'Olahraga',
                        'Ibadah' => 'Ibadah',
                        'Infrastruktur' => 'Infrastruktur',
                        'Umum' => 'Umum',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Rusak Ringan' => 'Rusak Ringan',
                        'Rusak Berat' => 'Rusak Berat',
                        'Tidak Aktif' => 'Tidak Aktif',
                    ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->actionsColumnLabel('Aksi')
            ->emptyStateHeading('Belum ada fasilitas desa')
            ->emptyStateDescription('Tambahkan fasilitas desa pertama dengan klik tombol di atas.')
            ->emptyStateIcon('heroicon-o-building-library')
            ->poll('30s')
            ->striped()
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFasilitasDesa::route('/'),
            'create' => Pages\CreateFasilitasDesa::route('/create'),
            'edit' => Pages\EditFasilitasDesa::route('/{record}/edit'),
        ];
    }
}