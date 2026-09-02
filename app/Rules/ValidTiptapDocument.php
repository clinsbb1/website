<?php

namespace App\Rules;

use App\Support\TiptapRenderer;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates the shape of a Tiptap document submitted as JSON. Whether it
 * must also contain non-empty text (required before publishing) is checked
 * separately in the request, since drafts are allowed to be sparse.
 */
class ValidTiptapDocument implements ValidationRule
{
    public function __construct(private bool $requireContent = false) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $decoded = is_array($value) ? $value : json_decode((string) $value, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            $fail('The article content is not valid.');

            return;
        }

        if (($decoded['type'] ?? null) !== 'doc') {
            $fail('The article content is not a valid document.');

            return;
        }

        if ($this->requireContent && ! TiptapRenderer::hasContent($decoded)) {
            $fail('Add some content before publishing.');
        }
    }
}
