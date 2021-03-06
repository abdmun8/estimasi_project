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
function switch_day($i)
{
	$days = array('senin', 'selasa', 'rabu', 'kamis', 'jum\'at', 'sabtu', 'minggu');
	return $days[$i - 1];
}

// calc diff date
function calcDiffDate($dt1, $dt2, $tipe = 'm')
{
	$date1 = new DateTime($dt1);
	$date2 = $date1->diff(new DateTime($dt2));
	switch ($tipe) {
		case 'days':
			return $date2->days;
			break;
		case 'y':
			return $date2->y;
			break;
		case 'm':
			return $date2->m;
			break;
		case 'd':
			return $date2->d;
			break;
		case 'h':
			return $date2->h;
			break;
	}
}

function createSequence($code, $length, $number)
{
	$sequence = sprintf('%0' . $length . 'd', $number);
	return $code . $sequence;
}

function addSpace($v, $value)
{
	$num_space = 0;
	if ($v == 'object') {
		$num_space = 2;
	} else if ($v == 'sub_object') {
		$num_space = 4;
	} else {
		$num_space = 0;
	}
	$space = '';
	for ($i = 0; $i < $num_space; $i++) {
		$space .= ' ';
	}
	return $space . $value;
}

function cek_risk($nilai)
{

	$ketentuan = [
		'PROJECT NORMAL'      => ['min' => 1, 'max' => 2.25],
        'PROJECT MEDIUM RISK' => ['min' => 2.3, 'max' => 3.7],
        'PROJECT HIGH RISK'   => ['min' => 3.75, 'max' => 4.45],
        'VERY HIGH RISK'      => ['min' => 4.5, 'max' => 8],
	];

	foreach ($ketentuan as $key => $value) {
		if ($nilai >= $value['min'] && $nilai <= $value['max']) {
			return $key;
		}
	}
	return 'FALSE';
}
