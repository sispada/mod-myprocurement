<?php

namespace Module\MyProcurement\Http\Resources;

use Module\MyProcurement\Models\MyProcurementHistory;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HistoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return HistoryResource::collection($this->collection);
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
                'combos' => MyProcurementHistory::mapCombos($request),

                /** the page data filter */
                'filters' => MyProcurementHistory::mapFilters(),

                /** the table header */
                'headers' => MyProcurementHistory::mapHeaders($request),

                /** the page icon */
                'icon' => MyProcurementHistory::getPageIcon('myprocurement-history'),

                /** the record key */
                'key' => MyProcurementHistory::getDataKey(),

                /** the page default */
                'recordBase' => MyProcurementHistory::mapRecordBase($request),

                /** the page statuses */
                'statuses' => MyProcurementHistory::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => MyProcurementHistory::getPageTitle($request, 'myprocurement-history'),

                /** the usetrash flag */
                'usetrash' => MyProcurementHistory::hasSoftDeleted(),
            ]
        ];
    }
}
