<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MovieCard extends Component
{
    public $movie;
    public $genres;

    public function __construct($movie, $genres)
    {
        $this->movie = $movie;
        $this->genres = $genres;
    }

    public function render()
    {
        return view('components.movie-card', [
            'id' => $this->movie['id'],
            'poster' => "https://image.tmdb.org/t/p/w500/{$this->movie['poster_path']}",
            'title' => $this->movie['title'],
            'rating' => $this->movie['vote_average'] * 10,
            'date' => \Carbon\Carbon::parse($this->movie['release_date'])->format('m/Y'),
            'genreIDs' => $this->movie['genre_ids']
        ]);
    }
}
