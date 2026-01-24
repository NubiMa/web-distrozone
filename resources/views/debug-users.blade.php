<!DOCTYPE html>
<html>

<head>
    <title>Users Debug</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .info {
            background: #e3f2fd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .credential {
            background: #fff3cd;
            padding: 10px;
            margin: 5px 0;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <h1>DistroZone - Users Debug Page</h1>

    <div class="info">
        <h2>Database Connection</h2>
        <p><strong>Database:</strong> {{ config('database.default') }}</p>
        <p><strong>Host:</strong> {{ config('database.connections.mysql.host') }}</p>
        <p><strong>Database Name:</strong> {{ config('database.connections.mysql.database') }}</p>
    </div>

    <h2>Users in Database</h2>
    <p>Total users: <strong>{{ $users->count() }}</strong></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Active</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><strong>{{ $user->role }}</strong></td>
                    <td>{{ $user->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $user->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No users found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="info">
        <h2>Test Credentials</h2>
        <div class="credential">
            <strong>Admin:</strong><br>
            Email: admin@distrozone.com<br>
            Password: admin123
        </div>
        <div class="credential">
            <strong>Kasir 1:</strong><br>
            Email: budi@distrozone.com<br>
            Password: kasir123
        </div>
        <div class="credential">
            <strong>Kasir 2:</strong><br>
            Email: siti@distrozone.com<br>
            Password: kasir123
        </div>
        <div class="credential">
            <strong>Customer:</strong><br>
            Email: customer@test.com<br>
            Password: customer123
        </div>
    </div>

    <div style="margin-top: 30px; padding: 15px; background: #f5f5f5; border-radius: 5px;">
        <h3>Quick Actions</h3>
        <p>
            <a href="/login"
                style="display: inline-block; padding: 10px 20px; background: #0F0F0F; color: white; text-decoration: none; border-radius: 5px;">Go
                to Login Page</a>
            <a href="/"
                style="display: inline-block; padding: 10px 20px; background: #FF6B00; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">Go
                to Homepage</a>
        </p>
    </div>
</body>

</html>