<div class="flex items-center justify-center h-screen">
    <div class="bg-white rounded-xl p-8 w-full max-w-md shadow-md">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Complete Your Payment</h2>

        @error('error')
            <small class="text-red-500 text-center">{{ $message }}</small>
        @enderror

        <form wire:submit="createSource">
            <label for="amount" class="block text-sm text-gray-500 mb-1">Amount (PHP)</label>
            <input type="number" id="amount" name="amount" step="0.01" wire:model.live.debounce.500ms="amount"
                placeholder="e.g. 500" value="{{ old('amount') }}" required
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-base mb-5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('amount')
                <small class="text-red-500">{{ $message }}</small>
            @enderror
            <p class="text-xs text-gray-400 uppercase tracking-wide my-2">E-Wallets</p>
            <div class="grid grid-cols-3 gap-3 mb-3">
                <label
                    class="border-2 border-gray-200 rounded-lg py-3 px-2 text-center cursor-pointer transition has-checked:border-blue-500 has-checked:bg-blue-50">
                    <input type="radio" name="type" wire:model="type" value="gcash" class="hidden">
                    <img src="https://static.vecteezy.com/system/resources/previews/067/065/665/non_2x/gcash-logo-square-rounded-gcash-logo-free-download-gcash-logo-free-png.png"
                        alt="GCash" class="h-7 object-contain mx-auto">
                    <span class="block text-xs mt-1 text-gray-700">GCash</span>
                </label>
                <label
                    class="border-2 border-gray-200 rounded-lg py-3 px-2 text-center cursor-pointer transition has-checked:border-blue-500 has-checked:bg-blue-50">
                    <input type="radio" name="type" wire:model.live.debounce.500ms='type' value="grab_pay"
                        class="hidden">
                    <img src="https://static.vecteezy.com/system/resources/previews/067/065/641/large_2x/grabpay-logo-square-rounded-grabpay-logo-free-download-grabpay-logo-free-png.png"
                        alt="GrabPay" class="h-7 object-contain mx-auto">
                    <span class="block text-xs mt-1 text-gray-700">GrabPay</span>
                </label>
                <label
                    class="border-2 border-gray-200 rounded-lg py-3 px-2 text-center cursor-pointer transition has-checked:border-blue-500 has-checked:bg-blue-50">
                    <input type="radio" name="type" wire:model.live.debounce.500ms='type' value="maya"
                        class="hidden">
                    <img src="https://brandlogos.net/wp-content/uploads/2025/03/maya-logo_brandlogos.net_y6kkp-512x512.png"
                        alt="Maya" class="h-7 object-contain mx-auto">
                    <span class="block text-xs mt-1 text-gray-700">Maya</span>
                    <span class="inline-block text-[0.65rem] bg-amber-100 text-amber-800 rounded px-1 mt-0.5">Live
                        only</span>
                </label>
                <label
                    class="border-2 border-gray-200 rounded-lg py-3 px-2 text-center cursor-pointer transition has-checked:border-blue-500 has-checked:bg-blue-50">
                    <input type="radio" name="type" wire:model.live.debounce.500ms='type' value="qrph"
                        class="hidden">
                    <img src="https://svgstack.com/media/img/qr-ph-logo-6f76723590.webp" alt="QR PH"
                        class="h-7 object-contain mx-auto">
                    <span class="block text-xs mt-1 text-gray-700">QR PH</span>
                    <span class="inline-block text-[0.65rem] bg-amber-100 text-amber-800 rounded px-1 mt-0.5">Live
                        only</span>
                </label>
            </div>

            <hr class="border-gray-100 my-4">

            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Online Banking</p>
            <div class="grid grid-cols-3 gap-3 mb-3">
                <label
                    class="border-2 border-gray-200 rounded-lg py-3 px-2 text-center cursor-pointer transition has-checked:border-blue-500 has-checked:bg-blue-50">
                    <input type="radio" name="type" wire:model.live.debounce.500ms='type' value="bpi"
                        class="hidden">
                    <img src="https://logodix.com/logo/2069477.jpg" alt="BPI" class="h-7 object-contain mx-auto">
                    <span class="block text-xs mt-1 text-gray-700">BPI</span>
                    <span class="inline-block text-[0.65rem] bg-amber-100 text-amber-800 rounded px-1 mt-0.5">Live
                        only</span>
                </label>
                <label
                    class="border-2 border-gray-200 rounded-lg py-3 px-2 text-center cursor-pointer transition has-checked:border-blue-500 has-checked:bg-blue-50">
                    <input type="radio" name="type" wire:model.live.debounce.500ms='type' value="unionbank"
                        class="hidden">
                    <img src="https://logodix.com/logo/2207246.jpg" alt="UnionBank" class="h-7 object-contain mx-auto">
                    <span class="block text-xs mt-1 text-gray-700">UnionBank</span>
                    <span class="inline-block text-[0.65rem] bg-amber-100 text-amber-800 rounded px-1 mt-0.5">Live
                        only</span>
                </label>
                <label
                    class="border-2 border-gray-200 rounded-lg py-3 px-2 text-center cursor-pointer transition has-checked:border-blue-500 has-checked:bg-blue-50">
                    <input type="radio" name="type" wire:model.live.debounce.500ms='type' value="metrobank"
                        class="hidden">
                    <img src="https://logodix.com/logo/1799112.png" alt="Metrobank" class="h-7 object-contain mx-auto">
                    <span class="block text-xs mt-1 text-gray-700">Metrobank</span>
                    <span class="inline-block text-[0.65rem] bg-amber-100 text-amber-800 rounded px-1 mt-0.5">Live
                        only</span>
                </label>
            </div>
            @error('type')
                <small class="text-red-500">{{ $message }}</small>
            @enderror

            <button type="submit" wire:loading.attr="disabled"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-base cursor-pointer mt-4 transition">
                <span wire:loading>Please wait...</span>
                <span wire:loading.remove>Pay Now</span>
            </button>
        </form>
    </div>
</div>
