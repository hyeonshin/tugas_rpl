<?php
require_once 'koneksi.php';
class Authentication extends Koneksi
{
	private $koneksi;
	
	function __construct()
	{
		$this->koneksi = new Koneksi();
		session_start();
	}

	public function login($username, $password)
	{
		$password = htmlentities($_POST['password']);

		try {
			$sql = "SELECT * FROM pencatat_sipil WHERE nip=:username";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(":username", $username);
			$query->execute();
			$data = $query->fetch();

			if (password_verify($password, $data['password'])) {
			$_SESSION['admin_session'] = $data['nip'];
			return "LOGIN AS ADMIN";
	/* 				} elseif ($data['level'] == "Petugas PLN") {
						$_SESSION['pln_session'] = $data['id_user'];
						return "LOGIN AS PLN";
					} else {
						$_SESSION['pembayaran_session'] = $data['id_user'];
						return "LOGIN AS PEMBAYARAN";
					}
	 */			
			} else {	
				return "WRONG";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function logged_admin()
	{
		if (isset($_SESSION['admin_session'])) {
			return TRUE;
		}
	}

/* 	public function logged_pln()
	{
		if (isset($_SESSION['pln_session'])) {
			return TRUE;
		}
	}

	public function logged_pembayaran()
	{
		if (isset($_SESSION['pembayaran_session'])) {
			return TRUE;
		}
	}
 */
	public function get_data_admin()
	{
		if (!$this->logged_admin()) {
			return FALSE;
		}

		try {
			$sql = "SELECT * FROM pencatat_sipil WHERE nip=:username";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(":username", $_SESSION['admin_session']);
			$query->execute();
			return $query->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
/* 
	public function get_data_pln()
	{
		if (!$this->logged_pln()) {
			return FALSE;
		}

		try {
			$sql = "SELECT * FROM tbl_user WHERE id_user=:id_user";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(":id_user", $_SESSION['pln_session']);
			$query->execute();
			return $query->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}

	public function get_data_pembayaran()
	{
		if (!$this->logged_pembayaran()) {
			return FALSE;
		}

		try {
			$sql = "SELECT * FROM tbl_user WHERE id_user=:id_user";
			$query = $this->koneksi->db->prepare($sql);
			$query->bindParam(":id_user", $_SESSION['pembayaran_session']);
			$query->execute();
			return $query->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage();
			return FALSE;
		}
	}
 */
	public function logout_admin()
	{
		session_destroy();
		unset($_SESSION['admin_session']);
		return TRUE;
	}

/* 	public function logout_pln()
	{
		session_destroy();
		unset($_SESSION['pln_session']);
		return TRUE;
	}

	public function logout_pembayaran()
	{
		session_destroy();
		unset($_SESSION['pembayaran_session']);
		return TRUE;
	} */
}
?>