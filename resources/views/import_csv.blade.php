<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import CSV vào bảng users</title>
</head>
<body>

    <h1>Import CSV vào bảng users</h1>

    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form upload CSV -->
    <form action="{{ route('import.csv.post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="file">Chọn file CSV</label>
            <input type="file" name="file" id="file" accept=".csv" required>
        </div>
        <button type="submit">Import</button>
    </form>

</body>
</html>
