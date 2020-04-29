<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Spatie\ViewModels\ViewModel;

class SingleActorViewModel extends ViewModel
{
    private $actor;
    private $social;
    private $credits;

    public function __construct($actor, $social, $credits)
    {
        $this->actor = $actor;
        $this->social = $social;
        $this->credits = $credits;
    }

    public function actor()
    {
        return collect($this->actor)->merge([
            'image' => $this->actor['profile_path'] ? "https://image.tmdb.org/t/p/w300/{$this->actor['profile_path']}" : "https://via.placeholder.com/300x450",
            'birthday' => Carbon::parse($this->actor['birthday'])->format('d.m.Y'),
            'age' => Carbon::parse($this->actor['birthday'])->age,
            'biography' => nl2br(htmlspecialchars($this->actor['biography'])),
            'isFemale' => $this->actor['gender'] === 1 ? true : false,
        ])->only(['image', 'birthday', 'age', 'name', 'biography', 'place_of_birth', 'homepage', 'isFemale']);
    }

    public function social()
    {
        return collect($this->social)->merge([
            'facebook' => $this->social['facebook_id'] ? "https://facebook.com/{$this->social['facebook_id']}" : null,
            'twitter' => $this->social['twitter_id'] ? "https://twitter.com/{$this->social['twitter_id']}" : null,
            'instagram' => $this->social['instagram_id'] ? "https://instagram.com/{$this->social['instagram_id']}" : null,
        ])->only(['facebook', 'twitter', 'instagram']);
    }

    public function knownFor()
    {
        $cast = collect($this->credits)->get('cast');

        return collect($cast)->where('media_type', 'movie')->sortByDesc('popularity')->take(5)
            ->map(function ($movie) {
                $title = isset($movie['title']) && $movie['title'] ? $movie['title'] : 'Без названия';
                $originalTitle = isset($movie['original_title']) && $movie['original_title']
                    ? $movie['original_title'] : 'Untitled';

                if ($movie['original_language'] !== 'ru') {
                    $title .= " ({$originalTitle})";
                }

                return collect($movie)->merge([
                    'poster' => $movie['poster_path']
                        ? "https://image.tmdb.org/t/p/w185/{$movie['poster_path']}"
                        : 'https://via.placeholder.com/185x278',
                    'title' => $title
                ]);
            });
    }

    public function credits()
    {
        $cast = collect($this->credits)->get('cast');

        return collect($cast)
            ->map(function ($movie) {
                if (isset($movie['release_date']) && $movie['release_date']) {
                    $releaseDate = $movie['release_date'];
                } elseif (isset($movie['first_air_date']) && $movie['first_air_date']) {
                    $releaseDate = $movie['first_air_date'];
                } else {
                    $releaseDate = null;
                }

                if (isset($movie['title']) && $movie['title']) {
                    $title = $movie['title'];
                } elseif (isset($movie['name']) && $movie['name']) {
                    $title = $movie['name'];
                } else {
                    $title = 'Без названия';
                }

                return collect($movie)->merge([
                    'release_date' => $releaseDate,
                    'release_year' => isset($releaseDate) ? Carbon::parse($releaseDate)->format('Y') : null,
                    'title' => $title,
                    'character' => isset($movie['character']) ? $movie['character'] : '',
                    'link' => URL::to("/movies/{$movie['id']}")
                ])->only(['character', 'title', 'release_date', 'release_year', 'link']);
            })->filter(function ($movie) {
                return $movie['release_date'];
            })->sortByDesc('release_date');
    }
}
