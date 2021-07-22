<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Raza extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name = 'raza';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única para cada raza'
			],
			'nombre' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Nombre de la raza',
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
	 * Devuelve el nombre de la raza
	 */
	public function __toString() {
		return $this->get('raza');
	}
}
