<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MutasiPendudukResource\Pages\ManageMutasiPenduduk;
use App\Models\MutasiPenduduk;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MutasiPendudukResource extends Resource
{
    protected static ?string $model = MutasiPenduduk::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('nik')->relationship('penduduk', 'nama_lengkap')->searchable()->required(),
            Select::make('jenis_mutasi')->options(['Kelahiran' => 'Kelahiran', 'Kematian' => 'Kematian', 'Kedatangan' => 'Kedatangan', 'Kepindahan' => 'Kepindahan'])->required(),
            DatePicker::make('tanggal_mutasi')->required(),
            Textarea::make('keterangan')->required()->columnSpanFull(),
            TextInput::make('dokumen_bukti')->required()->columnSpanFull(),
            Select::make('status_verifikasi')->options(['Pending' => 'Pending', 'Disetujui' => 'Disetujui', 'Ditolak' => 'Ditolak'])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('penduduk.nama_lengkap')->label('Penduduk')->searchable(),
                TextColumn::make('jenis_mutasi')->badge(),
                TextColumn::make('tanggal_mutasi')->date('d M Y')->sortable(),
                TextColumn::make('status_verifikasi')->badge(),
            ])
            ->filters([
                SelectFilter::make('status_verifikasi')->options(['Pending' => 'Pending', 'Disetujui' => 'Disetujui', 'Ditolak' => 'Ditolak']),
                SelectFilter::make('jenis_mutasi')->options(['Kelahiran' => 'Kelahiran', 'Kematian' => 'Kematian', 'Kedatangan' => 'Kedatangan', 'Kepindahan' => 'Kepindahan']),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([
                Action::make('approve')->label('Setujui')->color('success')->requiresConfirmation()->action(fn (MutasiPenduduk $record) => $record->update(['status_verifikasi' => 'Disetujui', 'diverifikasi_oleh' => auth('admin')->id()])),
                Action::make('reject')->label('Tolak')->color('danger')->requiresConfirmation()->action(fn (MutasiPenduduk $record) => $record->update(['status_verifikasi' => 'Ditolak', 'diverifikasi_oleh' => auth('admin')->id()])),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageMutasiPenduduk::route('/')];
    }
}
