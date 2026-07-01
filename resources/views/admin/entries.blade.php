<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Raffle Entries - Hero | Abans Auto Grand Draw</title>
    <style>
        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            background: #0a0000;
            font-family: Arial, Helvetica, sans-serif;
            color: #fff;
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
        }

        .count {
            color: #d99;
            font-size: 14px;
        }

        nav {
            margin-top: 6px;
        }

        nav a {
            color: #ff9d9d;
            font-size: 13px;
            text-decoration: none;
        }

        nav a:hover {
            color: #fff;
        }

        form.logout button {
            padding: 9px 16px;
            border: 1px solid #5a1414;
            background: #260808;
            color: #ff9d9d;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        form.logout button:hover {
            background: #3a0d0d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #150505;
            border: 1px solid #3a0d0d;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            text-align: left;
            padding: 12px 14px;
            font-size: 14px;
            border-bottom: 1px solid #2a0a0a;
        }

        th {
            background: #200707;
            color: #e11d1d;
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
            color: #d99;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #3a0d0d;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }

        .pagination a:hover {
            background: #260808;
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
                border: 1px solid #3a0d0d;
                border-radius: 8px;
                overflow: hidden;
            }

            td {
                border-bottom: 1px solid #2a0a0a;
            }

            td::before {
                content: attr(data-label);
                display: block;
                color: #e11d1d;
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
                <h1>Grand Draw Entries</h1>
                <div class="count">{{ $entries->total() }} total submissions</div>
                <nav><a href="{{ route('admin.winner-draw') }}">Winner Draw &rarr;</a></nav>
            </div>
            <form class="logout" method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">Log Out</button>
            </form>
        </header>

        @if ($entries->isEmpty())
            <div class="empty">No entries submitted yet.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Mobile Number</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $entry)
                        <tr>
                            <td data-label="#">{{ $entry->id }}</td>
                            <td data-label="First Name">{{ $entry->first_name }}</td>
                            <td data-label="Last Name">{{ $entry->last_name }}</td>
                            <td data-label="Mobile Number">{{ $entry->mobile_number }}</td>
                            <td data-label="Submitted At">{{ $entry->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $entries->links() }}
            </div>
        @endif
    </div>
</body>
</html>
