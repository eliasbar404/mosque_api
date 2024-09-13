<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\SubAdmin;

class ManageUsersController extends Controller
{
    //---- Get All Members ---
    //------------------------
    public function get_all_members(){
        $members = Member::all();
        return $members;
    }

    //---- Get Member By ID ----
    //--------------------------
    public function get_member($id){
        $member = Member::where('id',$id)->first();
        return $member;
    }

    //-- Change Member Status ---
    //---------------------------
    public function member_unactive($id){
        $member = Member::where('id',$id)->first();
        if($member){
            $member->status = 'unactive';
            $member->save();
            return response()->json("unactive member successfully!", 200);
        }
        return response()->json("There is an error", 400);
    }

    public function member_active($id){
        $member = Member::where('id',$id)->first();
        if($member){
            $member->status = 'active';
            $member->save();
            return response()->json("active member successfully!", 200);
        }
        return response()->json("There is an error", 400);
    }

    //-- Delete Member ------
    //-----------------------
    public function delete_member($id){
        $member = Member::where('id',$id)->first();
        if($member){
            $member->delete();
            return response()->json("delete member successfully!", 200);
        }
        return response()->json("There is an error!", 400);
    }





    //----- get All subadmins -----
    //-----------------------------
    public function get_All_subAdmins(){
        $subAdmins = SubAdmin::all();
        return response()->json($subAdmins, 200);
    }

    //---- Get Member By ID ----
    //--------------------------
    public function get_subadmin($id){
        $subAdmin = SubAdmin::where('id',$id)->first();
        return $subAdmin;
    }

    //----- Change subAdmin status --
    //-------------------------------
    public function subAdmin_unactive($id){
        $subAdmin = SubAdmin::where('id',$id)->first();

        $subAdmin->status = 'unactive';
        $subAdmin->save();

        return response()->json("unactive sub Admin successfully!", 200);
    }
    public function subAdmin_active($id){
        $subAdmin = SubAdmin::where('id',$id)->first();

        $subAdmin->status = 'active';
        $subAdmin->save();

        return response()->json("unactive sub Admin successfully!", 200);
    }

    //----- Delete sub Admin -----
    // ---------------------------
    public function delete_subAdmin($id){
        $subAdmin = SubAdmin::where('id',$id)->first();
        if($subAdmin){
            $subAdmin->delete();
            return response()->json("delete subAdmin successfully!", 200);
        }
        return response()->json("There is an error!", 400);
    }
}
