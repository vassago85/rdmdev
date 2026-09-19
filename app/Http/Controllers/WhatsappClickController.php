<?php

namespace App\Http\Controllers;

use App\Models\WhatsappClick;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class WhatsappClickController extends Controller
{
    public function store(Request $request): Response
    {
        $placement = Str::slug((string) $request->input('placement', 'unknown'), '_');
        if (! array_key_exists($placement, WhatsappClick::PLACEMENTS)) {
            $placement = 'unknown';
        }

        $rawPath = (string) $request->input('path', '');
        $path = preg_match('#^https?://#i', $rawPath)
            ? null
            : parse_url($rawPath, PHP_URL_PATH);
        $path = is_string($path) && str_starts_with($path, '/')
            ? Str::limit($path, 255, '')
            : null;

        WhatsappClick::create([
            'placement'  => $placement,
            'path'       => $path ?: null,
            'created_at' => now(),
        ]);

        return response()->noContent();
    }
}