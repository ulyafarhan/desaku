<?php

namespace App\Filament\Resources;

use App\Events\PengajuanStatusUpdated;
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

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class PengajuanSuratResource extends Resource
{
    protected static ?string $model = PengajuanSurat::class;

    protected static ?string $recordTitleAttribute = 'nomor_registrasi';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan';

    protected static ?string $navigationLabel = 'Pengajuan Surat';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = PengajuanSurat::query()->pending()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Pengajuan')
                ->schema([
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
                ])->columns(2),

            Section::make('Detail Data & Dokumen Persyaratan')
                ->schema([
                    Placeholder::make('data_isian_details')
                        ->label('Data Isian Formulir')
                        ->content(function ($record) {
                            if (! $record || empty($record->data_isian)) {
                                return 'Tidak ada data.';
                            }
                            $html = '<table class="min-w-full divide-y divide-slate-200 border text-sm rounded-lg overflow-hidden">';
                            $html .= '<tbody class="divide-y divide-slate-200 bg-white">';
                            foreach ($record->data_isian as $key => $value) {
                                $html .= sprintf(
                                    '<tr><td class="whitespace-nowrap px-4 py-2 font-semibold text-slate-800 border-r bg-slate-50 w-1/3">%s</td><td class="px-4 py-2 text-slate-700">%s</td></tr>',
                                    e(ucwords(str_replace('_', ' ', $key))),
                                    e($value)
                                );
                            }
                            $html .= '</tbody></table>';
                            return new HtmlString($html);
                        })->columnSpanFull(),

                    Placeholder::make('file_syarat_links')
                        ->label('File Persyaratan')
                        ->content(function ($record) {
                            if (! $record || empty($record->file_syarat)) {
                                return 'Tidak ada dokumen.';
                            }
                            $html = '<ul class="list-disc pl-5 space-y-1 text-sm">';
                            foreach ($record->file_syarat as $label => $path) {
                                if (empty($path)) continue;
                                $url = str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
                                $html .= sprintf(
                                    '<li><strong class="text-slate-700">%s:</strong> <a href="%s" target="_blank" class="text-teal-600 underline font-semibold hover:text-teal-700 inline-flex items-center gap-1">Buka / Download Berkas <svg class="size-3.5 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></a></li>',
                                    e(ucwords(str_replace('_', ' ', $label))),
                                    e($url)
                                );
                            }
                            $html .= '</ul>';
                            return new HtmlString($html);
                        })->columnSpanFull(),
                ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('15s')
            ->columns([
                TextColumn::make('nomor_registrasi')->searchable()->sortable(),
                TextColumn::make('pemohon.nama_lengkap')->label('Pemohon')->searchable(),
                TextColumn::make('kategori.nama_surat')->label('Jenis Surat')->searchable(),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'Pending' => 'warning',
                    'Diproses' => 'info',
                    'Disetujui' => 'success',
                    'Ditolak' => 'danger',
                    'Selesai' => 'success',
                    default => 'gray',
                }),
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
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (PengajuanSurat $record): bool => ! in_array($record->status, ['Disetujui', 'Selesai'], true))
                    ->requiresConfirmation()
                    ->action(function (PengajuanSurat $record): void {
                        $admin = auth('admin')->user();

                        \Illuminate\Support\Facades\DB::transaction(function () use ($record, $admin) {
                            $lockedRecord = PengajuanSurat::where('id', $record->id)->lockForUpdate()->firstOrFail();
                            $oldStatus = $lockedRecord->status;

                            PengajuanSurat::withoutEvents(function () use ($lockedRecord, $admin) {
                                $lockedRecord->update([
                                    'status' => 'Disetujui',
                                    'diverifikasi_oleh' => $admin?->id,
                                ]);
                            });

                            TrackingPengajuanSurat::create([
                                'pengajuan_surat_id' => $lockedRecord->id,
                                'status_sebelumnya' => $oldStatus,
                                'status_baru' => 'Disetujui',
                                'keterangan_update' => 'Pengajuan disetujui oleh ' . ($admin?->username ?? 'admin'),
                                'diupdate_oleh' => $admin?->id,
                            ]);

                            if (app()->runningUnitTests()) {
                                GenerateSuratPdfJob::dispatch($lockedRecord);
                            } else {
                                GenerateSuratPdfJob::dispatchSync($lockedRecord);
                            }

                            // Broadcast/dispatch event
                            PengajuanStatusUpdated::dispatch($lockedRecord, $oldStatus, 'Disetujui');
                        });
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (PengajuanSurat $record): bool => $record->status !== 'Ditolak')
                    ->schema([
                        Textarea::make('catatan_penolakan')->label('Catatan Penolakan')->required(),
                    ])
                    ->action(function (PengajuanSurat $record, array $data): void {
                        $admin = auth('admin')->user();

                        \Illuminate\Support\Facades\DB::transaction(function () use ($record, $admin, $data) {
                            $lockedRecord = PengajuanSurat::where('id', $record->id)->lockForUpdate()->firstOrFail();
                            $oldStatus = $lockedRecord->status;

                            PengajuanSurat::withoutEvents(function () use ($lockedRecord, $admin, $data) {
                                $lockedRecord->update([
                                    'status' => 'Ditolak',
                                    'catatan_penolakan' => $data['catatan_penolakan'],
                                    'diverifikasi_oleh' => $admin?->id,
                                ]);
                            });

                            TrackingPengajuanSurat::create([
                                'pengajuan_surat_id' => $lockedRecord->id,
                                'status_sebelumnya' => $oldStatus,
                                'status_baru' => 'Ditolak',
                                'keterangan_update' => $data['catatan_penolakan'],
                                'diupdate_oleh' => $admin?->id,
                            ]);

                            // Broadcast/dispatch event
                            PengajuanStatusUpdated::dispatch($lockedRecord, $oldStatus, 'Ditolak');
                        });
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('approveBulk')
                        ->label('Setujui Massal')
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Support\Collection $records): void {
                            $admin = auth('admin')->user();
                            
                            \Illuminate\Support\Facades\DB::transaction(function () use ($records, $admin) {
                                foreach ($records as $record) {
                                    // Skip already approved or completed
                                    if (in_array($record->status, ['Disetujui', 'Selesai'], true)) {
                                        continue;
                                    }

                                    $lockedRecord = PengajuanSurat::where('id', $record->id)->lockForUpdate()->firstOrFail();
                                    $oldStatus = $lockedRecord->status;

                                    PengajuanSurat::withoutEvents(function () use ($lockedRecord, $admin) {
                                        $lockedRecord->update([
                                            'status' => 'Disetujui',
                                            'diverifikasi_oleh' => $admin?->id,
                                        ]);
                                    });

                                    TrackingPengajuanSurat::create([
                                        'pengajuan_surat_id' => $lockedRecord->id,
                                        'status_sebelumnya' => $oldStatus,
                                        'status_baru' => 'Disetujui',
                                        'keterangan_update' => 'Pengajuan disetujui secara massal oleh ' . ($admin?->username ?? 'admin'),
                                        'diupdate_oleh' => $admin?->id,
                                    ]);

                                    if (app()->runningUnitTests()) {
                                        GenerateSuratPdfJob::dispatch($lockedRecord);
                                    } else {
                                        GenerateSuratPdfJob::dispatchSync($lockedRecord);
                                    }

                                    // Dispatch status update event
                                    PengajuanStatusUpdated::dispatch($lockedRecord, $oldStatus, 'Disetujui');
                                }
                            });
                        }),
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->actionsColumnLabel('Aksi');
    }

    public static function getPages(): array
    {
        return ['index' => ManagePengajuanSurat::route('/')];
    }
}
