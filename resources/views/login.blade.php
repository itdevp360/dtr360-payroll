<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | People360</title>
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100">
    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-8 shadow-xl">
            <div class="mb-6 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-slate-900 text-xl font-semibold text-white">
                    P
                </div>
                <h2 class="text-2xl font-semibold text-slate-900">Welcome back</h2>
                <p class="mt-2 text-sm text-slate-500">Sign in to continue to People360</p>
            </div>

            <div class="mb-4 text-sm text-red-500" id="error"></div>

            <div class="space-y-4">
                <input id="email" type="email" placeholder="Email" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100">
                <input id="password" type="password" placeholder="Password" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-100">
                <button onclick="login()" class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Sign In
                </button>
            </div>
        </div>
    </div>

    <div id="loadingOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50">
        <div class="rounded-2xl bg-white px-6 py-5 shadow-xl">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 animate-spin text-slate-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm font-medium text-slate-700">Signing you in...</p>
            </div>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const loadingOverlay = document.getElementById('loadingOverlay');
        const errorBox = document.getElementById('error');

        function showLoading(show) {
            if (!loadingOverlay) return;
            loadingOverlay.classList.toggle('hidden', !show);
            loadingOverlay.classList.toggle('flex', show);
        }

        window.login = async function () {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            errorBox.innerText = '';
            showLoading(true);

            try {
                const userCredential = await signInWithEmailAndPassword(auth, email, password);
                const token = await userCredential.user.getIdToken();

                const response = await fetch("{{ url('/firebase-login') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ token: token })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    window.location = "{{ url('dashboard') }}";
                } else {
                    errorBox.innerText = data.message || 'Unable to sign in.';
                    showLoading(false);
                }
            } catch (e) {
                errorBox.innerText = e.message || 'Unable to sign in.';
                showLoading(false);
            }
        }
    </script>
</body>
</html>