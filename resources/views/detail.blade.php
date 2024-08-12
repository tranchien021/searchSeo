<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keyword Details</title>
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

        header {
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

        .keyword-details {
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .keyword-details h2 {
            margin-top: 0;
            font-size: 24px;
            color: #007bff;
        }

        .keyword-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .keyword-details th,
        .keyword-details td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .keyword-details th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .keyword-details tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .keyword-details tr:hover {
            background-color: #e9ecef;
        }

        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .button-container a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            padding: 10px 20px;
            border: 2px solid #007bff;
            border-radius: 6px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .button-container a:hover {
            background-color: #007bff;
            color: white;
        }

        .results-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        .results-list li {
            margin-bottom: 15px;
        }

        .results-list a {
            text-decoration: none;
            color: #007bff;
        }

        .results-list a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <h1>Keyword Details</h1>
    </header>

    <div class="container">
        <div class="keyword-details">
            <h2>Details for Keyword: {{ $keyword->keyword }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Keyword</td>
                        <td>{{ $keyword->keyword }}</td>
                    </tr>
                    <tr>
                        <td>URL</td>
                        <td>{{ $keyword->url }}</td>
                    </tr>
                    <tr>
                        <td>Google Rank</td>
                        <td>{{ $keyword->google_rank }}</td>
                    </tr>
                    <tr>
                        <td>Yahoo Rank</td>
                        <td>{{ $keyword->yahoo_rank }}</td>
                    </tr>
                    <tr>
                        <td>Google Results</td>
                        <td>
                            <ul class="results-list">
                                @foreach (json_decode($keyword->google_results, true) as $result)
                                    <li>
                                        <strong>Title:</strong> {{ $result['title'] }}<br>
                                        <strong>Link:</strong> <a href="{{ $result['link'] }}" target="_blank">{{ $result['link'] }}</a><br>
                                        <strong>Snippet:</strong> {{ $result['snippet'] }}<br>
                                        <strong>Position:</strong> {{ $result['position'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>Yahoo Results</td>
                        <td>
                            <ul class="results-list">
                                @foreach (json_decode($keyword->yahoo_results, true) as $result)
                                    <li>
                                        <strong>Title:</strong> {{ $result['title'] }}<br>
                                        <strong>Link:</strong> <a href="{{ $result['link'] }}" target="_blank">{{ $result['link'] }}</a><br>
                                        <strong>Snippet:</strong> {{ $result['snippet'] }}<br>
                                        <strong>Position:</strong> {{ $result['position'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="button-container">
            <a href="/">Back to Search Results</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 SEO Search Rankings. Bản quyền của MinhChien</p>
    </footer>
</body>

</html>
