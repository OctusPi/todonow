<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Utils\Notify;
use App\Security\Guardian;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Validator;

/**
 * Class Controller
 * Base controller with reusable methods for CRUD operations.
 */
abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public const PAGINATION_SIZE = 20;

    /**
     * @param Model|null $model The model associated with this controller.
     */
    public function __construct(protected ?Model $model = null)
    {
    }

    /**
     * Handles the default index route.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->authResponse($request);
    }

    /**
     * Saves or updates a record in the database.
     *
     * @param Request $request
     * @param array $override Data to override during the save process.
     * @return object
     */
    public final function baseSave(Request $request, array $override = [])
    {
        Guardian::AuthCheck($this->model, $request);

        $dataModel = array_merge($request->all(), ['user_id' => $request->user()?->id], $override);

        if ($request->id && !$this->model->find($request->id)) {
            return self::responseJson(404, 'Registro não encontrado.');
        }

        $this->model->fill($dataModel);

        if (method_exists($this->model, 'rules') && method_exists($this->model, 'messages')){
            $validator = Validator::make($dataModel, $this->model->rules(), $this->model->messages());
            if ($validator->fails()) {
                return response()->json(Notify::warning($validator->errors()->first()), 400);
            }
        }

        return $this->model->save()
            ? self::responseJson(200)
            : self::responseJson(500, 'Falha ao gravar dados...');
    }

    /**
     * Retrieves details of a specific record.
     *
     * @param Request $request
     * @param array|null $with Relationships to load.
     * @return \Illuminate\Http\JsonResponse
     */
    public final function baseDetails(Request $request, ?array $with = null)
    {
        Guardian::AuthCheck($this->model, $request);
        $filter = array_merge(['id' => $request->id], $this->insertUser($request));

        $query = $this->model->query();
        $query->where(function ($query) use ($filter) {
            foreach ($filter as $k => $v) {
                $query->where($k, $v);
            }
        });

        if ($with) {
            $query->with($with);
        }

        return response()->json($query->first()?->toArray());
    }

    /**
     * Retrieves a list of records with optional filters.
     *
     * @param Request $request
     * @param array $search Filters for the query.
     * @param array|null $order Sorting criteria.
     * @param array|null $with Relationships to load.
     * @param array|null $between Range filters.
     * @return \Illuminate\Http\JsonResponse
     */
    public final function baseList(Request $request, array $search = [], ?array $order = null, ?array $with = null, ?array $between = null)
    {
        Guardian::AuthCheck($this->model, $request);
        $filter = array_merge($search, $this->insertUser($request));

        $query = $this->model->query();
        $query->where(function ($query) use ($filter) {
            foreach ($filter as $k => $v) {
                $query->where($k, $v);
            }
        });

        array_filter([
            $between ? fn() => $query->whereBetween(key($between), current($between)) : null,
            $order ? fn() => $query->orderBy(...$order) : null,
            $with ? fn() => $query->with($with) : null,
            $request->page ? fn() => $query->paginate($request->total_page ?? self::PAGINATION_SIZE, page: $request->page) : null,
        ], fn($clause) => $clause && $clause());

        $data = $query->get();

        return response()->json(['data' => $data?->toArray(), 'total' => $data->total()]);
    }

    /**
     * Deletes a record.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public final function baseDelete(Request $request): JsonResponse
    {
        Guardian::AuthCheck($this->model, $request);
        $filter = array_merge(['id' => $request->id], $this->insertUser($request));

        $delete = $this->model->where($filter)->delete();

        return $delete
            ? self::responseJson(200, 'Registro excluído com sucesso...')
            : self::responseJson(500, 'Exclusão não permitida...');
    }

    public function save(Request $request)
    {
        return $this->baseSave($request);
    }

    public function details(Request $request)
    {
        return $this->baseDetails($request);
    }

    public function list(Request $request){
        return $this->baseList($request);
    }

    public function delete(Request $request)
    {
        return $this->baseDelete($request);
    }

    /**
     * Authenticates and handles the response for unauthorized access.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function authResponse(Request $request)
    {
        if (!Guardian::AuthAccess($this->model, $request)) {
            return response()->json(Notify::warning('Acesso não autorizado'), 403);
        }

        return response()->json('access-granted');
    }

    /**
     * Inserts user-related filters for specific models.
     *
     * @param Request $request
     * @return array
     */
    private function insertUser(Request $request): array
    {
        return match ($this->model::class) {
            Task::class => ['user_id' => $request->user()?->id],
            default => [],
        };
    }

    /**
     * Generates a standardized JSON response.
     *
     * @param int $code HTTP status code.
     * @param string|null $msg Optional message.
     */
    public static function responseJson(int $code, ?string $msg = null): JsonResponse
    {
        $resp = (object) match (true) {
            $code == 200 => ['code' => $code, 'notify' => Notify::success($msg ?? 'Dados salvos com sucesso...'), 'status' => true],
            $code >= 400 && $code < 500 => ['code' => $code, 'notify' => Notify::warning($msg), 'status' => false],
            default => ['code' => $code, 'notify' => Notify::error($msg), 'status' => false],
        };

        return response()->json($resp->notify, $code);
    }
}
