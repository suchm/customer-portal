<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileImageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ProfileImageController extends Controller
{
    public function uploadImage(ProfileImageRequest $request){

        $user = Auth::user(); // Get the authenticated user

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Create a unique filename

            // Check the current filesystem disk and store accordingly
            if (config('filesystems.default') === 'public') {
                // Save on the public disk for public access
                $path = $file->storeAs('profile_images', $fileName, 'public');
            } else {
                // Save on the local disk (private storage)
                $path = $file->storeAs('profile_images', $fileName, 'local');
            }

            // Save the filename to the user profile
            $user->profile_image = $fileName;
            $user->save();

            return redirect()->back()->with('message', 'Profile image updated successfully.');
        }

        // Handle the case where no file was uploaded
        return redirect()->back()->withErrors(['profile_image' => 'Please upload an image.']);
    }

    public function getProfileImage($filename)
    {
        // ONLY REQUIRED FOR LOCALLY STORED IMAGES
        // Get the path on local disk (private storage)
        $path = storage_path('app/private/profile_images/' . $filename);

        if (!Storage::disk('local')->exists('profile_images/' . $filename)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        $user->profile_image = null;
        $user->save();

        return redirect()->back()->with('message', 'Profile image successfully deleted.');
    }
}
