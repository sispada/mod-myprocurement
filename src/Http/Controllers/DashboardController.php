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
            $userpath = $request->user()->userable->slug . DIRECTORY_SEPARATOR;
            $filename = $request->slug . DIRECTORY_SEPARATOR . $request->uuid . $request->extension;
            $filepath = $userpath . $filename;

            if (Storage::disk('uploads')->put($filepath, $request->file('file'))) {
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
}
