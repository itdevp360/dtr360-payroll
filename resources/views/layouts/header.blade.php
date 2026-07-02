<header class="mb-6 flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
            P
        </div>
        <div>
            <h1 class="text-lg font-semibold text-slate-900">@yield('page-title', 'Payroll')</h1>
            <p class="text-sm text-slate-500">Welcome back</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <div class="relative">
            <button id="notificationToggle" type="button" class="flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span>Notifications</span>
                <span id="notificationCount" class="rounded-full bg-rose-500 px-2 py-0.5 text-[11px] font-semibold text-white">0</span>
            </button>

            <div id="notificationDropdown" class="absolute right-0 z-20 mt-2 hidden w-80 rounded-xl border border-slate-200 bg-white p-2 shadow-lg">
                <div class="flex items-center justify-between border-b border-slate-100 px-2 pb-2">
                    <p class="text-sm font-semibold text-slate-800">Notifications</p>
                    <span class="text-xs font-medium text-slate-400">Unread only</span>
                </div>

                <div id="notificationList" class="mt-2 max-h-72 space-y-2 overflow-y-auto"></div>
            </div>
        </div>

        <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                {{ strtoupper(substr(Session::get('firebase_user.name') ?? 'User', 0, 1)) }}
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-semibold text-slate-800">{{ Session::get('firebase_user.name') ?? 'User' }}</span>
                <a href="{{ url('/logout') }}" class="text-xs text-rose-500 hover:underline">Logout</a>
            </div>
        </div>
    </div>
</header>

<div id="notificationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-3">
    <div class="w-full max-w-md rounded-2xl bg-white p-4 shadow-xl">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-semibold text-slate-900">Notification details</h3>
            <button id="closeNotificationModal" type="button" class="text-sm text-slate-500 hover:text-slate-700">Close</button>
        </div>
        <div id="notificationModalBody" class="mt-3 space-y-2 text-sm text-slate-600"></div>
    </div>
</div>

