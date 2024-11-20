<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratBastResource\Pages;
use App\Models\BarangTransaksi;
use App\Models\Faskes;
use App\Models\SuratBast;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;

class SuratBastResource extends Resource
{
    protected static ?string $model = SuratBast::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $pluralModelLabel = 'Surat BAST';

    protected static ?string $modelLabel = 'Surat BAST';

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nomor')
                ->required()
                ->label('Nomor Surat BAST')
                ->rule('regex:/^[a-zA-Z0-9\/\.\-\:\s]+$/'),
            DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal Surat BAST'),
            DatePicker::make('tanggal_transaksi')
                ->required()
                ->label('Tanggal Transaksi'),
            Select::make('faskes_id')
                ->label('Faskes')
                ->searchable()
                ->options(Faskes::pluck('nama', 'id'))
                ->required()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('barang_transaksi_ids', [])),
            Select::make('barang_transaksi_ids')
                ->label('Barang Transaksi')
                ->options(function (callable $get) {
                    $faskesId = $get('faskes_id');
                    $transactionDate = $get('tanggal_transaksi');

                    if (!$faskesId || !$transactionDate) return [];

                    return BarangTransaksi::where('faskes_id', $faskesId)
                        ->whereDate('tanggal_transaksi', [$transactionDate])
                        ->with(['items.barangMaster'])
                        ->get()
                        ->pluck('detail', 'id');
                })
                ->multiple()
                ->required()
                ->reactive(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nomor')
                ->label('Nomor Surat')
                ->sortable()
                ->searchable()
                ->getStateUsing(fn ($record) => $record->nomor),
            TextColumn::make('tanggal')
                ->label('Tanggal Surat')
                ->date()
                ->sortable(),
            TextColumn::make('faskes.nama')
                ->label('Faskes')
                ->sortable()
                ->searchable(),
            TextColumn::make('barangTransaksis.tanggal_transaksi')
                ->label('Tanggal Transaksi')
                ->date()
                ->getStateUsing(function ($record) {
                    return $record->barangTransaksis->map(function ($transaksi) {
                        $transactionDate = $transaksi->tanggal_transaksi;

                        if ($transactionDate instanceof \DateTime) {
                            return $transactionDate->format('d-m-Y');
                        } elseif (is_string($transactionDate)) {
                            return $transactionDate;
                        }

                        return 'N/A';
                    })->implode("<br>");
                })
                ->sortable()
                ->html(),
            TextColumn::make('barangTransaksis.items.barangMaster.nama_barang')
                ->label('Nama Barang')
                ->getStateUsing(function ($record) {
                    $items = $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->nama_barang;
                        });
                    })->unique()->values();

                    $formattedList = $items->map(function ($item, $index) {
                        return ($index + 1) . '. ' . $item;
                    })->implode('<br>');

                    return $formattedList;
                })
                ->html()
                ->alignLeft(),
            TextColumn::make('barangTransaksis.items.barangMaster.nomor_batch')
                ->label('Nomor Batch')
                ->getStateUsing(function ($record) {
                    $items = $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->nomor_batch;
                        });
                    })->unique()->values();

                    $formattedList = $items->map(function ($item, $index) {
                        return ($index + 1) . '. ' . $item;
                    })->implode('<br>');

                    return $formattedList;
                })
                ->html()
                ->alignLeft(),
            TextColumn::make('barangTransaksis.items.jumlah')
                ->label('Jumlah')
                ->getStateUsing(function ($record) {
                    return $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->jumlah;
                        });
                    })->sum();
                }),
            TextColumn::make('barangTransaksis.items.barangMaster.harga_satuan')
                ->label('Harga Satuan')
                ->getStateUsing(function ($record) {
                    return $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            $formattedPrice = 'Rp. ' . number_format($item->barangMaster->harga_satuan, 2, ',', '.');
                            return "<div class='px-2 py-1'>{$formattedPrice}</div>";
                        });
                    })->unique()->implode('');
                })
                ->html()
                ->alignRight(),
            TextColumn::make('total_harga')
                ->label('Total Harga')
                ->getStateUsing(function ($record) {
                    $totalHarga = $record->barangTransaksis->sum(function ($transaksi) {
                        return $transaksi->items->sum(function ($item) {
                            return $item->jumlah * $item->barangMaster->harga_satuan;
                        });
                    });

                    return 'Rp. ' . number_format($totalHarga, 2, ',', '.');
                }),
        ])
            ->actions([
                Action::make('printBAST')
                    ->label('Print BAST')
                    ->icon('heroicon-o-printer')
                    ->url(fn (SuratBast $record) => route('print.surat-serah-terima', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratBasts::route('/'),
            'create' => Pages\CreateSuratBast::route('/create'),
            'edit' => Pages\EditSuratBast::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Laporan';
    }

    public static function getSlug(): string
    {
        return 'surat-bast';
    }
}
