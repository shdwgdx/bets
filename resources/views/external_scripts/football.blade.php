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
require 'lvbet.blade.php';
require 'mrgreen.blade.php';
require 'olybet.blade.php';
require 'klondaika.blade.php';
require 'feniks.blade.php';
require 'pafbet.blade.php';
require 'optibet.blade.php';

// csgo

getMatchesSourceLvbetCsgo('https://lvbet.lv/sports/en/pre-matches/counter-strike-go-cs:go/world/--/51/7453/');
sleep(2);
getMatchesSourceBetsafeCsgo('https://www.betsafe.lv/en/sportsbook/esports/counter-strike-go');
sleep(2);
getMatchesSourceMrgreenCsgo('https://www.mrgreen.lv/en/sports/category/2120-cs-go');
sleep(2);
getMatchesSourceOptibetCsgo('https://sb-data.optibet.lv/en/events/upcoming/wcg?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&hoursDelay=72');
getMatchesSourceKlondaikaCsgo('https://sb-data.optibet.lv/en/events/upcoming/wcg?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&hoursDelay=72');
getMatchesSourcePafbetCsgo('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/esports/cs_go/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1693499539500&useCombined=true&useCombinedLive=true');
// champions-league

getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/football/europe/uefa-champions-league-qualification/--/1/35259/57921/', sport: 'football', league: 'champions');
sleep(2);
getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/football/champions-league/champions-league', sport: 'football', league: 'champions');
sleep(2);
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/6674-champions-league', sport: 'football', league: 'champions');
sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=18286520&game=22912934&region=20001&type=0&sport=1&lang=en', sport: 'football', league: 'champions');
sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/spain/la_liga/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693468032979&useCombined=true&useCombinedLive=true', sport: 'football', league: 'champions');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/476/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'champions');
getMatchesSourceOptibet('https://sb-data.klondaika.lv/en/events/group/476/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'champions');
// //europe-league

getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/football/europe/uefa-europa-league-qualification/--/1/35259/65045/', sport: 'football', league: 'europe');
sleep(2);
getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/football/europa-league/europa-league', sport: 'football', league: 'europe');
sleep(2);
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/6675-europa-league', sport: 'football', league: 'europe');
sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=18275688&game=22910774&region=20001&type=0&sport=1&lang=en', sport: 'football', league: 'europe');
sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/europa_league_qualification/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693468881399&useCombined=true&useCombinedLive=true', sport: 'football', league: 'europe');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/1546/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'europe');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/1546/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'football', league: 'europe');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/football/europa_league_qualification/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1693493080494&useCombined=true&useCombinedLive=true', sport: 'football', league: 'europe');
// //la-league

getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/football/spain/la-liga/--/1/35306/41533/', sport: 'football', league: 'la');
sleep(2);
getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/football/spain/spain-la-liga', sport: 'football', league: 'la');
sleep(2);
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/306-laliga', sport: 'football', league: 'la');
sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=545&game=22910774&region=2150001&type=0&sport=1&lang=en', sport: 'football', league: 'la');
sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/spain/la_liga/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693468032979&useCombined=true&useCombinedLive=true', sport: 'football', league: 'la');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/543/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'la');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/543/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'football', league: 'la');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/football/spain/la_liga/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1693491610695&useCombined=true&useCombinedLive=true', sport: 'football', league: 'la');
// // premier-league

getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/football/england/premier-league/--/1/35148/37685/', sport: 'football', league: 'premier');
sleep(2);
getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/football/england/england-premier-league-epl', sport: 'football', league: 'premier');
sleep(2);
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/94-premier-league', sport: 'football', league: 'premier');
sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=538&game=22910774&region=2570001&type=0&sport=1&lang=en', sport: 'football', league: 'premier');
sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/england/premier_league/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693468032979&useCombined=true&useCombinedLive=true', sport: 'football', league: 'premier');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/467/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'premier');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/467/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'football', league: 'premier');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/football/england/premier_league/all/competitions.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1693491776517', sport: 'football', league: 'premier');
// // bundesliga-league;

getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/football/germany/bundesliga/--/1/35223/37682/', sport: 'football', league: 'bundes');
sleep(2);
getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/football/germany/germany-bundesliga', sport: 'football', league: 'bundes');
sleep(2);
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/268-bundesliga', sport: 'football', league: 'bundes');
sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=541&game=22910774&region=900001&type=0&sport=1&lang=en', sport: 'football', league: 'bundes');
sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/germany/bundesliga/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693468032979&useCombined=true&useCombinedLive=true', sport: 'football', league: 'bundes');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/476/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'football', league: 'bundes');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/476/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'football', league: 'bundes');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/football/germany/bundesliga/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693468032979&useCombined=true&useCombinedLive=true', sport: 'football', league: 'bundes');
// nhl

getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/ice-hockey/north-america/nhl/--/2/34642/35109/', sport: 'hockey', league: 'nhl');
sleep(2);
getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/ice-hockey/nhl/nhl', sport: 'hockey', league: 'nhl');
sleep(2);
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/6-nhl', sport: 'hockey', league: 'nhl');
sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=19207&game=22675876&region=50002&type=0&sport=2&lang=en', sport: 'hockey', league: 'nhl');
sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/ice_hockey/nhl/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693760314009&useCombined=true&useCombinedLive=true', sport: 'hockey', league: 'nhl');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/2255/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'hockey', league: 'nhl');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/2255/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'hockey', league: 'nhl');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/ice_hockey/nhl/all/all/competitions.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1693491852466', sport: 'hockey', league: 'nhl');
// //nba

getMatchesSourceLvbet('https://lvbet.lv/sports/en/pre-matches/basketball/united-states-of-america/nba/--/3/60762/61118/', sport: 'basketball', league: 'nba');
sleep(2);
getMatchesSourceBetsafe('https://www.betsafe.lv/en/sportsbook/basketball/nba', sport: 'basketball', league: 'nba');
sleep(2);
getMatchesSourceMrgreen('https://www.mrgreen.lv/en/sports/competition/206-nba', sport: 'basketball', league: 'nba');
sleep(2);
getMatchesSourceOlybet('https://www.olybet.lv/sports?competition=756&game=22538956&region=50003&type=0&sport=3&lang=en', sport: 'basketball', league: 'nba');
sleep(2);
getMatchesSourcePafbet('https://eu-offering-api.kambicdn.com/offering/v2018/paflv/listView/basketball/nba/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=3&ncid=1693469200471&useCombined=true&useCombinedLive=true', sport: 'basketball', league: 'nba');
getMatchesSourceKlondaika('https://sb-data.klondaika.lv/en/events/group/466/?domainId=17&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOltdfQ==', sport: 'basketball', league: 'nba');
getMatchesSourceOptibet('https://sb-data.optibet.lv/en/events/group/466/?domainId=0&gameTypes=1,3,9,10,15,18,21,16,19,22,11,12,24,2,108,112,59,34,37,40,43,35,38,41,44,95,4,109&lsp=eyJ1cmwiOiJodHRwczovL3ZzdHJlYW1lci5lbmxhYnMuc2VydmljZXMiLCJwcm92aWRlcl9pZHMiOlsxLDJdfQ==', sport: 'basketball', league: 'nba');
getMatchesSourceFeniks('https://eu-offering-api.kambicdn.com/offering/v2018/gtlv/listView/basketball/nba/all/all/matches.json?lang=en_GB&market=LV&client_id=2&channel_id=1&ncid=1693491966107&useCombined=true&useCombinedLive=true', sport: 'basketball', league: 'nba');
