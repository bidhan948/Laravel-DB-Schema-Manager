<?php

namespace Bidhan\Bhadhan\Http\Controllers;

use App\Http\Controllers\Controller;
use Bidhan\Bhadhan\Services\BhadhanDBManagerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SchemaController extends Controller
{
    public function index(Request $request): View | JsonResponse
    {
        if (config('bhadhan.mode') != 'dev') {
            dd('Sorry The Environment Is In Production');
        }

        if ($request->has('isAjax') && $request->isAjax) {
            $databaseName = BhadhanDBManagerService::getCurrentDatabaseName();
            $data['connection_name'] = $databaseName;
            $data['tables'] = BhadhanDBManagerService::getAllDbTables();

            if ($request->has('tableName')) {
                $data[$request->tableName] = DB::select(
                    'SELECT * FROM information_schema.columns WHERE table_name = ? ORDER BY ordinal_position',
                    [$request->tableName]
                );
                $data['primary_key'] = BhadhanDBManagerService::getPrimaryKey($request->tableName);
                $data['foreign_keys'] = BhadhanDBManagerService::getForeignKeys($request->tableName);
            }

            return response()->json($data);
        }

        return view('Bhadhan::schema');
    }

    public function performanceMetrics(Request $request): View | JsonResponse
    {
        if ($request->has('isAjax') && $request->isAjax) {
            $data['tableWithSizes'] = BhadhanDBManagerService::getAllTableWithSize();
            $data['totalSchemaSize'] = BhadhanDBManagerService::getCurrentSchemaSize();
            $data['dbViews'] = BhadhanDBManagerService::getAllDBViews();
            return response()->json($data);
        }

        return view('Bhadhan::performance-metrics');
    }

    public function sql(): View | JsonResponse
    {
        return view('Bhadhan::sql-editor');
    }

    public function sqlToData(Request $request): ?JsonResponse
    {
        try {
            $rawSql = $request->rawSql;
            $executionTime = 0;

            DB::listen(function ($query) use (&$executionTime, $rawSql) {
                if ($query->sql == $rawSql) {
                    $executionTime = $query->time;
                }
            });

            $data['fetchFromSql'] = DB::select($rawSql);
            $rowCount = count($data['fetchFromSql']);
            $data['executionTime'] = $executionTime . " ms";
            $data['columnNames'] = $rowCount ? array_keys((array)$data['fetchFromSql'][0]) : [];
            $data['timestamp'] = now()->format('Y-m-d H:i:s');
            $data['summary'] = "✅ {$rowCount} row(s) fetched - {$executionTime} ms, on {$data['timestamp']}";

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
