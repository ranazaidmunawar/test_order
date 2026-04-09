<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User\Language;
use App\Models\User\BasicSetting;
use App\Models\User\BasicExtended;
use App\Http\Helpers\UserPermissionHelper;
use App\Traits\UserCurrentLanguageTrait;

class UserComposer
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
        $user = getRootUser();
        if (!$user) {
            return;
        }

        $currentLang = $this->getUserCurrentLanguage($user);

        if ($currentLang) {
            $bs = BasicSetting::query()
                ->where('user_id', $user->id)
                ->where('language_id', $currentLang->id)
                ->first();

            $be = BasicExtended::query()
                ->where('user_id', $user->id)
                ->where('language_id', $currentLang->id)
                ->first();

            $packagePermissions = UserPermissionHelper::packagePermission($user->id);
            $packagePermissions = json_decode($packagePermissions, true);

            if (!is_null(Auth::guard('web')->user()->admin_id)) {
                $roleBasedPermission = Auth::guard('web')->user()->role->permissions;
                $permissions = json_decode($roleBasedPermission, true);
            } else {
                $permissions = $packagePermissions;
            }

            $data = [
                'user' => $user,
                'bs' => $bs,
                'be' => $be,
                'userBs' => $bs,
                'userBe' => $be,
                'packagePermissions' => $packagePermissions,
                'permissions' => $permissions,
                'activeTheme' => $bs->theme,
                'default' => $currentLang,
                'user_name' => $user->username,
                'tusername' => $user->username,
                'userLangs' => Language::query()->where('user_id', $user->id)->get(),
                'currentLang' => $currentLang,
                'userCurrentLang' => $currentLang,
            ];

            $view->with($data);
        }
    }
}
