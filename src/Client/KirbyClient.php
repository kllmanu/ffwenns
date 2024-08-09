<?php

namespace App\Client;

use Contao\CoreBundle\Filesystem\VirtualFilesystemInterface;

class KirbyClient
{
    public function __construct(private readonly VirtualFilesystemInterface $filesStorage, readonly string $newsDir)
    {
    }

    public function posts(): array
    {
        $dirs = $this->filesStorage->listContents($this->newsDir)->directories();
        $posts = [];

        foreach ($dirs as $index => $dir) {
            $path = $dir->getPath();
            $file = $path . '/post.txt';
            $contents = $this->filesStorage->read($file);

            printf("[%04d/%d]: Reading %s\n", $index + 1, count($dirs->toArray()), $path);

            $posts[] = array_merge($this->decode($contents), $this->images($path));
        }

        return $posts;
    }

    private function images(string $path): array
    {
        $files = $this->filesStorage->listContents($path)->files();
        $images = [];

        foreach ($files as $file) {
            if ($file->isImage()) {
                $images[] = $file->getUuid()->toBinary();
            }
        }

        return [
            'images' => $images
        ];
    }

    /**
     * @see https://github.com/getkirby/kirby/blob/4.3.0/src/Data/Txt.php#L81
     */
    private function decode(string $string)
    {

        // explode all fields by the line separator
        $fields = preg_split('!\n----\s*\n*!', $string);

        // start the data array
        $data = [];

        // loop through all fields and add them to the content
        foreach ($fields as $field) {
            if ($pos = strpos($field, ':')) {
                $key = strtolower(trim(substr($field, 0, $pos)));
                $key = str_replace(['-', ' '], '_', $key);

                // Don't add fields with empty keys
                if (empty($key) === true) {
                    continue;
                }

                $value = trim(substr($field, $pos + 1));

                // unescape escaped dividers within a field
                $data[$key] = preg_replace(
                    '!(?<=\n|^)\\\\----!',
                    '----',
                    $value
                );
            }
        }

        return $data;
    }
}