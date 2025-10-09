<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-4">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6 sm:p-8">
            <div class="text-center mb-6">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Verify Your Email</h1>
                <p class="text-gray-600 mt-2 text-sm sm:text-base">
                    We've sent a verification link to your email address.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg">
                    {{ session('info') }}
                </div>
            @endif

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-gray-700 text-sm">
                    Please check your inbox and click the verification link we sent to complete your registration.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.verification.resend') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Resend Verification Email
                </button>
            </form>

            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-800">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>