<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\Galaxia;
use OsumiFramework\App\Model\Planeta;
use OsumiFramework\App\Model\Alianza;
use OsumiFramework\App\Model\Raza;
use OsumiFramework\App\Model\Jugador;
use OsumiFramework\App\Model\Especial;
use OsumiFramework\OFW\Plugins\OImage;

class webService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Función para obtener la lista completa de galaxias
	 *
	 * @return array Lista de galaxias
	 */
	public function getGalaxias(): array {
		$ret = [];
		$db = new ODB();

		$sql = "SELECT * FROM `galaxia` ORDER BY `nombre`";
		$db->query($sql);
		$ret = [];

		while ($res = $db->next()) {
			$galaxia = new Galaxia();
			$galaxia->update($res);

			array_push($ret, $galaxia);
		}

		return $ret;
	}

	/**
	 * Función para obtener la lista de planetas de una galaxia / sector / cuadrante concretos
	 *
	 * @param int $galaxia Id de la galaxia
	 *
	 * @param int $sector Número del sector
	 *
	 * @param int $cuadrante Número del cuadrante
	 *
	 * @return array Lista de planetas ya descubiertos en las coordenadas indicadas
	 */
	public function getPlanetas(int $galaxia, int $sector, int $cuadrante): array {
		$ret = [];
		$db = new ODB();
		$sql = "SELECT * FROM `planeta` WHERE `id_galaxia` = ? AND `sector` = ? AND `cuadrante` = ?";
		$db->query($sql, [$galaxia, $sector, $cuadrante]);

		while ($res = $db->next()) {
			$p = new Planeta();
			$p->update($res);
			array_push($ret, $p);
		}

		return $ret;
	}

	/**
	 * Función para obtener la lista completa de alianzas
	 *
	 * @return array Lista de alianzas
	 */
	public function getAlianzas(): array {
		$ret = [];
		$db = new ODB();

		$sql = "SELECT * FROM `alianza` ORDER BY `nombre`";
		$db->query($sql);
		$ret = [];

		while ($res = $db->next()) {
			$alianza = new Alianza();
			$alianza->update($res);

			array_push($ret, $alianza);
		}

		return $ret;
	}

	/**
	 * Función para borrar una alianza, primero se la "desasigna" a todos los jugadores y luego finalmente la borra
	 *
	 * @param Alianza $alianza Alianza a borrar
	 *
	 * @return void
	 */
	public function deleteAlianza(Alianza $alianza): void {
		$db = new ODB();
		$sql = "UPDATE `jugador` SET `id_alianza` = NULL WHERE `id_alianza` = ?";
		$db->query($sql, [$alianza->get('id')]);

		$alianza->delete();
	}

	/**
	 * Función para obtener la lista completa de razas
	 *
	 * @return array Lista de razas
	 */
	public function getRazas(): array {
		$ret = [];
		$db = new ODB();

		$sql = "SELECT * FROM `raza` ORDER BY `nombre`";
		$db->query($sql);
		$ret = [];

		while ($res = $db->next()) {
			$raza = new Raza();
			$raza->update($res);

			array_push($ret, $raza);
		}

		return $ret;
	}

	/**
	 * Función para obtener la lista completa de jugadores
	 *
	 * @return array Lista de jugadores
	 */
	public function getJugadores(): array {
		$ret = [];
		$db = new ODB();

		$sql = "SELECT * FROM `jugador` ORDER BY `nombre`";
		$db->query($sql);
		$ret = [];

		while ($res = $db->next()) {
			$jugador = new Jugador();
			$jugador->update($res);

			array_push($ret, $jugador);
		}

		return $ret;
	}

	/**
	 * Función para borrar un jugador, primero se la "desasigna" a todos los planetas y luego finalmente lo borra
	 *
	 * @param Jugador $jugador Jugador a borrar
	 *
	 * @return void
	 */
	public function deleteJugador(Jugador $jugador): void {
		$db = new ODB();
		$sql = "UPDATE `planeta` SET `id_jugador` = NULL WHERE `id_jugador` = ?";
		$db->query($sql, [$jugador->get('id')]);

		$jugador->delete();
	}

	/**
	 * Función para obtener la lista completa de planetas especiales
	 *
	 * @return array Lista de planetas especiales
	 */
	public function getEspeciales(): array {
		$ret = [];
		$db = new ODB();

		$sql = "SELECT * FROM `especial` ORDER BY `nombre`";
		$db->query($sql);
		$ret = [];

		while ($res = $db->next()) {
			$especial = new Especial();
			$especial->update($res);

			array_push($ret, $especial);
		}

		return $ret;
	}

	/**
	 * Obtener la extensión de una foto en formato Base64
	 *
	 * @param string $data Imagen en formato Base64
	 *
	 * @return string Extensión de la imagen
	 */
	public function getFotoExt(string $data): string {
		$arr_data = explode(';', $data);
		$arr_data = explode(':', $arr_data[0]);
		$arr_data = explode('/', $arr_data[1]);

		return $arr_data[1];
	}

	/**
	 * Guarda una imagen en Base64 en la ubicación indicada
	 *
	 * @param string $dir Ruta en la que guardar la imagen
	 *
	 * @param string $base64_string Imagen en formato Base64
	 *
	 * @param int $id Id de la imagen
	 *
	 * @param string $ext Extensión del archivo de imagen
	 *
	 * @return string Devuelve la ruta completa a la nueva imagen
	 */
	public function saveImage(string $dir, string $base64_string, int $id, string $ext): string {
		$ruta = $dir.$id.'.'.$ext;

		if (file_exists($ruta)) {
			unlink($ruta);
		}

		$ifp = fopen($ruta, "wb");
		$data = explode(',', $base64_string);
		fwrite($ifp, base64_decode($data[1]));
		fclose($ifp);

		return $ruta;
	}

	/**
	 * Guarda una imagen en Base64 para un especial. Si no tiene formato WebP se convierte
	 *
	 * @param string $base64_string Imagen en formato Base64
	 *
	 * @param int $id Id de la imagen
	 *
	 * @return void
	 */
	public function saveNewImage(string $base64_string, int $id): void {
		$ext = $this->getFotoExt($base64_string);
		$ruta = $this->saveImage($this->getConfig()->getDir('ofw_tmp'), $base64_string, $id, $ext);
		$im = new OImage();
		$im->load($ruta);

		// Compruebo tamaño inicial
		if ($im->getWidth() > 400 || $im->getHeight() > 600) {
			$im->resizeToWidth(400);
			$im->save($ruta, $im->getImageType());
		}

		$photo_route = $this->getConfig()->getExtra('photo').$id.'.webp';

		// Guardo la imagen ya modificada como WebP
		$im->save($photo_route, IMAGETYPE_WEBP);

		// Borro la imagen temporal
		unlink($ruta);
	}

	/**
	 * Función para borrar un especial, primero se lo desasigna de cualquier planeta y luego finalmente borra el especial
	 *
	 * @param Especial $especial Especial a borrar
	 *
	 * @return void
	 */
	public function deleteEspecial(Especial $especial): void {
		$db = new ODB();
		$sql = "UPDATE `planeta` SET `id_especial` = NULL WHERE `id_especial` = ?";
		$db->query($sql, [$especial->get('id')]);

		$especial->deleteFull();
	}
}
