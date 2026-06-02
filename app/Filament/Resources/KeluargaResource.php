<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeluargaResource\Pages\ManageKeluarga;
use App\Models\Keluarga;
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

class KeluargaResource extends Resource
{
    protected static ?string $model = Keluarga::class;

    protected static ?string $recordTitleAttribute = 'no_kk';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('no_kk')->label('No KK')->required()->length(16),
            Textarea::make('alamat')->required()->columnSpanFull(),
            TextInput::make('dusun')->required(),
            TextInput::make('rt_rw')->label('RT/RW')->required(),
            Select::make('kepala_keluarga_nik')->relationship('kepalaKeluarga', 'nama_lengkap')->searchable()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_kk')->label('No KK')->searchable(),
                TextColumn::make('kepalaKeluarga.nama_lengkap')->label('Kepala Keluarga')->searchable(),
                TextColumn::make('dusun')->searchable(),
                TextColumn::make('rt_rw')->label('RT/RW'),
                TextColumn::make('anggota_count')->counts('anggota')->label('Anggota'),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageKeluarga::route('/')];
    }
}
