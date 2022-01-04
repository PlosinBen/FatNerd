<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Responsable;
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

//    protected $subTitle;

    private array $props = [];

    protected function prop($name, $value): self
    {
        $this->props[$name] = $value;

        return $this;
    }

    protected function subTitle(string $subtitle): self
    {
        return $this->prop('_subTitle', $subtitle);
    }

    protected function view($viewPath, $props = []): Responsable
    {
        return Inertia::render($viewPath,
            $props +
            array_filter($this->props) +
            array_filter(['_title' => $this->title])
        );
    }

    protected function title($title, $subTitle = null): self
    {
        return $this->prop('_title', $title)
            ->prop('_subTitle', $subTitle);
    }

    protected function paginationList(JsonResource $jsonResource)
    {
        return $this->prop('list', $jsonResource);
    }

    protected function showError(string $message, string $redirectTo = null, int $reciprocal = 0): Responsable
    {
        return $this->view('Error', [
            'message' => $message,
            'redirectTo' => $reciprocal,
            'reciprocal' => $reciprocal
        ]);
    }
}
