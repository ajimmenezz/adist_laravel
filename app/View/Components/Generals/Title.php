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
    public $title;
    public $subtitle;
    public $breadcrumb;
    public function __construct(String $title, String $subtitle = '', array $breadcrumb = [])
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->breadcrumb = $breadcrumb;
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
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'breadcrumb' => $this->breadcrumb
            ]
        );
    }
}
