<?php

namespace App\Observers;

use App\Models\TahunAjaran;

class TahunAjaranObserver
{
    /**
     * Handle the TahunAjaran "created" event.
     */
    public function created(TahunAjaran $tahunAjaran): void
    {
        // Jika active diatur ke true
        if ($tahunAjaran->active) {
            // Nonaktifkan semua data lain yang active = true
            TahunAjaran::where('active', true)
                ->where('id', '!=', $tahunAjaran->id)
                ->update(['active' => false]);
        }
    }

    /**
     * Handle the TahunAjaran "updated" event.
     */
    public function updated(TahunAjaran $tahunAjaran): void
    {
        //
        // Jika active diatur ke true
        if ($tahunAjaran->active) {
            // Nonaktifkan semua data lain yang active = true
            TahunAjaran::where('active', true)
                ->where('id', '!=', $tahunAjaran->id)
                ->update(['active' => false]);
        }
    }

    /**
     * Handle the TahunAjaran "deleted" event.
     */
    public function deleted(TahunAjaran $tahunAjaran): void
    {
        //
    }

    /**
     * Handle the TahunAjaran "restored" event.
     */
    public function restored(TahunAjaran $tahunAjaran): void
    {
        //
    }

    /**
     * Handle the TahunAjaran "force deleted" event.
     */
    public function forceDeleted(TahunAjaran $tahunAjaran): void
    {
        //
    }
}
