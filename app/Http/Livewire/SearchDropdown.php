<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SearchDropdown extends Component
{
    public $search = '';

    public function render()
    {
        $searchResults = [];

        if (strlen($this->search) >= 2) {
            $searchResults = Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/search/movie?language=ru-RU&query={$this->search}")
                ->json()['results'];
        }

        return view('components.search-dropdown', [
            'searchResults' => collect($searchResults)->sortByDesc('popularity')->take(7)
        ]);
    }
}
