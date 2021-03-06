<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Service\webService;
use OsumiFramework\App\Model\Alianza;
use OsumiFramework\App\Model\Jugador;
use OsumiFramework\App\Model\Planeta;
use OsumiFramework\App\Model\Galaxia;
use OsumiFramework\App\Model\Especial;

#[ORoute(
	type: 'json',
	prefix: '/api'
)]
class api extends OModule {
	private ?webService $web_service = null;

	function __construct() {
		$this->web_service = new webService();
	}

	/**
	 * Función para obtener la lista de galaxias
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-galaxias')]
	public function getGalaxias(ORequest $req): void {
		$status = 'ok';
		$list = $this->web_service->getGalaxias();

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'api/galaxias_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para obtener la lista de planetas de una galaxia / sector / cuadrante indicados
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-planetas')]
	public function getPlanetas(ORequest $req): void {
		$status = 'ok';
		$galaxia = $req->getParamInt('galaxia');
		$sector = $req->getParamInt('sector');
		$cuadrante = $req->getParamInt('cuadrante');
		$list = [];

		if (is_null($galaxia) || is_null($sector) || is_null($cuadrante)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$list = $this->web_service->getPlanetas($galaxia, $sector, $cuadrante);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'api/planetas_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para obtener la lista de alianzas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-alianzas')]
	public function getAlianzas(ORequest $req): void {
		$status = 'ok';
		$list = $this->web_service->getAlianzas();

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'api/alianzas_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para guardar una alianza
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/save-alianza')]
	public function saveAlianza(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$nombre = $req->getParamString('nombre');

		if (is_null($id) || is_null($nombre)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$alianza = new Alianza();
			if ($id != -1) {
				$alianza->find(['id' => $id]);
			}
			$alianza->set('nombre', $nombre);
			$alianza->save();
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para borrar una alianza
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/delete-alianza')]
	public function deleteAlianza(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$alianza = new Alianza();
			if ($alianza->find(['id' => $id])) {
				$this->web_service->deleteAlianza($alianza);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para obtener la lista de razas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-razas')]
	public function getRazas(ORequest $req): void {
		$status = 'ok';
		$list = $this->web_service->getRazas();

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'api/razas_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para obtener la lista de jugadores
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-jugadores')]
	public function getJugadores(ORequest $req): void {
		$status = 'ok';
		$list = $this->web_service->getJugadores();

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'api/jugadores_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para guardar un jugador
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/save-jugador')]
	public function saveJugador(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$nombre = $req->getParamString('nombre');
		$raza = $req->getParam('raza');
		$alianza = $req->getParam('alianza');

		if (is_null($id) || is_null($nombre) || is_null($raza)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$jugador = new Jugador();
			if ($id != -1) {
				$jugador->find(['id' => $id]);
			}
			$jugador->set('nombre', $nombre);
			$jugador->set('id_raza', $raza['id']);
			if ($alianza['id'] != -1) {
				$jugador->set('id_alianza', $alianza['id']);
			}
			else {
				$jugador->set('id_alianza', null);
			}
			$jugador->save();
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para borrar un jugador
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/delete-jugador')]
	public function deleteJugador(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$jugador = new Jugador();
			if ($jugador->find(['id' => $id])) {
				$this->web_service->deleteJugador($jugador);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para guardar un planeta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/save-planeta')]
	public function savePlaneta(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$nombre = $req->getParamString('nombre');
		$sector = $req->getParamInt('sector');
		$cuadrante = $req->getParamInt('cuadrante');
		$ind = $req->getParamInt('ind');
		$valor = $req->getParamInt('valor');
		$protegido = $req->getParamBool('protegido');
		$galaxia = $req->getParam('galaxia');
		$jugador = $req->getParam('jugador');
		$especial = $req->getParam('especial');

		if (is_null($id) || is_null($nombre) || is_null($sector) || is_null($cuadrante) || is_null($ind) || is_null($valor) || is_null($protegido) || is_null($galaxia)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$planeta = new Planeta();
			$new = true;
			if ($id != -1) {
				$new = false;
				$planeta->find(['id' => $id]);
			}
			$planeta->set('id_galaxia', $galaxia['id']);
			$planeta->set('sector', $sector);
			$planeta->set('cuadrante', $cuadrante);
			$planeta->set('ind', $ind);
			$planeta->set('nombre', $nombre);
			$planeta->set('valor', $valor);
			if (!is_null($jugador) && array_key_exists('id', $jugador) && $jugador['id'] != -1) {
				$planeta->set('id_jugador', $jugador['id']);
			}
			else {
				$planeta->set('id_jugador', null);
			}
			if (!is_null($especial) && array_key_exists('id', $especial) && $especial['id'] != -1) {
				$planeta->set('id_especial', $especial['id']);
			}
			else {
				$planeta->set('id_especial', null);
			}
			$planeta->set('protegido', $protegido);
			$planeta->save();

			if ($new) {
				$galaxia = new Galaxia();
				$galaxia->find(['id' => $planeta->get('id_galaxia')]);
				$galaxia->set('investigados', $galaxia->get('investigados') +1);
				$galaxia->save();
			}
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para obtener la lista de planetas especiales
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-especiales')]
	public function getEspeciales(ORequest $req): void {
		$status = 'ok';
		$list = $this->web_service->getEspeciales();

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'api/especiales_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para guardar un especial
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/save-especial')]
	public function saveEspecial(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$nombre = $req->getParamString('nombre');
		$img = $req->getParamString('img');

		if (is_null($id) || is_null($nombre) || is_null($img)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$especial = new Especial();
			if ($id != -1) {
				$especial->find(['id' => $id]);
			}
			$especial->set('nombre', $nombre);
			$especial->save();

			if (stripos($img, 'data') !== false) {
				$this->web_service->saveNewImage($img, $especial->get('id'));
			}
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para borrar un especial
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/delete-especial')]
	public function deleteEspecial(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$especial = new Especial();
			if ($especial->find(['id' => $id])) {
				$this->web_service->deleteEspecial($especial);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
