<?php
/**
 * かなをヘボン式ローマ字に変換
 */

class Convert_Kana_To_Hebon {

    /**
     * かな、読み対応
     */
    public const KANA_ALPHABET_TABLE = [
        'あ' => 'A',  'い' => 'I',   'う' => 'U',   'え' => 'E',  'お' => 'O',
        'か' => 'KA', 'き' => 'KI',  'く' => 'KU',  'け' => 'KE', 'こ' => 'KO',
        'さ' => 'SA', 'し' => 'SHI', 'す' => 'SU',  'せ' => 'SE', 'そ' => 'SO',
        'た' => 'TA', 'ち' => 'CHI', 'つ' => 'TSU', 'て' => 'TE', 'と' => 'TO',
        'な' => 'NA', 'に' => 'NI',  'ぬ' => 'NU',  'ね' => 'NE', 'の' => 'NO',
        'は' => 'HA', 'ひ' => 'HI',  'ふ' => 'HU',  'へ' => 'HE', 'ほ' => 'HO',
        'ま' => 'MA', 'み' => 'MI',  'む' => 'MU',  'め' => 'ME', 'も' => 'MO',
        'や' => 'YA', 'ゆ' => 'YU',  'よ' => 'YO',
        'ら' => 'RA', 'り' => 'RI',  'る' => 'RU',  'れ' => 'RE', 'ろ' => 'RO',
        'わ' => 'WA', 'ゐ' => 'I',   'ゑ' => 'E',   'を' => 'O',
        'が' => 'GA', 'ぎ' => 'GI',  'ぐ' => 'GU',  'げ' => 'GE', 'ご' => 'GO',
        'ざ' => 'ZA', 'じ' => 'JI',  'ず' => 'ZU',  'ぜ' => 'ZE', 'ぞ' => 'ZO',
        'だ' => 'DA', 'ぢ' => 'JI',  'づ' => 'ZU',  'で' => 'DE', 'ど' => 'DO',
        'ば' => 'BA', 'び' => 'BI',  'ぶ' => 'BU',  'べ' => 'BE', 'ぼ' => 'BO',
        'ぱ' => 'PA', 'ぴ' => 'PI',  'ぷ' => 'PU',  'ぺ' => 'PE', 'ぽ' => 'PO',
        'きゃ' => 'KYA', 'きゅ' => 'KYU', 'きょ' => 'KYO',
        'しゃ' => 'SHA', 'しゅ' => 'SHU', 'しょ' => 'SHO',
        'ちゃ' => 'CHA', 'ちゅ' => 'CHU', 'ちょ' => 'CHO',
        'にゃ' => 'NYA', 'にゅ' => 'NYU', 'にょ' => 'NYO',
        'ひゃ' => 'HYA', 'ひゅ' => 'HYU', 'ひょ' => 'HYO',
        'みゃ' => 'MYA', 'みゅ' => 'MYU', 'みゅ' => 'MYO',
        'りゃ' => 'RYA', 'りゅ' => 'RYU', 'りょ' => 'RYO',
        'ぎゃ' => 'GYA', 'ぎゅ' => 'GYU', 'ぎょ' => 'GYO',
        'じゃ' => 'JA',  'じゅ' => 'JU',  'じょ' => 'JO',
        'びゃ' => 'BYA', 'びゅ' => 'BYU', 'びょ' => 'BYO',
        'ぴゃ' => 'PYA', 'ぴゅ' => 'PYU', 'ぴょ' => 'PYO',
    ];

    /**
     * 変換不可　長音だけど読む
     * 部分一致変換
     */
    private const RARE_CASE = [
        'のうえ'   => 'NOUE',
        'こうちわ' => 'KOUCHIWA',
        'まつうら' => 'MATSUURA',
    ];

    /**
     * 先変換リスト
     *
     * @var array key: kana, value: hebon
     */
    private $rare_case;

    /**
     * 名前
     *
     * @var array
     */
    private $names;

    /**
     * コンストラクタ
     *
     * @param string|array $name
     * @param array $rare_case
     */
    public function __construct( $name, $rare_case = [] ) {
        $this->rare_case = array_merge( self::RARE_CASE, $rare_case );
        $this->set_name( $name );
    }

    /**
     * 名前セット
     *
     * @param string|array $name
     * @return void
     */
    private function set_name( $name ) {
        $arr_name = $name;
        if ( ! is_array( $name ) && is_string( $name ) ) {
            $name = preg_replace( '/　/', ' ', $name );
            $arr_name = explode( ' ', $name );
        }

        $this->names = $arr_name;
    }

    /**
     * ヘボン式ローマ字取得
     *
     * @return array
     */
    public function get_hebon() {
        if ( ! is_array( $this->names ) ) false;

        $hebon = [];
        foreach ( $this->names as $name ) {
            $hebon[] = $this->convert_name_to_hebon( $name );
        }

        return $hebon;
    }

