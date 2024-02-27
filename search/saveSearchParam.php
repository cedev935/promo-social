<?php

require '../config.php';

function validatePostData($postData, $searchParams)
{
    $type = $postData["search_type"] ?? '';
    $network = $postData["search_network"] ?? '';
    $keyword = $postData["search_keyword"] ?? '';

    // Define an array to hold validation errors
    $errors = [];

    $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    if (empty($type)) {
        $errors[] = "Type field is required.";
    }

    $network = htmlspecialchars($network, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    if (empty($network)) {
        $errors[] = "Network field is required.";
    }

    $keyword = htmlspecialchars($keyword, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $keywords = explode(" ", $keyword);
    $keywords = array_filter($keywords);

    if (empty($keywords)) {
        $errors[] = "Keyword field is required.";
    }

    if (empty($errors)) {
        $currentSearchKeywordsCount = count($searchParams);

        // Max Search Params
        if ($currentSearchKeywordsCount >= MAX_SEARCH_KEYWORD) $errors[] = 'Max search added.';
    }

    if (empty($errors)){
        // Duplicate Search Params
        foreach ($searchParams as $searchParam) {
            if (
                $searchParam['type'] == $type &&
                $searchParam['network'] == $network &&
                $searchParam['keyword'] == $keyword
            ) {
                $errors[] = "Duplicate search parameters.";
                break;
            }
        }
    }

    return $errors;
}

function createProductKeyword($keyword)
{
    $phrases = [
        phrase1,
        phrase2,
        phrase3,
        phrase4,
        phrase5,
        phrase6,
        phrase7
    ];

    $prependKeyword = '';
    foreach ($phrases as $key => $phrase) {
        $operator = ' OR ';
        if ($key == 0) $operator = '';
        $prependKeyword .= "{$operator}\"{$phrase} {$keyword}\"";
    }
    return trim($prependKeyword);
}

function saveSearchParamsInFile($data)
{
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'search_params.json';

    // Check if the file already exists
    if (file_exists($filePath)) {
        $jsonData = file_get_contents($filePath);
        $existingData = $jsonData ? json_decode($jsonData, true) : [];
        if (!empty($existingData)) {
            $data = array_merge($existingData, $data);
        }
    }

    $jsonData = json_encode($data, JSON_PRETTY_PRINT);

    file_put_contents($filePath, $jsonData);
    return true;
}

function saveSearchParam()
{
    $searchParams = getSearchParams();
    $currentSearchKeywordsCount = count($searchParams);
    $validationErrors = validatePostData($_POST, $searchParams);

    // Check if there are any validation errors
    if (!empty($validationErrors)) {
        $response = array(
            'message' => 'Error',
            'errors' => $validationErrors
        );

        http_response_code(400);
        echo json_encode($response);
    } else {
        $searchId = $currentSearchKeywordsCount++;
        $type = htmlspecialchars($_POST["search_type"], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $network = htmlspecialchars($_POST["search_network"], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $keyword = htmlspecialchars($_POST["search_keyword"], ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Create Search Url
        $temp_keyword = $keyword;
        if ($type == 'Product') {
            $temp_keyword = createProductKeyword($keyword);
        }

        $temp_keyword = urlencode($temp_keyword);

        $search_url = str_replace(
            ['{KEYWORD}', '{NETWORK}'],
            [$temp_keyword, $network],
            SEARCH_BASE_URL . 'search-result.php?q={KEYWORD}&site={NETWORK}&rss&apikey=' . SEARCH_API_KEY
        );

        $data[] = [
            'id' => $searchId,
            'search_url' => $search_url,
            'keyword' => $keyword,
            'type' => $type,
            'network' => $network
        ];

        saveSearchParamsInFile($data);

        http_response_code(200);
        $response = array(
            'message' => 'Success',
            'searchId' => $searchId,
            'searchParams' => "{$keyword} ({$type}, {$network})"
        );
        echo json_encode($response);
    }
}

// Handle the request based on the request method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    saveSearchParam();
}
