<?php
/**
 * Created by PhpStorm.
 * User: demiin
 * Date: 23.07.15
 * Time: 9:52
 */
$strlen = 1000000;
$colsCount = "<tgroup cols=\"(\d)\">";
$content = "/<para>([\"«»\w\(\)\d\+\s\-\:\,\.№\/]*)<\/para>/u";
$fp = fopen('/tmp/1.xml', 'rt');
$curMP = 1;
if ($fp) {
    while (!feof($fp)) {
        $str = fgets($fp, $strlen);
        if (preg_match($colsCount, $str, $matches)) {
            $maxCols = $matches[1];
        }
        unset($matches);
        $pos_row = 0;
        $oldpos_row = $pos_row;
        while ($pos_row = strpos($str, "/row", $pos_row)) {
            $row = substr($str, $oldpos_row, $pos_row+5 - $oldpos_row);
            $pos_row += 5;
            $oldpos_row = $pos_row;
            $pos_entry = 0;
            $oldpos_entry = $pos_entry;
            $curEntry = 1;
            while ($pos_entry = strpos($row, "/entry", $pos_entry)) {
                $entry = substr($row, $oldpos_entry, $pos_entry+7 - $oldpos_entry);
                $pos_entry += 7;
                $oldpos_entry = $pos_entry;
                if (preg_match_all($content, $entry, $matches)) {
                    $mp[$curMP][$curEntry] = $matches[1];
                }
                ++$curEntry;
            }
            ++$curMP;
        }
    }
    fclose($fp);
}

if (sizeof($mp)) {
    echo "<p class=\"rtecenter\"><strong>".
          "<span style=\"color:#B22222\"><span style=\"font-size:14px\">Август</span></span></strong>".
          "</p>";
    echo "<table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody>";
    for ($curMP = 1; $curMP <= sizeof($mp); ++$curMP) {
        echo "<tr><td style=\"width:302px\">" . $mp[$curMP][1][0]. (sizeof($mp[$curMP][1]) == 1 ? "" : "<br>". $mp[$curMP][1][1]). "</td>".
                 "<td style=\"width:76px\">" . $mp[$curMP][2][0]. (sizeof($mp[$curMP][2]) == 1 ? "" : "<br>". $mp[$curMP][2][1]). "</td>".
                 "<td style=\"width:274px\">" . $mp[$curMP][3][0]. (sizeof($mp[$curMP][3]) == 1 ? "" : "<br>". $mp[$curMP][3][1]). "</td>".
             "</tr>";
    }
    echo "</tbody></table>";
}
