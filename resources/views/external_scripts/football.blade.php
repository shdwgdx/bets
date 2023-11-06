<?php
require 'vendor/simpleHtmlDom/simple_html_dom.php';
require 'vendor/autoload.php'; // Подключение php-webdriver
require_once __DIR__ . '/../../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

require 'methods.blade.php';
require 'betsafe.blade.php';
require './csgo/betsafe.blade.php';
require './csgo/lvbet.blade.php';
require './csgo/mrgreen.blade.php';
require './csgo/optibet.blade.php';
require './csgo/klondaika.blade.php';
require './csgo/pafbet.blade.php';
require './csgo/williamhill.blade.php';
require 'lvbet.blade.php';
require 'mrgreen.blade.php';
require 'olybet.blade.php';
require 'klondaika.blade.php';
require 'feniks.blade.php';
require 'pafbet.blade.php';
require 'optibet.blade.php';
require 'williamhill.blade.php';
require 'tonybet.blade.php';
require 'rabona.blade.php';
require 'rollino.blade.php';

// csgo

getMatchesSourceLvbetCsgo('https://lvbet.lv/sports/en/pre-matches/counter-strike-go-cs:go/world/--/51/7453/');

getMatchesSourceBetsafeCsgo('https://www.betsafe.lv/api/sb/v1/widgets/events-table/v2?categoryIds=119&eventSortBy=StartDate&maxMarketCount=3&pageNumber=1');
getMatchesSourceMrgreenCsgo('https://www.mrgreen.lv/en/sports/category/2120-cs-go');
// // sleep(2);
getMatchesSourceWilliamhillCsgo('https://www.williamhill.lv/en/sports/category/2120-cs-go');
// // sleep(2);
getMatchesSourceOptibetCsgo('https://sb-data.optibet.lv/en/events/upcoming/wcg?domainId=0&gameTypes=*');
getMatchesSourceKlondaikaCsgo('https://sb-data.optibet.lv/en/events/upcoming/wcg?domainId=0&gameTypes=*');
getMatchesSourcePafbetCsgo('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/esports/cs_go/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1693499539500&useCombined=true&useCombinedLive=true');
// // // champions-league

getMatchesSourceLvbet('https://offer.lvbet.lv/client-api/v3/matches/competition-view/?lang=en&sports_groups_ids=37660', sport: 'football', league: 'champions', url_string: 'https://lvbet.lv/sports/en/pre-matches/football/europe/uefa-champions-league/teams_string/--/1/35259/37660/match_id/');
getMatchesSourceBetsafe('https://www.betsafe.lv/api/sb/v1/widgets/events-table/v2?categoryIds=1&competitionIds=6134&eventSortBy=StartDate&maxMarketCount=3&pageNumber=1', sport: 'football', league: 'champions');
getMatchesSourceMrgreen('6674-champions-league', sport: 'football', league: 'champions');
// sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=18286520&game=22912934&region=20001&type=0&sport=1&lang=en', sport: 'football', league: 'champions');
// sleep(2);
getMatchesSourceWilliamhill('6674-champions-league', sport: 'football', league: 'champions');
// sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/champions_league/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1695807996395&category=12579&useCombined=true&useCombinedLive=true', sport: 'football', league: 'champions');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/495/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'champions');
getMatchesSourceOptibet('https://sb-data.klondaika.lv/en/events/group/495/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'champions');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/football/champions_league/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1695807996395&category=12579&useCombined=true&useCombinedLive=true', sport: 'football', league: 'champions');
getMatchesSourceTonybet('https://platform.tonybet.es/api/event/list?competitor1Id_neq=&competitor2Id_neq=&status_in%5B%5D=0&status_in%5B%5D=2&limit=150&main=1&relations%5B%5D=odds&relations%5B%5D=league&relations%5B%5D=result&relations%5B%5D=competitors&relations%5B%5D=withMarketsCount&relations%5B%5D=players&relations%5B%5D=sportCategories&relations%5B%5D=broadcasts&relations%5B%5D=statistics&relations%5B%5D=additionalInfo&leagueId_eq=1008006&oddsExists_eq=1&lang=es', sport: 'football', league: 'champions', url_string: 'https://tonybet.es/prematch/football/1008006-uefa-champions-league/match_id');
getMatchesSourceRabona('https://sb2frontend-altenar2.biahosted.com/api/Sportsbook/GetEvents?timezoneOffset=-180&langId=86&skinName=rabona&configId=12&culture=en-CA&countryCode=ES&deviceType=Desktop&numformat=en&integration=rabona&sportids=66&categoryids=0&champids=16808&group=AllEvents&period=periodall&withLive=true&outrightsDisplay=none&marketTypeIds=&couponType=0&marketGroupId=0', sport: 'football', league: 'champions');
// // // //europe-league

