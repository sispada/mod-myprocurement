<?php

namespace Module\MyProcurement\Http\Resources;

use Module\MyProcurement\Models\MyProcurementAuction;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuctionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return AuctionResource::collection($this->collection);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function with($request): array
    {
        if ($request->has('initialized')) {
            return [];
        }

        return [
            'setups' => [
                /** the page combo */
                'combos' => MyProcurementAuction::mapCombos($request),

                /** the page data filter */
                'filters' => MyProcurementAuction::mapFilters(),

                /** the table header */
                'headers' => MyProcurementAuction::mapHeaders($request),

                /** the page icon */
                'icon' => MyProcurementAuction::getPageIcon('myprocurement-auction'),

                /** the record key */
                'key' => MyProcurementAuction::getDataKey(),

                /** the page default */
                'recordBase' => MyProcurementAuction::mapRecordBase($request),

                /** the page statuses */
                'statuses' => MyProcurementAuction::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => MyProcurementAuction::getPageTitle($request, 'myprocurement-auction'),

                /** the usetrash flag */
                'usetrash' => MyProcurementAuction::hasSoftDeleted(),
            ]
        ];
    }
}
