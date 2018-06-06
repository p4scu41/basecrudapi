<?php

namespace p4scu41\BaseCRUDApi\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use p4scu41\BaseCRUDApi\Exceptions\ValidationModelException;
use p4scu41\BaseCRUDApi\Http\Controllers\BaseController;
use p4scu41\BaseCRUDApi\Support\ArraySupport;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tylercd100\LERN\Facades\LERN;

/**
 * Controllers Base Api Class
 *
 * @category Controllers
 * @package  p4scu41\BaseCRUDApi\Http\Controllers\Api\V1
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BaseApiController extends BaseController
{
    /**
     * BaseRepository
     *
     * @var p4scu41\BaseCRUDApi\Repositories\BaseRepository
     */
    protected $repository;

    /**
     * @param p4scu41\BaseCRUDApi\Repositories\BaseRepository
     */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            parent::index($request);

            return response()->jsonPaginate($this->repository->paginate());
        } catch (Exception $e) {
            LERN::record($e);
            return response()->jsonException($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            parent::store($request);

            $model = $this->repository->create($request->all());

            return response()->jsonSuccess(['data' => $model, 'status' => SymfonyResponse::HTTP_CREATED]);
        } catch (ValidationModelException $e) {
            return response()->jsonException($e);
        } catch (ValidatorException $e) {
            return response()->jsonException($e, [
                'message' => ArraySupport::errorsToString($e->getMessageBag()->toArray()),
                'status' => SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY,
            ]);
        } catch (Exception $e) {
            LERN::record($e);
            return response()->jsonException($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id Resource primary key
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            parent::show($id);

            $model = $this->repository->findOrFail($id);

            return response()->jsonSuccess(['data' => $model]);
        } catch (ModelNotFoundException $e) {
            return response()->jsonNotFound();
        } catch (Exception $e) {
            LERN::record($e);
            return response()->jsonException($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Request instance
     * @param int                      $id      Resource primary key
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            parent::update($request, $id);

            $model = $this->repository->update($request->all(), $id);

            return response()->jsonSuccess(['data' => $model]);
        } catch (ValidationModelException $e) {
            return response()->jsonException($e);
        } catch (ValidatorException $e) {
            return response()->jsonException($e, [
                'message' => ArraySupport::errorsToString($e->getMessageBag()->toArray()),
                'status' => SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->jsonNotFound();
        } catch (Exception $e) {
            LERN::record($e);
            return response()->jsonException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Resource primary key
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            parent::destroy($id);

            $model = $this->repository->findOrFail($id);

            $this->repository->delete($id);

            return response()->jsonSuccess(['data' => $model]);
        } catch (ModelNotFoundException $e) {
            return response()->jsonNotFound();
        } catch (Exception $e) {
            LERN::record($e);
            return response()->jsonException($e);
        }
    }
}
