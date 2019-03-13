<?php

/*fungsi untuk merubah angka menjadi nama status */
/*misal:
* angka 1 menjadi "Aktif"
*/
function status_data($angka = 0)
{
	if ($angka == 1) {
		$str = 'Aktif';
	} else {
		$str = 'Tidak Aktif';
	}

	return $str;
}

function status_yn($angka = 0)
{
	if ($angka == 1) {
		$str = 'Yes';
	} else {
		$str = 'No';
	}

	return $str;
}

/*u/ merubah angka biasa menjadi format rupiah*/
/*misal:
* angka 5000 menjadi Rp 5.000
*/
function format_rupiah($angka = 0)
{
	return  'Rp ' . number_format($angka);
}

/*
* for Generate Random Code (int) for code activation
*/
function genActivationCode($len = 5)
{
    $angka = range(0, 9);
    shuffle($angka);
    return implode('', array_rand($angka, $len));
}

/* 
* Switch day to indo
*/
function switch_day($i){
	$days = array('senin','selasa','rabu','kamis','jum\'at','sabtu','minggu');
	return $days[$i - 1];
}