<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Xlsx_reader {

    function read($filepath, $sheetName)
    {
        $zip = new ZipArchive();
        if ($zip->open($filepath) !== true) {
            throw new Exception('Gagal membuka file Excel.');
        }

        $workbookXml = simplexml_load_string($zip->getFromName('xl/workbook.xml'));
        $sheetRid = null;
        foreach ($workbookXml->sheets->sheet as $sheet) {
            if ((string) $sheet['name'] === $sheetName) {
                $attrs = $sheet->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships');
                $sheetRid = (string) $attrs['id'];
                break;
            }
        }
        if ($sheetRid === null) {
            $zip->close();
            throw new Exception("Sheet '".$sheetName."' tidak ditemukan di dalam file Excel.");
        }

        $relsXml = simplexml_load_string($zip->getFromName('xl/_rels/workbook.xml.rels'));
        $target = null;
        foreach ($relsXml->Relationship as $rel) {
            if ((string) $rel['Id'] === $sheetRid) {
                $target = (string) $rel['Target'];
                break;
            }
        }
        if ($target === null) {
            $zip->close();
            throw new Exception('Gagal menemukan lokasi sheet di dalam file Excel.');
        }

        $strings = array();
        $sstRaw = $zip->getFromName('xl/sharedStrings.xml');
        if ($sstRaw !== false) {
            $sst = simplexml_load_string($sstRaw);
            foreach ($sst->si as $si) {
                if (isset($si->t)) {
                    $strings[] = (string) $si->t;
                } else {
                    $txt = '';
                    foreach ($si->r as $r) {
                        $txt .= (string) $r->t;
                    }
                    $strings[] = $txt;
                }
            }
        }

        $sheetRaw = $zip->getFromName('xl/'.$target);
        $zip->close();

        if ($sheetRaw === false) {
            throw new Exception('Gagal membaca isi sheet.');
        }

        $sheetXml = simplexml_load_string($sheetRaw);

        $rows = array();
        foreach ($sheetXml->sheetData->row as $row) {
            $cells = array();
            foreach ($row->c as $c) {
                $idx = $this->_colToIndex((string) $c['r']);
                $type = (string) $c['t'];
                $val = isset($c->v) ? (string) $c->v : '';
                if ($type === 's' && $val !== '') {
                    $val = isset($strings[(int) $val]) ? $strings[(int) $val] : '';
                }
                $cells[$idx] = $val;
            }
            $rows[] = $cells;
        }

        return $rows;
    }

    private function _colToIndex($ref)
    {
        $col = preg_replace('/[0-9]/', '', $ref);
        $idx = 0;
        for ($i = 0; $i < strlen($col); $i++) {
            $idx = $idx * 26 + (ord($col[$i]) - 64);
        }
        return $idx - 1;
    }
}
