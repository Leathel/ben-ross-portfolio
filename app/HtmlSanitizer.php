<?php

namespace App;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

class HtmlSanitizer
{
    /** @var array<int, string> */
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u',
        'h2', 'h3', 'ul', 'ol', 'li', 'blockquote', 'code', 'pre', 'a', 'img',
    ];

    /**
     * Reduce rich text to a safe, predictable subset of HTML.
     */
    public function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return null;
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previousSetting = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8"><html><body>'.$html.'</body></html>');
        libxml_clear_errors();
        libxml_use_internal_errors($previousSetting);

        $body = $document->getElementsByTagName('body')->item(0);

        if (! $body) {
            return null;
        }

        $cleaned = '';

        foreach ($body->childNodes as $node) {
            $cleaned .= $this->cleanNode($node);
        }

        $cleaned = trim($cleaned);

        return $cleaned === '' ? null : $cleaned;
    }

    /**
     * Clean a single DOM node recursively.
     */
    private function cleanNode(DOMNode $node): string
    {
        if ($node instanceof DOMText) {
            return e($node->nodeValue ?? '');
        }

        if (! $node instanceof DOMElement) {
            return '';
        }

        $tag = strtolower($node->tagName);
        $inner = '';

        foreach ($node->childNodes as $child) {
            $inner .= $this->cleanNode($child);
        }

        if ($tag === 'br') {
            return '<br>';
        }

        if ($tag === 'img') {
            $src = trim($node->getAttribute('src'));

            if (! $this->isSafeImageUrl($src)) {
                return '';
            }

            $attributes = ' src="'.e($src).'"';
            $alt = trim($node->getAttribute('alt'));

            if ($alt !== '') {
                $attributes .= ' alt="'.e($alt).'"';
            }

            return "<img{$attributes}>";
        }

        if (! in_array($tag, self::ALLOWED_TAGS, true)) {
            return $inner;
        }

        $attributes = '';

        if ($tag === 'a') {
            $href = trim($node->getAttribute('href'));

            if ($this->isSafeUrl($href)) {
                $attributes = ' href="'.e($href).'" target="_blank" rel="noopener noreferrer"';
            }
        }

        return "<{$tag}{$attributes}>{$inner}</{$tag}>";
    }

    /**
     * Determine whether a link target is safe to render.
     */
    private function isSafeUrl(string $url): bool
    {
        if ($url === '' || preg_match('/[\x00-\x1F\x7F]/', $url)) {
            return false;
        }

        $lower = strtolower($url);

        if (str_starts_with($lower, 'http://') || str_starts_with($lower, 'https://') || str_starts_with($lower, 'mailto:')) {
            return true;
        }

        return str_starts_with($url, '/') && ! str_starts_with($url, '//');
    }

    /**
     * Determine whether an image source is safe to render.
     */
    private function isSafeImageUrl(string $url): bool
    {
        if ($url === '' || preg_match('/[\x00-\x1F\x7F]/', $url)) {
            return false;
        }

        $lower = strtolower($url);

        if (str_starts_with($lower, 'http://') || str_starts_with($lower, 'https://')) {
            return true;
        }

        return str_starts_with($url, '/') && ! str_starts_with($url, '//');
    }
}
