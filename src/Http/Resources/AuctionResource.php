<?php

namespace Module\MyProcurement\Http\Resources;

use Module\MyProcurement\Models\MyProcurementAuction;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return MyProcurementAuction::mapResource($request, $this);
    }
}
