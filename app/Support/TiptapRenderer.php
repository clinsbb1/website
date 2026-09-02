<?php

namespace App\Support;

/**
 * Renders a Tiptap document (as decoded JSON) into safe, whitelisted HTML.
 *
 * This is intentionally NOT a general-purpose renderer: only the node/mark
 * types the admin editor's toolbar can actually produce are supported.
 * Anything else — an unrecognised node, an unsafe link/image protocol, raw
 * HTML smuggled into a text node — is stripped or escaped rather than
 * trusted. The stored JSON is treated as structured input, never as HTML.
 */
class TiptapRenderer
{
    private const ALLOWED_URL_SCHEMES = ['http', 'https', 'mailto'];

    /**
     * @param  array<string, mixed>|null  $document
     */
    public static function render(?array $document): string
    {
        if (! $document || ($document['type'] ?? null) !== 'doc') {
            return '';
        }

        return static::renderNodes($document['content'] ?? []);
    }

    /**
     * Plain-text extraction, used for excerpts, reading time, and search-style previews.
     *
     * @param  array<string, mixed>|null  $document
     */
    public static function toPlainText(?array $document): string
    {
        if (! $document) {
            return '';
        }

        $text = '';
        $walk = function (array $node) use (&$walk, &$text): void {
            if (($node['type'] ?? null) === 'text') {
                $text .= $node['text'] ?? '';

                return;
            }
            foreach ($node['content'] ?? [] as $child) {
                $walk($child);
            }
            if (in_array($node['type'] ?? null, ['paragraph', 'heading', 'listItem'], true)) {
                $text .= ' ';
            }
        };

        foreach ($document['content'] ?? [] as $node) {
            $walk($node);
        }

        return trim(preg_replace('/\s+/', ' ', $text));
    }

    /**
     * Whether the document contains any non-empty text content.
     *
     * @param  array<string, mixed>|null  $document
     */
    public static function hasContent(?array $document): bool
    {
        return static::toPlainText($document) !== '';
    }

    /**
     * @param  array<int, array<string, mixed>>  $nodes
     */
    private static function renderNodes(array $nodes): string
    {
        $html = '';
        foreach ($nodes as $node) {
            $html .= static::renderNode($node);
        }

        return $html;
    }

    /**
     * @param  array<string, mixed>  $node
     */
    private static function renderNode(array $node): string
    {
        $type = $node['type'] ?? null;
        $content = fn () => static::renderNodes($node['content'] ?? []);

        return match ($type) {
            'paragraph' => '<p>'.$content().'</p>',
            'heading' => static::renderHeading($node, $content()),
            'text' => static::renderText($node),
            'blockquote' => '<blockquote>'.$content().'</blockquote>',
            'bulletList' => '<ul>'.$content().'</ul>',
            'orderedList' => '<ol>'.$content().'</ol>',
            'listItem' => '<li>'.$content().'</li>',
            'codeBlock' => '<pre><code>'.static::renderCodeBlockText($node).'</code></pre>',
            'image' => static::renderImage($node),
            'horizontalRule' => '<hr>',
            'hardBreak' => '<br>',
            default => '', // unknown/unsupported node types fail safe: silently skipped
        };
    }

    /**
     * @param  array<string, mixed>  $node
     */
    private static function renderHeading(array $node, string $inner): string
    {
        $level = (int) ($node['attrs']['level'] ?? 2);
        $level = in_array($level, [2, 3], true) ? $level : 2; // article title is the page H1

        return "<h{$level}>{$inner}</h{$level}>";
    }

    /**
     * @param  array<string, mixed>  $node
     */
    private static function renderText(array $node): string
    {
        $text = e($node['text'] ?? '');

        foreach ($node['marks'] ?? [] as $mark) {
            $text = static::wrapMark($mark, $text);
        }

        return $text;
    }

    /**
     * @param  array<string, mixed>  $mark
     */
    private static function wrapMark(array $mark, string $text): string
    {
        return match ($mark['type'] ?? null) {
            'bold' => "<strong>{$text}</strong>",
            'italic' => "<em>{$text}</em>",
            'code' => "<code>{$text}</code>",
            'link' => static::wrapLink($mark, $text),
            default => $text, // unsupported marks are dropped, text is kept
        };
    }

    /**
     * @param  array<string, mixed>  $mark
     */
    private static function wrapLink(array $mark, string $text): string
    {
        $href = static::sanitizeUrl($mark['attrs']['href'] ?? null);
        if (! $href) {
            return $text;
        }

        $href = e($href);

        return "<a href=\"{$href}\" target=\"_blank\" rel=\"noopener noreferrer\">{$text}</a>";
    }

    /**
     * @param  array<string, mixed>  $node
     */
    private static function renderImage(array $node): string
    {
        $src = static::sanitizeUrl($node['attrs']['src'] ?? null);
        if (! $src) {
            return '';
        }

        $alt = e($node['attrs']['alt'] ?? '');
        $src = e($src);

        return "<img src=\"{$src}\" alt=\"{$alt}\" loading=\"lazy\">";
    }

    /**
     * @param  array<string, mixed>  $node
     */
    private static function renderCodeBlockText(array $node): string
    {
        $text = '';
        foreach ($node['content'] ?? [] as $child) {
            if (($child['type'] ?? null) === 'text') {
                $text .= $child['text'] ?? '';
            }
        }

        return e($text);
    }

    private static function sanitizeUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $url = trim($url);
        $scheme = parse_url($url, PHP_URL_SCHEME);

        // A scheme-less relative URL (e.g. a locally stored image path) is fine.
        if ($scheme === null) {
            return $url;
        }

        return in_array(strtolower($scheme), self::ALLOWED_URL_SCHEMES, true) ? $url : null;
    }
}
