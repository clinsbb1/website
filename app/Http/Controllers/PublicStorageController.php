<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

/**
 * Serves uploaded article images from the public disk when the web server
 * can't (no public/storage symlink — it isn't in git, so a fresh deploy
 * doesn't have one). When the symlink does exist, the web server serves the
 * file directly and never reaches this route.
 */
class PublicStorageController extends Controller
{
    public function __invoke(string $path)
    {
        $disk = Storage::disk('public');

        abort_unless(
            preg_match('#^articles/[A-Za-z0-9._/-]+\.(jpe?g|png|webp)$#i', $path)
                && ! str_contains($path, '..')
                && $disk->exists($path),
            404
        );

        // Filenames are random hashes, so a file at a given URL never changes.
        return $disk->response($path, null, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
