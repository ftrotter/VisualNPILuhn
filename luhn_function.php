<?php
// from http://www.php.net/manual/en/ref.math.php#109457
function luhn($num){
    if(!$num)
        return false;
    $num = array_reverse(str_split($num));
    $add = 0;
    foreach($num as $k => $v){
        if($k%2)
            $v = $v*2;
        $add += ($v >= 10 ? $v - 9 : $v);
    }
    return ($add%10 == 0);
}
