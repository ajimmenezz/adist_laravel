<?php

namespace App\View\Components\Generals;

use Illuminate\View\Component;

class Drawer extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $id;
    public $title;
    public $content;


    public function __construct($id, $title, $content)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.generals.drawer', [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content
        ]);
    }
}
