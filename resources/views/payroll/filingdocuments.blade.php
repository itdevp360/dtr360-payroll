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

        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <table id="documentsTable" class="table table-bordered table-striped w-100">
                    <thead class="table-light">
                        <tr>
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
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
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

            $('#leaveType, #deductLeave, #halfDayLeave').on('change', toggleLeaveFields);
            $('#correctionType').on('change', toggleCorrectionTimeFields);
            $('#overtimeTimeFrom, #overtimeTimeTo').on('change keyup', calculateOvertimeHours);
            toggleLeaveFields();
            toggleCorrectionTimeFields();

            const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
            let pendingReviewFormId = null;

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

            const openReviewModal = function (formId) {
                pendingReviewFormId = formId;
                $('#reviewSummary').html(buildReviewSummary(formId));
                reviewModal.show();
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

                reviewModal.hide();
                const formLabel = pendingReviewFormId === 'leaveForm'
                    ? 'Leave'
                    : (pendingReviewFormId === 'correctionForm' ? 'Correction' : 'Overtime');
                alert(formLabel + ' submission confirmed.');
                pendingReviewFormId = null;
            });

            const rows = [
                {
                    documentType: 'Leave',
                    dateFiled: '2026-07-25',
                    status: 'Pending',
                    remarks: 'Vacation leave request',
                    action: 'View'
                },
                {
                    documentType: 'Correction',
                    dateFiled: '2026-07-24',
                    status: 'Approved',
                    remarks: 'Time-in correction',
                    action: 'View'
                },
                {
                    documentType: 'Overtime',
                    dateFiled: '2026-07-23',
                    status: 'Pending',
                    remarks: 'Additional hours worked',
                    action: 'View'
                }
            ];

            $('#documentsTable').DataTable({
                data: rows,
                columns: [
                    { data: 'documentType' },
                    { data: 'dateFiled' },
                    { data: 'status' },
                    { data: 'remarks' },
                    {
                        data: 'action',
                        orderable: false,
                        render: function (data) {
                            return `<button class="btn btn-outline-secondary btn-sm rounded-pill px-3"><span class="me-1">👁️</span>${data}</button>`;
                        }
                    }
                ],
                lengthChange: false,
                paging: true,
                searching: true
            });
        });
    </script>
</body>
</html>
