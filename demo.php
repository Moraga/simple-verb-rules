<?php

header('content-type: text/plain; charset=utf-8');

$ruls = [
    // verbal número-pessoal
    ['o', [
        'vb' => ['ps' => '1s']]],
    ['s', [
        'vb' => ['ps' => '2s']]],
    ['mos', [
        'vb' => ['ps' => '1p']]],
    ['is', [
        'vb' => ['ps' => '2p']]],
    ['m', [
        'vb' => ['ps' => '3p']]],

    // duvida
    ['es', [
        'vb' => ['ps' => '2s', 'prev-not' => 's'] // cláusula
        ]],

    // temporal
    ['va', [
        'vb' => ['tp' => 'pret imper do indi']
        ]],
    ['ia', [
        'vb' => ['tp' => 'pref imper do indic']
        ]],
    ['ra', [
        'vb' => ['tp' => 'pret mais-que-perf do indi']
        ]],

    // futuro
    ['ar', [
        'vb' => ['tp' => 'futuro']
        ]],
    ['ard', [
        'vb' => ['tp' => 'futuro', 'ps' => '2p']
        ]],

    // futuro do presente
    ['rá', [
        'vb' => ['tp' => 'fut do presente']
        ]],
    ['arei', [
        'vb' => ['tp' => 'fut do presente', 'ps' => '1s']
        ]],
    ['erei', [
        'vb' => ['tp' => 'fut do presente', 'ps' => '1s']
        ]],
    ['irei', [
        'vb' => ['tp' => 'fut do presente', 'ps' => '1s']
        ]],
    ['are', [
        'vb' => ['tp' => 'fut do presente']
        ]],
    ['ere', [
        'vb' => ['tp' => 'fut do presente']
        ]],
    ['ire', [
        'vb' => ['tp' => 'fut do presente']
        ]],
    ['arao', [
        'vb' => ['tp' => 'fut do present', 'ps' => '3p']
        ]],
    ['erao', [
        'vb' => ['tp' => 'fut do present', 'ps' => '3p']
        ]],
    ['irao', [
        'vb' => ['tp' => 'fut do present', 'ps' => '3p']
        ]],

    // futuro do pretérito
    ['ria', [
        'vb' => ['tp' => 'fut do pret do indi']
        ]],
    ['rie', [
        'vb' => ['tp' => 'fut do pret do indi', 'ps' => '2p'],
        ]],

    ['re', [
        'vb' => ['tp' => 'pret mais q perf do indi'],
        ]],
    ['sse', [
        'vb' => ['tp' => 'pret imper do subj']
        ]],

    ['a', [
        'vt' => ['vt' => 'a'],
        'gn' => ['gn' => 'fem']
        ]],

    ['e', [
        'vt' => ['vt' => 'e']
        ]]
];

foreach ($ruls as &$rule)
    array_splice($rule, 1, 0, [strlen($rule[0]) * -1]);

usort($ruls, function($a, $b) {
    if ($a[1] == $b[1])
        return 0;
    return $a[1] > $b[1] ? 1 : -1;
});

// print_r($ruls); exit;

array_map(function($str) {
    echo "\n$str\n";
    $class = [];
    $ret = parse($str, $class);
    echo $ret;
    $space = 20 - strlen($ret);
    foreach ($class as $k => $v) {
        echo str_repeat(' ', $space) . $k;
        krsort($v);
        foreach ($v as $s => $i)
            // echo " [$s $i]";
            echo " $i";
        echo "\n";
        $space = 20;
    }
}, [
    'cantar', 'cantares', 'cantarmos', 'cantardes', 'cantarem',

    // pretérito imperfeito
    'vendesse', 'vendesses', 'vendessemos', 'vendesseis', 'vendessem',
    'cantasse', 'cantasses', 'cantassemos', 'cantasseis', 'cantassem',

    // futuro do pretérito
    'partiria', 'partirias', 'partiriamos', 'partirieis', 'partiriam',
    'venderia', 'venderias', 'venderiamos', 'venderieis', 'venderiam',
    'cantaria', 'cantarias', 'cantariamos', 'cantarieis', 'cantariam',

    // futuro do presente
    'partirei', 'partirás', 'partirá', 'partiremos', 'partireis', 'partirao',
    'venderei', 'venderás', 'venderá', 'venderemos', 'vendereis', 'venderao',
    'cantarei', 'cantarás', 'cantará', 'cantaremos', 'cantareis', 'cantarao',


    'cantara', 'cantaras', 'cantaramos', 'cantareis', 'cantaram',
    'partira', 'partiras', 'partiramos', 'partireis', 'partiram',
    'vendera', 'venderas', 'venderamos', 'vendereis', 'venderam',
    'canto', 'cantas', 'canta', 'cantamos', 'cantais', 'cantam',

    'cantavamos',
    'cantavas',
    'cantavam',
]);

function parse($str, &$class=[]) {
    global $ruls;

    foreach ($ruls as list($sufx, $slce, $rule)) {
        if (substr($str, $slce) == $sufx && ($sufx != 'es' || substr($str, -4)[1] != 's')) {
            if (isset($class['vb']['pess']))
                // $keep = ['vb' => ['pess' => $rule['vb']['pess']]];
                unset($rule['vb']['pess']);

            $class = array_replace_recursive($class, $rule);

            return parse(substr($str, 0, $slce), $class);
        }
    }

    if (strpos('aeiou', substr($str, -1)) !== false && isset($class['vb'])) {
        $str = substr($str, 0, -1);
        if (!isset($class['vb']['tp']))
            $class['vb']['tp'] = 'presente';
    }

    return $str;
}

?>