    /**
     * 名前をヘボン式ローマ字に
     *
     * @param string $kana_name
     * @return string hebon name
     */
    private function convert_name_to_hebon( $kana_name ) {
        $kana_name = mb_convert_kana( $kana_name, 'Hc' );
        $kana_name = strtoupper( $kana_name );

        $kana_name = $this->rare_case_check( $kana_name );

        $alphabet_name = '';
        $last_char = [
            'kana'     => '',
            'alphabet' => ''
        ];

        for ( $i = 0 ; $i < mb_strlen( $kana_name, 'UTF-8' ) ; $i++ ) {
            $index_char = $this->index_char( $i, $kana_name );

            if ( 'ん' === $index_char['kana'] ) {
                $index_char['alphabet'] = $this->hatsuon( $i, $kana_name );
            }

            if ( 'っ' === $index_char['kana'] ) {
                $index_char['alphabet'] = $this->sokuon( $i, $kana_name );
            }

            if ( '' !== $index_char['alphabet'] ) {
                if ( '' !== $last_char['alphabet'] && ! preg_match( '/[A-Z]/', $index_char['kana'] ) ) {
                    $index_char['alphabet'] = $this->chouon( $i, $kana_name, $last_char, $index_char );
                }

                $alphabet_name .= $index_char['alphabet'];
            }

            if ( mb_strlen( $index_char['kana'], 'UTF-8' ) > 1 ) {
                $i++;
            }

            $last_char = $index_char;
        }

        return $alphabet_name;
    }

    /**
     * 指定のインデックスの対応アルファベット取得
     *
     * @param int $index
     * @param string $kana_name
     * @return array kana and alphabet
     */
    private function index_char( $index, $kana_name ) {
        if ( $index + 1 < mb_strlen( $kana_name, 'UTF-8' ) ) {
            if ( array_key_exists( mb_substr( $kana_name, $index, 2, 'UTF-8' ), self::KANA_ALPHABET_TABLE ) ) {
                return [
                    'kana'     => mb_substr( $kana_name, $index, 2, 'UTF-8' ),
                    'alphabet' => self::KANA_ALPHABET_TABLE[ mb_substr( $kana_name, $index, 2, 'UTF-8' ) ]
                ];
            }
        }

        if ( array_key_exists( mb_substr( $kana_name, $index, 1, 'UTF-8' ), self::KANA_ALPHABET_TABLE ) ) {
            return [
                'kana'     => mb_substr( $kana_name, $index, 1, 'UTF-8' ),
                'alphabet' => self::KANA_ALPHABET_TABLE[ mb_substr( $kana_name, $index, 1, 'UTF-8' ) ]
            ];
        }

        if ( preg_match( '/[A-Z]/', mb_substr( $kana_name, $index, 1, 'UTF-8' ) ) ) {
            return [
                'kana'     => mb_substr( $kana_name, $index, 1, 'UTF-8' ),
                'alphabet' => mb_substr( $kana_name, $index, 1, 'UTF-8' )
            ];
        }

        return [
            'kana'     => mb_substr( $kana_name, $index, 1, 'UTF-8' ),
            'alphabet' => ''
        ];
    }

    /**
     * 撥音対応「ん」
     *
     * @param int $index
     * @param string $kana_name
     * @return string M or N
     */
    private function hatsuon( $index, $kana_name ) {
        if ( $index + 1 < mb_strlen( $kana_name, 'UTF-8' ) ) {
            $next_index_char = $this->index_char( $index + 1, $kana_name );

            $targets = [
                'B', 'M', 'P'
            ];

            if ( in_array( mb_substr( $next_index_char['alphabet'], 0, 1, 'UTF-8' ), $targets, true ) ) {
                return 'M';
            }
        }

        return 'N';
    }

    /**
     * 促音「っ」
     *
     * @param int $index
     * @param string $kana_name
     * @return string T or next char
     */
    private function sokuon( $index, $kana_name ) {
        if (  $index + 1 >= mb_strlen( $kana_name, 'UTF-8' ) ) return '';

        $next_index_char = $this->index_char( $index + 1, $kana_name );

        if ( 0 === strpos( $next_index_char['alphabet'], 'CH', 0 ) ) {
            return 'T';
        }

        return mb_substr( $next_index_char['alphabet'], 0, 1, 'UTF-8' );
    }

    /**
     * 長音対応
     *
     * @param int $index
     * @param string $kana_name
     * @param array $last_char last changed pair
     * @param array $index_char now pair
     * @return string 
     */
    private function chouon( $index, $kana_name, $last_char, $index_char ) {
        $targets = [
            'AA',
            'UU',
            'EE',
            'OO',
            'OU'
        ];
        $join_char = $last_char['alphabet'] . $index_char['alphabet'];
        
        if ( 'お' === $index_char['kana'] && $index + 1 === mb_strlen( $kana_name, 'UTF-8' ) ) {
            return $index_char['alphabet'];
        }

        foreach ( $targets as $target ) {
            if ( preg_match( '/' . $target . '/', $join_char ) ) {
                return '';
            }
        }

        return $index_char['alphabet'];
    }

    /**
     * 例外形処理
     *
     * @param string $kana_name
     * @return string change alphabet if match rare case
     */
    private function rare_case_check( $kana_name ) {
        foreach ( $this->rare_case as $kana => $alphabet ) {
            if ( preg_match( '/' . $kana . '/', $kana_name ) ) {
                return preg_replace( '/' . $kana . '/', $alphabet, $kana_name );
            }
        }

        return $kana_name;
    }
}