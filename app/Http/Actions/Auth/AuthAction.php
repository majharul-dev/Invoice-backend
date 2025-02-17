<?php

namespace App\Actions\Auth;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;

class AuthAction
{
    public static array $profile;

    public static function getGravatarProfile($email): array
    {
        $emailHash = md5(strtolower(trim($email)));
        $url = "https://en.gravatar.com/{$emailHash}.json";

        $response = Http::get($url);
        $name = null;
        if ($response->successful()) {
            self::$profile = $response->json();
            // Extract the name and image URL if available
            $name = self::$profile['entry'][0]['displayName'] ?? null;
            $imageUrl = self::$profile['entry'][0]['thumbnailUrl'] ?? null;

            // If name is null, use the part before the '@' in the email
            if (is_null($name)) {
                $name = preg_replace('/[.-].*/', '', explode('@', $email)[0]);
            }

            return [
                'name' => $name,
                'image_url' => $imageUrl,
            ];
        }

        if (is_null($name)) {
            $name = preg_replace('/[.-].*/', '', explode('@', $email)[0]);
        }
        // Return null if no profile found
        return [
            'name' => $name,
            'image_url' => null,
        ];
    }

    public static function createUserWithGravatarProfile(string $email, string $password): User
    {
        // Fetch Gravatar profile (name and image)
        $gravatarProfile = self::getGravatarProfile($email);

        // Create the user
        $user = User::create([
            'name' => $gravatarProfile['name'] ?? 'no name',
            'email' => $email,
            'profile_photo_url' => $gravatarProfile['image_url'],
            'password' => Hash::make($password),
        ]);

        // Create the profile using the merged data
        Profile::create(array_merge(
            self::getProfile(), [
            'user_id' => $user->id, // Set the user ID
        ]));

        return $user;
    }


    public static function getProfile()
    {
        return self::$profile['entry'][0] ?? [];
    }

    public static function getAddress(): array
    {
        if (!app()->environment('local')) {
            $ip = request()->ip();
            $location = Location::get($ip);
            return [
                'ip' => $location->ip,
                'iso_code' => $location->isoCode,
                'country' => $location->countryName,
                'city' => $location->cityName,
                'state' => $location->regionName,
                'postal_code' => $location->postalCode,
                'lat' => $location->latitude,
                'lon' => $location->longitude,
                'zip_code' => $location->zipCode,
                'timezone' => $location->timezone,

                // Assuming address_line_1, address_line_2 come from some other logic
                'address_line_1' => 'Default Address Line 1', // You can modify this part
                'address_line_2' => null
            ];
        }
        // Fake data for local environment
        return [
            // Fake data for testing
            'ip' => '127.0.0.1',
            'iso_code' => 'US',
            'country' => 'United States',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'lat' => '40.712776',
            'lon' => '-74.005974',
            'zip_code' => '10001',
            'timezone' => 'America/New_York',

            // Fake address fields
            'address_line_1' => 'Fake Address Line 1',
            'address_line_2' => 'Fake Address Line 2'
        ];
    }
}
