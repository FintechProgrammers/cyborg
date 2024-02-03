<?php

namespace App\View\Components;

use App\Models\Exchange;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ExchangeComponent extends Component
{

    public $exchange;

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->exchange = Exchange::get();

        return view('components.exchange-component');
    }
}
