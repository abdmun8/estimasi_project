<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Reporter
{
    var $_CI;
    function __construct()
    {
        $this->_CI = &get_instance();
    }
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
            'id' =>  $v['id'],
            'id_parent' =>  $v['id_parent'],
            'id_header' =>  $v['id_header'],
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
            'id' =>  $v['id'],
            'id_parent' =>  $v['id_parent'],
            'id_part_jasa' =>  $v['id_part_jasa'],
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

    public function findChildLabour($v)
    {
        $hour = $v['tipe_item'] != 'item' ? '' : $v['hour'];
        $rate = $v['tipe_item'] != 'item' ? '' : $v['rate'];
        $new = [
            'id' =>  $v['id'],
            'id_parent' =>  $v['id_parent'],
            'id_part_jasa' =>  $v['id_part_jasa'],
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

    // public function 

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

    public function getStructureTree($data)
    {
        $arrId = [];
        $struct = [];
        $arrData = [];
        $n = 0;
        foreach ($data as $key => $s) {
            $struct[$n] = [];
            if ($s['tipe_item'] == 'section') {
                $arrData[] = $s;
                array_push($arrId, $s['id']);
                array_push($struct[$n], $s['id']);
                foreach ($data as $key => $o) {
                    if ($o['id_parent'] == $s['id'] && !in_array($o['id'], $arrId) && $o['tipe_item'] != 'item') {
                        $arrData[] = $o;
                        array_push($arrId, $o['id']);
                        array_push($struct[$n], $o['id']);
                        foreach ($data as $key => $so) {
                            if ($so['id_parent'] == $o['id'] && !in_array($so['id'], $arrId) && $so['tipe_item'] != 'item') {
                                $arrData[] = $so;
                                array_push($arrId, $so['id']);
                                array_push($struct[$n], $so['id']);
                                // foreach ($data as $key => $i) {
                                //     if ($i['id_parent'] == $so['id'] && !in_array($i['id'], $arrId)) {
                                //         $arrData[] = $i;
                                //         array_push($arrId, $i['id']);
                                //     }
                                // }
                            }
                        }
                    }
                }
            }
            if (count($struct[$n]) > 0) {
                $n++;
            }
            // var_dump($struct);die;
        }

        return $struct;
    }


    public function getDataSummary($part, $material, $labour)
    {

        // var_dump($part);die;
        $dataSectionPart = array_filter($part, function ($item) {
            return $item['tipe_item'] == 'section';
        });

        $parentPart = $this->getStructureTree($part);
        $storedPart = [];
        $n = 0;
        foreach ($dataSectionPart as $key => $value) {
            $parent = implode("','", array_values($parentPart[$n]));
            $sql = "SELECT `id`,
            `id_header`,
            `id_parent`,
            `tipe_item`,
            `tipe_id`,
            `tipe_name`,
            `item_code`,
            `item_name`,
            `spec`,
            `merk`,
            `satuan`,
            `harga`,
            `qty`,
            `kategori`,
            `updated_datetime`,
            `tipe_parent`,
            `nama_kategori`, SUM(rm) AS total_rm,SUM(elc) AS total_elc, SUM(pnu) AS total_pnu, SUM(hyd) AS total_hyd, SUM(mch) AS total_mch, SUM(sub) as total_sub, {$value['id']} AS id_section FROM (SELECT
                j.*,
                (SELECT
                        tipe_item
                    FROM
                        quotation.part_jasa p
                    WHERE
                        p.id = j.id_parent) AS tipe_parent,
                k.`desc` AS nama_kategori, 
                if(j.kategori = '10001',j.harga * qty,0) AS rm,
                if(j.kategori = '10002',j.harga * qty,0) AS elc,
                if(j.kategori = '10004',j.harga * qty,0) AS pnu,
                if(j.kategori = '10005',j.harga * qty,0) AS hyd,
                if(j.kategori = '10003',j.harga * qty,0) AS mch,
                if(j.kategori = '20001',j.harga * qty,0) AS sub
            FROM
                quotation.`part_jasa` j
                    LEFT JOIN
                `sgedb`.`akunbg` k ON j.kategori = k.accno
            WHERE
                
            j.id_header = '{$value['id_header']}' AND j.id_parent IN('$parent') AND j.tipe_item = 'item') AS grouping GROUP BY id_section";

            $data = $this->_CI->db->query($sql)->result_array();
            if (count($data) > 0) {
                foreach ($data as $key => $item) {
                    $item['tipe_id'] = $item['tipe_item'] == 'item' ? '' : $item['tipe_id'];
                    array_push($storedPart, $item);
                }
            }
            $n++;
        }
        // output $sortedPart

        // dataMaterial
        $dataMaterial = $this->getStructure($material, 'findChildMaterial');
        $sectionMaterial = array_filter($dataMaterial, function ($item) {
            return $item['tipe_item'] == 'section';
        });

        // data Material
        $dataParentMaterial = array_filter($labour, function ($item) {
            return $item['tipe_item'] != 'item';
        });

        $dataSort = $this->getStructure($dataParentMaterial, 'findChildLabour');
        $storedMaterial = array_values($sectionMaterial);
        // output $sortedMaterial

        // data Labour
        $dataSectionLabour = array_filter($labour, function ($item) {
            return $item['tipe_item'] == 'section';
        });
        // print_r($dataSectionLabour);die;
        $parentLabour = $this->getStructureTree($labour);
        $storedLabour = [];
        $n = 0;
        foreach ($dataSectionLabour as $key => $value) {
            $parent = implode("','", array_values($parentLabour[$n]));
            $sql = "SELECT 
            `id`,
            `id_parent`,
            `id_header`,
            `id_part_jasa`,
            `tipe_id`,
            `tipe_item`,
            `tipe_name`,
            `id_labour`,
            `aktivitas`,
            `sub_aktivitas`,
            `hour`,
            `rate`,
            `updated_datetime`,
            `opsi`,
            `tipe_parent`,
            `id_section`, SUM(eng) AS total_eng, SUM(prod) AS total_prod FROM (SELECT
            `l`.*,id AS opsi, 
                (
            SELECT
                tipe_item
            FROM
                labour b
            WHERE
                b.id = l.id_parent) AS tipe_parent, if(l.tipe_name = 'ENGINEERING', (l.`hour` * l.rate),0) AS eng,if(l.tipe_name = 'PRODUCTION', (l.`hour` * l.rate),0) AS prod, {$value['id_part_jasa']} AS id_section
            FROM
                `labour` `l`
            WHERE
                l.id_header ='{$value['id_header']}' AND l.id_parent IN ('$parent') AND l.tipe_item = 'item') AS grouping GROUP BY id_section";
            // echo $sql;die;
            $data = $this->_CI->db->query($sql)->result_array();
            if (count($data) > 0) {
                foreach ($data as $key => $item) {
                    $item['tipe_id'] = $item['tipe_item'] == 'item' ? '' : $item['tipe_id'];
                    array_push($storedLabour, $item);
                }
            }
            $n++;
        }

        $data = [];
        $no = 0;
        foreach ($dataSectionPart as $key => $value) {
            $no = ++$no;
            $temp = [];
            $temp['no'] = $no;
            $temp['id'] = $value['id'];
            $temp['qty'] = $value['qty'];
            $temp['tipe_id'] = $value['tipe_id'];
            $temp['tipe_name'] = $value['tipe_name'];

            if($storedPart){
                foreach ($storedPart as $key => $part) {
                    if ($value['id'] == $part['id_section']) {
                        $temp['total_rm'] = $part['total_rm'];
                        $temp['total_elc'] = $part['total_elc'];
                        $temp['total_pnu'] = $part['total_pnu'];
                        $temp['total_hyd'] = $part['total_hyd'];
                        $temp['total_mch'] = $part['total_mch'];
                        $temp['total_sub'] = $part['total_sub'];
                    }
                }
            }else{
                $temp['total_rm'] = 0;
                $temp['total_elc'] = 0;
                $temp['total_pnu'] = 0;
                $temp['total_hyd'] = 0;
                $temp['total_mch'] = 0;
                $temp['total_sub'] = 0;
            }
            // var_dump($dataSectionPart);die;
            if($storedMaterial){
                foreach ($storedMaterial as $key => $material) {
                    if ($value['id'] == $material['id_part_jasa']) {
                        $temp['total_rm'] += $material['total'];
                    }
                }
            }else{
                $temp['total_rm'] += 0;
            }

            if($storedLabour){
                foreach ($storedLabour as $key => $labour) {
                    // var_dump($labour);die;
                    if ($value['id'] == $labour['id_part_jasa']) {
                        $temp['total_eng'] = $labour['total_eng'];
                        $temp['total_prod'] = $labour['total_prod'];
                    }
                }
            }else{
                $temp['total_eng'] = 0;
                $temp['total_prod'] = 0;
            }
            $data[] = $temp;
        }

        return $data;
    }
}
