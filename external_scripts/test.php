<?php
function levenshteinDistance($str1, $str2)
{
    $len1 = strlen($str1);
    $len2 = strlen($str2);
    $dp = array();

    for ($i = 0; $i <= $len1; $i++) {
        $dp[$i] = array();
        $dp[$i][0] = $i;
    }

    for ($j = 0; $j <= $len2; $j++) {
        $dp[0][$j] = $j;
    }

    for ($i = 1; $i <= $len1; $i++) {
        for ($j = 1; $j <= $len2; $j++) {
            $cost = ($str1[$i - 1] != $str2[$j - 1]) ? 1 : 0;
            $dp[$i][$j] = min(
                $dp[$i - 1][$j] + 1,
                $dp[$i][$j - 1] + 1,
                $dp[$i - 1][$j - 1] + $cost
            );
        }
    }

    return $dp[$len1][$len2];
}

function cosineSimilarity($str1, $str2)
{
    $tokens1 = explode(' ', $str1);
    $tokens2 = explode(' ', $str2);

    $vector1 = array_count_values($tokens1);
    $vector2 = array_count_values($tokens2);

    $dotProduct = 0;
    $magnitude1 = 0;
    $magnitude2 = 0;

    foreach ($vector1 as $term => $count1) {
        $count2 = isset($vector2[$term]) ? $vector2[$term] : 0;
        $dotProduct += $count1 * $count2;
        $magnitude1 += $count1 ** 2;
    }

    foreach ($vector2 as $term => $count2) {
        $magnitude2 += $count2 ** 2;
    }

    $magnitude1 = sqrt($magnitude1);
    $magnitude2 = sqrt($magnitude2);

    if ($magnitude1 * $magnitude2 === 0) {
        return 0;
    }

    return $dotProduct / ($magnitude1 * $magnitude2);
}


// Команда2: Rakow Czestochowa
// Raków Czestochowa
$str1 = 'FC Czestochowa';
$str2 = 'FC København';


$similarity = similar_text('CHAMPIONS LEAGUE', 'UEFA CHAMPIONS LEAGUE QUALIFICATION', $percent);

echo "Степень сходства: $percent%";
