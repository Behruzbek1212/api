<?php

namespace App\Services;

use App\Filters\ChatFilter;
use App\Filters\ChatsFilter;
use App\Repository\CandidateRepository;
use App\Repository\ChatsRepository;
use App\Traits\HasScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ChatsServices
{
    use HasScopes;
    
    public $repository;

    public function __construct(ChatsRepository $data)
    {
        $this->repository = $data;
    }

    public static function getInstance(): ChatsServices
    {
        return new static(ChatsRepository::getInctance());
    }
    

    public function list()
    {  
        $data = $this->repository->list();
        $query = $data->with(['resume', 'job'])
                ->where('deleted_at', null)
                ->whereHas('job', function ($query) {
                    return $query->where('deleted_at', null);
                });
        $chatsFilter =  ChatsFilter::apply($query);
        
        return  $chatsFilter;
    }

    public function listCandidate(){
        $data = $this->repository->list();
       
        $query = $data->with(['resume'])
            ->where('deleted_at', null)
            ->orderBy('updated_at', 'desc')
            ->whereHas('job', function ($query) {
                return $query->where('deleted_at', null);
            })
            ->paginate(request()->get('limit') ?? 10);

        return $query;    
    }
    
}
