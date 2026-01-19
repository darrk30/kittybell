<?php

namespace App\Observers;

use App\Models\Presentation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PresentationObserver
{
    public function updating(Presentation $product): void
    {
        // Verifica si la imagen cambió
        if ($product->isDirty('image')) {
            $old = $product->getOriginal('image');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
        }
    }

    /**
     * Handle the Presentation "created" event.
     */
    public function created(Presentation $presentation): void
    {
        //
    }

    /**
     * Handle the Presentation "updated" event.
     */
    public function updated(Presentation $presentation): void
    {
        //
    }

    /**
     * Handle the Presentation "deleted" event.
     */
    public function deleted(Presentation $presentation): void
    {
        if ($presentation->image) {
            Storage::disk('public')->delete($presentation->image);
        }
    }

    /**
     * Handle the Presentation "restored" event.
     */
    public function restored(Presentation $presentation): void
    {
        //
    }

    /**
     * Handle the Presentation "force deleted" event.
     */
    public function forceDeleted(Presentation $presentation): void
    {
        //
    }
}
