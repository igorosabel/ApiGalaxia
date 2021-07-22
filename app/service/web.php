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
}
