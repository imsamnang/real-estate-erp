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

        // Re-render all components on the page with the new locale.
        $this->dispatch('locale-changed', locale: $locale);
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
