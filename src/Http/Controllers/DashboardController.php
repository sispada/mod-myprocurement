<?php

namespace Module\MyProcurement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Module\Procurement\Models\ProcurementDoctype;
use Module\Procurement\Models\ProcurementDocument;
use Module\Procurement\Models\ProcurementType;

class DashboardController extends Controller
{
    /**
     * index function
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request): void
    {
        //
    }

    public function document(Request $request)
    {
        if ($type = ProcurementType::with(['doctypes'])->find((int) $request->type_id)) {
            return $type->doctypes->reduce(function ($carry, $document) {
                array_push($carry, [
                    'id' => $document->id,
                    'name' => $document->name,
                    'slug' => $document->slug,
                    'mime' => $document->mime,
                    'extension' => optional($document)->extension ?: '.pdf',
                    'maxsize' => $document->maxsize,
                    'path' => null
                ]);

                return $carry;
            }, []);
        }

        return [];
    }

    /**
     * upload function
     *
     * @param Request $request
     * @return void
     */
    public function upload(Request $request)
    {
        $document = ProcurementDocument::firstWhere('slug', $request->slug);

        if (! $document) {
            return response()->json([
                'status' => 422,
                'message' => 'Upload file tidak valid'
            ], 422);
        }

        $request->validate([
            'file' => "required|file|max:{$document->maxsize}"
        ]);

        if ($request->hasFile('file') && $request->file('file')) {
            $fileslug = $request->user()->userable->slug . DIRECTORY_SEPARATOR . $request->slug;
            $filename = $request->uuid . $request->extension;
            $filepath = $fileslug . DIRECTORY_SEPARATOR . $filename;

            if (Storage::disk('uploads')->putFileAs($fileslug, $request->file('file'), $filename)) {
                return response()->json([
                    'path' => $filepath
                ], 200);
            }
        }

        return response()->json([
            'status' => 422,
            'message' => 'Upload file bermasalah'
        ], 422);
    }

    /**
     * download function
     *
     * @param Request $request
     * @return void
     */
    public function download(Request $request)
    {
        if (! str($request->path)->contains($request->user()->userable->slug)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        if (!Storage::disk('uploads')->exists($request->path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        return optional(Storage::disk('uploads'))->download($request->path, 'downloaded-file.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="sample.pdf"',
        ]);
    }

    /**
     * destroy function
     *
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        if (! str($request->path)->contains($request->user()->userable->slug)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        if (!Storage::disk('uploads')->exists($request->path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        if (Storage::disk('uploads')->delete($request->path)) {
            return response()->json([
                'success' => true,
                'message' => 'Hapus file dari server berhasil.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Hapus file dari server gagal.'
        ], 500);
    }
}
