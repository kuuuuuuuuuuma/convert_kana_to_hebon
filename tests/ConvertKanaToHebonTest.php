<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../class-convert-kana-to-hebon.php';

class ConvertKanaToHebonTest extends TestCase {

    public function test_input_case() {
        $random_case = json_decode( file_get_contents( __DIR__ .  '/json/input.json' ) );
        foreach ( $random_case as $case ) {
            $hebon = new Convert_Kana_To_Hebon( $case->kana );
            self::assertSame( $case->hebon, $hebon->get_hebon() );
        }
    }

    public function test_random_case() {
        $random_case = json_decode( file_get_contents( __DIR__ .  '/json/random.json' ) );
        foreach ( $random_case as $case ) {
            $hebon = new Convert_Kana_To_Hebon( $case->kana );
            self::assertSame( $case->hebon, $hebon->get_hebon() );
        }
    }

    public function test_cyouon_case() {
        $random_case = json_decode( file_get_contents( __DIR__ .  '/json/cyouon.json' ) );
        foreach ( $random_case as $case ) {
            $hebon = new Convert_Kana_To_Hebon( $case->kana );
            self::assertSame( $case->hebon, $hebon->get_hebon() );
        }
    }

    public function test_hatsuon_case() {
        $random_case = json_decode( file_get_contents( __DIR__ .  '/json/hatsuon.json' ) );
        foreach ( $random_case as $case ) {
            $hebon = new Convert_Kana_To_Hebon( $case->kana );
            self::assertSame( $case->hebon, $hebon->get_hebon() );
        }
    }

    public function test_sokuon_case() {
        $random_case = json_decode( file_get_contents( __DIR__ .  '/json/sokuon.json' ) );
        foreach ( $random_case as $case ) {
            $hebon = new Convert_Kana_To_Hebon( $case->kana );
            
            self::assertSame( $case->hebon, $hebon->get_hebon() );
        }
    }

    public function test_rare_case() {
        $random_case = json_decode( file_get_contents( __DIR__ .  '/json/rare.json' ) );
        foreach ( $random_case as $case ) {
            $hebon = new Convert_Kana_To_Hebon( $case->kana );
            
            self::assertSame( $case->hebon, $hebon->get_hebon() );
        }
    }

    public function test_option() {
        $random_case = json_decode( file_get_contents( __DIR__ .  '/json/option.json' ) );
        foreach ( $random_case as $case ) {
            $hebon = new Convert_Kana_To_Hebon( $case->kana, [
                'おんもり' => 'ONMORI',
                'おおの'   => 'OHNO'
            ] );
            
            self::assertSame( $case->hebon, $hebon->get_hebon() );
        }
    }
}