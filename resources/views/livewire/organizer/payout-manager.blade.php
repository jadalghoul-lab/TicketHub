<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payouts') }}
        </h2>
        
        <flux:modal.trigger name="request-payout">
            <flux:button variant="primary" icon="plus">Request Payout</flux:button>
        </flux:modal.trigger>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Available Balance</h3>
                    <span class="text-2xl font-bold text-indigo-600">€{{ number_format($availableBalance, 2) }}</span>
                </div>
                <p class="text-sm text-gray-500">Your available balance is your total ticket sales minus any payouts already requested or processed.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payout History</h3>
                    
                    @if($payouts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank Details</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($payouts as $payout)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payout->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                €{{ number_format($payout->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($payout->status === 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                @elseif($payout->status === 'approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                                @elseif($payout->status === 'paid')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                                @elseif($payout->status === 'rejected')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ Str::limit($payout->bank_details, 20) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $payouts->links() }}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <span class="material-symbols-outlined text-4xl mb-2">account_balance_wallet</span>
                            <p>No payout requests found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Request Modal using Flux -->
    <flux:modal name="request-payout" class="md:w-96">
        <form wire:submit.prevent="submitRequest">
            <flux:heading size="lg" class="mb-4">Request Payout</flux:heading>
            
            <div class="space-y-4">
                <flux:input wire:model="amount" type="number" step="0.01" max="{{ $availableBalance }}" label="Amount (€)" />
                
                <flux:textarea wire:model="bank_details" label="Bank Details (IBAN / Account Number)" placeholder="Provide your full IBAN, Bank Name, and Account Holder Name" rows="3" />
                
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded text-sm mt-4">
                    <p><strong>Note:</strong> Payout requests may take 2-5 business days to process after approval.</p>
                </div>
            </div>

            <div class="flex mt-6 space-x-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Submit Request</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