<script>
    const notificationToggle = document.getElementById('notificationToggle');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationList = document.getElementById('notificationList');
    const notificationCount = document.getElementById('notificationCount');
    const notificationModal = document.getElementById('notificationModal');
    const notificationModalBody = document.getElementById('notificationModalBody');
    const closeNotificationModal = document.getElementById('closeNotificationModal');
    let notificationStore = [];
    let selectedNotification = null;

    if (notificationToggle) {
        notificationToggle.addEventListener('click', function (event) {
            event.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
        });
    }

    document.addEventListener('click', function () {
        if (notificationDropdown) {
            notificationDropdown.classList.add('hidden');
        }
    });

    async function closeNotificationModalAndMarkRead() {
        if (!notificationModal) return;

        notificationModal.classList.add('hidden');
        notificationModal.classList.remove('flex');

        if (selectedNotification && selectedNotification.id) {
            const notificationId = selectedNotification.id;
            selectedNotification = null;
            await markNotificationAsRead(notificationId);
        }
    }

    if (closeNotificationModal) {
        closeNotificationModal.addEventListener('click', closeNotificationModalAndMarkRead);
    }

    if (notificationModal) {
        notificationModal.addEventListener('click', function (event) {
            if (event.target === notificationModal) {
                closeNotificationModalAndMarkRead();
            }
        });
    }

    function formatNotificationDate(value) {
        if (!value) return 'Just now';

        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return value;

        return date.toLocaleString();
    }

    function formatAttendanceDateTime(value) {
        if (!value) return '';

        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return value;

        return date.toLocaleString('en-US', {
            month: '2-digit',
            day: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    function renderNotificationItems(items) {
        notificationStore = items;

        if (!notificationList) return;

        if (!items.length) {
            notificationList.innerHTML = '<div class="rounded-lg border border-dashed border-slate-200 px-3 py-4 text-center text-sm text-slate-500">No new notifications</div>';
            return;
        }

        notificationList.innerHTML = items.map(function (item) {
            return `
                <button type="button" data-id="${item.id}" class="flex w-full items-start gap-2 rounded-lg border border-slate-100 p-2 text-left transition hover:bg-slate-50">
                    <div class="mt-0.5 h-2.5 w-2.5 rounded-full bg-rose-500"></div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-700">${item.message}</p>
                        <p class="text-xs text-slate-400">${formatNotificationDate(item.createdAt)}</p>
                    </div>
                </button>
            `;
        }).join('');

        notificationList.querySelectorAll('button[data-id]').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = button.getAttribute('data-id');
                const item = notificationStore.find(function (entry) {
                    return entry.id === id;
                });

                if (item) {
                    selectedNotification = item;
                    openNotificationModal(item);
                }
            });
        });
    }

    function openNotificationModal(item) {
        if (!notificationModal || !notificationModalBody) return;

        notificationModalBody.innerHTML = `
<div class="rounded-xl bg-slate-50 p-2.5">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">${item.type || 'Notification'}</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">${item.message || 'No details available.'}</p>
            </div>
            
            ${(item.employeeName || item.department || item.timeIn || item.timeOut || item.previousEmployeeName || item.previousDepartment || item.previousTimeIn || item.previousTimeOut) ? `
                <div class="rounded-xl border border-slate-200 p-2.5">
                    <p class="text-sm font-semibold text-slate-800">Attendance details</p>
                    <div class="mt-1 text-xs text-slate-600">
                        <p><span class="font-semibold text-slate-700">Employee:</span> ${item.employeeName || 'System'}</p>
                        <p><span class="font-semibold text-slate-700">Department:</span> ${item.department || 'System'}</p>
                    </div>
                    <div class="mt-2 overflow-hidden rounded-lg border border-slate-200">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="px-2 py-1.5 font-semibold">Field</th>
                                    <th class="px-2 py-1.5 font-semibold">Old</th>
                                    <th class="px-2 py-1.5 font-semibold">New</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700">
                                ${item.previousTimeIn || item.timeIn ? `<tr class="border-t border-slate-200"><td class="px-2 py-1.5 font-medium">Time In</td><td class="px-2 py-1.5">${item.previousTimeIn ? formatAttendanceDateTime(item.previousTimeIn) : '—'}</td><td class="px-2 py-1.5">${item.timeIn ? formatAttendanceDateTime(item.timeIn) : '—'}</td></tr>` : ''}
                                ${item.previousTimeOut || item.timeOut ? `<tr class="border-t border-slate-200"><td class="px-2 py-1.5 font-medium">Time Out</td><td class="px-2 py-1.5">${item.previousTimeOut ? formatAttendanceDateTime(item.previousTimeOut) : '—'}</td><td class="px-2 py-1.5">${item.timeOut ? formatAttendanceDateTime(item.timeOut) : '—'}</td></tr>` : ''}
                            </tbody>
                        </table>
                    </div>
                </div>
            ` : ''}
            <div class="rounded-xl border border-slate-200 p-2.5 text-xs">
                <p><span class="font-semibold text-slate-700">From:</span> ${item.editedBy || 'System'}</p>
                <p class="mt-1"><span class="font-semibold text-slate-700">Created:</span> ${formatNotificationDate(item.createdAt)}</p>
            </div>
        `;

        notificationModal.classList.remove('hidden');
        notificationModal.classList.add('flex');
    }

    async function fetchUnreadNotifications() {
        try {
            const response = await fetch('{{ route("notifications.unread") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            const items = data.notifications || [];

            if (notificationCount) {
                notificationCount.textContent = items.length;
            }

            renderNotificationItems(items);
        } catch (error) {
            console.error('Unable to load notifications:', error);
        }
    }

    async function markNotificationAsRead(id) {
        try {
            const response = await fetch('{{ url("/notifications") }}/' + id + '/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                if (notificationDropdown) {
                    notificationDropdown.classList.add('hidden');
                }

                await fetchUnreadNotifications();
            }
        } catch (error) {
            console.error('Unable to update notification:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', fetchUnreadNotifications);
</script>