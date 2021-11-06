# convert_kana_to_hebon
かな(カナ)からヘボン式ローマ字に変換できます。

# 使用
「class-convert-kana-to-hebon.php」を取り込んで使用してください。

# 使い方
```php
$name = 'てすと　てすこ';
$hebon = new Convert_Kana_To_Hebon( $name );
echo $hebon->get_hebon();

// 結果
Array
(
    [0] => TESUTO
    [1] => TESUKO
)
```

# 入力
```php
// 半角スペース
$name = 'てすと てすこ';

// 全角スペース
$name = 'てすと　てすこ';

// 配列
$name = [
  'てすと',
  'てすこ'
];
```

# 例外形
例外形を追加することも可能です。
インスタンス生成時の第二引数に指定してください。

デフォルトでは「のうえ」「まつうら」「こうちわ」が設定されています。
長音だけれど読む場合、任意で変更したい場合に使用してください。

例)
```php
$name = 'てすと　てすこ';
$option = [
  'てすこ' => 'TESUKOOOO'
];
$hebon = new Convert_Kana_To_Hebon( $name, $option );
echo $hebon->get_hebon();

// 結果
Array
(
    [0] => TESUKOOOO
    [1] => TESUKO
)
```

# LICENSE
MIT LICENSE
