<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class LandingController extends Controller
{
    public function showHomePage()
    {
        $countries = json_decode(file_get_contents(resource_path('data/countries.json')));
        sort($countries);
        return view('home-page', compact('countries'));
    }

    public function redirect(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
            'country' => 'required|string|max:100',
        ]);

        // Check if the country exists in the DB, else create it
        $country = Country::firstOrCreate(['name' => $request->country]);

        // Redirect based on role
        if ($request->role === 'admin') {
            return redirect("/admin/{$country->id}");
        } else {
            return redirect("/register/{$country->id}");
        }
    }
}
