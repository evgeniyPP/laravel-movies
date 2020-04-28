<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class MoviesController extends Controller
{
    public function index()
    {
        $popularMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/popular?language=ru-RU&region=RU')
            ->json()['results'];

        $nowPlayingMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/now_playing?language=ru-RU&region=RU')
            ->json()['results'];

        $genresArray = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/movie/list?language=ru-RU')
            ->json()['genres'];

        $genres = collect($genresArray)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });

        return view('index', [
            'popularMovies' => $popularMovies,
            'nowPlayingMovies' => $nowPlayingMovies,
            'genres' => $genres
        ]);
    }

    public function show($id)
    {
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/movie/{$id}?language=ru-RU&append_to_response=credits,videos,images&include_image_language=en,null")
            ->json();

        $genres = '';
        foreach ($movie['genres'] as $key => $genre) {
            reset($movie['genres']);
            $genres .= $genre['name'];
            end($movie['genres']);
            if ($key !== key($movie['genres'])) {
                $genres .= ', ';
            }
        }

        $crew = [];
        foreach ($movie['credits']['crew'] as $movieCrew) {
            switch ($movieCrew['job']) {
                case 'Director':
                    $crew[] = [
                        'name' => $movieCrew['name'],
                        'job' => 'Режиссер'
                    ];
                    break;
                case 'Director of Photography':
                    $crew[] = [
                        'name' => $movieCrew['name'],
                        'job' => 'Оператор'
                    ];
                    break;
                case 'Sound Mixer':
                    $crew[] = [
                        'name' => $movieCrew['name'],
                        'job' => 'Звукооператор'
                    ];
                    break;
                case 'Original Music Composer':
                    $crew[] = [
                        'name' => $movieCrew['name'],
                        'job' => 'Композитор'
                    ];
                    break;
            }
        }

        $isTrailer = !empty($movie['videos']['results']);

        return view('show', [
            'poster' => "https://image.tmdb.org/t/p/w500/{$movie['poster_path']}",
            'title' => $movie['title'],
            'rating' => $movie['vote_average'] * 10,
            'date' => \Carbon\Carbon::parse($movie['release_date'])->format('d.m.Y'),
            'genres' => $genres,
            'overview' => $movie['overview'],
            'crew' => $crew,
            'isTrailer' => $isTrailer,
            'youtubeLink' => $isTrailer
                ? "https://www.youtube.com/embed/{$movie['videos']['results'][0]['key']}" : null,
            'cast' => $movie['credits']['cast'],
            'images' => $movie['images']['backdrops']
        ]);
    }
}
