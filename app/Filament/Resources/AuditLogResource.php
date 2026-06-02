<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages\ManageAuditLogs;
use App\Models\AuditLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('user_type')->badge(),
                TextColumn::make('user_id'),
                TextColumn::make('tindakan')->searchable(),
                TextColumn::make('nama_tabel')->searchable(),
                TextColumn::make('record_id'),
            ])
            ->filters([
                SelectFilter::make('user_type')->options(['admin' => 'Admin', 'warga' => 'Warga']),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ManageAuditLogs::route('/')];
    }
}
