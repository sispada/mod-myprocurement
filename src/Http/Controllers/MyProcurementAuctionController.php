<?php

namespace Module\MyProcurement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\MyProcurement\Models\MyProcurementAuction;
use Module\MyProcurement\Http\Resources\AuctionCollection;
use Module\MyProcurement\Http\Resources\AuctionShowResource;

class MyProcurementAuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', MyProcurementAuction::class);

        return new AuctionCollection(
            MyProcurementAuction::applyMode($request->mode)
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
        Gate::authorize('create', MyProcurementAuction::class);

        $request->validate([]);

        return MyProcurementAuction::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\MyProcurement\Models\MyProcurementAuction $myProcurementAuction
     * @return \Illuminate\Http\Response
     */
    public function show(MyProcurementAuction $myProcurementAuction)
    {
        Gate::authorize('show', $myProcurementAuction);

        return new AuctionShowResource($myProcurementAuction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\MyProcurement\Models\MyProcurementAuction $myProcurementAuction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MyProcurementAuction $myProcurementAuction)
    {
        Gate::authorize('update', $myProcurementAuction);

        $request->validate([]);

        return MyProcurementAuction::updateRecord($request, $myProcurementAuction);
    }

    /**
     * submitted function
     *
     * @param Request $request
     * @param MyProcurementAuction $myProcurementAuction
     * @return \Illuminate\Http\Response
     */
    public function submitted(Request $request, MyProcurementAuction $myProcurementAuction)
    {
        Gate::authorize('submitted', $myProcurementAuction);

        $request->validate([]);

        return MyProcurementAuction::submittedRecord($request, $myProcurementAuction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\MyProcurement\Models\MyProcurementAuction $myProcurementAuction
     * @return \Illuminate\Http\Response
     */
    public function destroy(MyProcurementAuction $myProcurementAuction)
    {
        Gate::authorize('delete', $myProcurementAuction);

        return MyProcurementAuction::deleteRecord($myProcurementAuction);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\MyProcurement\Models\MyProcurementAuction $myProcurementAuction
     * @return \Illuminate\Http\Response
     */
    public function restore(MyProcurementAuction $myProcurementAuction)
    {
        Gate::authorize('restore', $myProcurementAuction);

        return MyProcurementAuction::restoreRecord($myProcurementAuction);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\MyProcurement\Models\MyProcurementAuction $myProcurementAuction
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(MyProcurementAuction $myProcurementAuction)
    {
        Gate::authorize('destroy', $myProcurementAuction);

        return MyProcurementAuction::destroyRecord($myProcurementAuction);
    }
}
