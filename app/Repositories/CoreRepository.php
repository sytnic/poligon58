<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application\mixed;
use PhpParser\Node\Expr\AssignOp\Mod;

/**
 * Class CoreRepository.
 *
 * @package App/Repositories
 *
 * Репозиторий работы с сущностью.
 * Может выдавать наборы данных.
 * Не может создавать/изменять сущности.
 */
abstract class CoreRepository
{
    /**
     * @var Model
     */

    protected $model;

    /**
     * Делегируем создание объекта Laravel'ю
     * 
     * CoreRepository construct
     */

    public function __construct()
    {
        $this->model = app($this->getModelClass());
        // app - внутренняя функция Laravel,
        // получится примерно следующее:
        // $this->model = app('App\Models\BlogCategory');
        // можно делать и так:
        // $this->model = new $this->getModelClass();
        
    }

    /**
     * Должен быть реализован потомком.
     * Имя Модели будет сообщать потомок.
     * 
     * @return mixed
     */
    abstract protected function getModelClass();

    /**
     * Репозиторий клонирует модель
     * и не сохраняет состояние исходной модели. 
     * 
     * @return Model| \Illuminate\Foundation\Application\mixed
     */
    protected function startConditions()
    {
        return clone $this->model;
    }
}