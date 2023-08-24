<?php

namespace App\Http\Resources;

use App\Models\Location;
use App\Services\AnnouncementServices;
use App\Services\JobServices;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nette\Utils\Random;

class AnnouncementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {   
       
        return [
            'title' => $this->title ?? null,
            'salary' => $this->salary ?? null,
            'work_hours' => $this->work_hours ?? null,
            'job-slug' => $this->slug ?? null,
            'image' =>  $this->image($this->customer->user->phone, $this->title, $this->salary, $this->location_id )?? null,
            "address" => $this->location_id ?? null,
            'links' => $this->for_connection_link ?? null,
            'for_connection' => $this->getForConnection($this->customer->user->phone, $this->for_connection_phone ) ?? null,
            'post_number' => null,
            'company_name' =>  $this->customer->name ?? null,
            'hash_tag' => $this->getHashTab($this->title, $this->location_id) ?? null
        ];
    }

    public function image($company_name, $title, $salary, $location_id)
    {
            if($company_name !== null && $title !== null && $salary !== null && $location_id !==null){
                $postNumber = Random::generate('4', '0-9');
                $imageUrl =  JobServices::getInstance()->createJobBanner($company_name, $title, $salary,  $location_id , $postNumber);

                return $imageUrl;
            }
            return null;
            
    }

    public function getHashTab($title, $address)
    {
        $data = AnnouncementServices::getHashTab($title, $address);

        return $data;
    }

    public function getForConnection($customer_phone, $for_connection)
    {

        $num = [$customer_phone]  ?? [];
        if($for_connection !== null && $for_connection !== []){
             $data = array_merge($num , $for_connection);
             return $data;
        }

        return $num ;
    }

    

    
}
