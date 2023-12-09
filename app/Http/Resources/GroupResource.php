<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class GroupResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'status' => $this->status,
            'role' => $this->role,
            'thumbnail_url' => 'https://picsum.photos/100',
//            'thumbnail_path' => $this->thumbnail_path,
            'auto_approval' => $this->auto_approval,
            'about' => $this->about,
            'description' => Str::words($this->about, 10),
            'user_id' => $this->user_id,
//            'deleted_at' => $this->deleted_at,
//            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
