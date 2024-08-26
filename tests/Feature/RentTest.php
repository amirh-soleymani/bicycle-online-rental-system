<?php

namespace Tests\Feature;

use App\Models\Bicycle;
use App\Models\Rent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_bicycle_search(): void
    {
        $image = UploadedFile::fake()->image('bicycle.jpg');

        $bicycle = Bicycle::create([
                'brand' => 'Test Brand',
                'model' => 'Test Model',
                'color' => 'Test Color',
                'prod_year' => '2020',
                'image' => $image
            ]
        );

        $response = $this->getJson('/api/bicycleSearch?pickup_date=2024-12-25&return_date=2024-12-28');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Done'
            ])
            ->assertJsonCount(2,'data')
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

        $bicycle->delete();
    }

    public function test_member_type_user_dont_have_access_admin_rental_report(): void
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
            ->getJson('/api/adminReport');

        $testUser->delete();
        $testResponse->assertStatus(403);
    }

    public function test_admin_type_user_dont_have_access_member_rental_report(): void
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
            ->getJson('/api/memberReport');

        $testUser->delete();
        $testResponse->assertStatus(403);
    }

    public function test_admin_type_user_have_access_admin_rental_report(): void
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
            ->getJson('/api/adminReport');

        $testUser->delete();
        $testResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Done'
            ])
            ->assertJsonStructure(
                [
                    'status',
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'bicycle' =>
                                [
                                    'id',
                                    'brand',
                                    'model',
                                    'color',
                                    'prod_year',
                                    'image',
                                    'created_at',
                                    'updated_at'
                                ],
                            'user' =>
                                [
                                    'id',
                                    'name',
                                    'email',
                                    'type',
                                    'created_at',
                                    'updated_at'
                            ],
                            'pickup_date',
                            'return_date',
                        ],
                    ]
                ]
            );
    }

    public function test_member_type_user_have_access_member_rental_report(): void
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
            ->getJson('/api/memberReport');

        $testUser->delete();
        $testResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Done'
            ])
            ->assertJsonStructure(
                [
                    'status',
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'bicycle' =>
                                [
                                    'id',
                                    'brand',
                                    'model',
                                    'color',
                                    'prod_year',
                                    'image',
                                    'created_at',
                                    'updated_at'
                                ],
                            'user' =>
                                [
                                    'id',
                                    'name',
                                    'email',
                                    'type',
                                    'created_at',
                                    'updated_at'
                                ],
                            'pickup_date',
                            'return_date',
                        ],
                    ]
                ]
            );
    }

    public function test_rent_bicycle(): void
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
            ->postJson('/api/rentBicycle', [
                'bicycle_id' => '1',
                'pickup_date' => '2024-12-25',
                'return_date' => '2024-12-27',
            ]);

        $rent = Rent::where([
            ['bicycle_id', 1],
            ['pickup_date', '2024-12-25'],
            ['return_date', '2024-12-27']
        ]);
        $rent->delete();
        $testUser->delete();

        $testResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Bicycle Reserved Successfully'
            ])
            ->assertJsonStructure(
                [
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'bicycle' =>
                            [
                                'id',
                                'brand',
                                'model',
                                'color',
                                'prod_year',
                                'image',
                                'created_at',
                                'updated_at'
                            ],
                        'user' =>
                            [
                                'id',
                                'name',
                                'email',
                                'type',
                                'created_at',
                                'updated_at'
                            ],
                        'pickup_date',
                        'return_date',
                    ],
                ]
            );
    }


}
