<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    /**
     * Handles the storing of a new short URL.
     *
     * Validates the incoming request to ensure the 'original_url' is provided and is a valid URL.
     * Generates a unique short code for the URL and stores it in the database.
     * Returns a JSON response with the short URL and the original URL.
     *
     * @param Request $request the HTTP request object containing the original URL
     *
     * @return \Illuminate\Http\JsonResponse the JSON response containing the short and original URLs
     */
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
        ]);

        $shortCode = Str::random(6);

        // Evita duplicidade
        while (ShortUrl::where('short_code', $shortCode)->exists()) {
            $shortCode = Str::random(6);
        }

        $shortUrl = ShortUrl::create([
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
        ]);

        return response()->json([
            'short_url' => url($shortCode),
            'original_url' => $shortUrl->original_url,
        ]);
    }

    /**
     * Redirects to the original URL based on the given short code.
     *
     * Finds the original URL associated with the provided short code from the database.
     * Throws a 404 error if the short code does not exist.
     * Redirects the user to the original URL.
     *
     * @param string $shortCode the short code used to look up the original URL
     *
     * @return \Illuminate\Http\RedirectResponse a redirect response to the original URL
     */
    public function redirect($shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->firstOrFail();

        return redirect()->away($shortUrl->original_url);
    }
}