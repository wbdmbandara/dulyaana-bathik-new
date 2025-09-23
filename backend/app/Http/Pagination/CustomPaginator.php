<?php

namespace App\Http\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

class CustomPaginator extends LengthAwarePaginator
{
    public function render($view = null, $data = [])
    {
        return view('custom-pagination', [
            'paginator' => $this,
            'elements' => $this->elements(),
        ])->render();
    }
    
    public function getCustomInfo()
    {
        return [
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
        ];
    }
}