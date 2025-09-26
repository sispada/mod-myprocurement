<?php

namespace Module\MyProcurement\Models;

use Illuminate\Http\Request;
use Module\System\Traits\HasMeta;
use Illuminate\Support\Facades\DB;
use Module\System\Traits\Filterable;
use Module\System\Traits\Searchable;
use Module\System\Traits\HasPageSetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\Procurement\Models\ProcurementWorkunit;
use Module\Procurement\Models\ProcurementWorkgroup;
use Module\MyProcurement\Http\Resources\AuctionResource;
use Module\Procurement\Models\ProcurementBiodata;
use Module\Procurement\Models\ProcurementMethod;
use Module\Procurement\Models\ProcurementType;

class MyProcurementAuction extends Model
{
    use Filterable;
    use HasMeta;
    use HasPageSetup;
    use Searchable;
    use SoftDeletes;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'platform';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'procurement_auctions';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['myprocurement-auction'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'documents' => 'array',
        'meta' => 'array',
        'reports' => 'array',
    ];

    /**
     * The default key for the order.
     *
     * @var string
     */
    protected $defaultOrder = 'name';

    /**
     * booted function
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope('onlyProcessed', function (Builder $query) {
            $query->whereNotIn('status', ['COMPLETED', 'ABORTED']);
        });
    }

    /**
     * mapCombos function
     *
     * @param Request $request
     * @return array
     */
    public static function mapCombos(Request $request): array
    {
        return [
            'types' => ProcurementType::forCombo('name AS title', 'id AS value', 'min'),
            'methods' => ProcurementMethod::forCombo(),
            'officers' => ProcurementBiodata::where('role', 'PPBJ')->forCombo(),
            'workgroups' => ProcurementWorkgroup::forCombo(),
            'workunits' => optional($request->user()->userable)->workunit_id ? ProcurementWorkunit::where('id', $request->user()->userable->workunit_id)->forCombo() : []
        ];
    }

    /**
     * mapHeaders function
     *
     * readonly value?: SelectItemKey<any>
     * readonly title?: string | undefined
     * readonly align?: 'start' | 'end' | 'center' | undefined
     * readonly width?: string | number | undefined
     * readonly minWidth?: string | undefined
     * readonly maxWidth?: string | undefined
     * readonly nowrap?: boolean | undefined
     * readonly sortable?: boolean | undefined
     *
     * @param Request $request
     * @return array
     */
    public static function mapHeaders(Request $request): array
    {
        return [
            ['title' => 'Name', 'value' => 'name'],
            ['title' => 'Mode', 'value' => 'mode'],
            ['title' => 'Pagu', 'value' => 'ceiling'],
            ['title' => 'Unit Kerja', 'value' => 'workunit_name'],
            ['title' => 'Status', 'value' => 'status', 'sortable' => false, 'width' => '170'],
        ];
    }

