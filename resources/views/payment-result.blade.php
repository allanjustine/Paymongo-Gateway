<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment {{ ucfirst($status) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-zinc-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-xl p-10 w-full max-w-sm text-center shadow-md">
        <div class="text-5xl mb-4">{{ $status === 'success' ? '✅' : '❌' }}</div>
        <h2 class="text-xl font-semibold text-gray-900 mb-2">
            {{ $status === 'success' ? 'Payment Successful' : 'Payment Failed' }}
        </h2>
        <p class="text-gray-500 text-sm mb-6">{{ $message }}</p>
        <a href="{{ route('payment.form') }}"
            class="inline-block px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
            Back to Payment
        </a>
    </div>
</body>

</html>
