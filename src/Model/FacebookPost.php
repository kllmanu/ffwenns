<?php

namespace App\Model;

use Arispati\EmojiRemover\EmojiRemover;

class FacebookPost
{
    public function __construct(private readonly array $data)
    {
    }

    public function parse(): array
    {
        $post['sid'] = $this->parseSid();
        $post['date'] = $this->parseDate();
        $post['title'] = $this->parseTitle();
        $post['text'] = $this->parseText();
        $post['category'] = $this->parseCategory();

        return $post;
    }

    public function parseSid(): string
    {
        return explode('_', $this->data['id'])[1];
    }

    public function parseDate(): int
    {
        return strtotime($this->data['created_time']);
    }

    public function parseTitle(): string
    {
        $pattern_dashes = '/---.*(.*).*---/m';
        $pattern_emojis = '/^\s*(?:[[:^print:]]{6}).{10,200}(?:[[:^print:]]{6})/m';

        if (!array_key_exists('message', $this->data)) {
            return '-';
        }

        // try to parse title from post message
        if (preg_match($pattern_dashes, $this->data['message'], $matches_dashes)) {
            return self::sanitize($matches_dashes[0]);
        }

        if (preg_match($pattern_emojis, $this->data['message'], $matches_emojis)) {
            return self::sanitize($matches_emojis[0]);
        }

        return '-';
    }

    public function parseText(): string
    {
        if (!array_key_exists('message', $this->data)) {
            return '';
        }

        if ($this->parseTitle() !== '-') {
            $text = explode($this->parseTitle(), $this->data['message']);
            $text = array_reduce($text, fn($carry, $item) => strlen($item) > strlen($carry) ? $item : $carry);

            return self::sanitize($text);
        }

        return self::sanitize($this->data['message']);
    }

    public function parseCategory(): string
    {
        if (!array_key_exists('message_tags', $this->data)) {
            return '';
        }

        $categories = array_column($this->data['message_tags'], 'name');

        $einsaetze = '/^#eins.{1,2}tz/i';
        $uebungen = '/^#.{1,2}bung/i';
        $taetigkeiten = '/^#t.{1,2}tigkeit/i';

        if (preg_grep($einsaetze, $categories)) return 'Einsätze';
        if (preg_grep($uebungen, $categories)) return 'Übungen';
        if (preg_grep($taetigkeiten, $categories)) return 'Tätigkeiten';

        return '';
    }

    private static function sanitize(string|null $str): string|null
    {
        if (!$str) {
            return null;
        }

        $content = EmojiRemover::filter($str);
        $content = preg_replace('/#\S+/', '', $content);
        $content = str_replace('---', '', $content);
        $content = trim($content);

        return $content;
    }
}

