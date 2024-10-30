<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('profile image is uploaded', function () {

    Storage::fake('local');
    $filename = 'profile-image.jpg';

    $user = User::factory()->create();

    $response = $this
        ->followingRedirects() // Follows where the redirect goes. Changes status to 200 instead of 302
        ->actingAs($user)
        ->post(route('profile-image.upload'), [
        'profile_image' => UploadedFile::fake()->image($filename),
    ]);

    $response->assertStatus(200);

    // Check that the file exists on the public disk
    $newFileName = now()->timestamp . '_' . $filename; // modified filename
    $filePath = 'profile_images/' . $newFileName; // Simulate the full path
    Storage::disk('local')->assertExists($filePath);

    $user->refresh();
    $this->assertEquals($newFileName, $user->profile_image);
});

test('profile image is deleted', function () {

    Storage::fake('local');

    $filename = now()->timestamp . '_profile-image.jpg';

    $user = User::factory()->create([
        'profile_image' => $filename
    ]);

    $response = $this
        ->followingRedirects() // Follows where the redirect goes. Changes status to 200 instead of 302
        ->actingAs($user)
        ->delete(route('profile-image.destroy'));

    $response->assertStatus(200);

    $user->refresh();
    $this->assertEquals(null, $user->profile_image);
});
