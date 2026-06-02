<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSuratResource\Pages\ManagePengajuanSurat;
use App\Jobs\GenerateSuratPdfJob;
use App\Models\PengajuanSurat;
use App\Models\TrackingPengajuanSurat;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PengajuanSuratResource extends Resource
{
    protected static ?string $model = PengajuanSurat::class;

    protected static ?string $recordTitleAttribute = 'nomor_registrasi';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nomor_registrasi')->disabled(),
            Select::make('nik_pemohon')->relationship('pemohon', 'nama_lengkap')->searchable()->disabled(),
            Select::make('kategori_surat_id')->relationship('kategori', 'nama_surat')->searchable()->required(),
            Select::make('status')->options([
                'Pending' => 'Pending',
                'Diproses' => 'Diproses',
                'Disetujui' => 'Disetujui',
                'Ditolak' => 'Ditolak',
                'Selesai' => 'Selesai',
            ])->required(),
            Textarea::make('catatan_penolakan')->columnSpanFull(),
            TextInput::make('file_pdf_url')->label('URL PDF')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_registrasi')->searchable()->sortable(),
                TextColumn::make('pemohon.nama_lengkap')->label('Pemohon')->searchable(),
                TextColumn::make('kategori.nama_surat')->label('Jenis Surat')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'Pending' => 'Pending',
                    'Diproses' => 'Diproses',
                    'Disetujui' => 'Disetujui',
                    'Ditolak' => 'Ditolak',
                    'Selesai' => 'Selesai',
                ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->visible(fn (PengajuanSurat $record): bool => ! in_array($record->status, ['Disetujui', 'Selesai'], true))
                    ->requiresConfirmation()
                    ->action(function (PengajuanSurat $record): void {
                        $admin = auth('admin')->user();
                        $oldStatus = $record->status;

                        $record->update([
                            'status' => 'Disetujui',
                            'diverifikasi_oleh' => $admin?->id,
                        ]);

                        TrackingPengajuanSurat::create([
                            'pengajuan_surat_id' => $record->id,
                            'status_sebelumnya' => $oldStatus,
                            'status_baru' => 'Disetujui',
                            'keterangan_update' => 'Pengajuan disetujui oleh ' . ($admin?->username ?? 'admin'),
                            'diupdate_oleh' => $admin?->id,
                        ]);

                        GenerateSuratPdfJob::dispatch($record);
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->visible(fn (PengajuanSurat $record): bool => $record->status !== 'Ditolak')
                    ->schema([
                        Textarea::make('catatan_penolakan')->label('Catatan Penolakan')->required(),
                    ])
                    ->action(function (PengajuanSurat $record, array $data): void {
                        $admin = auth('admin')->user();
                        $oldStatus = $record->status;

                        $record->update([
                            'status' => 'Ditolak',
                            'catatan_penolakan' => $data['catatan_penolakan'],
                            'diverifikasi_oleh' => $admin?->id,
                        ]);

                        TrackingPengajuanSurat::create([
                            'pengajuan_surat_id' => $record->id,
                            'status_sebelumnya' => $oldStatus,
                            'status_baru' => 'Ditolak',
                            'keterangan_update' => $data['catatan_penolakan'],
                            'diupdate_oleh' => $admin?->id,
                        ]);
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManagePengajuanSurat::route('/')];
    }
}
