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

        // Fallback for cases where basic_settings might not be the first record
        if (!$bs) {
            $defaultLang = \App\Models\Language::where('is_default', 1)->first();
            if ($defaultLang) {
                $bs = $defaultLang->basic_setting;
                $be = $defaultLang->basic_extended;
            }
        }

        $data = [
            'bs' => $bs,
            'be' => $be
        ];

        $view->with($data);
    }
}
