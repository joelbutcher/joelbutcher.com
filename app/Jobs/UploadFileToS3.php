<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadFileToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  string|File|UploadedFile  $file
     */
    public function __construct(
        private readonly string $path,
        private readonly File|string|UploadedFile $file,
    ) {
        //
    }

    public function handle(FilesystemManager $storage): void
    {
        $storage->disk('s3')->put(
            $this->path,
            is_string($this->file) ? file_get_contents($this->file) : $this->file,
        );
    }
}
