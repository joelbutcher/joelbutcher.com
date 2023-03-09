<?php

namespace App\Console\Commands;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DownloadHboTemplates extends Command
{
    /**
     * @var string
     */
    protected $signature = 'hbo:templates
                            {--stage : Indicates if we should pull from the staging environment.}';

    public function handle(): int
    {
        (new Filesystem())->ensureDirectoryExists(path: $this->path());

        $baseUrl = $this->option('stage')
            ? 'https://braze-files-stage.s3.eu-central-1.amazonaws.com'
            : 'https://braze-files-production.s3.eu-central-1.amazonaws.com';

        $xml = simplexml_load_string(Http::get($baseUrl)->body());

        $urls = [];
        foreach ($xml->Contents as $child) {
            $filename = (string) $child->Key;

            if (! Str::contains($filename, ['_EN_', '_ENG_'])) {
                continue;
            }

            $urls[$filename] = "$baseUrl/$filename";
        }

        collect($urls)
            ->chunk(100)
            ->map(fn (Collection $chunk) => Http::pool(fn (Pool $pool) => $chunk->map(
                callback: fn (string $url, string $filename) => $pool->as($filename)->get($url),
            )))->each(fn (array $chunk) => $this->processChunk($chunk));

        return Command::SUCCESS;
    }

    private function processChunk(array $chunk)
    {
        /** @var Response|ConnectException $response */
        foreach ($chunk as $filename => $response) {
            $this->processResponse($response, $filename);
        }
    }

    private function processResponse(ConnectException|Response $response, string $filename)
    {
        if ($response instanceof ConnectException) {
            dd($response->getHandlerContext());
        }

        $this->saveFile($filename, $response->body());
    }

    private function saveFile(string $filename, string $body): void
    {
        (new Filesystem())->put(
            path: $this->path($filename),
            contents: $body
        );
    }

    private function path(?string $filename = null): string
    {
        $directory = storage_path($this->option('stage') ? 'app/templates/stage' : 'app/templates');

        if (! $filename) {
            return $directory;
        }

        $filename = Str::replace('.txt', '.html', $filename);

        return "$directory/$filename";
    }
}
