<?php

namespace App\Modules\Ofertas\Controllers\Api;

use App\Modules\Ofertas\Models\Offer;
use App\Modules\Ofertas\Requests\OfferRequest;
use App\Http\Controllers\Api\ApiController;
use Throwable;

class OfferApiController extends ApiController
{
    /**
     * Listar ofertas.
     */
    public function index()
    {
        $ofertas = Offer::with('program.area.trainingcenter')
            ->latest()
            ->get();

        return $this->success($ofertas);
    }

    /**
     * Crear oferta.
     */
    public function store(OfferRequest $request)
    {
        $user = $this->requireAdmin();

        $data = $request->validated();

        $data['admin_id'] = $this->adminProfileId($user);

        $archivo = null;

        try {

            if ($request->hasFile('imagen')) {

                $archivo = $this->subirArchivo(
                    $request->file('imagen'),
                    'oferta_' . uniqid(),
                    'adminsena/ofertas'
                );
                $data['imagen'] = $archivo['url'];
                $data['public_id'] = $archivo['public_id'];
            }
            $oferta = Offer::create($data);
            return $this->created(
                $this->loadRelations($oferta),
                'Oferta creada correctamente.'
            );
        } catch (Throwable $e) {
            if ($archivo) {
                try {
                    $this->eliminarArchivo(
                        $archivo['public_id'],
                        $archivo['resource_type']
                    );
                } catch (Throwable $error) {
                    report($error);
                }
            }
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la oferta.',
            ], 500);
        }
    }
    /**
     * Mostrar oferta.
     */
    public function show(int $id)
    {
        $oferta = Offer::with('program.area.trainingcenter')
            ->findOrFail($id);
        $this->authorizeVisibility($oferta);
        return $this->success($this->loadRelations($oferta));
    }
    /**
     * Actualizar oferta.
     */
    public function update(OfferRequest $request, Offer $oferta)
    {
        $user = $this->requireAdmin();
        $data = $request->validated();
        $data['admin_id'] = $this->adminProfileId($user);
        $archivoNuevo = null;
        $publicIdAnterior = $oferta->public_id;
        try {
            if ($request->hasFile('imagen')) {
                $archivoNuevo = $this->subirArchivo(
                    $request->file('imagen'),
                    'oferta_' . uniqid(),
                    'adminsena/ofertas'
                );
                $data['imagen'] = $archivoNuevo['url'];
                $data['public_id'] = $archivoNuevo['public_id'];
            } else {
                unset($data['imagen']);
            }
            $oferta->update($data);
            // Eliminar imagen anterior después de actualizar.
            if ($archivoNuevo && $publicIdAnterior) {
                $this->eliminarArchivo($publicIdAnterior);
            }
            return $this->success(
                $this->loadRelations($oferta->fresh()),
                'Oferta actualizada correctamente.'
            );
        } catch (Throwable $e) {
            // Si falla la actualización, eliminar imagen nueva.
            if ($archivoNuevo) {
                try {
                    $this->eliminarArchivo(
                        $archivoNuevo['public_id'],
                        $archivoNuevo['resource_type']
                    );
                } catch (Throwable $error) {
                    report($error);
                }
            }
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la oferta.',
            ], 500);
        }
    }
    /**
     * Eliminar oferta.
     */
    public function destroy(Offer $oferta)
    {
        $this->requireAdmin();
        $publicId = $oferta->public_id;
        $oferta->delete();
        if ($publicId) {
            try {
                $this->eliminarArchivo($publicId);
            } catch (Throwable $e) {
                report($e);
            }
        }
        return $this->success(
            null,
            'Oferta eliminada correctamente.'
        );
    }
    /**
     * Controlar visibilidad de las ofertas.
     */
    private function authorizeVisibility(Offer $oferta): void
    {
        $user = $this->requireRole([
            'Admin',
            'Instructor',
            'Aprendiz',
            'Aspirante',
        ]);
        abort_if(
            $user && $user->rol !== 'Admin' && !$oferta->publicada,
            404,
            'La oferta no está disponible.'
        );
    }
    /**
     * Cargar relaciones de la oferta.
     */
    private function loadRelations(Offer $oferta): Offer
    {
        return $oferta
            ->load([
                'program.area.trainingcenter',
                'admin.user',
            ])
            ->loadCount([
                'inscriptions as inscripciones_activas_count' => function ($registros) {
                    $registros->where('estado', '!=', 'Cancelada');
                },
            ]);
    }
}
