<?php

namespace App\Traits\Medialibrary;

use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;

trait Mediable
{
    use InteractsWithMedia;

    public function addHashedMedia($file)
    {
        $name = Str::lower(Str::random(10));
        $ext  = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        return $this->addMedia($file)
            // ->usingName($name)
            ->usingFileName(Str::lower("$name.$ext"));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile();
    }

    public function registerAllMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Manipulations::FIT_MAX, 100, 100);
        $this->addMediaConversion('sm')->fit(Manipulations::FIT_MAX, 640, 640);
        $this->addMediaConversion('xl')->fit(Manipulations::FIT_MAX, 1440, 1440);
    }
}
