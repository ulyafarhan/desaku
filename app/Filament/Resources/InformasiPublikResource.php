<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InformasiPublikResource\Pages\ManageInformasiPublik;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InformasiPublikResource extends Resource
{
    protected static ?string $model = InformasiPublik::class;

    protected static ?string $recordTitleAttribute = 'judul';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Informasi Publik';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('judul')->required()->maxLength(255)->columnSpanFull(),
            TextInput::make('slug')->maxLength(255),
            TextInput::make('kategori')->required()->maxLength(50),
            FileUpload::make('cover_image_file')
                ->label('Upload Gambar Cover')
                ->image()
                ->disk('public')
                ->directory('informasi')
                ->columnSpanFull()
                ->afterStateHydrated(function ($component, $state, $record) {
                    if ($record && $record->cover_image && !str_starts_with($record->cover_image, 'http')) {
                        $component->state($record->cover_image);
                    }
                }),
            TextInput::make('cover_image_url')
                ->label('Atau URL Gambar Cover (CDN/External)')
                ->columnSpanFull()
                ->afterStateHydrated(function ($component, $state, $record) {
                    if ($record && $record->cover_image && str_starts_with($record->cover_image, 'http')) {
                        $component->state($record->cover_image);
                    }
                }),
            RichEditor::make('konten')->required()->columnSpanFull(),
            Toggle::make('is_published')->label('Publikasikan')->default(false),
            Select::make('author_id')->relationship('author', 'username')->default(fn () => auth('admin')->id()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('30s')
            ->columns([
                TextColumn::make('judul')->searchable()->sortable()->limit(50),
                TextColumn::make('kategori')->badge()->color('info')->searchable(),
                IconColumn::make('is_published')->boolean()->label('Terbit'),
                TextColumn::make('author.username')->label('Penulis'),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori')->options(fn () => InformasiPublik::query()->pluck('kategori', 'kategori')->all()),
                SelectFilter::make('is_published')
                    ->label('Status')
                    ->options([
                        '1' => 'Terbit',
                        '0' => 'Draft',
                    ]),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->actionsColumnLabel('Aksi');
    }

    public static function getPages(): array
    {
        return ['index' => ManageInformasiPublik::route('/')];
    }
}
