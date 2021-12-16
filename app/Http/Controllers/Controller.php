<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $title;

    private $props = [];

    protected function view($viewPath, $props = [])
    {
        return Inertia::render($viewPath, $props + $this->props);
    }

    protected function prop($name, $value)
    {
        $this->props[$name] = $value;

        return $this;
    }

    protected function paginationList(JsonResource $jsonResource)
    {
        return $this->prop('list', $jsonResource);
    }
}
