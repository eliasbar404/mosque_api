<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class ManageUsersController extends Controller
{
    // Get All Users
    public function get_all_users(){
        $members = Member::all();
        return $members;
    }

    // Get User By ID
    public function get_user($id){
        $member = Member::where('id',$id)->first();
        return $member;
    }

    // Unactive User
    public function unactive_user($id){
        $member = Member::where('id',$id)->first();
        if($member){
            $member->status = 'unactive';
        }
    }

    // Active User
    public function active_user($id){
        $member = Member::where('id',$id)->first();
        if($member){
            $member->status = 'active';
        }
    }
}
