<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralModelLabel(): string{
        return 'Asset Koperasi';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('nomor_asset')
                    ->label('Nomot Asset')
                    ->numeric()
                    ->minLength(9)
                    ->placeholder('Masukan Nomor Asset')
                    ->required(),

                    Forms\Components\TextInput::make('nama')
                    ->label('Nama Asset')
                    ->placeholder('Masukan Nama Asset')
                    ->required(),

                    Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah Asset')
                    ->default(1)
                    ->placeholder('Silahkan Masukan Jumlah Nya')
                    ->required(),

                    Forms\Components\TextInput::make('description')
                    ->label('Desctiption Asset')
                    ->placeholder('Masukan Description ')
                    ->required()


                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('nomor_asset')->label('Nomor Asset')->searchable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama Asset')->searchable(),
                Tables\Columns\TextColumn::make('quantity')->label('Jumlah Asset'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
