<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageService
{
    public function store(UploadedFile $image): array
    {
        if (! $this->cloudinaryConfigured()) {
            return [
                'image_url' => '/storage/'.$image->store('products', 'public'),
                'image_public_id' => null,
            ];
        }

        $publicId = 'coffeeshop/products/'.Str::uuid();
        $timestamp = time();
        $parameters = ['public_id' => $publicId, 'timestamp' => $timestamp];

        $response = Http::timeout(30)
            ->attach('file', $image->get(), $image->getClientOriginalName())
            ->post($this->cloudinaryUrl('upload'), [
                ...$parameters,
                'api_key' => config('services.cloudinary.api_key'),
                'signature' => $this->signature($parameters),
            ])
            ->throw()
            ->json();

        return [
            'image_url' => $response['secure_url'],
            'image_public_id' => $response['public_id'],
        ];
    }

    public function delete(?string $imageUrl, ?string $publicId = null): void
    {
        if ($publicId && $this->cloudinaryConfigured()) {
            $timestamp = time();
            $parameters = [
                'invalidate' => 'true',
                'public_id' => $publicId,
                'timestamp' => $timestamp,
            ];

            Http::timeout(20)->post($this->cloudinaryUrl('destroy'), [
                ...$parameters,
                'api_key' => config('services.cloudinary.api_key'),
                'signature' => $this->signature($parameters),
            ])->throw();

            return;
        }

        if ($imageUrl && str_starts_with($imageUrl, '/storage/')) {
            Storage::disk('public')->delete(substr($imageUrl, strlen('/storage/')));
        }
    }

    private function cloudinaryConfigured(): bool
    {
        return filled(config('services.cloudinary.cloud_name'))
            && filled(config('services.cloudinary.api_key'))
            && filled(config('services.cloudinary.api_secret'));
    }

    private function cloudinaryUrl(string $action): string
    {
        return 'https://api.cloudinary.com/v1_1/'
            .config('services.cloudinary.cloud_name')
            .'/image/'.$action;
    }

    private function signature(array $parameters): string
    {
        ksort($parameters);
        $payload = collect($parameters)
            ->map(fn ($value, $key) => $key.'='.$value)
            ->implode('&');

        return sha1($payload.config('services.cloudinary.api_secret'));
    }
}
