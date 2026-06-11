<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriSuratResource\Pages\ManageKategoriSurat;
use App\Models\KategoriSurat;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KategoriSuratResource extends Resource
{
    protected static ?string $model = KategoriSurat::class;

    protected static ?string $recordTitleAttribute = 'nama_surat';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Kategori Surat';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('kode_surat')->required()->maxLength(20),
            TextInput::make('nama_surat')->required()->maxLength(100),
            TextInput::make('template_view')->required()->maxLength(100),
            Textarea::make('schema_isian')
                ->label('Schema Isian JSON')
                ->formatStateUsing(fn ($state) => json_encode($state ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
                ->dehydrateStateUsing(fn ($state) => json_decode($state ?: '[]', true) ?: [])
                ->required()
                ->columnSpanFull(),
            Textarea::make('syarat_dokumen')
                ->label('Syarat Dokumen JSON')
                ->formatStateUsing(fn ($state) => json_encode($state ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
                ->dehydrateStateUsing(fn ($state) => json_decode($state ?: '[]', true) ?: [])
                ->required()
                ->columnSpanFull(),
            Toggle::make('is_active')->label('Aktif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_surat')->searchable(),
                TextColumn::make('nama_surat')->searchable()->sortable(),
                TextColumn::make('template_view'),
                IconColumn::make('is_active')->boolean()->label('Aktif'),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->actionsColumnLabel('Aksi');
    }

    public static function getPages(): array
    {
        return ['index' => ManageKategoriSurat::route('/')];
    }
}
