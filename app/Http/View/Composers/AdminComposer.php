<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\BasicSetting;
use App\Models\BasicExtended;

class AdminComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $bs = BasicSetting::first();
        $be = BasicExtended::first();

        $data = [
            'bs' => $bs,
            'be' => $be
        ];

        $view->with($data);
    }
}
