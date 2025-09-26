<?php

namespace Module\MyProcurement\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\MyProcurement\Models\MyProcurementHistory;
use Module\System\Http\Resources\UserLogActivity;

class HistoryShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            /**
             * the record data
             */
            'record' => MyProcurementHistory::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => MyProcurementHistory::mapCombos($request, $this),

                'icon' => MyProcurementHistory::getPageIcon('myprocurement-history'),

                'key' => MyProcurementHistory::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => MyProcurementHistory::mapStatuses($request, $this),

                'title' => MyProcurementHistory::getPageTitle($request, 'myprocurement-history'),
            ],
        ];
    }
}
