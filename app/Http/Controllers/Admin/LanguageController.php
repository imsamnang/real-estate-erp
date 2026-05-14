<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $available = explode(',', env('APP_AVAILABLE_LOCALES', 'en,km'));

        if (! in_array($locale, $available, true)) {
            $locale = config('app.locale');
        }

        $request->session()->put('locale', $locale);
        app()->setLocale($locale);

        return response()->json([
            'locale' => $locale,
            'translations' => $this->translationsFor($locale),
        ]);
    }

    public function translations(Request $request, ?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $available = explode(',', env('APP_AVAILABLE_LOCALES', 'en,km'));

        if (! in_array($locale, $available, true)) {
            $locale = config('app.locale');
        }

        return response()->json([
            'locale' => $locale,
            'translations' => $this->translationsFor($locale),
        ]);
    }

    private function translationsFor(string $locale): array
    {
        $path = lang_path("$locale/messages.php");

        if (! is_file($path)) {
            return [];
        }

        $translations = require $path;

        return is_array($translations) ? $translations : [];
    }
}
