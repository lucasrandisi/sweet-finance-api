<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
			'id' => $this->id,
			'stop' => $this->stop,
			'limit' => $this->limit,
			'user' => new UserResource($this->user),
			'stock' => new StockResource($this->stock),
			'action' => $this->action,
			'amount' => $this->amount,
			'state' => $this->state,
			'price_at_create_time' => $this->price_at_create_time,
			'created_at' => $this->created_at
		];
    }
}
