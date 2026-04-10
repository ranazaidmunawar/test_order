<?php

namespace App\Http\Controllers\Client;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\BasicSetting;
use App\Models\User\BasicExtra;
use App\Models\User\PageHeading;
use App\Models\User\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use App\Traits\UserCurrentLanguageTrait;
use App\Models\User\Menu;
use App\Models\User\Social;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Language;


class OrderController extends Controller
{
    use UserCurrentLanguageTrait;
    public function __construct(){}

    public function index()
    {
        $user = getUser();
        $orders = ProductOrder::query()
            ->where('customer_id',Auth::guard('client')->user()->id)
            ->where('user_id',$user->id)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $currentLang = $this->getUserCurrentLanguage($user);
        $bs = BasicSetting::query()
            ->where('user_id', $user->id)
            ->where('language_id', $currentLang->id)
            ->first();
        $be = \App\Models\User\BasicExtended::query()
            ->where('user_id', $user->id)
            ->where('language_id', $currentLang->id)
            ->first();

        $data['orders'] = $orders;
        $data['user'] = $user;
        $data['bs'] = $bs;
        $data['be'] = $be;
        $data['userBs'] = $bs;
        $data['userBe'] = $be;
        $data['rtl'] = $currentLang->rtl;
        $data['currentLang'] = $currentLang;
        $data['userCurrentLang'] = $currentLang;
        $data['keywords'] = json_decode($currentLang->keywords, true);
        $data['activeTheme'] = $bs->theme;
        $data['allLanguageInfos'] = Language::query()
            ->where('user_id', $user->id)
            ->get();
        $userMenu = Menu::query()
            ->where('user_id', $user->id)
            ->where('language_id', $currentLang->id)
            ->first();
        $data['userMenus'] = $userMenu ? $userMenu->menus : [];
        $data['socialMediaInfos'] = Social::query()
            ->where('user_id', $user->id)
            ->get();
        $data['packagePermissions'] = json_decode(UserPermissionHelper::packagePermission($user->id), true);
        $data['userBex'] = BasicExtra::query()
            ->where('user_id', $user->id)
            ->first();
        $data['upageHeading'] = PageHeading::query()
            ->where('user_id', $user->id)
            ->where('language_id', $currentLang->id)
            ->first();

        return view('user-front.client.order', $data);
    }

    public function orderDetails($domain, $id)
    {
        $user = getUser();
        $order = ProductOrder::query()
            ->where('customer_id', Auth::guard('client')->user()->id)
            ->where('user_id', $user->id)->with('orderItems')
            ->find($id);

        if ($order && !empty($order->customer_id)) {
            if (Auth::guard('client')->check()
                && Auth::guard('client')->user()->id != $order->customer_id) {
                return back();
            }
        }

        if ($order && Auth::guard('client')->user()->can('viewFront', $order)) {
            $user = getUser();
            $currentLang = $this->getUserCurrentLanguage($user);
            
            // Pass $order as 'data' to match existing 600+ lines of view code
            $data['data'] = $order;
            $data['order'] = $order; 
            
            $data['bs'] = BasicSetting::query()
                ->where('user_id', $user->id)
                ->where('language_id', $currentLang->id)
                ->first();
            $data['be'] = \App\Models\User\BasicExtended::query()
                ->where('user_id', $user->id)
                ->where('language_id', $currentLang->id)
                ->first();
            $data['rtl'] = $currentLang->rtl;
            $data['currentLang'] = $currentLang;
            $data['userCurrentLang'] = $currentLang;
            $data['keywords'] = json_decode($currentLang->keywords, true);
            $data['activeTheme'] = $data['bs']->theme ?? null;
            $data['allLanguageInfos'] = Language::query()
                ->where('user_id', $user->id)
                ->get();
            $userMenu = Menu::query()
                ->where('user_id', $user->id)
                ->where('language_id', $currentLang->id)
                ->first();
            $data['userMenus'] = $userMenu ? $userMenu->menus : [];
            $data['socialMediaInfos'] = Social::query()
                ->where('user_id', $user->id)
                ->get();
            $data['packagePermissions'] = json_decode(UserPermissionHelper::packagePermission($user->id), true);
            $data['userBex'] = BasicExtra::query()
                ->where('user_id', $user->id)
                ->first();
            $data['upageHeading'] = PageHeading::query()
                ->where('user_id', $user->id)
                ->where('language_id', $currentLang->id)
                ->first();

            return view('user-front.client.order_details', $data);
        }

        return abort(404);
    }
    public function downloadFile($domain, Request $request){
        $user = getUser();
        $bs = BasicSetting::query()
            ->where('user_id', $user->id)
            ->first();
        try {
            return Uploader::downloadFile(Constant::WEBSITE_PRODUCT_INVOICE, $request->id, $request->id, $bs);
        } catch (FileNotFoundException $e) {
            Session::flash('error', 'Sorry, this file does not exist anymore!');
            return redirect()->back();
        }
    }
}
