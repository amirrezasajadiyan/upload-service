<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadImageTest extends TestCase
{
    public function test_upload_image_requires_valid_jwt()
    {
        Storage::fake('public');

        $response = $this->postJson('/api/upload', [
            'image' => UploadedFile::fake()->image('photo.jpg'),
        ]);

        $response->assertStatus(401); // Unauthorized
    }

    public function test_upload_image_with_valid_jwt()
    {
        Storage::fake('public');

        $response = Http::post('http://auth-service:8000/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
//        dd($response['token']);
        $token = 'Bearer ' . $response['token'];

        // Simulate a real JWT token issued by auth service


        $response = $this->postJson('/api/upload', [
            'image' => UploadedFile::fake()->image('photo.jpg'),
        ], [
            'Authorization' => $token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'path'
            ]);
    }
}