getMatchesSourceLvbet('https://offer.lvbet.lv/client-api/v3/matches/competition-view/?lang=en&sports_groups_ids=37538', sport: 'football', league: 'europe', url_string: 'https://lvbet.lv/sports/en/pre-matches/football/europe/uefa-europa-league/teams_string/--/1/35259/37538/match_id/');
getMatchesSourceBetsafe('https://www.betsafe.lv/api/sb/v1/widgets/events-table/v2?categoryIds=1&competitionIds=2612&eventSortBy=StartDate&maxMarketCount=3&pageNumber=1', sport: 'football', league: 'europe');
// sleep(2);
getMatchesSourceMrgreen('6675-europa-league', sport: 'football', league: 'europe');
// sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=18275688&game=22910774&region=20001&type=0&sport=1&lang=en', sport: 'football', league: 'europe');
// sleep(2);
getMatchesSourceWilliamhill('6675-europa-league', sport: 'football', league: 'europe');
// sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/europa_league/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1695807905906&useCombined=true&useCombinedLive=true', sport: 'football', league: 'europe');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/1546/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'europe');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/1546/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'football', league: 'europe');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/football/europa_league/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1693493080494&useCombined=true&useCombinedLive=true', sport: 'football', league: 'europe');
getMatchesSourceTonybet('https://platform.tonybet.es/api/event/list?competitor1Id_neq=&competitor2Id_neq=&status_in%5B%5D=0&status_in%5B%5D=2&limit=150&main=1&relations%5B%5D=odds&relations%5B%5D=league&relations%5B%5D=result&relations%5B%5D=competitors&relations%5B%5D=withMarketsCount&relations%5B%5D=players&relations%5B%5D=sportCategories&relations%5B%5D=broadcasts&relations%5B%5D=statistics&relations%5B%5D=additionalInfo&leagueId_eq=1008283&oddsExists_eq=1&lang=es', sport: 'football', league: 'europe', url_string: 'https://tonybet.es/prematch/football/1008283-uefa-europa-league/match_id');
getMatchesSourceRabona('https://sb2frontend-altenar2.biahosted.com/api/Sportsbook/GetEvents?timezoneOffset=-180&langId=86&skinName=rabona&configId=12&culture=en-CA&countryCode=ES&deviceType=Desktop&numformat=en&integration=rabona&sportids=66&categoryids=0&champids=16809&group=AllEvents&period=periodall&withLive=true&outrightsDisplay=none&marketTypeIds=&couponType=0&marketGroupId=0', sport: 'football', league: 'europe');
// // // //la-league