    /**
     * mapResource function
     *
     * @param Request $request
     * @return array
     */
    public static function mapResource(Request $request, $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'mode' => $model->mode,
            'type_id' => $model->type_id,
            'method_id' => $model->method_id,
            'month' => $model->month,
            'year' => $model->year,
            'source' => $model->source,
            'ceiling' => 'Rp. ' . number_format($model->ceiling, 0, ',', '.'),
            'officer_id' => $model->officer_id,
            'workunit' => [
                'title' => $model->workunit_name,
                'value' => $model->workunit_id
            ],
            'workunit_name' => $model->workunit_name,
            'status' => $model->status,

            'subtitle' => (string) $model->updated_at,
            'updated_at' => (string) $model->updated_at,
        ];
    }

    /**
     * mapResource function
     *
     * @param Request $request
     * @return array
     */
    public static function mapResourceShow(Request $request, $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'mode' => $model->mode,
            'type_id' => $model->type_id,
            'method_id' => $model->method_id,
            'month' => $model->month,
            'year' => $model->year,
            'source' => $model->source,
            'ceiling' => floatval($model->ceiling),
            'officer_id' => $model->officer_id,
            'workunit' => [
                'title' => $model->workunit_name,
                'value' => $model->workunit_id
            ],
            'workgroup_id' => $model->workgroup_id,
            'workunit_name' => $model->workunit_name,
            'status' => $model->status,
        ];
    }

    /**
     * mapStatuses function
     *
     * @param Request $request
     * @return array
     */
    public static function mapStatuses(Request $request, $model = null): array
    {
        return [
            'canCreate' => $request->user()->hasLicenseAs('myprocurement-ppk'),
            'canEdit' => $request->user()->hasLicenseAs('myprocurement-ppk') && (optional($model)->status === 'DRAFTED' || optional($model)->status === 'REJECTED'),
            'canUpdate' => $request->user()->hasLicenseAs('myprocurement-ppk') && (optional($model)->status === 'DRAFTED' || optional($model)->status === 'REJECTED'),
            'canDelete' => $request->user()->hasLicenseAs('myprocurement-ppk') && optional($model)->status === 'DRAFTED',
            'canRestore' => $request->user()->hasLicenseAs('myprocurement-ppk') && optional($model)->status === 'DRAFTED',
            'canDestroy' => $request->user()->hasLicenseAs('myprocurement-ppk') && optional($model)->status === 'DRAFTED',

            'isPPK' => $request->user()->hasLicenseAs('myprocurement-ppk'),
        ];
    }

    /**
     * scopeForCurrentUser function
     *
     * @param Builder $query
     * @param Model $user
     * @return void
     */
    public function scopeForCurrentUser(Builder $query, Model $user)
    {
        return $query->where('workunit_id', $user->userable->workunit_id);
    }

    /**
     * The model store method
     *
     * @param Request $request
     * @return void
     */
    public static function storeRecord(Request $request)
    {
        $model = new static();

        $workunit = ProcurementWorkunit::find($request->workunit_id);

        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $request->name;
            $model->slug = sha1(str($request->name)->slug());
            $model->mode = $request->mode;
            $model->type_id = $request->type_id;
            $model->method_id = $request->method_id;
            $model->month = $request->month;
            $model->year = $request->year;
            $model->source = $request->source;
            $model->ceiling = $request->ceiling;
            $model->officer_id = $request->officer_id ?: null;
            $model->workunit_id = $request->workunit_id;
            $model->workunit_name = optional($workunit)->name;
            $model->status = 'DRAFTED';
            $model->drafted_by = $request->user()->userable_id;
            $model->save();

            DB::connection($model->connection)->commit();

            return new AuctionResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model update method
     *
     * @param Request $request
     * @param [type] $model
     * @return void
     */
    public static function updateRecord(Request $request, $model)
    {
        $workunit = ProcurementWorkunit::find($request->workunit_id);

        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $request->name;
            $model->mode = $request->mode;
            $model->type = $request->type;
            $model->method = $request->method;
            $model->month = $request->month;
            $model->year = $request->year;
            $model->source = $request->source;
            $model->ceiling = $request->ceiling;
            $model->officer_id = $request->officer_id ?: null;
            $model->workunit_id = $request->workunit_id;
            $model->workunit_name = optional($workunit)->name;
            $model->save();

            DB::connection($model->connection)->commit();

            return new AuctionResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * submittedRecord function
     *
     * @param Request $request
     * @param [type] $model
     * @return void
     */
    public static function submittedRecord(Request $request, $model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->status = 'SUBMITTED';
            $model->submitted_by = $request->user()->userable_id;
            $model->save();

            DB::connection($model->connection)->commit();

            return response()->json([
                'success' => true,
                'message' => 'kirim pengajuan berhasil.'
            ], 200);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model delete method
     *
     * @param [type] $model
     * @return void
     */
    public static function deleteRecord($model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->delete();

            DB::connection($model->connection)->commit();

            return new AuctionResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model restore method
     *
     * @param [type] $model
     * @return void
     */
    public static function restoreRecord($model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->restore();

            DB::connection($model->connection)->commit();

            return new AuctionResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model destroy method
     *
     * @param [type] $model
     * @return void
     */
    public static function destroyRecord($model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->forceDelete();

            DB::connection($model->connection)->commit();

            return new AuctionResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
