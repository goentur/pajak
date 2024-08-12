<?php


namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class TargetRepository
{
	public function data($request)
	{
		$pbb = $this->pbb($request);
		$pdr = $this->pdr($request);

		return compact('pbb', 'pdr');
	}

	protected function pbb($request)
	{
		$year = $request->tahun ?? date('Y');
		$result = DB::connection('oracle')->select("SELECT TO_CHAR (SYSDATE, 'YYYY') THN,
       T.TARGET_PBB,
       T.TARGET_BPHTB,
       P.REALISASI_PBB,
       B.REALISASI_BPHTB
  FROM (  SELECT TARGET.PBB TARGET_PBB, TARGET.BPHTB TARGET_BPHTB
            FROM TARGET
           WHERE TARGET.TAHUN = TO_CHAR (SYSDATE, 'YYYY')
        ORDER BY TGL DESC) T,
       (SELECT SUM (
                    PEMBAYARAN_SPPT.JML_SPPT_YG_DIBAYAR
                  - PEMBAYARAN_SPPT.DENDA_SPPT)
                  REALISASI_PBB
          FROM PEMBAYARAN_SPPT
         WHERE TRUNC (PEMBAYARAN_SPPT.TGL_PEMBAYARAN_SPPT, 'YEAR') =
                  TRUNC (SYSDATE, 'YEAR')) P,
       (SELECT SUM (BPHTB_SSPD.JML_BAYAR) REALISASI_BPHTB
          FROM BPHTB_SSPD
         WHERE TRUNC (BPHTB_SSPD.TGL, 'YEAR') = TRUNC (SYSDATE, 'YEAR')) B");
		return $result;
	}

	protected function pdr($request)
	{
		$year = $request->tahun ?? date('Y');
		$result = DB::connection('mysql')->select("SELECT
	A.s_jenisobjek,
	A.s_namakorek,
	A.total_target,
	B.jml_sspd,
	B.total_byr,
	ROUND( B.total_byr / A.total_target * 100 ) PERSEN 
FROM
	(
	SELECT
		s_rekening_header.s_jenisobjek,
		s_rekening_header.s_namakorek,
		sum( s_targetdetail.s_targetjumlah ) total_target 
	FROM
		s_rekening_header
		INNER JOIN s_rekening ON s_rekening.s_jenisobjek = s_rekening_header.s_jenisobjek
		INNER JOIN s_targetdetail ON s_targetdetail.s_targetrekening = s_rekening.s_idkorek
		INNER JOIN ( SELECT * FROM s_target WHERE s_target.s_tahuntarget = YEAR ( CURRENT_DATE ) ORDER BY s_target.s_idtarget DESC LIMIT 1 ) trgt ON trgt.s_idtarget = s_targetdetail.s_idtargetheader 
	GROUP BY
		s_rekening_header.s_jenisobjek,
		s_rekening_header.s_namakorek 
	ORDER BY
		s_rekening_header.s_jenisobjek,
		s_rekening_header.s_namakorek 
	) A,
	(
	SELECT
		tt.rek,
		count( * ) jml_sspd,
		sum( tt.jumlah_byr ) total_byr 
	FROM
		(
			( SELECT t_transaksi.t_jenispajak rek, t_transaksi.t_jmlhpembayaran jumlah_byr, t_transaksi.t_tglpembayaran tgl FROM t_transaksi ) UNION
			(
			SELECT
				t_transaksi.t_jenispajak rek,
				t_skpdkb.t_jmlhbayarskpdkb jumlah_byr,
				t_skpdkb.t_tglbayarskpdkb tgl 
			FROM
				t_transaksi
				INNER JOIN t_skpdkb ON t_skpdkb.t_idtransaksi = t_transaksi.t_idtransaksi 
			) UNION
			(
			SELECT
				t_transaksi.t_jenispajak rek,
				t_skpdkbt.t_jmlhbayarskpdkbt jumlah_byr,
				t_skpdkbt.t_tglbayarskpdkbt tgl 
			FROM
				t_transaksi
				INNER JOIN t_skpdkbt ON t_skpdkbt.t_idtransaksi = t_transaksi.t_idtransaksi 
			) UNION
			(
			SELECT
				t_transaksi.t_jenispajak rek,
				t_skpdt.t_jmlhbayarskpdt jumlah_byr,
				t_skpdt.t_tglbayarskpdt tgl 
			FROM
				t_transaksi
				INNER JOIN t_skpdt ON t_skpdt.t_idtransaksi = t_transaksi.t_idtransaksi 
			) UNION
			(
			SELECT
				t_transaksi.t_jenispajak rek,
				t_skrdt.t_jmlhbayarskrdt jumlah_byr,
				t_skrdt.t_tglbayarskrdt tgl 
			FROM
				t_transaksi
				INNER JOIN t_skrdt ON t_skrdt.t_idtransaksi = t_transaksi.t_idtransaksi 
			) 
		) tt 
	WHERE
		YEAR ( tt.tgl ) = YEAR ( CURRENT_DATE ) 
	GROUP BY
		tt.rek 
	ORDER BY
		tt.rek 
	) B 
WHERE
	A.s_jenisobjek = B.rek");

		return $result;
	}
}
