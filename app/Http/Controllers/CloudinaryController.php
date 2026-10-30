<?php

namespace App\Http\Controllers;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryController extends Controller
{
    private ?Cloudinary $cloudinary = null;

    /**
     * Cliente de Cloudinary con la configuración del .env.
     */
    protected function cloudinary(): Cloudinary
    {
        if ($this->cloudinary) {
            return $this->cloudinary;
        }

        $config = config('services.cloudinary');
        if (!empty($config['url'])) {
            return $this->cloudinary = new Cloudinary($config['url']);
        }
        abort_if(
            empty($config['cloud_name']) || empty($config['api_key']) || empty($config['api_secret']),
            500,
            'Cloudinary no está configurado. Revisa las variables CLOUDINARY_* del archivo .env.'
        );
        return $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $config['cloud_name'],
                'api_key' => $config['api_key'],
                'api_secret' => $config['api_secret'],
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }
    protected function subirArchivo(
        UploadedFile $archivo,
        string $nombre = '',
        string $carpeta = ''
    ): array {
        $opciones = [
            'folder' => $carpeta,
            'resource_type' => 'image',
        ];
        if (!empty($nombre)) {
            $opciones['public_id'] = $nombre;
        }
        $resultado = $this->cloudinary()
            ->uploadApi()
            ->upload($archivo->getRealPath(), $opciones);
        return [
            'url' => $resultado['secure_url'],
            'public_id' => $resultado['public_id'],
            'resource_type' => $resultado['resource_type'],
        ];
    }
    protected function eliminarArchivo(?string $publicId, string $tipo = 'image'): void
    {
        if (empty($publicId)) {
            return;
        }
        $this->cloudinary()
            ->uploadApi()
            ->destroy($publicId, ['resource_type' => $tipo]);
    }
}
