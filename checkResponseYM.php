<?php
$t = [
"Пароль: 0885
Спишется 1,01р.
Перевод на счет 410015625543861",
"пароль 0885
Спишется: 1,01
Перевод на счет 410015625543861",
"Пароль 088513
спишется 123,01
Перевод средств на счет 410015625543861",
"Это просто так текст
спишется 123,01
Пароль 088513
Перевод средств на счет 410015625543861",
"Недостаточно средств.",
"Сумма указана неверно.",
];

foreach ($t as $s) {
    try {
        var_dump(checkResponseYM($s));
    } catch (\Exception $e) {
        var_dump($e->getMessage(), $e->data);
    }
}

function checkResponseYM(string $str): array {
    $status = 0;
    $result = [
        'code' => 'undefined',
        'value' => 'undefined',
        'wallet' => 'undefined',
    ];
    if (preg_match('/[Пп]ароль:?\s*(\d{4,})/mi', $str, $rr)) {
        $result['code'] = $rr[1];
        $status++;
    }
    if (preg_match('/[Сс]пишется:?\s*([0-9,]+)/mi', $str, $rr)) {
        $result['value'] = str_replace(',', '.', $rr[1]);
        $status++;
    }
    if (preg_match('/[Пп]еревод.*счет\s(\d{15,})/mi', $str, $rr)) {
        $result['wallet'] = $rr[1];
        $status++;
    }
    if ($status == 3) return $result;
    $e = new \Exception('Unresolved response');
    $e->{'data'} = $result;
    throw($e);
}
