<?php
require_once 'koneksi.php';
class Library extends Koneksi
{
	private $koneksi;

	function __construct()
	{
		$this->koneksi = new Koneksi();
	}

	// Script User
	public function code_generator_user()
	{
		try {
			$sql = "SELECT MAX(id_user) FROM tbl_user";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();

			if ($data) {
				$result = substr($data[0], 4);
				$code = (int) $result;
				$code = $code + 1;
				$generate = "PTG-".str_pad($code, 3, "0", STR_PAD_LEFT);
			} else {
				$generate = "PTG-000";
			}
			return $generate;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function input_user($id_user, $username, $password, $nama_user, $level, $status)
	{
		$id_user = htmlentities($_POST['id_user']);
		$username = htmlentities($_POST['username']);
		$password = password_hash(htmlentities($_POST['password']), PASSWORD_DEFAULT);
		$nama_user = htmlentities($_POST['nama_user']);
		$level = htmlentities($_POST['level']);
		$status = htmlentities($_POST['status']);

		try {
			$sql = "INSERT INTO `tbl_user`(`id_user`, `username`, `password`, `nama_user`, `level`, `status`) VALUES (?,?,?,?,?,?)";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $id_user);
			$query->bindParam(2, $username);
			$query->bindParam(3, $password);
			$query->bindParam(4, $nama_user);
			$query->bindParam(5, $level);
			$query->bindParam(6, $status);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			if ($e->errorInfo[0] == 23000) {
				return "UNIQUE";
			} else {
				echo $e->getMessage();
				return FALSE;
			}
		}
	}

	public function edit_user($id_user, $nama_user, $level,$status)
	{
		$id_user = htmlentities($_POST['id_user']);
		$nama_user = htmlentities($_POST['nama_user']);
		$level = htmlentities($_POST['level']);
		$status = htmlentities($_POST['status']);
		try {
			$sql = "UPDATE `tbl_user` SET `nama_user`=?,`level`=?,`status`=? WHERE id_user='$id_user'";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $nama_user);
			$query->bindParam(2, $level);
			$query->bindParam(3, $status);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function view_user()
	{
		try {
			$sql = "SELECT * FROM tbl_user WHERE level!='Admin Sistem' ORDER BY id_user ASC";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function select_user($id_user)
	{
		try {
			$sql = "SELECT * FROM `tbl_user` WHERE id_user='$id_user'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function aktifkan_user($id_user)
	{
		try {
			$sql = "UPDATE tbl_user SET status='AKTIF' WHERE id_user='$id_user'";
			$query = $this->koneksi->db->query($sql);
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function nonaktifkan_user($id_user)
	{
		try {
			$sql = "UPDATE tbl_user SET status='TIDAK AKTIF' WHERE id_user='$id_user'";
			$query = $this->koneksi->db->query($sql);
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function forget_password($id_user, $password)
	{
		$id_user = htmlentities($_POST['id_user']);
		$password = password_hash(htmlentities($_POST['password']), PASSWORD_DEFAULT);

		try {
			$sql = "UPDATE `tbl_user` SET `password`=? WHERE id_user='$id_user'";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $password);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function change_password($id_user, $password_baru)
	{
		$id_user = htmlentities($_POST['id_user']);
		$password_lama = htmlentities($_POST['password_lama']);
		$password_baru = password_hash(htmlentities($_POST['password_baru']), PASSWORD_DEFAULT);

		try {
			$sql = "SELECT * FROM tbl_user WHERE id_user='$id_user'";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();

			if (password_verify($password_lama, $data['password'])) {
				$sql = "UPDATE tbl_user SET password=? WHERE id_user='$id_user'";
				$query = $this->koneksi->db->prepare($sql);
				$query->bindParam(1, $password_baru);
				$query->execute();
				return "SUCCESS";
			} else {
				return "NOT SAME";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function jumlah_petugas_pln()
	{
		try {
			$sql = "SELECT COUNT(*) AS jumlah FROM tbl_user WHERE level='Petugas PLN'";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();
			$result = $data['jumlah'];
			return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function jumlah_petugas_pembayaran()
	{
		try {
			$sql = "SELECT COUNT(*) AS jumlah FROM tbl_user WHERE level='Petugas Pembayaran'";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();
			$result = $data['jumlah'];
			return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	// Script Tarif
	public function code_generator_tarif()
	{
		try {
			$sql = "SELECT MAX(id_tarif) FROM tbl_tarif";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();

			if ($data) {
				$result = substr($data[0], 4);
				$code = (int) $result;
				$code = $code + 1;
				$generate = "TRF-".str_pad($code, 4, "0", STR_PAD_LEFT);
			} else {
				$generate = "TRF-0000";
			}
			return $generate;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function input_al($no_akta, $nama_lahir, $tmp_lahir, $tgl_lahir, $wn, $jk, $ket, $tmp_kutipan, $tgl_kutipan, $nip_pencatat)
	{
		$no_akta = htmlentities($_POST['no_akta']);
		$nama_lahir = htmlentities($_POST['nama_lahir']);
		$tmp_lahir = htmlentities($_POST['tmp_lahir']);
		$tgl_lahir = htmlentities($_POST['tgl_lahir']);
		$wn = htmlentities($_POST['wn']);
		$jk = htmlentities($_POST['jk']);
		$ket = htmlentities($_POST['ket']);
		$tmp_kutipan = htmlentities($_POST['tmp_kutipan']);
		$tgl_kutipan = htmlentities($_POST['tgl_kutipan']);
		$nip_pencatat = htmlentities($_POST['nip_pencatat']);

		try {
			$sql = "INSERT INTO `akta_kelahiran`(`no_akta`, `nama_lahir`, `tempat_lahir`, `tgl_lahir`, `warga_negara`, `j_kelamin`, `ket`, `tempat_kutipan`, `tgl_kutipan`, `nip_pencatat`) VALUES (?,?,?,?,?,?,?,?,?,?)";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $no_akta);
			$query->bindParam(2, $nama_lahir);
			$query->bindParam(3, $tmp_lahir);
			$query->bindParam(4, $tgl_lahir);
			$query->bindParam(5, $wn);
			$query->bindParam(6, $jk);
			$query->bindParam(7, $ket);
			$query->bindParam(8, $tmp_kutipan);
			$query->bindParam(9, $tgl_kutipan);
			$query->bindParam(10, $nip_pencatat);
			
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function edit_al($no_akta, $nama_lahir, $tmp_lahir, $tgl_lahir, $wn, $jk, $ket, $tmp_kutipan, $tgl_kutipan, $nip_pencatat)
	{
		$no_akta = htmlentities($_POST['no_akta']);
		$nama_lahir = htmlentities($_POST['nama_lahir']);
		$tmp_lahir = htmlentities($_POST['tmp_lahir']);
		$tgl_lahir = htmlentities($_POST['tgl_lahir']);
		$wn = htmlentities($_POST['wn']);
		$jk = htmlentities($_POST['jk']);
		$ket = htmlentities($_POST['ket']);
		$tmp_kutipan = htmlentities($_POST['tmp_kutipan']);
		$tgl_kutipan = htmlentities($_POST['tgl_kutipan']);
		$nip_pencatat = htmlentities($_POST['nip_pencatat']);

		try {
			$sql = "UPDATE `akta_kelahiran` SET `nama_lahir`=?,`tempat_lahir`=?,`tgl_lahir`=?,`warga_negara`=?,`j_kelamin`=?,`ket`=? WHERE no_akta='$no_akta'";
			$query = $this->koneksi->db->prepare($sql);
			//$query->bindParam(1, $no_akta);
			$query->bindParam(1, $nama_lahir);
			$query->bindParam(2, $tmp_lahir);
			$query->bindParam(3, $tgl_lahir);
			$query->bindParam(4, $wn);
			$query->bindParam(5, $jk);
			$query->bindParam(6, $ket);
			/* $query->bindParam(7, $tmp_kutipan);
			$query->bindParam(8, $tgl_kutipan);
			$query->bindParam(9, $nip_pencatat); */

			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function view_al()
	{
		try {
			$sql = "SELECT * FROM akta_kelahiran ORDER BY no_akta ASC";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_jk()
	{
		try {
			$sql = "SELECT * FROM akta_kelahiran GROUP BY j_kelamin";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function view_nip()
	{
		try {
			$sql = "SELECT * FROM pencatat_sipil ORDER BY nip ASC";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function select_al($no_akta)
	{
		try {
			$sql = "SELECT * FROM `akta_kelahiran` WHERE no_akta='$no_akta'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function delete_al($no_akta)
	{
		try {
			$sql = "DELETE FROM `akta_kelahiran` WHERE no_akta='$no_akta'";
			$query = $this->koneksi->db->query($sql);
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			return FALSE;
		}
	}

	public function jumlah_al()
	{
		try {
			$sql = "SELECT COUNT(*) AS jumlah FROM akta_kelahiran";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();
			$result = $data['jumlah'];
			return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}


//Script Kartu Keluarga
	public function input_kk($no_kk, $kepala, $kelurahan, $rw, $rt)
	{
		$no_kk = htmlentities($_POST['no_kk']);
		$kepala = htmlentities($_POST['kepala']);
		$kelurahan = htmlentities($_POST['kelurahan']);
		$rw = htmlentities($_POST['rw']);
		$rt = htmlentities($_POST['rt']);

		try {
			$sql = "INSERT INTO `k_keluarga`(`no_kk`, `nama_kepala`, `no_kelurahan`, `no_rw`, `no_rt`) VALUES (?,?,?,?,?)";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $no_kk);
			$query->bindParam(2, $kepala);
			$query->bindParam(3, $kelurahan);
			$query->bindParam(4, $rw);
			$query->bindParam(5, $rt);
			
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function edit_kk($no_kk, $kepala, $kelurahan, $rw, $rt)
	{
		$no_kk = htmlentities($_POST['no_kk']);
		$kepala = htmlentities($_POST['kepala']);
		$kelurahan = htmlentities($_POST['kelurahan']);
		$rw = htmlentities($_POST['rw']);
		$rt = htmlentities($_POST['rt']);


		try {
			$sql = "UPDATE `k_keluarga` SET `nama_kepala`=?,`no_kelurahan`=?,`no_rw`=?,`no_rt`=? WHERE no_kk='$no_kk'";
			$query = $this->koneksi->db->prepare($sql);
			
			$query->bindParam(1, $kepala);
			$query->bindParam(2, $kelurahan);
			$query->bindParam(3, $rw);
			$query->bindParam(4, $rt);

			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_kk()
	{
		try {
			$sql = "SELECT * FROM k_keluarga ORDER BY no_kk ASC";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function select_kk($no_kk)
	{
		try {
			$sql = "SELECT * FROM `k_keluarga` WHERE no_kk='$no_kk'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function delete_kk($no_kk)
	{
		try {
			$sql = "DELETE FROM `k_keluarga` WHERE no_kk='$no_kk'";
			$query = $this->koneksi->db->query($sql);
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			return FALSE;
		}
	}

	public function jumlah_kk()
	{
		try {
			$sql = "SELECT COUNT(*) AS jumlah FROM k_keluarga";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();
			$result = $data['jumlah'];
			return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_rt()
	{
		try {
			$sql = "SELECT * FROM rt ORDER BY id_rt ASC";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_rw()
	{
		try {
			$sql = "SELECT * FROM rw ORDER BY no_rw ASC";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_kelurahan()
	{
		try {
			$sql = "SELECT * FROM kelurahan ORDER BY no_kelurahan ASC";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}


	// Script Kota
	public function code_generator_kota()
	{
		try {
			$sql = "SELECT MAX(id_kota) FROM tbl_kota";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();

			if ($data) {
				$result = $data[0];
				$code = (int) $result;
				$generate = $code + 1;
			} else {
				$generate = 0;
			}
			return $generate;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function input_kota($id_kota, $nama_kota, $ppju)
	{
		$id_kota = htmlentities($_POST['id_kota']);
		$nama_kota = htmlentities($_POST['nama_kota']);
		$ppju = htmlentities($_POST['ppju']);

		try {
			$sql = "INSERT INTO `tbl_kota`(`id_kota`, `nama_kota`, `ppju`) VALUES (?,?,?)";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $id_kota);
			$query->bindParam(2, $nama_kota);
			$query->bindParam(3, $ppju);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function edit_kota($id_kota, $nama_kota, $ppju)
	{
		$id_kota = htmlentities($_POST['id_kota']);
		$nama_kota = htmlentities($_POST['nama_kota']);
		$ppju = htmlentities($_POST['ppju']);

		try {
			$sql = "UPDATE `tbl_kota` SET `nama_kota`=?,`ppju`=? WHERE id_kota='$id_kota'";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $nama_kota);
			$query->bindParam(2, $ppju);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function view_kota()
	{
		try {
			$sql = "SELECT * FROM tbl_kota ORDER BY id_kota ASC";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function select_kota($id_kota)
	{
		try {
			$sql = "SELECT * FROM `tbl_kota` WHERE id_kota='$id_kota'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function delete_kota($id_kota)
	{
		try {
			$sql = "DELETE FROM `tbl_kota` WHERE id_kota='$id_kota'";
			$query = $this->koneksi->db->query($sql);
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			return FALSE;
		}
	}

	public function jumlah_kota()
	{
		try {
			$sql = "SELECT COUNT(*) AS jumlah FROM tbl_kota";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();
			$result = $data['jumlah'];
			return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	// Script Pelanggan
	public function code_generator_pelanggan()
	{
		try {
			$sql = "SELECT MAX(id_pelanggan) FROM tbl_pelanggan";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();

			if ($data) {
				$result = substr($data[0], 4);
				$code = (int) $result;
				$code = $code + 1;
				$generate = "PLG-".str_pad($code, 4, "0", STR_PAD_LEFT);
			} else {
				$generate = "PLG-0000";
			}
			return $generate;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function input_pelanggan($id_pelanggan, $id_tarif, $id_kota, $nometer, $nama_pelanggan, $alamat)
	{
		$id_pelanggan = htmlentities($_POST['id_pelanggan']);
		$id_tarif = htmlentities($_POST['id_tarif']);
		$id_kota = htmlentities($_POST['id_kota']);
		$nometer = htmlentities($_POST['nometer']);
		$nama_pelanggan = htmlentities($_POST['nama_pelanggan']);
		$alamat = htmlentities($_POST['alamat']);

		try {
			$sql = "INSERT INTO `tbl_pelanggan`(`id_pelanggan`, `id_tarif`, `id_kota`, `nometer`, `nama_pelanggan`, `alamat`) VALUES (?,?,?,?,?,?)";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $id_pelanggan);
			$query->bindParam(2, $id_tarif);
			$query->bindParam(3, $id_kota);
			$query->bindParam(4, $nometer);
			$query->bindParam(5, $nama_pelanggan);
			$query->bindParam(6, $alamat);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function edit_pelanggan($id_pelanggan, $id_tarif, $id_kota, $nometer, $nama_pelanggan, $alamat)
	{
		$id_pelanggan = htmlentities($_POST['id_pelanggan']);
		$id_tarif = htmlentities($_POST['id_tarif']);
		$id_kota = htmlentities($_POST['id_kota']);
		$nometer = htmlentities($_POST['nometer']);
		$nama_pelanggan = htmlentities($_POST['nama_pelanggan']);
		$alamat = htmlentities($_POST['alamat']);

		try {
			$sql = "UPDATE `tbl_pelanggan` SET `id_tarif`=?,`id_kota`=?,`nometer`=?,`nama_pelanggan`=?,`alamat`=? WHERE id_pelanggan='$id_pelanggan'";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $id_tarif);
			$query->bindParam(2, $id_kota);
			$query->bindParam(3, $nometer);
			$query->bindParam(4, $nama_pelanggan);
			$query->bindParam(5, $alamat);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function view_pelanggan()
	{
		try {
			$sql = "SELECT * FROM v_pelanggan ORDER BY id_pelanggan ASC";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function select_pelanggan($id_pelanggan)
	{
		try {
			$sql = "SELECT * FROM `tbl_pelanggan` WHERE id_pelanggan='$id_pelanggan'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function select_v_pelanggan($id_pelanggan)
	{
		try {
			$sql = "SELECT * FROM `v_pelanggan` WHERE id_pelanggan='$id_pelanggan'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function select_penggunaan_pelanggan($id_pelanggan)
	{
		try {
			$sql = "SELECT * FROM `v_penggunaan` WHERE id_pelanggan='$id_pelanggan'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function select_tagihan_pelanggan($id_pelanggan)
	{
		try {
			$sql = "SELECT * FROM `v_tagihan` WHERE id_pelanggan='$id_pelanggan'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function select_tagihan_pembayaran($id_tagihan)
	{
		try {
			$sql = "SELECT * FROM `v_tagihan` WHERE id_tagihan='$id_tagihan'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function jumlah_pelanggan()
	{
		try {
			$sql = "SELECT COUNT(*) AS jumlah FROM v_pelanggan";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();
			$result = $data['jumlah'];
			return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	// Script Penggunaan
	public function code_generator_penggunaan()
	{
		try {
			$sql = "SELECT MAX(id_penggunaan) FROM tbl_penggunaan";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();

			if ($data) {
				$result = substr($data[0], 4);
				$code = (int) $result;
				$code = $code + 1;
				$generate = "PGN-".str_pad($code, 4, "0", STR_PAD_LEFT);
			} else {
				$generate = "PGN-0000";
			}
			return $generate;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function input_penggunaan($id_penggunaan, $id_pelanggan, $bulan, $tahun, $meter_awal, $meter_akhir)
	{
		$id_penggunaan = htmlentities($_POST['id_penggunaan']);
		$id_pelanggan = htmlentities($_POST['id_pelanggan']);
		$bulan = htmlentities($_POST['bulan']);
		$tahun = htmlentities($_POST['tahun']);
		$meter_awal = htmlentities($_POST['meter_awal']);
		$meter_akhir = htmlentities($_POST['meter_akhir']);

		try {
			$sql = "INSERT INTO `tbl_penggunaan`(`id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `meter_awal`, `meter_akhir`) VALUES (?,?,?,?,?,?)";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $id_penggunaan);
			$query->bindParam(2, $id_pelanggan);
			$query->bindParam(3, $bulan);
			$query->bindParam(4, $tahun);
			$query->bindParam(5, $meter_awal);
			$query->bindParam(6, $meter_akhir);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function view_penggunaan()
	{
		try {
			$sql = "SELECT * FROM v_penggunaan ORDER BY id_penggunaan ASC";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function select_penggunaan($id_penggunaan)
	{
		try {
			$sql = "SELECT * FROM `v_penggunaan` WHERE id_penggunaan='$id_penggunaan'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	// Script Biaya Admin
	public function view_biaya_admin()
	{
		try {
			$sql = "SELECT * FROM `tbl_biaya_admin`";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function select_biaya_admin($id_biaya_admin)
	{
		try {
			$sql = "SELECT * FROM `tbl_biaya_admin` WHERE id_biaya_admin='$id_biaya_admin'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	
	public function edit_biaya_admin($id_biaya_admin, $biaya_admin)
	{
		$id_biaya_admin = htmlentities($_POST['id_biaya_admin']);
		$biaya_admin = htmlentities($_POST['biaya_admin']);

		try {
			$sql = "UPDATE `tbl_biaya_admin` SET `biaya_admin`=? WHERE id_biaya_admin='$id_biaya_admin'";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $biaya_admin);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function jumlah_biaya_admin()
	{
		try {
			$sql = "SELECT SUM(biaya_admin) AS jumlah FROM tbl_biaya_admin";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();
			$result = $data['jumlah'];
			return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	// Script Pembayaran
	public function code_generator_pembayaran()
	{
		try {
			$sql = "SELECT MAX(id_pembayaran) FROM tbl_pembayaran";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();

			if ($data) {
				$result = substr($data[0], 8);
				$code = (int) $result;
				$code = $code + 1;
				$generate = "PT".date("ymd")."".$code;
			} else {
				$generate = "PT0000000000000";
			}
			return $generate;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function input_pembayaran($id_pembayaran, $id_pelanggan, $id_tagihan, $id_biaya_admin, $id_user, $tgl_pembayaran, $biaya_denda, $biaya_admin, $jumlah_biaya)
	{
		$id_pembayaran = htmlentities($_POST['id_pembayaran']);
		$id_pelanggan = htmlentities($_POST['id_pelanggan']);
		$id_tagihan = htmlentities($_POST['id_tagihan']);
		$id_biaya_admin = htmlentities($_POST['id_biaya_admin']);
		$id_user = htmlentities($_POST['id_user']);
		$tgl_pembayaran = htmlentities($_POST['tgl_pembayaran']);
		$biaya_denda = htmlentities($_POST['biaya_denda']);
		$biaya_admin = htmlentities($_POST['biaya_admin']);
		$jumlah_biaya = htmlentities($_POST['jumlah_biaya']);

		try {
			$sql = "INSERT INTO `tbl_pembayaran`(`id_pembayaran`, `id_pelanggan`, `id_tagihan`, `id_biaya_admin`, `id_user`, `tgl_pembayaran`, `biaya_denda`, `biaya_admin`, `jumlah_biaya`) VALUES (?,?,?,?,?,?,?,?,?)";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $id_pembayaran);
			$query->bindParam(2, $id_pelanggan);
			$query->bindParam(3, $id_tagihan);
			$query->bindParam(4, $id_biaya_admin);
			$query->bindParam(5, $id_user);
			$query->bindParam(6, $tgl_pembayaran);
			$query->bindParam(7, $biaya_denda);
			$query->bindParam(8, $biaya_admin);
			$query->bindParam(9, $jumlah_biaya);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function select_print_pembayaran($id_tagihan)
	{
		try {
			$sql = "SELECT * FROM `v_hasil_pembayaran` WHERE id_tagihan='$id_tagihan'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function jumlah_pembayaran()
	{
		try {
			$sql = "SELECT COUNT(*) AS jumlah FROM tbl_pembayaran";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();
			$result = $data['jumlah'];
			return $result;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	// Script User Surat Pengantar
	public function view_spengantar()
	{
		try {
			$sql = "SELECT * FROM `s_pengantar` WHERE nik='321001'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function select_spengantar($nik)
	{
		try {
			$sql = "SELECT * FROM `s_pengantar` WHERE nik='$nik'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function select_spengantar2($nik)
	{
		try {
			$sql = "SELECT * FROM `warga` WHERE nik='$nik'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function select_spengantar_full($no_surat)
	{
		try {
			$sql = "SELECT * FROM `s_pengantar` WHERE no_surat='$no_surat'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function code_generator_spengantar()
	{
		try {
			$sql = "SELECT MAX(no_surat) FROM s_pengantar";
			$query = $this->koneksi->db->query($sql);
			$data = $query->fetch();

			if ($data) {
				$result = substr($data[0], 6);
				$code = (int) $result;
				$code = $code + 1;
				$generate = date("ymd")."".$code;
			} else {
				$generate = "0000000000000";
			}
			return $generate;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function input_spengantar($no_surat, $nik, $keperluan, $ket_lain, $tgl_surat, $validitas)
	{
		$no_surat = htmlentities($_POST['no_spengantar']);
		//$nik = htmlentities($_POST['nik']);
		$keperluan = htmlentities($_POST['keperluan']);
		$ket_lain = htmlentities($_POST['ket_lain']);
		$tgl_surat = htmlentities($_POST['tgl_surat']);

		try {
			$sql = "INSERT INTO `s_pengantar`(`no_surat`, `nik`, `keperluan`, `ket_lain`, `tgl_surat`, `validitas`) VALUES (?,?,?,?,?,?)";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(1, $no_surat);
			$query->bindParam(2, $nik);
			$query->bindParam(3, $keperluan);
			$query->bindParam(4, $ket_lain);
			$query->bindParam(5, $tgl_surat);
			$query->bindParam(6, $validitas);
			
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_nik()
	{
		try {
			$sql = "SELECT * FROM `warga`";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	// Script RT Surat Pengantar
	public function view_val_rt()
	{
		try {
			$sql = "SELECT * FROM `s_pengantar` WHERE validitas='RT'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function valid_rt($no_surat,$validitas)
	{
		try {
			$sql = "UPDATE `s_pengantar` SET `validitas`=? WHERE no_surat='$no_surat'";
			$query = $this->koneksi->db->prepare($sql);
			//$query->bindParam(1, $no_akta);
			$query->bindParam(1, $validitas);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	// Script RW Surat Pengantar
	public function view_val_rw()
	{
		try {
			$sql = "SELECT * FROM `s_pengantar` WHERE validitas='RW'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function valid_rw($no_surat,$validitas)
	{
		try {
			$sql = "UPDATE `s_pengantar` SET `validitas`=? WHERE no_surat='$no_surat'";
			$query = $this->koneksi->db->prepare($sql);
			
			$query->bindParam(1, $validitas);
			$query->execute();
			if ($query) {
				return "SUCCESS";
			} else {
				return "FAILED";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	
	public function view_usia_p()
	{
		try {
			$sql = "SELECT *, 2020 - YEAR(tgl_lahir) AS usia FROM warga JOIN akta_kelahiran ON warga.no_akta_kelahiran = akta_kelahiran.no_akta WHERE (2020 - YEAR(tgl_lahir) > 17) AND (2020 - YEAR(tgl_lahir) < 55)";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function view_usia_l()
	{
		try {
			$sql = "SELECT *, 2020 - YEAR(tgl_lahir) AS usia FROM warga JOIN akta_kelahiran ON warga.no_akta_kelahiran = akta_kelahiran.no_akta WHERE (2020 - YEAR(tgl_lahir) > 55)";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_j_pekerjaan()
	{
		try {
			$sql = "SELECT k_keluarga.nama_kepala AS kepala_keluarga, warga.* FROM warga JOIN akta_kelahiran ON warga.no_akta_kelahiran = akta_kelahiran.no_akta JOIN k_keluarga ON warga.no_kk = k_keluarga.no_kk WHERE warga.no_kk = '5270013'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_s_menikah()
	{
		try {
			$sql = "SELECT * FROM warga WHERE status = 'menikah'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_s_meninggal()
	{
		try {
			$sql = "SELECT * FROM warga JOIN akta_kematian ON warga.no_akta_kematian = akta_kematian.no_akta WHERE no_akta_kematian != 'NULL'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_jk_laki()
	{
		try {
			$sql = "SELECT * FROM warga JOIN akta_kelahiran ON warga.no_akta_kelahiran = akta_kelahiran.no_akta WHERE akta_kelahiran.j_kelamin = 'Laki-laki'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function count_laki()
	{
		try {
			$sql = "SELECT COUNT(*) as jml_laki FROM warga JOIN akta_kelahiran ON warga.no_akta_kelahiran = akta_kelahiran.no_akta WHERE akta_kelahiran.j_kelamin = 'Laki-laki'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function view_jk_perempuan()
	{
		try {
			$sql = "SELECT * FROM warga JOIN akta_kelahiran ON warga.no_akta_kelahiran = akta_kelahiran.no_akta WHERE akta_kelahiran.j_kelamin = 'Perempuan'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	public function count_perempuan()
	{
		try {
			$sql = "SELECT COUNT(*) as jml_perempuan FROM warga JOIN akta_kelahiran ON warga.no_akta_kelahiran = akta_kelahiran.no_akta WHERE akta_kelahiran.j_kelamin = 'Perempuan'";
			$query = $this->koneksi->db->query($sql);
			return $query;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
}
?>
