<?php

namespace App\Classes\Medialibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;



class CustomPathGenerator implements PathGenerator
{
    /**
     * Get the path for the given media, relative to the root storage path.
     *
     * @param \Spatie\MediaLibrary\Media $media
     *
     * @return string
     */
    public function getPath(Media $media): string
    {
        $uuid = $media->uuid;
        return substr($uuid, 0, 2) . '/' . substr($uuid, 2, 2) . '/' . $uuid . '/';
    }
    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     *
     * @param \Spatie\MediaLibrary\Media $media
     *
     * @return string
     */
    public function getPathForConversions(Media $media): string
    {
        $uuid = $media->uuid;
        return substr($uuid, 0, 2) . '/' . substr($uuid, 2, 2) . '/' . $uuid . '/c/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . '/cri/';
    }

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        return $media->getKey();
    }
}
