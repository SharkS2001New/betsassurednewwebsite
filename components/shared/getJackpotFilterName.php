<?php
function returnJackpotNameSavedInDB($current_url) {
    if ($current_url === '/sportpesa-mega-jackpot-predictions') {
        return "Sportpesa Mega Jackpot";
    } elseif ($current_url === '/sportpesa-midweek-jackpot-predictions') {
        return "Sportpesa Midweek Jackpot";
    } elseif ($current_url === '/betika-grand-jackpot-predictions') {
        return "Betika Mega Jackpot";
    } elseif ($current_url === '/betika-midweek-jackpot-predictions') {
        return "Betika Midweek Jackpot";
    } elseif ($current_url === '/betika-sababisha-jackpot-predictions') {
        return "Betika Sababisha Jackpot";
    } elseif ($current_url === '/odibet-laki-tatu-daily-jackpot-predictions') {
        return "Odibet Laki Tatu Jackpot";
    } elseif ($current_url === '/mozzart-daily-jackpot-predictions') {
        return "Mozzart Super Daily Jackpot";
    } elseif ($current_url === '/mozzart-super-grand-jackpot-predictions') {
        return "Mozzart Bet Grand Jackpot";
    } elseif ($current_url === '/shabiki-jackpot-predictions') {
        return "Shabiki Midweek Jackpot";
    } elseif ($current_url === '/sportybet-jackpot-predictions') {
        return "Sporty Bet Jackpot";
    } elseif ($current_url === '/betlion-daily-jp-jackpot-predictions') {
        return "Betlion Daily Jackpot";
    } elseif ($current_url === '/betlion-goliath-jackpot-predictions') {
        return "Betlion Goliath Jackpot";
    } elseif ($current_url === '/betika-kitonga-jackpot-tz') {
        return "Betika Kitonga Tanzania";
    } elseif ($current_url === '/sportpesa-supa-jackpot-13-predictions-tz') {
        return "Sportpesa Midweek Tanzania Jackpot";
    } elseif ($current_url === '/sportpesa-supa-jackpot-17-predictions-tz') {
        return "Sportpesa Supa Jackpot Tanzania";
    } elseif ($current_url === '/betway-jackpot-predictions-uganda') {
        return "Betway Uganda Jackpot";
    } elseif ($current_url === '/betway-jackpot-predictions-kenya') {
        return "Betway Kenya Jackpot";
    } elseif ($current_url === '/betway-jackpot-predictions-tanzania') {
        return "Betway Tanzania Jackpot";
    } elseif ($current_url === '/betsafe-daily-jackpot-predictions') {
        return "Betsafe Daily Jackpot";
    } elseif ($current_url === '/betsafe-mita-tano-jackpot-predictions') {
        return "Betsafe Mita Tano Jackpot";
    } elseif ($current_url === '/22-bet-toto-jackpot-predictions') {
        return "22 Bet Toto Jackpot";
    } elseif ($current_url === '/bet9ja-supa9ja-jackpot-predictions') {
        return "Bet9ja Supa9ja Jackpot";
    } elseif ($current_url === '/1xbet-toto-15-jackpot-predictions') {
        return "1XBet Toto 15 Jackpot";
    } elseif ($current_url === '/merrybet-jackpot-predictions') {
        return "MerryBet Jackpot";
    } elseif ($current_url === '/betking-jackpot-predictions') {
        return "BetKing Jackpot";
    } elseif (in_array($current_url, [
        '/betpawa-pick13-jackpot-predictions-uganda',
        '/betpawa-pick13-jackpot-predictions-nigeria',
        '/betpawa-pick13-jackpot-predictions-kenya',
        '/betpawa-pick13-jackpot-predictions-dr-congo',
        '/betpawa-pick13-jackpot-predictions-tanzania',
        '/betpawa-pick13-jackpot-predictions-zambia',
        '/betpawa-pick13-jackpot-predictions-ghana',
        '/betpawa-pick13-jackpot-predictions-cameroon'
    ])) {
        return "Betpawa Pick13 Jackpot";
    } elseif (in_array($current_url, [
        '/betpawa-pick17-jackpot-predictions-uganda',
        '/betpawa-pick17-jackpot-predictions-nigeria',
        '/betpawa-pick17-jackpot-predictions-kenya',
        '/betpawa-pick17-jackpot-predictions-dr-congo',
        '/betpawa-pick17-jackpot-predictions-tanzania',
        '/betpawa-pick17-jackpot-predictions-zambia',
        '/betpawa-pick17-jackpot-predictions-ghana',
        '/betpawa-pick17-jackpot-predictions-cameroon'
    ])) {
        return "Betpawa Pick 17 Jackpot";
    } else {
        return "Unknown Jackpot";
    }
}
?>
