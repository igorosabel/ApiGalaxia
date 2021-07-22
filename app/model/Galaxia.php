<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Galaxia extends OModel{
	/**
	 * Configures current model object based on data-base table structure
	 */
	 function __construct() {
		$table_name = 'galaxia';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada usuario'
			],
			'nombre' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Nombre de la galaxia',
				'nullable' => false
			],
			'sectores' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número de sectores en la galaxia',
				'nullable' => false
			],
			'cuadrantes' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número de cuadrantes en el sector de la galaxia',
				'nullable' => false
			],
			'planetas' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número de planetas por cuadrante',
				'nullable' => false
			],
			'num' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número total de planetas en la galaxia',
				'nullable' => false
			],
			'investigados' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número planetas investigados',
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
	 * Devuelve el nombre de la galaxia
	 */
	public function __toString(){
		return $this->get('nombre');
	}

	/**
	 * Devuelve la lista de planetas de un sector/cuadrante indicado
	 *
	 * @return array Lista de planetas
	 */
	public function getPlanetas(int $sector, int $cuadrante): array {
		$sql = "SELECT * FROM `planeta` WHERE `id_galaxia` = ? AND `sector` = ? AND `cuadrante` = ?";
		$this->db->query($sql, [
			$this->get('id'),
			$sector,
			$cuadrante
		]);
		$list = [];

		while ($res = $this->db->next()) {
			$planeta = new Planeta();
			$planeta->update($res);
			array_push($list, $planeta);
		}

		return $list;
	}
}
