<?php

namespace Module\MyProcurement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\MyProcurement\Models\MyProcurementHistory;
use Module\MyProcurement\Http\Resources\HistoryCollection;
use Module\MyProcurement\Http\Resources\HistoryShowResource;

class MyProcurementHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', MyProcurementHistory::class);

        return new HistoryCollection(
            MyProcurementHistory::forCurrentUser($request->user())
                ->applyMode($request->mode)
                ->filter($request->filters)
                ->search($request->findBy)
                ->sortBy($request->sortBy)
                ->paginate($request->itemsPerPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('create', MyProcurementHistory::class);

        $request->validate([]);

        return MyProcurementHistory::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\MyProcurement\Models\MyProcurementHistory $myProcurementHistory
     * @return \Illuminate\Http\Response
     */
    public function show(MyProcurementHistory $myProcurementHistory)
    {
        Gate::authorize('show', $myProcurementHistory);

        return new HistoryShowResource($myProcurementHistory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\MyProcurement\Models\MyProcurementHistory $myProcurementHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MyProcurementHistory $myProcurementHistory)
    {
        Gate::authorize('update', $myProcurementHistory);

        $request->validate([]);

        return MyProcurementHistory::updateRecord($request, $myProcurementHistory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\MyProcurement\Models\MyProcurementHistory $myProcurementHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MyProcurementHistory $myProcurementHistory)
    {
        Gate::authorize('delete', $myProcurementHistory);

        return MyProcurementHistory::deleteRecord($myProcurementHistory);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\MyProcurement\Models\MyProcurementHistory $myProcurementHistory
     * @return \Illuminate\Http\Response
     */
    public function restore(MyProcurementHistory $myProcurementHistory)
    {
        Gate::authorize('restore', $myProcurementHistory);

        return MyProcurementHistory::restoreRecord($myProcurementHistory);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\MyProcurement\Models\MyProcurementHistory $myProcurementHistory
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(MyProcurementHistory $myProcurementHistory)
    {
        Gate::authorize('destroy', $myProcurementHistory);

        return MyProcurementHistory::destroyRecord($myProcurementHistory);
    }
}
