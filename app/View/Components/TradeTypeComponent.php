<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TradeTypeComponent extends Component
{
    public $tradeType;

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->tradeType = [
            "spot" => "spot",
            "future" => "future",
        ];

        return view('components.trade-type-component');
    }
}
