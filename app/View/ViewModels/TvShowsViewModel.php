<?php

namespace App\View\ViewModels;

use Carbon\Carbon;
use Spatie\ViewModels\ViewModel;

class TvShowsViewModel extends ViewModel
{
    public $popularTv;
    public $topRatedTv;
    public $genres;

    public function __construct($popularTv, $topRatedTv, $genres)
    {
        $this->popularTv = $popularTv;
        $this->topRatedTv = $topRatedTv;
        $this->genres = $genres;
    }

    public function popularTv()
    {
        return $this->formatShows($this->popularTv);
    }

    public function topRatedTv()
    {
        return $this->formatShows($this->topRatedTv);
    }

    public function genres()
    {
        return collect($this->genres)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });
    }

    private function formatShows($shows)
    {
        return collect($shows)->map(function ($show) {
            $genresFormatted = collect($show['genre_ids'])->mapWithKeys(function ($id) {
                return [$id => $this->genres()->get($id)];
            })->implode(', ');

            return collect($show)->merge([
                'poster' => "https://image.tmdb.org/t/p/w500/{$show['poster_path']}",
                'rating' => $show['vote_average'] * 10 . '%',
                'date' => Carbon::parse($show['first_air_date'])->format('Y'),
                'genres' => $genresFormatted,
                'original_name' => $show['original_language'] !== 'ru' ? $show['original_name'] : ''
            ])->only('id', 'name', 'poster', 'rating', 'date', 'genres', 'original_name');
        });
    }
}
