<?php

namespace App\Modules\Noticias\Controllers\Api;

use App\Modules\Noticias\Models\News;
use App\Modules\Noticias\Requests\NewsApiRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class NewsApiController extends ApiController
{
    public function index(Request $request)
    {
        $noticias = News::with(['trainingcenter', 'admin.user'])->latest()->get();
        return response()->json($noticias);
    }

    public function store(NewsApiRequest $request)
    {
        $user = $this->requireAdmin();
        $data = $request->validated();
        $data['admin_id'] = $this->adminProfileId($user);
        $data['imagen'] = $request->file('imagen')?->store('news', 'public');

        $noticia = News::create($data);

        return response()->json($this->loadRelations($noticia));
    }

    public function show(int $id)
    {
        $noticia = News::with(['trainingcenter', 'admin.user'])->findOrFail($id);
        return response()->json($noticia);
    }

    public function update(NewsApiRequest $request, News $noticia)
    {
        $user = $this->requireAdmin();
        $data = $request->validated();
        $data['admin_id'] = $this->adminProfileId($user);
        $data['imagen'] = $request->file('imagen')?->store('news', 'public') ?? $noticia->imagen;
        $noticia->update($data);

        return response()->json($this->loadRelations($noticia->fresh()));
    }

    public function destroy(News $noticia)
    {
        $this->requireAdmin();
        $noticia->delete();

        return response()->json($noticia);
    }

    private function loadRelations(News $noticia): News
    {
        return $noticia->load(['trainingcenter', 'admin.user']);
    }
}
