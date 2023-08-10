<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $appends = ['current_weather'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getCurrentWeatherAttribute()
    {

        $weatherApiUrl =  "https://api.openweathermap.org/data/2.5/weather";

        $response = Http::get(
            $weatherApiUrl,
            [
                'lat' => $this->latitude,
                'lon' => $this->longitude,
                'appid' => "9da6aa02d3de69ff966aa69b39f6a1c3",
                'units' => "metric"
            ]
        );

        if ($response->successful()) {
            return $response->json();
        }
        // Cache::put('key', 'value');
        return $response;
    }

    public function updateCurrentWeatherAttribute()
    {

        $weatherApiUrl =  "https://api.openweathermap.org/data/2.5/weather";

        $response = Http::get(
            $weatherApiUrl,
            [
                'lat' => $this->latitude,
                'lon' => $this->longitude,
                'appid' => "9da6aa02d3de69ff966aa69b39f6a1c3",
                'units' => "metric"
            ]
        );

        if ($response->successful()) {
            return $response->json();
        }
        // Cache::put('key', 'value');
        return $response;
    }
}
