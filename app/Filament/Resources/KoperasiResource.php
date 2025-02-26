<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KoperasiResource\Pages;
use App\Filament\Resources\KoperasiResource\RelationManagers;
use App\Models\koperasi;
use App\Models\Asset;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KoperasiResource extends Resource
{
    protected static ?string $model = Koperasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    Public static function getPluralModelLabel(): string{
        return 'Koperasi Minjam';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Select::make('Anggota_id')
                    ->label('Anggota')
                    ->options(User::pluck('name','id'))
                    ->searchable()
                    ->preload()
                    
                    ->required(),

                    Forms\Components\Select::make('Asset_id')
                    ->label('Asset Barang')
                    ->searchable()
                    ->options(Asset::pluck('nama','id'))
                    ->required(),

                    Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah Barang')
                    ->placeholder('Masukan Jumlah Barang')
                    ->default(1)
                    ->required(),

                    Forms\Components\Textarea::make('descriptiom')
                    ->label('Desciption ')
                    ->placeholder('Masukan Description ')
                    ->required(),

                    Forms\Components\Select::make('status')
                    ->label('Status Barang')
                    ->options([
                        'Pinjam'=>'Pinjam',
                        'Selesai'=>'Selesai',
                    ])
                    ->default('Pinjam')
                    ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('Anggota_id')
                ->label('Nama Anggota')
                ->formatStateUsing(fn ($state) => User::find($state)?->name ?? '-')
                ->searchable(query: function ($query, $search) {
                    return $query->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                }),
            
            Tables\Columns\TextColumn::make('Asset_id')
                ->label('Nama Asset')
                ->formatStateUsing(fn ($state) => Asset::find($state)?->nama ?? '-')
                ->searchable(query: function ($query, $search) {
                    return $query->orWhereHas('asset', fn ($q) => $q->where('nama', 'like', "%{$search}%"));
                }),
                
                Tables\Columns\TextColumn::make('quantity')->label('Jumlah Barang'),
                Tables\Columns\BadgeColumn::make('status')->label('Status Barang')->colors([
                    'Pinjam'=>'Warning',
                    'Selesai'=>'primary',
                ])

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListKoperasis::route('/'),
            'create' => Pages\CreateKoperasi::route('/create'),
            'edit' => Pages\EditKoperasi::route('/{record}/edit'),
        ];
    }
}
