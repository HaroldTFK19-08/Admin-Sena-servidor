<?php

namespace App\Modules\Usuarios\Controllers\Api;

use App\Modules\Usuarios\Models\Admin;
use App\Modules\Usuarios\Models\Aspirant;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * CRUD genérico de perfiles para administradores y aspirantes.
 * Aprendices e instructores tienen su propio controlador
 * (ApprenticeApiController y TeacherApiController).
 */
class RoleProfileApiController extends ApiController
{
    public function index(Request $request)
    {
        $this->requireAdmin();
        $config = $this->profileConfig($request);
        $query = $config['model']::query()->with($config['relations']);

        if ($request->filled('buscar')) {
            $buscar = trim((string) $request->input('buscar'));
            $query->whereHas('user', function (Builder $users) use ($buscar) {
                $users->where('nombre_1', 'like', "%{$buscar}%")
                    ->orWhere('apellido_1', 'like', "%{$buscar}%")
                    ->orWhere('identificacion', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        return response()->json($query->orderByDesc('id')->get());
    }

    public function store(Request $request)
    {
        $this->requireAdmin();
        $config = $this->profileConfig($request);
        $data = $request->validate($this->rules($config));
        $data = $this->storeFiles($request, $data, $config);
        $profile = $config['model']::create($data);

        return response()->json($profile->load($config['relations']));
    }

    public function show(Request $request, int $profileId)
    {
        $this->requireAdmin();
        $config = $this->profileConfig($request);

        return response()->json($config['model']::with($config['relations'])->findOrFail($profileId));
    }

    public function update(Request $request, int $profileId)
    {
        $this->requireAdmin();
        $config = $this->profileConfig($request);
        $profile = $config['model']::findOrFail($profileId);
        $data = $request->validate($this->rules($config, $profileId));
        $data = $this->storeFiles($request, $data, $config, $profile);
        $profile->update($data);

        return response()->json($profile->fresh($config['relations']));
    }

    public function destroy(Request $request, int $profileId)
    {
        $this->requireAdmin();
        $config = $this->profileConfig($request);
        $profile = $config['model']::findOrFail($profileId);
        $profile->delete();

        return response()->json($profile);
    }

    private function profileConfig(Request $request): array
    {
        return match ($request->route('profileType')) {
            'admins' => [
                'model' => Admin::class,
                'table' => 'admins',
                'role' => 'Admin',
                'label' => 'Administradores',
                'label_singular' => 'Administrador',
                'relations' => ['user'],
                'rules' => [
                    'cargo' => ['required', 'string', 'max:255'],
                    'ubicacion_oficina' => ['required', 'string', 'max:255'],
                    'direccion' => ['nullable', 'string', 'max:255'],
                ],
            ],
            'aspirantes' => [
                'model' => Aspirant::class,
                'table' => 'aspirants',
                'role' => 'Aspirante',
                'label' => 'Aspirantes',
                'label_singular' => 'Aspirante',
                'relations' => ['user'],
                'rules' => [
                    'eps' => ['required', Rule::in(['SURA', 'Sanitas', 'Nueva EPS', 'Salud Total', 'Compensar', 'Coosalud', 'Emssanar'])],
                    'certificado_eps' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:4096'],
                    'certificado_bachiller' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:4096'],
                    'certificado_icfes' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:4096'],
                ],
            ],
            default => abort(404),
        };
    }

    private function rules(array $config, ?int $profileId = null): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('rol', $config['role']),
                Rule::unique($config['table'], 'user_id')->ignore($profileId),
            ],
            ...$config['rules'],
        ];
    }

    private function storeFiles(Request $request, array $data, array $config, ?Model $profile = null): array
    {
        foreach ($config['rules'] as $field => $_) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $data[$field] = $request->file($field)->store("certificates/{$config['table']}", 'public');
        }

        if ($profile && $config['table'] === 'aspirants') {
            foreach (['certificado_eps', 'certificado_bachiller', 'certificado_icfes'] as $field) {
                if (! $request->hasFile($field)) {
                    unset($data[$field]);
                }
            }
        }

        return $data;
    }
}
