<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdministratorResource\Pages\ManageAdministrator;
use App\Models\Administrator;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class AdministratorResource extends Resource
{
    protected static ?string $model = Administrator::class;

    protected static ?string $recordTitleAttribute = 'username';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('username')->required()->maxLength(50),
            TextInput::make('password')
                ->password()
                ->revealable()
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $operation): bool => $operation === 'create'),
            Select::make('role')->options(['keuchik' => 'Keuchik', 'sekdes' => 'Sekdes', 'operator' => 'Operator'])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')->searchable()->sortable(),
                TextColumn::make('role')->badge(),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')->options(['keuchik' => 'Keuchik', 'sekdes' => 'Sekdes', 'operator' => 'Operator']),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageAdministrator::route('/')];
    }
}
