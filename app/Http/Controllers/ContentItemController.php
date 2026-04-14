<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\ContentItem\StoreContentItemRequest;
use App\Http\Requests\contentItem\UpdateContentItemRequest;
use App\Http\Resources\ContentItemResource;
use App\Services\ContentItemService;
use App\Support\PaginationHelper;
use App\Support\TryHttpCatch;
use DragonCode\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Raw;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContentItemController extends Controller
{

    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */

    // protected ContentItemService $_contentItemService;

    public function __construct(private ContentItemService $contentItemService)
    {
        // $this->_contentItemService = $contentItemService;
    }



    //* DONE
    public function indexForUser(Request $request)
    {
        $perPage = $request->input('per_page');
        $user_id = Auth::id();
        $filters = $request->only([
            'search',
            'content_type_id',
            'progress_status_id',
            'tags' // Array de IDs de tags que viene del frontend
        ]);

        return TryHttpCatch::handle(
            function () use ($user_id, $perPage, $filters) {
                $content_items = $this->contentItemService->indexForUser($user_id, $filters, $perPage);


                $result = $perPage > 0 ? [
                    'items' => ContentItemResource::collection($content_items),
                    'meta_data' => PaginationHelper::meta($content_items),
                ] : ContentItemResource::collection($content_items);



                return ApiResponseClass::sendResponse(
                    result: $result,
                    message: 'items loaded successfully',
                    code: Response::HTTP_OK
                );
            }
        );
    }


    //* DONE
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        // Agregamos tag_id a los permitidos
        $filters = $request->only([
            'search',
            'user_id',
            'content_type_id',
            'is_active',
            'progress_status_id',
            'tags'
        ]);

        return TryHttpCatch::handle(
            function () use ($perPage, $filters) {
                $content_items = $this->contentItemService->index($filters, $perPage);

                // Si es paginado, devolvemos meta_data, si no, solo la colección
                $resource = ContentItemResource::collection($content_items);

                $result = $perPage > 0 ? [
                    'items' => $resource,
                    'meta_data' => PaginationHelper::meta($content_items),
                ] : $resource;

                return ApiResponseClass::sendResponse($result, 'Admin items loaded successfully');
            }
        );
    }

    public function store(StoreContentItemRequest $request)
    {

        $user_admin_id = Auth::id();
        $validated = $request->validated();
        $image = $request->file('image');

        return TryHttpCatch::handle(
            function () use ($user_admin_id, $validated) {
                $contentItem = $this->contentItemService->store($user_admin_id, $validated);
                return ApiResponseClass::sendResponse(new ContentItemResource($contentItem), 'items creada exitosamnete', Response::HTTP_CREATED);
            }
        );
    }

    public function storeForUser(StoreContentItemRequest $request)
    {
        $user_id = Auth::id();
        $validated = $request->validated();

        // IMPORTANTE: Obtenemos el archivo directamente
        $image = $request->file('image');

        return TryHttpCatch::handle(
            // Debes pasar $image en el 'use' para que el Servicio la reciba
            function () use ($user_id, $validated, $image) {
                // Pasamos la imagen como tercer argumento
                $contentItem = $this->contentItemService->storeForUser($user_id, $validated, $image);

                return ApiResponseClass::sendResponse(
                    new ContentItemResource($contentItem),
                    'Item creado exitosamente',
                    Response::HTTP_CREATED
                );
            }
        );
    }
    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {

        return TryHttpCatch::handle(
            function () use ($slug) {
                $content_item = $this->contentItemService->show($slug);
                return ApiResponseClass::sendResponse(new ContentItemResource($content_item), 'item loaded successfully', Response::HTTP_OK);
            }
        );
    }


    //*todo SOLO VA NECESITAR EL SLUG PARA LA BUSQUEDA
    public function showForUser(string $user_id, string $slug)
    {
        $user_id = Auth::id();
        return TryHttpCatch::handle(
            function () use ($user_id, $slug) {
                $content_item = $this->contentItemService->showForUser($user_id, $slug);
                return ApiResponseClass::sendResponse(new ContentItemResource($content_item), 'item loaded successfully', Response::HTTP_OK);
            }
        );
    }

    //**
    //** Update the specified resource in storage.
    //** TODO PENDIENTE UPDATEFORUSER
    //* pendiente observadores de eloquent
    public function update(UpdateContentItemRequest $request, string $id)
    {

        $this->authorize('update', $request);
        $validated = $request->validated();


        return TryHttpCatch::handle(
            function () use ($validated, $id) {
                $content_item = $this->contentItemService->update($validated, $id);
                return ApiResponseClass::sendResponse($content_item, 'content item actualizada con exito', Response::HTTP_OK);
            }
        );
    }

    public function updateForUser(UpdateContentItemRequest $request, string $id)
    {

        $user_id = Auth::id();
        $this->authorize('update', $request);
        $validated = $request->validated();


        return TryHttpCatch::handle(
            function () use ($user_id, $validated, $id) {
                $content_item = $this->contentItemService->updateForUser($user_id, $validated, $id);
                return ApiResponseClass::sendResponse($content_item, 'content_item actualizada con exito', Response::HTTP_OK);
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     */

    //** TODO PENDIENTE DELETEFORUSER


    public function destroyForUser(string $id)
    {
        $user_id = Auth::id();

        $content_item = $this->contentItemService->showForUser($user_id, $id);
        $this->authorize('delete', $content_item);

        return TryHttpCatch::handle(
            function () use ($user_id, $id) {
                $this->contentItemService->destroyForUser($user_id, $id);
                return ApiResponseClass::sendResponse(null, 'Content_item eliminada con éxito', Response::HTTP_OK);
            }
        );
    }

    public function destroy(string $id)
    {

        return TryHttpCatch::handle(
            function () use ($id) {
                $this->contentItemService->destroy($id);
                return ApiResponseClass::sendResponse(null, 'Content_item eliminada con éxito', Response::HTTP_OK);
            }
        );
    }
}


// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

// public function store(Request $request)
// {
//     $request->validate([
//         'title' => 'required|string|max:255',
//         'image' => 'nullable|image|max:2048', // Validación de seguridad
//         // ... otras validaciones
//     ]);

//     $imagePath = null;

//     if ($request->hasFile('image')) {
//         // Subimos a Cloudinary en una carpeta específica para tu proyecto StackMyHobbies
//         $upload = $request->file('image')->storeOnCloudinary('stack_my_hobbies');

//         // Guardamos el Public ID (ejemplo: stack_my_hobbies/manga_xyz)
//         $imagePath = $upload->getPublicId();
//     }

//     ContentItem::create([
//         'title' => $request->title,
//         'slug' => Str:>:slug($request->title),
//         'image_path' => $imagePath,
//         'user_id' => auth()->id(),
//         // ... demás campos
//     ]);

//     return redirect()-route('content.index')->with('success', 'Item creado correctamente');
// }
