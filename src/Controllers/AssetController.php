<?php

namespace Foryoufeng\Generator\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class AssetController
{
    public function __invoke($path): \Illuminate\Http\Response
    {
        $basePath = __DIR__ . '/../../resources/assets/';
        $fullPath = realpath($basePath . $path);
        // 防止目录穿越攻击
        if (!$fullPath || !str_starts_with($fullPath, realpath($basePath))) {
            abort(403, 'Invalid asset path.');
        }

        if (!file_exists($fullPath)) {
            abort(404, 'Asset not found.');
        }

        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);

        $mimeTypes = [
            'css'   => 'text/css',
            'js'    => 'application/javascript',
            'woff2' => 'font/woff2',
            'woff'  => 'font/woff',
            'ttf'   => 'font/ttf',
            'svg'   => 'image/svg+xml',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'gif'   => 'image/gif',
        ];

        $mime = $mimeTypes[$extension] ?? 'application/octet-stream';

        return Response::make(File::get($fullPath), 200, [
            'Content-Type' => $mime,
        ]);
    }
}
