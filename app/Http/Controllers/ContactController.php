<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function contactStore(Request $request)
    {
       $validatedAttr = $request->validate([
            'name'      => 'required|string',
            'phone'     => 'required|numeric|digits:11',
            'email'     => 'required|email',
            'message'   => 'required|string'
        ]);

        Contact::create($validatedAttr);
        flash()->success('Your message has been send');
        return redirect()->back();
    }


    /**
     * for admin view
     */
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'DESC')->paginate(5);
        return view('admin.contact.contact', compact('contacts'));
    }

    public function deleteContact(Request $request)
    {
        $contactId = $request->contact_id;
        $contact = Contact::find($contactId);
        $contact->delete();
        flash()->success('Message has been deleted');
        return redirect()->back();
    }
}
