<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Especial extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name = 'especial';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única para cada planeta especial'
			],
			'nombre' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Nombre del planeta especial',
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
	 * Devuelve el nombre del planeta especial
	 */
	public function __toString() {
		return $this->get('nombre');
	}

	/**
	 * Función para obtener la URL del especial
	 *
	 * @return string URL absoluta de la imagen del especial
	 */
	public function getUrl(): string {
		global $core;
		return $core->config->getUrl('base').'especiales/'.$this->get('id').'.webp';
	}

	/**
	 * Función para borrar un especial con su foto
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		global $core;
		$photo_route = $core->config->getExtra('photo').$this->get('id').'.webp';

		if (file_exists($photo_route)) {
			unlink($photo_route);
		}

		$this->delete();
	}
}
