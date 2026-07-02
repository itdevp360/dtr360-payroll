<aside class="flex h-screen w-64 flex-col border-r border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-6 py-5">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                P
            </div>
            <div>
                <p class="text-lg font-semibold text-slate-900">People360</p>
                <p class="text-sm text-slate-500">Payroll Workspace</p>
            </div>
        </div>
    </div>

    <nav class="mt-6 flex-1 space-y-1 px-3">
        <a href="{{ url('/dashboard') }}" class="flex items-center rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-900">
            <span class="mr-3 text-slate-500">⌂</span>
            Home
        </a>

        @if(isset($usertype) && trim($usertype) === 'Approver')
            <a href="{{ url('/payroll/approval') }}" class="flex items-center rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-900">
                <span class="mr-3 text-slate-500">✓</span>
                Approvals
            </a>
        @endif

        <a href="{{ url('/payroll/dashboard') }}" class="flex items-center rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-900">
            <span class="mr-3 text-slate-500">◫</span>
            Payroll
        </a>
        <a href="{{ url('/settings') }}" class="flex items-center rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-900">
            <span class="mr-3 text-slate-500">⚙</span>
            Settings
        </a>
    </nav>
</aside>

<script>
    const userDept = "{{ $dept ?? '' }}";
    const usertype = "{{ $usertype ?? '' }}";
</script>