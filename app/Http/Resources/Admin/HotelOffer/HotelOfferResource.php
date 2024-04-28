<?php

namespace App\Http\Resources\Admin\HotelOffer;

use App\Http\Resources\Admin\ImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'is_published' => $this->is_published,
            'image' => $this->image,
            'content' => $this->content,
            'image' => new ImageResource($this->image),
        ];
    }
}
