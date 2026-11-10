<?php

namespace App\Services;

use App\Exceptions\CatalogoNftException;
use App\Interfaces\CatalogoNftInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class MonoverseCatalogoNftService implements CatalogoNftInterface
{
    public function consultar(int $pagina): array
    {
        try {
            $respuesta = Http::acceptJson()
                ->connectTimeout(3)
                ->timeout(8)
                ->get(rtrim((string) config('services.monoverse.base_url'), '/').'/api/v1/nfts', [
                    'page' => $pagina,
                ]);
        } catch (ConnectionException $excepcion) {
            throw new CatalogoNftException('Monoverse no disponible.', previous: $excepcion);
        }

        if (! $respuesta->successful()) {
            throw new CatalogoNftException('Respuesta HTTP de Monoverse no exitosa.');
        }

        $contenido = $respuesta->json();
        $estructura = json_decode($respuesta->body());

        if (! is_object($estructura) || ! is_array($estructura->data ?? null)
            || ! is_object($estructura->meta ?? null) || ! is_array($contenido) || Validator::make($contenido, [
                'data' => ['present', 'array'],
                'data.*' => ['array'],
                'data.*.id' => ['required', 'integer', 'min:1'],
                'data.*.name' => ['nullable', 'string'],
                'data.*.description' => ['nullable', 'string'],
                'data.*.collection' => ['nullable', 'string'],
                'data.*.price' => ['nullable', 'numeric', 'min:0'],
                'data.*.currency' => ['nullable', 'string'],
                'data.*.seller' => ['nullable', 'string'],
                'data.*.image_url' => ['nullable', 'string'],
                'data.*.detail_url' => ['nullable', 'string'],
                'meta' => ['required', 'array'],
                'meta.current_page' => ['required', 'integer', 'min:1', 'max:1000000'],
                'meta.per_page' => ['required', 'integer', 'min:1'],
                'meta.total' => ['required', 'integer', 'min:0'],
                'meta.last_page' => ['required', 'integer', 'min:1'],
            ])->fails() || ! array_is_list($contenido['data'])) {
            throw new CatalogoNftException('Estructura de Monoverse inválida.');
        }

        // Solo transferimos campos del contrato; Blade escapará los textos externos.
        $nfts = array_map(function (array $nft): array {
            return [
                'id' => (int) $nft['id'],
                'name' => $nft['name'] ?? null,
                'description' => $nft['description'] ?? null,
                'collection' => $nft['collection'] ?? null,
                'price' => isset($nft['price']) ? (string) $nft['price'] : null,
                'currency' => $nft['currency'] ?? null,
                'seller' => $nft['seller'] ?? null,
                'image_url' => $this->urlPublica($nft['image_url'] ?? null),
                'detail_url' => $this->urlPublica($nft['detail_url'] ?? null),
            ];
        }, $contenido['data']);

        return [
            'data' => $nfts,
            'meta' => [
                'current_page' => (int) $contenido['meta']['current_page'],
                'per_page' => (int) $contenido['meta']['per_page'],
                'total' => (int) $contenido['meta']['total'],
                'last_page' => (int) $contenido['meta']['last_page'],
            ],
        ];
    }

    private function urlPublica(?string $url): ?string
    {
        return filter_var($url, FILTER_VALIDATE_URL)
            && in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true)
                ? $url : null;
    }
}
