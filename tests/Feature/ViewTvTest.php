<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ViewTvTest extends TestCase
{
    /** @test */
    public function the_tv_index_page_shows_the_correct_info()
    {
        $this->withoutExceptionHandling();

        Http::fake([
            'https://api.themoviedb.org/3/tv/popular?language=ru-RU' => $this->fakePopularTv(),
            'https://api.themoviedb.org/3/tv/top_rated?language=ru-RU' => $this->fakeHighestRatedTv(),
            'https://api.themoviedb.org/3/genre/tv/list?language=ru-RU' => $this->fakeGenres(),
        ]);


        $response = $this->get(route('tv.index'));

        $response->assertSuccessful();
        $response->assertSee('Популярные ТВ-шоу');
        $response->assertSee('The Simpsons');
        $response->assertSee('анимация, комедия');
        $response->assertSee('Самые рейтинговые шоу');
        $response->assertSee('Во все тяжкие');
        $response->assertSee('Breaking Bad');
    }

    /** @test */
    public function the_tv_page_shows_the_correct_info()
    {
        $this->withoutExceptionHandling();

        Http::fake([
            'https://api.themoviedb.org/3/tv/12345?language=ru-RU&append_to_response=credits,videos,images&include_image_language=en,null' => $this->fakeSingleTv(),
        ]);

        $response = $this->get(route('tv.show', 12345));

        $response->assertSuccessful();
        $response->assertSee('Во все тяжкие');
        $response->assertSee('драма');
        $response->assertSee('20.01.2008 - 29.09.2013');
        $response->assertSee('Vince Gilligan');
    }


    private function fakeSingleTv()
    {
        return Http::response([
            "adult" => false,
            "backdrop_path" => "/hreiLoPysWG79TsyQgMzFKaOTF5.jpg",
            "genres" => [
                ["id" => 18, "name" => "драма"],
            ],
            "id" => 12345,
            "created_by" => [
                0 => [
                    "id" => 66633,
                    "credit_id" => "52542286760ee31328001a7b",
                    "name" => "Vince Gilligan",
                    "gender" => 2,
                    "profile_path" => "/lYqC8Amj4owX05xQg5Yo7uUHgah.jpg",
                ],
            ],
            "overview" => "Breaking Bad description. As the gang return to Jumanji to rescue one of their own, they discover that nothing is as they expect. The players will have to brave parts unknown and unexplored.",
            "poster_path" => "/bB42KDdfWkOvmzmYkmK58ZlCa9P.jpg",
            "first_air_date" => "2008-01-20",
            "last_air_date" => "2013-09-29",
            'in_production' => false,
            "runtime" => 123,
            "name" => "Во все тяжкие",
            "original_name" => "Breaking Bad",
            "vote_average" => 8.5,
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
                        "gender" => 1,
                        "id" => 546,
                        "job" => "Casting Director",
                        "name" => "Jeanne McCarthy",
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


    private function fakePopularTv()
    {
        return Http::response([
            'results' => [
                [
                    "popularity" => 406.677,
                    "vote_count" => 2607,
                    "video" => false,
                    "poster_path" => "/xBHvZcjRiWyobQ9kxBhO6B2dtRI.jpg",
                    "id" => 456,
                    "adult" => false,
                    "backdrop_path" => "/5BwqwxMEjeFtdknRV792Svo0K1v.jpg",
                    "original_language" => "en",
                    "genre_ids" => [
                        16,
                        35,
                    ],
                    "name" => "Симпсоны",
                    "vote_average" => 7.3,
                    "overview" => "The Simpsons description. The near future, a time when both hope and hardships drive humanity to look to the stars and beyond. While a mysterious phenomenon menaces to destroy life on planet earth.",
                    "first_air_date" => "1989-12-17",
                    'original_name' => "The Simpsons",
                    'in_production' => true
                ]
            ]
        ], 200);
    }

    private function fakeHighestRatedTv()
    {
        return Http::response([
            'results' => [
                [
                    "popularity" => 406.677,
                    "vote_count" => 2607,
                    "video" => false,
                    "poster_path" => "/xBHvZcjRiWyobQ9kxBhO6B2dtRI.jpg",
                    "id" => 1396,
                    "adult" => false,
                    "backdrop_path" => "/5BwqwxMEjeFtdknRV792Svo0K1v.jpg",
                    "original_language" => "en",
                    "genre_ids" => [
                        18,
                    ],
                    "name" => "Во все тяжкие",
                    "vote_average" => 8.5,
                    "overview" => "Breaking Bad description. The near future, a time when both hope and hardships drive humanity to look to the stars and beyond. While a mysterious phenomenon menaces to destroy life on planet earth.",
                    "first_air_date" => "2008-01-20",
                    "last_air_date" => "2013-09-29",
                    'original_name' => "Breaking Bad",
                    'in_production' => false
                ]
            ]
        ], 200);
    }

    private function fakeGenres()
    {
        return Http::response([
            'genres' => [
                [
                    "id" => 28,
                    "name" => "экшн"
                ],
                [
                    "id" => 12,
                    "name" => "приключения"
                ],
                [
                    "id" => 16,
                    "name" => "анимация"
                ],
                [
                    "id" => 35,
                    "name" => "комедия"
                ],
                [
                    "id" => 80,
                    "name" => "расследование"
                ],
                [
                    "id" => 99,
                    "name" => "документальный"
                ],
                [
                    "id" => 18,
                    "name" => "драма"
                ],
                [
                    "id" => 10751,
                    "name" => "семейный"
                ],
                [
                    "id" => 14,
                    "name" => "фэнтези"
                ],
                [
                    "id" => 36,
                    "name" => "история"
                ],
                [
                    "id" => 27,
                    "name" => "ужасы"
                ],
                [
                    "id" => 10402,
                    "name" => "музыка"
                ],
                [
                    "id" => 9648,
                    "name" => "мистика"
                ],
                [
                    "id" => 10749,
                    "name" => "романтика"
                ],
                [
                    "id" => 878,
                    "name" => "научная фантастика"
                ],
                [
                    "id" => 10770,
                    "name" => "ТВ-фильм"
                ],
                [
                    "id" => 53,
                    "name" => "триллер"
                ],
                [
                    "id" => 10752,
                    "name" => "военный"
                ],
                [
                    "id" => 37,
                    "name" => "вестерн"
                ],
            ]
        ], 200);
    }
}
