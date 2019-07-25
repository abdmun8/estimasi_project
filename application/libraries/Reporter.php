<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reporter
{
    public function typeCheck($v)
    {
        if ($v == 'section') {
            return 'CCFFE8';
        } else if ($v == 'object') {
            return 'C5DEED';
        } else if ($v == 'sub_object') {
            return 'FBE1B6';
        } else {
            return 'FFFFFF';
        }
    }

    public function getKategori($k)
    {
        if ($k == '')
            return '';
        $kategori = [
            "10001" => "RAW MATERIAL",
            "10002" => "ELECTRIC STD PART",
            "10003" => "MECHANIC STD PART",
            "10004" => "PNEUMATIC STD PART",
            "10005" => "HYDRAULIC STD PART",
            "20001" => "JASA SPECIAL PROCESS",
            "40003" => "Import"
        ];
        return $kategori[$k];
    }
    
    public function findChildPart($v)
    {
        $new = [];
        $harga = $v['tipe_item'] !== 'item' ? '' : $v['harga'];
        $qty = $v['tipe_item'] != 'item' ? '' : $v['qty'];
        $item_code = $v['tipe_item'] != 'item' ? '' : $v['item_code'];
        $new = [
            'tipe_id' =>  $v['tipe_id'],
            'tipe_name' =>  $v['tipe_name'],
            'item_code' =>  $item_code,
            'item_name' =>  $v['item_name'],
            'spec' =>  $v['spec'],
            'merk' =>  $v['merk'],
            'satuan' =>  $v['satuan'],
            'harga' =>  $harga,
            'qty' =>  $qty,
            'total' =>  $v['total'],
            'kategori' =>  $this->getKategori($v['kategori']),
            'tipe_item' =>  $v['tipe_item']
        ];
        return $new;
    }

    public function findChildMaterial($v)
    {
        $new = [];
        $qty = $v['tipe_item'] != 'item' ? '' : $v['qty'];
        $l = $v['tipe_item'] != 'item' ? '' : $v['l'];
        $w = $v['tipe_item'] != 'item' ? '' : $v['w'];
        $h = $v['tipe_item'] != 'item' ? '' : $v['h'];
        $t = $v['tipe_item'] != 'item' ? '' : $v['t'];
        $density = $v['tipe_item'] != 'item' ? '' : $v['density'];
        $weight = $v['tipe_item'] != 'item' ? '' : $v['weight'];
        $item_code = $v['tipe_item'] != 'item' ? '' : $v['item_code'];
        $new = [
            'tipe_id' =>  $v['tipe_id'],
            'tipe_name' =>  $v['tipe_name'],
            'item_code' =>  $item_code,
            'part_name' =>  $v['part_name'],
            'units' =>  $v['units'],
            'qty' =>  $qty,
            'materials' =>  $v['materials'],
            'l' =>  $l,
            'w' =>  $w,
            'h' =>  $h,
            't' =>  $t,
            'density' =>  $density,
            'weight' =>  $weight,
            'total' =>  $v['total'],
            'tipe_item' =>  $v['tipe_item'],
        ];
        return $new;
    }

    public function findChildLabour($v){
        $hour = $v['tipe_item'] != 'item' ? '' : $v['hour'];
        $rate = $v['tipe_item'] != 'item' ? '' : $v['rate'];
        $new = [
            'id' =>  $v['id'],
            'id_parent' =>  $v['id_parent'],
            'id_header' =>  $v['id_header'],
            'tipe_id' =>  $v['tipe_id'],
            'tipe_name' =>  $v['tipe_name'],
            'id_labour' =>  $v['id_labour'],
            'aktivitas' =>  $v['aktivitas'],
            'hour' =>  $hour,
            'rate' =>  $rate,
            'total' =>  $v['total'],
            'tipe_item' =>  $v['tipe_item'],
        ];
        return $new;
    }

    public function getStructure($data, $functionName)
    {
        $arrId = [];
        $arrData = [];
        foreach ($data as $key => $s) {
            if ($s['tipe_item'] == 'section') {
                $arrData[] = $this->$functionName($s);
                array_push($arrId, $s['id']);
                foreach ($data as $key => $o) {
                    if ($o['id_parent'] == $s['id'] && !in_array($o['id'], $arrId)) {
                        $arrData[] = $this->$functionName($o);
                        array_push($arrId, $o['id']);
                        foreach ($data as $key => $so) {
                            if ($so['id_parent'] == $o['id'] && !in_array($so['id'], $arrId)) {
                                $arrData[] = $this->$functionName($so);
                                array_push($arrId, $so['id']);
                                foreach ($data as $key => $i) {
                                    if ($i['id_parent'] == $so['id'] && !in_array($i['id'], $arrId)) {
                                        $arrData[] = $this->$functionName($i);
                                        array_push($arrId, $i['id']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $arrData;
    }
}
