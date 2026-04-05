<div class="font-sans bg-zinc-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-xl p-10 w-full max-w-sm text-center shadow-md">
        <div class="text-5xl mb-4">❌</div>
        <h2 class="text-xl font-semibold text-gray-900 mb-2">
            Payment Failed
        </h2>
        <p class="text-gray-500 text-sm mb-6">
            Payment failed. Please try again.
        </p>
        <a wire:navigate href="{{ route('payment.form') }}"
            class="inline-block px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
            Back to Payment
        </a>
    </div>
</div>
