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

    // Menyimpan data item original sebelum update
    protected $originalItems = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeFill(): void
    {
        // Simpan data original items sebelum form diisi
        $this->originalItems = $this->record->items->map(function ($item) {
            return [
                'id' => $item->id,
                'barang_master_id' => $item->barang_master_id,
                'jumlah' => $item->jumlah,
            ];
        })->keyBy('id')->toArray();
    }

    protected function beforeSave(): void
{
    DB::beginTransaction();
    try {
        $newItems = collect($this->data['items']);

        // 1. Proses setiap item baru
        foreach ($newItems as $newItem) {
            $barangMaster = BarangMaster::find($newItem['barang_master_id']);
            if (!$barangMaster) continue;

            // Cari item yang sama di original items
            $originalItem = collect($this->originalItems)->first(function ($item) use ($newItem) {
                return $item['barang_master_id'] == $newItem['barang_master_id'];
            });

            if ($originalItem) {
                // Item sudah ada sebelumnya, hitung selisih
                $selisih = $newItem['jumlah'] - $originalItem['jumlah'];

                if ($selisih > 0) {
                    // Jika jumlah baru lebih besar, kurangi stok
                    if ($barangMaster->stock < $selisih) {
                        throw new \Exception("Stok tidak mencukupi untuk {$barangMaster->nama_barang}. Stok tersedia: {$barangMaster->stock}");
                    }
                    $barangMaster->stock -= $selisih;
                } else {
                    // Jika jumlah baru lebih kecil, kembalikan stok
                    $barangMaster->stock += abs($selisih);
                }
            } else {
                // Item baru, kurangi stok
                if ($barangMaster->stock < $newItem['jumlah']) {
                    throw new \Exception("Stok tidak mencukupi untuk {$barangMaster->nama_barang}. Stok tersedia: {$barangMaster->stock}");
                }
                $barangMaster->stock -= $newItem['jumlah'];
            }

            $barangMaster->save();
        }

        // 2. Kembalikan stok untuk item yang dihapus
        foreach ($this->originalItems as $originalItem) {
            // Cek apakah item masih ada di newItems
            $masihAda = $newItems->contains(function ($newItem) use ($originalItem) {
                return $newItem['barang_master_id'] == $originalItem['barang_master_id'];
            });

            if (!$masihAda) {
                // Jika item dihapus, kembalikan stok
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
            ->title('Success')
            ->success()
            ->body('Transaksi berhasil diupdate dan stok telah disesuaikan')
            ->send();

        // Redirect ke halaman index setelah save
        $this->redirect($this->getResource()::getUrl('index'));
    }

    // Handle when edit is canceled
    protected function afterCancel(): void
    {
        $this->redirect($this->getResource()::getUrl('index'));
    }

    // Handle when record is deleted
    protected function afterDelete(): void
    {
        DB::beginTransaction();
        try {
            // Kembalikan semua stok saat transaksi dihapus
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
