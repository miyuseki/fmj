<?php
function parseStringToArray($input)
{
    // 文字列を "／" で分割
    $parts = explode('/', $input);

    // 配列の長さが奇数の場合、エラーを返す
    if (count($parts) % 2 !== 0) {
        throw new Exception("入力文字列が不正です。キーと値のペアが必要です。");
    }

    // 空の連想配列を作成
    $result = [];

    // キーと値のペアを連想配列に格納
    for ($i = 0; $i < count($parts); $i += 2) {
        $key = $parts[$i];
        $value = $parts[$i + 1];
        $result[$key] = $value;
    }

    return $result;
}