<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // show all listings
    public function index()
    {
        return view('listings.index', [
            // 'listings' => Listing::latest()->filter(request(['tag', 'search']))->get()  // get the latest data
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
            // 'listings' => Listing::latest()->filter(request(['tag', 'search']))->simplePaginate(2)
        ]);
    }

    // show single listing
    public function show(Listing $listing)
    {
        return view('listings.show', [       // Route Model Binding
            'listing' =>  $listing
        ]);
    }

    public function create()
    {
        return view('listings.create');
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->user()->id;

        Listing::create($formFields);


        return redirect('/')->with('message', 'Listing Created Successfully');
    }

    public function edit(Listing $listing)
    {
        return view('listings.edit', ['listing' => $listing]);
    }

    public function update(Request $request, Listing $listing)
    {
        // Make sure logged in user is owner

        if ($listing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized Action');
        }


        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);


        return back()->with('message', 'Listing Updated Successfully');
    }

    public function destroy(Listing $listing)
    {

        if ($listing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $listing->delete();

        return redirect('/')->with('message', 'Listing Deleted Successfully');
    }

    public function manage()
    {
        return view('/listings.manage', [
            'listings' => auth()->user()->listings()->get()
        ]);
    }
}
