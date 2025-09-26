<?php

namespace Module\MyProcurement\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\MyProcurement\Models\MyProcurementAuction;
use Module\System\Http\Resources\UserLogActivity;

class AuctionShowResource extends JsonResource
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
            'record' => MyProcurementAuction::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => MyProcurementAuction::mapCombos($request, $this),

                'icon' => MyProcurementAuction::getPageIcon('myprocurement-auction'),

                'key' => MyProcurementAuction::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => MyProcurementAuction::mapStatuses($request, $this),

                'title' => MyProcurementAuction::getPageTitle($request, 'myprocurement-auction'),
            ],
        ];
    }
}
