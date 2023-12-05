<?php

namespace App\View\Components\Generals;

use Illuminate\View\Component;

class Title extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $content;
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view(
            'components.generals.title',
            [
                'title' => $this->content['title'],
                'subtitle' => $this->content['subtitle'],
                'breadcrumb' => $this->content['breadcrumb'],
            ]
        );
    }
}
