<?php

namespace App\View\Components\Generals;

use Illuminate\View\Component;

class Modal extends Component
{
    public $id;
    public $title;
    public $buttonAcceptLabel;
    public $buttonCloseLabel;
    public $body;
    public $buttonAcceptId;

    public function __construct(String $id, String $title, $body, String $buttonAcceptId, String $buttonAcceptLabel, String $buttonCloseLabel = "Cerrar")
    {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->buttonAcceptId = $buttonAcceptId;
        $this->buttonAcceptLabel = $buttonAcceptLabel;
        $this->buttonCloseLabel = $buttonCloseLabel;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.generals.modal',[
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'buttonAcceptId' => $this->buttonAcceptId,
            'buttonAcceptLabel' => $this->buttonAcceptLabel,
            'buttonCloseLabel' => $this->buttonCloseLabel
        ]);
    }
}
