<div>
    <form wire:submit="save" class="space-y-6">
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
                {{ session('message') }}
            </div>
        @endif

        <div class="space-y-4">
            <flux:input 
                wire:model="company_name" 
                label="Company Name" 
                placeholder="Enter your company name" 
                required 
            />

            <flux:input 
                wire:model="contact_email" 
                type="email" 
                label="Contact Email" 
                placeholder="For public support inquiries" 
            />

            <flux:input 
                wire:model="payout_email" 
                type="email" 
                label="Payout Email" 
                placeholder="For receiving funds (Stripe/PayPal)" 
            />

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Logo</label>
                
                @if ($current_logo)
                    <div class="mb-3">
                        <img src="{{ Storage::url($current_logo) }}" alt="Logo" class="w-24 h-24 object-cover rounded shadow-sm">
                    </div>
                @endif
                
                <input type="file" wire:model="logo" class="block w-full text-sm text-gray-500
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-md file:border-0
                  file:text-sm file:font-semibold
                  file:bg-indigo-50 file:text-indigo-700
                  hover:file:bg-indigo-100" />
                  
                <div wire:loading wire:target="logo" class="text-sm text-indigo-600 mt-1">Uploading...</div>
                @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
            <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Save Profile</span>
                <span wire:loading wire:target="save">Saving...</span>
            </flux:button>
        </div>
    </form>
</div>
