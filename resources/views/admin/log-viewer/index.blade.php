@extends('layouts.admin')

@section('main-content')

<div class="log-viewer-container" style="height: calc(100vh - 120px); display: flex; flex-direction: column;">
    <!-- Top Navigation Bar -->
    <div class="log-viewer-header" style="background: #fff; border-bottom: 1px solid #dee2e6; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <a href="{{ route('home') }}" style="color: #495057; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to Laravel
            </a>
            <h3 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="fab fa-github"></i> Log Viewer
            </h3>
        </div>
        <div style="display: flex; align-items: center; gap: 15px;">
            @if($file && isset($statistics['by_level']) && !empty($statistics['by_level']))
                <div style="position: relative;">
                    <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle" 
                                style="background: #f8f9fa; border: 1px solid #dee2e6; cursor: pointer;" 
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
                       style="padding: 8px 12px; border: 1px solid #dee2e6; border-radius: 4px; width: 250px;">
                <button type="submit" style="background: none; border: none; cursor: pointer;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <a href="{{ route('log-viewer.index', ['file' => $file]) }}" style="color: #495057; text-decoration: none; font-size: 18px;" title="Refresh">
                <i class="fas fa-sync-alt"></i>
            </a>
            <button style="background: none; border: none; cursor: pointer; font-size: 18px; color: #495057;" title="Settings">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div style="display: flex; flex: 1; overflow: hidden;">
        <!-- Left Sidebar - Log Files -->
        <div class="log-viewer-sidebar" style="width: 300px; background: #f8f9fa; border-right: 1px solid #dee2e6; display: flex; flex-direction: column; overflow: hidden;">
            <div style="padding: 15px; border-bottom: 1px solid #dee2e6;">
                <h5 style="margin: 0 0 10px 0;">Log files on {{ ucfirst(config('app.env')) }}</h5>
                <select class="form-control form-control-sm" onchange="window.location.href='{{ route('log-viewer.index') }}?sort=' + this.value">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest first</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest first</option>
                </select>
            </div>
            <div style="padding: 15px; border-bottom: 1px solid #dee2e6;">
                <input type="text" class="form-control form-control-sm" placeholder="root" readonly>
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
                                        onclick="event.stopPropagation(); toggleFileMenu('{{ $logFile['name'] }}', event)">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div id="menu-{{ md5($logFile['name']) }}" 
                                     class="dropdown-menu" 
                                     style="display: none; position: absolute; right: 0; top: 100%; z-index: 1000; background: white; border: 1px solid #dee2e6; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); min-width: 150px;">
                                    <a class="dropdown-item" href="{{ route('log-viewer.download', $logFile['name']) }}" style="padding: 8px 15px; display: block; text-decoration: none; color: #495057;">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <a class="dropdown-item" href="#" 
                                       onclick="event.preventDefault(); if(confirm('Are you sure?')) { window.location.href='{{ route('log-viewer.delete', $logFile['name']) }}'; }"
                                       style="padding: 8px 15px; display: block; text-decoration: none; color: #dc3545;">
                                        <i class="fas fa-trash"></i> Delete
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
                <div style="padding: 15px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <select class="form-control form-control-sm" style="width: auto;" onchange="updateSort(this.value)">
                            <option value="newest" selected>Newest first</option>
                            <option value="oldest">Oldest first</option>
                        </select>
                        <select class="form-control form-control-sm" style="width: auto;" onchange="updatePerPage(this.value)">
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
                    <div style="padding: 15px; border-top: 1px solid #dee2e6; display: flex; justify-content: center; gap: 5px;">
                        @for($i = 1; $i <= $logData['last_page']; $i++)
                            <a href="{{ route('log-viewer.index', array_merge(request()->all(), ['page' => $i])) }}" 
                               style="padding: 8px 12px; border: 1px solid #dee2e6; text-decoration: none; color: #495057; 
                                      {{ $i == $logData['current_page'] ? 'background: #007bff; color: white; border-color: #007bff;' : 'background: white;' }}
                                      border-radius: 4px; margin: 0 2px;">
                                {{ $i }}
                            </a>
                        @endfor
                        @if($logData['current_page'] < $logData['last_page'])
                            <a href="{{ route('log-viewer.index', array_merge(request()->all(), ['page' => $logData['current_page'] + 1])) }}" 
                               style="padding: 8px 12px; border: 1px solid #dee2e6; text-decoration: none; color: #495057; background: white; border-radius: 4px; margin-left: 5px;">
                                <i class="fas fa-chevron-right"></i>
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
    
    function toggleFileMenu(fileName, event) {
        const menuId = 'menu-' + btoa(fileName).replace(/[^a-zA-Z0-9]/g, '');
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
