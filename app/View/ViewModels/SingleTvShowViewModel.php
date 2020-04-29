<?php

namespace App\View\ViewModels;

use Carbon\Carbon;
use Spatie\ViewModels\ViewModel;

class SingleTvShowViewModel extends ViewModel
{
    public $tvshow;

    public function __construct($tvshow)
    {
        $this->tvshow = $tvshow;
    }

    public function tvshow()
    {
        $hasTrailer = !empty($this->tvshow['videos']['results']);
        $firstAirDate = Carbon::parse($this->tvshow['first_air_date'])->format('d.m.Y');

        if ($this->tvshow['in_production']) {
            $date = "{$firstAirDate} - по н.в.";
        } else {
            $lastAirDate = Carbon::parse($this->tvshow['last_air_date'])->format('d.m.Y');
            $date = "{$firstAirDate} - {$lastAirDate}";
        }

        return collect($this->tvshow)->merge([
            'poster' => $this->tvshow['poster_path']
                ? "https://image.tmdb.org/t/p/w500/{$this->tvshow['poster_path']}"
                : 'https://via.placeholder.com/500x750',
            'rating' => $this->tvshow['vote_average'] * 10 . '%',
            'date' => $date,
            'genres' => collect($this->tvshow['genres'])->pluck('name')->flatten()->implode(', '),
            'images' => collect($this->tvshow['images']['backdrops'])->take(9),
            'cast' => collect($this->tvshow['credits']['cast'])->take(10),
            'hasTrailer' => $hasTrailer,
            'trailer' => $hasTrailer
                ? "https://www.youtube.com/embed/{$this->tvshow['videos']['results'][0]['key']}"
                : null,
            'crew' => collect($this->tvshow['credits']['crew'])->map(function ($crewMember) {
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
            })->filter()->only([
                'poster',
                'name',
                'original_name',
                'rating',
                'date',
                'genres',
                'overview',
                'crew',
                'hasTrailer',
                'trailer',
                'cast',
                'images',
                'created_by'
            ])
        ]);
    }
}
