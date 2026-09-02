<?php

namespace Tests\Unit;

use App\Support\TiptapRenderer;
use PHPUnit\Framework\TestCase;

class TiptapRendererTest extends TestCase
{
    public function test_renders_paragraphs_and_marks(): void
    {
        $doc = ['type' => 'doc', 'content' => [
            ['type' => 'paragraph', 'content' => [
                ['type' => 'text', 'text' => 'Hello '],
                ['type' => 'text', 'text' => 'world', 'marks' => [['type' => 'bold']]],
            ]],
        ]];

        $this->assertSame('<p>Hello <strong>world</strong></p>', TiptapRenderer::render($doc));
    }

    public function test_clamps_heading_levels_to_two_and_three(): void
    {
        $doc = ['type' => 'doc', 'content' => [
            ['type' => 'heading', 'attrs' => ['level' => 1], 'content' => [['type' => 'text', 'text' => 'Title']]],
        ]];

        $this->assertSame('<h2>Title</h2>', TiptapRenderer::render($doc));
    }

    public function test_script_tagged_text_is_escaped_not_executed(): void
    {
        $doc = ['type' => 'doc', 'content' => [
            ['type' => 'paragraph', 'content' => [
                ['type' => 'text', 'text' => '<script>alert(1)</script>'],
            ]],
        ]];

        $html = TiptapRenderer::render($doc);

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
    }

    public function test_unknown_node_types_are_skipped_safely(): void
    {
        $doc = ['type' => 'doc', 'content' => [
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Before']]],
            ['type' => 'someExoticEmbedNode', 'content' => []],
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'After']]],
        ]];

        $this->assertSame('<p>Before</p><p>After</p>', TiptapRenderer::render($doc));
    }

    public function test_javascript_protocol_links_are_stripped(): void
    {
        $doc = ['type' => 'doc', 'content' => [
            ['type' => 'paragraph', 'content' => [
                ['type' => 'text', 'text' => 'click me', 'marks' => [
                    ['type' => 'link', 'attrs' => ['href' => 'javascript:alert(1)']],
                ]],
            ]],
        ]];

        $html = TiptapRenderer::render($doc);

        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertSame('<p>click me</p>', $html);
    }

    public function test_javascript_protocol_image_src_is_stripped(): void
    {
        $doc = ['type' => 'doc', 'content' => [
            ['type' => 'image', 'attrs' => ['src' => 'javascript:alert(1)', 'alt' => 'x']],
        ]];

        $this->assertSame('', TiptapRenderer::render($doc));
    }

    public function test_safe_https_links_render_with_target_blank(): void
    {
        $doc = ['type' => 'doc', 'content' => [
            ['type' => 'paragraph', 'content' => [
                ['type' => 'text', 'text' => 'go', 'marks' => [
                    ['type' => 'link', 'attrs' => ['href' => 'https://example.com']],
                ]],
            ]],
        ]];

        $this->assertSame(
            '<p><a href="https://example.com" target="_blank" rel="noopener noreferrer">go</a></p>',
            TiptapRenderer::render($doc)
        );
    }

    public function test_has_content_detects_empty_documents(): void
    {
        $this->assertFalse(TiptapRenderer::hasContent(['type' => 'doc', 'content' => []]));
        $this->assertFalse(TiptapRenderer::hasContent(['type' => 'doc', 'content' => [
            ['type' => 'paragraph', 'content' => []],
        ]]));
        $this->assertTrue(TiptapRenderer::hasContent(['type' => 'doc', 'content' => [
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'hi']]],
        ]]));
    }
}
