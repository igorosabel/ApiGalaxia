<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Planeta extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name = 'planeta';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única de cada foto'
			],
			'id_galaxia' => [
				'type'     => OModel::NUM,
				'comment'  => 'Id de la galaxia en la que se encuentra el planeta',
				'nullable' => false,
				'ref'      => 'galaxia.id'
			],
			'sector' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número del sector en el que se encuentra el planeta',
				'nullable' => false
			],
			'cuadrante' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número del cuadrante en el sector',
				'nullable' => false
			],
			'ind' => [
				'type'     => OModel::NUM,
				'comment'  => 'Indice del planeta en la lista de planetas de un cuadrante',
				'nullable' => false
			],
			'nombre' => [
				'type'     => OModel::TEXT,
				'size'     => 200,
				'comment'  => 'Nombre del planeta',
				'nullable' => false
			],
			'valor' => [
				'type'     => OModel::NUM,
				'comment'  => 'Valor del planeta',
				'nullable' => false
			],
			'id_jugador' => [
				'type'     => OModel::NUM,
				'comment'  => 'Id del jugador que habita el planeta',
				'nullable' => true,
				'ref'      => 'jugador.id'
			],
			'id_especial' => [
				'type'     => OModel::NUM,
				'comment'  => 'Id un planeta especial',
				'nullable' => true,
				'ref'      => 'especial.id'
			],
			'protegido' => [
				'type'     => OModel::BOOL,
				'comment'  => 'Indica si el planeta está protegido',
				'nullable' => false
			],
			'created_at' => [
				'type'    => OModel::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OModel::UPDATED,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
	}

	/**
	 * Devuelve el nombre del planeta
	 */
	public function __toString() {
		return $this->get('nombre');
	}

	private ?Galaxia $galaxia = null;

	/**
	 * Devuelve la galaxia a la que pertenece el planeta
	 *
	 * @return Galaxia Galaxia a la que pertenece el planeta
	 */
	public function getGalaxia(): Galaxia {
		if (is_null($this->galaxia)){
			$this->loadGalaxia();
		}
		return $this->galaxia;
	}

	/**
	 * Guarda la galaxia a la que pertenece el planeta
	 *
	 * @param Galaxia $galaxia Galaxia a la que pertenece el planeta
	 *
	 * @return void
	 */
	public function setGalaxia(Galaxia $galaxia): void {
		$this->galaxia = $galaxia;
	}

	/**
	 * Carga la galaxia a la que pertenece el planeta
	 *
	 * @return void
	 */
	public function loadGalaxia(): void {
		$galaxia = new Galaxia();
		$galaxia->find(['id'=>$this->get('id_galaxia')]);
		$this->setGalaxia($galaxia);
	}

	/**
	 * Devuelve si un planeta tiene o no un jugador
	 *
	 * @return bool El planeta tiene jugador
	 */
	public function hasJugador(): bool {
		return !is_null($this->get('id_jugador'));
	}

	private ?Jugador $jugador = null;

	/**
	 * Devuelve el jugador de un planeta
	 *
	 * @return Jugador Jugador del planeta
	 */
	public function getJugador(): ?Jugador {
		if (is_null($this->jugador)){
			$this->loadJugador();
		}
		return $this->jugador;
	}

	/**
	 * Guarda jugador de un planeta
	 *
	 * @param Jugador $jugador Jugador de un planeta
	 *
	 * @return void
	 */
	public function setJugador(Jugador $jugador): void {
		$this->jugador = $jugador;
	}

	/**
	 * Carga el jugador de un planeta
	 *
	 * @return void
	 */
	public function loadJugador(): void {
		if ($this->hasJugador()) {
			$jugador = new Jugador();
			$jugador->find(['id'=>$this->get('id_jugador')]);
			$this->setJugador($jugador);
		}
	}

	/**
	 * Devuelve si un planeta es especial
	 *
	 * @return bool El planeta es especial
	 */
	public function isEspecial(): bool {
		return !is_null($this->get('id_especial'));
	}

	private ?Especial $especial = null;

	/**
	 * Devuelve el planeta especial
	 *
	 * @return Especial Planeta especial
	 */
	public function getEspecial(): ?Especial {
		if (is_null($this->especial)){
			$this->loadEspecial();
		}
		return $this->especial;
	}

	/**
	 * Guarda especial de un planeta
	 *
	 * @param Especial $especial Especial de un planeta
	 *
	 * @return void
	 */
	public function setEspecial(Especial $especial): void {
		$this->especial = $especial;
	}

	/**
	 * Carga el especial de un planeta
	 *
	 * @return void
	 */
	public function loadEspecial(): void {
		if ($this->isEspecial()) {
			$especial = new Especial();
			$especial->find(['id'=>$this->get('id_especial')]);
			$this->setEspecial($especial);
		}
	}
}