getMatchesSourceLvbet('https://offer.lvbet.lv/client-api/v3/matches/competition-view/?lang=en&sports_groups_ids=41533', sport: 'football', league: 'la', url_string: 'https://lvbet.lv/sports/en/pre-matches/football/spain/la-liga/teams_string/--/1/35306/41533/match_id/');
getMatchesSourceBetsafe('https://www.betsafe.lv/api/sb/v1/widgets/events-table/v2?categoryIds=1&competitionIds=12&eventSortBy=StartDate&maxMarketCount=3&pageNumber=1', sport: 'football', league: 'la');
// sleep(2);
getMatchesSourceMrgreen('306-laliga', sport: 'football', league: 'la');
// sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=545&game=22910774&region=2150001&type=0&sport=1&lang=en', sport: 'football', league: 'la');
// sleep(2);
getMatchesSourceWilliamhill('306-laliga', sport: 'football', league: 'la');
// sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/spain/la_liga/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693468032979&useCombined=true&useCombinedLive=true', sport: 'football', league: 'la');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/543/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'la');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/543/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'football', league: 'la');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/football/spain/la_liga/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1693491610695&useCombined=true&useCombinedLive=true', sport: 'football', league: 'la');
getMatchesSourceTonybet('https://platform.tonybet.es/api/event/list?competitor1Id_neq=&competitor2Id_neq=&status_in%5B%5D=0&status_in%5B%5D=2&limit=150&main=1&relations%5B%5D=odds&relations%5B%5D=league&relations%5B%5D=result&relations%5B%5D=competitors&relations%5B%5D=withMarketsCount&relations%5B%5D=players&relations%5B%5D=sportCategories&relations%5B%5D=broadcasts&relations%5B%5D=statistics&relations%5B%5D=additionalInfo&leagueId_eq=1008007&oddsExists_eq=1&lang=es', sport: 'football', league: 'la', url_string: 'https://tonybet.es/prematch/football/1008007-laliga/match_id');
getMatchesSourceRabona('https://sb2frontend-altenar2.biahosted.com/api/Sportsbook/GetEvents?timezoneOffset=-180&langId=86&skinName=rabona&configId=12&culture=en-CA&countryCode=ES&deviceType=Desktop&numformat=en&integration=rabona&sportids=66&categoryids=0&champids=2941&group=AllEvents&period=periodall&withLive=true&outrightsDisplay=none&marketTypeIds=&couponType=0&marketGroupId=0', sport: 'football', league: 'la');
// // // // premier-league

getMatchesSourceLvbet('https://offer.lvbet.lv/client-api/v3/matches/competition-view/?lang=en&sports_groups_ids=37685', sport: 'football', league: 'premier', url_string: 'https://lvbet.lv/sports/en/pre-matches/football/england/premier-league/teams_string/--/1/35148/37685/match_id/');
getMatchesSourceBetsafe('https://www.betsafe.lv/api/sb/v1/widgets/events-table/v2?categoryIds=1&competitionIds=3&eventSortBy=StartDate&maxMarketCount=3&pageNumber=1', sport: 'football', league: 'premier');
// sleep(2);
getMatchesSourceMrgreen('94-premier-league', sport: 'football', league: 'premier');
// sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=538&game=22910774&region=2570001&type=0&sport=1&lang=en', sport: 'football', league: 'premier');
// sleep(2);
getMatchesSourceWilliamhill('94-premier-league', sport: 'football', league: 'premier');
// sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/england/premier_league/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693468032979&useCombined=true&useCombinedLive=true', sport: 'football', league: 'premier');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/467/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'premier');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/467/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'football', league: 'premier');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/football/england/premier_league/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1695810074532&useCombined=true&useCombinedLive=true', sport: 'football', league: 'premier');
getMatchesSourceTonybet('https://platform.tonybet.es/api/event/list?competitor1Id_neq=&competitor2Id_neq=&status_in%5B%5D=0&status_in%5B%5D=2&limit=150&main=1&relations%5B%5D=odds&relations%5B%5D=league&relations%5B%5D=result&relations%5B%5D=competitors&relations%5B%5D=withMarketsCount&relations%5B%5D=players&relations%5B%5D=sportCategories&relations%5B%5D=broadcasts&relations%5B%5D=statistics&relations%5B%5D=additionalInfo&leagueId_eq=1008013&oddsExists_eq=1&lang=es', sport: 'football', league: 'premier', url_string: 'https://tonybet.es/prematch/football/1008013-premier-league/match_id');
getMatchesSourceRabona('https://sb2frontend-altenar2.biahosted.com/api/Sportsbook/GetEvents?timezoneOffset=-180&langId=86&skinName=rabona&configId=12&culture=en-CA&countryCode=ES&deviceType=Desktop&numformat=en&integration=rabona&sportids=66&categoryids=0&champids=2936&group=AllEvents&period=periodall&withLive=true&outrightsDisplay=none&marketTypeIds=&couponType=0&marketGroupId=0', sport: 'football', league: 'premier');
// getMatchesSourceRollino('https://rollino.io/ca/sport?bt-path=%2Fsoccer%2Fengland%2Fpremier-league-1669818860469096448', sport: 'football', league: 'premier');
// // // // bundesliga-league;

