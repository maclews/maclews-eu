<?php

namespace App\Repository;

class ToolsRepository
{
    private static function code128image(string $data, int $height = 60, int $scale = 1): string
    {
        $code_string = '';
        $chksum = 104;
        // Must not change order of array elements as the checksum depends on the array's key to validate final code
        $code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "\`" => "111422", "a" => "121124", "b" => "121421", "c" => "141122", "d" => "141221", "e" => "112214", "f" => "112412", "g" => "122114", "h" => "122411", "i" => "142112", "j" => "142211", "k" => "241211", "l" => "221114", "m" => "413111", "n" => "241112", "o" => "134111", "p" => "111242", "q" => "121142", "r" => "121241", "s" => "114212", "t" => "124112", "u" => "124211", "v" => "411212", "w" => "421112", "x" => "421211", "y" => "212141", "z" => "214121", "{" => "412121", "|" => "111143", "}" => "111341", "~" => "131141", "DEL" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "FNC 4" => "114131", "CODE A" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
        $code_keys = array_keys($code_array);
        $code_vals = array_flip($code_keys);
        for ($x = 1, $xMax = strlen($data); $x <= $xMax; $x++) {
            $active_key = $data[($x - 1)];
            $code_string .= $code_array[$active_key];
            $chksum += $code_vals[$active_key] * $x;
        }
        $code_string .= $code_array[$code_keys[$chksum - (intval($chksum / 103) * 103)]];
        $code_string = '211214' . $code_string . '2331112';
        // Minimum padding of 10 each side
        $padding = 10;
        // + 1 because reasons
        $code_length = $padding * 2 + 1;
        for ($i = 1, $iMax = strlen($code_string); $i <= $iMax; $i++) {
            $code_length += (int)$code_string[$i - 1];
        }
        $img_width = $code_length * $scale;
        $img_height = $height * $scale;
        $image = imagecreate($img_width, $img_height);
        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);
        $location = $padding;
        for ($pos = 1, $posMax = strlen($code_string); $pos <= $posMax; $pos++) {
            $cur_size = $location . $code_string[$pos - 1];
            imagefilledrectangle(
                $image,
                $location * $scale,
                0,
                $cur_size * $scale,
                $img_height,
                ($pos % 2) ? $black : $white
            );
            $location = $cur_size;
        }
        ob_start();
        imagegif($image);
        $img = ob_get_clean();
        return 'data:image/gif;base64,' . base64_encode($img);
    }

    public static function code128table(array $input): array
    {
        $s = '';
        $i = 0;
        $a = 0;
        foreach ($input as $c) {
            if (empty($c)) {
                continue;
            }
            if ($i > 3) {
                $s .= "\n</tr>\n<tr>";
                $i = 0;
            }
            $s .= "\n<td><img src=\"" . self::code128image($c, 20) . "\" alt=\"\"><br><small>{$c}</small></td>";
            $i++;
            $a++;
        }
        return [ $s . PHP_EOL , $a ];
    }
}