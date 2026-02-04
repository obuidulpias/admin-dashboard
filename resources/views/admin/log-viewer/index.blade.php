@extends('layouts.admin')

@section('main-content')

<div class="log-viewer-container" style="height: calc(100vh - 100px); display: flex; flex-direction: column;">
    <!-- Top Navigation Bar -->
    <div class="log-viewer-header" style="background: #fff; border-bottom: 1px solid #dee2e6; padding: 6px 12px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('home') }}" style="color: #495057; text-decoration: none; font-size: 13px;">
                <i class="fas fa-arrow-left"></i> Back to Laravel
            </a>
            <h3 style="margin: 0; display: flex; align-items: center; gap: 6px; font-size: 16px;">
                <i class="fab fa-github" style="font-size: 14px;"></i> Log Viewer
            </h3>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            @if($file && isset($statistics['by_level']) && !empty($statistics['by_level']))
                <div style="position: relative;">
                    <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle" 
                                style="background: #f8f9fa; border: 1px solid #dee2e6; cursor: pointer; padding: 4px 8px; font-size: 12px;" 
                                type="button" 
                                id="levelFilterDropdown" 
                                data-toggle="dropdown" 
                                aria-haspopup="true" 
                                aria-expanded="false">
                            @php
                                $selectedLevels = $filters['levels'] ?? [];
                                $levelCounts = [];
                                $totalCount = 0;
                                $selectedCount = 0;
                                foreach($statistics['by_level'] as $level => $count) {
                                    $levelCounts[] = ['level' => $level, 'count' => $count];
                                    $totalCount += $count;
                                    if(empty($selectedLevels) || in_array($level, $selectedLevels)) {
                                        $selectedCount += $count;
                                    }
                                }
                                if(empty($selectedLevels)) {
                                    $summary = $totalCount . ' entries in ' . $levelCounts[0]['count'] . ' ' . $levelCounts[0]['level'];
                                    if(count($levelCounts) > 1) {
                                        $summary .= ' + ' . (count($levelCounts) - 1) . ' more';
                                    }
                                } else {
                                    $firstLevel = array_filter($levelCounts, function($l) use ($selectedLevels) { return in_array($l['level'], $selectedLevels); });
                                    $firstLevel = reset($firstLevel);
                                    $summary = $selectedCount . ' entries in ' . $firstLevel['count'] . ' ' . $firstLevel['level'];
                                    $remaining = count(array_filter($levelCounts, function($l) use ($selectedLevels) { return in_array($l['level'], $selectedLevels); })) - 1;
                                    if($remaining > 0) {
                                        $summary .= ' + ' . $remaining . ' more';
                                    }
                                }
                            @endphp
                            {{ $summary }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="levelFilterDropdown" style="padding: 10px; min-width: 250px;">
                            <form method="GET" action="{{ route('log-viewer.index') }}" id="levelFilterForm">
                                <input type="hidden" name="file" value="{{ $file }}">
                                <input type="hidden" name="search" value="{{ $filters['search'] ?? '' }}">
                                <div style="margin-bottom: 10px;">
                                    <a href="#" onclick="event.preventDefault(); selectAllLevels();" style="font-size: 12px; color: #6c757d; text-decoration: none; margin-right: 10px;">Select all</a>
                                    <a href="#" onclick="event.preventDefault(); deselectAllLevels();" style="font-size: 12px; color: #6c757d; text-decoration: none;">Deselect all</a>
                                </div>
                                @php
                                    $levelOrder = ['EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR', 'WARNING', 'NOTICE', 'INFO', 'DEBUG'];
                                    $levelStats = $statistics['by_level'] ?? [];
                                @endphp
                                @foreach($levelOrder as $level)
                                    @if(isset($levelStats[$level]))
                                        <div style="padding: 5px 0;">
                                            <label style="display: flex; align-items: center; cursor: pointer; margin: 0;">
                                                <input type="checkbox" 
                                                       name="levels[]" 
                                                       value="{{ $level }}" 
                                                       class="level-checkbox"
                                                       {{ (empty($selectedLevels) || in_array($level, $selectedLevels)) ? 'checked' : '' }}
                                                       style="margin-right: 10px;">
                                                <span style="flex: 1; font-size: 14px;">{{ $level }}</span>
                                                <span style="font-size: 12px; color: #6c757d;">{{ $levelStats[$level] }} {{ $levelStats[$level] == 1 ? 'entry' : 'entries' }}</span>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #dee2e6;">
                                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">Apply Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
            <form method="GET" action="{{ route('log-viewer.index') }}" style="display: flex; align-items: center; gap: 10px;">
                <input type="hidden" name="file" value="{{ $file }}">
                @if(!empty($filters['levels']))
                    @foreach($filters['levels'] as $level)
                        <input type="hidden" name="levels[]" value="{{ $level }}">
                    @endforeach
                @endif
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                       placeholder="Search in '{{ $file }}' →" 
                       style="padding: 4px 8px; border: 1px solid #dee2e6; border-radius: 3px; width: 200px; font-size: 12px;">
                <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                    <i class="fas fa-search" style="font-size: 14px;"></i>
                </button>
            </form>
            <a href="{{ route('log-viewer.index', ['file' => $file]) }}" style="color: #495057; text-decoration: none; font-size: 14px;" title="Refresh">
                <i class="fas fa-sync-alt"></i>
            </a>
            <button style="background: none; border: none; cursor: pointer; font-size: 14px; color: #495057; padding: 0;" title="Settings">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div style="display: flex; flex: 1; overflow: hidden;">
        <!-- Left Sidebar - Log Files -->
        <div class="log-viewer-sidebar" style="width: 220px; background: #f8f9fa; border-right: 1px solid #dee2e6; display: flex; flex-direction: column; overflow: hidden;">
            <div style="padding: 8px 12px; border-bottom: 1px solid #dee2e6;">
                <h5 style="margin: 0 0 6px 0; font-size: 14px;">Log files on {{ ucfirst(config('app.env')) }}</h5>
                <select class="form-control form-control-sm" style="font-size: 12px; padding: 4px 8px;" onchange="window.location.href='{{ route('log-viewer.index') }}?sort=' + this.value">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest first</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest first</option>
                </select>
            </div>
            <div style="padding: 8px 12px; border-bottom: 1px solid #dee2e6;">
                <input type="text" class="form-control form-control-sm" placeholder="root" readonly style="font-size: 12px; padding: 4px 8px;">
            </div>
            <div style="flex: 1; overflow-y: auto; padding: 10px;">
                @forelse($logFiles as $logFile)
                    <div class="log-file-item" 
                         style="padding: 12px; margin-bottom: 5px; border-radius: 4px; cursor: pointer; 
                                {{ $file === $logFile['name'] ? 'background: #007bff; color: white;' : 'background: white;' }}"
                         onclick="window.location.href='{{ route('log-viewer.index', ['file' => $logFile['name']]) }}'">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1;">
                                <div style="font-weight: 500; font-size: 14px;">{{ $logFile['name'] }}</div>
                                <div style="font-size: 12px; opacity: 0.8; margin-top: 4px;">
                                    {{ app(\App\Services\LogViewerService::class)->formatFileSize($logFile['size']) }}
                                </div>
                            </div>
                            <div class="dropdown" style="position: relative;">
                                <button class="btn btn-sm" 
                                        style="background: none; border: none; color: inherit; padding: 0 5px;"
                                        onclick="event.stopPropagation(); toggleFileMenu('{{ md5($logFile['name']) }}', event)">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div id="menu-{{ md5($logFile['name']) }}" 
                                     class="dropdown-menu log-viewer-menu" 
                                     style="display: none; position: absolute; left: 0; top: 100%; z-index: 1000;">
                                    <a class="dropdown-item log-viewer-menu-item" href="#" 
                                       onclick="event.preventDefault(); clearIndex('{{ $logFile['name'] }}');">
                                        <i class="fas fa-database"></i>
                                        <span>Clear index</span>
                                    </a>
                                    <a class="dropdown-item log-viewer-menu-item" href="{{ route('log-viewer.download', $logFile['name']) }}">
                                        <i class="fas fa-cloud-download-alt"></i>
                                        <span>Download</span>
                                    </a>
                                    <a class="dropdown-item log-viewer-menu-item log-viewer-menu-item-danger" href="#" 
                                       onclick="event.preventDefault(); deleteFile('{{ $logFile['name'] }}');">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                    </a>
                                    <a class="dropdown-item log-viewer-menu-item log-viewer-menu-item-danger" href="#" 
                                       onclick="event.preventDefault(); showDeleteMultipleModal();">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Delete Multiple</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 20px; text-align: center; color: #6c757d;">
                        No log files found
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Main Panel - Log Entries -->
        <div class="log-viewer-main" style="flex: 1; display: flex; flex-direction: column; overflow: hidden; background: white;">
            @if($file)
                <div style="padding: 6px 12px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <select class="form-control form-control-sm" style="width: auto; padding: 4px 8px; font-size: 12px;" onchange="updateSort(this.value)">
                            <option value="newest" selected>Newest first</option>
                            <option value="oldest">Oldest first</option>
                        </select>
                        <select class="form-control form-control-sm" style="width: auto; padding: 4px 8px; font-size: 12px;" onchange="updatePerPage(this.value)">
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 items per page</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 items per page</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 items per page</option>
                        </select>
                    </div>
                </div>
                
                <div style="flex: 1; overflow-y: auto;">
                    <table class="table table-hover" style="margin: 0;">
                        <thead style="background: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th style="width: 120px;">Severity</th>
                                <th style="width: 180px;">Datetime</th>
                                <th style="width: 100px;">Env</th>
                                <th>Message</th>
                                <th style="width: 80px;">Line</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logData['entries'] ?? [] as $entry)
                                <tr class="log-entry-row" 
                                    data-line="{{ $entry['line'] }}"
                                    data-full-message="{{ htmlspecialchars($entry['message'] ?? '', ENT_QUOTES) }}"
                                    data-stack-trace="{{ htmlspecialchars($entry['stack_trace'] ?? '', ENT_QUOTES) }}"
                                    data-full="{{ htmlspecialchars($entry['full'] ?? '', ENT_QUOTES) }}"
                                    onclick="toggleLogEntry(this, event)"
                                    style="cursor: pointer; {{ in_array($entry['level'], ['ERROR', 'CRITICAL', 'EMERGENCY', 'ALERT']) ? 'background-color: #fff5f5;' : '' }}">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            @if($entry['level'] === 'EMERGENCY' || $entry['level'] === 'ALERT' || $entry['level'] === 'CRITICAL')
                                                <i class="fas fa-exclamation-circle" style="color: #dc3545;"></i>
                                            @elseif($entry['level'] === 'ERROR')
                                                <i class="fas fa-exclamation-circle" style="color: #dc3545;"></i>
                                                @if(!empty($entry['stack_trace']))
                                                    <i class="fas fa-chevron-right expand-chevron" style="color: #6c757d; font-size: 12px; transition: transform 0.3s;"></i>
                                                @endif
                                            @elseif($entry['level'] === 'WARNING')
                                                <i class="fas fa-exclamation-triangle" style="color: #ffc107;"></i>
                                            @elseif($entry['level'] === 'NOTICE')
                                                <i class="fas fa-info-circle" style="color: #17a2b8;"></i>
                                            @else
                                                <i class="fas fa-info-circle" style="color: #007bff;"></i>
                                            @endif
                                            <span style="font-size: 13px;">{{ $entry['level'] }}</span>
                                        </div>
                                    </td>
                                    <td style="font-size: 13px;">{{ $entry['datetime'] }}</td>
                                    <td>
                                        <span class="badge badge-secondary" style="font-size: 11px;">{{ $entry['environment'] }}</span>
                                    </td>
                                    <td>
                                        <div class="log-message-preview" style="font-size: 13px; max-width: 600px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ Str::limit($entry['message'], 80) }}
                                        </div>
                                        <div class="log-message-full" style="display: none; font-size: 13px; max-width: 100%; white-space: pre-wrap; word-wrap: break-word; padding: 15px; background: #f8f9fa; border-radius: 4px; margin-top: 10px; border-left: 3px solid #007bff;">
                                            <div style="font-family: 'Courier New', monospace; line-height: 1.8; color: #212529;">{{ $entry['message'] }}</div>
                                        </div>
                                    </td>
                                    <td style="text-align: right;" onclick="event.stopPropagation();">
                                        <a href="#" onclick="copyLogLink({{ $entry['line'] }}, event); return false;" 
                                           style="color: #6c757d; text-decoration: none;" title="Copy link">
                                            {{ $entry['line'] }} <i class="fas fa-link" style="font-size: 10px;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @if(!empty($entry['stack_trace']))
                                    <tr class="stack-trace-row" id="stack-trace-{{ $entry['line'] }}" style="display: none;">
                                        <td colspan="5" style="background: #f8f9fa; padding: 0;">
                                            <div style="padding: 15px; font-size: 12px; white-space: pre-wrap; word-wrap: break-word; font-family: 'Courier New', monospace; line-height: 1.8; color: #212529;">
                                                {{ $entry['stack_trace'] }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px; color: #6c757d;">
                                        No log entries found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(isset($logData['last_page']) && $logData['last_page'] > 1)
                    <div style="padding: 8px; border-top: 1px solid #dee2e6; display: flex; justify-content: center; gap: 2px; align-items: center;">
                        @php
                            $currentPage = $logData['current_page'];
                            $lastPage = $logData['last_page'];
                        @endphp
                        
                        <!-- Page 1 -->
                        @if($lastPage >= 1)
                            <a href="{{ route('log-viewer.index', array_merge(request()->all(), ['page' => 1])) }}" 
                               style="padding: 2px 6px; border: 1px solid #dee2e6; text-decoration: none; color: {{ $currentPage == 1 ? 'white' : '#495057' }}; 
                                      {{ $currentPage == 1 ? 'background: #007bff; border-color: #007bff;' : 'background: white;' }}
                                      border-radius: 2px; font-size: 11px; min-width: 24px; text-align: center; display: inline-block; line-height: 1.4;">
                                1
                            </a>
                        @endif
                        
                        <!-- Page 2 -->
                        @if($lastPage >= 2)
                            <a href="{{ route('log-viewer.index', array_merge(request()->all(), ['page' => 2])) }}" 
                               style="padding: 2px 6px; border: 1px solid #dee2e6; text-decoration: none; color: {{ $currentPage == 2 ? 'white' : '#495057' }}; 
                                      {{ $currentPage == 2 ? 'background: #007bff; border-color: #007bff;' : 'background: white;' }}
                                      border-radius: 2px; font-size: 11px; min-width: 24px; text-align: center; display: inline-block; line-height: 1.4;">
                                2
                            </a>
                        @endif
                        
                        <!-- Next Button -->
                        @if($currentPage < $lastPage)
                            <a href="{{ route('log-viewer.index', array_merge(request()->all(), ['page' => $currentPage + 1])) }}" 
                               style="padding: 2px 6px; border: 1px solid #dee2e6; text-decoration: none; color: #495057; background: white; 
                                      border-radius: 2px; font-size: 11px; min-width: 24px; text-align: center; display: inline-block; line-height: 1.4;">
                                <i class="fas fa-chevron-right" style="font-size: 9px;"></i>
                            </a>
                        @endif
                    </div>
                @endif
            @else
                <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #6c757d;">
                    <div style="text-align: center;">
                        <i class="fas fa-file-alt" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                        <p>Select a log file from the sidebar to view entries</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .log-viewer-container {
        margin: -20px;
    }
    
    .log-file-item:hover {
        opacity: 0.8;
    }
    
    .log-entry-row:hover {
        background-color: #f8f9fa !important;
    }
    
    .dropdown-menu {
        padding: 0;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    /* Log viewer menu - clean and proper styling */
    .log-viewer-menu {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        min-width: 200px;
        padding: 4px 0;
    }
    
    .log-viewer-menu .log-viewer-menu-item {
        padding: 10px 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: #495057;
        font-size: 13px;
        white-space: nowrap;
        transition: background-color 0.2s ease;
    }
    
    .log-viewer-menu .log-viewer-menu-item:hover {
        background-color: #f8f9fa;
        color: #495057;
    }
    
    .log-viewer-menu .log-viewer-menu-item i {
        width: 18px;
        text-align: center;
        color: #6c757d;
        flex-shrink: 0;
    }
    
    .log-viewer-menu .log-viewer-menu-item span {
        flex: 1;
        color: inherit;
    }
    
    .log-viewer-menu .log-viewer-menu-item-danger {
        color: #dc3545;
    }
    
    .log-viewer-menu .log-viewer-menu-item-danger:hover {
        background-color: #fff5f5;
        color: #dc3545;
    }
    
    .log-viewer-menu .log-viewer-menu-item-danger i {
        color: #dc3545;
    }
    
    .table td {
        vertical-align: middle;
        padding: 12px 8px;
    }
    
    .badge {
        padding: 4px 8px;
    }
    
    .expand-icon {
        cursor: pointer;
    }
    
    .stack-trace-row td {
        padding: 0 !important;
    }
    
    .log-entry-row.expanded {
        background-color: #fff !important;
    }
    
    .log-entry-row.expanded td {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .log-entry-row.expanded td:last-child {
        padding-bottom: 12px;
    }
    
    .log-message-full {
        animation: fadeIn 0.2s ease-in;
        margin-bottom: 0;
    }
    
    .stack-trace-row {
        background-color: #f8f9fa !important;
    }
    
    .stack-trace-row td {
        border-top: none !important;
        padding-top: 0 !important;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('scripts')
<script>
    // Toggle log entry expand/collapse - must be global
    window.toggleLogEntry = function(row, event) {
        // Don't trigger if clicking on links
        if (event && (event.target.tagName === 'A' || event.target.closest('a'))) {
            return;
        }
        
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const line = row.getAttribute('data-line');
        const preview = row.querySelector('.log-message-preview');
        const full = row.querySelector('.log-message-full');
        const stackTraceRow = document.getElementById('stack-trace-' + line);
        const chevron = row.querySelector('.expand-chevron');
        
        // Toggle expanded state
        if (row.classList.contains('expanded')) {
            // Collapse
            row.classList.remove('expanded');
            if (preview) preview.style.display = 'block';
            if (full) full.style.display = 'none';
            if (stackTraceRow) stackTraceRow.style.display = 'none';
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        } else {
            // Expand - show full message and stack trace
            row.classList.add('expanded');
            if (preview) preview.style.display = 'none';
            if (full) full.style.display = 'block';
            if (stackTraceRow) stackTraceRow.style.display = 'table-row';
            if (chevron) chevron.style.transform = 'rotate(90deg)';
        }
    }
    
    function toggleFileMenu(fileHash, event) {
        const menuId = 'menu-' + fileHash;
        const menu = document.getElementById(menuId);
        
        // Close all other menus
        document.querySelectorAll('.dropdown-menu').forEach(m => {
            if (m.id !== menuId) {
                m.style.display = 'none';
            }
        });
        
        // Toggle current menu
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        
        // Close on outside click
        setTimeout(() => {
            document.addEventListener('click', function closeMenu() {
                menu.style.display = 'none';
                document.removeEventListener('click', closeMenu);
            });
        }, 0);
    }
    
    function clearIndex(fileName) {
        if (confirm('Are you sure you want to clear the index for this log file?')) {
            fetch('{{ route("log-viewer.clear-index", ":file") }}'.replace(':file', fileName), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Index cleared successfully');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to clear index'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while clearing the index');
            });
        }
    }
    
    function deleteFile(fileName) {
        if (confirm('Are you sure you want to delete this log file?')) {
            window.location.href = '{{ route("log-viewer.delete", ":file") }}'.replace(':file', fileName);
        }
    }
    
    function showDeleteMultipleModal() {
        // Close all menus first
        document.querySelectorAll('.dropdown-menu').forEach(m => {
            m.style.display = 'none';
        });
        
        // Create modal HTML
        const modalHtml = `
            <div id="deleteMultipleModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; display: flex; align-items: center; justify-content: center;">
                <div style="background: white; border-radius: 8px; padding: 20px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;">
                    <h4 style="margin: 0 0 15px 0;">Delete Multiple Log Files</h4>
                    <p style="margin: 0 0 15px 0; color: #6c757d;">Select log files to delete:</p>
                    <div id="fileList" style="max-height: 300px; overflow-y: auto; margin-bottom: 15px;">
                        @foreach($logFiles as $logFile)
                            <label style="display: flex; align-items: center; padding: 8px; cursor: pointer; border-bottom: 1px solid #f0f0f0;">
                                <input type="checkbox" name="files[]" value="{{ $logFile['name'] }}" style="margin-right: 10px;">
                                <span style="flex: 1;">{{ $logFile['name'] }}</span>
                                <span style="color: #6c757d; font-size: 12px;">{{ app(\App\Services\LogViewerService::class)->formatFileSize($logFile['size']) }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button onclick="closeDeleteMultipleModal()" style="padding: 8px 16px; border: 1px solid #dee2e6; background: white; border-radius: 4px; cursor: pointer;">Cancel</button>
                        <button onclick="confirmDeleteMultiple()" style="padding: 8px 16px; border: none; background: #dc3545; color: white; border-radius: 4px; cursor: pointer;">Delete Selected</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }
    
    function closeDeleteMultipleModal() {
        const modal = document.getElementById('deleteMultipleModal');
        if (modal) {
            modal.remove();
        }
    }
    
    function confirmDeleteMultiple() {
        const checkboxes = document.querySelectorAll('#fileList input[type="checkbox"]:checked');
        const files = Array.from(checkboxes).map(cb => cb.value);
        
        if (files.length === 0) {
            alert('Please select at least one file to delete');
            return;
        }
        
        if (confirm(`Are you sure you want to delete ${files.length} file(s)?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("log-viewer.delete-multiple") }}';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            files.forEach(file => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'files[]';
                input.value = file;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function selectAllLevels() {
        document.querySelectorAll('.level-checkbox').forEach(cb => {
            cb.checked = true;
        });
    }
    
    function deselectAllLevels() {
        document.querySelectorAll('.level-checkbox').forEach(cb => {
            cb.checked = false;
        });
    }
    
    function updateSort(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', value);
        window.location.href = url.toString();
    }
    
    function updatePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }
    
    function copyLogLink(line, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        const url = window.location.href.split('?')[0] + '?file={{ $file }}&line=' + line;
        navigator.clipboard.writeText(url).then(() => {
            alert('Link copied to clipboard!');
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });
</script>
@endsection
