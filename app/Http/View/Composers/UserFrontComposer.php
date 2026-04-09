<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\User\Language;
use App\Models\User\BasicSetting;
use App\Models\User\BasicExtended;
use App\Models\User\BasicExtra;
use App\Models\User\Menu;
use App\Models\User\Social;
use App\Models\User\PageHeading;
use App\Models\User\Popup;
use App\Http\Helpers\UserPermissionHelper;
use App\Traits\UserCurrentLanguageTrait;

class UserFrontComposer
{
    use UserCurrentLanguageTrait;

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = getUser();
        if (!$user) {
            return;
        }

        $currentLang = $this->getUserCurrentLanguage($user);
        if (!$currentLang) {
            return;
        }

        $bs = BasicSetting::query()
            ->where('user_id', $user->id)
            ->where('language_id', $currentLang->id)
            ->first();

        $be = BasicExtended::query()
            ->where('user_id', $user->id)
            ->where('language_id', $currentLang->id)
            ->first();

        $allLanguageInfos = Language::query()
            ->where('user_id', $user->id)
            ->get();

        $userMenu = Menu::query()
            ->where('user_id', $user->id)
            ->where('language_id', $currentLang->id)
            ->first();

        $packagePermissions = json_decode(UserPermissionHelper::packagePermission($user->id), true);

        $data = [
            'user' => $user,
            'bs' => $bs,
            'be' => $be,
            'userBs' => $bs,
            'userBe' => $be,
            'rtl' => $currentLang->rtl,
            'currentLang' => $currentLang,
            'userCurrentLang' => $currentLang,
            'keywords' => json_decode($currentLang->keywords, true),
            'activeTheme' => $bs?->theme,
            'allLanguageInfos' => $allLanguageInfos,
            'langs' => $allLanguageInfos,
            'userMenus' => $userMenu ? $userMenu->menus : '[]',
            'socialMediaInfos' => Social::query()
                ->where('user_id', $user->id)
                ->get(),
            'packagePermissions' => $packagePermissions,
            'userBex' => BasicExtra::query()
                ->where('user_id', $user->id)
                ->first(),
            'upageHeading' => PageHeading::query()
                ->where('user_id', $user->id)
                ->where('language_id', $currentLang->id)
                ->first(),
            'apopups' => Popup::query()
                ->where('user_id', $user->id)
                ->where('language_id', $currentLang->id)
                ->where('status', 1)
                ->get()
        ];

        $view->with($data);
    }
}
