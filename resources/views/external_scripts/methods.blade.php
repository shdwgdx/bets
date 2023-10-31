<?php
function findOrCreateItemSport($existingItems, $name, $className, $similarityThreshold)
{
    $similarityThreshold = 80;
    $item = null;
    $maxSimilarity = 0;
    $name = strtolower(trim($name));
    foreach ($existingItems as $existingItem) {
        $similarity = 0;
        similar_text($name, $existingItem->title, $similarity);

        if ($similarity > $maxSimilarity) {
            $maxSimilarity = $similarity;
            $item = $existingItem;
        }
    }

    if ($maxSimilarity > $similarityThreshold) {
        return $item;
    } else {
        $newItem = $className::create([
            'title' => $name,
        ]);
        return $newItem;
    }
}
function findOrCreateItemLeague($existingItems, $name, $className, $similarityThreshold, $sportId)
{
    $similarityThreshold = 80;
    $item = null;
    $maxSimilarity = 0;
    $name = strtolower(trim($name));
    foreach ($existingItems as $existingItem) {
        $similarity = 0;

        similar_text($name, $existingItem->title, $similarity);

        if ($similarity > $maxSimilarity) {
            $maxSimilarity = $similarity;
            $item = $existingItem;
        }
    }
    if ($maxSimilarity > $similarityThreshold) {
        return $item;
    } else {
        $newItem = $className::create([
            'title' => $name,
            'sport_id' => $sportId,
        ]);
        return $newItem;
    }
}

// function findOrCreateItemGame($existingItems, $team1, $team2, $date, $className, $similarityThreshold, $leagueId)
// {
//     $item = null;
//     $maxSimilarity = 0;
//     $team1 = strtolower(trim($team1));
//     $team2 = strtolower(trim($team2));

// $removePrefix = function ($team) {
//     $prefixes = ['fk', 'cf', 'ud', 'ks', 'fc', 'ac', 'as', 'sc', 'ss', 'sk', 'sv', 'afc', 'nk', 'cd'];
//     $team = trim($team);
//     foreach ($prefixes as $prefix) {
//         if (strpos($team, $prefix) === 0) {
//             $team = substr($team, strlen($prefix));
//             break;
//         }
//     }
//     return trim($team);
// };

//     $team1 = $removePrefix($team1);
//     $team2 = $removePrefix($team2);

//     foreach ($existingItems as $existingItem) {
//         $similarity1 = 0;
//         $similarity2 = 0;
//         similar_text($team1, $existingItem->team1, $similarity1);
//         similar_text($team2, $existingItem->team2, $similarity2);

//         if ($similarity1 > $similarityThreshold && $similarity1 > $maxSimilarity) {
//             $maxSimilarity = $similarity1;
//             $item = $existingItem;
//         } elseif ($similarity2 > $similarityThreshold && $similarity2 > $maxSimilarity) {
//             $maxSimilarity = $similarity2;
//             $item = $existingItem;
//         }
//     }

//     if ($maxSimilarity > $similarityThreshold) {
//         return $item;
//     } else {
//         $newItem = $className::create([
//             'date' => $date,
//             'team1' => $team1,
//             'team2' => $team2,
//             'league_id' => $leagueId,
//         ]);
//         return $newItem;
//     }
// }
// function findOrCreateItemGame($existingItems, $team1, $team2, $date, $className, $similarityThreshold, $leagueId)
// {
//     $item = null;
//     $maxSimilarity = 0;

//     $team1 = preg_replace('/[0-9\.]+/', '', $team1);
//     $team2 = preg_replace('/[0-9\.]+/', '', $team2);
//     $team1 = strtolower(trim($team1));
//     $team2 = strtolower(trim($team2));
//     $removePrefixAndPostfix = function ($team) {
//         $prefixes = ['fk', 'cf', 'ud', 'ks', 'fc', 'ac', 'as', 'sc', 'ss', 'sk', 'sv', 'afc', 'nk', 'cd', 'vfl', 'rb', 'tsg', 'vfb', 'fsv'];
//         $postfixes = ['fk', 'cf', 'ud', 'ks', 'fc', 'ac', 'as', 'sc', 'ss', 'sk', 'sv', 'afc', 'nk', 'cd', 'vfl', 'rb', 'tsg', 'vfb', 'fsv'];

