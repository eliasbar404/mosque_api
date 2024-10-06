<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Contact;
use Validator;

class ContactController extends Controller
{
    // Create New Contact
    public function Create_Contact(){
        $validator = Validator::make(request()->all(), [
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'phone_number'    => 'nullable|string|max:255',
            'email'           => 'required|email',
            'message'         => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $contact = new Contact;
        $contact->id            = \Illuminate\Support\Str::uuid(); // Generate UUID
        $contact->first_name    = request()->first_name;
        $contact->last_name     = request()->last_name;
        $contact->email         = request()->email;
        $contact->phone_number  = request()->phone_number;
        $contact->message       = request()->message;
        $contact->save();

        return response()->json($contact, 201);


    }
    // Get All Cantocts
    public function Get_All_Contacts(){
        $contacts = Contact::all();
        return $contacts;
    }
    // Get One Contact By ID
    public function Get_Contact($id){
        $contact = Contact::where('id',$id)->first();
        return $contact;
    }

    // Delete Contact
    public function Delete_contact($id){
        $contact = Contact::where('id',$id)->first();
        $contact->delete();
        return response()->json("Delete the Contact $id succesfully", 200);
    }

    // Change Contact Status
    public function Change_Status($id){
        $contact = Contact::where('id',$id)->first();
        $contact->status = "readed";
        $contact->save();
        return response()->json("Change the Contact $id Status succesfully", 200);
    }
}
