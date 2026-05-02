<?php

namespace App\Services;

use App\Models\Organizer;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class OrganizerService
{
    /**
     * Create or update the organizer profile.
     */
    public function saveProfile(array $data, ?UploadedFile $logo = null): Organizer
    {
        $user = auth()->user();
        
        $organizer = $user->organizer ?? new Organizer(['user_id' => $user->id]);
        
        $organizer->company_name = $data['company_name'];
        $organizer->slug = Str::slug($data['company_name'] . '-' . uniqid());
        $organizer->contact_email = $data['contact_email'] ?? null;
        $organizer->payout_email = $data['payout_email'] ?? null;
        
        // Handling the logo upload
        if ($logo) {
            // Delete old logo if it exists
            if ($organizer->logo && Storage::disk('public')->exists($organizer->logo)) {
                Storage::disk('public')->delete($organizer->logo);
            }
            
            $path = $logo->store('organizer_logos', 'public');
            $organizer->logo = $path;
        }

        // If it's a new profile, it should be pending admin approval
        if (!$organizer->exists) {
            $organizer->status = 'pending';
        }

        $organizer->save();

        return $organizer;
    }
}
