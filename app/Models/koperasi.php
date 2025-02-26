<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

use Illuminate\Validation\ValidationException;

class koperasi extends Model
{
    //
    protected $fillable = [
        'Anggota_id',
        'Asset_id',
        'quantity',
        'descriptiom',
        'status',
    ];

    public function Asset(){
        return $this->belongsTo(Asset::class,'Asset_id','id');
    }

    Public Function User(){
        return $this->belongsTo(User::class,'Anggota_id','id');

    }
  
   
   

    public static function boot()
    {
        parent::boot();

        static::creating(function ($koperasi) {
            $asset = Asset::find($koperasi->Asset_id);

            // Cek stok
            if (!$asset || $asset->quantity < $koperasi->quantity) {
                // Tampilkan notifikasi
                Notification::make()
                    ->title('Gagal Meminjam')
                    ->body('Stok tidak mencukupi untuk transaksi ini.')
                    ->danger()
                    ->send();

                // Batalkan proses dengan error 422 (Unprocessable Entity)
                throw ValidationException::withMessages([
                    'quantity' => 'Stok tidak mencukupi untuk transaksi ini.',
                ]);
            }

            // Kurangi stok jika cukup
            $asset->decrement('quantity', $koperasi->quantity);
        });

        static::updating(function ($koperasi) {
            if ($koperasi->isDirty('status') && $koperasi->status == 'Selesai') {
                $asset = Asset::find($koperasi->Asset_id);
                if ($asset) {
                    $asset->increment('quantity', $koperasi->getOriginal('quantity'));
                }
            }
        });
    }


}
