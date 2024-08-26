<?php

namespace Tests\Feature;

use App\Models\Bicycle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BicycleTest extends TestCase
{
    public function test_member_type_user_dont_have_access_to_bicycles(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test',
            'email' => 'testBicycle@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'member'
        ]);

        $token = $testUser->createToken('appAuthenticationToken')->accessToken;

        $testResponse = $this
            ->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/bicycles');

        $testUser->delete();
        $testResponse->assertStatus(403);
    }

    public function test_admin_type_user_have_access_to_bicycles(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test',
            'email' => 'testBicycle@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'admin'
        ]);

        $token = $testUser->createToken('appAuthenticationToken')->accessToken;

        $testResponse = $this
            ->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/bicycles');

        $testUser->delete();
        $testResponse->assertStatus(200)
            ->assertJsonStructure(
                [
                    'status',
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'brand',
                            'model',
                            'color',
                            'prod_year',
                            'image',
                        ],
                    ]
                ]
            );
    }

    public function test_create_bicycle(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test',
            'email' => 'testBicycle@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'admin'
        ]);

        $token = $testUser->createToken('appAuthenticationToken')->accessToken;

        $image = UploadedFile::fake()->image('bicycle.jpg');

        $testResponse = $this
            ->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/bicycles', [
                'brand' => 'Test Brand',
                'model' => 'Test Model',
                'color' => 'Test Color',
                'prod_year' => '2020',
                'image' => $image
            ]);

        $testUser->delete();

        $testResponse->assertStatus(200)
            ->assertJsonStructure(
                [
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'brand',
                        'model',
                        'color',
                        'prod_year',
                        'image',
                    ],
                ]
            );
    }

    public function test_show_bicycle_return_success_response(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test',
            'email' => 'testBicycle@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'admin'
        ]);

        $token = $testUser->createToken('appAuthenticationToken')->accessToken;

        $testResponse = $this
            ->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/bicycles/1');

        $testUser->delete();
        $testResponse->assertStatus(200)
            ->assertJsonStructure(
                [
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'brand',
                        'model',
                        'color',
                        'prod_year',
                        'image',
                    ],
                ]
            );
    }

    public function test_show_bicycle_return_error_response(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test',
            'email' => 'testBicycle@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'admin'
        ]);

        $token = $testUser->createToken('appAuthenticationToken')->accessToken;

        $testResponse = $this
            ->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/bicycles/100');

        $testUser->delete();
        $testResponse->assertStatus(404);
    }

    public function test_update_bicycle_return_success_response(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test',
            'email' => 'testBicycle@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'admin'
        ]);

        $token = $testUser->createToken('appAuthenticationToken')->accessToken;

        $image = UploadedFile::fake()->image('bicycle.jpg');

        $bicycle = Bicycle::create([
            'brand' => 'Test Brand',
            'model' => 'Test Model',
            'color' => 'Test Color',
            'prod_year' => '2020',
            'image' => $image
            ]
        );

        $testResponse = $this
            ->withHeader('Authorization', "Bearer $token")
            ->patchJson('/api/bicycles/' . $bicycle->id, [
                'brand' => 'Update Test Brand',
                'model' => 'Update Test Model',
                'color' => 'Update Test Color',
                'prod_year' => 'Update 2020',
            ]);

        $testUser->delete();

        $testResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Bicycle Updated Successfully!'
            ])
            ->assertJsonStructure(
                [
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'brand',
                        'model',
                        'color',
                        'prod_year',
                        'image',
                    ],
                ]
            );

        $bicycle->delete();
    }

    public function test_delete_bicycle_return_success_response(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test',
            'email' => 'testBicycle@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'admin'
        ]);

        $token = $testUser->createToken('appAuthenticationToken')->accessToken;

        $image = UploadedFile::fake()->image('bicycle.jpg');

        $bicycle = Bicycle::create([
                'brand' => 'Test Brand',
                'model' => 'Test Model',
                'color' => 'Test Color',
                'prod_year' => '2020',
                'image' => $image
            ]
        );

        $testResponse = $this
            ->withHeader('Authorization', "Bearer $token")
            ->deleteJson('/api/bicycles/' . $bicycle->id);

        $testUser->delete();

        $testResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Bicycle Deleted Successfully'
            ]);
    }

    public function test_delete_bicycle_return_error_not_found(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test',
            'email' => 'testBicycle@gmail.com',
            'password' => Hash::make('password'),
            'type' => 'admin'
        ]);

        $token = $testUser->createToken('appAuthenticationToken')->accessToken;

        $testResponse = $this
            ->withHeader('Authorization', "Bearer $token")
            ->deleteJson('/api/bicycles/1000');

        $testUser->delete();

        $testResponse->assertStatus(404);
    }

}
