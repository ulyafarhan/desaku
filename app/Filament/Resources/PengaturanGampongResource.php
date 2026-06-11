<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaturanGampongResource\Pages\ManagePengaturanGampong;
use App\Models\PengaturanGampong;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PengaturanGampongResource extends Resource
{
    protected static ?string $model = PengaturanGampong::class;

    protected static ?string $recordTitleAttribute = 'kunci';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Desa';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('kunci')->required()->maxLength(50),
            Select::make('tipe_data')->options(['string' => 'String', 'integer' => 'Integer', 'boolean' => 'Boolean', 'json' => 'JSON'])->required(),
            Textarea::make('nilai')->required()->columnSpanFull(),
            TextInput::make('deskripsi')->maxLength(255)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kunci')->searchable()->sortable(),
                TextColumn::make('tipe_data')->badge(),
                TextColumn::make('nilai')->limit(60)->searchable(),
                TextColumn::make('updated_at')->dateTime('d M Y H:i'),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->actionsColumnLabel('Aksi');
    }

    public static function getPages(): array
    {
        return ['index' => ManagePengaturanGampong::route('/')];
    }
}
