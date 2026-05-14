<?php

namespace App\Livewire;

use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $locale = 'en';

    public array $locales = [
        'en' => 'English',
        'km' => 'ខ្មែរ',
    ];

    public function mount(): void
    {
        $this->locale = app()->getLocale();
    }

    public function switch(string $locale): void
    {
        $available = explode(',', env('APP_AVAILABLE_LOCALES', 'en,km'));

        if (! in_array($locale, $available, true)) {
            return;
        }

        session()->put('locale', $locale);
        app()->setLocale($locale);
        $this->locale = $locale;

        // Notify listeners (Livewire components) about the locale change.
        $this->dispatch('locale-changed', locale: $locale);

        // Re-fetch the current page via Livewire's SPA-style navigate so the
        // entire layout (sidebar, header, breadcrumb, content) is re-rendered
        // with the new locale. We dispatch the navigate call as a JS snippet
        // so window.location.href resolves on the *browser* side — calling
        // url()->current() server-side returns the Livewire /livewire/update
        // endpoint, not the actual page URL.
        //
        // Unlike window.location.reload(), Livewire.navigate morphs the DOM
        // in place, preserves scroll position, and does not flash the page.
        $this->js('Livewire.navigate(window.location.href)');
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
