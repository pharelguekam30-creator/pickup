<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AuthController;
use App\Models\User;
use ReflectionMethod;

class MapTest extends TestCase
{
    public function test_getCoordsFromQuarter_returns_valid_coordinates()
    {
        $controller = new AuthController();
        $method = new ReflectionMethod($controller, 'getCoordsFromQuarter');
        $method->setAccessible(true);

        list($lat, $lng) = $method->invoke($controller, 'Douala', 'Bonanjo', 1);

        $this->assertIsFloat($lat);
        $this->assertIsFloat($lng);
        $this->assertGreaterThanOrEqual(4.01, $lat);
        $this->assertLessThanOrEqual(4.10, $lat);
        $this->assertGreaterThanOrEqual(9.65, $lng);
        $this->assertLessThanOrEqual(9.85, $lng);
    }

    public function test_getCoordsFromQuarter_different_users_get_different_coords()
    {
        $controller = new AuthController();
        $method = new ReflectionMethod($controller, 'getCoordsFromQuarter');
        $method->setAccessible(true);

        list($lat1, $lng1) = $method->invoke($controller, 'Yaounde', 'Mfoundi', 1);
        list($lat2, $lng2) = $method->invoke($controller, 'Yaounde', 'Mfoundi', 2);

        $this->assertNotEquals([$lat1, $lng1], [$lat2, $lng2]);
    }

    public function test_getCoordsFromQuarter_same_user_gets_same_coords()
    {
        $controller = new AuthController();
        $method = new ReflectionMethod($controller, 'getCoordsFromQuarter');
        $method->setAccessible(true);

        list($lat1, $lng1) = $method->invoke($controller, 'Douala', 'Bonanjo', 1);
        list($lat2, $lng2) = $method->invoke($controller, 'Douala', 'Bonanjo', 1);

        $this->assertEquals([$lat1, $lng1], [$lat2, $lng2]);
    }

    public function test_getCoordsFromQuarter_fallback_for_unknown_city()
    {
        $controller = new AuthController();
        $method = new ReflectionMethod($controller, 'getCoordsFromQuarter');
        $method->setAccessible(true);

        list($lat, $lng) = $method->invoke($controller, 'Inconnu', 'QuartierX', 1);

        $this->assertIsFloat($lat);
        $this->assertIsFloat($lng);
    }

    public function test_vidangeur_with_city_quarter_gets_coords_on_register()
    {
        $user = User::factory()->create([
            'role' => 'vidangeur',
            'city' => 'Douala',
            'quarter' => 'Bonanjo',
            'latitude' => null,
            'longitude' => null,
        ]);

        $controller = new AuthController();
        $method = new ReflectionMethod($controller, 'getCoordsFromQuarter');
        $method->setAccessible(true);

        list($lat, $lng) = $method->invoke($controller, 'Douala', 'Bonanjo', $user->id);

        $user->latitude = $lat;
        $user->longitude = $lng;
        $user->save();

        $this->assertNotNull($user->fresh()->latitude);
        $this->assertNotNull($user->fresh()->longitude);
    }
}
