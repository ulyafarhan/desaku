<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BotKnowledgeResource\Pages;
use App\Models\BotKnowledge;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BotKnowledgeResource extends Resource
{
    protected static ?string $model = BotKnowledge::class;

    protected static ?string $recordTitleAttribute = 'pertanyaan_atau_topik';

    public static function getGloballySearchableAttributes(): array
    {
        return ['pertanyaan_atau_topik', 'kunci', 'jawaban_atau_konten'];
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Bot Knowledge Base';

    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Bot Knowledge Base')
                ->description('Kelola data FAQ dan basis pengetahuan Telegram Bot.')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->schema([
                    TextInput::make('kunci')
                        ->label('Kunci / Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-key')
                        ->placeholder('Contoh: sktm, profil_desa'),
                    Select::make('tipe')
                        ->label('Tipe Data')
                        ->options([
                            'faq' => 'FAQ (Jawaban Instan)',
                            'kb' => 'RAG KB (Context AI)',
                        ])
                        ->required()
                        ->prefixIcon('heroicon-o-variable'),
                    TextInput::make('pertanyaan_atau_topik')
                        ->label('Pertanyaan atau Topik')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-information-circle')
                        ->placeholder('Contoh: Surat Keterangan Tidak Mampu'),
                    TextInput::make('kata_kunci')
                        ->label('Kata Kunci (dipisahkan koma)')
                        ->required()
                        ->placeholder('sktm, tidak mampu, miskin')
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? $state : array_map('trim', explode(',', $state)))
                        ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                        ->prefixIcon('heroicon-o-tag'),
                    Textarea::make('jawaban_atau_konten')
                        ->label('Jawaban atau Konten Context')
                        ->required()
                        ->rows(6)
                        ->columnSpanFull()
                        ->placeholder('Masukkan jawaban langsung (FAQ) atau konten pendukung (KB)...'),
                    Toggle::make('is_aktif')
                        ->label('Aktif')
                        ->default(true)
                        ->onIcon('heroicon-m-check')
                        ->offIcon('heroicon-m-x-mark')
                        ->onColor('success'),
                ])->columns(1)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pertanyaan_atau_topik')
                    ->label('Pertanyaan / Topik')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('kunci')
                    ->label('Kunci')
                    ->sortable()
                    ->copyable(),
                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'faq' => 'success',
                        'kb' => 'info',
                        default => 'gray',
                    }),
                IconColumn::make('is_aktif')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->since()
                    ->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->actionsColumnLabel('Aksi')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBotKnowledge::route('/'),
        ];
    }
}
