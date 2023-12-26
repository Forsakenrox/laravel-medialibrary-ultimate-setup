<?php

namespace App\Models;

use App\Traits\Medialibrary\Mediable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as ModelMedia;

class Media extends ModelMedia implements HasMedia
{
    use HasFactory;
    use Mediable;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($user = auth()->user()) {
                $model->user_id = $user->id;
            }
            if ($user = auth('sanctum')->user()) {
                $model->user_id = $user->id;
            }
        });
    }

    public static function add($file)
    {
        $media = new Media();
        //Если модель не существует (загрузка временного файла)
        $media->exists = true;
        $newMedia = $media->addHashedMedia($file)->toMediaCollection('temp');
        return $newMedia;
    }

    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('cover')
    //         ->singleFile();
    // }

    // public function registerAllMediaConversions(?Media $media = null): void
    // {
    //     $this->addMediaConversion('thumb')->fit(Manipulations::FIT_MAX, 100, 100);
    //     $this->addMediaConversion('sm')->fit(Manipulations::FIT_MAX, 640, 640);
    //     $this->addMediaConversion('xl')->fit(Manipulations::FIT_MAX, 1440, 1440);
    // }
}
