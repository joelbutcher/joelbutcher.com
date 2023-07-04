<?php

namespace App\Listeners;

use App\Concerns\HandlesArticleEntries;
use App\Domains\Article\Actions\SaveArticle;
use App\Domains\Article\DTOs\ArticleData;
use App\Jobs\UploadFileToS3;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Statamic\Entries\Entry;
use Statamic\Events\EntrySaved;

class SaveArticleListener implements ShouldQueue
{
    use InteractsWithQueue, HandlesArticleEntries;

    public function __construct(
        private readonly SaveArticle $saveArticle
    ) {
    }

    public function handle(EntrySaved $event): void
    {
        if (! $this->isValid($event)) {
            return;
        }

        $entry = $event->entry;

        assert($entry instanceof Entry);

        if ($entry->has('image')) {
            $this->uploadImageToS3(strval($entry->get('image')));
        }

        ($this->saveArticle)(
            data: ArticleData::fromEntry($entry),
        );
    }

    private function uploadImageToS3(string $assetPath): void
    {
        if (! file_exists($filePath = public_path('assets/'.$assetPath))) {
            return;
        }

        dispatch(new UploadFileToS3(
            path: $assetPath,
            file: $filePath,
        ));
    }
}
