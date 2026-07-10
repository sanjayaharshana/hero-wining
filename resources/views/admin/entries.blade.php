<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flora Admin Panel</title>
    <style>
        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            background: #f5f6f7;
            font-family: Arial, Helvetica, sans-serif;
            color: #1a1a1a;
        }

        .wrap {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px 16px 60px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        h1 {
            font-size: 22px;
            margin: 0;
            color: #1a1a1a;
        }

        .count {
            color: #6b7280;
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 9px 16px;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: #009a4c;
            color: #fff;
            font-weight: bold;
        }

        .btn-primary:hover {
            background: #007a3d;
        }

        .btn-secondary {
            background: #ffffff;
            color: #1a1a1a;
            font-weight: bold;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #f3f4f6;
        }

        form.logout button {
            padding: 9px 16px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #374151;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        form.logout button:hover {
            background: #f3f4f6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            text-align: left;
            padding: 12px 14px;
            font-size: 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f3f4f6;
            color: #009a4c;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .empty {
            padding: 40px 20px;
            text-align: center;
            color: #6b7280;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #1a1a1a;
            text-decoration: none;
            font-size: 14px;
        }

        .pagination a:hover {
            background: #f3f4f6;
        }

        .pagination span.disabled {
            color: #9ca3af;
            border-color: #e5e7eb;
        }

        .pagination span.current {
            background: #009a4c;
            border-color: #009a4c;
            color: #fff;
            font-weight: bold;
        }

        .status {
            background: #ecfdf3;
            border: 1px solid #009a4c;
            color: #007a3d;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .delete-btn {
            padding: 7px 12px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #374151;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .delete-btn:hover {
            background: #1a1a1a;
            color: #fff;
            border-color: #1a1a1a;
        }

        @media (max-width: 640px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tr {
                margin-bottom: 12px;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                overflow: hidden;
            }

            td {
                border-bottom: 1px solid #e5e7eb;
            }

            td::before {
                content: attr(data-label);
                display: block;
                color: #009a4c;
                font-size: 11px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <div>
                <h1>Flora Admin Panel</h1>
                <div class="count">{{ $entries->total() }} total submissions</div>
            </div>
            <div class="header-actions">
                <a class="btn btn-secondary" href="{{ route('admin.entries.export') }}">Export CSV</a>
                <form class="logout" method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit">Log Out</button>
                </form>
            </div>
        </header>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($entries->isEmpty())
            <div class="empty">No entries submitted yet.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Stall Number</th>
                        <th>Favourite Dip</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $entry)
                        <tr>
                            <td data-label="Name">{{ $entry->name }}</td>
                            <td data-label="Stall Number">{{ $entry->stall_number }}</td>
                            <td data-label="Favourite Dip">{{ $entry->favourite_dip }}</td>
                            <td data-label="Submitted At">{{ $entry->created_at->format('Y-m-d H:i') }}</td>
                            <td data-label="Actions">
                                <form method="POST" action="{{ route('admin.entries.destroy', $entry) }}" onsubmit="return confirm('Delete this entry? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($entries->hasPages())
                <div class="pagination">
                    @if ($entries->onFirstPage())
                        <span class="disabled">&laquo; Prev</span>
                    @else
                        <a href="{{ $entries->previousPageUrl() }}">&laquo; Prev</a>
                    @endif

                    @for ($page = 1; $page <= $entries->lastPage(); $page++)
                        @if ($page === $entries->currentPage())
                            <span class="current">{{ $page }}</span>
                        @else
                            <a href="{{ $entries->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($entries->hasMorePages())
                        <a href="{{ $entries->nextPageUrl() }}">Next &raquo;</a>
                    @else
                        <span class="disabled">Next &raquo;</span>
                    @endif
                </div>
            @endif
        @endif
    </div>
</body>
</html>
