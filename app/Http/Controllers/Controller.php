<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Goutte\Client;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function home()
    {
        $results = Keyword::orderBy('created_at', 'desc')->get();
        return view('search', compact('results'));
    }

    public function getRankings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'keywords' => 'required|array|max:5', // Đảm bảo keywords là mảng và tối đa 5 từ khóa
            'keywords.*' => 'string|max:255', // Mỗi từ khóa là một chuỗi ký tự và tối đa 255 ký tự
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'errorCode' => 422,
                'success' => false,
            ], 422);
        }

        $url = $request->input('url');
        $keywords = $request->input('keywords'); // Lấy mảng từ khóa
        $results = [];

        foreach ($keywords as $keyword) {
            $keyword = trim($keyword); // Loại bỏ khoảng trắng thừa
            if (strpos($keyword, ' ') !== false) {
                $andKeywords = explode(' ', $keyword);
                $searchQuery = implode('+', array_map('urlencode', $andKeywords));
            } else {
                $searchQuery = urlencode($keyword);
            }

            $googleResult = $this->getGoogleRanking($url, $searchQuery);
            $yahooResult = $this->getYahooRanking($url, $searchQuery);

            $newKeyword = new Keyword();
            $newKeyword->keyword = $keyword;
            $newKeyword->url = $url;
            $newKeyword->google_rank = $googleResult['rank'] ?? 'out of rank';
            $newKeyword->yahoo_rank = $yahooResult['rank'] ?? 'out of rank';
            $newKeyword->google_results = json_encode($googleResult['results']);
            $newKeyword->yahoo_results = json_encode($yahooResult['results']);
            $newKeyword->save();

            $results[] = [
                'keyword' => $keyword,
                'google_rank' => $googleResult['rank'] ?? 'out of rank',
                'google_results' => $googleResult['results'],
                'yahoo_rank' => $yahooResult['rank'] ?? 'out of rank',
                'yahoo_results' => $yahooResult['results'],
            ];
        }

        return response()->json([
            'message' => 'Rankings processed successfully.',
            'success' => true,
            'data' => $results,
        ]);
    }


    public function getGoogleRanking($url, $keywords)
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://www.google.com/search?q=' . $keywords);

        $response = $client->getResponse();
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return [
                'rank' => null,
                'results' => [],
                'error' => 'Failed to fetch Google results.',
            ];
        }

        $rank = null;
        $results = [];
        $crawler->filter('div.g')->each(function ($node, $index) use (&$rank, $url, &$results) {
            $linkNode = $node->filter('a');
            $link = $linkNode->attr('href');
            $title = $node->filter('h3')->text();
            $snippet = $node->filter('span.aCOpRe')->text();

            $results[] = [
                'title' => $title,
                'link' => $link,
                'snippet' => $snippet,
                'position' => $index + 1,
            ];

            if (strpos($link, $url) !== false) {
                $rank = $index + 1;
            }
        });

        return [
            'rank' => $rank,
            'results' => $results,
        ];
    }

    public function getYahooRanking($url, $keywords)
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://search.yahoo.com/search?p=' . $keywords);

        $response = $client->getResponse();
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return [
                'rank' => null,
                'results' => [],
                'error' => 'Failed to fetch Yahoo results.',
            ];
        }

        $rank = null;
        $results = [];

        $crawler->filter('div.dd.algo')->each(function ($node, $index) use (&$rank, $url, &$results) {
            $linkNode = $node->filter('a');
            $link = $linkNode->attr('href');
            $title = $node->filter('h3.title')->text();
            $snippet = $node->filter('p')->text();

            $results[] = [
                'title' => $title,
                'link' => $link,
                'snippet' => $snippet,
                'position' => $index + 1,
            ];

            if (strpos($link, $url) !== false) {
                $rank = $index + 1;
            }
        });

        return [
            'rank' => $rank,
            'results' => $results,
        ];
    }

    public function tempalteRankings()
    {
        return view('search');
    }

    public function postRankings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'keywords' => 'required|string', // Đảm bảo keywords là một chuỗi văn bản
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tìm kiếm.')
                ->withInput();
        }

        $url = $request->input('url');
        $keywords = $request->input('keywords');
        $keywords = array_filter(array_map('trim', explode("\r\n", $keywords)));
        $results = [];

        foreach ($keywords as $keyword) {
            $keyword = trim($keyword); // Loại bỏ khoảng trắng thừa
            if (strpos($keyword, ' ') !== false) {
                $andKeywords = explode(' ', $keyword);
                $searchQuery = implode('+', array_map('urlencode', $andKeywords));
            } else {
                $searchQuery = urlencode($keyword);
            }

            $googleResult = $this->getGoogleRanking($url, $searchQuery);
            $yahooResult = $this->getYahooRanking($url, $searchQuery);

            $newKeyword = new Keyword();
            $newKeyword->keyword = $keyword;
            $newKeyword->url = $url;
            $newKeyword->google_rank = $googleResult['rank'] ?? 'out of rank';
            $newKeyword->yahoo_rank = $yahooResult['rank'] ?? 'out of rank';
            $newKeyword->google_results = json_encode($googleResult['results']);
            $newKeyword->yahoo_results = json_encode($yahooResult['results']);
            $newKeyword->save();

            $results[] = [
                'keyword' => $keyword,
                'google_rank' => $googleResult['rank'],
                'google_results' => $googleResult['results'],
                'yahoo_rank' => $yahooResult['rank'],
                'yahoo_results' => $yahooResult['results'],
            ];
        }

        return view('search', ['results' => $results])
            ->with('success', 'Tìm kiếm thành công');
    }

    public function detailRankings($keyword)
    {
        try {
            $keywordDetails = Keyword::where('keyword', $keyword)->firstOrFail();
            return view('detail', [
                'keyword' => $keywordDetails,

            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->view('errors.404', [], 404);
        } catch (\Exception $e) {
            return response()->view('errors.500', ['error' => $e->getMessage()], 500);
        }
    }

    public function detailRankingsAPI($keyword)
    {
        try {
            $keywordDetails = Keyword::where('keyword', $keyword)->firstOrFail();
            return response()->json([
                'keyword' => $keywordDetails,
                'success' => true,
                'message' => 'get Detail Success',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->view('errors.404', [], 404);
        } catch (\Exception $e) {
            return response()->view('errors.500', ['error' => $e->getMessage()], 500);
        }
    }

}