getMatchesSourceLvbet('https://offer.lvbet.lv/client-api/v3/matches/competition-view/?lang=en&sports_groups_ids=37682', sport: 'football', league: 'bundes', url_string: 'https://lvbet.lv/sports/en/pre-matches/football/germany/bundesliga/teams_string/--/1/35223/37682/match_id/');
getMatchesSourceBetsafe('https://www.betsafe.lv/api/sb/v1/widgets/events-table/v2?categoryIds=1&competitionIds=15&eventSortBy=StartDate&maxMarketCount=3&pageNumber=1', sport: 'football', league: 'bundes');
getMatchesSourceMrgreen('268-bundesliga', sport: 'football', league: 'bundes');
// sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=541&game=22910774&region=900001&type=0&sport=1&lang=en', sport: 'football', league: 'bundes');
// sleep(2);
getMatchesSourceWilliamhill('268-bundesliga', sport: 'football', league: 'bundes');
// sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/germany/bundesliga/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693468032979&useCombined=true&useCombinedLive=true', sport: 'football', league: 'bundes');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/476/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'bundes');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/476/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'football', league: 'bundes');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/germany/bundesliga/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693468032979&useCombined=true&useCombinedLive=true', sport: 'football', league: 'bundes');
getMatchesSourceTonybet('https://platform.tonybet.es/api/event/list?competitor1Id_neq=&competitor2Id_neq=&status_in%5B%5D=0&status_in%5B%5D=2&limit=150&main=1&relations%5B%5D=odds&relations%5B%5D=league&relations%5B%5D=result&relations%5B%5D=competitors&relations%5B%5D=withMarketsCount&relations%5B%5D=players&relations%5B%5D=sportCategories&relations%5B%5D=broadcasts&relations%5B%5D=statistics&relations%5B%5D=additionalInfo&leagueId_eq=1008027&oddsExists_eq=1&lang=es', sport: 'football', league: 'bundes', url_string: 'https://tonybet.es/prematch/football/1008027-bundesliga/match_id');
getMatchesSourceRabona('https://sb2frontend-altenar2.biahosted.com/api/Sportsbook/GetEvents?timezoneOffset=-180&langId=86&skinName=rabona&configId=12&culture=en-CA&countryCode=ES&deviceType=Desktop&numformat=en&integration=rabona&sportids=66&categoryids=0&champids=2950&group=AllEvents&period=periodall&withLive=true&outrightsDisplay=none&marketTypeIds=&couponType=0&marketGroupId=0', sport: 'football', league: 'bundes');
// // // nhl

