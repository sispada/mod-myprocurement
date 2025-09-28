<?php

namespace Module\MyProcurement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Procurement\Models\ProcurementDoctype;
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

    public function upload(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')) {
            return response()->json([
                'path' => 'xx'
            ], 200);
        }

        return response()->json([
            'status' => 422,
            'message' => 'File tidak ada'
        ], 422);
    }
}
