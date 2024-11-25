<?php

namespace App\Filament\Resources\BarangTransaksiResource\Pages;

use App\Filament\Resources\BarangTransaksiResource;
use App\Models\BarangMaster;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class EditBarangTransaksi extends EditRecord
{
    protected static string $resource = BarangTransaksiResource::class;
    protected $originalItems = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeFill(): void
    {
        // Store original items data before form is filled
        $this->originalItems = $this->record->items->map(function ($item) {
            return [
                'id' => $item->id,
                'barang_master_id' => $item->barang_master_id,
                'jumlah' => $item->jumlah,
                'harga_satuan' => $item->harga_satuan
            ];
        })->keyBy('id')->toArray();
    }

    protected function beforeSave(): void
    {
        DB::beginTransaction();
        try {
            $newItems = collect($this->data['items']);

            // Proses perubahan jumlah pada item yang ada
            foreach ($newItems as $newItem) {
                $barangMaster = BarangMaster::find($newItem['barang_master_id']);
                if (!$barangMaster) {
                    throw new \Exception("Barang tidak ditemukan");
                }

                // Cari item yang sesuai di original items
                $originalItem = collect($this->originalItems)->first(function ($item) use ($newItem) {
                    return $item['barang_master_id'] == $newItem['barang_master_id'];
                });

                if ($originalItem) {
                    // Jika jumlah baru lebih kecil dari jumlah lama
                    if ($newItem['jumlah'] < $originalItem['jumlah']) {
                        // Hitung selisih
                        $selisih = $originalItem['jumlah'] - $newItem['jumlah'];
                        // Tambahkan selisih ke stock
                        $barangMaster->stock += $selisih;
                        $barangMaster->save();
                    }
                    // Jika jumlah baru lebih besar, tidak perlu melakukan apa-apa
                }
            }

            // Proses item yang dihapus
            foreach ($this->originalItems as $originalItem) {
                $stillExists = $newItems->contains(function ($newItem) use ($originalItem) {
                    return $newItem['barang_master_id'] == $originalItem['barang_master_id'];
                });

                if (!$stillExists) {
                    // Kembalikan stock untuk item yang dihapus
                    $barangMaster = BarangMaster::find($originalItem['barang_master_id']);
                    if ($barangMaster) {
                        $barangMaster->stock += $originalItem['jumlah'];
                        $barangMaster->save();
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error')
                ->danger()
                ->body($e->getMessage())
                ->send();

            $this->halt();
        }
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Berhasil')
            ->success()
            ->body('Transaksi berhasil diupdate dan stok telah disesuaikan')
            ->send();

        $this->redirect($this->getResource()::getUrl('index'));
    }

    protected function afterCancel(): void
    {
        $this->redirect($this->getResource()::getUrl('index'));
    }

    protected function afterDelete(): void
    {
        DB::beginTransaction();
        try {
            foreach ($this->originalItems as $originalItem) {
                $barangMaster = BarangMaster::find($originalItem['barang_master_id']);
                if ($barangMaster) {
                    $barangMaster->stock += $originalItem['jumlah'];
                    $barangMaster->save();
                }
            }

            DB::commit();

            Notification::make()
                ->title('Berhasil')
                ->success()
                ->body('Transaksi berhasil dihapus dan stok telah dikembalikan')
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('Gagal mengembalikan stok: ' . $e->getMessage())
                ->send();
        }

        $this->redirect($this->getResource()::getUrl('index'));
    }
}
