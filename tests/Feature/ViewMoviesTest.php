<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ViewMoviesTest extends TestCase
{
    /** @test */
    public function the_main_page_shows_the_correct_info()
    {
        Http::fake([
            'https://api.themoviedb.org/3/movie/popular?language=ru-RU&region=RU' => $this->fakeMovies(),
            'https://api.themoviedb.org/3/movie/now_playing?language=ru-RU&region=RU' => $this->fakeMovies(),
            'https://api.themoviedb.org/3/genre/movie/list?language=ru-RU' => $this->fakeGenres()
        ]);

        $response = $this->get(route('movies.index'));

        $response->assertSuccessful();
        $response->assertSee('Популярные фильмы');
        $response->assertSee('Fake Movie');
        $response->assertSee('боевик, приключения, мультфильм');
        $response->assertSee('Смотрят сейчас');
    }

    /** @test */
    public function the_movie_page_shows_the_correct_info()
    {
        Http::fake([
            'https://api.themoviedb.org/3/movie/*' => $this->fakeSingleMovie()
        ]);

        $response = $this->get(route('movies.show', 419704));

        $response->assertSuccessful();
        $response->assertSee('Fake Jumanji: The Next Level');
        $response->assertSee('Jake Kasdan');
        $response->assertSee('Режиссер');
        $response->assertSee('Dwayne Johnson');
    }

    /** @test */
    public function the_search_dropdown_works_correctly()
    {
        Http::fake([
            'https://api.themoviedb.org/3/search/movie?language=ru-RU&query=Fake%20Movie' => $this->fakeMovies()
        ]);

        Livewire::test('search-dropdown')
            ->assertDontSee('Fake Movie')
            ->set('search', 'Fake Movie')
            ->assertSee('Fake Movie');
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
                        28,
                        12,
                        16
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
        return Http::response(['genres' => [
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
        return Http::response([
            "adult" => false,
            "backdrop_path" => "/hreiLoPysWG79TsyQgMzFKaOTF5.jpg",
            "genres" => [
                ["id" => 28, "name" => "Action"],
                ["id" => 12, "name" => "Adventure"],
                ["id" => 35, "name" => "Comedy"],
                ["id" => 14, "name" => "Fantasy"],
            ],
            "homepage" => "http://jumanjimovie.com",
            "id" => 12345,
            "overview" => "As the gang return to Jumanji to rescue one of their own, they discover that nothing is as they expect. The players will have to brave parts unknown and unexplored.",
            "poster_path" => "/bB42KDdfWkOvmzmYkmK58ZlCa9P.jpg",
            "release_date" => "2019-12-04",
            "runtime" => 123,
            "title" => "Fake Jumanji: The Next Level",
            "vote_average" => 6.8,
            "credits" => [
                "cast" => [
                    [
                        "cast_id" => 2,
                        "character" => "Dr. Smolder Bravestone",
                        "credit_id" => "5aac3960c3a36846ea005147",
                        "gender" => 2,
                        "id" => 18918,
                        "name" => "Dwayne Johnson",
                        "order" => 0,
                        "profile_path" => "/kuqFzlYMc2IrsOyPznMd1FroeGq.jpg",
                    ]
                ],
                "crew" => [
                    [
                        "credit_id" => "5d51d4ff18b75100174608d8",
                        "department" => "Production",
                        "gender" => 2,
                        "id" => 546,
                        "job" => "Director",
                        "name" => "Jake Kasdan",
                        "profile_path" => null,
                    ]
                ]
            ],
            "videos" => [
                "results" => [
                    [
                        "id" => "5d1a1a9b30aa3163c6c5fe57",
                        "iso_639_1" => "en",
                        "iso_3166_1" => "US",
                        "key" => "rBxcF-r9Ibs",
                        "name" => "JUMANJI: THE NEXT LEVEL - Official Trailer (HD)",
                        "site" => "YouTube",
                        "size" => 1080,
                        "type" => "Trailer",
                    ]
                ]
            ],
            "images" => [
                "backdrops" => [
                    [
                        "aspect_ratio" => 1.7777777777778,
                        "file_path" => "/hreiLoPysWG79TsyQgMzFKaOTF5.jpg",
                        "height" => 2160,
                        "iso_639_1" => null,
                        "vote_average" => 5.388,
                        "vote_count" => 4,
                        "width" => 3840,
                    ]
                ],
                "posters" => [
                    []
                ]
            ]
        ], 200);
    }
}