//         $team = trim($team);

//         // Удаление префикса
//         foreach ($prefixes as $prefix) {
//             if (strpos($team, $prefix) === 0) {
//                 $nextChar = substr($team, strlen($prefix), 1);

//                 // Проверка, является ли следующий символ пробелом или пустым
//                 // Если да, то префикс не является частью слова и его можно удалить
//                 if ($nextChar === ' ' || $nextChar === '') {
//                     $team = substr($team, strlen($prefix));
//                     break;
//                 }
//             }
//         }

//         // Удаление постфикса
//         foreach ($postfixes as $postfix) {
//             $postfixLength = strlen($postfix);

//             // Проверка, является ли постфикс отдельным словом или частью другого слова
//             if (substr($team, -$postfixLength) === $postfix) {
//                 $prevChar = substr($team, -$postfixLength - 1, 1);

//                 // Проверка, является ли предыдущий символ пробелом
//                 // Если да, то постфикс не является частью слова и его можно удалить
//                 if ($prevChar === ' ') {
//                     $team = substr($team, 0, -$postfixLength);
//                     break;
//                 }
//             }
//         }

//         return trim($team);
//     };
//     $team1 = $removePrefixAndPostfix($team1);
//     $team2 = $removePrefixAndPostfix($team2);
//     $teams = [$team1, $team2];
//     // $originalTeams = $teams;

//     sort($teams);

//     // if ($teams === $originalTeams) {
//     //     echo 'Массив не был отсортирован';
//     // } else {
//     //     echo 'Массив был отсортирован';
//     // }
//     $team1 = $teams[0];
//     $team2 = $teams[1];
//     foreach ($existingItems as $existingItem) {
//         $similarity1 = 0;
//         similar_text($team1, $existingItem->team1, $similarity1);

//         $similarity2 = 0;
//         similar_text($team2, $existingItem->team2, $similarity2);

//         if ($similarity1 > $similarityThreshold && $similarity2 > $similarityThreshold && ($similarity1 + $similarity2) / 2 > $maxSimilarity) {
//             $maxSimilarity = ($similarity1 + $similarity2) / 2;
//             $item = $existingItem;
//         }
//     }

//     if ($item !== null) {
//         return $item;
//     } else {
//         $newItem = $className::create([
//             'date' => $date,
//             'team1' => $team1,
//             'team2' => $team2,
//             'league_id' => $leagueId,
//         ]);
//         return $newItem;
//     }
// }

