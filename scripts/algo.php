<?php
    // ========= MIN MAX SUM ==================
    // function ret_sum($arr){
    //     $arrlength = count($arr);
    //     $m=0;
    //     for($x = 0; $x < $arrlength-1; $x++) {
    //         $m = $m + $arr[$x];
    //     }
    //     return $m;
    // }
    // function compare_arr($arr){
    //     sort($arr);
    //     $mx = ret_sum($arr);
    //     rsort($arr);
    //     $my = ret_sum($arr);
    //     echo $mx ." ". $my;
    // }
    // $arr = array(1,3,5,7,9);
    // compare_arr($arr);
    // max(2,4,6,8,10)
    // ===========  Hei
    // function compare_arr($arr){
    //     $mx = max($arr);
    //     $arrlength = count($arr);
    //     $m=0;
    //     for($i = 0; $i < $arrlength; $i++) {
    //         if($arr[$i] == $mx) {
    //             $m = $m + 1;
    //         }
    //     }
    //     return $m;
    // }

    // $arr = array(1,3,9,7,9);
    // $c = compare_arr($arr);
    // echo $c;

    // ========  Conversion 24 Hours Time Format ===============

    // $t = DateTime::createFromFormat('h:i:s A', '07:05:45 PM');
    // $t24 = $t->format('H:i:s');
    // echo $t24;

    // =============  Student Grade ===========================
// 4,73,67,38,
    // function get_next_mul5($n){
    //     for($i = 0; $i <= 4; $i++) {
    //         $x = $n + $i;
    //         if ($x % 5 == 0) {
    //             return $x;
    //         }
    //     }
    // }
    // function compare_arr($arr){
    //     $mx = max($arr);
    //     $arrlength = count($arr);
    //     // $m=0;
    //     for($i = 0; $i < $arrlength; $i++) {
    //         $mul5 = get_next_mul5($arr[$i]);
    //         if (($mul5 - $arr[$i]) < 3 && $arr[$i] >=38) {
    //             echo $mul5 . "<br>";
    //         }
    //         else if (($mul5 - $arr[$i]) >= 3 || $arr[$i] < 38){
    //             echo $arr[$i] . "<br>";
    //         }

    //     }
    //     // return $m;
    // }
    // $arr = array(73,67,38,33);
    // $c = compare_arr($arr);

    //  ====================== Kangaroo Jump =============================
    //  0 3 4 2
    // function getState($s,$x){
    //     $d = $s + $x;
    //     return $d;
    // }
    // $ks1 =0; $ks2 = 4; $kx1 = 3; $kx2 = 2;
    //     $kstate1 = 0;
    //     $kstate2 = 0;
    // while($kstate1 >=0 || $kstate2 >=0) 
    // {
    //     $kstate1 = getState($ks1,$kx1);
    //     $kstate2 = getState($ks2,$kx2);
    //     if ($kstate1 == $kstate2) {
    //         echo "Yes";
    //         break;
    //     }
    //     if ($kstate1 > $kstate2 && $kx1 > $kx2) {
    //         echo "No";
    //         break;
    //     }
    //     else {
    //         $ks1 = $kstate1;
    //         $ks2 = $kstate2; 
    //     }
    // }

    function process($n) {
        if ($n%3 == 0 && $n%5 == 0) {
            echo "FIzzBuzz" . "<br>";
        }else if ($n%3 == 0) {
            echo "Fizz" . "<br>";
        }else if ($n%5 == 0) {
            echo "Buzz" . "<br>";
        }else {
            echo $n . "<br>";
        }
    }
    $n = 2;
    for($i = 1; $i < 16; $i++) {
        process($i);
    }
?>