getMatchesSourceLvbet('https://offer.lvbet.lv/client-api/v3/matches/competition-view/?lang=en&sports_groups_ids=35109', sport: 'hockey', league: 'nhl', url_string: 'https://lvbet.lv/sports/en/pre-matches/ice-hockey/north-america/nhl/teams_string/--/2/34642/35109/match_id/');
getMatchesSourceBetsafe('https://www.betsafe.lv/api/sb/v1/widgets/events-table/v2?categoryIds=2&competitionIds=50&eventSortBy=StartDate&maxMarketCount=3&pageNumber=1', sport: 'hockey', league: 'nhl');
getMatchesSourceMrgreen('6-nhl', sport: 'hockey', league: 'nhl');
// sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=19207&game=22675876&region=50002&type=0&sport=2&lang=en', sport: 'hockey', league: 'nhl');
// sleep(2);
getMatchesSourceWilliamhill('6-nhl', sport: 'hockey', league: 'nhl');
// sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/ice_hockey/nhl/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1695807808499&category=11974&useCombined=true&useCombinedLive=true', sport: 'hockey', league: 'nhl');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/2255/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'hockey', league: 'nhl');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/2255/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'hockey', league: 'nhl');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/ice_hockey/nhl/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1695810250733&category=11974&useCombined=true&useCombinedLive=true', sport: 'hockey', league: 'nhl');
getMatchesSourceTonybet('https://platform.tonybet.es/api/event/list?competitor1Id_neq=&competitor2Id_neq=&status_in%5B%5D=0&status_in%5B%5D=2&limit=150&main=1&relations%5B%5D=odds&relations%5B%5D=league&relations%5B%5D=result&relations%5B%5D=competitors&relations%5B%5D=withMarketsCount&relations%5B%5D=players&relations%5B%5D=sportCategories&relations%5B%5D=broadcasts&relations%5B%5D=statistics&relations%5B%5D=additionalInfo&leagueId_eq=1013499&oddsExists_eq=1&lang=es', sport: 'hockey', league: 'nhl', url_string: 'https://tonybet.es/prematch/ice-hockey/1013499-nhl/match_id/');
getMatchesSourceRabona('https://sb2frontend-altenar2.biahosted.com/api/Sportsbook/GetEvents?timezoneOffset=-180&langId=86&skinName=rabona&configId=12&culture=en-CA&countryCode=ES&deviceType=Desktop&numformat=en&integration=rabona&sportids=70&categoryids=0&champids=3232&group=AllEvents&period=periodall&withLive=true&outrightsDisplay=none&marketTypeIds=&couponType=0&marketGroupId=0', sport: 'hockey', league: 'nhl');
// // // //nba

getMatchesSourceLvbet('https://offer.lvbet.lv/client-api/v3/matches/competition-view/?lang=en&sports_groups_ids=61118', sport: 'basketball', league: 'nba', url_string: 'https://lvbet.lv/sports/en/pre-matches/basketball/united-states-of-america/nba/teams_string/--/3/60762/61118/match_id/');
getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/basketball/nba', sport: 'basketball', league: 'nba');
getMatchesSourceMrgreen('206-nba', sport: 'basketball', league: 'nba');
// getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=756&game=22538956&region=50003&type=0&sport=3&lang=en', sport: 'basketball', league: 'nba');
// sleep(2);
getMatchesSourceWilliamhill('206-nba', sport: 'basketball', league: 'nba');
// sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/basketball/nba/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693469200471&useCombined=true&useCombinedLive=true', sport: 'basketball', league: 'nba');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/466/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'basketball', league: 'nba');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/466/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'basketball', league: 'nba');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/basketball/nba/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1693491966107&useCombined=true&useCombinedLive=true', sport: 'basketball', league: 'nba');
getMatchesSourceTonybet('https://platform.tonybet.es/api/event/list?competitor1Id_neq=&competitor2Id_neq=&status_in%5B%5D=0&status_in%5B%5D=2&limit=150&main=1&relations%5B%5D=odds&relations%5B%5D=league&relations%5B%5D=result&relations%5B%5D=competitors&relations%5B%5D=withMarketsCount&relations%5B%5D=players&relations%5B%5D=sportCategories&relations%5B%5D=broadcasts&relations%5B%5D=statistics&relations%5B%5D=additionalInfo&leagueId_eq=1008918&oddsExists_eq=1&lang=es', sport: 'basketball', league: 'nba', url_string: 'https://tonybet.es/prematch/basketball/1008918-nba/match_id');
getMatchesSourceRabona('https://sb2frontend-altenar2.biahosted.com/api/Sportsbook/GetEvents?timezoneOffset=-180&langId=86&skinName=rabona&configId=12&culture=en-CA&countryCode=ES&deviceType=Desktop&numformat=en&integration=rabona&sportids=67&categoryids=0&champids=2980&group=AllEvents&period=periodall&withLive=true&outrightsDisplay=none&marketTypeIds=&couponType=0&marketGroupId=0', sport: 'basketball', league: 'nba');
