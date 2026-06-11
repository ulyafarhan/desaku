<?php

namespace App\Filament\Resources;

use App\Events\MutasiStatusUpdated;
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

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan';

    protected static ?string $navigationLabel = 'Mutasi Penduduk';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = MutasiPenduduk::query()->where('status_verifikasi', 'Pending')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

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
            ->poll('15s')
            ->columns([
                TextColumn::make('penduduk.nama_lengkap')->label('Penduduk')->searchable(),
                TextColumn::make('jenis_mutasi')->badge()->color(fn (string $state): string => match ($state) {
                    'Kelahiran' => 'success',
                    'Kematian' => 'danger',
                    'Kedatangan' => 'info',
                    'Kepindahan' => 'warning',
                    default => 'gray',
                }),
                TextColumn::make('tanggal_mutasi')->date('d M Y')->sortable(),
                TextColumn::make('status_verifikasi')->badge()->color(fn (string $state): string => match ($state) {
                    'Pending' => 'warning',
                    'Disetujui' => 'success',
                    'Ditolak' => 'danger',
                    default => 'gray',
                }),
            ])
            ->filters([
                SelectFilter::make('status_verifikasi')->options(['Pending' => 'Pending', 'Disetujui' => 'Disetujui', 'Ditolak' => 'Ditolak']),
                SelectFilter::make('jenis_mutasi')->options(['Kelahiran' => 'Kelahiran', 'Kematian' => 'Kematian', 'Kedatangan' => 'Kedatangan', 'Kepindahan' => 'Kepindahan']),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (MutasiPenduduk $record): bool => $record->status_verifikasi === 'Pending')
                    ->requiresConfirmation()
                    ->action(function (MutasiPenduduk $record): void {
                        $oldStatus = $record->status_verifikasi;
                        $record->update([
                            'status_verifikasi' => 'Disetujui',
                            'diverifikasi_oleh' => auth('admin')->id(),
                        ]);
                        MutasiStatusUpdated::dispatch($record, $oldStatus, 'Disetujui');
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (MutasiPenduduk $record): bool => $record->status_verifikasi === 'Pending')
                    ->requiresConfirmation()
                    ->action(function (MutasiPenduduk $record): void {
                        $oldStatus = $record->status_verifikasi;
                        $record->update([
                            'status_verifikasi' => 'Ditolak',
                            'diverifikasi_oleh' => auth('admin')->id(),
                        ]);
                        MutasiStatusUpdated::dispatch($record, $oldStatus, 'Ditolak');
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->actionsColumnLabel('Aksi');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['penduduk']);
    }

    public static function getPages(): array
    {
        return ['index' => ManageMutasiPenduduk::route('/')];
    }
}
