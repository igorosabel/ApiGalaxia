<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Jugador extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name = 'jugador';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única para cada tag'
			],
			'nombre' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Texto de la tag',
				'nullable' => false
			],
			'id_raza' => [
				'type'     => OModel::NUM,
				'comment'  => 'Raza del jugador',
				'nullable' => false,
				'ref'      => 'raza.id'
			],
			'id_alianza' => [
				'type'     => OModel::NUM,
				'comment'  => 'Id de la alianza a la que pertenece el jugador',
				'nullable' => true,
				'ref'      => 'alianza.id'
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
	 * Devuelve el nombre del jugador
	 */
	public function __toString() {
		return $this->get('nombre');
	}

	private ?Raza $raza = null;

	/**
	 * Devuelve la raza de un jugador
	 *
	 * @return Raza Raza del jugador
	 */
	public function getRaza(): Raza {
		if (is_null($this->raza)){
			$this->loadRaza();
		}
		return $this->raza;
	}

	/**
	 * Guarda la raza de un jugador
	 *
	 * @param Raza $raza Raza de un jugador
	 *
	 * @return void
	 */
	public function setRaza(Raza $raza): void {
		$this->raza = $raza;
	}

	/**
	 * Carga la raza de un jugador
	 *
	 * @return void
	 */
	public function loadRaza(): void {
		$raza = new Raza();
		$raza->find(['id'=>$this->get('id_raza')]);
		$this->setRaza($raza);
	}

	/**
	 * Devuelve si un jugador tiene o no una alianza
	 *
	 * @return bool El jugador está en una alianza
	 */
	public function hasAlianza(): bool {
		return !is_null($this->get('id_alianza'));
	}

	private ?Alianza $alianza = null;

	/**
	 * Devuelve la alianza de un jugador
	 *
	 * @return Alianza Alianza del jugador
	 */
	public function getAlianza(): ?Alianza {
		if (is_null($this->alianza)){
			$this->loadAlianza();
		}
		return $this->alianza;
	}

	/**
	 * Guarda la alianza de un jugador
	 *
	 * @param Alianza $alianza Alianza de un jugador
	 *
	 * @return void
	 */
	public function setAlianza(Alianza $alianza): void {
		$this->alianza = $alianza;
	}

	/**
	 * Carga la alianza de un jugador
	 *
	 * @return void
	 */
	public function loadAlianza(): void {
		if ($this->hasAlianza()) {
			$alianza = new Alianza();
			$alianza->find(['id'=>$this->get('id_alianza')]);
			$this->setAlianza($alianza);
		}
	}
}
