<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompanySettingController extends Controller
{
    /**
     * Display the company settings.
     */
    public function index()
    {
        // Assuming there's only one set of company settings
        $settings = CompanySetting::first();

        if (!$settings) {
            return response()->json([
                'message' => 'Company settings not found. Please create them.',
                'settings' => null
            ], 404);
        }

        return response()->json($settings);
    }

    /**
     * Store or update company settings.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'company_name' => 'nullable|string|max:255',
                'company_email' => 'nullable|email|max:255',
                'company_phone' => 'nullable|string|max:255',
                'company_address' => 'nullable|string|max:255',
                'default_currency' => 'nullable|string|max:10',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        // Find the first (and likely only) company settings record, or create a new one
        $settings = CompanySetting::firstOrNew([]);
        $settings->fill($request->all());
        $settings->save();

        return response()->json($settings, 200);
    }
}
