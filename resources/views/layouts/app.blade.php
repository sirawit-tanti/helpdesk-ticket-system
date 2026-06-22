<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Helpdesk Ticket System')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="bg-light">
    @auth
    <nav class="navbar navbar-expand-lg app-navbar">
        <div class="container-fluid">
            <button class="btn app-mobile-menu-btn d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <i class="bi bi-list"></i>
            </button>

            <a class="navbar-brand app-navbar-brand" href="{{ route('dashboard') }}">
                <span class="app-brand-mark" aria-hidden="true">
                    <!-- <span class="app-brand-ticket"></span> -->
                    <i class="bi bi-headset"></i>
                    <span class="app-brand-dot"></span>
                </span>

                <span class="app-brand-text">
                    Helpdesk
                </span>
            </a>

            <div class="ms-auto d-flex align-items-center gap-2 gap-md-3">
                <a href="{{ route('profile.edit') }}" class="app-mobile-user-chip d-md-none" title="Profile"
                    aria-label="Profile">
                    <span class="app-mobile-user-avatar app-user-avatar-{{ auth()->user()->role?->name ?? 'user' }}">
                        {{ auth()->user()->initial }}
                    </span>

                    <span class="app-mobile-user-name">
                        {{ auth()->user()->name }}
                    </span>
                </a>

                <div class="app-user-info d-none d-md-flex">
                    <div class="app-user-avatar app-user-avatar-{{ auth()->user()->role?->name ?? 'user' }}">
                        {{ auth()->user()->initial }}
                    </div>

                    <div class="app-user-meta">
                        <div class="app-user-name">
                            {{ auth()->user()->name }}
                        </div>

                        <div class="app-user-role">
                            <span class="app-role-chip app-role-chip-{{ auth()->user()->role?->name ?? 'user' }}">
                                {{ auth()->user()->role?->display_name ?? 'User' }}
                            </span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="btn app-profile-btn d-none d-md-inline-flex">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mb-0 d-none d-md-block">
                    @csrf

                    <button type="submit" class="btn app-logout-btn">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start app-mobile-sidebar" tabindex="-1" id="mobileSidebar"
        aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileSidebarLabel">
                Helpdesk Menu
            </h5>

            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0">
            @include('layouts.partials.sidebar-menu', ['menuPrefix' => 'mobile'])
        </div>
    </div>
    @endauth

    <div class="container-fluid">
        <div class="row min-vh-100">
            @auth
            <aside class="col-lg-2 d-none d-lg-block app-sidebar">
                @include('layouts.partials.sidebar-menu', ['menuPrefix' => 'desktop'])
            </aside>
            @endauth

            <main class="{{ auth()->check() ? 'col-12 col-lg-10 app-main-content' : 'col-12 app-guest-content' }}">
                @yield('content')
            </main>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3 app-toast-container">
        @if(session('success'))
        <div class="toast app-toast app-toast-success" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="3500">
            <div class="toast-header">
                <span class="app-toast-icon">
                    <i class="bi bi-check-lg"></i>
                </span>

                <strong class="me-auto">Success</strong>

                <small>Now</small>

                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>

            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast app-toast app-toast-error" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="5000">
            <div class="toast-header">
                <span class="app-toast-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </span>

                <strong class="me-auto">Error</strong>

                <small>Now</small>

                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>

            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
        @endif
    </div>
    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content app-confirm-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmActionModalLabel">
                        Confirm Action
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-0" id="confirmActionModalMessage">
                        Are you sure you want to continue?
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button" class="btn btn-primary" id="confirmActionModalButton">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.querySelectorAll('.toast').forEach(function(toastElement) {
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
    });

    function showAppToast(message, type = 'success') {
        const toastContainer = document.querySelector('.app-toast-container');

        if (!toastContainer) {
            alert(message);
            return;
        }

        const toastElement = document.createElement('div');
        const isSuccess = type === 'success';

        toastElement.className = `toast app-toast ${isSuccess ? 'app-toast-success' : 'app-toast-error'}`;
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        toastElement.setAttribute('data-bs-delay', isSuccess ? '2500' : '4000');

        toastElement.innerHTML = `
        <div class="toast-header">
            <span class="app-toast-icon">
                <i class="bi ${isSuccess ? 'bi-check-lg' : 'bi-exclamation-triangle'}"></i>
            </span>

            <strong class="me-auto">${isSuccess ? 'Success' : 'Error'}</strong>

            <small>Now</small>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="toast"
                aria-label="Close"
            ></button>
        </div>

        <div class="toast-body">
            ${message}
        </div>
    `;

        toastContainer.appendChild(toastElement);

        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    document.querySelectorAll('[data-copy-text]').forEach(function(button) {
        button.addEventListener('click', async function() {
            const text = button.getAttribute('data-copy-text');
            const label = button.getAttribute('data-copy-label') || 'Copied';

            if (!text) {
                return;
            }

            try {
                await navigator.clipboard.writeText(text);

                const originalHtml = button.innerHTML;

                button.innerHTML = '<i class="bi bi-check-lg me-1"></i>Copied';
                button.disabled = true;

                setTimeout(function() {
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                }, 1400);

                showAppToast(label, 'success');
            } catch (error) {
                showAppToast('Unable to copy text', 'error');
            }
        });
    });

    document.querySelectorAll('[data-password-toggle]').forEach(function(button) {
        button.addEventListener('click', function() {
            const inputId = button.getAttribute('data-password-toggle');
            const input = document.getElementById(inputId);

            if (!input) {
                return;
            }

            const isPassword = input.getAttribute('type') === 'password';

            input.setAttribute('type', isPassword ? 'text' : 'password');
            button.textContent = isPassword ? 'Hide' : 'Show';
        });
    });

    const bulkActionForm = document.getElementById('bulkActionForm');
    const bulkActionSelect = document.getElementById('bulkActionSelect');
    const bulkActionSubmit = document.getElementById('bulkActionSubmit');
    const selectedTicketCount = document.getElementById('selectedTicketCount');
    const selectAllTickets = document.getElementById('selectAllTickets');

    function updateBulkActionState() {
        if (!bulkActionForm) {
            return;
        }

        const checkedTickets = document.querySelectorAll('.ticket-checkbox:checked');
        const selectedCount = checkedTickets.length;
        const hasAction = bulkActionSelect && bulkActionSelect.value !== '';

        if (selectedTicketCount) {
            selectedTicketCount.textContent = selectedCount;
        }

        if (bulkActionSubmit) {
            bulkActionSubmit.disabled = selectedCount === 0 || !hasAction;
        }
    }

    function refreshBulkTicketCheckboxes() {
        if (!bulkActionForm || !bulkActionSelect) {
            return;
        }

        const action = bulkActionSelect.value;
        const currentUserId = bulkActionForm.getAttribute('data-current-user-id');

        document.querySelectorAll('.ticket-checkbox').forEach(function(checkbox) {
            const assigneeId = checkbox.getAttribute('data-assignee-id');
            const isClosed = checkbox.getAttribute('data-is-closed') === '1';

            let shouldDisable = false;

            if (action === 'assign_to_me' && assigneeId === currentUserId) {
                shouldDisable = true;
            }

            if (action === 'close' && isClosed) {
                shouldDisable = true;
            }

            checkbox.disabled = shouldDisable;

            if (shouldDisable) {
                checkbox.checked = false;
            }
        });

        if (selectAllTickets) {
            selectAllTickets.checked = false;
        }

        updateBulkActionState();
    }

    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', refreshBulkTicketCheckboxes);
        refreshBulkTicketCheckboxes();
    }

    document.querySelectorAll('.ticket-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', updateBulkActionState);
    });

    if (selectAllTickets) {
        selectAllTickets.addEventListener('change', function() {
            document.querySelectorAll('.ticket-checkbox:not(:disabled)').forEach(function(checkbox) {
                checkbox.checked = selectAllTickets.checked;
            });

            updateBulkActionState();
        });
    }

    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function(event) {
            const checkedTickets = document.querySelectorAll('.ticket-checkbox:checked');
            const selectedCount = checkedTickets.length;
            const action = bulkActionSelect ? bulkActionSelect.value : '';

            if (selectedCount === 0) {
                event.preventDefault();
                alert('Please select at least one available ticket.');
                return;
            }

            if (!action) {
                event.preventDefault();
                alert('Please choose a bulk action.');
                return;
            }

            const actionLabel = action === 'assign_to_me' ?
                'assign selected ticket(s) to you' :
                'close selected ticket(s)';

            if (!confirm(`Are you sure you want to ${actionLabel}? (${selectedCount} selected)`)) {
                event.preventDefault();
            }
        });
    }

    document.querySelectorAll('[data-auto-submit]').forEach(function(element) {
        element.addEventListener('change', function() {
            element.closest('form')?.submit();
        });
    });

    document.querySelectorAll('form[data-loading-form]').forEach(function(form) {
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('[type="submit"]');

            if (!submitButton) {
                return;
            }

            if (submitButton.dataset.loading === 'true') {
                return;
            }

            submitButton.dataset.loading = 'true';
            submitButton.disabled = true;

            const originalText = submitButton.innerHTML;
            submitButton.dataset.originalText = originalText;

            submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
            Processing...
        `;
        });
    });
    document.querySelectorAll('.ticket-row[data-href]').forEach(function(row) {
        row.addEventListener('click', function(event) {
            const ignoredSelector =
                'a, button, input, select, textarea, label, form, .ticket-row-actions';

            if (event.target.closest(ignoredSelector)) {
                return;
            }

            window.location.href = row.getAttribute('data-href');
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const confirmModalElement = document.getElementById('confirmActionModal');
        const confirmTitle = document.getElementById('confirmActionModalLabel');
        const confirmMessage = document.getElementById('confirmActionModalMessage');
        const confirmButton = document.getElementById('confirmActionModalButton');

        if (confirmModalElement && confirmTitle && confirmMessage && confirmButton) {
            const confirmModal = new bootstrap.Modal(confirmModalElement);
            let selectedForm = null;

            document.querySelectorAll('[data-confirm-action]').forEach(function(button) {
                button.addEventListener('click', function() {
                    selectedForm = button.closest('form');

                    confirmTitle.textContent = button.dataset.confirmTitle || 'Confirm Action';
                    confirmMessage.textContent = button.dataset.confirmMessage ||
                        'Are you sure you want to continue?';
                    confirmButton.textContent = button.dataset.confirmButton || 'Confirm';

                    confirmButton.className = 'btn ' + (button.dataset.confirmClass ||
                        'btn-primary');

                    confirmModal.show();
                });
            });

            confirmButton.addEventListener('click', function() {
                if (!selectedForm) {
                    return;
                }

                confirmButton.disabled = true;
                confirmButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                    Processing...
                `;

                selectedForm.submit();
            });

            confirmModalElement.addEventListener('hidden.bs.modal', function() {
                selectedForm = null;
                confirmButton.disabled = false;
                confirmButton.textContent = 'Confirm';
                confirmButton.className = 'btn btn-primary';
            });
        }
        const commentTypeToggle = document.querySelector('[data-comment-type-toggle]');
        const internalNoteAlert = document.querySelector('[data-internal-note-alert]');
        const commentInput = document.querySelector('[data-comment-input]');
        const commentHint = document.querySelector('[data-comment-hint]');
        const commentSubmit = document.querySelector('[data-comment-submit]');

        if (commentTypeToggle && commentInput && commentHint && commentSubmit) {
            const updateCommentType = function() {
                if (commentTypeToggle.checked) {
                    internalNoteAlert?.classList.remove('d-none');
                    commentInput.placeholder = 'Write an internal note...';
                    commentHint.textContent = 'Internal notes are hidden from requesters.';
                    commentSubmit.innerHTML = '<i class="bi bi-shield-lock me-1"></i> Add Internal Note';
                    commentSubmit.classList.remove('btn-primary');
                    commentSubmit.classList.add('btn-warning');
                } else {
                    internalNoteAlert?.classList.add('d-none');
                    commentInput.placeholder = 'Write your reply...';
                    commentHint.textContent =
                        'Public replies are visible to the requester and support team.';
                    commentSubmit.innerHTML = '<i class="bi bi-send me-1"></i> Send Reply';
                    commentSubmit.classList.remove('btn-warning');
                    commentSubmit.classList.add('btn-primary');
                }
            };

            commentTypeToggle.addEventListener('change', updateCommentType);
            updateCommentType();
        }

        const fileInput = document.getElementById('attachments');
        const selectedFileCount = document.getElementById('selectedFileCount');
        const selectedFileTitle = document.getElementById('selectedFileTitle');
        const selectedFileList = document.getElementById('selectedFileList');

        if (fileInput && selectedFileCount && selectedFileTitle && selectedFileList) {
            const selectedFilesStore = new DataTransfer();

            const formatFileSize = function(bytes) {
                const sizeKb = bytes / 1024;

                if (sizeKb >= 1024) {
                    return `${(sizeKb / 1024).toFixed(2)} MB`;
                }

                return `${sizeKb.toFixed(2)} KB`;
            };

            const renderSelectedFiles = function() {
                const files = Array.from(selectedFilesStore.files);

                selectedFileCount.textContent = `${files.length} file(s)`;

                if (files.length === 0) {
                    selectedFileTitle.textContent = 'No files selected';
                    selectedFileList.classList.add('d-none');
                    selectedFileList.innerHTML = '';
                    fileInput.files = selectedFilesStore.files;
                    return;
                }

                selectedFileTitle.textContent = files.length === 1 ?
                    files[0].name :
                    `${files.length} files selected`;

                selectedFileList.classList.remove('d-none');

                selectedFileList.innerHTML = files.map(function(file, index) {
                    return `
                <div class="selected-file-item">
                    <div class="selected-file-icon">
                        <i class="bi bi-file-earmark"></i>
                    </div>

                    <div class="selected-file-content">
                        <div class="selected-file-name">${file.name}</div>
                        <div class="selected-file-size">${formatFileSize(file.size)}</div>
                    </div>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger selected-file-remove"
                        data-remove-file-index="${index}"
                    >
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            `;
                }).join('');

                fileInput.files = selectedFilesStore.files;
            };

            fileInput.addEventListener('change', function() {
                const newFiles = Array.from(fileInput.files || []);

                newFiles.forEach(function(newFile) {
                    const isDuplicate = Array.from(selectedFilesStore.files).some(function(
                        existingFile) {
                        return existingFile.name === newFile.name &&
                            existingFile.size === newFile.size &&
                            existingFile.lastModified === newFile.lastModified;
                    });

                    if (!isDuplicate) {
                        selectedFilesStore.items.add(newFile);
                    }
                });

                renderSelectedFiles();
            });

            selectedFileList.addEventListener('click', function(event) {
                const removeButton = event.target.closest('[data-remove-file-index]');

                if (!removeButton) {
                    return;
                }

                const removeIndex = Number(removeButton.dataset.removeFileIndex);
                const files = Array.from(selectedFilesStore.files);

                selectedFilesStore.items.clear();

                files.forEach(function(file, index) {
                    if (index !== removeIndex) {
                        selectedFilesStore.items.add(file);
                    }
                });

                renderSelectedFiles();
            });
        }
        const activityFilterButtons = document.querySelectorAll('[data-activity-filter]');
        const activityItems = document.querySelectorAll('[data-activity-item]');
        const activityEmpty = document.querySelector('[data-activity-empty]');

        if (activityFilterButtons.length && activityItems.length) {
            activityFilterButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const selectedType = button.dataset.activityFilter;
                    let visibleCount = 0;

                    activityFilterButtons.forEach(function(item) {
                        item.classList.remove('active');
                    });

                    button.classList.add('active');

                    activityItems.forEach(function(item) {
                        const itemType = item.dataset.activityType;

                        const shouldShow = selectedType === 'all' || itemType ===
                            selectedType;

                        item.classList.toggle('d-none', !shouldShow);

                        if (shouldShow) {
                            visibleCount++;
                        }
                    });

                    if (activityEmpty) {
                        activityEmpty.classList.toggle('d-none', visibleCount > 0);
                    }
                });
            });
        }
    });
    </script>
</body>

</html>