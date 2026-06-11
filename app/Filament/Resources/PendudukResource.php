<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendudukResource\Pages\ManagePenduduk;
use App\Models\Penduduk;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PendudukResource extends Resource
{
    protected static ?string $model = Penduduk::class;

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Data Kependudukan';

    protected static ?string $navigationLabel = 'Data Penduduk';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nik')->label('NIK')->required()->length(16),
            TextInput::make('no_kk')->label('No KK')->required()->length(16),
            TextInput::make('nama_lengkap')->required()->maxLength(100),
            TextInput::make('tempat_lahir')->required()->maxLength(50),
            DatePicker::make('tanggal_lahir')->required(),
            Select::make('jenis_kelamin')->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])->required(),
            TextInput::make('agama')->required(),
            TextInput::make('pendidikan')->required(),
            TextInput::make('pekerjaan')->required(),
            TextInput::make('status_perkawinan')->required(),
            TextInput::make('status_keluarga')->required(),
            Select::make('status_mutasi')->options(['Tetap' => 'Tetap', 'Pindah' => 'Pindah', 'Meninggal' => 'Meninggal'])->required(),
            TextInput::make('telegram_chat_id')->maxLength(50),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik')->label('NIK')->searchable(),
                TextColumn::make('nama_lengkap')->searchable()->sortable(),
                TextColumn::make('keluarga.dusun')->label('Dusun')->searchable(),
                TextColumn::make('keluarga.rt_rw')->label('RT/RW'),
                TextColumn::make('status_mutasi')->badge(),
            ])
            ->filters([
                SelectFilter::make('status_mutasi')->options(['Tetap' => 'Tetap', 'Pindah' => 'Pindah', 'Meninggal' => 'Meninggal']),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->actionsColumnLabel('Aksi');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['keluarga']);
    }

    public static function getPages(): array
    {
        return ['index' => ManagePenduduk::route('/')];
    }
}
