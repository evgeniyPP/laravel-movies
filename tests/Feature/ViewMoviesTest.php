<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ViewMoviesTest extends TestCase
{
    /** @test */
    public function the_main_page_shows_the_correct_info()
    {
        // Http::fake([
        //     'https://api.themoviedb.org/3/movie/popular?language=ru-RU&region=RU' => $this->fakeMovies(),
        //     'https://api.themoviedb.org/3/movie/now_playing?language=ru-RU&region=RU' => $this->fakeMovies(),
        //     'https://api.themoviedb.org/3/genre/movie/list?language=ru-RU' => $this->fakeGenres()
        // ]);

        $response = $this->get(route('movies.index'));

        $response->assertSuccessful();
        $response->assertSee('Популярные фильмы');
        // $response->assertSee('Fake Movie');
    }

    /** @test */
    public function the_movie_page_shows_the_correct_info()
    {
        Http::fake([
            'https://api.themoviedb.org/3/movie/*' => $this->fakeSingleMovie()
        ]);

        $response = $this->get(route('movies.show', 419704));

        $response->assertSuccessful();
        $response->assertSee('К звёздам');
    }

    private function fakeMovies()
    {
        return Http::response([
            'results' => [
                [
                    "popularity" => 513.305,
                    "vote_count" => 3131,
                    "video" => false,
                    "poster_path" => "/dcbPgUymJt6tbUbs7U6L2Jc0wXD.jpg",
                    "id" => 419704,
                    "adult" => false,
                    "backdrop_path" => "/5BwqwxMEjeFtdknRV792Svo0K1v.jpg",
                    "original_language" => "en",
                    "original_title" => "Original Title of Fake Movie",
                    "genre_ids" => [
                        0 => 37, // вестерн
                        1 => 10402 // музыка
                    ],
                    "title" => "Fake Movie",
                    "vote_average" => 10,
                    "overview" => "Описание фильма",
                    "release_date" => "1996-12-11"
                ]
            ]
        ], 200, ['Headers']);
    }

    private function fakeGenres()
    {
        return Http::response(['results' => [
            0 => [
                "id" => 28,
                "name" => "боевик"
            ],
            1 => [
                "id" => 12,
                "name" => "приключения"
            ],
            2 => [
                "id" => 16,
                "name" => "мультфильм"
            ]
        ]], 200, ['Headers']);
    }

    private function fakeSingleMovie()
    {
        return Http::response(['results' => [
            [
                "adult" => false,
                "backdrop_path" => "/5BwqwxMEjeFtdknRV792Svo0K1v.jpg",
                "belongs_to_collection" => null,
                "budget" => 87500000,
                "genres" => [
                    0 => [
                        "id" => 878,
                        "name" => "фантастика",
                    ],
                    1 => [
                        "id" => 18,
                        "name" => "драма"
                    ]
                ],
                "homepage" => "https://www.foxmovies.com/movies/ad-astra",
                "id" => 419704,
                "imdb_id" => "tt2935510",
                "original_language" => "en",
                "original_title" => "Ad Astra",
                "overview" => "Инженер армейского корпуса путешествует по Галактике в поисках отца, который отправился на поиски внеземной цивилизации 20 лет назад и исчез.",
                "popularity" => 513.305,
                "poster_path" => "/dcbPgUymJt6tbUbs7U6L2Jc0wXD.jpg",
                "production_companies" => [],
                "production_countries" => [],
                "release_date" => "2019-09-17",
                "revenue" => 127175922,
                "runtime" => 123,
                "spoken_languages" => [],
                "status" => "Released",
                "tagline" => "Ответы на наши вопросы лежат за пределами досягаемости",
                "title" => "К звёздам",
                "video" => false,
                "vote_average" => 6.0,
                "vote_count" => 3140,
                "credits" => [],
                "videos" => [],
                "images" => []
            ]
        ]]);
    }
}