function findOrCreateItemGame($existingItems, $team1, $team2, $date, $className, $similarityThreshold, $leagueId)
{
    $item = null;
    $maxSimilarity = 0;
    $similarityThreshold = 80;
    $team1 = strtolower(trim($team1));
    $team2 = strtolower(trim($team2));
    // $team1 = preg_replace('/[0-9\.]+/', '', $team1);
    // $team2 = preg_replace('/[0-9\.]+/', '', $team2);
    // $removePrefixAndPostfix = function ($team) {
    //     $prefixes = ['fk', 'cf', 'ud', 'ks', 'fc', 'ac', 'as', 'sc', 'ss', 'sk', 'sv', 'afc', 'nk', 'cd', 'vfl', 'rb', 'tsg', 'vfb', 'fsv'];
    //     $postfixes = ['fk', 'cf', 'ud', 'ks', 'fc', 'ac', 'as', 'sc', 'ss', 'sk', 'sv', 'afc', 'nk', 'cd', 'vfl', 'rb', 'tsg', 'vfb', 'fsv'];

    //     $team = trim($team);

    //     // Удаление префикса
    //     foreach ($prefixes as $prefix) {
    //         if (strpos($team, $prefix) === 0) {
    //             $nextChar = substr($team, strlen($prefix), 1);

    //             // Проверка, является ли следующий символ пробелом или пустым
    //             // Если да, то префикс не является частью слова и его можно удалить
    //             if ($nextChar === ' ' || $nextChar === '') {
    //                 $team = substr($team, strlen($prefix));
    //                 break;
    //             }
    //         }
    //     }

    //     // Удаление постфикса
    //     foreach ($postfixes as $postfix) {
    //         $postfixLength = strlen($postfix);

    //         // Проверка, является ли постфикс отдельным словом или частью другого слова
    //         if (substr($team, -$postfixLength) === $postfix) {
    //             $prevChar = substr($team, -$postfixLength - 1, 1);

    //             // Проверка, является ли предыдущий символ пробелом
    //             // Если да, то постфикс не является частью слова и его можно удалить
    //             if ($prevChar === ' ') {
    //                 $team = substr($team, 0, -$postfixLength);
    //                 break;
    //             }
    //         }
    //     }

    //     return trim($team);
    // };
    // $team1 = $removePrefixAndPostfix($team1);
    // $team2 = $removePrefixAndPostfix($team2);
    $teamMappings = [
        'wolverhampton wanderers' => 'wolves',
        'wolverhampton wanderers fc' => 'wolves',
        'deportivo alaves' => 'alaves',
        'deportivo alavés' => 'alaves',
        'lavés' => 'alaves',
        'brighton and hove albion' => 'brighton',
        'brighton & hove albion fc' => 'brighton',
        'brighton & hove albion' => 'brighton',
        'afc bournemouth' => 'bournemouth',
        'ajax amsterdam' => 'ajax',
        'ajax Ámsterdam' => 'ajax',
        'fk crvena zvezda' => 'red star belgrade',
        'crvena zvezda' => 'red star belgrade',
        'bsc young boys' => 'young boys',
        'psv' => 'psv eindhoven',
        'aek atenas' => 'aek athens',
        'aek' => 'aek athens',
        'aris' => 'aris limassol fc',
        'tsc' => 'tsc backa topola',
        'olympiacos' => 'olympiacos piraeus',
        'hacken gothenburg' => 'bk hacken',
        'cologne' => '1.fc koln',
    ];
    $team1 = isset($teamMappings[$team1]) ? $teamMappings[$team1] : $team1;
    $team2 = isset($teamMappings[$team2]) ? $teamMappings[$team2] : $team2;

    foreach ($existingItems as $existingItem) {
        $similarity1 = 0;
        $existingTeam1 = strtolower(trim($existingItem->team1));
        $existingTeam1 = isset($teamMappings[$existingTeam1]) ? $teamMappings[$existingTeam1] : $existingTeam1;
        similar_text($team1, $existingTeam1, $similarity1);

        $similarity2 = 0;
        $existingTeam2 = strtolower(trim($existingItem->team2));
        $existingTeam2 = isset($teamMappings[$existingTeam2]) ? $teamMappings[$existingTeam2] : $existingTeam2;
        similar_text($team2, $existingTeam2, $similarity2);

        if ($similarity1 > $similarityThreshold && $similarity2 > $similarityThreshold && ($similarity1 + $similarity2) / 2 > $maxSimilarity) {
            $maxSimilarity = ($similarity1 + $similarity2) / 2;
            $item = $existingItem;
        }
    }

    if ($item !== null) {
        return ['item' => $item, 'reverse' => false];
    } else {
        // Проверяем команды в обратном порядке
        foreach ($existingItems as $existingItem) {
            $reverseSimilarity1 = 0;
            $existingTeam2 = strtolower(trim($existingItem->team2));
            $existingTeam2 = isset($teamMappings[$existingTeam2]) ? $teamMappings[$existingTeam2] : $existingTeam2;
            similar_text($team1, $existingTeam2, $reverseSimilarity1);

            $reverseSimilarity2 = 0;
            $existingTeam1 = strtolower(trim($existingItem->team1));
            $existingTeam1 = isset($teamMappings[$existingTeam1]) ? $teamMappings[$existingTeam1] : $existingTeam1;
            similar_text($team2, $existingTeam1, $reverseSimilarity2);

            if ($reverseSimilarity1 > $similarityThreshold && $reverseSimilarity2 > $similarityThreshold && ($reverseSimilarity1 + $reverseSimilarity2) / 2 > $maxSimilarity) {
                $maxSimilarity = ($reverseSimilarity1 + $reverseSimilarity2) / 2;
                $item = $existingItem;
            }
        }

        if ($item !== null) {
            return ['item' => $item, 'reverse' => true];
        } else {
            $newItem = $className::create([
                'date' => $date,
                'team1' => $team1,
                'team2' => $team2,
                'league_id' => $leagueId,
            ]);
            return ['item' => $newItem, 'reverse' => false];
        }
    }
}

