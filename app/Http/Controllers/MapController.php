<?php

namespace App\Http\Controllers;

use App\Models\User;

class MapController extends Controller
{
    private $cityBounds = [
        'douala' => ['lat' => [4.00, 4.10], 'lng' => [9.65, 9.85]],
        'yaounde' => ['lat' => [3.80, 3.92], 'lng' => [11.40, 11.55]],
        'yaoundé' => ['lat' => [3.80, 3.92], 'lng' => [11.40, 11.55]],
        'bafoussam' => ['lat' => [5.45, 5.55], 'lng' => [10.38, 10.45]],
        'bafang' => ['lat' => [5.14, 5.20], 'lng' => [10.16, 10.22]],
        'bamenda' => ['lat' => [5.92, 6.00], 'lng' => [10.12, 10.18]],
        'garoua' => ['lat' => [9.27, 9.35], 'lng' => [13.37, 13.43]],
        'maroua' => ['lat' => [10.56, 10.62], 'lng' => [14.30, 14.35]],
        'nkongsamba' => ['lat' => [4.93, 5.00], 'lng' => [9.91, 9.96]],
        'kribi' => ['lat' => [2.91, 2.96], 'lng' => [9.89, 9.94]],
        'limbe' => ['lat' => [3.99, 4.06], 'lng' => [9.19, 9.25]],
        'buea' => ['lat' => [4.14, 4.19], 'lng' => [9.21, 9.26]],
        'dschang' => ['lat' => [5.42, 5.48], 'lng' => [10.04, 10.10]],
        'foumban' => ['lat' => [5.71, 5.76], 'lng' => [10.88, 10.93]],
        'kumba' => ['lat' => [4.61, 4.66], 'lng' => [9.41, 9.46]],
        'ebolowa' => ['lat' => [2.88, 2.93], 'lng' => [11.13, 11.18]],
        'sangmelima' => ['lat' => [2.91, 2.96], 'lng' => [11.96, 12.01]],
        'bertoua' => ['lat' => [4.56, 4.62], 'lng' => [13.66, 13.71]],
        'ngaoundere' => ['lat' => [7.29, 7.35], 'lng' => [13.56, 13.61]],
        'edea' => ['lat' => [3.78, 3.83], 'lng' => [10.11, 10.16]],
        'mbouda' => ['lat' => [5.61, 5.66], 'lng' => [10.24, 10.30]],
    ];

    private function coordsFromQuarter($city, $quarter, $userId)
    {
        $key = strtolower(trim($city ?? ''));
        $bounds = $this->cityBounds[$key] ?? ['lat' => [4.00, 4.10], 'lng' => [9.65, 9.85]];

        $hash = crc32(strtolower(trim($quarter ?? '')) . '_' . $userId);
        $ratioLat = (($hash & 0xFFFF) % 1000) / 1000;
        $ratioLng = (($hash >> 16) % 1000) / 1000;

        $lat = $bounds['lat'][0] + $ratioLat * ($bounds['lat'][1] - $bounds['lat'][0]);
        $lng = $bounds['lng'][0] + $ratioLng * ($bounds['lng'][1] - $bounds['lng'][0]);

        return [round($lat, 7), round($lng, 7)];
    }

    private function ensureCoords($vidangeur)
    {
        if ($vidangeur->latitude && $vidangeur->longitude &&
            abs($vidangeur->latitude - round($vidangeur->latitude, 3)) > 0.001) {
            return;
        }
        $coords = $this->coordsFromQuarter($vidangeur->city, $vidangeur->quarter, $vidangeur->id);
        $vidangeur->latitude = $coords[0];
        $vidangeur->longitude = $coords[1];
        $vidangeur->save();
    }

    public function index()
    {
        $vidangeurs = User::where('role', 'vidangeur')->get();
        foreach ($vidangeurs as $v) {
            $this->ensureCoords($v);
        }
        return view('map.simple', compact('vidangeurs'));
    }

    public function test() { return $this->index(); }
    public function embed() { return $this->index(); }
    public function simple() { return $this->index(); }
}
