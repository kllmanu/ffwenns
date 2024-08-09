<?php

namespace App\Model;

use Contao\CoreBundle\Filesystem\VirtualFilesystemInterface;
use Contao\Dbafs;
use Imagine\Imagick\Imagine;
use Symfony\Component\Filesystem\Path;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FacebookPostAttachments
{
    private readonly Imagine $imagine;

    public function __construct(
        private readonly VirtualFilesystemInterface $filesStorage,
        private readonly string                     $newsDir,
        private readonly HttpClientInterface        $httpClient,
        private readonly string                     $accessToken
    )
    {
        $this->imagine = new Imagine();
    }

    public function parse(array $data, string $sid): array
    {
        $query = $this->buildBatchQuery($data);
        $attachments = $this->fetchAttachments($query);
        $images = $this->parseAttachments($attachments, $sid);

        return ['images' => $images];
    }

    /**
     * @throws \Exception
     */
    private function downloadAttachment(array $attachment, $sid): string
    {
        $body = json_decode($attachment['body'], true);
        $path = parse_url($body['images'][0]['source'], PHP_URL_PATH);

        $file = Path::getFilenameWithoutExtension($path);
        $dest = Path::join($this->newsDir, $sid);
        $webp = Path::join($dest, $file) . ".webp";

        $this->filesStorage->createDirectory($dest);

        if (!$this->filesStorage->fileExists($webp)) {
            $contents = file_get_contents($body['images'][0]['source']);

            $image = $this->imagine->load($contents);
            $image->save('./files/' . $webp, ['quality' => 80]);
        }

        return Dbafs::addResource(Path::join('files', $webp))->uuid;
    }

    private function parseAttachments(array $attachments, string $sid): array
    {
        $images = [];

        foreach ($attachments as $attachment) {
            if ($attachment['code'] != 200) {
                continue;
            }

            $images[] = $this->downloadAttachment($attachment, $sid);
        }

        return $images;
    }

    private function fetchAttachments(array $query): array
    {
        $response = $this->httpClient->request('POST', 'https://graph.facebook.com/ffwenns/posts', [
            'query' => [
                'batch' => json_encode($query),
                'access_token' => $this->accessToken,
                'include_headers' => 'false'
            ]
        ]);

        return $response->toArray();
    }

    private function buildBatchQuery(array $data): array
    {
        $attachments = $data['attachments']['data'] ?? [];
        $query = [];

        foreach ($attachments as $attachment) {
            $target = $attachment['target'] ?? null;
            $type = $attachment['type'] ?? null;

            if ($type == 'photo') {
                $query[] = [
                    'method' => 'GET',
                    'relative_url' => $target['id'] . '?fields=images'
                ];

                continue;
            }

            if ($type != 'album') continue;

            $subattachments = $attachment['subattachments']['data'] ?? [];

            foreach ($subattachments as $subattachment) {
                if ($subattachment['type'] == 'photo') {
                    $query[] = [
                        'method' => 'GET',
                        'relative_url' => $subattachment['target']['id'] . '?fields=images'
                    ];
                }
            }
        }

        return $query;
    }
}