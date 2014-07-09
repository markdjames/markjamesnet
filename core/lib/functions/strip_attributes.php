<?php
function stripAttributes($htmlString) {
    $regEx = '/([^<]*<\s*[a-z](?:[0-9]|[a-z]{0,9}))(?:(?:\s*[a-z\-]{2,14}\s*=\s*(?:"[^"]*"|\'[^\']*\'))*)(\s*\/?>[^<]*)/i'; // match any start tag

    $chunks = preg_split($regEx, $htmlString, -1,  PREG_SPLIT_DELIM_CAPTURE);
    $chunkCount = count($chunks);

    $strippedString = '';
    for ($n = 1; $n < $chunkCount; $n++) {
        $strippedString .= $chunks[$n];
    }

    return $strippedString;
}
?>