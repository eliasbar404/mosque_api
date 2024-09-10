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
    public function member_status($id){
        $member = Member::where('id',$id)->first();
        if($member->stauts == 'active'){
            $member->status = 'unactive';
            return response()->json("unactive member successfully!", 200);
        }
        else{
            $member->status = 'active';
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
    public function subAdmin_status($id){
        $subAdmin = SubAdmin::where('id',$id)->first();
        if($subAdmin->stauts == 'active'){
            $subAdmin->status = 'unactive';
            return response()->json("unactive sub Admin successfully!", 200);
        }
        else{
            $subAdmin->status = 'active';
            return response()->json("active sub Admin successfully!", 200);
        }
    
        return response()->json("There is an error", 400);

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
