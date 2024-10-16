<?php

namespace App\Filament\Resources;

use Filament\Tables\Actions\Action;

use App\Filament\Resources\SuratRekonResource\Pages;
use App\Models\BarangTransaksi;
use App\Models\Faskes;
use App\Models\SuratRekon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class SuratRekonResource extends Resource
{
    protected static ?string $model = SuratRekon::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $pluralModelLabel = 'Surat Rekon';
    protected static ?string $modelLabel = 'Surat Rekon';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nomor')
                ->required()
                ->label('Nomor Surat'),
            TextInput::make('spmb_nomor')
                ->required()
                ->label('SPMB'),
            DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal Surat'),
            Select::make('faskes_id')
                ->label('Faskes')
                ->options(Faskes::pluck('nama', 'id')->toArray())
                ->required()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('barang_transaksi_ids', [])),
            DatePicker::make('start_date')
                ->required()
                ->label('Tanggal Awal'),
            DatePicker::make('end_date')
                ->required()
                ->label('Tanggal Akhir'),
            Select::make('barang_transaksi_ids')
                ->label('Barang Transaksi (Rentang Tanggal)')
                ->options(function (callable $get) {
                    $faskesId = $get('faskes_id');
                    $startDate = $get('start_date');
                    $endDate = $get('end_date');

                    if (!$faskesId || !$startDate || !$endDate) return [];

                    // Mengambil barang transaksi dari faskes dalam rentang tanggal yang ditentukan
                    return BarangTransaksi::where('faskes_id', $faskesId)
                        ->whereBetween('tanggal_transaksi', [$startDate, $endDate]) // Filter berdasarkan rentang tanggal
                        ->with(['items.barangMaster']) // Memastikan relasi barang master dimuat
                        ->get()
                        ->pluck('detail', 'id');
                })
                ->multiple()
                ->required()
                ->reactive(),
            // TextInput::make('barangTransaksis.items.barangMaster.total_harga')
            //     ->disabled()
            //     ->label('Total Harga')
            //     ->reactive()
            //     ->afterStateHydrated(function (TextInput $component, $state) {
            //         $component->state(number_format($state, 2, ',', '.'));
            //     })
            //     ->dehydrateStateUsing(fn ($state) => str_replace([',', '.'], ['', '.'], $state)),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->label('Nomor Surat'),
                Tables\Columns\TextColumn::make('spmb_nomor')->label('SPMB'),
                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal Surat')->date(),
                Tables\Columns\TextColumn::make('faskes.nama')->label('Faskes'),
                Tables\Columns\TextColumn::make('start_date')->label('Tanggal Awal')->date(),
                Tables\Columns\TextColumn::make('end_date')->label('Tanggal Akhir')->date(),
                // Tables\Columns\TextColumn::make('total_harga')->label('Total Harga'),
            ])
            ->filters([/* Add any necessary filters here */])
            ->actions([
                Action::make('printREKON')
                    ->label('Print Rekon')
                    ->icon('heroicon-o-printer')
                    ->url(fn (SuratRekon $record) => route('print.surat-rekon', $record->id)) // Ganti dengan route yang sesuai
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratRekons::route('/'),
            'create' => Pages\CreateSuratRekon::route('/create'),
            'edit' => Pages\EditSuratRekon::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Laporan';
    }
    public static function getSlug(): string
    {
        return 'surat-rekon';
    }
}
