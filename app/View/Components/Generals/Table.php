<?php

namespace App\View\Components\Generals;

use Illuminate\View\Component;

class Table extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $id;
    public $headers;
    public function __construct(String $id, array $headers)
    {
        $this->id = $id;
        $this->headers = $headers;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.generals.table', [
            'id' => $this->id,
            'headers' => $this->headers
        ]);
    }
}
