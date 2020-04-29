<?php

namespace App\View\ViewModels;

use Carbon\Carbon;
use Spatie\ViewModels\ViewModel;

class SingleMovieViewModel extends ViewModel
{
    public $movie;

    public function __construct($movie)
    {
        $this->movie = $movie;
    }

    public function movie()
    {
        $hasTrailer = !empty($this->movie['videos']['results']);

        return collect($this->movie)->merge([
            'poster' => $this->movie['poster_path']
                ? "https://image.tmdb.org/t/p/w500/{$this->movie['poster_path']}"
                : 'https://via.placeholder.com/500x750',
            'rating' => $this->movie['vote_average'] * 10 . '%',
            'date' => Carbon::parse($this->movie['release_date'])->format('m/Y'),
            'genres' => collect($this->movie['genres'])->pluck('name')->flatten()->implode(', '),
            'images' => collect($this->movie['images']['backdrops'])->take(9),
            'cast' => collect($this->movie['credits']['cast'])->take(10),
            'hasTrailer' => $hasTrailer,
            'trailer' => $hasTrailer
                ? "https://www.youtube.com/embed/{$this->movie['videos']['results'][0]['key']}"
                : null,
            'crew' => collect($this->movie['credits']['crew'])->map(function ($crewMember) {
                switch ($crewMember['job']) {
                    case 'Director':
                        $crewMember['job'] = 'Режиссер';
                        return $crewMember;
                    case 'Director of Photography':
                        $crewMember['job'] = 'Оператор';
                        return $crewMember;
                    case 'Sound Mixer':
                        $crewMember['job'] = 'Звукооператор';
                        return $crewMember;
                    case 'Original Music Composer':
                        $crewMember['job'] = 'Композитор';
                        return $crewMember;
                    default:
                        return null;
                }
            })->filter()
        ])->only([
            'poster',
            'title',
            'rating',
            'date',
            'genres',
            'overview',
            'crew',
            'hasTrailer',
            'trailer',
            'cast',
            'images'
        ]);
    }
}
