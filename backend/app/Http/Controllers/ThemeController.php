<?php

namespace App\Http\Controllers;
use App\Models\ThemeSettings;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function getThemeSettings(): JsonResponse
    {
        $settings = ThemeSettings::first();
        // return response()->json([
        //     'background_color' => $settings->background_color,
        //     'default_color' => $settings->default_color,
        //     'heading_color' => $settings->heading_color,
        //     'accent_color' => $settings->accent_color,
        //     'surface_color' => $settings->surface_color,
        //     'contrast_color' => $settings->contrast_color,
        //     'nav_color' => $settings->nav_color,
        //     'nav_hover_color' => $settings->nav_hover_color,
        //     'nav_mobile_background_color' => $settings->nav_mobile_background_color,
        //     'nav_dropdown_background_color' => $settings->nav_dropdown_background_color,
        //     'nav_dropdown_color' => $settings->nav_dropdown_color,
        //     'nav_dropdown_hover_color' => $settings->nav_dropdown_hover_color,
        // ]);

        // Default theme data - For testing purpose
        return response()->json([
            'background_color' => $settings->background_color ?? '#ffffff',
            'default_color' => $settings->default_color ?? '#212529',
            'heading_color' => $settings->heading_color ?? '#3e0925',
            'accent_color' => $settings->accent_color ?? '#8c0d4f',
            'surface_color' => $settings->surface_color ?? '#ffffff',
            'contrast_color' => $settings->contrast_color ?? '#ffffff',
            'nav_color' => $settings->nav_color ?? '#212529',
            'nav_hover_color' => $settings->nav_hover_color ?? '#8c0d4f',
            'nav_mobile_background_color' => $settings->nav_mobile_background_color ?? '#ffffff',
            'nav_dropdown_background_color' => $settings->nav_dropdown_background_color ?? '#ffffff',
            'nav_dropdown_color' => $settings->nav_dropdown_color ?? '#212529',
            'nav_dropdown_hover_color' => $settings->nav_dropdown_hover_color ?? '#8c0d4f',
        ]);
    }

    public function updateThemeSettings(Request $request): JsonResponse
    {
        $settings = ThemeSettings::first();
        $settings->update($request->all());
        return response()->json($settings);
    }
}
