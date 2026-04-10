<?php

namespace App\Http\View\Composers;

use App\Models\Language;
use App\Models\Popup;
use Illuminate\View\View;

class FrontComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        if (!$currentLang) {
            $currentLang = Language::first();
        }

        if ($currentLang) {
            $lang_id = $currentLang->id;
            $bs = $currentLang->basic_setting;
            $be = $currentLang->basic_extended;

            $data = [
                'bs' => $bs,
                'be' => $be,
                'rtl' => $currentLang->rtl,
                'langs' => Language::all(),
                'currentLang' => $currentLang,
                'menus' => $currentLang->menus->menus ?? '[]',
                'popups' => Popup::query()->where('language_id', $lang_id)->where('status', 1)->orderBy('serial_number', 'ASC')->get()
            ];

            $view->with($data);
        }
    }
}
