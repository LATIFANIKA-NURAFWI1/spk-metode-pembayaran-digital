<?php
class Topsis {
    private $db;
    private $alternatifs = [];
    private $kriterias = [];
    private $matriks = [];
    private $customWeights = null;

    public function __construct($pdo, $customWeights = null) {
        $this->db = $pdo;
        $this->customWeights = $customWeights;
        $this->loadData();
    }

    private function loadData() {
        $stmt = $this->db->query("SELECT * FROM alternatif ORDER BY id");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->alternatifs[$row['id']] = $row;
        }

        $stmt = $this->db->query("SELECT * FROM kriteria ORDER BY id");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($this->customWeights !== null && isset($this->customWeights[$row['id']])) {
                $row['bobot'] = $this->customWeights[$row['id']];
            }
            $this->kriterias[$row['id']] = $row;
        }

        $stmt = $this->db->query("SELECT * FROM nilai_matriks");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->matriks[$row['id_alternatif']][$row['id_kriteria']] = (float)$row['nilai'];
        }
    }

    public function calculate() {
        if (empty($this->alternatifs) || empty($this->kriterias) || empty($this->matriks)) {
            return [];
        }

        // 1. Pembagi (Akar Sum Kuadrat)
        $pembagi = [];
        foreach ($this->kriterias as $id_k => $k) {
            $sum_sq = 0;
            foreach ($this->alternatifs as $id_a => $a) {
                $val = $this->matriks[$id_a][$id_k] ?? 0;
                $sum_sq += pow($val, 2);
            }
            $pembagi[$id_k] = sqrt($sum_sq);
        }

        // 2. Normalisasi & Normalisasi Terbobot
        $norm_terbobot = [];
        foreach ($this->alternatifs as $id_a => $a) {
            foreach ($this->kriterias as $id_k => $k) {
                $val = $this->matriks[$id_a][$id_k] ?? 0;
                $norm = $pembagi[$id_k] != 0 ? $val / $pembagi[$id_k] : 0;
                $norm_terbobot[$id_a][$id_k] = $norm * $k['bobot'];
            }
        }

        // 3. Solusi Ideal Positif & Negatif
        $ideal_pos = [];
        $ideal_neg = [];
        foreach ($this->kriterias as $id_k => $k) {
            $vals = [];
            foreach ($this->alternatifs as $id_a => $a) {
                $vals[] = $norm_terbobot[$id_a][$id_k];
            }
            if ($k['tipe'] == 'Benefit') {
                $ideal_pos[$id_k] = max($vals);
                $ideal_neg[$id_k] = min($vals);
            } else { // Cost
                $ideal_pos[$id_k] = min($vals);
                $ideal_neg[$id_k] = max($vals);
            }
        }

        // 4. Jarak Ideal Positif & Negatif
        $d_pos = [];
        $d_neg = [];
        foreach ($this->alternatifs as $id_a => $a) {
            $sum_pos = 0;
            $sum_neg = 0;
            foreach ($this->kriterias as $id_k => $k) {
                $sum_pos += pow($norm_terbobot[$id_a][$id_k] - $ideal_pos[$id_k], 2);
                $sum_neg += pow($norm_terbobot[$id_a][$id_k] - $ideal_neg[$id_k], 2);
            }
            $d_pos[$id_a] = sqrt($sum_pos);
            $d_neg[$id_a] = sqrt($sum_neg);
        }

        // 5. Nilai Preferensi
        $hasil = [];
        foreach ($this->alternatifs as $id_a => $a) {
            $total_d = $d_neg[$id_a] + $d_pos[$id_a];
            $v = $total_d != 0 ? $d_neg[$id_a] / $total_d : 0;
            $hasil[] = [
                'id_alternatif' => $id_a,
                'kode' => $a['kode'],
                'nama' => $a['nama'],
                'nilai' => $v
            ];
        }

        // Sort desc
        usort($hasil, function($a, $b) {
            return $b['nilai'] <=> $a['nilai'];
        });

        // Add rank
        $rank = 1;
        foreach ($hasil as &$h) {
            $h['rank'] = $rank++;
        }

        return $hasil;
    }
}
?>