function findOrCreateItemGameCsgo($existingItems, $team1, $team2, $date, $className, $similarityThreshold, $leagueId)
{
    $item = null;
    $maxSimilarity = 0;
    $similarityThreshold = 40;
    $team1 = strtolower(trim($team1));
    $team2 = strtolower(trim($team2));

    foreach ($existingItems as $existingItem) {
        $similarity1 = 0;
        similar_text($team1, $existingItem->team1, $similarity1);

        $similarity2 = 0;
        similar_text($team2, $existingItem->team2, $similarity2);

        if ($similarity1 > $similarityThreshold && $similarity2 > $similarityThreshold && ($similarity1 + $similarity2) / 2 > $maxSimilarity) {
            $maxSimilarity = ($similarity1 + $similarity2) / 2;
            $item = $existingItem;
        }
    }

    if ($item !== null) {
        return ['item' => $item, 'reverse' => false];
    } else {
        // Проверяем команды в обратном порядке
        foreach ($existingItems as $existingItem) {
            $reverseSimilarity1 = 0;
            similar_text($team1, $existingItem->team2, $reverseSimilarity1);

            $reverseSimilarity2 = 0;
            similar_text($team2, $existingItem->team1, $reverseSimilarity2);

            if ($reverseSimilarity1 > $similarityThreshold && $reverseSimilarity2 > $similarityThreshold && ($reverseSimilarity1 + $reverseSimilarity2) / 2 > $maxSimilarity) {
                $maxSimilarity = ($reverseSimilarity1 + $reverseSimilarity2) / 2;
                $item = $existingItem;
            }
        }

        if ($item !== null) {
            return ['item' => $item, 'reverse' => true];
        } else {
            $newItem = $className::create([
                'date' => $date,
                'team1' => $team1,
                'team2' => $team2,
                'league_id' => $leagueId,
            ]);
            return ['item' => $newItem, 'reverse' => false];
        }
    }
}

function findOrCreateItemLeagueCsgo($existingItems, $name, $className, $similarityThreshold, $sportId)
{
    $similarityThreshold = 85;
    $item = null;
    $maxSimilarity = 0;

    $name = strtolower(trim($name));

    $nameMappings = [
        'cct south america series qual.' => 'cct',
        'cct south america' => 'cct',
        'cct east europe series qual.' => 'cct',
        'cct eastern europe' => 'cct',
        'cs:go. cct' => 'cct',
        'cct east europe' => 'cct',
        'cct oceania' => 'cct',
        'cct oceania series' => 'cct',
        'cct east europe series' => 'cct',
        'cct east europe series. closed qualifier' => 'cct',
        'cct south america. qualifier' => 'cct',
        'esl challenger league north america' => 'esl challenger',
    ];
    $name = isset($nameMappings[$name]) ? $nameMappings[$name] : $name;

    foreach ($existingItems as $existingItem) {
        $similarity = 0;
        $existingName = strtolower(trim($existingItem->title));
        $existingName = isset($nameMappings[$existingName]) ? $nameMappings[$existingName] : $existingName;
        similar_text($name, $existingName, $similarity);

        if ($similarity > $maxSimilarity) {
            $maxSimilarity = $similarity;
            $item = $existingItem;
        }
    }

    if ($maxSimilarity > $similarityThreshold) {
        return $item;
    } else {
        $newItem = $className::create([
            'title' => $name,
            'sport_id' => $sportId,
        ]);
        return $newItem;
    }
}
