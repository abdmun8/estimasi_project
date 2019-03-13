<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HR_Model extends CI_Model {

    private $dbhr;

    public function __construct()
    {
        parent::__construct();
        $this->dbhr = $this->load->database('dbhr', TRUE);

    }

    public function getDataKandidat()
    {
        $data = NULL;
        $sql = " SELECT 
            kandidat.id_kandidat,
            kandidat.nama,
            kandidat.email,
            kandidat.jenis_kelamin,
            kandidat.`status`,
            kandidat.agama,
            kandidat.no_hp,
            kandidat.alamat,
            kandidat.kec,
            kab,
            kandidat.alamat2,
            kandidat.kec2,
            kandidat.kab2,
            kandidat.nm_photo,
            kandidat.`resume`,
            pendidikan.institusi,
            pendidikan.kualifikasi,
            pendidikan.thn_lulus,
            pendidikan.studi,
            pendidikan.jurusan,
            pendidikan.nilai,
            pendidikan.keterangan,
            pengalaman.perusahaan,
            pengalaman.posisi,
            pengalaman.spesial,
            pengalaman.jabatan,
            pengalaman.gaji,
            pengalaman.keterangan AS jobdesc,
            pengalaman.lama_kerja,
            training.training,
            info.gaji_harapan,
            info.keterangan AS kelebihan
        FROM
            data_kandidat AS kandidat
                LEFT JOIN
            (SELECT 
                *
            FROM
                (SELECT 
                id_kandidat,
                    institusi,
                    kualifikasi,
                    thn_lulus,
                    studi,
                    jurusan,
                    nilai,
                    keterangan
            FROM
                data_pendidikan
            ORDER BY thn_lulus DESC) AS pendidikan
            GROUP BY id_kandidat) AS pendidikan ON kandidat.id_kandidat = pendidikan.id_kandidat
                LEFT JOIN
            (SELECT 
                id_kandidat,
                    GROUP_CONCAT(nm_pt
                        SEPARATOR ',') AS perusahaan,
                    GROUP_CONCAT(posisi
                        SEPARATOR ',') AS posisi,
                    GROUP_CONCAT(spesial
                        SEPARATOR ',') AS spesial,
                    GROUP_CONCAT(jabatan
                        SEPARATOR ',') AS jabatan,
                    GROUP_CONCAT(gaji
                        SEPARATOR ',') AS gaji,
                    GROUP_CONCAT(keterangan
                        SEPARATOR ',') AS keterangan,
                    SUM(lama_kerja) AS lama_kerja
            FROM
                (SELECT 
                *,
                    (RIGHT(tgl_selesai, 4) - RIGHT(tgl_mulai, 4)) AS lama_kerja
            FROM
                data_pengalaman
            ORDER BY tgl_selesai DESC) AS pengalaman
            GROUP BY id_kandidat) AS pengalaman ON kandidat.id_kandidat = pengalaman.id_kandidat
                LEFT JOIN
            (SELECT 
                id_kandidat,
                    GROUP_CONCAT(nama_training
                        SEPARATOR ',') AS training
            FROM
                data_training
            GROUP BY id_kandidat) AS training ON kandidat.id_kandidat = training.id_kandidat
                LEFT JOIN
            data_info AS info ON kandidat.id_kandidat = info.id
        WHERE
            kandidat.tanggal_lahir <> '0000-00-00'
                AND kandidat.level_user = '' ";
        $query = $this->dbhr->query($sql);

        if( $query->num_rows() > 0){
            $data = $query->result();
        }

        return $data;
    }    
}