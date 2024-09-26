<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->profile->avatar ?? '',
            'bio' => $this->profile->bio ?? '',
            'website' => $this->profile->website ?? '',
            // Поля для пароля обычно не возвращаются по соображениям безопасности
        ];
    }
}
