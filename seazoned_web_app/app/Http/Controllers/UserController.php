<?php

namespace App\Http\Controllers;

use App\AddressBook;
use App\BookService;
use App\Landscaper;
use App\Service;
use App\UserDetail;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use File;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        //Profile Image
        $profile = UserDetail::where("user_id", "=", session("user_id"));
        $old_image = $profile->get()[0]->profile_image;
        $profile = $profile->first();

        if (Input::hasFile('profile_image') && Input::file('profile_image')->isValid()) {
            $avatar = $request->file("profile_image");
            $fileName = rand(1111, 9999) . "_" . time() . "_" . $avatar->getClientOriginalName();
            $avatar->move("public/uploads/profile_picture/", $fileName);
            $profile->profile_image = $fileName;
            $profile->save();
            if ($old_image != "" && File::exists("uploads/profile_picture/" . $old_image)) {
                File::delete("uploads/profile_picture/" . $old_image);
            }
            session(["profile_image" => url("uploads/profile_picture/" . $fileName), "prof_img" => $fileName]);
        }

        return redirect()->route("user-my-profile");
    }
}