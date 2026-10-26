<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filing of Documents | People360</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">

    @include('layouts.sidebar')

    <main class="flex-1 p-6">
        @include('layouts.header')

        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mt-4">
            <h2 class="text-xl font-semibold text-slate-800">Filing of Documents</h2>
            <div class="flex flex-wrap gap-2">
                <button class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#fileLeaveModal">
                    <span class="me-2">🗓️</span> File Leave
                </button>
                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#fileCorrectionModal">
                    <span class="me-2">✏️</span> File Correction
                </button>
                <button class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#fileOvertimeModal">
                    <span class="me-2">⏰</span> File Overtime
                </button>
            </div>
        </div>

        <div class="flex gap-3 items-end mt-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Start Date</label>
                <input type="date" id="startDate" class="border rounded px-2 py-1">
            <div class="modal fade" id="viewModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Document No.: <span id="modalUniqueId"></span> </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <p><strong>Employee Name:</strong> <span id="modalEmployee"></span></p>
                            <p><strong>Document Type:</strong> <span id="modalDocType"></span></p>
                            <p><strong>Date:</strong> <span id="modalDate"></span></p>
                            <hr>

                            <!-- Dynamic content -->
                            <div id="modalExtraDetails"></div>

                            </div>
                            <div class="modal-footer flex flex-col items-start gap-2">

                                <div id="rejectReasonContainer" class="w-full hidden">
                                    <label class="text-sm font-medium">Reject Reason</label>
                                    <textarea id="rejectReasonInline" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="flex gap-2 w-full justify-end">
                                    <button id="retractDoc" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Retract
                                    </button>

                                    <button id="closeDoc" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>

                            </div>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                    
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Documents</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <textarea id="rejectReason" class="form-control" placeholder="Enter reason..." rows="3"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmReject" class="btn btn-danger">Reject</button>
                    </div>

                    </div>
                </div>
            </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">End Date</label>
                <input type="date" id="endDate" class="border rounded px-2 py-1">
            </div>

            <button id="filterBtn" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                <span class="me-2">🔎</span> Load Documents
            </button>
        </div>

        <div class="flex gap-2 mt-3">
            <button id="cutoff1" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                <span class="me-2">📅</span> 26 prev - 10 current
            </button>
            <button id="cutoff2" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                <span class="me-2">📅</span> 11 - 25
            </button>
            <button id="thisMonth" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                <span class="me-2">📅</span> Full Month
            </button>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <table id="documentsTable" class="table table-bordered table-striped w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Employee Name</th>
                            <th>Document Type</th>
                            <th>Date Filed</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="fileLeaveModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md" style="max-width: 720px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">File Leave</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="leaveForm">
                            <div class="alert alert-light border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Remaining Leave Credits</span>
                                    <span class="badge bg-primary-subtle text-primary">{{ $remainingLeaves ?? '—' }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date of Filing</label>
                                <input type="date" id="leaveDateFiled" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Date From</label>
                                    <input type="date" id="leaveDateFrom" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date To</label>
                                    <input type="date" id="leaveDateTo" class="form-control" required>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label">Leave Type</label>
                                    <select id="leaveType" class="form-select">
                                        <option value="Vacation">Vacation</option>
                                        <option value="Sick">Sick</option>
                                        <option value="Maternity">Maternity</option>
                                        <option value="Paternity">Paternity</option>
                                        <option value="Birthday">Birthday</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Reason</label>
                                    <textarea id="leaveReason" class="form-control" rows="3" placeholder="State your reason" required></textarea>
                                </div>
                            </div>

                            <div id="leaveLocationGroup" class="mt-3 d-none">
                                <label class="form-label">Location</label>
                                <input type="text" id="leaveLocation" class="form-control" placeholder="Enter location">
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-2 fs-5">
                                        <input class="form-check-input" type="checkbox" id="deductLeave">
                                        <label class="form-check-label" for="deductLeave">Deduct on Leave Credits</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="leaveDaysGroup" class="d-none">
                                        <label class="form-label">No. of Days</label>
                                        <input type="number" id="leaveDays" class="form-control" step="0.1" min="0.1" placeholder="e.g. 0.5">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-2 fs-5">
                                        <input class="form-check-input" type="checkbox" id="halfDayLeave">
                                        <label class="form-check-label" for="halfDayLeave">Half day leave</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="halfDayGroup" class="d-none">
                                        <label class="form-label">AM/PM</label>
                                        <select id="halfDayPeriod" class="form-select">
                                            <option value="">Select period</option>
                                            <option value="AM">AM</option>
                                            <option value="PM">PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3" form="leaveForm">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="fileCorrectionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md" style="max-width: 720px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">File Correction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="correctionForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Date of Filing</label>
                                    <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Attendance Date</label>
                                    <input type="date" class="form-control" required>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label">Correction Type</label>
                                    <select id="correctionType" class="form-select">
                                        <option value="Time In">Time In</option>
                                        <option value="Time Out">Time Out</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Reason</label>
                                    <textarea class="form-control" rows="3" placeholder="Explain the correction" required></textarea>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div id="correctionTimeInGroup" class="col-md-6 d-none">
                                    <label class="form-label">Time In</label>
                                    <input type="time" id="correctionTimeIn" class="form-control" required>
                                </div>
                                <div id="correctionTimeOutGroup" class="col-md-6 d-none">
                                    <label class="form-label">Time Out</label>
                                    <input type="time" id="correctionTimeOut" class="form-control" >
                                </div>
                            </div>

                            <div class="form-check form-switch mt-2 fs-5">
                                <input class="form-check-input" type="checkbox" id="nextDayTimeOut">
                                <label class="form-check-label" for="nextDayTimeOut">Next Day Time Out</label>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3" form="correctionForm">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="fileOvertimeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md" style="max-width: 720px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">File Overtime</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="overtimeForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Date of Filing</label>
                                    <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Overtime Date</label>
                                    <input type="date" class="form-control" required>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label">Time From</label>
                                    <input type="time" id="overtimeTimeFrom" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Time To</label>
                                    <input type="time" id="overtimeTimeTo" class="form-control" required>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label">Hours</label>
                                    <input type="number" id="overtimeHours" class="form-control" step="0.25" min="0.25" placeholder="e.g. 2.5">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Reason</label>
                                    <textarea class="form-control" rows="3" placeholder="Explain the overtime request" required></textarea>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-2 fs-5">
                                        <input class="form-check-input" type="checkbox" id="overnightOt" >
                                        <label class="form-check-label ms-2" for="overnightOt">Overnight OT</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-2 fs-5">
                                        <input class="form-check-input" type="checkbox" id="flexiHours" >
                                        <label class="form-check-label" for="flexiHours">Flexi Hours</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3" form="overtimeForm">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h6 class="modal-title">Review Details</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-2">
                        <p class="small text-muted mb-2">Please review your submission before continuing.</p>
                        <div id="reviewSummary" class="small"></div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmReviewSubmit" class="btn btn-outline-primary btn-sm rounded-pill px-3">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmRetract" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h6 class="modal-title">Retract Submission</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-2">
                        <p class="small text-muted mb-2">Are you sure you want to retract this submission?</p>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmRetractSubmit" class="btn btn-outline-primary btn-sm rounded-pill px-3">Retract</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            const dept = "{{ $dept ?? 'all' }}".trim() || 'all';
            const usertype = "{{ $usertype ?? '' }}".trim();
            const guid = "{{ $guid ?? '' }}".trim();
            const baseUrl = "{{ url('/payroll/filing-documents') }}";
            let documentsTable = null;

            const toggleLeaveFields = function () {
                const isVacation = $('#leaveType').val() === 'Vacation';
                $('#leaveLocationGroup').toggleClass('d-none', !isVacation);
                $('#leaveDaysGroup').toggleClass('d-none', !$('#deductLeave').is(':checked'));
                $('#halfDayGroup').toggleClass('d-none', !$('#halfDayLeave').is(':checked'));
            };

            const toggleCorrectionTimeFields = function () {
                const type = $('#correctionType').val();
                const showTimeIn = type === 'Time In' || type === 'Both';
                const showTimeOut = type === 'Time Out' || type === 'Both';
                const showNextDayOption = type === 'Time Out' || type === 'Both';

                $('#correctionTimeInGroup').toggleClass('d-none', !showTimeIn);
                $('#correctionTimeOutGroup').toggleClass('d-none', !showTimeOut);
                $('#nextDayTimeOut').closest('.form-check').toggleClass('d-none', !showNextDayOption);
            };

            const calculateOvertimeHours = function () {
                const timeFrom = $('#overtimeTimeFrom').val();
                const timeTo = $('#overtimeTimeTo').val();

                if (!timeFrom || !timeTo) {
                    $('#overtimeHours').val('');
                    return;
                }

                const [fromHours, fromMinutes] = timeFrom.split(':').map(Number);
                const [toHours, toMinutes] = timeTo.split(':').map(Number);
                const startMinutes = fromHours * 60 + fromMinutes;
                const endMinutes = toHours * 60 + toMinutes;
                const diffMinutes = endMinutes - startMinutes;

                if (diffMinutes <= 0) {
                    $('#overtimeHours').val('');
                    return;
                }

                const hours = (diffMinutes / 60).toFixed(2);
                $('#overtimeHours').val(hours);
            };

            function formatDateValue(value) {
                if (!value) return '-';
                const date = new Date(value);
                if (Number.isNaN(date.getTime())) return value;
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            function normalizeDocument(row) {
                if (!row || typeof row !== 'object') {
                    return {
                        empName: '-',
                        documentType: 'Document',
                        dateFiled: '-',
                        status: 'Pending',
                        remarks: '—',
                        action: 'View'
                    };
                }
                const employeeName = row.employeeName || row.employeeName || row.name || '—';
                const docType = row.docType || row.otType || row.leaveType || row.documentType || row.type || 'Document';
                const status = row.isApproved === true ? 'Approved' : (row.isApproved === false ? 'Pending' : 'Pending');
                const filedDate = row.date || row.otDate || row.dateFrom || row.dateTo || row.correctDate || row.finalDate || '-';
                const remarks = row.reason || row.approveRejectReason || row.leaveType || row.otType || row.correctTime || row.comment || '—';

                return {
                    ...row,
                    employeeName: employeeName,
                    documentType: docType,
                    dateFiled: formatDateValue(filedDate),
                    status: status,
                    remarks: remarks,
                    action: 'View'
                };
            }

            function flattenOwnDocuments(response) {
                if (!response) return [];

                if (Array.isArray(response)) {
                    console.log('Flattening array response:', response);
                    return response;
                }

                const containers = [];
                const preferredKeys = ['all', 'docs', 'ots', 'data'];

                preferredKeys.forEach((key) => {
                    if (Array.isArray(response[key])) {
                        containers.push(...response[key]);
                    }
                });

                if (containers.length) {
                    return containers;
                }

                const valueArrays = Object.values(response || {}).filter(Array.isArray);
                return valueArrays.flat().filter(Boolean);
            }

            function loadDocuments(startDate, endDate) {
                const url = `${baseUrl}/${dept}/${startDate}/${endDate}/${usertype}/${guid}`;

                if (documentsTable) {
                    documentsTable.destroy();
                }
                
                documentsTable = $('#documentsTable').DataTable({
                    ajax: {
                        url: url,
                        dataSrc: function (response) {
                            const rows = flattenOwnDocuments(response);
                            console.log('Fetched documents:', rows);
                            return rows.map(normalizeDocument);
                        }
                    },
                    columns: [
                        { data: 'employeeName' },
                        { data: 'documentType' },
                        { data: 'dateFiled' },
                        { data: 'status' },
                        { data: 'remarks' },
                        {
                            data: 'action',
                            orderable: false,
                            render: function (data, type, row) {
                                    const payload = encodeURIComponent(JSON.stringify(row || {}));
                                    return `<button class="btn btn-outline-secondary btn-sm rounded-pill px-3 view-btn" data-row="${payload}" title="View"><span class="me-1">👁️</span>${data}</button>`;
                                }
                        }
                    ],
                    lengthChange: false,
                    paging: true,
                    searching: true,
                    order: [[1, 'desc']]
                });
            }

            $('#leaveType, #deductLeave, #halfDayLeave').on('change', toggleLeaveFields);
            $('#correctionType').on('change', toggleCorrectionTimeFields);
            $('#overtimeTimeFrom, #overtimeTimeTo').on('change keyup', calculateOvertimeHours);
            toggleLeaveFields();
            toggleCorrectionTimeFields();

            function formatInputDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function buildCutoffRange(type) {
                const today = new Date();

                if (type === 'second') {
                    const start = new Date(today.getFullYear(), today.getMonth(), 11);
                    const end = new Date(today.getFullYear(), today.getMonth(), 25);
                    return {
                        startDate: formatInputDate(start),
                        endDate: formatInputDate(end)
                    };
                }

                const start = new Date(today.getFullYear(), today.getMonth() - 1, 26);
                const end = new Date(today.getFullYear(), today.getMonth(), 10);

                return {
                    startDate: formatInputDate(start),
                    endDate: formatInputDate(end)
                };
            }

            function getCurrentCutoffRange() {
                const today = new Date();
                const day = today.getDate();

                if (day > 15) {
                    return buildCutoffRange('second');
                }

                return buildCutoffRange('first');
            }

            function applyDateRange(startDate, endDate) {
                $('#startDate').val(startDate);
                $('#endDate').val(endDate);
            }
            const retractModalEl = document.getElementById('confirmRetract');
            const retractModal = (typeof bootstrap !== 'undefined' && retractModalEl)
                ? (bootstrap.Modal.getOrCreateInstance ? bootstrap.Modal.getOrCreateInstance(retractModalEl) : new bootstrap.Modal(retractModalEl))
                : null;
            let pendingRetractDocId = null;
            const reviewModalEl = document.getElementById('reviewModal');
            const reviewModal = (typeof bootstrap !== 'undefined' && reviewModalEl)
                ? (bootstrap.Modal.getOrCreateInstance ? bootstrap.Modal.getOrCreateInstance(reviewModalEl) : new bootstrap.Modal(reviewModalEl))
                : null;
            let pendingReviewFormId = null;
            
            const handleRetractSubmission = function () {
                if (!pendingRetractDocId) {
                    return;
                }

                if (retractModal) retractModal.hide();
                alert('Document ID ' + pendingRetractDocId + ' retracted successfully.');
                pendingRetractDocId = null;
            };

            const buildReviewSummary = function (formId) {
                const form = $('#' + formId);
                const summary = [];

                form.find('input:not([type="submit"]), select, textarea').each(function () {
                    const $field = $(this);
                    if ($field.closest('.d-none').length) {
                        return;
                    }

                    const label = $field.closest('.row, .mb-3, .mt-3, .form-check').find('label').first().text().trim();
                    if (!label) {
                        return;
                    }

                    const value = $field.is(':checkbox')
                        ? ($field.is(':checked') ? 'Yes' : 'No')
                        : $field.val();

                    if (value !== '' && value !== null) {
                        summary.push('<div><strong>' + label + '</strong>: ' + value + '</div>');
                    }
                });

                if (!summary.length) {
                    summary.push('<div>No details entered.</div>');
                }

                return summary.join('');
            };

            const openRetractModal = function (docId) {
                pendingRetractDocId = docId;
                if (retractModal && retractModal.show) retractModal.show();
            };

            // When user clicks Retract in the view modal, open the confirm modal
            $('#retractDoc').on('click', function (e) {
                e.preventDefault();
                // currentDocId is set when opening the view modal
                if (!currentDocId) {
                    alert('No document selected to retract');
                    return;
                }
                openRetractModal(currentDocId);
            });

            // Confirm retract submission
            $('#confirmRetractSubmit').on('click', function () {
                if (!pendingRetractDocId) {
                    alert('Nothing to retract');
                    return;
                }

                // Optionally send AJAX to server to retract the document
                const baseUrl = "{{ url('/payroll') }}";
                $.ajax({
                    url: `${baseUrl}/retract-document`,
                    method: 'POST',
                    data: {
                        id: pendingRetractDocId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        if (retractModal && retractModal.hide) retractModal.hide();
                        alert('Document retracted successfully.');
                        // refresh table if present
                        if (documentsTable && documentsTable.ajax) {
                            documentsTable.ajax.reload(null, false);
                        }
                        pendingRetractDocId = null;
                    },
                    error: function (err) {
                        alert('Failed to retract document.');
                        console.error(err);
                    }
                });
            });

            const openReviewModal = function (formId) {
                pendingReviewFormId = formId;
                $('#reviewSummary').html(buildReviewSummary(formId));
                if (reviewModal && reviewModal.show) reviewModal.show();
            };

            $('#leaveForm').on('submit', function (e) {
                e.preventDefault();
                openReviewModal('leaveForm');
            });

            $('#correctionForm').on('submit', function (e) {
                e.preventDefault();
                openReviewModal('correctionForm');
            });

            $('#overtimeForm').on('submit', function (e) {
                e.preventDefault();
                openReviewModal('overtimeForm');
            });

                $('#confirmReviewSubmit').on('click', function () {
                if (!pendingReviewFormId) {
                    return;
                }

                if (reviewModal) reviewModal.hide();
                const formLabel = pendingReviewFormId === 'leaveForm'
                    ? 'Leave'
                    : (pendingReviewFormId === 'correctionForm' ? 'Correction' : 'Overtime');
                alert(formLabel + ' submission confirmed.');
                pendingReviewFormId = null;
            });

            $('#filterBtn').on('click', function () {
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();

                if (!startDate || !endDate) {
                    alert('Please select start and end date');
                    return;
                }

                loadDocuments(startDate, endDate);
            });

            $('#cutoff1').on('click', function () {
                const { startDate, endDate } = buildCutoffRange('first');
                applyDateRange(startDate, endDate);
                loadDocuments(startDate, endDate);
            });

            $('#cutoff2').on('click', function () {
                const { startDate, endDate } = buildCutoffRange('second');
                applyDateRange(startDate, endDate);
                loadDocuments(startDate, endDate);
            });

            $('#thisMonth').on('click', function () {
                const today = new Date();
                const startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                const endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);

                const startValue = formatInputDate(startDate);
                const endValue = formatInputDate(endDate);

                applyDateRange(startValue, endValue);
                loadDocuments(startValue, endValue);
            });

            const { startDate, endDate } = getCurrentCutoffRange();
            applyDateRange(startDate, endDate);
            loadDocuments(startDate, endDate);
        });

            const formatManilaTime = (value) => {
                if (!value) return '';

                const date = new Date(value);
                if (isNaN(date.getTime())) return value;

                return new Intl.DateTimeFormat('en-PH', {
                    timeZone: 'Asia/Manila',
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                }).format(date);
            };

            let currentDocId = null;
            let empKey = null;

            $('#documentsTable tbody').on('click', 'button.view-btn', function () {
                const payload = $(this).attr('data-row') || '';
                let data = {};
                try {
                    data = JSON.parse(decodeURIComponent(payload));
                } catch (err) {
                    console.error('Failed to parse row payload', err);
                }

                currentDocId = data.id || data.uniqueId || null;
                empKey = data.empKey || null;

                $('#modalEmployee').text(data.employeeName || data.name || '-');
                $('#modalDocType').text(data.docType || data.documentType || data.type || '-');
                $('#modalUniqueId').text(data.uniqueId || data.id || '-');
                $('#modalDate').text(data.date || data.dateFiled || data.otDate || '-');

                // Show Retract button only when document is not approved / still pending
                try {
                    const isApproved = data.isApproved === true || (data.status && String(data.status).toLowerCase() === 'approved');
                    $('#retractDoc').toggle(!isApproved);
                } catch (e) {
                    console.error('Error checking approval status for retract button', e);
                    $('#retractDoc').show();
                }

                let extraHtml = '';
                const docType = (data.docType || data.documentType || '').toLowerCase();

                if (docType === 'leave') {
                    extraHtml = `
                        <p><strong>Date From:</strong> ${data.dateFrom || data.dateFrom || ''}</p>
                        <p><strong>Date To:</strong> ${data.dateTo || data.dateTo || ''}</p>
                        <p><strong>Reason:</strong> ${data.reason || ''}</p>
                        <p><strong>No. of days:</strong> ${data.noOfDay || data.noOfDays || data.days || ''}</p>
                        <p><strong>Is Half Day:</strong> ${data.isHalfday}</p>
                        <p><strong>Deduct to leave credits:</strong> ${data.deductLeave || ''}</p>
                    `;
                } else if (docType === 'overtime') {
                    extraHtml = `
                        <p><strong>OT Date:</strong> ${formatManilaTime(data.otDate)}</p>
                        <p><strong>Start Time:</strong> ${formatManilaTime(data.otfrom)}</p>
                        <p><strong>End Time:</strong> ${formatManilaTime(data.otTo || data.otto)}</p>
                        <p><strong>Reason:</strong> ${data.reason || ''}</p>
                    `;
                } else if (docType === 'correction' || docType === 'ob' || docType === 'corrections') {
                    if (data.correctBothTime && data.correctBothTime !== '') {
                        extraHtml = `
                            <p><strong>Corrected Date:</strong> ${data.correctDate || ''}</p>
                            <p><strong>Corrected Time:</strong> ${data.correctTime || ''}</p>
                            <p><strong>Corrected Time Out:</strong> ${data.correctBothTime || ''}</p>
                            <p><strong>Reason:</strong> ${data.reason || ''}</p>
                        `;
                    } else {
                        extraHtml = `
                            <p><strong>Corrected Date:</strong> ${data.correctDate || ''}</p>
                            <p><strong>Corrected Time:</strong> ${data.correctTime || ''}</p>
                            <p><strong>Is Out:</strong> ${data.isOut || ''}</p>
                            <p><strong>Reason:</strong> ${data.reason || ''}</p>
                        `;
                    }
                } else {
                    // fallback: list a few common fields
                    extraHtml = `
                        <p><strong>Remarks:</strong> ${data.remarks || data.reason || ''}</p>
                    `;
                }

                $('#modalExtraDetails').html(extraHtml);
                const modalEl = document.getElementById('viewModal');
                if (typeof bootstrap !== 'undefined' && modalEl) {
                    const instance = bootstrap.Modal.getOrCreateInstance ? bootstrap.Modal.getOrCreateInstance(modalEl) : new bootstrap.Modal(modalEl);
                    if (instance && instance.show) instance.show();
                } else if (modalEl) {
                    // fallback to jQuery if bootstrap JS not available yet
                    $(modalEl).modal('show');
                }
            });
    </script>
</body>
</html>
