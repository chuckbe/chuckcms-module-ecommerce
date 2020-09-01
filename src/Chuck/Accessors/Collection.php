<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck\Accessors;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;
use Exception;
use Illuminate\Support\Facades\Schema;
use Auth;

use App\Http\Requests;

class Collection
{
    private $collectionRepository;

    public function __construct(CollectionRepository $collectionRepository) 
    {
        $this->collectionRepository = $collectionRepository;
    }

    public function all()
    {
        return $this->collectionRepository->get();
    }

    public function parents()
    {
        return $this->collectionRepository->parents();
    }

    public function children($collection)
    {
        return $this->collectionRepository->children($collection);
    }

    public function hasChildren($collection)
    {
        return $this->collectionRepository->hasChildren($collection);
    }
}