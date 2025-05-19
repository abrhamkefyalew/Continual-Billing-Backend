<?php

namespace App\Traits\Api\V1;

trait NonQueuedMediaConversions
{
    public function customizeMediaConversions(): void
    {
        // \Log::info('Registering media conversions...');

        $this->addMediaConversion('optimized')
            ->width(1000)
            ->height(1000)
            ->nonQueued();  // this can ALSO be configured in 'config/media-library.php' - BUT IF Only spatie 'config' file is published 

        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->nonQueued();  // this can ALSO be configured in 'config/media-library.php' - BUT IF Only spatie 'config' file is published 

        // \Log::info('Media conversions registered successfully.');
    }
}