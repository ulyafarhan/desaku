<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InformasiPublikResource\Pages\ManageInformasiPublik;
use App\Models\InformasiPublik;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('judul')->required()->maxLength(255),
            TextInput::make('slug')->maxLength(255),
            TextInput::make('kategori')->required()->maxLength(50),
            TextInput::make('cover_image')->label('URL Cover')->columnSpanFull(),
            Textarea::make('konten')->required()->rows(8)->columnSpanFull(),
            Toggle::make('is_published')->label('Publikasikan')->default(false),
            Select::make('author_id')->relationship('author', 'username')->default(fn () => auth('admin')->id()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->searchable()->sortable(),
                TextColumn::make('kategori')->badge()->searchable(),
                IconColumn::make('is_published')->boolean()->label('Terbit'),
                TextColumn::make('author.username')->label('Penulis'),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori')->options(fn () => InformasiPublik::query()->pluck('kategori', 'kategori')->all()),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageInformasiPublik::route('/')];
    }
}
