<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Service\webService;
use OsumiFramework\App\Model\Alianza;
use OsumiFramework\App\Model\Jugador;

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
}
