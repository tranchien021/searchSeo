<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO Search Rankings</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eef1f7;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        header,
        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        .form-container {
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 95%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: border-color 0.3s ease-in-out;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease-in-out;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .results-container {
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9ecef;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        footer {
            font-size: 14px;
            color: #ddd;
            padding: 10px 0;
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
    </style>

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css"
        integrity="sha512-6S2HWzVFxruDlZxI3sXOZZ4/eJ8AcxkQH1+JjSe/ONCEqR9L4Ysq5JdT5ipqtzU7WHalNwzwBv+iE51gNHJNqQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"
        integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @if (Session::has('success'))
        <script>
            toastr.options = {
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "timeOut": "5000"
            }
            toastr.success("{{ Session::get('success') }}", 'Thành công');
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            toastr.options = {
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "timeOut": "5000"
            }
            toastr.error("{{ Session::get('error') }}", 'Lỗi !!!');
        </script>
    @endif
</head>

<body>
    <header>
        <h1>SEO Search Rankings</h1>
    </header>

    <div class="container">
        <!-- Form Section -->
        <div class="form-container">
            <form action="{{ route('post-rankings') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" name="url" id="url" required>
                </div>
                <div class="form-group">
                    <label for="keywords">Keywords</label>
                    <textarea name="keywords" id="keywords" rows="4" required></textarea>
                    <small style="color: red">Up to 5 entries are allowed, each one separated by a new line. If a space is inserted, it
                        becomes an AND search.</small>
                </div>
                <div class="form-group button-container">
                    <button type="submit">Search</button>
                </div>
            </form>
        </div>

        <!-- Results Section -->
        @if (!empty($results))
            <div class="results-container">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2">Keyword</th>
                            <th colspan="2">Google</th>
                            <th colspan="2">Yahoo</th>
                        </tr>
                        <tr>
                            <th>Rank</th>
                            <th>Search Results</th>
                            <th>Rank</th>
                            <th>Search Results</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $result)
                            <tr class="clickable-row" data-url="{{ route('detail-rankings', $result['keyword']) }}">
                                <td>{{ $result['keyword'] }}</td>
                                <td>{{ $result['google_rank'] ?? 'out of rank' }}</td>
                                <td>
                                    @php
                                        $googleResults = is_array($result['google_results']) ? $result['google_results'] : json_decode($result['google_results'], true);
                                    @endphp
                                    {{ is_array($googleResults) ? count($googleResults) : 0 }}
                                </td>
                                <td>{{ $result['yahoo_rank'] ?? 'out of rank' }}</td>
                                <td>
                                    @php
                                        $yahooResults = is_array($result['yahoo_results']) ? $result['yahoo_results'] : json_decode($result['yahoo_results'], true);
                                    @endphp
                                    {{ is_array($yahooResults) ? count($yahooResults) : 0 }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <footer>
        <p>&copy; 2024 SEO Search Rankings. Bản quyền của MinhChien</p>
    </footer>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var rows = document.querySelectorAll('.clickable-row');

        rows.forEach(function(row) {
            row.addEventListener('click', function() {
                var url = row.getAttribute('data-url');
                window.location.href = url;
            });
        });
    });
</script>

</html>
