<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait SearchableFilter
 * 
 * Fornece funcionalidade de filtro dinâmico e genérico para qualquer Model
 * 
 * Uso no Service:
 * -----
 * use SearchableFilter;
 * 
 * public function index($request = [])
 * {
 *     return $this->applyFilters($request);
 * }
 * -----
 * 
 * Padrão de filtro esperado no front-end:
 * {
 *     "filters": {
 *         "name": {
 *             "value": "João",
 *             "operator": "like"  // like, =, !=, >, <, >=, <=, in, between
 *         },
 *         "created_at": {
 *             "value": "2025-01-01",
 *             "operator": ">"
 *         }
 *     },
 *     "sort": {
 *         "field": "created_at",
 *         "direction": "desc"  // asc, desc
 *     },
 *     "pagination": {
 *         "page": 1,
 *         "per_page": 15
 *     }
 * }
 */
trait SearchableFilter
{
    /**
     * Aplica filtros dinâmicos à query
     * 
     * @param array $request Dados da requisição com filtros, sort e paginação
     * @return \Illuminate\Http\Response Response formatada com dados
     */
    public function applyFilters(array $request = [])
    {
        $query = $this->model::query();

        // Aplica filtros
        if (!empty($request['filters'])) {
            $query = $this->applyFilterConditions($query, $request['filters']);
        }

        // Obtém total antes da paginação
        $total = $query->count();

        // Aplica ordenação
        if (!empty($request['sort'])) {
            $query = $this->applySorting($query, $request['sort']);
        }

        // Aplica paginação
        $pagination = $request['pagination'] ?? ['page' => 1, 'per_page' => 15];
        $page = $pagination['page'] ?? 1;
        $perPage = $pagination['per_page'] ?? 15;

        $items = $query->paginate($perPage, ['*'], 'page', $page);

        $model = $this->successPaginated($items, $total);
        return $model;
    }

    /**
     * Aplica condições de filtro à query
     * 
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function applyFilterConditions(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $filter) {
            // Pula se não houver valor
            if (empty($filter['value']) && $filter['value'] !== 0 && $filter['value'] !== '0') {
                continue;
            }

            $operator = $filter['operator'] ?? '=';
            $value = $filter['value'];

            switch ($operator) {
                case 'like':
                    $query->where($field, 'like', "%{$value}%");
                    break;

                case 'ilike': // case-insensitive like
                    $query->where($field, 'ilike', "%{$value}%");
                    break;

                case 'in':
                    $values = is_array($value) ? $value : explode(',', $value);
                    $query->whereIn($field, $values);
                    break;

                case 'not_in':
                    $values = is_array($value) ? $value : explode(',', $value);
                    $query->whereNotIn($field, $values);
                    break;

                case 'between':
                    if (is_array($value) && count($value) === 2) {
                        $query->whereBetween($field, $value);
                    }
                    break;

                case 'null':
                    $query->whereNull($field);
                    break;

                case 'not_null':
                    $query->whereNotNull($field);
                    break;

                case '!=':
                case 'ne':
                    $query->where($field, '!=', $value);
                    break;

                default: // =, >, <, >=, <=
                    $query->where($field, $operator, $value);
                    break;
            }
        }

        return $query;
    }

    /**
     * Aplica ordenação à query
     * 
     * @param Builder $query
     * @param array $sort Deve conter 'field' e 'direction'
     * @return Builder
     */
    protected function applySorting(Builder $query, array $sort): Builder
    {
        $field = $sort['field'] ?? null;
        $direction = strtoupper($sort['direction'] ?? 'ASC');

        if ($field && in_array($direction, ['ASC', 'DESC'])) {
            $query->orderBy($field, $direction);
        }

        return $query;
    }

    /**
     * Resposta de sucesso com paginação
     * 
     * @param mixed $data
     * @param int $total
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    protected function successPaginated($data, int $total = 0, string $message = 'OK', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'meta' => [
                'total' => $total,
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
            ]
        ], $code);
    }

    /**
     * Resposta de sucesso simples
     * 
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    protected function success($data = [], string $message = 'OK', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Resposta de erro
     * 
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    protected function error(string $message = 'Erro', int $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}
